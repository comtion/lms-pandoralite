<?php

use App\Libraries\TotpService;
use CodeIgniter\Test\CIUnitTestCase;

final class P0SecurityTest extends CIUnitTestCase
{
    public function testTotpMatchesRfc6238Sha1Vector(): void
    {
        $totp = new TotpService();
        $secret = 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ';
        $this->assertSame('287082', $totp->code($secret, intdiv(59, 30)));
        $this->assertNotNull($totp->verify($secret, '287082', 59, 0));
    }

    public function testTotpRejectsMalformedCodes(): void
    {
        $this->assertNull((new TotpService())->verify('GEZDGNBVGY3TQOJQ', '12345x'));
    }

    public function testSensitiveMutationsArePostOnly(): void
    {
        $routes = file_get_contents(APPPATH . 'Config/Routes.php');
        foreach (['login/mfa', 'security/mfa/enable', 'security/mfa/disable', 'operations/jobs/(:num)/retry'] as $path) {
            $this->assertStringContainsString("\$routes->post('{$path}'", $routes);
        }
    }

    public function testOidcUsesStateAndPkce(): void
    {
        $controller = file_get_contents(APPPATH . 'Controllers/Oidc.php');
        $this->assertStringContainsString('code_challenge_method', $controller);
        $this->assertStringContainsString("hash_equals", $controller);
        $this->assertStringContainsString('code_verifier', $controller);
    }
}
