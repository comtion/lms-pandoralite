<?php

namespace App\Controllers;

use App\Models\PermissionModel;
use App\Models\QuizModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class QuizPortal extends BaseController
{
    public function manage()
    {
        $context = $this->adminContext();
        if (! is_array($context)) {
            return $context;
        }

        $model = new QuizModel();
        $filters = $this->filters();

        return view('quiz/manage', [
            'path' => 'managecourse/courses_all',
            'title' => 'Course Quizzes',
            'title_main' => $context['permissions']->parentMenuTitle('managecourse/courses_all', $context['lang']) ?: 'Manage Course',
            'menus' => $context['permissions']->menuTree($context['user'], $context['lang']),
            'user' => $context['user'],
            'name' => $this->session->get('name'),
            'quizzes' => $model->adminQuizzes($filters, $context['lang']),
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

    public function edit($quizId)
    {
        return $this->form((int) $quizId);
    }

    public function update($quizId)
    {
        return $this->write((int) $quizId);
    }

    public function status($quizId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new QuizModel())->setQuizStatus((int) $quizId, (int) $this->request->getPost('status'), $context['user']);

        return redirect()->to(site_url('managecourse/quizzes'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function storeQuestion($quizId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new QuizModel())->createQuestion((int) $quizId, $this->request->getPost(), $context['user']);

        return redirect()->to(site_url('managecourse/quizzes/' . (int) $quizId . '/edit'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function updateQuestion($questionId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new QuizModel())->updateQuestion((int) $questionId, $this->request->getPost(), $context['user']);

        return redirect()->to(site_url('managecourse/quizzes/' . (int) ($result['quiz_id'] ?? 0) . '/edit'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function questionStatus($questionId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $status = (string) $this->request->getPost('archive') === '1' ? null : (int) $this->request->getPost('status');
        $result = (new QuizModel())->setQuestionStatus((int) $questionId, $status, $context['user']);

        return redirect()->to(site_url('managecourse/quizzes/' . (int) ($result['quiz_id'] ?? 0) . '/edit'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function importQuestions($quizId)
    {
        $context = $this->adminContext('ru_add');
        if (! is_array($context)) {
            return $context;
        }

        $file = $this->request->getFile('question_file');
        if (! $file || ! $file->isValid()) {
            return redirect()->to(site_url('managecourse/quizzes/' . (int) $quizId . '/edit'))->with('course_error', 'Question import file is required.');
        }

        $extension = strtolower($file->getClientExtension() ?: $file->guessExtension() ?: '');
        if (! in_array($extension, ['xlsx', 'xls', 'csv'], true)) {
            return redirect()->to(site_url('managecourse/quizzes/' . (int) $quizId . '/edit'))->with('course_error', 'Import file must be XLSX, XLS, or CSV.');
        }

        try {
            $rows = $this->questionRowsFromFile($file->getTempName());
        } catch (\Throwable $e) {
            return redirect()->to(site_url('managecourse/quizzes/' . (int) $quizId . '/edit'))->with('course_error', 'Unable to read import file: ' . $e->getMessage());
        }

        $result = (new QuizModel())->importQuestions((int) $quizId, $rows, $context['user']);

        return redirect()
            ->to(site_url('managecourse/quizzes/' . (int) $quizId . '/edit'))
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function importTemplate()
    {
        $context = $this->adminContext('ru_view');
        if (! is_array($context)) {
            return $context;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Quiz Import');
        $headers = ['type', 'question_eng', 'question_th', 'score', 'choice1', 'choice2', 'choice3', 'choice4', 'choice5', 'choice6', 'choice7', 'choice8', 'choice9', 'choice10', 'correct_answer', 'blank_score_mode', 'status'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray(['multi', 'What is the correct safety step?', 'Correct safety step', 1, 'Wear PPE', 'Ignore warning sign', 'Skip inspection', '', '', '', '', '', '', '', 1, '', 1], null, 'A2');
        $sheet->fromArray(['text', 'Explain how to report an incident.', 'Incident report explanation', 2, '', '', '', '', '', '', '', '', '', '', '', '', 1], null, 'A3');
        $sheet->fromArray(['fill_blank', 'The emergency number is ____ and the assembly point is ____.', 'Emergency number and assembly point', 2, '191', 'Gate A', '', '', '', '', '', '', '', '', '', 'partial', 1], null, 'A4');
        $sheet->fromArray(['sort_order', 'Arrange the safety process.', 'Safety process order', 2, 'Inspect area', 'Wear PPE', 'Start work', '', '', '', '', '', '', '', '1,2,3', '', 1], null, 'A5');
        foreach (range('A', 'Q') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $sheet->freezePane('A2');

        $file = WRITEPATH . 'cache/quiz_import_template_' . date('YmdHis') . '.xlsx';
        (new Xlsx($spreadsheet))->save($file);

        return $this->response->download($file, null)->setFileName('quiz_import_template.xlsx');
    }
    public function grading($quizId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $grading = (new QuizModel())->gradingRows((int) $quizId, $context['lang']);
        if (! $grading) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Quiz grading ' . $quizId);
        }

        return view('quiz/grading', [
            'path' => 'managecourse/courses_all',
            'title' => 'Subjective Grading',
            'title_main' => $context['permissions']->parentMenuTitle('managecourse/courses_all', $context['lang']) ?: 'Manage Course',
            'menus' => $context['permissions']->menuTree($context['user'], $context['lang']),
            'user' => $context['user'],
            'name' => $this->session->get('name'),
            'grading' => $grading,
        ]);
    }

    public function gradeAnswer($answerId)
    {
        $context = $this->adminContext('ru_edit');
        if (! is_array($context)) {
            return $context;
        }

        $result = (new QuizModel())->gradeAnswer((int) $answerId, (float) $this->request->getPost('tc_score'), (string) $this->request->getPost('tc_note'), $context['user']);

        return redirect()->to(site_url('managecourse/quizzes/' . (int) ($result['quiz_id'] ?? 0) . '/grading'))->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    public function show($quizId)
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

        $quiz = (new QuizModel())->quizDetail((int) $quizId, $user, $lang);
        if (! $quiz) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Quiz ' . $quizId);
        }

        return view('quiz/show', [
            'quiz' => $quiz,
            'result' => session()->getFlashdata('quiz_result'),
            'path' => 'coursemain/quiz/' . (int) $quizId,
            'menus' => $permissions->menuTree($user, $lang),
            'user' => $user,
            'name' => $this->session->get('name'),
        ]);
    }

    public function submit($quizId)
    {
        $user = $this->session->get('user');
        $lang = $this->session->get('lang') ?? 'english';
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $answers = $this->request->getPost('answers') ?? [];
        if (! is_array($answers)) {
            $answers = [];
        }

        $result = (new QuizModel())->submit((int) $quizId, $answers, $user, $lang);

        return redirect()
            ->to(site_url('coursemain/quiz/' . (int) $quizId))
            ->with($result['ok'] ? 'quiz_result' : 'course_error', $result);
    }

    private function form(?int $quizId)
    {
        $context = $this->adminContext($quizId ? 'ru_edit' : 'ru_add');
        if (! is_array($context)) {
            return $context;
        }

        $model = new QuizModel();
        $quiz = $quizId ? $model->quizForEdit($quizId, $context['lang']) : null;
        if ($quizId && ! $quiz) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Quiz ' . $quizId);
        }

        return view('quiz/form', [
            'path' => 'managecourse/courses_all',
            'title' => $quizId ? 'Edit Quiz' : 'Create Quiz',
            'title_main' => $context['permissions']->parentMenuTitle('managecourse/courses_all', $context['lang']) ?: 'Manage Course',
            'menus' => $context['permissions']->menuTree($context['user'], $context['lang']),
            'user' => $context['user'],
            'name' => $this->session->get('name'),
            'quiz' => $quiz,
            'courses' => $model->activeCourses($context['lang']),
            'defaultCourseId' => (int) $this->request->getGet('cos_id'),
        ]);
    }

    private function write(?int $quizId)
    {
        $context = $this->adminContext($quizId ? 'ru_edit' : 'ru_add');
        if (! is_array($context)) {
            return $context;
        }

        $model = new QuizModel();
        $result = $quizId
            ? $model->updateQuiz($quizId, $this->request->getPost(), $context['user'])
            : $model->createQuiz($this->request->getPost(), $context['user']);
        $targetId = (int) ($result['quiz_id'] ?? $quizId ?? 0);

        return redirect()
            ->to($result['ok'] && $targetId > 0 ? site_url('managecourse/quizzes/' . $targetId . '/edit') : current_url())
            ->with($result['ok'] ? 'course_notice' : 'course_error', $result['message']);
    }

    private function questionRowsFromFile(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);
        if ($data === []) {
            return [];
        }

        $headerRow = array_shift($data);
        $headers = [];
        foreach ($headerRow as $column => $header) {
            $key = strtolower(trim((string) $header));
            if ($key !== '') {
                $headers[$column] = $key;
            }
        }

        $rows = [];
        $line = 2;
        foreach ($data as $row) {
            $mapped = ['_line' => $line];
            foreach ($headers as $column => $key) {
                $mapped[$key] = trim((string) ($row[$column] ?? ''));
            }
            $line++;

            if (implode('', array_diff_key($mapped, ['_line' => true])) === '') {
                continue;
            }

            $type = strtolower($mapped['type'] ?? 'multi');
            $correct = strtolower((string) ($mapped['correct_answer'] ?? '1'));
            if (preg_match('/^(10|[1-9])$/', $correct)) {
                $correct = 'mul_c' . $correct;
            } elseif (preg_match('/^choice(10|[1-9])$/', $correct, $match)) {
                $correct = 'mul_c' . $match[1];
            }
            if ($type === 'sort_order') {
                $correct = implode(',', array_map(static function ($part) {
                    $part = trim(strtolower($part));
                    if (preg_match('/^(10|[1-9])$/', $part)) {
                        return 'mul_c' . $part;
                    }
                    if (preg_match('/^choice(10|[1-9])$/', $part, $match)) {
                        return 'mul_c' . $match[1];
                    }

                    return $part;
                }, explode(',', $correct)));
            }

            $questionType = in_array($type, ['text', 'fill_blank', 'sort_order'], true) ? $type : 'multi';

            $rows[] = [
                '_line' => $mapped['_line'],
                'ques_type' => $questionType,
                'ques_name_eng' => $mapped['question_eng'] ?? '',
                'ques_name_th' => $mapped['question_th'] ?? '',
                'ques_score' => $mapped['score'] ?? '1',
                'ques_status' => ($mapped['status'] ?? '1') === '0' ? '0' : '1',
                'ques_blank_score_mode' => ($mapped['blank_score_mode'] ?? '') === 'partial' ? 'partial' : 'all_or_nothing',
                'mul_c1_eng' => $mapped['choice1'] ?? '',
                'mul_c2_eng' => $mapped['choice2'] ?? '',
                'mul_c3_eng' => $mapped['choice3'] ?? '',
                'mul_c4_eng' => $mapped['choice4'] ?? '',
                'mul_answer' => $correct,
            ];
            for ($i = 1; $i <= 10; $i++) {
                $rows[array_key_last($rows)]['mul_c' . $i . '_eng'] = $mapped['choice' . $i] ?? '';
            }
        }

        return $rows;
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
