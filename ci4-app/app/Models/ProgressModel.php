<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgressModel extends Model
{
    protected $returnType = 'array';

    public function recalculate(int $courseId, array $user, string $lang): array
    {
        $enrollment = $this->enrollment($courseId, (int) ($user['emp_id'] ?? 0));
        if (! $enrollment) {
            return ['ok' => false, 'message' => 'Enrollment not found.'];
        }

        $course = $this->db->table('lms_cos')
            ->where('cos_id', $courseId)
            ->where('cos_isDelete', '0')
            ->get()
            ->getRowArray();
        if (! $course) {
            return ['ok' => false, 'message' => 'Course not found.'];
        }

        $lessonTotal = $this->db->table('lms_les')
            ->where('cos_id', $courseId)
            ->where('les_status', '1')
            ->where('les_isDelete', '0')
            ->countAllResults();
        $lessonDone = $this->db->table('lms_les_tc')
            ->where('cosen_id', $enrollment['cosen_id'])
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('learn_status', '2')
            ->countAllResults();

        $quizzes = $this->db->table('lms_qiz')
            ->where('cos_id', $courseId)
            ->where('quiz_show', '1')
            ->where('quiz_status', '1')
            ->where('quiz_isDelete', '0')
            ->get()
            ->getResultArray();
        $quizDone = 0;
        $score = 0.0;
        $total = 0.0;
        foreach ($quizzes as $quiz) {
            $attempt = $this->db->table('lms_qiz_tc')
                ->where('qiz_id', $quiz['qiz_id'])
                ->where('cosen_id', $enrollment['cosen_id'])
                ->where('qiz_status', '3')
                ->where('qiztc_isDelete', '0')
                ->orderBy('qiztc_id', 'DESC')
                ->get()
                ->getRowArray();
            if (! $attempt) {
                continue;
            }

            $quizDone++;
            $score += (float) ($attempt['sum_score'] ?? 0);
            $questionTotal = $this->db->table('lms_ques')
                ->selectSum('ques_score', 'total_score')
                ->where('qiz_id', $quiz['qiz_id'])
                ->where('ques_status', '1')
                ->where('ques_isDelete', '0')
                ->get()
                ->getRowArray();
            $total += (float) ($questionTotal['total_score'] ?? 0);
        }

        $surveyRequired = (string) ($course['is_survey_required'] ?? '0') === '1';
        $surveyTotal = $surveyRequired ? $this->db->table('lms_survey')
            ->where('cos_id', $courseId)
            ->where('sv_status', '1')
            ->where('sv_isDelete', '0')
            ->countAllResults() : 0;
        $surveyDone = $surveyTotal > 0 ? $this->db->table('lms_qn_user')
            ->where('cosen_id', $enrollment['cosen_id'])
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->whereIn('qnu_status', [1, 2])
            ->countAllResults() : 0;

        $checks = 0;
        $passedChecks = 0;
        if ($lessonTotal > 0) {
            $checks++;
            if ($lessonDone >= $lessonTotal) {
                $passedChecks++;
            }
        }
        if ($quizzes !== []) {
            $checks++;
            if ($quizDone >= count($quizzes)) {
                $passedChecks++;
            }
        }
        if ($surveyTotal > 0) {
            $checks++;
            if ($surveyDone >= $surveyTotal) {
                $passedChecks++;
            }
        }

        $percent = $total > 0 ? round(($score / $total) * 100, 2) : ($checks > 0 && $checks === $passedChecks ? 100.0 : 0.0);
        $grade = $this->grade($course, $courseId, $percent);
        $completed = $checks > 0 && $checks === $passedChecks;
        if ((float) ($course['goal_score'] ?? 0) > 0 && $total > 0) {
            $completed = $completed && $percent >= (float) $course['goal_score'];
        }

        $now = date('Y-m-d H:i:s');
        $finish = $completed
            ? ($this->emptyDate((string) ($enrollment['cosen_finishtime'] ?? '')) ? $now : $enrollment['cosen_finishtime'])
            : '0000-00-00 00:00:00';

        $this->db->table('lms_cos_enroll')
            ->where('cosen_id', $enrollment['cosen_id'])
            ->update([
                'cosen_score' => $score,
                'cosen_score_per' => $percent,
                'cosen_grade' => $grade,
                'cosen_status_sub' => $completed ? 1 : 2,
                'cosen_finishtime' => $finish,
                'cosen_modifiedby' => (string) ($user['u_id'] ?? ''),
                'cosen_modifieddate' => $now,
            ]);

        $certificate = null;
        if ($completed && $this->certificateAllowed($courseId, $percent)) {
            $certificate = (new CertificateModel())->ensureCertificate($courseId, (int) ($user['emp_id'] ?? 0), $lang, false);
        }

        return [
            'ok' => true,
            'completed' => $completed,
            'score' => $score,
            'total' => $total,
            'percent' => $percent,
            'grade' => $grade,
            'certificate' => $certificate,
        ];
    }

    private function enrollment(int $courseId, int $employeeId): ?array
    {
        $row = $this->db->table('lms_cos_enroll')
            ->where('cos_id', $courseId)
            ->where('emp_id', $employeeId)
            ->where('cosen_isDelete', '0')
            ->orderBy('cosen_id', 'DESC')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function grade(array $course, int $courseId, float $percent): string
    {
        $rule = $this->db->table('lms_cug')->where('course_id', $courseId)->get()->getRowArray();
        if (! $rule) {
            return $percent >= (float) ($course['goal_score'] ?? 0) ? 'P' : 'F';
        }

        if ((string) ($course['cos_typegrading'] ?? '0') === '1') {
            if ($percent >= (float) $rule['mina']) {
                return 'A';
            }
            if ($percent >= (float) $rule['minb']) {
                return 'B';
            }
            if ($percent >= (float) $rule['minc']) {
                return 'C';
            }
            if ($percent >= (float) $rule['mind']) {
                return 'D';
            }
            return 'F';
        }

        return $percent >= (float) $rule['mina'] ? 'P' : 'F';
    }

    private function certificateAllowed(int $courseId, float $percent): bool
    {
        $badge = $this->db->table('lms_bad')->where('courses_id', $courseId)->get()->getRowArray();
        if (! $badge) {
            return true;
        }

        $rule = $this->db->table('lms_cug')->where('course_id', $courseId)->get()->getRowArray();
        if (! $rule) {
            return true;
        }

        $condition = (string) ($badge['badges_condition'] ?? 'P');
        $threshold = match ($condition) {
            'A', 'P', 'F' => (float) $rule['mina'],
            'B' => (float) $rule['minb'],
            'C' => (float) $rule['minc'],
            'D' => (float) $rule['mind'],
            default => 0.0,
        };

        return $percent >= $threshold;
    }

    private function emptyDate(string $value): bool
    {
        return $value === '' || str_starts_with($value, '0000-00-00');
    }
}
