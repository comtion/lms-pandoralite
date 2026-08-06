<?php
use App\Libraries\ParityService;
use CodeIgniter\Test\CIUnitTestCase;
final class MigrationParityTest extends CIUnitTestCase
{
    public function testAllProductionMenuPathsHaveExplicitRoutes(): void
    {
        $report=(new ParityService())->report();
        $this->assertSame(47,$report['total']);
        $this->assertSame([], $report['missing']);
        $this->assertSame(100.0,$report['coverage_percent']);
    }
}
