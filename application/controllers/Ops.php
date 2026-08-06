<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ops extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!is_cli()) {
			show_404();
		}
		$this->load->database();
	}

	public function backup($type = 'all')
	{
		$this->runJob('backup:'.$type, function () use ($type) {
			if (!in_array($type, array('database', 'uploads', 'all'), TRUE)) {
				throw new InvalidArgumentException('Type must be database, uploads or all');
			}
			$directory = ROOT_DIR.'storage'.DIRECTORY_SEPARATOR.'backups';
			if (!is_dir($directory) && !mkdir($directory, 0750, TRUE)) {
				throw new RuntimeException('Cannot create backup directory');
			}
			$results = array();
			if ($type === 'database' || $type === 'all') $results[] = $this->backupDatabase($directory);
			if ($type === 'uploads' || $type === 'all') $results[] = $this->backupUploads($directory);
			return $results;
		});
	}

	public function cleanup($days = 90)
	{
		$this->runJob('cleanup', function () use ($days) {
			$days = min(max((int) $days, 7), 3650);
			$cutoff = date('Y-m-d H:i:s', time() - ($days * 86400));
			$metrics = array();
			foreach (array(
				'lms_rate_limits' => 'expires_at',
				'lms_notification_outbox' => 'created_at',
			) as $table => $column) {
				$this->db->where($column.' <', $cutoff)->delete($table);
				$metrics[$table] = $this->db->affected_rows();
			}
			return $metrics;
		});
	}

	public function dispatch_notifications($limit = 100)
	{
		$this->runJob('notifications:dispatch', function () use ($limit) {
			$limit = min(max((int) $limit, 1), 500);
			$rows = $this->db->where('status', 'pending')->where('available_at <=', date('Y-m-d H:i:s'))
				->order_by('outbox_id', 'ASC')->limit($limit)->get('lms_notification_outbox')->result_array();
			$sent = 0;
			$failed = 0;
			foreach ($rows as $row) {
				$this->db->where('outbox_id', $row['outbox_id'])->where('status', 'pending')
					->update('lms_notification_outbox', array('status' => 'processing', 'locked_at' => date('Y-m-d H:i:s')));
				try {
					if ($row['channel'] !== 'email') throw new RuntimeException('Unsupported channel');
					$this->load->library('email');
					$this->email->clear(TRUE)->from(getenv('LMS_MAIL_FROM') ?: 'noreply@localhost')
						->to($row['recipient'])->subject($row['subject'])->message($row['body']);
					if (!$this->email->send()) throw new RuntimeException('Email transport failed');
					$this->db->where('outbox_id', $row['outbox_id'])->update('lms_notification_outbox', array(
						'status' => 'sent', 'sent_at' => date('Y-m-d H:i:s'), 'attempts' => (int) $row['attempts'] + 1,
					));
					$sent++;
				} catch (Throwable $exception) {
					$this->db->where('outbox_id', $row['outbox_id'])->update('lms_notification_outbox', array(
						'status' => (int) $row['attempts'] >= 4 ? 'failed' : 'pending',
						'attempts' => (int) $row['attempts'] + 1,
						'available_at' => date('Y-m-d H:i:s', time() + 300),
						'last_error' => substr($exception->getMessage(), 0, 2000),
					));
					$failed++;
				}
			}
			return array('processed' => count($rows), 'sent' => $sent, 'failed' => $failed);
		});
	}

	public function generate_reminders($daysAhead = 7)
	{
		$this->runJob('notifications:reminders', function () use ($daysAhead) {
			$daysAhead = min(max((int) $daysAhead, 1), 90);
			$today = date('Y-m-d 00:00:00');
			$until = date('Y-m-d 23:59:59', time() + ($daysAhead * 86400));
			$requests = $this->db->where('status', 'approved')
				->where('expires_at >=', $today)->where('expires_at <=', $until)
				->get('lms_enrollment_requests')->result_array();
			$created = 0;
			foreach ($requests as $request) {
				$refId = 'enrollment:'.$request['request_id'].':'.date('Ymd', strtotime($request['expires_at']));
				$exists = $this->db->where(array(
					'com_id' => $request['com_id'], 'emp_id' => $request['emp_id'],
					'type' => 'deadline', 'ref_type' => 'reminder', 'ref_id' => $refId,
				))->count_all_results('lms_notifications');
				if ($exists) continue;
				$this->db->insert('lms_notifications', array(
					'com_id' => $request['com_id'], 'emp_id' => $request['emp_id'],
					'type' => 'deadline', 'ref_type' => 'reminder', 'ref_id' => $refId,
					'title' => 'Course deadline approaching',
					'message' => 'Complete this course before '.date('d/m/Y', strtotime($request['expires_at'])),
					'url' => 'course/detail/'.$request['cos_id'], 'priority' => 'high',
					'is_read' => 0, 'created_at' => date('Y-m-d H:i:s'),
				));
				$created++;
			}
			return array('matched' => count($requests), 'created' => $created);
		});
	}

	private function backupDatabase($directory)
	{
		$this->load->dbutil();
		$data = $this->dbutil->backup(array('format' => 'gzip', 'filename' => 'database.sql'));
		$restorePrelude = implode("\n", array(
			'SET @LMS_OLD_SQL_MODE=@@SESSION.SQL_MODE;',
			'SET @LMS_OLD_FOREIGN_KEY_CHECKS=@@SESSION.FOREIGN_KEY_CHECKS;',
			'SET @LMS_OLD_UNIQUE_CHECKS=@@SESSION.UNIQUE_CHECKS;',
			'SET @LMS_OLD_AUTOCOMMIT=@@SESSION.AUTOCOMMIT;',
			"SET SESSION SQL_MODE='NO_ENGINE_SUBSTITUTION';",
			'SET SESSION FOREIGN_KEY_CHECKS=0;',
			'SET SESSION UNIQUE_CHECKS=0;',
			'SET SESSION AUTOCOMMIT=0;',
			'',
		));
		$restoreEpilogue = implode("\n", array(
			'',
			'COMMIT;',
			'SET SESSION AUTOCOMMIT=@LMS_OLD_AUTOCOMMIT;',
			'SET SESSION UNIQUE_CHECKS=@LMS_OLD_UNIQUE_CHECKS;',
			'SET SESSION FOREIGN_KEY_CHECKS=@LMS_OLD_FOREIGN_KEY_CHECKS;',
			'SET SESSION SQL_MODE=@LMS_OLD_SQL_MODE;',
			'',
		));
		$data = gzencode($restorePrelude, 6).$data.gzencode($restoreEpilogue, 6);
		$file = $directory.DIRECTORY_SEPARATOR.'database-'.date('Ymd-His').'.sql.gz';
		if (file_put_contents($file, $data, LOCK_EX) === FALSE) throw new RuntimeException('Database backup failed');
		return $this->recordBackup('database', $file);
	}

	private function backupUploads($directory)
	{
		if (!class_exists('ZipArchive')) throw new RuntimeException('ZipArchive extension is required');
		$file = $directory.DIRECTORY_SEPARATOR.'uploads-'.date('Ymd-His').'.zip';
		$zip = new ZipArchive();
		if ($zip->open($file, ZipArchive::CREATE | ZipArchive::EXCL) !== TRUE) {
			throw new RuntimeException('Cannot create uploads archive');
		}
		$root = realpath(ROOT_DIR.'uploads');
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
		foreach ($iterator as $entry) {
			if ($entry->isFile()) $zip->addFile($entry->getRealPath(), substr($entry->getRealPath(), strlen($root) + 1));
		}
		$zip->close();
		return $this->recordBackup('uploads', $file);
	}

	private function recordBackup($type, $file)
	{
		$record = array(
			'backup_type' => $type, 'status' => 'completed', 'file_path' => $file,
			'file_size' => filesize($file), 'checksum_sha256' => hash_file('sha256', $file),
			'started_at' => date('Y-m-d H:i:s'), 'finished_at' => date('Y-m-d H:i:s'),
		);
		$this->db->insert('lms_backup_runs', $record);
		return $record;
	}

	private function runJob($name, callable $callback)
	{
		$started = microtime(TRUE);
		$this->db->insert('lms_job_runs', array('job_name' => $name, 'status' => 'running', 'started_at' => date('Y-m-d H:i:s')));
		$id = $this->db->insert_id();
		try {
			$metrics = $callback();
			$this->db->where('job_run_id', $id)->update('lms_job_runs', array(
				'status' => 'completed', 'finished_at' => date('Y-m-d H:i:s'),
				'duration_ms' => (int) ((microtime(TRUE) - $started) * 1000),
				'metrics_json' => json_encode($metrics, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
			));
			echo json_encode(array('ok' => TRUE, 'job' => $name, 'metrics' => $metrics), JSON_PRETTY_PRINT).PHP_EOL;
		} catch (Throwable $exception) {
			$this->db->where('job_run_id', $id)->update('lms_job_runs', array(
				'status' => 'failed', 'finished_at' => date('Y-m-d H:i:s'),
				'duration_ms' => (int) ((microtime(TRUE) - $started) * 1000),
				'message' => substr($exception->getMessage(), 0, 4000),
			));
			fwrite(STDERR, $exception->getMessage().PHP_EOL);
			exit(1);
		}
	}
}
