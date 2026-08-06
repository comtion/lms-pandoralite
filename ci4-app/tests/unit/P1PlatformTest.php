<?php

use PHPUnit\Framework\TestCase;

final class P1PlatformTest extends TestCase
{
    public function testP1RoutesAreExplicitAndMutationsArePostOnly(): void
    {
        $routes = file_get_contents(APPPATH . 'Config/Routes.php');
        foreach ([
            "\$routes->get('health/ready'", "\$routes->get('notifications'",
            "\$routes->post('notifications/preferences'", "\$routes->post('managecourse/courses_all/(:num)/lifecycle'",
            "\$routes->post('enrollment/requests/(:num)/decision'", "\$routes->post('enrollment/courses/(:num)/policy'",
            "\$routes->get('workflows'", "\$routes->post('workflows/requests/(:num)/decision'",
        ] as $needle) $this->assertStringContainsString($needle, $routes);
    }

    public function testP1WorkflowSecurityUsesScopeTransactionsAndLocks(): void
    {
        $lifecycle = file_get_contents(APPPATH . 'Models/CourseLifecycleModel.php');
        $enrollment = file_get_contents(APPPATH . 'Models/EnrollmentWorkflowModel.php');
        foreach ([$lifecycle, $enrollment] as $source) {
            $this->assertStringContainsString('transBegin()', $source);
            $this->assertStringContainsString('FOR UPDATE', $source);
            $this->assertStringContainsString("['com_id']", $source);
            $this->assertStringContainsString('transRollback()', $source);
        }
    }

    public function testP1IncludesHealthAuditPerformanceAndAccessibility(): void
    {
        $this->assertFileExists(APPPATH . 'Controllers/Health.php');
        $this->assertFileExists(APPPATH . 'Commands/AuditVerify.php');
        $this->assertFileExists(APPPATH . 'Commands/PerformanceSmoke.php');
        $this->assertStringContainsString('role="alert"', file_get_contents(APPPATH . 'Views/auth/login.php'));
        $this->assertStringContainsString('Skip to content', file_get_contents(APPPATH . 'Views/notifications/index.php'));
        $this->assertStringContainsString('role="alert"', file_get_contents(APPPATH . 'Views/workflows/index.php'));
    }
}
