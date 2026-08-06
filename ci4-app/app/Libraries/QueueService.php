<?php

namespace App\Libraries;

class QueueService
{
    public function dispatch(string $type, array $payload = [], ?string $idempotencyKey = null, string $queue = 'default'): int
    {
        $now = date('Y-m-d H:i:s');
        $db = db_connect();
        if ($idempotencyKey !== null) {
            $existing = $db->table('lms_jobs')->select('id')->where('idempotency_key', $idempotencyKey)->get()->getRowArray();
            if ($existing) {
                return (int) $existing['id'];
            }
        }
        $db->table('lms_jobs')->insert([
            'queue' => $queue, 'type' => $type, 'payload' => json_encode($payload, JSON_THROW_ON_ERROR),
            'idempotency_key' => $idempotencyKey, 'status' => 'pending', 'attempts' => 0, 'max_attempts' => 5,
            'available_at' => $now, 'created_at' => $now, 'updated_at' => $now,
        ]);
        return (int) $db->insertID();
    }

    public function reserve(string $queue = 'default'): ?array
    {
        $db = db_connect();
        $db->transStart();
        $job = $db->query(
            "SELECT * FROM lms_jobs WHERE queue = ? AND status = 'pending' AND available_at <= NOW() ORDER BY id LIMIT 1 FOR UPDATE",
            [$queue]
        )->getRowArray();
        if ($job) {
            $db->table('lms_jobs')->where('id', $job['id'])->update([
                'status' => 'running', 'reserved_at' => date('Y-m-d H:i:s'),
                'attempts' => (int) $job['attempts'] + 1, 'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $db->transComplete();
        return $db->transStatus() && $job ? $job : null;
    }

    public function complete(int $id): void
    {
        db_connect()->table('lms_jobs')->where('id', $id)->update([
            'status' => 'completed', 'completed_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function fail(array $job, \Throwable $error): void
    {
        $attempts = (int) $job['attempts'] + 1;
        $terminal = $attempts >= (int) $job['max_attempts'];
        $delay = config('P0')->queueRetryBaseSeconds * (2 ** max(0, $attempts - 1));
        db_connect()->table('lms_jobs')->where('id', $job['id'])->update([
            'status' => $terminal ? 'failed' : 'pending',
            'last_error' => mb_substr($error->getMessage(), 0, 4000),
            'available_at' => date('Y-m-d H:i:s', time() + $delay),
            'reserved_at' => null, 'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function retry(int $id): bool
    {
        return db_connect()->table('lms_jobs')->where('id', $id)->where('status', 'failed')->update([
            'status' => 'pending', 'attempts' => 0, 'available_at' => date('Y-m-d H:i:s'),
            'reserved_at' => null, 'completed_at' => null, 'last_error' => null, 'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
