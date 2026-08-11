<?php

namespace App\Controllers;

use App\Models\PermissionModel;
use App\Models\ReportModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportPortal extends BaseController
{
    public function learnerReport()
    {
        $context = $this->context('report/learnerReport');
        if (! is_array($context)) {
            return $context;
        }

        $model = new ReportModel();
        $filters = $this->filters();

        return view('reports/learner', [
            'path' => 'report/learnerReport',
            'title' => $context['permissions']->menuTitle('report/learnerReport', $context['lang']) ?: 'Learning History Report',
            'title_main' => $context['permissions']->parentMenuTitle('report/learnerReport', $context['lang']),
            'menus' => $context['permissions']->menuTree($context['user'], $context['lang']),
            'user' => $context['user'],
            'name' => $this->session->get('name'),
            'rows' => $model->learnerRows($context['user'], $context['lang'], $filters),
            'companies' => $model->companies($context['user']),
            'courses' => $model->courses(),
            'filters' => $filters,
        ]);
    }

    public function learnerExport()
    {
        $context = $this->context('report/learnerReport', 'ru_print');
        if (! is_array($context)) {
            return $context;
        }

        $rows = (new ReportModel())->learnerRows($context['user'], $context['lang'], $this->filters(), 5000);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = ['Company', 'Employee ID', 'Learner', 'Course Code', 'Course', 'Hours', 'Status', 'Score %', 'Grade', 'Started', 'Finished'];
        $sheet->fromArray($headers, null, 'A1');
        $line = 2;
        foreach ($rows as $row) {
            $sheet->fromArray(\App\Libraries\ExportSanitizer::row([
                $row['company_name'],
                $row['emp_c'],
                $row['learner_name'],
                $row['ccode'],
                $row['course_title'],
                $row['cos_hour'],
                $row['status_label'],
                $row['cosen_score_per'],
                $row['cosen_grade'],
                $this->cleanDate((string) $row['cosen_firsttime']),
                $this->cleanDate((string) $row['cosen_finishtime']),
            ]), null, 'A' . $line);
            $line++;
        }
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $file = WRITEPATH . 'cache/learner_report_' . date('YmdHis') . '.xlsx';
        (new Xlsx($spreadsheet))->save($file);

        return $this->response->download($file, null)->setFileName('learner_report_' . date('YmdHis') . '.xlsx');
    }

    public function courseSummaryExport()
    {
        $context = $this->context('report/learnerReport', 'ru_print');
        if (! is_array($context)) {
            return $context;
        }

        $rows = (new ReportModel())->courseSummaryRows($context['user'], $context['lang'], $this->filters(), 5000);
        $headers = ['Company', 'Course Code', 'Course', 'Hours', 'Enrolled', 'Completed', 'In Progress', 'Not Started', 'Completion %', 'Average Score %'];
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                $row['company_name'],
                $row['ccode'],
                $row['course_title'],
                $row['cos_hour'],
                $row['enrolled_count'],
                $row['completed_count'],
                $row['in_progress_count'],
                $row['not_started_count'],
                $row['completion_rate'],
                $row['avg_score'],
            ];
        }

        return $this->xlsxDownload('course_summary', $headers, $data);
    }

    public function scormExport()
    {
        $context = $this->context('report/learnerReport', 'ru_print');
        if (! is_array($context)) {
            return $context;
        }

        $rows = (new ReportModel())->scormRows($context['user'], $context['lang'], $this->filters(), 10000);
        $headers = ['Company', 'Employee ID', 'Learner', 'Course Code', 'Course', 'Lesson', 'SCORM ID', 'Variable', 'Value'];
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                $row['company_name'],
                $row['emp_c'],
                $row['learner_name'],
                $row['ccode'],
                $row['course_title'],
                $row['lesson_title'],
                $row['scm_id'],
                $row['var_name'],
                $row['var_value'],
            ];
        }

        return $this->xlsxDownload('scorm_tracking', $headers, $data);
    }

    public function scormReport()
    {
        $context = $this->context('report/learnerReport');
        if (! is_array($context)) {
            return $context;
        }
        $filters = $this->filters();
        return view('reports/scorm', [
            'title' => 'Advanced SCORM Tracking',
            'title_main' => $context['permissions']->parentMenuTitle('report/learnerReport', $context['lang']),
            'name' => $this->session->get('name'),
            'rows' => (new ReportModel())->scormSummaryRows($context['user'], $context['lang'], $filters),
            'companies' => (new ReportModel())->companies($context['user']),
            'courses' => (new ReportModel())->courses(),
            'filters' => $filters,
        ]);
    }

    public function certificateExport()
    {
        $context = $this->context('report/learnerReport', 'ru_print');
        if (! is_array($context)) {
            return $context;
        }

        $rows = (new ReportModel())->certificateRows($context['user'], $context['lang'], $this->filters(), 10000);
        $headers = ['Company', 'Employee ID', 'Learner', 'Course Code', 'Course', 'Certificate ID', 'Certificate File', 'Certificate Date', 'Created At'];
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                $row['company_name'],
                $row['emp_c'],
                $row['learner_name'],
                $row['ccode'],
                $row['course_title'],
                $row['cert_id'],
                $row['cert_file'],
                $this->cleanDate((string) $row['cert_date']),
                $this->cleanDate((string) $row['cert_createtime']),
            ];
        }

        return $this->xlsxDownload('certificate_issued', $headers, $data);
    }

    private function context(string $path, string $field = 'ru_view')
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, $path, $field)) {
            return redirect()->to(site_url('dashboard'));
        }

        return ['user' => $user, 'lang' => $lang, 'permissions' => $permissions];
    }

    private function filters(): array
    {
        return [
            'com_id' => (string) $this->request->getGet('com_id'),
            'cos_id' => (string) $this->request->getGet('cos_id'),
            'status' => (string) $this->request->getGet('status'),
            'date_start' => (string) $this->request->getGet('date_start'),
            'date_end' => (string) $this->request->getGet('date_end'),
        ];
    }

    private function cleanDate(string $date): string
    {
        return $date === '' || str_starts_with($date, '0000-00-00') ? '' : $date;
    }

    private function xlsxDownload(string $name, array $headers, array $rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($headers, null, 'A1');
        $line = 2;
        foreach ($rows as $row) {
            $sheet->fromArray(\App\Libraries\ExportSanitizer::row($row), null, 'A' . $line);
            $line++;
        }

        $lastColumn = $this->columnName(count($headers));
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $sheet->freezePane('A2');

        $file = WRITEPATH . 'cache/' . $name . '_' . date('YmdHis') . '.xlsx';
        (new Xlsx($spreadsheet))->save($file);

        return $this->response->download($file, null)->setFileName($name . '_' . date('YmdHis') . '.xlsx');
    }

    private function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)) . $name;
            $number = intdiv($number, 26);
        }

        return $name ?: 'A';
    }
}
