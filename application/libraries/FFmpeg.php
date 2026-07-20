<?php
class FFmpeg {
    private $ffmpeg_path;

    public function __construct() {
        $CI =& get_instance();
        $this->ffmpeg_path = $CI->config->item('ffmpeg_path');
    }

    // Check if FFmpeg is available on the system
    private function isFFmpegInstalled() {
        $command = escapeshellcmd("{$this->ffmpeg_path} -version");
        exec($command, $output, $return_code);
        return $return_code === 0;
    }

    // Convert input video to MP4 with H.264 and AAC codec
    public function convertToMp4($input_file, $output_file) {
        if (!$this->isFFmpegInstalled()) {
            return [
                'output' => 'FFmpeg is not installed or not accessible.',
                'status' => 1
            ];
        }

        // Escape input and output file paths to prevent security risks
        $input_file = escapeshellarg($input_file);
        $output_file = escapeshellarg($output_file);
        
        // คำสั่ง FFmpeg แปลงเป็น MP4 (H.264) และจำกัดความละเอียดไม่เกิน 720p
        /*
        -vf 'scale=-2:720' → ปรับความสูงเป็น 720p โดยอัตราส่วนกว้างยาวไม่เพี้ยน
        -c:v libx264 → ใช้ H.264 Codec สำหรับวิดีโอ
        -crf 23 → กำหนดคุณภาพไฟล์ (ยิ่งต่ำยิ่งชัด ไฟล์ใหญ่ขึ้น)
        -preset fast → ความเร็วในการแปลง (สามารถเปลี่ยนเป็น ultrafast, medium, slow)
        -c:a aac -b:a 128k → บีบอัดเสียงเป็น AAC 128kbps
        */

        // FFmpeg conversion command
        $command = "{$this->ffmpeg_path} -i $input_file -vf \"scale=-2:720\" -c:v libx264 -crf 23 -preset fast -c:a aac -b:a 128k $output_file 2>&1";

        // Execute the command
        exec($command, $output, $return_code);

        // Return the command output and status code
        return [
            'output' => implode("\n", $output),
            'status' => $return_code
        ];
    }
}
