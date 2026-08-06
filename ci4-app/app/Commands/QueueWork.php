<?php

namespace App\Commands;

use App\Libraries\QueueService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class QueueWork extends BaseCommand
{
    protected $group = 'P0 Operations';
    protected $name = 'queue:work';
    protected $description = 'Process persistent LMS background jobs.';
    protected $usage = 'queue:work [queue] [limit]';

    public function run(array $params)
    {
        $queue = $params[0] ?? 'default';
        $limit = max(1, min(1000, (int) ($params[1] ?? 50)));
        $service = new QueueService();
        for ($processed = 0; $processed < $limit; $processed++) {
            $job = $service->reserve($queue);
            if (! $job) {
                break;
            }
            try {
                $this->handle($job['type'], json_decode($job['payload'], true, 512, JSON_THROW_ON_ERROR));
                $service->complete((int) $job['id']);
            } catch (\Throwable $e) {
                $service->fail($job, $e);
                log_message('error', 'Queue job {id} failed: {error}', ['id' => $job['id'], 'error' => $e->getMessage()]);
            }
        }
        CLI::write("Processed {$processed} job(s).", 'green');
    }

    private function handle(string $type, array $payload): void
    {
        if ($type === 'backup.verify') {
            (new \App\Libraries\BackupService())->verify((string) ($payload['path'] ?? ''));
            return;
        }
        if ($type === 'course-notifications.dispatch') {
            command('course-notifications:dispatch');
            return;
        }
        throw new \RuntimeException("No handler registered for job type {$type}.");
    }
}
