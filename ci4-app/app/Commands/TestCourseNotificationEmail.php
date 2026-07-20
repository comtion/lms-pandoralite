<?php

namespace App\Commands;

use App\Models\CourseNotificationModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestCourseNotificationEmail extends BaseCommand
{
    protected $group = 'LMS';
    protected $name = 'course-notifications:test-email';
    protected $description = 'Sends a test email using LMS notification mail settings.';

    public function run(array $params)
    {
        $recipient = trim((string) ($params[0] ?? ''));
        if ($recipient === '') {
            CLI::error('Usage: php spark course-notifications:test-email recipient@example.com');
            return EXIT_ERROR;
        }

        $result = (new CourseNotificationModel())->sendTestEmail($recipient);
        if ($result['ok']) {
            CLI::write($result['message'], 'green');
            return EXIT_SUCCESS;
        }

        CLI::error($result['message']);
        return EXIT_ERROR;
    }
}
