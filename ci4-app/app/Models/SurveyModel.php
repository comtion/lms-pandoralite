<?php

namespace App\Models;

use CodeIgniter\Model;

class SurveyModel extends Model
{
    protected $returnType = 'array';

    public function adminSurveys(array $filters, string $lang, int $limit = 150): array
    {
        $builder = $this->db->table('lms_survey')
            ->select('lms_survey.*, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp')
            ->select('COUNT(DISTINCT lms_survey_de.svde_id) AS question_count')
            ->select('COUNT(DISTINCT lms_qn_user.qnu_id) AS submission_count')
            ->join('lms_cos', 'lms_cos.cos_id = lms_survey.cos_id', 'left')
            ->join('lms_survey_de', 'lms_survey_de.sv_id = lms_survey.sv_id AND lms_survey_de.svde_isDelete = 0', 'left')
            ->join('lms_qn_user', 'lms_qn_user.sv_id = lms_survey.sv_id AND lms_qn_user.qnu_status = 2', 'left')
            ->where('lms_survey.sv_isDelete', '0')
            ->groupBy('lms_survey.sv_id')
            ->orderBy('lms_survey.sv_id', 'DESC')
            ->limit($limit);

        if (! empty($filters['cos_id'])) {
            $builder->where('lms_survey.cos_id', (int) $filters['cos_id']);
        }

        if (($filters['status'] ?? '') !== '') {
            $builder->where('lms_survey.sv_status', (int) $filters['status']);
        }

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $builder->groupStart()
                ->like('lms_survey.sv_title_eng', $keyword)
                ->orLike('lms_survey.sv_title_th', $keyword)
                ->orLike('lms_survey.sv_title_jp', $keyword)
                ->orLike('lms_cos.ccode', $keyword)
                ->orLike('lms_cos.cname_eng', $keyword)
                ->orLike('lms_cos.cname_th', $keyword)
                ->groupEnd();
        }

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['title'] = $this->localized($row, $lang, 'sv_title');
            $row['course_title'] = $this->localized($row, $lang, 'cname');
            $row['period_label'] = $this->periodLabel((string) ($row['survey_open'] ?? ''), (string) ($row['survey_end'] ?? ''));
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

    public function surveyForEdit(int $surveyId, string $lang = 'english'): ?array
    {
        $survey = $this->db->table('lms_survey')
            ->select('lms_survey.*, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp')
            ->join('lms_cos', 'lms_cos.cos_id = lms_survey.cos_id', 'left')
            ->where('lms_survey.sv_id', $surveyId)
            ->where('lms_survey.sv_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $survey) {
            return null;
        }

        $survey['title'] = $this->localized($survey, $lang, 'sv_title');
        $survey['course_title'] = $this->localized($survey, $lang, 'cname');
        $survey['questions'] = $this->adminQuestions($surveyId, $lang);

        return $survey;
    }

    public function createSurvey(array $input, array $user): array
    {
        $payload = $this->surveyPayload($input);
        if (! $payload['ok']) {
            return $payload;
        }

        $now = date('Y-m-d H:i:s');
        $data = $payload['payload'] + [
            'qn_id' => 0,
            'sv_isDelete' => 0,
            'sv_createby' => (string) ($user['u_id'] ?? ''),
            'sv_createdate' => $now,
            'sv_modifiedby' => (string) ($user['u_id'] ?? ''),
            'sv_modifieddate' => $now,
        ];

        $this->db->table('lms_survey')->insert($data);
        $surveyId = (int) $this->db->insertID();
        $this->recordLog($user, 'survey_admin', 'Create survey: ' . $surveyId);

        return ['ok' => true, 'message' => 'Survey created.', 'survey_id' => $surveyId];
    }

    public function updateSurvey(int $surveyId, array $input, array $user): array
    {
        if (! $this->surveyForEdit($surveyId)) {
            return ['ok' => false, 'message' => 'Survey not found.'];
        }

        $payload = $this->surveyPayload($input);
        if (! $payload['ok']) {
            return $payload;
        }

        $data = $payload['payload'] + [
            'sv_modifiedby' => (string) ($user['u_id'] ?? ''),
            'sv_modifieddate' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('lms_survey')->where('sv_id', $surveyId)->update($data);
        $this->recordLog($user, 'survey_admin', 'Update survey: ' . $surveyId);

        return ['ok' => true, 'message' => 'Survey updated.', 'survey_id' => $surveyId];
    }

    public function setSurveyStatus(int $surveyId, int $status, array $user): array
    {
        if (! in_array($status, [0, 1], true)) {
            return ['ok' => false, 'message' => 'Invalid status.'];
        }

        if (! $this->surveyForEdit($surveyId)) {
            return ['ok' => false, 'message' => 'Survey not found.'];
        }

        $this->db->table('lms_survey')->where('sv_id', $surveyId)->update([
            'sv_status' => $status,
            'sv_modifiedby' => (string) ($user['u_id'] ?? ''),
            'sv_modifieddate' => date('Y-m-d H:i:s'),
        ]);
        $this->recordLog($user, 'survey_admin', 'Set survey status: ' . $surveyId . ' => ' . $status);

        return ['ok' => true, 'message' => 'Survey status updated.'];
    }

    public function createQuestion(int $surveyId, array $input, array $user): array
    {
        if (! $this->surveyForEdit($surveyId)) {
            return ['ok' => false, 'message' => 'Survey not found.'];
        }

        $payload = $this->questionPayload($input);
        if (! $payload['ok']) {
            return $payload;
        }

        $now = date('Y-m-d H:i:s');
        $data = $payload['payload'] + [
            'sv_id' => $surveyId,
            'svde_isDelete' => 0,
            'svde_createby' => (string) ($user['u_id'] ?? ''),
            'svde_createdate' => $now,
            'svde_modifiedby' => (string) ($user['u_id'] ?? ''),
            'svde_modifieddate' => $now,
        ];
        $this->db->table('lms_survey_de')->insert($data);
        $this->recordLog($user, 'survey_question', 'Create survey question for survey: ' . $surveyId);

        return ['ok' => true, 'message' => 'Survey question created.'];
    }

    public function updateQuestion(int $questionId, array $input, array $user): array
    {
        $question = $this->questionForEdit($questionId);
        if (! $question) {
            return ['ok' => false, 'message' => 'Question not found.'];
        }

        $payload = $this->questionPayload($input);
        if (! $payload['ok']) {
            return $payload + ['survey_id' => (int) $question['sv_id']];
        }

        $data = $payload['payload'] + [
            'svde_modifiedby' => (string) ($user['u_id'] ?? ''),
            'svde_modifieddate' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('lms_survey_de')->where('svde_id', $questionId)->update($data);
        $this->recordLog($user, 'survey_question', 'Update survey question: ' . $questionId);

        return ['ok' => true, 'message' => 'Survey question updated.', 'survey_id' => (int) $question['sv_id']];
    }

    public function setQuestionStatus(int $questionId, int $status, array $user): array
    {
        if (! in_array($status, [0, 1], true)) {
            return ['ok' => false, 'message' => 'Invalid status.'];
        }

        $question = $this->questionForEdit($questionId);
        if (! $question) {
            return ['ok' => false, 'message' => 'Question not found.', 'survey_id' => 0];
        }

        $this->db->table('lms_survey_de')->where('svde_id', $questionId)->update([
            'svde_status' => $status,
            'svde_modifiedby' => (string) ($user['u_id'] ?? ''),
            'svde_modifieddate' => date('Y-m-d H:i:s'),
        ]);
        $this->recordLog($user, 'survey_question', 'Set survey question status: ' . $questionId . ' => ' . $status);

        return ['ok' => true, 'message' => 'Question status updated.', 'survey_id' => (int) $question['sv_id']];
    }

    public function deleteQuestion(int $questionId, array $user): array
    {
        $question = $this->questionForEdit($questionId);
        if (! $question) {
            return ['ok' => false, 'message' => 'Question not found.', 'survey_id' => 0];
        }

        $this->db->table('lms_survey_de')->where('svde_id', $questionId)->update([
            'svde_isDelete' => 1,
            'svde_modifiedby' => (string) ($user['u_id'] ?? ''),
            'svde_modifieddate' => date('Y-m-d H:i:s'),
        ]);
        $this->recordLog($user, 'survey_question', 'Archive survey question: ' . $questionId);

        return ['ok' => true, 'message' => 'Question archived.', 'survey_id' => (int) $question['sv_id']];
    }

    public function report(int $surveyId, string $lang): ?array
    {
        $survey = $this->surveyForEdit($surveyId, $lang);
        if (! $survey) {
            return null;
        }

        $questions = $this->adminQuestions($surveyId, $lang);
        $summary = [];
        foreach ($questions as $question) {
            $summary[(int) $question['svde_id']] = [
                'question' => $question,
                'ratings' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
                'average' => 0.0,
                'responses' => 0,
            ];
        }

        $ratings = $this->db->table('lms_qn_user_de')
            ->select('lms_qn_user_de.svde_id, lms_qn_user_de.qnude_var, COUNT(*) AS total')
            ->join('lms_qn_user', 'lms_qn_user.qnu_id = lms_qn_user_de.qnu_id')
            ->where('lms_qn_user.sv_id', $surveyId)
            ->where('lms_qn_user.qnu_status', '2')
            ->groupBy('lms_qn_user_de.svde_id, lms_qn_user_de.qnude_var')
            ->get()
            ->getResultArray();

        foreach ($ratings as $rating) {
            $questionId = (int) $rating['svde_id'];
            $value = (int) $rating['qnude_var'];
            if (! isset($summary[$questionId]) || $value < 1 || $value > 5) {
                continue;
            }

            $count = (int) $rating['total'];
            $summary[$questionId]['ratings'][$value] = $count;
            $summary[$questionId]['responses'] += $count;
            $summary[$questionId]['average'] += $value * $count;
        }

        foreach ($summary as &$item) {
            $item['average'] = $item['responses'] > 0 ? round($item['average'] / $item['responses'], 2) : 0.0;
        }

        $submissions = $this->db->table('lms_qn_user')
            ->select('lms_qn_user.*, lms_emp.emp_c, lms_emp.fullname_th, lms_emp.fullname_en')
            ->join('lms_emp', 'lms_emp.emp_id = lms_qn_user.emp_id', 'left')
            ->where('lms_qn_user.sv_id', $surveyId)
            ->where('lms_qn_user.qnu_status', '2')
            ->orderBy('lms_qn_user.qnu_datetime', 'DESC')
            ->limit(300)
            ->get()
            ->getResultArray();

        return [
            'survey' => $survey,
            'summary' => array_values($summary),
            'submissions' => $submissions,
        ];
    }

    public function surveyDetail(int $surveyId, array $user, string $lang): ?array
    {
        $survey = $this->surveyRow($surveyId);
        if (! $survey) {
            return null;
        }

        (new CourseModel())->startCourse((int) $survey['cos_id'], $user, $lang);
        $enrollment = $this->enrollment((int) $survey['cos_id'], $user);
        if (! $enrollment) {
            return null;
        }

        $survey['title'] = $this->localized($survey, $lang, 'sv_title');
        $survey['explanation'] = $this->localized($survey, $lang, 'sv_explanation');
        $survey['questions'] = $this->questions($surveyId, $lang);
        $survey['enrollment'] = $enrollment;
        $survey['last_submission'] = $this->lastSubmission($surveyId, $user, (int) $enrollment['cosen_id']);

        return $survey;
    }

    public function submit(int $surveyId, array $answers, array $suggestions, string $overallSuggestion, array $user, string $lang): array
    {
        $survey = $this->surveyDetail($surveyId, $user, $lang);
        if (! $survey) {
            return ['ok' => false, 'message' => 'Survey not found.'];
        }

        if ($survey['questions'] === []) {
            return ['ok' => false, 'message' => 'This survey has no active questions.'];
        }

        foreach ($survey['questions'] as $question) {
            $value = (int) ($answers[$question['svde_id']] ?? 0);
            if ($value < 1 || $value > 5) {
                return ['ok' => false, 'message' => 'Please rate every survey question.'];
            }
        }

        $enrollment = $survey['enrollment'];
        $now = date('Y-m-d H:i:s');

        $this->db->transStart();
        $this->db->table('lms_qn_user')->insert([
            'emp_id' => $user['emp_id'] ?? null,
            'sv_id' => $surveyId,
            'qnu_suggestion' => $overallSuggestion,
            'qnu_datetime' => $now,
            'qnu_status' => 2,
            'cosen_id' => $enrollment['cosen_id'],
        ]);
        $submissionId = (int) $this->db->insertID();

        foreach ($survey['questions'] as $question) {
            $questionId = (int) $question['svde_id'];
            $this->db->table('lms_qn_user_de')->insert([
                'qnu_id' => $submissionId,
                'svde_id' => $questionId,
                'qnude_var' => (int) $answers[$questionId],
                'qnude_suggestion' => (string) ($suggestions[$questionId] ?? ''),
            ]);
        }

        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return ['ok' => false, 'message' => 'Survey submission failed.'];
        }

        $this->recordLog($user, 'survey', 'Submit survey: ' . $survey['title'] . ' (' . $surveyId . ')');
        $progress = (new ProgressModel())->recalculate((int) $survey['cos_id'], $user, $lang);

        return ['ok' => true, 'message' => 'Survey submitted.', 'submission_id' => $submissionId, 'progress' => $progress];
    }

    private function surveyRow(int $surveyId): ?array
    {
        $row = $this->db->table('lms_survey')
            ->where('sv_id', $surveyId)
            ->where('sv_status', '1')
            ->where('sv_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $row || ! $this->isInPeriod($row)) {
            return null;
        }

        return $row;
    }

    private function questions(int $surveyId, string $lang): array
    {
        $rows = $this->db->table('lms_survey_de')
            ->where('sv_id', $surveyId)
            ->where('svde_status', '1')
            ->where('svde_isDelete', '0')
            ->orderBy('svde_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['heading'] = $this->localized($row, $lang, 'svde_heading');
            $row['detail'] = $this->localized($row, $lang, 'svde_detail');
        }

        return $rows;
    }

    private function adminQuestions(int $surveyId, string $lang): array
    {
        $rows = $this->db->table('lms_survey_de')
            ->where('sv_id', $surveyId)
            ->where('svde_isDelete', '0')
            ->orderBy('svde_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['heading'] = $this->localized($row, $lang, 'svde_heading');
            $row['detail'] = $this->localized($row, $lang, 'svde_detail');
        }

        return $rows;
    }

    private function questionForEdit(int $questionId): ?array
    {
        $row = $this->db->table('lms_survey_de')
            ->where('svde_id', $questionId)
            ->where('svde_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function surveyPayload(array $input): array
    {
        $courseId = (int) ($input['cos_id'] ?? 0);
        $titleEng = trim((string) ($input['sv_title_eng'] ?? ''));
        $titleTh = trim((string) ($input['sv_title_th'] ?? $titleEng));
        if ($courseId <= 0 || $titleEng === '') {
            return ['ok' => false, 'message' => 'Course and English survey title are required.'];
        }

        $courseExists = $this->db->table('lms_cos')
            ->where('cos_id', $courseId)
            ->where('cos_isDelete', '0')
            ->countAllResults() > 0;
        if (! $courseExists) {
            return ['ok' => false, 'message' => 'Course not found.'];
        }

        $languages = $input['sv_lang'] ?? ['eng'];
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
                'sv_lang' => implode(',', $languages),
                'sv_title_th' => $titleTh,
                'sv_explanation_th' => trim((string) ($input['sv_explanation_th'] ?? '')),
                'sv_title_eng' => $titleEng,
                'sv_explanation_eng' => trim((string) ($input['sv_explanation_eng'] ?? '')),
                'sv_title_jp' => trim((string) ($input['sv_title_jp'] ?? '')),
                'sv_explanation_jp' => trim((string) ($input['sv_explanation_jp'] ?? '')),
                'sv_suggestion_status' => (int) ($input['sv_suggestion_status'] ?? 0),
                'sv_status' => (int) ($input['sv_status'] ?? 1),
                'survey_open' => $this->dateOrZero((string) ($input['survey_open'] ?? '')),
                'survey_end' => $this->dateOrZero((string) ($input['survey_end'] ?? '')),
            ],
        ];
    }

    private function questionPayload(array $input): array
    {
        $headingEng = trim((string) ($input['svde_heading_eng'] ?? ''));
        $headingTh = trim((string) ($input['svde_heading_th'] ?? $headingEng));
        if ($headingEng === '') {
            return ['ok' => false, 'message' => 'English question heading is required.'];
        }

        return [
            'ok' => true,
            'payload' => [
                'svde_heading_th' => $headingTh,
                'svde_detail_th' => trim((string) ($input['svde_detail_th'] ?? '')),
                'svde_heading_eng' => $headingEng,
                'svde_detail_eng' => trim((string) ($input['svde_detail_eng'] ?? '')),
                'svde_heading_jp' => trim((string) ($input['svde_heading_jp'] ?? '')),
                'svde_detail_jp' => trim((string) ($input['svde_detail_jp'] ?? '')),
                'svde_status' => (int) ($input['svde_status'] ?? 1),
            ],
        ];
    }

    private function dateOrZero(string $value): string
    {
        if (trim($value) === '') {
            return '0000-00-00 00:00:00';
        }

        $time = strtotime($value);
        return $time ? date('Y-m-d H:i:s', $time) : '0000-00-00 00:00:00';
    }

    private function periodLabel(string $start, string $end): string
    {
        if ($this->emptyDate($start) && $this->emptyDate($end)) {
            return 'Unlimited';
        }

        return ($this->emptyDate($start) ? 'Anytime' : substr($start, 0, 10))
            . ' - '
            . ($this->emptyDate($end) ? 'No end' : substr($end, 0, 10));
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

    private function lastSubmission(int $surveyId, array $user, int $enrollmentId): ?array
    {
        $submission = $this->db->table('lms_qn_user')
            ->where('sv_id', $surveyId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('cosen_id', $enrollmentId)
            ->where('qnu_status', '2')
            ->orderBy('qnu_id', 'DESC')
            ->get()
            ->getRowArray();

        if (! $submission) {
            return null;
        }

        $details = $this->db->table('lms_qn_user_de')
            ->where('qnu_id', $submission['qnu_id'])
            ->get()
            ->getResultArray();
        $submission['details'] = [];
        foreach ($details as $detail) {
            $submission['details'][(int) $detail['svde_id']] = $detail;
        }

        return $submission;
    }

    private function isInPeriod(array $row): bool
    {
        $start = (string) ($row['survey_open'] ?? '');
        $end = (string) ($row['survey_end'] ?? '');
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

    private function recordLog(array $actor, string $type, string $message): void
    {
        if (! $this->db->tableExists('lms_log')) {
            return;
        }

        $columns = array_flip($this->db->getFieldNames('lms_log'));
        $data = [];
        foreach ([
            'log_type' => $type,
            'log_action' => $message,
            'log_detail' => $message,
            'log_ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'log_createby' => (string) ($actor['u_id'] ?? ''),
            'log_createdate' => date('Y-m-d H:i:s'),
            'u_id' => $actor['u_id'] ?? null,
            'emp_id' => $actor['emp_id'] ?? null,
        ] as $field => $value) {
            if (isset($columns[$field])) {
                $data[$field] = $value;
            }
        }

        if ($data !== []) {
            $this->db->table('lms_log')->insert($data);
        }
    }
}
