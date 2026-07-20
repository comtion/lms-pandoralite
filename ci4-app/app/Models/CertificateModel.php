<?php

namespace App\Models;

use CodeIgniter\Model;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class CertificateModel extends Model
{
    protected $returnType = 'array';

    public function certificates(array $user, string $lang, bool $all = false, int $limit = 200): array
    {
        $builder = $this->db->table('lms_certificate')
            ->select('lms_certificate.*, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp')
            ->select('lms_emp.fullname_th, lms_emp.fullname_en, lms_emp.emp_c')
            ->select('lms_company.com_name_th, lms_company.com_name_eng')
            ->join('lms_cos', 'lms_cos.cos_id = lms_certificate.cos_id', 'left')
            ->join('lms_emp', 'lms_emp.emp_id = lms_certificate.emp_id', 'left')
            ->join('lms_company', 'lms_company.com_id = lms_emp.com_id', 'left')
            ->orderBy('lms_certificate.cert_id', 'DESC')
            ->limit($limit);

        if (! $all) {
            $builder->where('lms_certificate.emp_id', $user['emp_id'] ?? 0);
        }

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['course_title'] = $this->localized($row, $lang, 'cname');
            $row['learner_name'] = $this->learnerName($row, $lang);
            $row['company_name'] = $this->localized($row, $lang, 'com_name');
            $row['file_exists'] = is_file($this->certificatePath((string) $row['cert_file']));
        }

        return $rows;
    }

    public function certificate(int $certificateId): ?array
    {
        $row = $this->db->table('lms_certificate')
            ->where('cert_id', $certificateId)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function ensureCertificate(int $courseId, int $employeeId, string $lang, bool $regenerate = false): ?array
    {
        $existing = $this->db->table('lms_certificate')
            ->where('cos_id', $courseId)
            ->where('emp_id', $employeeId)
            ->orderBy('cert_id', 'DESC')
            ->get()
            ->getRowArray();

        if ($existing && ! $regenerate && is_file($this->certificatePath((string) $existing['cert_file']))) {
            return $existing;
        }

        if ($existing && $regenerate && is_file($this->certificatePath((string) $existing['cert_file']))) {
            @unlink($this->certificatePath((string) $existing['cert_file']));
        }

        if ($regenerate) {
            $this->db->table('lms_certificate')
                ->where('cos_id', $courseId)
                ->where('emp_id', $employeeId)
                ->delete();
            $existing = null;
        }

        $data = $this->certificateData($courseId, $employeeId, $lang);
        if (! $data) {
            return $existing ?: null;
        }

        $directory = $this->certificateDirectory();
        if (! is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }

        $fileName = 'Certificate_' . $courseId . '_' . $employeeId . '_' . date('YmdHis') . '.pdf';
        $filePath = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $fileName;
        $this->renderCertificatePdf($filePath, $data);

        $certDate = $this->emptyDate((string) ($data['cosen_finishtime'] ?? '')) ? date('Y-m-d') : date('Y-m-d', strtotime($data['cosen_finishtime']));
        $row = [
            'cos_id' => $courseId,
            'emp_id' => $employeeId,
            'cert_file' => $fileName,
            'cert_date' => $certDate,
            'cert_createtime' => date('Y-m-d H:i:s'),
        ];

        if ($existing && ! $regenerate) {
            $this->db->table('lms_certificate')->where('cert_id', $existing['cert_id'])->update($row);
            $row['cert_id'] = (int) $existing['cert_id'];
        } else {
            $this->db->table('lms_certificate')->insert($row);
            $row['cert_id'] = (int) $this->db->insertID();
        }

        return $row;
    }

    public function certificatePath(string $fileName): string
    {
        $safeName = basename($fileName);
        foreach ([
            FCPATH . 'uploads/certificate/' . $safeName,
            ROOTPATH . '../uploads/certificate/' . $safeName,
            '/var/www/html/imat8.1/uploads/certificate/' . $safeName,
        ] as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return FCPATH . 'uploads/certificate/' . $safeName;
    }

    private function certificateDirectory(): string
    {
        foreach ([
            FCPATH . 'uploads/certificate',
            '/var/www/html/imat8.1/uploads/certificate',
            ROOTPATH . '../uploads/certificate',
        ] as $directory) {
            if (is_dir($directory) && is_writable($directory)) {
                return $directory;
            }
        }

        return FCPATH . 'uploads/certificate';
    }

    private function certificateData(int $courseId, int $employeeId, string $lang): ?array
    {
        $row = $this->db->table('lms_cos_enroll')
            ->select('lms_cos_enroll.*, lms_cos.ccode, lms_cos.cname_th, lms_cos.cname_eng, lms_cos.cname_jp')
            ->select('lms_emp.fullname_th, lms_emp.fullname_en, lms_emp.emp_c')
            ->select('lms_company.com_name_th, lms_company.com_name_eng')
            ->join('lms_cos', 'lms_cos.cos_id = lms_cos_enroll.cos_id', 'left')
            ->join('lms_emp', 'lms_emp.emp_id = lms_cos_enroll.emp_id', 'left')
            ->join('lms_company', 'lms_company.com_id = lms_emp.com_id', 'left')
            ->where('lms_cos_enroll.cos_id', $courseId)
            ->where('lms_cos_enroll.emp_id', $employeeId)
            ->where('lms_cos_enroll.cosen_status', '1')
            ->where('lms_cos_enroll.cosen_status_sub', '1')
            ->where('lms_cos_enroll.cosen_isDelete', '0')
            ->orderBy('lms_cos_enroll.cosen_id', 'DESC')
            ->get()
            ->getRowArray();

        if (! $row) {
            return null;
        }

        $row['course_title'] = $this->localized($row, $lang, 'cname');
        $row['learner_name'] = $this->learnerName($row, $lang);
        $row['company_name'] = $this->localized($row, $lang, 'com_name');
        $row['issued_text'] = $lang === 'thai' ? 'Issued on ' . $this->thaiDate((string) $row['cosen_finishtime']) : 'Issued on ' . date('d F Y', strtotime((string) $row['cosen_finishtime']));

        return $row;
    }

    private function renderCertificatePdf(string $filePath, array $data): void
    {
        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];
        $mpdf = new Mpdf([
            'orientation' => 'L',
            'format' => 'A4',
            'margin_left' => 18,
            'margin_right' => 18,
            'margin_top' => 18,
            'margin_bottom' => 18,
            'tempDir' => WRITEPATH . 'cache',
            'fontDir' => array_merge($fontDirs, [
                FCPATH . 'assets/tfpdf/font',
                ROOTPATH . '../assets/tfpdf/font',
            ]),
            'fontdata' => $fontData + [
                'freesans' => ['R' => 'FreeSans.ttf', 'B' => 'FreeSansBold.ttf'],
                'cordia' => ['R' => 'cordia.ttf'],
            ],
            'default_font' => 'freesans',
        ]);

        $background = $this->certificateBackground((string) ($data['cosen_lang'] ?? 'english'));
        $backgroundCss = $background ? "background-image:url('" . str_replace('\\', '/', $background) . "');background-image-resize:6;" : '';
        $html = '<div style="' . $backgroundCss . 'width:100%;height:174mm;text-align:center;border:6px solid #e71921;padding:42mm 16mm 18mm;box-sizing:border-box;">'
            . '<div style="font-size:18pt;color:#e71921;font-weight:bold;letter-spacing:2px;">CERTIFICATE OF COMPLETION</div>'
            . '<div style="font-size:11pt;color:#555;margin-top:7mm;">This certificate is proudly presented to</div>'
            . '<div style="font-size:32pt;font-weight:bold;color:#111;margin-top:7mm;">' . $this->html($data['learner_name']) . '</div>'
            . '<div style="font-size:14pt;color:#555;margin-top:4mm;">' . $this->html($data['company_name']) . '</div>'
            . '<div style="font-size:12pt;color:#555;margin-top:10mm;">for successfully completing</div>'
            . '<div style="font-size:21pt;font-weight:bold;color:#111;margin-top:4mm;">' . $this->html($data['course_title']) . '</div>'
            . '<div style="font-size:12pt;color:#555;margin-top:8mm;">Score ' . $this->html((string) $data['cosen_score_per']) . '% | Grade ' . $this->html((string) $data['cosen_grade']) . '</div>'
            . '<div style="font-size:12pt;color:#555;margin-top:9mm;">' . $this->html($data['issued_text']) . '</div>'
            . '</div>';

        $mpdf->WriteHTML($html);
        $mpdf->Output($filePath, 'F');
    }

    private function certificateBackground(string $lang): string
    {
        $file = $lang === 'thai' ? 'certificate_original_th.jpg' : 'certificate_original.jpg';
        foreach ([
            FCPATH . 'uploads/certificate/' . $file,
            '/var/www/html/imat8.1/uploads/certificate/' . $file,
            ROOTPATH . '../uploads/certificate/' . $file,
        ] as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return '';
    }

    private function learnerName(array $row, string $lang): string
    {
        $order = $lang === 'thai' ? ['fullname_th', 'fullname_en'] : ['fullname_en', 'fullname_th'];
        foreach ($order as $key) {
            $value = trim((string) ($row[$key] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '-';
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

    private function thaiDate(string $date): string
    {
        if ($this->emptyDate($date)) {
            return date('d F Y');
        }

        return date('d/m/', strtotime($date)) . ((int) date('Y', strtotime($date)) + 543);
    }

    private function emptyDate(string $value): bool
    {
        return $value === '' || str_starts_with($value, '0000-00-00');
    }

    private function html(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
