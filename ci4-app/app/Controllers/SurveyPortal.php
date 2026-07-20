<?php

namespace App\Controllers;

use App\Models\PermissionModel;
use App\Models\SurveyModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SurveyPortal extends BaseController
{
    public function manage()
    {
        $context = $this->adminContext();
        if (! is_array($context)) {
            return $context;
        }

        $model = new SurveyModel();
        $filters = $this->filters();

        return view('survey/manage', [
            'path' => 'managecourse/courses_all',
            'title' => 'Course Surveys',
            'title_main' => $context['permissions']->parentMenuTitle('managecourse/courses_all', $context['lang']) ?: 'Manage Course',
            'menus' => $context['permissions']->menuTree($context['user'], $context['lang']),
            'user' => $context['user'],
            'name' => $this->session->get('name'),
            'surveys' => $model->adminSurveys($filters, $context['lang']),
            'courses' => $model->activeCourses($context['lang']),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return $this->form(null);
    }

    public function store()
    {
        return $this->write(null);
    }

    public function edit($surveyId)
    {
        return $this->form((int) $surveyId);
    }

    public function update($surveyId)
    {
        return $this->write((int) $surveyId);
    }

    public function status($surveyId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new SurveyModel())->setSurveyStatus((int) $surveyId, (int) $this->request->getPost('status'), $context['user']);

        return redirect()
            ->to(site_url('managecourse/surveys'))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function storeQuestion($surveyId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new SurveyModel())->createQuestion((int) $surveyId, $this->request->getPost(), $context['user']);

        return redirect()
            ->to(site_url('managecourse/surveys/' . (int) $surveyId . '/edit'))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function updateQuestion($questionId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new SurveyModel())->updateQuestion((int) $questionId, $this->request->getPost(), $context['user']);
        $surveyId = (int) ($result['survey_id'] ?? 0);

        return redirect()
            ->to(site_url('managecourse/surveys/' . $surveyId . '/edit'))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function questionStatus($questionId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $status = (string) $this->request->getPost('archive') === '1'
            ? null
            : (int) $this->request->getPost('status');
        $model = new SurveyModel();
        $result = $status === null
            ? $model->deleteQuestion((int) $questionId, $context['user'])
            : $model->setQuestionStatus((int) $questionId, $status, $context['user']);
        $surveyId = (int) ($result['survey_id'] ?? 0);

        return redirect()
            ->to(site_url('managecourse/surveys/' . $surveyId . '/edit'))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function report($surveyId)
    {
        $context = $this->adminContext('ru_view');
        if (! is_array($context)) {
            return $context;
        }

        $report = (new SurveyModel())->report((int) $surveyId, $context['lang']);
        if (! $report) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Survey report ' . $surveyId);
        }

        return view('survey/report', [
            'path' => 'managecourse/courses_all',
            'title' => 'Survey Report',
            'title_main' => $context['permissions']->parentMenuTitle('managecourse/courses_all', $context['lang']) ?: 'Manage Course',
            'menus' => $context['permissions']->menuTree($context['user'], $context['lang']),
            'user' => $context['user'],
            'name' => $this->session->get('name'),
            'report' => $report,
        ]);
    }

    public function exportReport($surveyId)
    {
        $context = $this->adminContext('ru_print');
        if (! is_array($context)) {
            return $context;
        }

        $report = (new SurveyModel())->report((int) $surveyId, $context['lang']);
        if (! $report) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Survey report ' . $surveyId);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Survey Summary');
        $sheet->fromArray(['Survey', $report['survey']['title']], null, 'A1');
        $sheet->fromArray(['Course', ($report['survey']['ccode'] ?? '-') . ' - ' . ($report['survey']['course_title'] ?? '-')], null, 'A2');
        $sheet->fromArray(['Question', 'Responses', 'Average', 'Rate 1', 'Rate 2', 'Rate 3', 'Rate 4', 'Rate 5'], null, 'A4');
        $line = 5;
        foreach ($report['summary'] as $item) {
            $sheet->fromArray([
                $item['question']['heading'],
                $item['responses'],
                $item['average'],
                $item['ratings'][1],
                $item['ratings'][2],
                $item['ratings'][3],
                $item['ratings'][4],
                $item['ratings'][5],
            ], null, 'A' . $line);
            $line++;
        }
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $file = WRITEPATH . 'cache/survey_report_' . (int) $surveyId . '_' . date('YmdHis') . '.xlsx';
        (new Xlsx($spreadsheet))->save($file);

        return $this->response->download($file, null)->setFileName('survey_report_' . (int) $surveyId . '_' . date('YmdHis') . '.xlsx');
    }

    public function show($surveyId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'coursemain/all_courses') && ! $permissions->can($user, 'coursemain/my_course')) {
            return redirect()->to(site_url('dashboard'));
        }

        $survey = (new SurveyModel())->surveyDetail((int) $surveyId, $user, $lang);
        if (! $survey) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Survey ' . $surveyId);
        }

        return view('survey/show', [
            'survey' => $survey,
            'result' => session()->getFlashdata('survey_result'),
            'path' => 'coursemain/survey/' . (int) $surveyId,
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
        ]);
    }

    public function submit($surveyId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $answers = $this->request->getPost('answers') ?? [];
        $suggestions = $this->request->getPost('suggestions') ?? [];
        if (! is_array($answers)) {
            $answers = [];
        }
        if (! is_array($suggestions)) {
            $suggestions = [];
        }

        $result = (new SurveyModel())->submit(
            (int) $surveyId,
            $answers,
            $suggestions,
            (string) $this->request->getPost('qnu_suggestion'),
            $user,
            $lang
        );

        return redirect()
            ->to(site_url('coursemain/survey/' . (int) $surveyId))
            ->with($result['ok'] ? 'survey_result' : 'course_error', $result);
    }

    private function form(?int $surveyId)
    {
        $context = $this->adminContext($surveyId ? 'ru_edit' : 'ru_add');
        if (! is_array($context)) {
            return $context;
        }

        $model = new SurveyModel();
        $survey = $surveyId ? $model->surveyForEdit($surveyId, $context['lang']) : null;
        if ($surveyId && ! $survey) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Survey ' . $surveyId);
        }

        return view('survey/form', [
            'path' => 'managecourse/courses_all',
            'title' => $surveyId ? 'Edit Survey' : 'Create Survey',
            'title_main' => $context['permissions']->parentMenuTitle('managecourse/courses_all', $context['lang']) ?: 'Manage Course',
            'menus' => $context['permissions']->menuTree($context['user'], $context['lang']),
            'user' => $context['user'],
            'name' => $this->session->get('name'),
            'survey' => $survey,
            'courses' => $model->activeCourses($context['lang']),
            'defaultCourseId' => (int) $this->request->getGet('cos_id'),
        ]);
    }

    private function write(?int $surveyId)
    {
        $context = $this->adminContext($surveyId ? 'ru_edit' : 'ru_add');
        if (! is_array($context)) {
            return $context;
        }

        $model = new SurveyModel();
        $result = $surveyId
            ? $model->updateSurvey($surveyId, $this->request->getPost(), $context['user'])
            : $model->createSurvey($this->request->getPost(), $context['user']);

        $targetId = (int) ($result['survey_id'] ?? $surveyId ?? 0);
        $target = $targetId > 0 ? site_url('managecourse/surveys/' . $targetId . '/edit') : site_url('managecourse/surveys/create');

        return redirect()
            ->to($result['ok'] ? $target : current_url())
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    private function adminContext(string $field = 'ru_view')
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $permissions = new PermissionModel();
        if (! $permissions->can($user, 'managecourse/courses_all', $field)) {
            return redirect()->to(site_url('dashboard'));
        }

        return ['user' => $user, 'lang' => $lang, 'permissions' => $permissions];
    }

    private function filters(): array
    {
        return [
            'cos_id' => (string) $this->request->getGet('cos_id'),
            'status' => (string) $this->request->getGet('status'),
            'keyword' => (string) $this->request->getGet('keyword'),
        ];
    }
}
