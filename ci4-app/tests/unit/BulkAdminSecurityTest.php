<?php

use CodeIgniter\Test\CIUnitTestCase;

final class BulkAdminSecurityTest extends CIUnitTestCase
{
    public function testBulkMutationRoutesArePostAndProtected(): void
    {
        $routes = file_get_contents(APPPATH . 'Config/Routes.php');

        foreach (['manage/userdata/bulk/import', 'manage/userdata/bulk/enrollment'] as $path) {
            $this->assertStringContainsString("\$routes->post('{$path}'", $routes);
            $this->assertStringContainsString("['filter' => 'auth']", $routes);
            $this->assertStringNotContainsString("\$routes->get('{$path}'", $routes);
        }
    }

    public function testBulkImportHasLimitsScopeAndRollback(): void
    {
        $model = file_get_contents(APPPATH . 'Models/BulkAdminModel.php');

        $this->assertStringContainsString('count($rows) > 1000', $model);
        $this->assertStringContainsString('outside your scope', $model);
        $this->assertStringContainsString('transRollback()', $model);
        $this->assertStringContainsString('Duplicate employee code or username', $model);
    }

    public function testSafeUnenrollProtectsStartedLearning(): void
    {
        $model = file_get_contents(APPPATH . 'Models/BulkAdminModel.php');

        $this->assertStringContainsString("cosen_firsttime", $model);
        $this->assertStringContainsString("has already started the course", $model);
        $this->assertStringContainsString("'cosen_isDelete' => 1", $model);
        $this->assertStringContainsString('seat limit', $model);
    }

    public function testTemplateDocumentsRequiredColumns(): void
    {
        $model = file_get_contents(APPPATH . 'Models/BulkAdminModel.php');

        foreach (['employee_code', 'username', 'password', 'company_code', 'department_id', 'group_id'] as $header) {
            $this->assertStringContainsString("'{$header}'", $model);
        }
    }
}
