<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

class BulkAdminModel extends Model
{
    private const USER_HEADERS = [
        'employee_code', 'username', 'password', 'first_name_en', 'last_name_en',
        'first_name_th', 'last_name_th', 'email', 'company_code', 'department_id',
        'group_id', 'language', 'status',
    ];

    public function userTemplate(): Spreadsheet
    {
        $sheet = (new Spreadsheet())->getActiveSheet();
        $sheet->setTitle('Users');
        $sheet->fromArray(self::USER_HEADERS, null, 'A1');
        $sheet->fromArray([
            'EMP001', 'emp001', 'ChangeMe123!', 'Jane', 'Doe', 'เจน', 'โด',
            'jane@example.com', 'IMAT', '', '4', 'english', '1',
        ], null, 'A2');
        $sheet->freezePane('A2');
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet->getParent();
    }

    public function importUsers(string $path, bool $commit, array $actor): array
    {
        try {
            $sheet = IOFactory::load($path)->getActiveSheet();
        } catch (Throwable $e) {
            return ['ok' => false, 'message' => 'The spreadsheet could not be read.', 'errors' => [$e->getMessage()]];
        }

        $rows = $sheet->toArray('', true, true, false);
        if ($rows === []) {
            return ['ok' => false, 'message' => 'The spreadsheet is empty.', 'errors' => []];
        }

        $headers = array_map(static fn ($value): string => strtolower(trim((string) $value)), array_shift($rows));
        if (array_slice($headers, 0, count(self::USER_HEADERS)) !== self::USER_HEADERS) {
            return ['ok' => false, 'message' => 'The spreadsheet headers do not match the current template.', 'errors' => []];
        }

        $rows = array_values(array_filter($rows, static fn (array $row): bool => trim(implode('', array_map('strval', $row))) !== ''));
        if ($rows === [] || count($rows) > 1000) {
            return ['ok' => false, 'message' => 'Import must contain between 1 and 1,000 users.', 'errors' => []];
        }

        $prepared = [];
        $errors = [];
        $seenEmployees = [];
        $seenUsers = [];
        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $input = array_combine(self::USER_HEADERS, array_pad(array_slice($row, 0, count(self::USER_HEADERS)), count(self::USER_HEADERS), ''));
            $validation = $this->prepareUser($input, $actor, $seenEmployees, $seenUsers);
            if (! $validation['ok']) {
                $errors[] = 'Row ' . $line . ': ' . $validation['message'];
                continue;
            }
            $prepared[] = $validation['input'];
            $seenEmployees[] = strtolower($validation['input']['emp_c']);
            $seenUsers[] = strtolower($validation['input']['useri']);
        }

        if ($errors !== []) {
            return ['ok' => false, 'message' => count($errors) . ' row(s) failed validation.', 'errors' => array_slice($errors, 0, 100), 'valid' => count($prepared)];
        }
        if (! $commit) {
            return ['ok' => true, 'message' => count($prepared) . ' user(s) are valid. No data was changed.', 'errors' => [], 'valid' => count($prepared), 'dry_run' => true];
        }

        $admin = new UserAdminModel();
        $this->db->transBegin();
        foreach ($prepared as $input) {
            $result = $admin->createUser($input, $actor);
            if (! $result['ok']) {
                $this->db->transRollback();
                return ['ok' => false, 'message' => 'Import rolled back: ' . $result['message'], 'errors' => []];
            }
        }
        $this->db->transCommit();

        return ['ok' => $this->db->transStatus(), 'message' => count($prepared) . ' user(s) imported successfully.', 'errors' => [], 'valid' => count($prepared)];
    }

    public function bulkEnrollment(int $courseId, array $employeeCodes, string $action, string $note, array $actor): array
    {
        if (! in_array($action, ['enroll', 'unenroll'], true)) {
            return ['ok' => false, 'message' => 'Invalid enrollment action.', 'errors' => []];
        }
        $employeeCodes = array_values(array_unique(array_filter(array_map('trim', $employeeCodes))));
        if ($courseId < 1 || $employeeCodes === [] || count($employeeCodes) > 1000) {
            return ['ok' => false, 'message' => 'Select a course and provide between 1 and 1,000 employee codes.', 'errors' => []];
        }

        $course = $this->db->table('lms_cos')->where('cos_id', $courseId)->where('cos_isDelete', 0)->get()->getRowArray();
        if (! $course || ! $this->sameCompany((int) $course['com_id'], $actor)) {
            return ['ok' => false, 'message' => 'Course not found or outside your company scope.', 'errors' => []];
        }

        $employees = $this->db->table('lms_emp e')
            ->select('e.emp_id,e.emp_c,e.lang,e.com_id,u.u_id')
            ->join('lms_usp u', 'u.emp_id=e.emp_id')
            ->whereIn('e.emp_c', $employeeCodes)->where('e.emp_isDelete', 0)->where('e.status', 1)
            ->where('u.u_isDelete', 0)->get()->getResultArray();
        $byCode = [];
        foreach ($employees as $employee) {
            $byCode[strtolower((string) $employee['emp_c'])] = $employee;
        }

        $errors = [];
        $targets = [];
        foreach ($employeeCodes as $code) {
            $employee = $byCode[strtolower($code)] ?? null;
            if (! $employee) {
                $errors[] = $code . ': active user not found';
            } elseif ((int) $employee['com_id'] !== (int) $course['com_id']) {
                $errors[] = $code . ': user belongs to another company';
            } else {
                $targets[] = $employee;
            }
        }
        if ($errors !== []) {
            return ['ok' => false, 'message' => 'No data changed; validation failed.', 'errors' => $errors];
        }

        $activeSeats = $this->db->table('lms_cos_enroll')->where('cos_id', $courseId)->where('cosen_isDelete', 0)->where('cosen_status', 1)->countAllResults();
        $activeTargetIds = $action === 'enroll'
            ? array_column($this->db->table('lms_cos_enroll')->select('emp_id')->where('cos_id', $courseId)
                ->whereIn('emp_id', array_column($targets, 'emp_id'))->where('cosen_isDelete', 0)->where('cosen_status', 1)
                ->groupBy('emp_id')->get()->getResultArray(), 'emp_id')
            : [];
        $newSeatCount = count($targets) - count($activeTargetIds);
        if ($action === 'enroll' && (int) ($course['seat_count'] ?? 0) > 0 && $activeSeats + $newSeatCount > (int) $course['seat_count']) {
            return ['ok' => false, 'message' => 'The bulk enrollment would exceed the course seat limit.', 'errors' => []];
        }

        $changed = 0;
        $skipped = 0;
        $now = date('Y-m-d H:i:s');
        $this->db->transBegin();
        foreach ($targets as $employee) {
            $enrollment = $this->db->table('lms_cos_enroll')->where('cos_id', $courseId)->where('emp_id', $employee['emp_id'])
                ->where('cosen_isDelete', 0)->orderBy('cosen_id', 'DESC')->get()->getRowArray();
            if ($action === 'enroll') {
                if ($enrollment && (int) $enrollment['cosen_status'] === 1) {
                    $skipped++;
                    continue;
                }
                $id = $this->createEnrollment($courseId, $employee, $actor, $now);
                $this->db->table('lms_log_enroll')->insert(['cosen_id' => $id]);
                $changed++;
                continue;
            }

            if (! $enrollment || (int) $enrollment['cosen_status'] !== 1) {
                $skipped++;
                continue;
            }
            if ((string) $enrollment['cosen_firsttime'] !== '0000-00-00 00:00:00' || (string) $enrollment['cosen_finishtime'] !== '0000-00-00 00:00:00') {
                $this->db->transRollback();
                return ['ok' => false, 'message' => 'Unenrollment rolled back: ' . $employee['emp_c'] . ' has already started the course.', 'errors' => []];
            }
            $this->db->table('lms_cos_enroll')->where('cosen_id', $enrollment['cosen_id'])->update([
                'cosen_status' => 0, 'cosen_isDelete' => 1, 'cosen_cancelnote' => trim($note),
                'cosen_modifiedby' => (string) ($actor['u_id'] ?? ''), 'cosen_modifieddate' => $now,
            ]);
            $changed++;
        }
        $this->recordLog($actor, 'enrollment', ucfirst($action) . ' ' . $changed . ' learner(s) for course ' . $courseId);
        $this->db->transCommit();

        return ['ok' => $this->db->transStatus(), 'message' => ucfirst($action) . ": {$changed} changed, {$skipped} skipped.", 'errors' => []];
    }

    public function coursesForActor(array $actor): array
    {
        $builder = $this->db->table('lms_cos')->select('cos_id,com_id,ccode,cname_eng,cname_th,seat_count')
            ->where('cos_isDelete', 0)->where('cos_status', 1)->orderBy('cos_id', 'DESC')->limit(500);
        if (! $this->isSuperAdmin($actor)) {
            $builder->where('com_id', (int) ($actor['com_id'] ?? 0));
        }
        return $builder->get()->getResultArray();
    }

    private function prepareUser(array $row, array $actor, array $seenEmployees, array $seenUsers): array
    {
        $employeeCode = trim((string) $row['employee_code']);
        $username = trim((string) $row['username']);
        $password = (string) $row['password'];
        $companyCode = trim((string) $row['company_code']);
        $company = $this->db->table('lms_company')->select('com_id')->where('com_code', $companyCode)
            ->where('com_status', 1)->where('com_isDelete', 0)->get()->getRowArray();
        if ($employeeCode === '' || $username === '' || trim((string) $row['first_name_en']) === '' || trim((string) $row['last_name_en']) === '') {
            return ['ok' => false, 'message' => 'Employee code, username, and English name are required.'];
        }
        if (strlen($password) < 8) {
            return ['ok' => false, 'message' => 'Password must contain at least 8 characters.'];
        }
        if (! $company || ! $this->sameCompany((int) $company['com_id'], $actor)) {
            return ['ok' => false, 'message' => 'Company code is invalid or outside your scope.'];
        }
        $groupId = (int) $row['group_id'];
        if ($this->db->table('lms_usp_gp')->where('ug_id', $groupId)->where('ug_status', 1)->where('ug_isDelete', 0)->countAllResults() === 0) {
            return ['ok' => false, 'message' => 'User group is invalid.'];
        }
        $departmentId = (int) $row['department_id'];
        if ($departmentId > 0 && $this->db->table('lms_depart')->where('dep_id', $departmentId)->where('com_id', $company['com_id'])->where('dep_status', 1)->where('dep_isDelete', 0)->countAllResults() === 0) {
            return ['ok' => false, 'message' => 'Department is invalid for the selected company.'];
        }
        if (in_array(strtolower($employeeCode), $seenEmployees, true) || in_array(strtolower($username), $seenUsers, true)) {
            return ['ok' => false, 'message' => 'Duplicate employee code or username inside the spreadsheet.'];
        }
        if ($this->db->table('lms_emp')->where('emp_c', $employeeCode)->where('emp_isDelete', 0)->countAllResults() > 0
            || $this->db->table('lms_usp')->where('useri', $username)->where('u_isDelete', 0)->countAllResults() > 0) {
            return ['ok' => false, 'message' => 'Employee code or username already exists.'];
        }

        return ['ok' => true, 'input' => [
            'emp_c' => $employeeCode, 'useri' => $username, 'password' => $password,
            'fname_en' => trim((string) $row['first_name_en']), 'lname_en' => trim((string) $row['last_name_en']),
            'fname_th' => trim((string) $row['first_name_th']), 'lname_th' => trim((string) $row['last_name_th']),
            'email' => trim((string) $row['email']), 'com_id' => (int) $company['com_id'],
            'dep_id' => $departmentId, 'ug_id' => $groupId,
            'lang' => in_array((string) $row['language'], ['thai', 'english', 'japan'], true) ? (string) $row['language'] : 'english',
            'status' => (int) $row['status'] === 0 ? 0 : 1,
        ]];
    }

    private function createEnrollment(int $courseId, array $employee, array $actor, string $now): int
    {
        $this->db->table('lms_cos_enroll')->insert([
            'cosen_lang' => (string) ($employee['lang'] ?? 'english'), 'cos_id' => $courseId, 'emp_id' => $employee['emp_id'],
            'cosen_score' => 0, 'cosen_score_per' => 0, 'cosen_grade' => '', 'cosen_reward' => 0, 'cosen_pfm' => 0,
            'cosen_timerequest' => $now, 'emp_approver_a' => 0, 'cosen_enroll_status_a' => 0, 'emp_approver_b' => 0,
            'cosen_enroll_status_b' => 0, 'cosen_status' => 1, 'cosen_status_sub' => 0, 'cosen_cancelnote' => '',
            'cosen_firsttime' => '0000-00-00 00:00:00', 'cosen_finishtime' => '0000-00-00 00:00:00',
            'cosen_rating' => 0, 'cosen_isDelete' => 0, 'cosen_createby' => (string) ($actor['u_id'] ?? ''),
            'cosen_createdate' => $now, 'cosen_modifiedby' => (string) ($actor['u_id'] ?? ''), 'cosen_modifieddate' => $now,
            'cosen_round' => 1,
        ]);
        return (int) $this->db->insertID();
    }

    private function sameCompany(int $companyId, array $actor): bool
    {
        return $this->isSuperAdmin($actor) || $companyId === (int) ($actor['com_id'] ?? 0);
    }

    private function isSuperAdmin(array $actor): bool
    {
        return (string) ($actor['u_id'] ?? '') === '1' || (string) ($actor['ug_id'] ?? '') === '1';
    }

    private function recordLog(array $actor, string $type, string $message): void
    {
        $this->db->table('lms_lg')->insert([
            'log_type' => $type, 'emp_id' => (int) ($actor['emp_id'] ?? 0), 'massage' => $message,
            'ip' => service('request')->getIPAddress(), 'device' => (string) service('request')->getUserAgent(),
            'log_time' => date('Y-m-d H:i:s'),
        ]);
    }
}
