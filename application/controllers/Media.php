<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends CI_Controller
{
    public function file()
    {
        $relativePath = $this->input->get('path', true);
        $defaultPath = $this->input->get('default', true);

        $filePath = $this->resolvePublicPath($relativePath);
        if ($filePath === null) {
            $filePath = $this->resolvePublicPath($defaultPath);
        }

        if ($filePath === null) {
            show_404();
            return;
        }

        $mimeType = function_exists('mime_content_type') ? mime_content_type($filePath) : null;
        if (!$mimeType) {
            $mimeType = 'application/octet-stream';
        }

        $this->output->set_content_type($mimeType);
        $this->output->set_header('Content-Length: ' . filesize($filePath));
        $this->output->set_header('Cache-Control: public, max-age=3600');
        readfile($filePath);
    }

    private function resolvePublicPath($relativePath)
    {
        $relativePath = ltrim(str_replace('\\', '/', (string) $relativePath), '/');
        if ($relativePath === '') {
            return null;
        }

        if (
            strpos($relativePath, '../') !== false ||
            strpos($relativePath, '..\\') !== false ||
            preg_match('/^[A-Za-z]:/', $relativePath)
        ) {
            return null;
        }

        $fullPath = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (!is_file($fullPath)) {
            return null;
        }

        $realPath = realpath($fullPath);
        $publicRoot = realpath(FCPATH);
        if ($realPath === false || $publicRoot === false || strpos($realPath, $publicRoot) !== 0) {
            return null;
        }

        return $realPath;
    }
}
