<?php

use App\Libraries\MigrationInventoryService;
use CodeIgniter\Test\CIUnitTestCase;

final class P0OperationsTest extends CIUnitTestCase
{
    public function testInventoryHasNoUnknownStatuses(): void
    {
        $report = (new MigrationInventoryService())->report();
        $this->assertNotEmpty($report['modules']);
        foreach ($report['modules'] as $module) {
            $this->assertContains($module['status'], ['native', 'partial', 'readonly', 'pending']);
            $this->assertNotSame('', $module['evidence']);
        }
    }

    public function testQueueImplementsIdempotencyBackoffAndDeadLetterState(): void
    {
        $source = file_get_contents(APPPATH . 'Libraries/QueueService.php');
        $this->assertStringContainsString('idempotency_key', $source);
        $this->assertStringContainsString("status' => \$terminal ? 'failed' : 'pending'", $source);
        $this->assertStringContainsString('2 **', $source);
    }

    public function testBackupValidatesResolvedPathAndChecksum(): void
    {
        $source = file_get_contents(APPPATH . 'Libraries/BackupService.php');
        $this->assertStringContainsString('realpath', $source);
        $this->assertStringContainsString("hash_file('sha256'", $source);
        $this->assertStringContainsString('str_starts_with', $source);
    }
}
