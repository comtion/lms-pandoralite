<?php

namespace App\Models;

use CodeIgniter\Model;

class ScormModel extends Model
{
    protected $returnType = 'array';

    public function package(int $scormId): ?array
    {
        $row = $this->db->table('lms_scm')
            ->select('lms_scm.*, lms_les.cos_id, lms_les.les_id, lms_les.les_name_eng, lms_les.les_name_th')
            ->join('lms_les', 'lms_les.les_id = lms_scm.lessons_id')
            ->where('lms_scm.id', $scormId)
            ->where('lms_les.les_isDelete', '0')
            ->get()
            ->getRowArray();

        if (! $row) {
            return null;
        }

        $row['launch_url'] = $this->launchUrl((string) $row['path']);
        return $row;
    }

    public function initialValues(int $scormId, array $user, mixed $name = null): array
    {
        $package = $this->package($scormId);
        if (! $package) {
            return [];
        }

        $logId = $this->ensureLogId((int) $package['cos_id'], $user);
        $rows = $this->db->table('lms_scm_val')
            ->where('scm_id', $scormId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('id_log', $logId)
            ->get()
            ->getResultArray();

        $values = [
            'cmi.core.lesson_status' => 'incomplete',
            'cmi.core.score.raw' => '',
            'cmi.core.score.max' => '',
            'cmi.core.score.min' => '',
            'cmi.suspend_data' => '',
            'cmi.core.entry' => '',
            'cmi.core.exit' => '',
            'cmi.core.student_id' => (string) ($user['emp_id'] ?? ''),
            'cmi.core.student_name' => $this->studentName($name),
        ];

        foreach ($rows as $row) {
            $values[$this->normalizeVarName((string) $row['var_name'])] = (string) $row['var_value'];
        }

        if (($values['cmi.core.exit'] ?? '') === 'suspend') {
            $values['cmi.core.entry'] = 'resume';
        }

        return $values;
    }

    public function saveValues(int $scormId, array $values, array $user): array
    {
        $package = $this->package($scormId);
        if (! $package) {
            return ['ok' => false, 'message' => 'SCORM package not found.'];
        }

        $logId = $this->ensureLogId((int) $package['cos_id'], $user);
        if ($logId <= 0) {
            return ['ok' => false, 'message' => 'Enrollment log is not available.'];
        }

        $clean = [];
        foreach ($values as $name => $value) {
            $name = $this->normalizeVarName((string) $name);
            if (! $this->allowedVar($name)) {
                continue;
            }
            $clean[$name] = mb_substr((string) $value, 0, 4096);
        }

        foreach ($clean as $name => $value) {
            $exists = $this->db->table('lms_scm_val')
                ->where('scm_id', $scormId)
                ->where('emp_id', $user['emp_id'] ?? 0)
                ->where('id_log', $logId)
                ->where('var_name', $this->dbVarName($name))
                ->get()
                ->getRowArray();

            $payload = [
                'id_log' => $logId,
                'scm_id' => $scormId,
                'emp_id' => $user['emp_id'] ?? null,
                'var_name' => $this->dbVarName($name),
                'var_value' => $value,
            ];

            if ($exists) {
                $this->db->table('lms_scm_val')->where('id', $exists['id'])->update($payload);
            } else {
                $this->db->table('lms_scm_val')->insert($payload);
            }
        }

        $status = strtolower((string) ($clean['cmi.core.lesson_status'] ?? $clean['cmi.completion_status'] ?? ''));
        if (in_array($status, ['completed', 'passed', 'failed', 'incomplete'], true)) {
            $this->syncLessonTracking((int) $package['les_id'], (int) $logId, $status, $user);
        }

        return ['ok' => true, 'message' => 'SCORM data saved.'];
    }

    public function launchUrl(string $path): string
    {
        $path = trim($path, '/');
        if ($path === '') {
            return '';
        }

        $base = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'scorm' . DIRECTORY_SEPARATOR;
        $dir = $base . str_replace('/', DIRECTORY_SEPARATOR, $path);
        if (is_file($dir)) {
            return site_url('uploads/scorm/' . $path);
        }

        $candidates = $this->launchCandidates($dir);
        foreach ($candidates as $candidate) {
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $path . '/' . $candidate);
            return site_url('uploads/scorm/' . ltrim($relative, '/'));
        }

        return site_url('uploads/scorm/' . $path);
    }

    private function launchCandidates(string $dir): array
    {
        $manifest = $dir . DIRECTORY_SEPARATOR . 'imsmanifest.xml';
        if (is_file($manifest)) {
            $launch = $this->launchFromManifest($manifest);
            if ($launch !== '' && is_file($dir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $launch))) {
                return [$launch];
            }
        }

        $fallback = ['index_lms.html', 'index_scorm.html', 'index.html', 'story.html', 'player.html'];
        return array_values(array_filter($fallback, fn ($file) => is_file($dir . DIRECTORY_SEPARATOR . $file)));
    }

    private function launchFromManifest(string $manifest): string
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($manifest);
        if (! $xml) {
            return '';
        }

        foreach ($xml->resources->resource ?? [] as $resource) {
            $attrs = $resource->attributes();
            $href = trim((string) ($attrs['href'] ?? ''));
            if ($href !== '') {
                return $href;
            }
        }

        $resources = $xml->xpath('//*[local-name()="resource"]') ?: [];
        foreach ($resources as $resource) {
            $attrs = $resource->attributes();
            $href = trim((string) ($attrs['href'] ?? ''));
            if ($href !== '') {
                return $href;
            }
        }

        return '';
    }

    private function ensureLogId(int $courseId, array $user): int
    {
        $enrollment = $this->db->table('lms_cos_enroll')
            ->where('cos_id', $courseId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('cosen_isDelete', '0')
            ->orderBy('cosen_id', 'DESC')
            ->get()
            ->getRowArray();

        if (! $enrollment) {
            (new CourseModel())->startCourse($courseId, $user, 'english');
            $enrollment = $this->db->table('lms_cos_enroll')
                ->where('cos_id', $courseId)
                ->where('emp_id', $user['emp_id'] ?? 0)
                ->where('cosen_isDelete', '0')
                ->orderBy('cosen_id', 'DESC')
                ->get()
                ->getRowArray();
        }

        if (! $enrollment) {
            return 0;
        }

        $log = $this->db->table('lms_log_enroll')
            ->where('cosen_id', $enrollment['cosen_id'])
            ->get()
            ->getRowArray();

        if ($log) {
            return (int) $log['id_log'];
        }

        $this->db->table('lms_log_enroll')->insert(['cosen_id' => $enrollment['cosen_id']]);
        return (int) $this->db->insertID();
    }

    private function syncLessonTracking(int $lessonId, int $logId, string $status, array $user): void
    {
        if ($logId <= 0) {
            return;
        }

        $log = $this->db->table('lms_log_enroll')->where('id_log', $logId)->get()->getRowArray();
        if (! $log) {
            return;
        }

        $learnStatus = match ($status) {
            'completed', 'passed' => '2',
            'failed' => '3',
            default => '1',
        };

        $existing = $this->db->table('lms_les_tc')
            ->where('les_id', $lessonId)
            ->where('emp_id', $user['emp_id'] ?? 0)
            ->where('cosen_id', $log['cosen_id'])
            ->get()
            ->getRowArray();

        $payload = [
            'cosen_id' => $log['cosen_id'],
            'emp_id' => $user['emp_id'] ?? null,
            'les_id' => $lessonId,
            'learn_status' => $learnStatus,
        ];

        if ($existing) {
            $this->db->table('lms_les_tc')->where('lestc_id', $existing['lestc_id'])->update(['learn_status' => $learnStatus]);
        } else {
            $this->db->table('lms_les_tc')->insert($payload);
        }
    }

    private function normalizeVarName(string $name): string
    {
        $name = str_replace('_cmi', 'cmi', $name);
        return str_replace(['cmi_core_', 'cmi.core.score_'], ['cmi.core.', 'cmi.core.score.'], $name);
    }

    private function dbVarName(string $name): string
    {
        if (str_starts_with($name, 'cmi.core.score.')) {
            return str_replace('cmi.core.score.', 'cmi_core_score_', $name);
        }

        if (str_starts_with($name, 'cmi.core.')) {
            return str_replace('cmi.core.', 'cmi_core_', $name);
        }

        if (str_starts_with($name, 'cmi.')) {
            return str_replace('.', '_', $name);
        }

        return $name;
    }

    private function allowedVar(string $name): bool
    {
        return str_starts_with($name, 'cmi.') || str_starts_with($name, 'adl.') || $name === 'scoid' || $name === 'attempt';
    }

    private function studentName(mixed $name): string
    {
        if (is_string($name)) {
            return $name;
        }

        if (! is_array($name)) {
            return '';
        }

        return (string) ($name['fullname_en'] ?? $name['fullname_th'] ?? $name['name'] ?? '');
    }
}
