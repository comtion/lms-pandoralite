<?php

use CodeIgniter\Test\CIUnitTestCase;

final class ApprovalSecurityTest extends CIUnitTestCase
{
    public function testApprovalRoutesArePostOnly(): void
    {
        $routes = file_get_contents(APPPATH . 'Config/Routes.php');

        foreach ([
            'dashboard/approvals/courses/(:num)',
            'dashboard/approvals/surveys/(:num)',
            'dashboard/approvals/course-groups/(:num)',
            'dashboard/approvals/bulk',
        ] as $path) {
            $this->assertStringContainsString("\$routes->post('{$path}'", $routes);
            $this->assertStringNotContainsString("\$routes->get('{$path}'", $routes);
        }
    }

    public function testCsrfAndSecureHeadersAreGlobal(): void
    {
        $filters = file_get_contents(APPPATH . 'Config/Filters.php');

        $this->assertStringContainsString("'csrf' =>", $filters);
        $this->assertStringContainsString("'secureheaders'", $filters);
        $this->assertStringContainsString("'scorm/datamodel/*'", $filters);
    }

    public function testEveryPostFormContainsCsrfField(): void
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(APPPATH . 'Views'));
        $missing = [];

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }
            $contents = file_get_contents($file->getPathname());
            $offset = 0;
            while (preg_match('/<form\b/i', $contents, $match, PREG_OFFSET_CAPTURE, $offset) === 1) {
                $start = $match[0][1];
                $end = $this->formTagEnd($contents, $start);
                $this->assertGreaterThan($start, $end, 'Unclosed form tag in ' . $file->getPathname());
                $tag = substr($contents, $start, $end - $start + 1);
                if (preg_match('/method\s*=\s*["\']post["\']/i', $tag) !== 1) {
                    $offset = $end + 1;
                    continue;
                }
                $after = substr($contents, $end + 1, 100);
                if (preg_match('/^\s*<\?=\s*csrf_field\(\)\s*\?>/', $after) !== 1) {
                    $missing[] = $file->getPathname() . ':' . (substr_count(substr($contents, 0, $start), "\n") + 1);
                }
                $offset = $end + 1;
            }
        }

        $this->assertSame([], $missing, 'POST forms without CSRF: ' . implode(', ', $missing));
    }

    public function testApprovalModelUsesTransactionsAndRowLocks(): void
    {
        $model = file_get_contents(APPPATH . 'Models/ApprovalModel.php');

        $this->assertStringContainsString('transBegin()', $model);
        $this->assertStringContainsString('FOR UPDATE', $model);
        $this->assertStringContainsString('transCommit()', $model);
        $this->assertStringContainsString('transRollback()', $model);
    }

    private function formTagEnd(string $contents, int $start): int
    {
        $length = strlen($contents);
        for ($index = $start; $index < $length; $index++) {
            if ($contents[$index] === '>' && ($index === 0 || $contents[$index - 1] !== '?')) {
                return $index;
            }
        }

        return -1;
    }
}
