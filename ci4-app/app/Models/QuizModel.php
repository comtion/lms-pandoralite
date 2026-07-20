<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends Model
{
    protected $returnType = 'array';

    public function adminQuizzes(array $filters, string $lang, int $limit = 150): array
    {
        $builder = $this->db->table('lms_qiz')
            ->select('lms_qiz.*, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp')
            ->select('COUNT(DISTINCT lms_ques.ques_id) AS question_count')
            ->select('COUNT(DISTINCT lms_qiz_tc.qiztc_id) AS attempt_count')
            ->join('lms_cos', 'lms_cos.cos_id = lms_qiz.cos_id', 'left')
            ->join('lms_ques', 'lms_ques.qiz_id = lms_qiz.qiz_id AND lms_ques.ques_isDelete = 0', 'left')
            ->join('lms_qiz_tc', 'lms_qiz_tc.qiz_id = lms_qiz.qiz_id AND lms_qiz_tc.qiztc_isDelete = 0', 'left')
            ->where('lms_qiz.quiz_isDelete', '0')
            ->groupBy('lms_qiz.qiz_id')
            ->orderBy('lms_qiz.qiz_id', 'DESC')
            ->limit($limit);

        if (! empty($filters['cos_id'])) {
            $builder->where('lms_qiz.cos_id', (int) $filters['cos_id']);
        }
        if (($filters['status'] ?? '') !== '') {
            $builder->where('lms_qiz.quiz_status', (int) $filters['status']);
        }

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $builder->groupStart()
                ->like('lms_qiz.quiz_name_eng', $keyword)
                ->orLike('lms_qiz.quiz_name_th', $keyword)
                ->orLike('lms_cos.ccode', $keyword)
                ->orLike('lms_cos.cname_eng', $keyword)
                ->orLike('lms_cos.cname_th', $keyword)
                ->groupEnd();
        }

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'quiz_name');
            $row['course_title'] = $this->localized($row, $lang, 'cname');
            $row['type_label'] = (string) $row['quiz_type'] === '1' ? 'Pre-test' : 'Post-test';
        }

        return $rows;
    }

    public function activeCourses(string $lang): array
    {
        $rows = $this->db->table('lms_cos')
            ->select('cos_id, ccode, cname_th, cname_eng, cname_jp')
            ->where('cos_isDelete', '0')
            ->orderBy('cos_id', 'DESC')
            ->limit(1000)
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'cname');
        }

        return $rows;
    }

    public function quizForEdit(int $quizId, string $lang = 'english'): ?array
    {
        $quiz = $this->db->table('lms_qiz')
            ->select('lms_qiz.*, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp')
            ->join('lms_cos', 'lms_cos.cos_id = lms_qiz.cos_id', 'left')
            ->where('lms_qiz.qiz_id', $quizId)
            ->where('lms_qiz.quiz_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $quiz) {
            return null;
        }

        $quiz['title'] = $this->localized($quiz, $lang, 'quiz_name');
        $quiz['course_title'] = $this->localized($quiz, $lang, 'cname');
        $quiz['questions'] = $this->adminQuestions($quizId, $lang);

        return $quiz;
    }

    public function createQuiz(array $input, array $user): array
    {
        $payload = $this->quizPayload($input);
        if (! $payload['ok']) {
            return $payload;
        }

        $now = date('Y-m-d H:i:s');
        $data = $payload['payload'] + [
            'quiz_isDelete' => 0,
            'quiz_createby' => (string) ($user['u_id'] ?? ''),
            'quiz_createdate' => $now,
            'quiz_modifiedby' => (string) ($user['u_id'] ?? ''),
            'quiz_modifieddate' => $now,
        ];
        $this->db->table('lms_qiz')->insert($data);
        $quizId = (int) $this->db->insertID();

        return ['ok' => true, 'message' => 'Quiz created.', 'quiz_id' => $quizId];
    }

    public function updateQuiz(int $quizId, array $input, array $user): array
    {
        if (! $this->quizForEdit($quizId)) {
            return ['ok' => false, 'message' => 'Quiz not found.'];
        }

        $payload = $this->quizPayload($input);
        if (! $payload['ok']) {
            return $payload;
        }

        $data = $payload['payload'] + [
            'quiz_modifiedby' => (string) ($user['u_id'] ?? ''),
            'quiz_modifieddate' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('lms_qiz')->where('qiz_id', $quizId)->update($data);

        return ['ok' => true, 'message' => 'Quiz updated.', 'quiz_id' => $quizId];
    }

    public function setQuizStatus(int $quizId, int $status, array $user): array
    {
        if (! in_array($status, [0, 1], true) || ! $this->quizForEdit($quizId)) {
            return ['ok' => false, 'message' => 'Invalid quiz or status.'];
        }

        $this->db->table('lms_qiz')->where('qiz_id', $quizId)->update([
            'quiz_status' => $status,
            'quiz_modifiedby' => (string) ($user['u_id'] ?? ''),
            'quiz_modifieddate' => date('Y-m-d H:i:s'),
        ]);

        return ['ok' => true, 'message' => 'Quiz status updated.'];
    }

    public function createQuestion(int $quizId, array $input, array $user): array
    {
        if (! $this->quizForEdit($quizId)) {
            return ['ok' => false, 'message' => 'Quiz not found.'];
        }

        $payload = $this->questionPayload($input);
        if (! $payload['ok']) {
            return $payload;
        }

        $this->db->transStart();
        $now = date('Y-m-d H:i:s');
        $data = $payload['question'] + [
            'qiz_id' => $quizId,
            'ques_isDelete' => 0,
            'ques_createby' => (string) ($user['u_id'] ?? ''),
            'ques_createdate' => $now,
            'ques_modifiedby' => (string) ($user['u_id'] ?? ''),
            'ques_modifieddate' => $now,
        ];
        $this->db->table('lms_ques')->insert($data);
        $questionId = (int) $this->db->insertID();
        if (in_array($data['ques_type'], ['multi', 'fill_blank', 'sort_order'], true)) {
            $this->saveChoices($questionId, $payload['choices'], $user);
        }
        $this->db->transComplete();

        return $this->db->transStatus()
            ? ['ok' => true, 'message' => 'Question created.']
            : ['ok' => false, 'message' => 'Question create failed.'];
    }

    public function importQuestions(int $quizId, array $rows, array $user): array
    {
        if (! $this->quizForEdit($quizId)) {
            return ['ok' => false, 'message' => 'Quiz not found.'];
        }

        $payloads = [];
        $errors = [];
        foreach ($rows as $index => $row) {
            $line = (int) ($row['_line'] ?? ($index + 2));
            $payload = $this->questionPayload($row);
            if (! $payload['ok']) {
                $errors[] = 'Row ' . $line . ': ' . $payload['message'];
                continue;
            }
            $payloads[] = $payload;
        }

        if ($payloads === []) {
            return ['ok' => false, 'message' => 'No valid question rows found.'];
        }

        if ($errors !== []) {
            return ['ok' => false, 'message' => implode(' ', array_slice($errors, 0, 8))];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->transStart();
        foreach ($payloads as $payload) {
            $data = $payload['question'] + [
                'qiz_id' => $quizId,
                'ques_isDelete' => 0,
                'ques_createby' => (string) ($user['u_id'] ?? ''),
                'ques_createdate' => $now,
                'ques_modifiedby' => (string) ($user['u_id'] ?? ''),
                'ques_modifieddate' => $now,
            ];
            $this->db->table('lms_ques')->insert($data);
            $questionId = (int) $this->db->insertID();
            if (in_array($data['ques_type'], ['multi', 'fill_blank', 'sort_order'], true)) {
                $this->saveChoices($questionId, $payload['choices'], $user);
            }
        }
        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return ['ok' => false, 'message' => 'Question import failed.'];
        }

        return ['ok' => true, 'message' => count($payloads) . ' question(s) imported.'];
    }

    public function updateQuestion(int $questionId, array $input, array $user): array
    {
        $question = $this->questionForEdit($questionId);
        if (! $question) {
            return ['ok' => false, 'message' => 'Question not found.', 'quiz_id' => 0];
        }

        $payload = $this->questionPayload($input);
        if (! $payload['ok']) {
            return $payload + ['quiz_id' => (int) $question['qiz_id']];
        }

        $data = $payload['question'] + [
            'ques_modifiedby' => (string) ($user['u_id'] ?? ''),
            'ques_modifieddate' => date('Y-m-d H:i:s'),
        ];
        $this->db->transStart();
        $this->db->table('lms_ques')->where('ques_id', $questionId)->update($data);
        if (in_array($data['ques_type'], ['multi', 'fill_blank', 'sort_order'], true)) {
            $this->saveChoices($questionId, $payload['choices'], $user);
        } else {
            $this->db->table('lms_ques_mul')->where('ques_id', $questionId)->update(['mul_isDelete' => 1]);
        }
        $this->db->transComplete();

        return $this->db->transStatus()
            ? ['ok' => true, 'message' => 'Question updated.', 'quiz_id' => (int) $question['qiz_id']]
            : ['ok' => false, 'message' => 'Question update failed.', 'quiz_id' => (int) $question['qiz_id']];
    }

    public function setQuestionStatus(int $questionId, ?int $status, array $user): array
    {
        $question = $this->questionForEdit($questionId);
        if (! $question) {
            return ['ok' => false, 'message' => 'Question not found.', 'quiz_id' => 0];
        }

        $data = [
            'ques_modifiedby' => (string) ($user['u_id'] ?? ''),
            'ques_modifieddate' => date('Y-m-d H:i:s'),
        ];
        if ($status === null) {
            $data['ques_isDelete'] = 1;
        } else {
            $data['ques_status'] = in_array($status, [0, 1], true) ? $status : 0;
        }
        $this->db->table('lms_ques')->where('ques_id', $questionId)->update($data);

        return ['ok' => true, 'message' => $status === null ? 'Question archived.' : 'Question status updated.', 'quiz_id' => (int) $question['qiz_id']];
    }

    public function gradingRows(int $quizId, string $lang): ?array
    {
        $quiz = $this->quizForEdit($quizId, $lang);
        if (! $quiz) {
            return null;
        }

        $rows = $this->db->table('lms_ques_tc')
            ->select('lms_ques_tc.*, lms_ques.ques_name_th, lms_ques.ques_name_eng, lms_ques.ques_name_jp, lms_ques.ques_score, lms_emp.emp_c, lms_emp.fullname_th, lms_emp.fullname_en')
            ->join('lms_ques', 'lms_ques.ques_id = lms_ques_tc.ques_id')
            ->join('lms_emp', 'lms_emp.emp_id = lms_ques_tc.emp_id', 'left')
            ->where('lms_ques_tc.qiz_id', $quizId)
            ->whereNotIn('lms_ques.ques_type', ['multi', '2choice', 'fill_blank', 'sort_order'])
            ->orderBy('lms_ques_tc.tc_finish', 'DESC')
            ->limit(300)
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['question_title'] = $this->localized($row, $lang, 'ques_name');
            $row['learner_name'] = $lang === 'thai' ? ($row['fullname_th'] ?: $row['fullname_en']) : ($row['fullname_en'] ?: $row['fullname_th']);
        }

        return ['quiz' => $quiz, 'rows' => $rows];
    }

    public function gradeAnswer(int $answerId, float $score, string $note, array $user): array
    {
        $answer = $this->db->table('lms_ques_tc')
            ->where('tc_id', $answerId)
            ->get()
            ->getRowArray();
        if (! $answer) {
            return ['ok' => false, 'message' => 'Answer not found.', 'quiz_id' => 0];
        }

        $this->db->table('lms_ques_tc')->where('tc_id', $answerId)->update([
            'tc_score' => max(0, $score),
            'tc_note' => $note,
            'tc_isSavescore' => 1,
        ]);
        $this->recalculateAttempt((int) $answer['qiztc_id']);

        return ['ok' => true, 'message' => 'Answer score updated.', 'quiz_id' => (int) $answer['qiz_id']];
    }

    public function quizDetail(int $quizId, array $user, string $lang): ?array
    {
        $quiz = $this->quizRow($quizId);
        if (! $quiz) {
            return null;
        }

        (new CourseModel())->startCourse((int) $quiz['cos_id'], $user, $lang);
        $quiz['title'] = $this->localized($quiz, $lang, 'quiz_name');
        $quiz['description'] = $this->localized($quiz, $lang, 'quiz_info');
        $quiz['type_label'] = (string) $quiz['quiz_type'] === '1' ? 'Pre-test' : 'Post-test';
        $quiz['questions'] = $this->questions($quizId, $lang);
        $quiz['last_attempt'] = $this->lastAttempt($quizId, $user);

        return $quiz;
    }

    public function submit(int $quizId, array $answers, array $user, string $lang): array
    {
        $quiz = $this->quizDetail($quizId, $user, $lang);
        if (! $quiz) {
            return ['ok' => false, 'message' => 'Quiz not found.'];
        }

        $enrollment = $this->enrollment((int) $quiz['cos_id'], $user);
        if (! $enrollment) {
            return ['ok' => false, 'message' => 'Enrollment is required before taking quiz.'];
        }

        $questions = $quiz['questions'];
        if ($questions === []) {
            return ['ok' => false, 'message' => 'This quiz has no active questions.'];
        }

        $totalScore = 0.0;
        $earnedScore = 0.0;
        foreach ($questions as $question) {
            $totalScore += (float) ($question['ques_score'] ?? 0);
            $scoreData = $this->scoreQuestion($question, $answers[$question['ques_id']] ?? null);
            $earnedScore += $scoreData['score'];
        }

        $percent = $totalScore > 0 ? round(($earnedScore / $totalScore) * 100, 2) : 0.0;
        $attemptNo = $this->attemptCount($quizId, $user, (int) $enrollment['cosen_id']) + 1;
        $now = date('Y-m-d H:i:s');

        $this->db->transStart();
        $this->db->table('lms_qiz_tc')->insert([
            'emp_id' => $user['emp_id'] ?? null,
            'qiz_id' => $quizId,
            'time_start' => $now,
            'time_mod' => $now,
            'time_finish' => $now,
            'sum_score' => $earnedScore,
            'per_score' => $percent,
            'limit_val' => $attemptNo,
            'qiz_status' => '3',
            'qiztc_isDelete' => 0,
            'cosen_id' => $enrollment['cosen_id'],
        ]);
        $attemptId = (int) $this->db->insertID();

        $number = 1;
        foreach ($questions as $question) {
            $rawAnswer = $answers[$question['ques_id']] ?? null;
            $scoreData = $this->scoreQuestion($question, $rawAnswer);
            $answer = $this->answerForStorage($rawAnswer);
            $score = $scoreData['score'];

            $this->db->table('lms_ques_tc')->insert([
                'qiztc_id' => $attemptId,
                'qiz_id' => $quizId,
                'ques_id' => $question['ques_id'],
                'emp_id' => $user['emp_id'] ?? null,
                'tc_answer' => $answer,
                'tc_finish' => $now,
                'tc_flag' => '0',
                'tc_save' => $answer !== '' ? '1' : '0',
                'tc_score' => $score,
                'tc_number' => $number,
                'tc_note' => '',
                'cosen_id' => $enrollment['cosen_id'],
                'tc_isSavescore' => (int) ($question['ques_isSavescore'] ?? 0),
            ]);
            $number++;
        }

        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return ['ok' => false, 'message' => 'Quiz submission failed.'];
        }

        $progress = (new ProgressModel())->recalculate((int) $quiz['cos_id'], $user, $lang);

        return [
            'ok' => true,
            'message' => 'Quiz submitted.',
            'attempt_id' => $attemptId,
            'score' => $earnedScore,
            'total' => $totalScore,
            'percent' => $percent,
            'passed' => $percent >= (float) ($quiz['quiz_maxscore'] ?? 0),
            'progress' => $progress,
        ];
    }

    private function quizRow(int $quizId): ?array
    {
        $row = $this->db->table('lms_qiz')
            ->where('qiz_id', $quizId)
            ->where('quiz_status', '1')
            ->where('quiz_show', '1')
            ->where('quiz_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $row || ! $this->isInPeriod($row)) {
            return null;
        }

        return $row;
    }

    private function questions(int $quizId, string $lang): array
    {
        $rows = $this->db->table('lms_ques')
            ->where('qiz_id', $quizId)
            ->where('ques_status', '1')
            ->where('ques_isDelete', '0')
            ->orderBy('ques_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'ques_name');
            $row['choices'] = [];
            $row['correct_answers'] = [];
            if (in_array((string) $row['ques_type'], ['multi', 'fill_blank', 'sort_order'], true)) {
                $multi = $this->db->table('lms_ques_mul')
                    ->where('ques_id', $row['ques_id'])
                    ->where('mul_status', '1')
                    ->where('mul_isDelete', '0')
                    ->get()
                    ->getRowArray();
                if ($multi) {
                    for ($i = 1; $i <= 10; $i++) {
                        $key = 'mul_c' . $i;
                        $text = $this->choiceText($multi, $lang, $key);
                        if ($text !== '') {
                            $row['choices'][] = ['value' => $key, 'text' => $text];
                        }
                    }
                    $row['correct_answers'] = array_values(array_filter(array_map('trim', explode(',', (string) ($multi['mul_answer'] ?? '')))));
                    if ((string) $row['ques_type'] === 'fill_blank') {
                        $row['blank_answers'] = array_map(static fn ($choice) => $choice['text'], $row['choices']);
                    } elseif ((string) $row['ques_type'] === 'sort_order') {
                        shuffle($row['choices']);
                    }
                }
            }
        }

        return $rows;
    }

    private function adminQuestions(int $quizId, string $lang): array
    {
        $rows = $this->db->table('lms_ques')
            ->where('qiz_id', $quizId)
            ->where('ques_isDelete', '0')
            ->orderBy('ques_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'ques_name');
            $row['choices'] = $this->choiceRow((int) $row['ques_id']);
        }

        return $rows;
    }

    private function questionForEdit(int $questionId): ?array
    {
        $row = $this->db->table('lms_ques')
            ->where('ques_id', $questionId)
            ->where('ques_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function choiceRow(int $questionId): array
    {
        $row = $this->db->table('lms_ques_mul')
            ->where('ques_id', $questionId)
            ->where('mul_isDelete', '0')
            ->orderBy('mul_id', 'DESC')
            ->get()
            ->getRowArray();

        return $row ?: [];
    }

    private function quizPayload(array $input): array
    {
        $courseId = (int) ($input['cos_id'] ?? 0);
        $nameEng = trim((string) ($input['quiz_name_eng'] ?? ''));
        $nameTh = trim((string) ($input['quiz_name_th'] ?? $nameEng));
        if ($courseId <= 0 || $nameEng === '') {
            return ['ok' => false, 'message' => 'Course and English quiz name are required.'];
        }

        $courseExists = $this->db->table('lms_cos')->where('cos_id', $courseId)->where('cos_isDelete', '0')->countAllResults() > 0;
        if (! $courseExists) {
            return ['ok' => false, 'message' => 'Course not found.'];
        }

        $languages = $input['quiz_lang'] ?? ['eng'];
        if (! is_array($languages)) {
            $languages = [$languages];
        }
        $languages = array_values(array_intersect($languages, ['th', 'eng', 'jp']));
        if ($languages === []) {
            $languages = ['eng'];
        }

        return [
            'ok' => true,
            'payload' => [
                'cos_id' => $courseId,
                'quiz_lang' => implode(',', $languages),
                'quiz_name_th' => $nameTh,
                'quiz_info_th' => trim((string) ($input['quiz_info_th'] ?? '')),
                'quiz_name_eng' => $nameEng,
                'quiz_info_eng' => trim((string) ($input['quiz_info_eng'] ?? '')),
                'quiz_name_jp' => trim((string) ($input['quiz_name_jp'] ?? '')),
                'quiz_info_jp' => trim((string) ($input['quiz_info_jp'] ?? '')),
                'period_open' => $this->dateOrZero((string) ($input['period_open'] ?? '')),
                'period_end' => $this->dateOrZero((string) ($input['period_end'] ?? '')),
                'quiz_random' => (int) ($input['quiz_random'] ?? 0),
                'quiz_random_choice' => (int) ($input['quiz_random_choice'] ?? 0),
                'quiz_show' => (int) ($input['quiz_show'] ?? 1),
                'quiz_grade' => (int) ($input['quiz_grade'] ?? 1),
                'quiz_type' => (int) ($input['quiz_type'] ?? 1),
                'quiz_answer' => (int) ($input['quiz_answer'] ?? 0),
                'quiz_limit' => (int) ($input['quiz_limit'] ?? 0),
                'quiz_limitval' => max(0, (int) ($input['quiz_limitval'] ?? 0)),
                'quiz_maxscore' => max(0, (int) ($input['quiz_maxscore'] ?? 80)),
                'quiz_ishint' => (int) ($input['quiz_ishint'] ?? 0),
                'quiz_model' => (int) ($input['quiz_model'] ?? 1),
                'quiz_status' => (int) ($input['quiz_status'] ?? 1),
                'quiz_numofshown' => max(0, (int) ($input['quiz_numofshown'] ?? 0)),
            ],
        ];
    }

    private function questionPayload(array $input): array
    {
        $nameEng = trim((string) ($input['ques_name_eng'] ?? ''));
        $nameTh = trim((string) ($input['ques_name_th'] ?? $nameEng));
        if ($nameEng === '') {
            return ['ok' => false, 'message' => 'English question is required.'];
        }

        $type = (string) ($input['ques_type'] ?? 'multi');
        if (! in_array($type, ['multi', 'text', 'fill_blank', 'sort_order'], true)) {
            $type = 'multi';
        }

        $choices = [];
        for ($i = 1; $i <= 10; $i++) {
            $choices['mul_c' . $i . '_eng'] = trim((string) ($input['mul_c' . $i . '_eng'] ?? ''));
            $choices['mul_c' . $i . '_th'] = trim((string) ($input['mul_c' . $i . '_th'] ?? ''));
            $choices['mul_c' . $i . '_jp'] = trim((string) ($input['mul_c' . $i . '_jp'] ?? ''));
        }
        $answer = $this->normalizeCorrectAnswer($type, (string) ($input['mul_answer'] ?? 'mul_c1'), $choices);

        if ($type === 'multi' && ! $this->hasAnyEnglishChoice($choices)) {
            return ['ok' => false, 'message' => 'At least one English choice is required.'];
        }
        if ($type === 'fill_blank' && ! $this->hasAnyEnglishChoice($choices)) {
            return ['ok' => false, 'message' => 'At least one blank answer is required.'];
        }
        if ($type === 'sort_order') {
            $itemCount = 0;
            foreach ($choices as $key => $value) {
                if (str_ends_with($key, '_eng') && $value !== '') {
                    $itemCount++;
                }
            }
            if ($itemCount < 2) {
                return ['ok' => false, 'message' => 'Sort order questions require at least two English items.'];
            }
        }

        $blankScoreMode = (string) ($input['ques_blank_score_mode'] ?? 'all_or_nothing');
        if (! in_array($blankScoreMode, ['all_or_nothing', 'partial'], true)) {
            $blankScoreMode = 'all_or_nothing';
        }

        return [
            'ok' => true,
            'question' => [
                'ques_type' => $type,
                'ques_name_th' => $nameTh,
                'ques_info_th' => trim((string) ($input['ques_info_th'] ?? '')),
                'ques_name_eng' => $nameEng,
                'ques_info_eng' => trim((string) ($input['ques_info_eng'] ?? '')),
                'ques_name_jp' => trim((string) ($input['ques_name_jp'] ?? '')),
                'ques_info_jp' => trim((string) ($input['ques_info_jp'] ?? '')),
                'ques_score' => max(0, (float) ($input['ques_score'] ?? 1)),
                'ques_hintname_th' => '',
                'ques_hintdetail_th' => '',
                'ques_hintname_eng' => '',
                'ques_hintdetail_eng' => '',
                'ques_hintname_jp' => '',
                'ques_hintdetail_jp' => '',
                'ques_hintimg' => '',
                'ques_status' => (int) ($input['ques_status'] ?? 1),
                'ques_isSavescore' => in_array($type, ['multi', 'fill_blank', 'sort_order'], true) ? 1 : 0,
                'ques_blank_score_mode' => $blankScoreMode,
            ],
            'choices' => $choices + ['mul_answer' => $answer],
        ];
    }

    private function saveChoices(int $questionId, array $choices, array $user): void
    {
        $now = date('Y-m-d H:i:s');
        $existing = $this->choiceRow($questionId);
        $data = $choices + [
            'mul_status' => 1,
            'mul_isDelete' => 0,
            'mul_modifiedby' => (string) ($user['u_id'] ?? ''),
            'mul_modifieddate' => $now,
        ];

        if ($existing) {
            $this->db->table('lms_ques_mul')->where('mul_id', $existing['mul_id'])->update($data);
            return;
        }

        $data += [
            'ques_id' => $questionId,
            'mul_createby' => (string) ($user['u_id'] ?? ''),
            'mul_createdate' => $now,
        ];
        $this->db->table('lms_ques_mul')->insert($data);
    }

    private function normalizeCorrectAnswer(string $type, string $answer, array $choices): string
    {
        $validKeys = $this->choiceKeys();
        if ($type === 'multi') {
            if (in_array($answer, $validKeys, true) && trim((string) ($choices[$answer . '_eng'] ?? '')) !== '') {
                return $answer;
            }

            foreach ($validKeys as $key) {
                if (trim((string) ($choices[$key . '_eng'] ?? '')) !== '') {
                    return $key;
                }
            }

            return 'mul_c1';
        }

        if ($type === 'fill_blank') {
            $keys = [];
            foreach ($validKeys as $key) {
                if (trim((string) ($choices[$key . '_eng'] ?? '')) !== '') {
                    $keys[] = $key;
                }
            }
            return implode(',', $keys);
        }

        if ($type === 'sort_order') {
            $itemKeys = [];
            foreach ($validKeys as $key) {
                if (trim((string) ($choices[$key . '_eng'] ?? '')) !== '') {
                    $itemKeys[] = $key;
                }
            }
            $parts = array_filter(array_map('trim', explode(',', strtolower($answer))));
            $keys = [];
            foreach ($parts as $part) {
                if (preg_match('/^(10|[1-9])$/', $part)) {
                    $part = 'mul_c' . $part;
                } elseif (preg_match('/^choice(10|[1-9])$/', $part, $match)) {
                    $part = 'mul_c' . $match[1];
                }
                if (in_array($part, $validKeys, true) && trim((string) ($choices[$part . '_eng'] ?? '')) !== '' && ! in_array($part, $keys, true)) {
                    $keys[] = $part;
                }
            }
            if (count($keys) === count($itemKeys)) {
                return implode(',', $keys);
            }

            return implode(',', $itemKeys);
        }

        return '';
    }

    private function choiceKeys(): array
    {
        $keys = [];
        for ($i = 1; $i <= 10; $i++) {
            $keys[] = 'mul_c' . $i;
        }

        return $keys;
    }

    private function hasAnyEnglishChoice(array $choices): bool
    {
        foreach ($this->choiceKeys() as $key) {
            if (trim((string) ($choices[$key . '_eng'] ?? '')) !== '') {
                return true;
            }
        }

        return false;
    }

    private function scoreQuestion(array $question, $rawAnswer): array
    {
        $maxScore = (float) ($question['ques_score'] ?? 0);
        $type = (string) ($question['ques_type'] ?? '');

        if ($type === 'multi') {
            $answer = is_array($rawAnswer) ? '' : (string) ($rawAnswer ?? '');
            return ['score' => in_array($answer, $question['correct_answers'] ?? [], true) ? $maxScore : 0.0];
        }

        if ($type === 'fill_blank') {
            $submitted = is_array($rawAnswer) ? array_values($rawAnswer) : [];
            $expected = array_values(array_filter((array) ($question['blank_answers'] ?? []), static fn ($value) => trim((string) $value) !== ''));
            if ($expected === []) {
                return ['score' => 0.0];
            }

            $correct = 0;
            foreach ($expected as $index => $answer) {
                if ($this->normalizeLearnerText((string) ($submitted[$index] ?? '')) === $this->normalizeLearnerText((string) $answer)) {
                    $correct++;
                }
            }

            if ((string) ($question['ques_blank_score_mode'] ?? 'all_or_nothing') === 'partial') {
                return ['score' => round(($correct / count($expected)) * $maxScore, 2)];
            }

            return ['score' => $correct === count($expected) ? $maxScore : 0.0];
        }

        if ($type === 'sort_order') {
            $submitted = is_array($rawAnswer) ? $rawAnswer : [];
            asort($submitted, SORT_NUMERIC);
            $ordered = array_keys($submitted);
            $expected = array_values((array) ($question['correct_answers'] ?? []));
            return ['score' => $expected !== [] && $ordered === $expected ? $maxScore : 0.0];
        }

        return ['score' => 0.0];
    }

    private function answerForStorage($rawAnswer): string
    {
        if (is_array($rawAnswer)) {
            return json_encode($rawAnswer, JSON_UNESCAPED_UNICODE) ?: '';
        }

        return trim((string) ($rawAnswer ?? ''));
    }

    private function normalizeLearnerText(string $value): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', $value) ?? $value), 'UTF-8');
    }

    private function recalculateAttempt(int $attemptId): void
    {
        if ($attemptId <= 0) {
            return;
        }

        $attempt = $this->db->table('lms_qiz_tc')->where('qiztc_id', $attemptId)->get()->getRowArray();
        if (! $attempt) {
            return;
        }

        $scoreRow = $this->db->table('lms_ques_tc')
            ->select('SUM(tc_score) AS earned')
            ->where('qiztc_id', $attemptId)
            ->get()
            ->getRowArray();
        $totalRow = $this->db->table('lms_ques')
            ->select('SUM(ques_score) AS total')
            ->where('qiz_id', (int) $attempt['qiz_id'])
            ->where('ques_isDelete', '0')
            ->get()
            ->getRowArray();

        $earned = (float) ($scoreRow['earned'] ?? 0);
        $total = (float) ($totalRow['total'] ?? 0);
        $percent = $total > 0 ? round(($earned / $total) * 100, 2) : 0.0;
        $this->db->table('lms_qiz_tc')->where('qiztc_id', $attemptId)->update([
            'sum_score' => $earned,
            'per_score' => $percent,
            'time_mod' => date('Y-m-d H:i:s'),
        ]);
    }

    private function dateOrZero(string $value): string
    {
        if (trim($value) === '') {
            return '0000-00-00 00:00:00';
        }

        $time = strtotime($value);
        return $time ? date('Y-m-d H:i:s', $time) : '0000-00-00 00:00:00';
    }

    private function enrollment(int $courseId, array $user): ?array
    {
        return $this->db->table('lms_cos_enroll')
            ->where('cos_id', $courseId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('cosen_isDelete', '0')
            ->orderBy('cosen_id', 'DESC')
            ->get()
            ->getRowArray();
    }

    private function lastAttempt(int $quizId, array $user): ?array
    {
        return $this->db->table('lms_qiz_tc')
            ->where('qiz_id', $quizId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('qiztc_isDelete', '0')
            ->orderBy('qiztc_id', 'DESC')
            ->get()
            ->getRowArray();
    }

    private function attemptCount(int $quizId, array $user, int $enrollmentId): int
    {
        return $this->db->table('lms_qiz_tc')
            ->where('qiz_id', $quizId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('cosen_id', $enrollmentId)
            ->where('qiztc_isDelete', '0')
            ->countAllResults();
    }

    private function isInPeriod(array $row): bool
    {
        $start = (string) ($row['period_open'] ?? '');
        $end = (string) ($row['period_end'] ?? '');
        if ($this->emptyDate($start) && $this->emptyDate($end)) {
            return true;
        }

        $now = time();
        return ($this->emptyDate($start) || strtotime($start) <= $now)
            && ($this->emptyDate($end) || strtotime($end) >= $now);
    }

    private function emptyDate(string $value): bool
    {
        return $value === '' || str_starts_with($value, '0000-00-00');
    }

    private function localized(array $row, string $lang, string $prefix): string
    {
        $order = match ($lang) {
            'thai' => ['th', 'eng', 'jp'],
            'japan' => ['jp', 'eng', 'th'],
            default => ['eng', 'th', 'jp'],
        };

        foreach ($order as $suffix) {
            $value = trim((string) ($row[$prefix . '_' . $suffix] ?? ''));
            if ($value !== '') {
                return strip_tags($value);
            }
        }

        return '-';
    }

    private function choiceText(array $row, string $lang, string $key): string
    {
        $order = match ($lang) {
            'thai' => ['th', 'eng', 'jp'],
            'japan' => ['jp', 'eng', 'th'],
            default => ['eng', 'th', 'jp'],
        };

        foreach ($order as $suffix) {
            $value = trim((string) ($row[$key . '_' . $suffix] ?? ''));
            if ($value !== '') {
                return strip_tags($value);
            }
        }

        return '';
    }
}
