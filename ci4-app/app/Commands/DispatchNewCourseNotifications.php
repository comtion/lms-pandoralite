<?php

namespace App\Commands;

use App\Models\CourseNotificationModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DispatchNewCourseNotifications extends BaseCommand
{
    protected $group = 'LMS';
    protected $name = 'course-notifications:dispatch';
    protected $description = 'Dispatches scheduled new-course notifications to learners.';

    public function run(array $params)
    {
        $limit = max(1, (int) ($params[0] ?? 25));
        $recipientLimit = max(1, (int) ($params[1] ?? 250));
        $summary = (new CourseNotificationModel())->dispatchDue($limit, $recipientLimit);

        CLI::write('Schedules: ' . $summary['schedules'], 'green');
        CLI::write('Recipient batch limit: ' . $recipientLimit);
        CLI::write('System notifications: ' . $summary['system']);
        CLI::write('Emails: ' . $summary['email']);
        CLI::write('Failed: ' . $summary['failed']);

        return $summary['failed'] > 0 ? EXIT_ERROR : EXIT_SUCCESS;
    }
}
