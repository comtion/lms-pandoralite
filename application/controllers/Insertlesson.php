<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Insertlesson extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Fetchdata_model', 'fetch', false);
        $this->load->model('Function_query_model', 'func_query', false);
        $this->load->model('Course_model', 'course', false);
        $this->load->model('Log_model', 'lg', false);
    }

    private function reArrayFiles($file)
    {
        $file_ary = array();
        $file_count = countArray($file['name']);
        $file_key = array_keys($file);
    
        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_key as $val) {
            $file_ary[$i][$val] = $file[$val][$i];
            }
        }
        return $file_ary;
    }

    private function getYoutubeEmbedUrl($url)
    {
  
        $urlParts   = explode('/', $url);
        $vidid      = explode('&', str_replace('watch?v=', '', end($urlParts)));
    
        return '//www.youtube.com/embed/' . $vidid[0];
    }

    public function index()
    {
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
        $this->lang->load($lang, $lang);
        date_default_timezone_set("Asia/Bangkok");
        $sess = $this->session->userdata("user");
        $emp_c = $sess['emp_c'];
        $this->fetch->loadDB();
        $output = array();
        if (isset($_REQUEST) && !empty($sess['emp_c'])) {
    
            function getContentUrl($url)
            {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);    // Follows redirect responses
                // gets the file content, trigger error if false
                $file = curl_exec($ch);
                if ($file === false) trigger_error(curl_error($ch));
                curl_close($ch);
                return $file;
            }
    
            function emptyDir($dir)
            {
                if (is_dir($dir)) {
                    $scn = scandir($dir);
                    foreach ($scn as $files) {
                        if ($files !== '.') {
                            if ($files !== '..') {
                                if (!is_dir($dir . '/' . $files)) {
                                    audit_unlink($dir . '/' . $files);
                                } else {
                                    emptyDir($dir . '/' . $files);
                                    rmdir($dir . '/' . $files);
                                }
                            }
                        }
                    }
                }
            }
            $course_id_lesson = isset($_REQUEST['course_id_lesson']) ? $_REQUEST['course_id_lesson'] : '';
            $les_lang = isset($_REQUEST['les_lang']) ? $_REQUEST['les_lang'] : '';
            $les_name_th = isset($_REQUEST['les_name_th']) ? $_REQUEST['les_name_th'] : '';
            $les_info_th = isset($_REQUEST['les_info_th']) ? $_REQUEST['les_info_th'] : '';
            $les_name_eng = isset($_REQUEST['les_name_eng']) ? $_REQUEST['les_name_eng'] : '';
            $les_info_eng = isset($_REQUEST['les_info_eng']) ? $_REQUEST['les_info_eng'] : '';
            $les_name_jp = isset($_REQUEST['les_name_jp']) ? $_REQUEST['les_name_jp'] : '';
            $les_info_jp = isset($_REQUEST['les_info_jp']) ? $_REQUEST['les_info_jp'] : '';
            $time_start = isset($_REQUEST['time_start_les']) ? $_REQUEST['time_start_les'] : '00:00:00';
            $time_end = isset($_REQUEST['time_end_les']) ? $_REQUEST['time_end_les'] : '00:00:00';
            $date_start = isset($_REQUEST['date_start_les_var']) && $_REQUEST['date_start_les_var'] != "" ? $_REQUEST['date_start_les_var'] . " " . $time_start : '0000-00-00 00:00:00';
            $date_end = isset($_REQUEST['date_end_les_var']) && $_REQUEST['date_end_les_var'] != "" ? $_REQUEST['date_end_les_var'] . " " . $time_end : '0000-00-00 00:00:00';
            $status_les = isset($_REQUEST['status_les']) ? $_REQUEST['status_les'] : '0';
            $les_type = isset($_REQUEST['les_type']) ? $_REQUEST['les_type'] : '1';
            $scm_type = isset($_REQUEST['scm_type']) ? $_REQUEST['scm_type'] : '0';
            $data = array(
                'cos_id' => $course_id_lesson,
                'les_lang' => $les_lang,
                'les_name_th' => $les_name_th,
                'les_info_th' => $les_info_th,
                'les_name_eng' => $les_name_eng,
                'les_info_eng' => $les_info_eng,
                'les_name_jp' => $les_name_jp,
                'les_info_jp' => $les_info_jp,
                'les_type' => $les_type,
                'scm_type' => $scm_type,
                'time_start' => $date_start,
                'time_end' => $date_end,
                'les_status' => $status_les,
                'les_modifiedby' => $sess['u_id'],
                'les_modifieddate' => date('Y-m-d H:i')
            );
            $arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
            $this->db->where('cos_id', $course_id_lesson);
            $this->db->update('lms_cos', $arr_update);
    
            $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $course_id_lesson . '"');
            $cos_lang = explode(',', $fetch_cos['cos_lang']);
            $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
            $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
            $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
            $cname = "";
            $lesname = "";
            if ($lang == "thai") {
                if ($fetch_cos['isTH'] == "1") {
                    $cname = $fetch_cos['cname_th'];
                    $lesname = $les_name_th;
                } else {
                    if ($cname == "") {
                    $cname = $fetch_cos['cname_eng'];
                    }
                    if ($cname == "") {
                    $cname = $fetch_cos['cname_jp'];
                    }
                    if ($lesname == "") {
                    $lesname = $les_name_eng;
                    }
                    if ($lesname == "") {
                    $lesname = $les_name_jp;
                    }
                }
            } elseif ($lang == "english") {
                if ($fetch_cos['isENG'] == "1") {
                    $cname = $fetch_cos['cname_eng'];
                    $lesname = $les_name_eng;
                } else {
                    if ($cname == "") {
                    $cname = $fetch_cos['cname_th'];
                    }
                    if ($cname == "") {
                    $cname = $fetch_cos['cname_jp'];
                    }
                    if ($lesname == "") {
                    $lesname = $les_name_th;
                    }
                    if ($lesname == "") {
                    $lesname = $les_name_jp;
                    }
                }
            } else {
                if ($fetch_cos['isJP'] == "1") {
                    $cname = $fetch_cos['cname_jp'];
                    $lesname = $les_name_jp;
                } else {
                    if ($cname == "") {
                    $cname = $fetch_cos['cname_eng'];
                    }
                    if ($cname == "") {
                    $cname = $fetch_cos['cname_th'];
                    }
                    if ($lesname == "") {
                    $lesname = $les_name_eng;
                    }
                    if ($lesname == "") {
                    $lesname = $les_name_th;
                    }
                }
            }
            $error_message = array();
            if ($_REQUEST['operation_lesson'] == "Add") {
                $data['les_createby'] = $sess['u_id'];
                $data['les_createdate'] = date('Y-m-d H:i');
                $this->db->insert('lms_les', $data);
                $lesson_id = $this->db->insert_id();
        
                $this->lg->record('lesson', 'Create lesson: ' . $lesname . '(' . $lesson_id . ') of course: ' . $cname . '(' . $course_id_lesson . ')' . '');
                if ($les_type == "1") {
                    if ($_REQUEST['type_media'] == "1") {
                        if ($_REQUEST['url_media'] != "") {
                            $arrurl = explode(",", $_REQUEST['url_media']);
                            for ($num_url = 0; $num_url < countArray($arrurl); $num_url++) {
                            $url = $this->getYoutubeEmbedUrl($arrurl[$num_url]);
                            $arr_youtube =  explode("//www.youtube.com/embed/", $url);
                            $id_yt = isset($arr_youtube[1]) ? str_replace(array("\n", "\r"), '', $arr_youtube[1]) : "";
                            //$input = 'https://img.youtube.com/vi/'.$id_youtube[1].'/hqdefault.jpg';
                            $dirimg = ROOT_DIR . 'uploads/thumbnail/';            // directory in which the image will be saved
                            $localfile = 'thumbnail_' . date('dmYHis') . $num_url . '.jpg';         // set image name the same as the file name of the source
                            // create the file with the image on the server
                            $varyoutube =  json_decode(getContentUrl('http://www.youtube.com/oembed?format=json&url=https://www.youtube.com/watch?v=' . $id_yt), true);
                            if (isset($varyoutube['thumbnail_url']) && $varyoutube['thumbnail_url'] != "") {
            
                                $r = file_put_contents($dirimg . $localfile, getContentUrl($varyoutube['thumbnail_url']));
                                /*if(getContentUrl($input)!=""){
                                $r = file_put_contents($dirimg.$localfile, getContentUrl($input));*/
                                if (!$r) {
                                $localfile = "default_cover.jpg";
                                }
                            } else {
                                $localfile = "default_cover.jpg";
                            }
                            /*$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id_yt)?file_get_contents("http://youtube.com/get_video_info?video_id=".$id_yt):"";
                                
                                parse_str($content, $ytarr);*/
                            $title = isset($ytarr['title']) ? $ytarr['title'] : "";
                            $each = array(
                                'lessons_id' => $lesson_id,
                                'med_name_th' => $title,
                                'med_name_eng' => $title,
                                'med_name_jp' => $title,
                                'thumbnail_med' => $localfile,
                                'type' => 'url',
                                'video' => $url
                            );
                            $this->course->insert_data_media($each, $lesson_id, 'url', $url);
                            $this->lg->record('lesson', 'Create URL YOUTUBE: ' . $url . ' of Lesson: ' . $lesname . '(' . $lesson_id . ')');
                            }
                        }
                    } elseif ($_REQUEST['type_media'] == "2") {
                        if (!empty($_FILES['media_file'])) {
                            $thumbnail = "";
                            // อัปโหลด thumbnail
                            if (!empty($_FILES['thumbnail_med'])) {
                                if ($_FILES['thumbnail_med']['tmp_name'] != "") {
                                    $thumbnail = 'thumbnail_' . date('dmYHis') . '.jpg';
                                    if (!audit_move_uploaded_file($_FILES['thumbnail_med']['tmp_name'], ROOT_DIR . "uploads/thumbnail/" . $thumbnail)) {
                                        $thumbnail = "";
                                    }
                                }
                            }

                            // ตั้งชื่อไฟล์ใหม่
                            $newname = 'MediaLesson_' . date('dmYHis') . '.mp4';
                            $uploaded_video_path = ROOT_DIR . "uploads/media/" . $newname;

                            // อัปโหลดไฟล์วิดีโอ
                            if (audit_move_uploaded_file($_FILES['media_file']['tmp_name'], $uploaded_video_path)) {
                                // เรียกฟังก์ชันแปลงวิดีโอ
                                $this->load->library('FFmpeg');
                                $converted_video_path = ROOT_DIR . "uploads/media/converted_" . $newname;

                                // เรียกฟังก์ชัน convertToMp4 ที่เราสร้างไว้
                                $result = $this->ffmpeg->convertToMp4($uploaded_video_path, $converted_video_path);
                                
                                if (isset($result['status'])) {
                                    switch ($result['status']) {
                                        case 0:
                                            // ถ้าแปลงวิดีโอสำเร็จ
                                            audit_unlink(ROOT_DIR . "uploads/media/" . $newname);
                                            $newname = 'converted_' . $newname;

                                            // เพิ่มข้อมูลในฐานข้อมูล
                                            $each = array(
                                                'lessons_id'    => $lesson_id,
                                                'med_name_th'   => isset($_REQUEST['med_name_th']) ? $_REQUEST['med_name_th'] : "",
                                                'med_name_eng'  => isset($_REQUEST['med_name_eng']) ? $_REQUEST['med_name_eng'] : "",
                                                'med_name_jp'   => isset($_REQUEST['med_name_jp']) ? $_REQUEST['med_name_jp'] : "",
                                                'thumbnail_med' => $thumbnail,
                                                'type'          => 'upload',
                                                'video'         => $newname
                                            );
                                            $this->course->insert_data_media($each, $lesson_id, 'upload', $newname);
                                            $this->lg->record('lesson', 'Create VDO: ' . $newname . ' of Lesson: ' . $lesname . '(' . $lesson_id . ')');
                                            break;
                                        case 1:
                                            // General error
                                            $error_message = [
                                                'thai' => 'เกิดข้อผิดพลาดทั่วไป โปรดตรวจสอบข้อมูลและตัวเลือกที่ใช้',
                                                'english' => 'General error occurred. Please check the input file and options.',
                                                'japan' => '一般的なエラーが発生しました。入力ファイルとオプションを確認してください。',
                                            ];
                                            break;
                                        case 3:
                                            // Invalid file or argument
                                            $error_message = [
                                                'thai' => 'ไฟล์ไม่ถูกต้องหรือมีการให้พารามิเตอร์ผิด กรุณาตรวจสอบไฟล์และอาร์กิวเมนต์ที่ใช้',
                                                'english' => 'Invalid file or argument provided. Please check the input file and options.',
                                                'japan' => '無効なファイルまたは引数が提供されました。入力ファイルとオプションを確認してください。',
                                            ];
                                            break;
                                        default:
                                            // Unknown error
                                            $error_message = [
                                                'thai' => 'เกิดข้อผิดพลาดไม่รู้จัก รหัสข้อผิดพลาด: ' . $result['status'] . ' - ' . $result['output'],
                                                'english' => 'An unknown error occurred. Error code: ' . $result['status'] . ' - ' . $result['output'],
                                                'japan' => '不明なエラーが発生しました。エラーコード: ' . $result['status'] . ' - ' . $result['output'],
                                            ];
                                            break;
                                    }

                                    if (isset($error_message) && !empty($error_message) && $newname != "") {
                                        audit_unlink(ROOT_DIR . "uploads/media/" . $newname);
                                        $newname = "";
                                    }
                                }
                            }
                        }
                    }
        
                    if (!empty($_FILES['path_file'])) {
        
                        $path_file = $this->reArrayFiles($_FILES['path_file']);
                        //print_r($path_file);
                        $name_file_th = isset($_REQUEST['name_file_th']) ? $_REQUEST['name_file_th'] : '';
                        $name_file_eng = isset($_REQUEST['name_file_eng']) ? $_REQUEST['name_file_eng'] : '';
                        $name_file_jp = isset($_REQUEST['name_file_jp']) ? $_REQUEST['name_file_jp'] : '';
                        $path_file_ori = $_REQUEST['path_file_ori'];
                        $id_fil = $_REQUEST['id_fil'];
                        $num_doc = 1;
                        $num_count = 0;
                        if (countArray($path_file) > 0) {
                            foreach ($path_file as $val) {
                                if ($val['name'] != "") {
                                    if ($id_fil[$num_count] == "") {
                                        $path_parts = pathinfo($val['name']);
                                        if (countArray($path_parts) > 0) {
                                            $newname = 'DocumentLesson_' . date('dmYHis') . $num_doc . "." . $path_parts['extension'];
                                            if (audit_move_uploaded_file($val['tmp_name'], ROOT_DIR . "uploads/document/" . $newname)) {
                                                $each = array(
                                                    'lessons_id' => $lesson_id,
                                                    'path_file' => $newname,
                                                    'name_file_th' => isset($name_file_th[$num_count]) ? $name_file_th[$num_count] : "",
                                                    'name_file_eng' => isset($name_file_eng[$num_count]) ? $name_file_eng[$num_count] : "",
                                                    'name_file_jp' => isset($name_file_jp[$num_count]) ? $name_file_jp[$num_count] : ""
                                                );
                                                $this->lg->record('lesson', 'Create Document: ' . $newname . ' of Lesson: ' . $lesname . '(' . $lesson_id . ')');
                                                $this->course->insert_data_document($each);
                                            }
                                        }
                                    }
                                    $num_doc++;
                                }
                                $num_count++;
                            }
                        }
                    }
                } else {
                    if (!empty($_FILES['scorm_file'])) {
                        $scmCode = $this->course->create_scorm_id($lesson_id);
                        $path = "scorm_" . $lesson_id . "_" . $scmCode;
                        $newDir = ROOT_DIR . "uploads/scorm/" . $path;
                        mkdir($newDir);
                        $scormFile = $_FILES['scorm_file'];
                        $sourcePath = $scormFile['tmp_name'];
                        $path_parts = pathinfo($scormFile['name']);
                        $targetPath = $newDir . "/" . $path . "." . $path_parts['extension'];
                        if (audit_move_uploaded_file($sourcePath, $targetPath)) {
                            $zip = new ZipArchive;
                            $openZip = $zip->open($targetPath);
                            $zip->extractTo($newDir);
                            $zip->close();
                            $this->course->update_scorm_id($scmCode, $path);
                            $this->lg->record('lesson', 'Create Scorm: ' . $path . ' of Lesson: ' . $lesname . '(' . $lesson_id . ')');
                        } else {
                            $this->course->delete_data($scmCode, 'id', 'lms_scm');
                            rmdir($newDir);
                        }
                    }
                }
            } else {
                $this->db->where('les_id', $_REQUEST['les_id']);
                $this->db->update('lms_les', $data);
                $periodLesson = $date_start != "0000-00-00 00:00:00" && $date_end != "0000-00-00 00:00:00" ? ' [' . $date_start . ' - ' . $date_end . ']' : '';
                $statusLesson = $status_les == "1" ? "Open " : "Close ";
                $this->lg->record('lesson', 'Update lesson: ' . $statusLesson . $lesname . '(' . $_REQUEST['les_id'] . ')' . $periodLesson . ' of course: ' . $cname . '(' . $course_id_lesson . ')' . '');
                $les_id = $_REQUEST['les_id'];

                if ($data['les_type'] == "1") {
        
                    $path = $this->course->check_scorm($_REQUEST['les_id']);
                    if ($path != "") {
                        $newDir = ROOT_DIR . "uploads/scorm/" . $path;
                        $this->course->delete_data($les_id, 'lessons_id', 'lms_scm');
            
                        $newDir = ROOT_DIR . "uploads/scorm/" . $path;
            
                        emptyDir($newDir);
                        rmdir($newDir);
                    }
                    if ($_REQUEST['type_media'] == "1") {
                        if ($_REQUEST['url_media'] != "") {
                            $arrurl = explode(",", $_REQUEST['url_media']);
                            $arr_checkmed = $this->course->check_media($les_id, 'url');
                            foreach ($arr_checkmed as $key) {
                                if (!in_array($key['video'], $arrurl)) {
                                    $this->course->delete_med($les_id, 'url', $key['video']);
                                }
                            }
                            for ($num_url = 0; $num_url < countArray($arrurl); $num_url++) {
                                $url = $this->getYoutubeEmbedUrl($arrurl[$num_url]);
                                $var_chkupload = $this->course->chk_uploadfileyoutube($les_id, $url);
                                if ($var_chkupload == 0) {
                                    $id_youtube = substr($url, 24);
                                    $arr_youtube =  explode("//www.youtube.com/embed/", $url);
                                    $id_yt = isset($arr_youtube[1]) ? str_replace(array("\n", "\r"), '', $arr_youtube[1]) : "";
                                    //$input = 'https://img.youtube.com/vi/'.$id_youtube[1].'/hqdefault.jpg';
                
                                    $dirimg = ROOT_DIR . 'uploads/thumbnail/';            // directory in which the image will be saved
                                    $localfile = 'thumbnail_' . date('dmYHis') . $num_url . '.jpg';         // set image name the same as the file name of the source
                                    // create the file with the image on the server
                                    // echo $id_yt."<br>";
                                    $varyoutube =  json_decode(getContentUrl('http://www.youtube.com/oembed?format=json&url=https://www.youtube.com/watch?v=' . $id_yt), true);
                                    if (isset($varyoutube['thumbnail_url']) && $varyoutube['thumbnail_url'] != "") {
                
                                        $r = file_put_contents($dirimg . $localfile, getContentUrl($varyoutube['thumbnail_url']));
                                        /*if(getContentUrl($input)!=""){
                                            $r = file_put_contents($dirimg.$localfile, getContentUrl($input));*/
                                        if (!$r) {
                                            $localfile = "default_cover.jpg";
                                        }
                                    } else {
                                        $localfile = "default_cover.jpg";
                                    }
                                    /*$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id_yt);*/
                                    $title = isset($ytarr['title']) ? $ytarr['title'] : "";
                                    /*parse_str($content, $ytarr);*/
                                    $each = array(
                                        'lessons_id'    => $les_id,
                                        'med_name_th'   => $title,
                                        'med_name_eng'  => $title,
                                        'med_name_jp'   => $title,
                                        'thumbnail_med' => $localfile,
                                        'type'          => 'url',
                                        'video'         => $url
                                    );
                                    $this->course->insert_data_media($each, $les_id, 'url', $url);
                                    $this->lg->record('lesson', 'Create URL YOUTUBE: ' . $url . ' of Lesson: ' . $lesname . '(' . $les_id . ')');
                                }
                            }
                        } else {
                            $this->db->where('lessons_id', $les_id);
                            $this->db->delete('lms_med');
                        }
                    } elseif ($_REQUEST['type_media'] == "2") {
                        if (!empty($_FILES['media_file'])) {
                            $thumbnail = "";
                            if (!empty($_FILES['thumbnail_med'])) {
                                if ($_FILES['thumbnail_med']['tmp_name'] != "") {
                                    $thumbnail = 'thumbnail_' . date('dmYHis') . '.jpg';
                                    if (!audit_move_uploaded_file($_FILES['thumbnail_med']['tmp_name'], ROOT_DIR . "uploads/thumbnail/" . $thumbnail)) {
                                    $thumbnail = "";
                                    }
                                }
                            }
                            if ($_FILES['media_file']['tmp_name'] != "") {
                                // ตั้งชื่อไฟล์ใหม่
                                $newname = 'MediaLesson_' . date('dmYHis') . '.mp4';
                                $uploaded_video_path = ROOT_DIR . "uploads/media/" . $newname;

                                // อัปโหลดไฟล์วิดีโอ
                                if (audit_move_uploaded_file($_FILES['media_file']['tmp_name'], $uploaded_video_path)) {
                                    // เรียกใช้งานฟังก์ชันแปลงวิดีโอ
                                    $this->load->library('FFmpeg');
                                    $converted_video_path = ROOT_DIR . "uploads/media/converted_" . $newname;
                        
                                    // เรียกฟังก์ชัน convertToMp4 ที่เราสร้างไว้
                                    $result = $this->ffmpeg->convertToMp4($uploaded_video_path, $converted_video_path);
                        
                                    if (isset($result['status'])) {
                                        switch ($result['status']) {
                                            case 0:
                                                // ถ้าแปลงวิดีโอสำเร็จ
                                                audit_unlink(ROOT_DIR . "uploads/media/" . $newname);
                                                $newname = 'converted_' . $newname;
                                    
                                                $each = array(
                                                    'lessons_id'    => $les_id,
                                                    'med_name_th'   => isset($_REQUEST['med_name_th']) ? $_REQUEST['med_name_th'] : "",
                                                    'med_name_eng'  => isset($_REQUEST['med_name_eng']) ? $_REQUEST['med_name_eng'] : "",
                                                    'med_name_jp'   => isset($_REQUEST['med_name_jp']) ? $_REQUEST['med_name_jp'] : "",
                                                    'thumbnail_med' => $thumbnail,
                                                    'type'          => 'upload',
                                                    'video'         => $newname
                                                );
                                                $this->course->insert_data_media($each, $les_id, 'upload', $newname);
                                                $this->lg->record('lesson', 'Create VDO: ' . $newname . ' of Lesson: ' . $lesname . '(' . $les_id . ')');
                                                break;
                                            case 1:
                                                // General error
                                                $error_message = [
                                                    'thai' => 'เกิดข้อผิดพลาดทั่วไป โปรดตรวจสอบข้อมูลและตัวเลือกที่ใช้',
                                                    'english' => 'General error occurred. Please check the input file and options.',
                                                    'japan' => '一般的なエラーが発生しました。入力ファイルとオプションを確認してください。',
                                                ];
                                                break;
                                            case 3:
                                                // Invalid file or argument
                                                $error_message = [
                                                    'thai' => 'ไฟล์ไม่ถูกต้องหรือมีการให้พารามิเตอร์ผิด กรุณาตรวจสอบไฟล์และอาร์กิวเมนต์ที่ใช้',
                                                    'english' => 'Invalid file or argument provided. Please check the input file and options.',
                                                    'japan' => '無効なファイルまたは引数が提供されました。入力ファイルとオプションを確認してください。',
                                                ];
                                                break;
                                            default:
                                                // Unknown error
                                                $error_message = [
                                                    'thai' => 'เกิดข้อผิดพลาดไม่รู้จัก รหัสข้อผิดพลาด: ' . $result['status'] . ' - ' . $result['output'],
                                                    'english' => 'An unknown error occurred. Error code: ' . $result['status'] . ' - ' . $result['output'],
                                                    'japan' => '不明なエラーが発生しました。エラーコード: ' . $result['status'] . ' - ' . $result['output'],
                                                ];
                                                break;
                                        }

                                        if (isset($error_message) && !empty($error_message) && $newname != "") {
                                            audit_unlink(ROOT_DIR . "uploads/media/" . $newname);
                                            $newname = "";
                                        }
                                    }
                                }
                            }
                        }
                    }
        
                    if (!empty($_FILES['path_file'])) {
                        $path_file = $this->reArrayFiles($_FILES['path_file']);
                        $name_file_th = isset($_REQUEST['name_file_th']) ? $_REQUEST['name_file_th'] : '';
                        $name_file_eng = isset($_REQUEST['name_file_eng']) ? $_REQUEST['name_file_eng'] : '';
                        $name_file_jp = isset($_REQUEST['name_file_jp']) ? $_REQUEST['name_file_jp'] : '';
            
                        $path_file_ori = isset($_REQUEST['path_file_ori']) ? $_REQUEST['path_file_ori'] : '';
                        $id_fil = isset($_REQUEST['id_fil']) ? $_REQUEST['id_fil'] : '';
            
                        $num_doc = 1;
                        $num_count = 0;
                        if (countArray($path_file) > 0) {
                            foreach ($path_file as $val) {
                                if ($val['name'] != "") {
                                    $path_parts = pathinfo($val['name']);
                                    if (countArray($path_parts) > 0) {
                                        $newname = 'DocumentLesson_' . date('dmYHis') . $num_doc . "." . $path_parts['extension'];
                                        if (audit_move_uploaded_file($val['tmp_name'], ROOT_DIR . "uploads/document/" . $newname)) {
                                            $each = array(
                                            'lessons_id' => $les_id,
                                            'path_file' => $newname,
                                            'name_file_th' => isset($name_file_th[$num_count]) ? $name_file_th[$num_count] : "",
                                            'name_file_eng' => isset($name_file_eng[$num_count]) ? $name_file_eng[$num_count] : "",
                                            'name_file_jp' => isset($name_file_jp[$num_count]) ? $name_file_jp[$num_count] : ""
                                            );
                                            $this->course->insert_data_document($each);
                                            $this->lg->record('lesson', 'Create Document: ' . $newname . ' of Lesson: ' . $lesname . '(' . $les_id . ')');
                                        }
                                    }
                                    $num_doc++;
                                }
                                $num_count++;
                            }
                        }
                        if (isset($_REQUEST['id_filedit'])) {
                            $name_file_th = isset($_REQUEST['name_file_thedit']) ? $_REQUEST['name_file_thedit'] : '';
                            $name_file_eng = isset($_REQUEST['name_file_engedit']) ? $_REQUEST['name_file_engedit'] : '';
                            $name_file_jp = isset($_REQUEST['name_file_jpedit']) ? $_REQUEST['name_file_jpedit'] : '';
                            $path_file_ori = isset($_REQUEST['path_file_oriedit']) ? $_REQUEST['path_file_oriedit'] : '';
                            $id_fil = isset($_REQUEST['id_filedit']) ? $_REQUEST['id_filedit'] : '';
                            $num_doc = 1;
                            $num_count = 0;
                            if (countArray($id_fil) > 0) {
                                for ($i = 0; $i < countArray($id_fil); $i++) {
                                    if ($id_fil[$i] != "") {
                                        $each = array(
                                            'id'            => $id_fil[$i],
                                            'lessons_id'    => $les_id,
                                            'path_file'     => $path_file_ori[$i],
                                            'name_file_th'  => isset($name_file_th[$num_count]) ? $name_file_th[$num_count] : "",
                                            'name_file_eng' => isset($name_file_eng[$num_count]) ? $name_file_eng[$num_count] : "",
                                            'name_file_jp'  => isset($name_file_jp[$num_count]) ? $name_file_jp[$num_count] : ""
                                        );
                                        $fetchChkNameLesDocument = $this->func_query->numrows(
                                            'lms_fil',
                                            '',
                                            '',
                                            '',
                                            'lessons_id = "' . $each['lessons_id'] . '" and id = "' . $each['id'] . '" and name_file_th = "' . $each['name_file_th'] . '" and name_file_eng = "' . $each['name_file_eng'] . '" and  name_file_jp = "' . $each['name_file_jp'] . '"'
                                        );
                                        $this->course->insert_data_document($each);
                                        if ($fetchChkNameLesDocument == 0) {
                                            $this->lg->record('lesson', 'Update name Document: ' . $path_file_ori[$i] . ' of Lesson: ' . $lesname . '(' . $les_id . ')');
                                        }
                                    }
                                    $num_doc++;
                                    $num_count++;
                                }
                            }
                        }
                    } else {
                        $name_file_th = isset($_REQUEST['name_file_thedit']) ? $_REQUEST['name_file_thedit'] : '';
                        $name_file_eng = isset($_REQUEST['name_file_engedit']) ? $_REQUEST['name_file_engedit'] : '';
                        $name_file_jp = isset($_REQUEST['name_file_jpedit']) ? $_REQUEST['name_file_jpedit'] : '';
                        $path_file_oriedit = isset($_REQUEST['path_file_oriedit']) ? $_REQUEST['path_file_oriedit'] : '';
                        $id_filedit = isset($_REQUEST['id_filedit']) ? $_REQUEST['id_filedit'] : '';
                        $path_file_ori = $path_file_oriedit;
                        $id_fil = $id_filedit;
                        $num_doc = 1;
                        $num_count = 0;
                        if (countArray($id_fil) > 0) {
                            for ($i = 0; $i < countArray($id_fil); $i++) {
                                if (isset($id_fil[$i]) && $id_fil[$i] != "") {
                                    $each = array(
                                        'id'            => $id_fil[$i],
                                        'lessons_id'    => $les_id,
                                        'path_file'     => $path_file_ori[$i],
                                        'name_file_th'  => isset($name_file_th[$num_count]) ? $name_file_th[$num_count] : "",
                                        'name_file_eng' => isset($name_file_eng[$num_count]) ? $name_file_eng[$num_count] : "",
                                        'name_file_jp'  => isset($name_file_jp[$num_count]) ? $name_file_jp[$num_count] : ""
                                    );
                                    $fetchChkNameLesDocument = $this->func_query->numrows(
                                        'lms_fil',
                                        '',
                                        '',
                                        '',
                                        'lessons_id = "' . $each['lessons_id'] . '" and id = "' . $each['id'] . '" and name_file_th = "' . $each['name_file_th'] . '" and name_file_eng = "' . $each['name_file_eng'] . '" and  name_file_jp = "' . $each['name_file_jp'] . '"'
                                    );
                                    $this->course->insert_data_document($each);
                                    if ($fetchChkNameLesDocument == 0) {
                                        $this->lg->record('lesson', 'Update name Document: ' . $path_file_ori[$i] . ' of Lesson: ' . $lesname . '(' . $les_id . ')');
                                    }
                                }
                                $num_doc++;
                                $num_count++;
                            }
                        }
                    }
                } else {
                    if (!empty($_FILES['scorm_file'])) {
                        if ($_FILES['scorm_file']['name'] != "") {
            
                            // $scorm_file = $this->reArrayFiles($_FILES['scorm_file']);
                            // print_r($_FILES['scorm_file']);
            
                            $path = $this->course->check_scorm($les_id);
                            if ($path != "") {
                                $newDir = ROOT_DIR . "uploads/scorm/" . $path;
            
                                emptyDir($newDir);
                                rmdir($newDir);
                                $this->course->delete_data($les_id, 'lessons_id', 'lms_scm');
                                $this->course->delete_document($les_id);
                                $this->course->delete_med_video($les_id, 'upload');
                                $this->course->delete_med_video($les_id, 'url');
                                //rmdir($newDir);
                            }
        
                            $scmCode = $this->course->create_scorm_id($les_id);
                            $path = "scorm_" . $les_id . "_" . $scmCode;
                            $newDir = ROOT_DIR . "uploads/scorm/" . $path;
                            mkdir($newDir);
                            $scormFile = $_FILES['scorm_file'];
                            $sourcePath = $scormFile['tmp_name'];
                            $path_parts = pathinfo($scormFile['name']);
                            $targetPath = $newDir . "/" . $path . "." . $path_parts['extension'];
                            if (audit_move_uploaded_file($sourcePath, $targetPath)) {
                                $zip = new ZipArchive;
                                $openZip = $zip->open($targetPath);
                                $zip->extractTo($newDir);
                                $zip->close();
                                $this->course->update_scorm_id($scmCode, $path);
                                $this->lg->record('lesson', 'Create Scorm: ' . $path . ' of Lesson: ' . $lesname . '(' . $les_id . ')');
                            } else {
                                $this->course->delete_data($scmCode, 'id', 'lms_scm');
            
                                emptyDir($newDir);
                                rmdir($newDir);
                            }
                        }
                    }
                }
            }
            if (!empty($error_message) && isset($error_message[$lang])) {
                $output['status'] = "0";
                $output['error'] = $error_message[$lang];
            } else {
                $output['status'] = "2";
            }
        } else {
            $output['status'] = "0";
        }
        echo json_encode($output);
    }
}
