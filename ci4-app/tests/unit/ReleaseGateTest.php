<?php

use PHPUnit\Framework\TestCase;

final class ReleaseGateTest extends TestCase
{
    public function testReleaseGateCoversProductionControls(): void
    {
        $source = file_get_contents(APPPATH . 'Commands/ReleaseGate.php');
        foreach (['tableExists', 'Database round trip', 'Upload execution guard', "ENVIRONMENT === 'production'", 'https://', 'secure', 'mfaRequiredForAdmins', 'oidcDiscoveryUrl'] as $needle) {
            $this->assertStringContainsString($needle, $source);
        }
    }
}
