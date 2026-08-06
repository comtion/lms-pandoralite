<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AuditVerify extends BaseCommand
{
    protected $group = 'Security';
    protected $name = 'audit:verify';
    protected $description = 'Verifies the tamper-evident lms_audit_logs SHA-256 hash chain.';

    public function run(array $params)
    {
        $rows = db_connect()->table('lms_audit_logs')->orderBy('audit_id', 'ASC')->get()->getResultArray();
        $previous = null;
        foreach ($rows as $row) {
            $id = $row['audit_id'];
            $storedPrevious = $row['audit_prev_hash'];
            $storedHash = $row['audit_hash'];
            unset($row['audit_id'], $row['audit_prev_hash'], $row['audit_hash']);
            ksort($row);
            $expected = hash('sha256', (string) $previous . '|' . json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            if ((string) $storedPrevious !== (string) $previous || ! hash_equals((string) $storedHash, $expected)) {
                CLI::error("Audit hash chain mismatch at audit_id {$id}.");
                return EXIT_ERROR;
            }
            $previous = $storedHash;
        }
        CLI::write('Audit hash chain passed. Rows checked: ' . count($rows), 'green');
        return EXIT_SUCCESS;
    }
}
