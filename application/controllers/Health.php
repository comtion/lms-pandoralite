<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Health extends CI_Controller
{
	public function ready()
	{
		$this->output->set_content_type('application/json', 'utf-8');
		$checks = array('php' => version_compare(PHP_VERSION, '8.4.0', '>='));
		try {
			$this->load->database();
			$checks['database'] = (bool) $this->db->query('SELECT 1')->row_array();
		} catch (Throwable $exception) {
			$checks['database'] = FALSE;
		}
		$checks['uploads_writable'] = is_dir(ROOT_DIR.'uploads') && is_writable(ROOT_DIR.'uploads');
		$ok = !in_array(FALSE, $checks, TRUE);
		$this->output->set_status_header($ok ? 200 : 503)->set_output(json_encode(array(
			'ok' => $ok,
			'checks' => $checks,
			'timestamp' => date(DATE_ATOM),
		)));
	}

	public function details()
	{
		$this->load->library('p0_guard');
		$this->p0_guard->admin();
		$this->load->database();
		$uploadRoot = ROOT_DIR.'uploads';
		$jobs = $this->db->order_by('started_at', 'DESC')->limit(20)->get('lms_job_runs')->result_array();
		$backups = $this->db->order_by('started_at', 'DESC')->limit(10)->get('lms_backup_runs')->result_array();
		$this->p0_guard->json(array(
			'ok' => TRUE,
			'php_version' => PHP_VERSION,
			'disk_free_bytes' => @disk_free_space(ROOT_DIR),
			'disk_total_bytes' => @disk_total_space(ROOT_DIR),
			'uploads_bytes' => $this->directorySize($uploadRoot),
			'latest_jobs' => $jobs,
			'latest_backups' => $backups,
		));
	}

	private function directorySize($path)
	{
		if (!is_dir($path)) return 0;
		$size = 0;
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
		);
		foreach ($iterator as $file) {
			if ($file->isFile()) $size += $file->getSize();
		}
		return $size;
	}
}
