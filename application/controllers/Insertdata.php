<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Insertdata extends CI_Controller
{
  public function updatelogcossv()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $fetch_cos = $this->func_query->query_result('lms_cos', '', '', '', '');
    if (countArray($fetch_cos) > 0) {
      foreach ($fetch_cos as $key => $value) {
        $fetch_rechk = $this->func_query->numrows('lms_log_updatecossv', '', '', '', 'logcossv_group = "course" and logcossv_fkid = "' . $value['cos_id'] . '"');
        if ($fetch_rechk == 0) {
          $arr_log = array(
            'logcossv_group' => 'course',
            'logcossv_fkid' => $value['cos_id'],
            'logcossv_nameth' => $value['cname_th'],
            'logcossv_nameen' => $value['cname_eng'],
            'logcossv_namejp' => $value['cname_jp'],
            'logcossv_descth' => $value['cdesc_th'],
            'logcossv_descen' => $value['cdesc_eng'],
            'logcossv_descjp' => $value['cdesc_jp'],
            'logcossv_cover' => $value['cos_pic'],
            'logcossv_expire_noti' => $value['cos_expire_noti'],
            'logcossv_status' => 'create',
            'logcossv_createby' => $value['cos_createby'],
            'logcossv_createdate' => $value['cos_createdate'],
          );
          $this->db->insert('lms_log_updatecossv', $arr_log);
        }
      }
    }
    $fetch_cos = $this->func_query->query_result('lms_sv', '', '', '', '');
    if (countArray($fetch_cos) > 0) {
      foreach ($fetch_cos as $key => $value) {
        $fetch_rechk = $this->func_query->numrows('lms_log_updatecossv', '', '', '', 'logcossv_group = "survey" and logcossv_fkid = "' . $value['sv_id'] . '"');
        if ($fetch_rechk == 0) {
          $arr_log = array(
            'logcossv_group' => 'survey',
            'logcossv_fkid' => $value['sv_id'],
            'logcossv_nameth' => $value['sv_title_th'],
            'logcossv_nameen' => $value['sv_title_eng'],
            'logcossv_namejp' => $value['sv_title_jp'],
            'logcossv_descth' => $value['sv_detail_th'],
            'logcossv_descen' => $value['sv_detail_eng'],
            'logcossv_descjp' => $value['sv_detail_jp'],
            'logcossv_cover' => $value['sv_cover'],
            'logcossv_expire_noti' => $value['sv_expire_noti'],
            'logcossv_status' => 'create',
            'logcossv_createby' => $value['sv_createby'],
            'logcossv_createdate' => $value['sv_createdate'],
          );
          $this->db->insert('lms_log_updatecossv', $arr_log);
        }
      }
    }
  }

  public function insert_coursegroup()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_REQUEST) && !empty($sess['emp_c'])) {
      $cgtitle_th = isset($_REQUEST['cgtitle_th']) ? $_REQUEST['cgtitle_th'] : "";
      $cgtitle_en = isset($_REQUEST['cgtitle_en']) ? $_REQUEST['cgtitle_en'] : "";
      $cgtitle_jp = isset($_REQUEST['cgtitle_jp']) ? $_REQUEST['cgtitle_jp'] : "";
      $cgdesc_th = isset($_REQUEST['cgdesc_th']) ? $_REQUEST['cgdesc_th'] : "";
      $cgdesc_en = isset($_REQUEST['cgdesc_en']) ? $_REQUEST['cgdesc_en'] : "";
      $cgdesc_jp = isset($_REQUEST['cgdesc_jp']) ? $_REQUEST['cgdesc_jp'] : "";
      $com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : "";
      $cg_status = isset($_REQUEST['cg_status']) ? $_REQUEST['cg_status'] : "0";
      $cg_id = isset($_REQUEST['cg_id']) ? $_REQUEST['cg_id'] : "";
      $cg_approve_by = isset($_REQUEST['cg_approve_by']) && countArray($_REQUEST['cg_approve_by']) ? implode(',', $_REQUEST['cg_approve_by']) : "";
      $arr = array(
        'cgtitle_th' => $cgtitle_th,
        'cgtitle_en' => $cgtitle_en,
        'cgtitle_jp' => $cgtitle_jp,
        'cgdesc_th' => $cgdesc_th,
        'cgdesc_en' => $cgdesc_en,
        'cgdesc_jp' => $cgdesc_jp,
        'com_id' => $com_id,
        'cg_approve_by' => $cg_approve_by,
        'cg_status' => $cg_status,
        'u_date' => date('Y-m-d H:i'),
        'u_by' => $sess['u_id'],
      );

      if (isset($_FILES['cg_icon']) && $_FILES['cg_icon']['error'] !== UPLOAD_ERR_NO_FILE) {
        $iconFile = $_FILES['cg_icon'];
        $iconInfo = $iconFile['error'] === UPLOAD_ERR_OK && is_uploaded_file($iconFile['tmp_name'])
          ? @getimagesize($iconFile['tmp_name'])
          : false;
        $isPng = $iconInfo !== false
          && isset($iconInfo[2])
          && $iconInfo[2] === IMAGETYPE_PNG
          && strtolower(pathinfo($iconFile['name'], PATHINFO_EXTENSION)) === 'png';

        if (!$isPng || $iconFile['size'] > (2 * 1024 * 1024)) {
          echo json_encode(array(
            'status' => '4',
            'message' => 'Course group icon must be a PNG file no larger than 2 MB.'
          ));
          return;
        }

        $iconDirectory = ROOT_DIR . 'uploads/course_group/icons/';
        if (!is_dir($iconDirectory) && !@mkdir($iconDirectory, 0755, true)) {
          echo json_encode(array('status' => '4', 'message' => 'Unable to create the icon upload directory.'));
          return;
        }

        try {
          $iconToken = bin2hex(random_bytes(8));
        } catch (Exception $exception) {
          $iconToken = str_replace('.', '', uniqid('', true));
        }
        $cgIcon = 'cog_icon_' . date('YmdHis') . '_' . $iconToken . '.png';
        $iconTargetPath = $iconDirectory . $cgIcon;

        if (!audit_move_uploaded_file($iconFile['tmp_name'], $iconTargetPath)) {
          echo json_encode(array('status' => '4', 'message' => 'Unable to save the course group icon.'));
          return;
        }

        if ($_REQUEST['operation'] == 'Edit') {
          $existingGroup = $this->func_query->query_row('lms_cog', '', '', '', 'cg_id="' . $_REQUEST['cg_id'] . '"');
          if (countArray($existingGroup) > 0 && !empty($existingGroup['cg_icon'])) {
            $oldIconPath = $iconDirectory . basename($existingGroup['cg_icon']);
            if (is_file($oldIconPath)) {
              audit_unlink($oldIconPath);
            }
          }
        }
        $arr['cg_icon'] = $cgIcon;
      }
      $cgtitle = "";
      if ($lang == "thai") {
        $cgtitle = $arr['cgtitle_th'] != "" ? $arr['cgtitle_th'] : $arr['cgtitle_en'];
        $cgtitle = $cgtitle != "" ? $cgtitle : $arr['cgtitle_jp'];
      } else if ($lang == "english") {
        $cgtitle = $arr['cgtitle_en'] != "" ? $arr['cgtitle_en'] : $arr['cgtitle_th'];
        $cgtitle = $cgtitle != "" ? $cgtitle : $arr['cgtitle_jp'];
      } else {
        $cgtitle = $arr['cgtitle_jp'] != "" ? $arr['cgtitle_jp'] : $arr['cgtitle_en'];
        $cgtitle = $cgtitle != "" ? $cgtitle : $arr['cgtitle_th'];
      }

      if (isset($_FILES['cgthumb']) && $_FILES['cgthumb'] != "") {
        if (isset($_FILES['cgthumb'])) {
          $imageSourcePath = $_FILES['cgthumb']['tmp_name'];
          $pathBG = $_FILES['cgthumb']['name'];
          if ($pathBG != "") {
            $array_pathext = explode('.', $pathBG);
            $extension = end($array_pathext);
            $cgthumb = "cog_" . date('YmdHis') . "." . $extension;
            $imageTargetPath = ROOT_DIR . "uploads/course_group/" . $cgthumb;
            if ($_REQUEST['operation'] == "Edit") {
              $fetch_img = $this->func_query->query_row('lms_cog', '', '', '', 'cg_id="' . $_REQUEST['cg_id'] . '"');
              if (countArray($fetch_img) > 0 && $fetch_img['cgthumb'] != "") {
                if (is_file(ROOT_DIR . "uploads/course_group/" . $fetch_img['cgthumb'])) {
                  audit_unlink(ROOT_DIR . "uploads/course_group/" . $fetch_img['cgthumb']);
                }
              }
            }
            if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
              $arr['cgthumb'] = $cgthumb;
            }
          }
        }
      }

      $arr['cg_approve'] = '2';
      if ($_REQUEST['operation'] == "Add") {
        $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id = "' . $com_id . '"');

        $fetch_id = $this->func_query->query_row('lms_cog', '', '', '', '', 'cg_id desc');
        $id2 = "";
        if (countArray($fetch_id) > 0) {
          $id1 = intval(substr($fetch_id["cgcode"], -4));
          $id1++;
          if ($id1 == 0 || $id1 < 10) {
            $id2 = $fetch_company['com_code'] . "000" . $id1;
          } else if ($id1 == 10 || $id1 < 100) {
            $id2 = $fetch_company['com_code'] . "00" . $id1;
          } else if ($id1 == 100 || $id1 < 1000) {
            $id2 = $fetch_company['com_code'] . "0" . $id1;
          } else {
            $id2 = $fetch_company['com_code'] . $id1;
          }
        } else {
          $id2 = $fetch_company['com_code'] . "0001";
        }
        $arr['cgcode'] = $id2;

        $arr['c_date'] = date('Y-m-d H:i');
        $arr['c_by'] = $sess['u_id'];
        if (in_array($sess['u_id'], $_REQUEST['cg_approve_by'])) {
          $arr['cg_approve'] = '1';
        }
        $fetch_chk = $this->func_query->numrows(
          'lms_cog',
          '',
          '',
          '',
          'cgtitle_th="' . $arr['cgtitle_th'] . '" and cgtitle_en="' . $arr['cgtitle_en'] . '" and cgtitle_jp="' . $arr['cgtitle_jp'] . '" and com_id="' . $com_id . '" and cg_isDelete="0"'
        );
        if ($fetch_chk == 0) {
          $this->db->insert('lms_cog', $arr);
          $id = $this->db->insert_id();
          if ($id != "") {
            $this->lg->record('courseGroup', 'Create course group: ' . $cgtitle . '(' . $id . ')');
            if ($arr['cg_approve'] == "1") {
              $arr_update = array(
                'cg_id' => $id,
                'coga_approve' => '1',
                'coga_createby' => $sess['u_id'],
                'coga_createdate' => date('Y-m-d H:i'),
              );
              $this->db->insert('lms_cog_approve', $arr_update);
            } else {
              $arr_update = array(
                'cg_id' => $id,
                'coga_approve' => '2',
                'coga_createby' => $sess['u_id'],
                'coga_createdate' => date('Y-m-d H:i'),
              );
              $this->db->insert('lms_cog_approve', $arr_update);
            }
            $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
            $fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="4"');
            $listApproved = array();
            $date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
            $lang = "english";
            if ($lang != "thai") {
              $date = date('d F Y');
            }
            $fetch_email = $this->func_query->query_result(
              'lms_usp',
              'lms_emp',
              'lms_emp.emp_id = lms_usp.emp_id',
              '',
              'lms_usp.u_id in (' . $cg_approve_by . ') and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0"',
              '',
              'emp_c,fullname_th,fullname_en,useri,email,com_id'
            );
            if (countArray($fetch_email) > 0) {
              foreach ($fetch_email as $key_rechk => $value_rechk) {
                $nameApprove = $value_rechk['emp_c'];
                if (!in_array($nameApprove, $listApproved)) {
                  array_push($listApproved, $nameApprove);
                }


                $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $value_rechk['com_id'] . '"');
                $subject_th = $fetch_formatmail['smf_subject_th'];
                $subject_en = $fetch_formatmail['smf_subject_en'];
                $message_th = $fetch_formatmail['smf_message_th'];
                $message_en = $fetch_formatmail['smf_message_en'];
                if ($subject_th != "") {
                  $subject_th = str_replace("#fullname", $value_rechk['fullname_th'], $subject_th);
                  $subject_th = str_replace("#username", $value_rechk['useri'], $subject_th);
                  $subject_th = str_replace("#email", $value_rechk['email'], $subject_th);
                  $subject_th = str_replace("#coursename", $cgtitle_th, $subject_th);
                  $subject_th = str_replace("#link_frontend", base_url() . 'managecourse/course_groups', $subject_th);
                  $subject_th = str_replace("#date", $date, $subject_th);
                  $subject_th = str_replace("#time", date('H:i'), $subject_th);
                  $subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
                  $subject_th = str_replace("#durationofstudy", 'No information', $subject_th);
                }
                if ($subject_en != "") {
                  $subject_en = str_replace("#fullname", $value_rechk['fullname_en'], $subject_en);
                  $subject_en = str_replace("#username", $value_rechk['useri'], $subject_en);
                  $subject_en = str_replace("#email", $value_rechk['email'], $subject_en);
                  $subject_en = str_replace("#coursename", $cgtitle_en, $subject_en);
                  $subject_en = str_replace("#link_frontend", base_url() . 'managecourse/course_groups', $subject_en);
                  $subject_en = str_replace("#date", $date, $subject_en);
                  $subject_en = str_replace("#time", date('H:i'), $subject_en);
                  $subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
                  $subject_en = str_replace("#durationofstudy", 'No information', $subject_en);
                }
                if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
                  $img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
                } else {
                  $img_val = '';
                }
                if ($message_th != "") {
                  $message_th = str_replace("#fullname", $value_rechk['fullname_th'], $message_th);
                  $message_th = str_replace("#username", $value_rechk['useri'], $message_th);
                  $message_th = str_replace("#password", '', $message_th);
                  $message_th = str_replace("#email", $value_rechk['email'], $message_th);
                  $message_th = str_replace("#coursename", $cgtitle_th, $message_th);
                  $message_th = str_replace("#link_frontend", base_url() . 'managecourse/course_groups', $message_th);
                  $message_th = str_replace("#date", $date, $message_th);
                  $message_th = str_replace("#time", date('H:i'), $message_th);
                  $message_th = str_replace("#image", $img_val, $message_th);
                  $message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
                  $message_th = str_replace("#durationofstudy", 'No information', $message_th);
                }
                if ($message_en != "") {
                  $message_en = str_replace("#fullname", $value_rechk['fullname_en'], $message_en);
                  $message_en = str_replace("#username", $value_rechk['useri'], $message_en);
                  $message_en = str_replace("#password", '', $message_en);
                  $message_en = str_replace("#email", $value_rechk['email'], $message_en);
                  $message_en = str_replace("#coursename", $cgtitle_en, $message_en);
                  $message_en = str_replace("#link_frontend", base_url() . 'managecourse/course_groups', $message_en);
                  $message_en = str_replace("#date", $date, $message_en);
                  $message_en = str_replace("#time", date('H:i'), $message_en);
                  $message_en = str_replace("#image", $img_val, $message_en);
                  $message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
                  $message_en = str_replace("#durationofstudy", 'No information', $message_en);
                }
                if ($lang == "thai") {
                  $this->db->sendEmail($value_rechk['email'], $message_th, $subject_th, $fetch_setmail);
                } else {
                  $this->db->sendEmail($value_rechk['email'], $message_en, $subject_en, $fetch_setmail);
                }
              }
              if (countArray($listApproved) > 0) {
                $this->lg->record('course_group', 'Create course approver: ' . implode(',', $listApproved) . ' of course group:' . $cgtitle . '(' . $id . ')');
              }
            }

            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        $this->db->where('cg_id', $_REQUEST['cg_id']);
        $this->db->update('lms_cog', $arr);
        $statusCG = $cg_status == "1" ? "Open " : "Close ";
        $this->lg->record('courseGroup', 'Update course group: ' . $statusCG . $cgtitle . '(' . $_REQUEST['cg_id'] . ')');
        $numrun = 1;
        $listApproved = array();
        if ($numrun == 1) {
          $arr_update = array(
            'cg_id' => $_REQUEST['cg_id'],
            'coga_approve' => '2',
            'coga_createby' => $sess['u_id'],
            'coga_createdate' => date('Y-m-d H:i'),
          );
          $this->db->insert('lms_cog_approve', $arr_update);
          $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
          $fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="4"');
          $date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
          $lang = "english";
          if ($lang != "thai") {
            $date = date('d F Y');
          }

          $fetch_email = $this->func_query->query_result(
            'lms_usp',
            'lms_emp',
            'lms_emp.emp_id = lms_usp.emp_id',
            '',
            'lms_usp.u_id in (' . $cg_approve_by . ') and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0"',
            '',
            'emp_c,fullname_th,fullname_en,useri,email,com_id'
          );
          if (countArray($fetch_email) > 0) {
            foreach ($fetch_email as $key_rechk => $value_rechk) {
              $nameApprove = $value_rechk['emp_c'];
              if (!in_array($nameApprove, $listApproved)) {
                array_push($listApproved, $nameApprove);
              }
              $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $value_rechk['com_id'] . '"');
              $subject_th = $fetch_formatmail['smf_subject_th'];
              $subject_en = $fetch_formatmail['smf_subject_en'];
              $message_th = $fetch_formatmail['smf_message_th'];
              $message_en = $fetch_formatmail['smf_message_en'];
              if ($subject_th != "") {
                $subject_th = str_replace("#fullname", $value_rechk['fullname_th'], $subject_th);
                $subject_th = str_replace("#username", $value_rechk['useri'], $subject_th);
                $subject_th = str_replace("#email", $value_rechk['email'], $subject_th);
                $subject_th = str_replace("#coursename", $cgtitle_th, $subject_th);
                $subject_th = str_replace("#link_frontend", base_url() . 'managecourse/course_groups', $subject_th);
                $subject_th = str_replace("#date", $date, $subject_th);
                $subject_th = str_replace("#time", date('H:i'), $subject_th);
                $subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
                $subject_th = str_replace("#durationofstudy", 'No information', $subject_th);
              }
              if ($subject_en != "") {
                $subject_en = str_replace("#fullname", $value_rechk['fullname_en'], $subject_en);
                $subject_en = str_replace("#username", $value_rechk['useri'], $subject_en);
                $subject_en = str_replace("#email", $value_rechk['email'], $subject_en);
                $subject_en = str_replace("#coursename", $cgtitle_en, $subject_en);
                $subject_en = str_replace("#link_frontend", base_url() . 'managecourse/course_groups', $subject_en);
                $subject_en = str_replace("#date", $date, $subject_en);
                $subject_en = str_replace("#time", date('H:i'), $subject_en);
                $subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
                $subject_en = str_replace("#durationofstudy", 'No information', $subject_en);
              }
              if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
                $img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
              } else {
                $img_val = '';
              }
              if ($message_th != "") {
                $message_th = str_replace("#fullname", $value_rechk['fullname_th'], $message_th);
                $message_th = str_replace("#username", $value_rechk['useri'], $message_th);
                $message_th = str_replace("#password", '', $message_th);
                $message_th = str_replace("#email", $value_rechk['email'], $message_th);
                $message_th = str_replace("#coursename", $cgtitle_th, $message_th);
                $message_th = str_replace("#link_frontend", base_url() . 'managecourse/course_groups', $message_th);
                $message_th = str_replace("#date", $date, $message_th);
                $message_th = str_replace("#time", date('H:i'), $message_th);
                $message_th = str_replace("#image", $img_val, $message_th);
                $message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
                $message_th = str_replace("#durationofstudy", 'No information', $message_th);
              }
              if ($message_en != "") {
                $message_en = str_replace("#fullname", $value_rechk['fullname_en'], $message_en);
                $message_en = str_replace("#username", $value_rechk['useri'], $message_en);
                $message_en = str_replace("#password", '', $message_en);
                $message_en = str_replace("#email", $value_rechk['email'], $message_en);
                $message_en = str_replace("#coursename", $cgtitle_en, $message_en);
                $message_en = str_replace("#link_frontend", base_url() . 'managecourse/course_groups', $message_en);
                $message_en = str_replace("#date", $date, $message_en);
                $message_en = str_replace("#time", date('H:i'), $message_en);
                $message_en = str_replace("#image", $img_val, $message_en);
                $message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
                $message_en = str_replace("#durationofstudy", 'No information', $message_en);
              }
              if ($lang == "thai") {
                $this->db->sendEmail($value_rechk['email'], $message_th, $subject_th, $fetch_setmail);
              } else {
                $this->db->sendEmail($value_rechk['email'], $message_en, $subject_en, $fetch_setmail);
              }
            }
            if (countArray($listApproved) > 0) {
              $this->lg->record('course_group', 'Update course approver: ' . implode(',', $listApproved) . ' of course group:' . $cgtitle . '(' . $_REQUEST['cg_id'] . ')');
            }
          }
        }

        $output['status'] = "2";
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function insert_sv_main()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Course_model', 'course', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_REQUEST) && !empty($sess['emp_c'])) {
      $com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : '';
      $sv_lang = isset($_REQUEST['sv_lang']) ? implode(',', $_REQUEST['sv_lang']) : "";
      $sv_userapprove = isset($_REQUEST['sv_userapprove']) ? implode(',', $_REQUEST['sv_userapprove']) : "";
      $sv_title_th = isset($_REQUEST['sv_title_th']) ? $_REQUEST['sv_title_th'] : '';
      $sv_detail_th = isset($_REQUEST['sv_detail_th']) ? $_REQUEST['sv_detail_th'] : '';
      $sv_explanation_th = isset($_REQUEST['sv_explanation_th']) ? $_REQUEST['sv_explanation_th'] : '';
      $sv_title_eng = isset($_REQUEST['sv_title_eng']) ? $_REQUEST['sv_title_eng'] : '';
      $sv_detail_eng = isset($_REQUEST['sv_detail_eng']) ? $_REQUEST['sv_detail_eng'] : '';
      $sv_explanation_eng = isset($_REQUEST['sv_explanation_eng']) ? $_REQUEST['sv_explanation_eng'] : '';
      $sv_title_jp = isset($_REQUEST['sv_title_jp']) ? $_REQUEST['sv_title_jp'] : '';
      $sv_detail_jp = isset($_REQUEST['sv_detail_jp']) ? $_REQUEST['sv_detail_jp'] : '';
      $sv_explanation_jp = isset($_REQUEST['sv_explanation_jp']) ? $_REQUEST['sv_explanation_jp'] : '';
      $sv_suggestion_status = isset($_REQUEST['sv_suggestion_status']) ? $_REQUEST['sv_suggestion_status'] : '0';
      $sv_status = isset($_REQUEST['sv_status']) ? $_REQUEST['sv_status'] : '0';
      $sv_isHeader = isset($_REQUEST['sv_isHeader']) ? $_REQUEST['sv_isHeader'] : '0';
      $sv_expire_noti = isset($_REQUEST['sv_expire_noti']) ? $_REQUEST['sv_expire_noti'] : '0';
      $sv_type = isset($_REQUEST['sv_type']) ? $_REQUEST['sv_type'] : '2';
      $time_start = isset($_REQUEST['time_start_survey']) ? $_REQUEST['time_start_survey'] . ":00" : '';
      $time_end = isset($_REQUEST['time_end_survey']) ? $_REQUEST['time_end_survey'] . ":00" : '';
      $survey_open_var = isset($_REQUEST['survey_open_var']) ? $_REQUEST['survey_open_var'] . " " . $time_start : '';
      $survey_end_var = isset($_REQUEST['survey_end_var']) ? $_REQUEST['survey_end_var'] . " " . $time_end : '';
      $data = array(
        'com_id' => $com_id,
        'sv_lang' => $sv_lang,
        'sv_title_th' => $sv_title_th,
        'sv_detail_th' => $sv_detail_th,
        'sv_explanation_th' => $sv_explanation_th,
        'sv_title_eng' => $sv_title_eng,
        'sv_detail_eng' => $sv_detail_eng,
        'sv_explanation_eng' => $sv_explanation_eng,
        'sv_title_jp' => $sv_title_jp,
        'sv_detail_jp' => $sv_detail_jp,
        'sv_explanation_jp' => $sv_explanation_jp,
        'sv_suggestion_status' => $sv_suggestion_status,
        'sv_open' => $survey_open_var,
        'sv_end' => $survey_end_var,
        'sv_type' => $sv_type,
        'sv_userapprove' => $sv_userapprove,
        'sv_isHeader' => $sv_isHeader,
        'sv_status' => $sv_status,
        'sv_expire_noti' => $sv_expire_noti,
        'sv_modifiedby' => $sess['u_id'],
        'sv_modifieddate' => date('Y-m-d H:i')
      );

      if (isset($_FILES['sv_cover']) && $_FILES['sv_cover'] != "") {
        if (isset($_FILES['sv_cover'])) {
          $imageSourcePath = $_FILES['sv_cover']['tmp_name'];
          $pathBG = $_FILES['sv_cover']['name'];
          if ($pathBG != "") {
            $array_pathext = explode('.', $pathBG);
            $extension = end($array_pathext);
            $sv_cover = "sv_" . date('YmdHis') . "." . $extension;
            $imageTargetPath = ROOT_DIR . "uploads/publicsv/" . $sv_cover;
            if ($_REQUEST['operation'] == "Edit") {
              $fetch_img = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
              if (countArray($fetch_img) > 0 && $fetch_img['sv_cover'] != "") {
                if (is_file(ROOT_DIR . "uploads/publicsv/" . $fetch_img['sv_cover'])) {
                  audit_unlink(ROOT_DIR . "uploads/publicsv/" . $fetch_img['sv_cover']);
                }
              }
            }
            if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
              $data['sv_cover'] = $sv_cover;
            }
          }
        }
      }
      if (!isset($data['sv_cover'])) {
        $data['sv_cover'] = $_REQUEST['sv_cover_ori'];
      }
      $sv_lang_arr = explode(',', $sv_lang);
      $value['isTH'] = in_array('th', $sv_lang_arr) ? "1" : "0";
      $value['isENG'] = in_array('eng', $sv_lang_arr) ? "1" : "0";
      $value['isJP'] = in_array('jp', $sv_lang_arr) ? "1" : "0";
      if ($value['isTH'] != "1") {
        $data['sv_title_th'] = "";
        $data['sv_detail_th'] = "";
        $data['sv_explanation_th'] = "";
      }

      if ($value['isENG'] != "1") {
        $data['sv_title_eng'] = "";
        $data['sv_detail_eng'] = "";
        $data['sv_explanation_eng'] = "";
      }

      if ($value['isJP'] != "1") {
        $data['sv_title_jp'] = "";
        $data['sv_detail_jp'] = "";
        $data['sv_explanation_jp'] = "";
      }

      $company = $this->course->query_data_onupdate($_REQUEST['com_id'], 'lms_company', 'com_id');
      if ($_REQUEST['operation'] == "Add") {
        $data['sv_createby'] = $sess['u_id'];
        $data['sv_createdate'] = date('Y-m-d H:i');

        if ($lang == "thai") {
          $sv_title = $data['sv_title_th'] != "" ? $data['sv_title_th'] : $data['sv_title_eng'];
          $sv_title = $sv_title != "" ? $sv_title : $data['sv_title_jp'];
        } else if ($lang == "english") {
          $sv_title = $data['sv_title_eng'] != "" ? $data['sv_title_eng'] : $data['sv_title_th'];
          $sv_title = $sv_title != "" ? $sv_title : $data['sv_title_jp'];
        } else {
          $sv_title = $data['sv_title_jp'] != "" ? $data['sv_title_jp'] : $data['sv_title_eng'];
          $sv_title = $sv_title != "" ? $sv_title : $data['sv_title_th'];
        }
        /*$fetch_chk = $this->func_query->numrows('lms_sv','','','','(sv_title_th="'.$data['sv_title_th'].'" and sv_title_eng="'.$data['sv_title_eng'].'" and sv_title_jp="'.$data['sv_title_jp'].'") and com_id="'.$data['com_id'].'" and sv_isDelete="0"');
            if($fetch_chk==0){*/
        $this->db->insert('lms_sv', $data);
        $id = $this->db->insert_id();
        if ($id != "") {
          $arr_log = array(
            'logcossv_group' => 'survey',
            'logcossv_fkid' => $id,
            'logcossv_nameth' => $sv_title_th,
            'logcossv_nameen' => $sv_title_eng,
            'logcossv_namejp' => $sv_title_jp,
            'logcossv_descth' => $sv_detail_th,
            'logcossv_descen' => $sv_detail_eng,
            'logcossv_descjp' => $sv_detail_jp,
            'logcossv_cover' => isset($data['sv_cover']) ? $data['sv_cover'] : '',
            'logcossv_expire_noti' => $sv_expire_noti,
            'logcossv_status' => 'updated',
            'logcossv_createby' => $sess['u_id'],
            'logcossv_createdate' => date('Y-m-d H:i:s'),
          );
          $this->db->insert('lms_log_updatecossv', $arr_log);
          // $this->lg->record('public_survey', 'Create public survey: '.$sv_title.'('.$id.') of company '.$company['com_name_th'].'');
          $output['status'] = "2";
        } else {
          $output['status'] = "3";
        }
        /*}else{
              $output['status'] = "1";
            }*/
      } else {
        if ($sess['u_id'] != "1") {
          $data['sv_public'] = 0;
          $data['sv_approve'] = 0;
          $arr_update = array(
            'sv_id' => $_REQUEST['sv_id'],
            'sva_approve' => '3',
            'sva_createby' => $sess['u_id'],
            'sva_createdate' => date('Y-m-d H:i'),
          );
          $this->db->insert('lms_sv_approve', $arr_update);
        }

        $fetch_chksv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
        $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_chksv['com_id'] . '"');
        if ($lang == "thai") {
          $sv_title = $fetch_chksv['sv_title_th'] != "" ? $fetch_chksv['sv_title_th'] : $fetch_chksv['sv_title_eng'];
          $sv_title = $sv_title != "" ? $sv_title : $fetch_chksv['sv_title_jp'];
        } else if ($lang == "english") {
          $sv_title = $fetch_chksv['sv_title_eng'] != "" ? $fetch_chksv['sv_title_eng'] : $fetch_chksv['sv_title_th'];
          $sv_title = $sv_title != "" ? $sv_title : $fetch_chksv['sv_title_jp'];
        } else {
          $sv_title = $fetch_chksv['sv_title_jp'] != "" ? $fetch_chksv['sv_title_jp'] : $fetch_chksv['sv_title_eng'];
          $sv_title = $sv_title != "" ? $sv_title : $fetch_chksv['sv_title_th'];
        }
        $fetch_chklog = $this->func_query->query_row('lms_log_updatecossv', '', '', '', 'logcossv_fkid="' . $_REQUEST['sv_id'] . '" and logcossv_group = "survey"', 'logcossv_id DESC');
        if (isset($fetch_chklog)) {
          $sv_cover = isset($data['sv_cover']) ? $data['sv_cover'] : '';
          if (
            $fetch_chklog['logcossv_nameth'] != $sv_title_th || $fetch_chklog['logcossv_nameen'] != $sv_title_eng ||
            $fetch_chklog['logcossv_namejp'] != $sv_title_jp || $fetch_chklog['logcossv_descth'] != $sv_detail_th ||
            $fetch_chklog['logcossv_descen'] != $sv_detail_eng || $fetch_chklog['logcossv_descjp'] != $sv_detail_jp ||
            $fetch_chklog['logcossv_cover'] != $sv_cover || $fetch_chklog['logcossv_expire_noti'] != $sv_expire_noti
          ) {
            $arr_log = array(
              'logcossv_group' => 'survey',
              'logcossv_fkid' => $_REQUEST['sv_id'],
              'logcossv_nameth' => $sv_title_th,
              'logcossv_nameen' => $sv_title_eng,
              'logcossv_namejp' => $sv_title_jp,
              'logcossv_descth' => $sv_detail_th,
              'logcossv_descen' => $sv_detail_eng,
              'logcossv_descjp' => $sv_detail_jp,
              'logcossv_cover' => isset($data['sv_cover']) ? $data['sv_cover'] : '',
              'logcossv_expire_noti' => $sv_expire_noti,
              'logcossv_status' => 'updated',
              'logcossv_createby' => $sess['u_id'],
              'logcossv_createdate' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('lms_log_updatecossv', $arr_log);

            if ($sess['ug_id'] == "1" && $fetch_chksv['sv_approve'] == "1") {
              $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');

              if ($lang == "thai") {
                $this->db->sendEmail(
                  'it.bangkok@verztec.com',
                  'เรียน Admin,<br>แบบสำรวจ ' . $sv_title . ' มีการเปลี่ยนแปลง <br>เมื่อวันที่และเวลา: ' . date('Y-m-d H:i:s') . '<br>บริษัท: ' . $fetch_company['com_code'] .
                    '<br>แก้ไขโดย: ' . $sess['fullname_th'],
                  'Survey ' . $sv_title . ' has updated information.',
                  $fetch_setmail
                );
              } else {
                $this->db->sendEmail(
                  'it.bangkok@verztec.com',
                  'Dear Admin,<br>Survey ' . $sv_title . ' has changed. <br>Date and time: ' . date('Y-m-d H:i:s') . '<br>Company: ' . $fetch_company['com_code'] .
                    '<br>Updated by: ' . $sess['fullname_en'],
                  'Survey ' . $sv_title . ' has updated information.',
                  $fetch_setmail
                );
              }
            }
          }
        } else {

          $arr_log = array(
            'logcossv_group' => 'survey',
            'logcossv_fkid' => $_REQUEST['sv_id'],
            'logcossv_nameth' => $sv_title_th,
            'logcossv_nameen' => $sv_title_eng,
            'logcossv_namejp' => $sv_title_jp,
            'logcossv_descth' => $sv_detail_th,
            'logcossv_descen' => $sv_detail_eng,
            'logcossv_descjp' => $sv_detail_jp,
            'logcossv_cover' => isset($data['sv_cover']) ? $data['sv_cover'] : '',
            'logcossv_expire_noti' => $sv_expire_noti,
            'logcossv_status' => 'updated',
            'logcossv_createby' => $sess['u_id'],
            'logcossv_createdate' => date('Y-m-d H:i:s'),
          );
          $this->db->insert('lms_log_updatecossv', $arr_log);
          if ($sess['ug_id'] == "1" && $fetch_chksv['sv_approve'] == "1") {
            $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');

            if ($lang == "thai") {
              $this->db->sendEmail(
                'it.bangkok@verztec.com',
                'เรียน Admin,<br>แบบสำรวจ ' . $sv_title . ' มีการเปลี่ยนแปลง <br>เมื่อวันที่และเวลา: ' . date('Y-m-d H:i:s') . '<br>บริษัท: ' . $fetch_company['com_code'] .
                  '<br>แก้ไขโดย: ' . $sess['fullname_th'],
                'Survey ' . $sv_title . ' has updated information.',
                $fetch_setmail
              );
            } else {
              $this->db->sendEmail(
                'it.bangkok@verztec.com',
                'Dear Admin,<br>Survey ' . $sv_title . ' has changed. <br>Date and time: ' . date('Y-m-d H:i:s') . '<br>Company: ' . $fetch_company['com_code'] .
                  '<br>Updated by: ' . $sess['fullname_en'],
                'Survey ' . $sv_title . ' has updated information.',
                $fetch_setmail
              );
            }
          }
        }
        /*$fetch_chk = $this->func_query->numrows('lms_sv','','','','sv_lang="'.$data['sv_lang'].'" and (sv_title_th="'.$data['sv_title_th'].'" and sv_title_eng="'.$data['sv_title_eng'].'" and sv_title_jp="'.$data['sv_title_jp'].'") and com_id="'.$data['com_id'].'" and sv_isDelete="0" and sv_id!="'.$_REQUEST['sv_id'].'"');
        if($fetch_chk==0){*/
        $this->course->update_survey_main($data, $_REQUEST['sv_id']);
        $output['status'] = "2";
        // $this->lg->record('public_survey', 'Update public survey: '.$sv_title.'('.$_REQUEST['sv_id'].') of company '.$company['com_name_th'].'');
        /*}else{
          $output['status'] = "1";
        }*/
      }
    }
    echo json_encode($output);
  }

  public function insert_cosmain()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_POST) && !empty($sess['emp_c'])) {
      $com_id = isset($_POST['com_id']) ? $_POST['com_id'] : "";
      $cg_id = isset($_POST['cg_id']) ? $_POST['cg_id'] : "";
      $tc_id = isset($_POST['tc_id']) ? $_POST['tc_id'] : "";
      $cos_lang = isset($_POST['cos_lang']) ? implode(',', $_POST['cos_lang']) : "";
      $cname_th = isset($_POST['cname_th']) ? $_POST['cname_th'] : "";
      $sub_description_th = isset($_POST['sub_description_th']) ? $_POST['sub_description_th'] : "";
      $cdesc_th = isset($_POST['cdesc_th']) ? $_POST['cdesc_th'] : "";
      $cname_eng = isset($_POST['cname_eng']) ? $_POST['cname_eng'] : "";
      $sub_description_eng = isset($_POST['sub_description_eng']) ? $_POST['sub_description_eng'] : "";
      $cdesc_eng = isset($_POST['cdesc_eng']) ? $_POST['cdesc_eng'] : "";
      $cname_jp = isset($_POST['cname_jp']) ? $_POST['cname_jp'] : "";
      $sub_description_jp = isset($_POST['sub_description_jp']) ? $_POST['sub_description_jp'] : "";
      $cdesc_jp = isset($_POST['cdesc_jp']) ? $_POST['cdesc_jp'] : "";
      $condition = isset($_POST['condition']) ? implode(',', $_POST['condition']) : "";
      $cos_status = isset($_POST['cos_status']) ? $_POST['cos_status'] : "0";
      $is_survey_required = isset($_POST['is_survey_required']) ? $_POST['is_survey_required'] : "0";
      $goal_score = isset($_POST['goal_score']) ? $_POST['goal_score'] : "";
      $cos_typegrading = isset($_POST['cos_typegrading']) ? $_POST['cos_typegrading'] : "";
      $seat_count = isset($_POST['seat_count']) ? $_POST['seat_count'] : "";
      $cos_hour = isset($_POST['cos_hour']) ? $_POST['cos_hour'] : "";
      $badges_name = isset($_POST['badges_name']) ? $_POST['badges_name'] : "";
      $badges_condition = isset($_POST['badges_condition']) ? $_POST['badges_condition'] : "";
      $badges_desc = isset($_POST['badges_desc']) ? $_POST['badges_desc'] : "";
      $mina = isset($_POST['mina']) ? $_POST['mina'] : "";
      $minb = isset($_POST['minb']) ? $_POST['minb'] : "";
      $minc = isset($_POST['minc']) ? $_POST['minc'] : "";
      $mind = isset($_POST['mind']) ? $_POST['mind'] : "";
      $cos_id = isset($_POST['cos_id']) ? $_POST['cos_id'] : "";
      $cos_expire_noti = isset($_POST['cos_expire_noti']) ? $_POST['cos_expire_noti'] : "0";
      if ($cos_typegrading != "1") {
        $mina = isset($_POST['mina_b']) ? $_POST['mina_b'] : "";
        $minb = 0;
        $minc = 0;
        $mind = 0;
      }
      $arr_cos = array(
        'com_id'              => $com_id,
        'tc_id'               => $tc_id,
        'cos_lang'            => $cos_lang,
        'cname_th'            => $cname_th,
        'sub_description_th'  => $sub_description_th,
        'cdesc_th'            => $cdesc_th,
        'cname_eng'           => $cname_eng,
        'sub_description_eng' => $sub_description_eng,
        'cdesc_eng'           => $cdesc_eng,
        'cname_jp'            => $cname_jp,
        'sub_description_jp'  => $sub_description_jp,
        'cdesc_jp'            => $cdesc_jp,
        'condition'           => $condition,
        'cos_status'          => $cos_status,
        'is_survey_required'  => $is_survey_required,
        'goal_score'          => $goal_score,
        'cos_typegrading'     => $cos_typegrading,
        'seat_count'          => $seat_count,
        'cos_expire_noti'     => $cos_expire_noti,
        'cos_hour'            => $cos_hour,
        'cos_modifiedby'      => $sess['u_id'],
        'cos_modifieddate'    => date('Y-m-d H:i')
      );


      if (isset($_FILES['cos_pic']) && $_FILES['cos_pic'] != "") {
        if (isset($_FILES['cos_pic'])) {
          $imageSourcePath = $_FILES['cos_pic']['tmp_name'];
          $pathBG = $_FILES['cos_pic']['name'];
          if ($pathBG != "") {
            $array_pathext = explode('.', $pathBG);
            $extension = end($array_pathext);
            $cos_pic = "cog_" . date('YmdHis') . "." . $extension;
            $imageTargetPath = ROOT_DIR . "uploads/course/" . $cos_pic;
            if ($_REQUEST['operation'] == "Edit") {
              $fetch_img = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $_REQUEST['cos_id'] . '"');
              if (countArray($fetch_img) > 0 && $fetch_img['cos_pic'] != "") {
                if (is_file(ROOT_DIR . "uploads/course/" . $fetch_img['cos_pic'])) {
                  audit_unlink(ROOT_DIR . "uploads/course/" . $fetch_img['cos_pic']);
                }
              }
            }
            if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
              $arr_cos['cos_pic'] = $cos_pic;
            }
          }
        }
      }
      if (!isset($arr_cos['cos_pic'])) {
        $arr_cos['cos_pic'] = $_REQUEST['cos_pic_ori'];
      }

      if ($lang == "thai") {
        $cname = $arr_cos['cname_th'] != "" ? $arr_cos['cname_th'] : $arr_cos['cname_eng'];
        $cname = $cname != "" ? $cname : $arr_cos['cname_jp'];
      } else if ($lang == "english") {
        $cname = $arr_cos['cname_eng'] != "" ? $arr_cos['cname_eng'] : $arr_cos['cname_th'];
        $cname = $cname != "" ? $cname : $arr_cos['cname_jp'];
      } else {
        $cname = $arr_cos['cname_jp'] != "" ? $arr_cos['cname_jp'] : $arr_cos['cname_eng'];
        $cname = $cname != "" ? $cname : $arr_cos['cname_th'];
      }
      if ($_REQUEST['operation'] == "Add") {
        $arr_cos['cos_createdate'] = date('Y-m-d H:i');
        $arr_cos['cos_createby'] = $sess['u_id'];

        $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id = "' . $arr_cos['com_id'] . '"');
        $fetch_id = $this->func_query->query_row('lms_cos', '', '', '', '', 'cos_id desc');
        $id2 = "";
        if (countArray($fetch_id) > 0) {
          $id1 = intval(substr($fetch_id["ccode"], -4));
          $id1++;
          if ($id1 == 0 || $id1 < 10) {
            $id2 = $fetch_company['com_code'] . "000" . $id1;
          } else if ($id1 == 10 || $id1 < 100) {
            $id2 = $fetch_company['com_code'] . "00" . $id1;
          } else if ($id1 == 100 || $id1 < 1000) {
            $id2 = $fetch_company['com_code'] . "0" . $id1;
          } else {
            $id2 = $fetch_company['com_code'] . $id1;
          }
        } else {
          $id2 = $fetch_company['com_code'] . "0001";
        }
        $arr_cos['ccode'] = $id2;
        /*if($sess['ug_approve']=="1"){
                $arr_cos['cos_approve'] = '1';
            }else{*/

        $where = '';
        if ($arr_cos['cname_th'] != "") {
          $where .= ' and cname_th="' . $arr_cos['cname_th'] . '"';
        }
        if ($arr_cos['cname_eng'] != "") {
          $where .= ' and cname_eng="' . $arr_cos['cname_eng'] . '"';
        }
        if ($arr_cos['cname_jp'] != "") {
          $where .= ' and cname_jp="' . $arr_cos['cname_jp'] . '"';
        }
        $fetch_chk = $this->func_query->numrows('lms_cos', '', '', '', 'com_id = "' . $com_id . '" and cos_isDelete="0"' . $where); //.$where
        if ($fetch_chk == 0) {
          $this->db->insert('lms_cos', $arr_cos);
          $id = $this->db->insert_id();
          if ($id != "") {
            $this->lg->record('Course', 'Create Course: ' . $cname . '(' . $id . ')');
            if (countArray($cg_id) > 0) {
              for ($i = 0; $i < countArray($cg_id); $i++) {
                $arr_cg = array(
                  'course_id' => $id,
                  'cg_id' => $cg_id[$i]
                );
                $this->db->insert('lms_cosincg', $arr_cg);
              }
            }
            $arr_cug = array(
              'course_id' => $id,
              'mina' => $mina,
              'minb' => $minb,
              'minc' => $minc,
              'mind' => $mind,
            );
            $this->db->insert('lms_cug', $arr_cug);
            $arr_log = array(
              'logcossv_group' => 'course',
              'logcossv_fkid' => $id,
              'logcossv_nameth' => $cname_th,
              'logcossv_nameen' => $cname_eng,
              'logcossv_namejp' => $cname_jp,
              'logcossv_descth' => $cdesc_th,
              'logcossv_descen' => $cdesc_eng,
              'logcossv_descjp' => $cdesc_jp,
              'logcossv_cover' => isset($arr_cos['cos_pic']) ? $arr_cos['cos_pic'] : '',
              'logcossv_expire_noti' => $cos_expire_noti,
              'logcossv_status' => 'create',
              'logcossv_createby' => $sess['u_id'],
              'logcossv_createdate' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('lms_log_updatecossv', $arr_log);
            if ($badges_name != "") {
              $arr_badges = array(
                'courses_id' => $id,
                'badges_name' => $badges_name,
                'badges_desc' => $badges_desc,
                'badges_condition' => $badges_condition,
                'time_create' => date('Y-m-d H:i'),
              );
              if (isset($_FILES['badges_img']) && $_FILES['badges_img'] != "") {
                if (isset($_FILES['badges_img'])) {
                  $imageSourcePath = $_FILES['badges_img']['tmp_name'];
                  $pathBG = $_FILES['badges_img']['name'];
                  if ($pathBG != "") {
                    $array_pathext = explode('.', $pathBG);
                    $extension = end($array_pathext);
                    $badges_img = "cog_" . date('YmdHis') . "." . $extension;
                    $imageTargetPath = ROOT_DIR . "uploads/badges/" . $badges_img;
                    if ($_REQUEST['operation'] == "Edit") {
                      $fetch_img = $this->func_query->query_row('lms_bad', '', '', '', 'courses_id="' . $_REQUEST['cos_id'] . '"');
                      if (countArray($fetch_img) > 0 && $fetch_img['badges_img'] != "") {
                        if (is_file(ROOT_DIR . "uploads/badges/" . $fetch_img['badges_img'])) {
                          audit_unlink(ROOT_DIR . "uploads/badges/" . $fetch_img['badges_img']);
                        }
                      }
                    }
                    if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
                      $arr_badges['badges_img'] = $badges_img;
                    }
                  }
                }
              }
              $this->db->insert('lms_bad', $arr_badges);
            }
            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        if ($sess['ug_id'] != "1") {
          $arr_cos['cos_approve'] = "0";
          $arr_cos['cos_public'] = "0";
          $arr_cos['cos_approveby'] = "";
          $arr_cos['cos_approvedate'] = "0000-00-00 00:00:00";
        }
        $where = '';
        if ($arr_cos['cname_th'] != "") {
          $where .= ' and cname_th="' . $arr_cos['cname_th'] . '"';
        }
        if ($arr_cos['cname_eng'] != "") {
          $where .= ' and cname_eng="' . $arr_cos['cname_eng'] . '"';
        }
        if ($arr_cos['cname_jp'] != "") {
          $where .= ' and cname_jp="' . $arr_cos['cname_jp'] . '"';
        }
        $fetch_chk_name = $this->func_query->numrows('lms_cos', '', '', '', 'cos_id!="' . $_REQUEST['cos_id'] . '" and com_id = "' . $com_id . '" and cos_isDelete="0"' . $where);
        if ($fetch_chk_name == 0) {
          $this->db->where('cos_id', $_REQUEST['cos_id']);
          $this->db->update('lms_cos', $arr_cos);

          $this->lg->record('Course', 'Update Course: ' . $cname . '(' . $_REQUEST['cos_id'] . ')');

          $this->db->where('course_id', $_REQUEST['cos_id']);
          $this->db->delete('lms_cosincg');
          if (countArray($cg_id) > 0) {
            for ($i = 0; $i < countArray($cg_id); $i++) {
              $arr_cg = array(
                'course_id' => $_REQUEST['cos_id'],
                'cg_id' => $cg_id[$i]
              );
              $this->db->insert('lms_cosincg', $arr_cg);
            }
          }
          $arr_cug = array(
            'mina' => $mina,
            'minb' => $minb,
            'minc' => $minc,
            'mind' => $mind,
          );
          $this->db->where('course_id', $_REQUEST['cos_id']);
          $this->db->update('lms_cug', $arr_cug);

          $fetch_chkcos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $_REQUEST['cos_id'] . '"');

          if ($lang == "thai") {
            $cname = $fetch_chkcos['cname_th'] != "" ? $fetch_chkcos['cname_th'] : $fetch_chkcos['cname_eng'];
            $cname = $cname != "" ? $cname : $fetch_chkcos['cname_jp'];
          } else if ($lang == "english") {
            $cname = $fetch_chkcos['cname_eng'] != "" ? $fetch_chkcos['cname_eng'] : $fetch_chkcos['cname_th'];
            $cname = $cname != "" ? $cname : $fetch_chkcos['cname_jp'];
          } else {
            $cname = $fetch_chkcos['cname_jp'] != "" ? $fetch_chkcos['cname_jp'] : $fetch_chkcos['cname_eng'];
            $cname = $cname != "" ? $cname : $fetch_chkcos['cname_th'];
          }

          $fetch_chklog = $this->func_query->query_row('lms_log_updatecossv', '', '', '', 'logcossv_fkid="' . $_REQUEST['cos_id'] . '" and logcossv_group = "course"', 'logcossv_id DESC');
          if (countArray($fetch_chklog) > 0) {
            $cos_pic = isset($arr_cos['cos_pic']) ? $arr_cos['cos_pic'] : '';
            if (
              $fetch_chklog['logcossv_nameth'] != $cname_th || $fetch_chklog['logcossv_nameen'] != $cname_eng || $fetch_chklog['logcossv_namejp'] != $cname_jp ||
              $fetch_chklog['logcossv_descth'] != $cdesc_th || $fetch_chklog['logcossv_descen'] != $cdesc_eng || $fetch_chklog['logcossv_descjp'] != $cdesc_jp ||
              $fetch_chklog['logcossv_cover'] != $cos_pic || $fetch_chklog['logcossv_expire_noti'] != $cos_expire_noti
            ) {
              $arr_log = array(
                'logcossv_group' => 'course',
                'logcossv_fkid' => $_REQUEST['cos_id'],
                'logcossv_nameth' => $cname_th,
                'logcossv_nameen' => $cname_eng,
                'logcossv_namejp' => $cname_jp,
                'logcossv_descth' => $cdesc_th,
                'logcossv_descen' => $cdesc_eng,
                'logcossv_descjp' => $cdesc_jp,
                'logcossv_cover' => isset($arr_cos['cos_pic']) ? $arr_cos['cos_pic'] : '',
                'logcossv_expire_noti' => $cos_expire_noti,
                'logcossv_status' => 'updated',
                'logcossv_createby' => $sess['u_id'],
                'logcossv_createdate' => date('Y-m-d H:i:s'),
              );
              $this->db->insert('lms_log_updatecossv', $arr_log);

              if ($sess['ug_id'] == "1" && $fetch_chkcos['cos_approve'] == "1") {
                $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
                $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_chkcos['com_id'] . '"');

                if ($lang == "thai") {
                  $this->db->sendEmail(
                    'it.bangkok@verztec.com',
                    'เรียน Admin,<br>คอร์ส ' . $cname . ' มีการเปลี่ยนแปลง <br>เมื่อวันที่และเวลา: ' . date('Y-m-d H:i:s') . '<br>บริษัท: ' . $fetch_company['com_code'] .
                      '<br>แก้ไขโดย: ' . $sess['fullname_th'],
                    'Course ' . $cname . ' has updated information.',
                    $fetch_setmail
                  );
                } else {
                  $this->db->sendEmail(
                    'it.bangkok@verztec.com',
                    'Dear Admin,<br>Course ' . $cname . ' has changed. <br>Date and time: ' . date('Y-m-d H:i:s') . '<br>Company: ' . $fetch_company['com_code'] .
                      '<br>Updated by: ' . $sess['fullname_en'],
                    'Course ' . $cname . ' has updated information.',
                    $fetch_setmail
                  );
                }
              }
            }
          } else {

            $arr_log = array(
              'logcossv_group' => 'course',
              'logcossv_fkid' => $_REQUEST['cos_id'],
              'logcossv_nameth' => $cname_th,
              'logcossv_nameen' => $cname_eng,
              'logcossv_namejp' => $cname_jp,
              'logcossv_descth' => $cdesc_th,
              'logcossv_descen' => $cdesc_eng,
              'logcossv_descjp' => $cdesc_jp,
              'logcossv_cover' => isset($arr_cos['cos_pic']) ? $arr_cos['cos_pic'] : '',
              'logcossv_expire_noti' => $cos_expire_noti,
              'logcossv_status' => 'updated',
              'logcossv_createby' => $sess['u_id'],
              'logcossv_createdate' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('lms_log_updatecossv', $arr_log);

            if ($sess['ug_id'] == "1" && $fetch_chkcos['cos_approve'] == "1") {
              $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
              $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_chkcos['com_id'] . '"');

              if ($lang == "thai") {
                $this->db->sendEmail(
                  'it.bangkok@verztec.com',
                  'เรียน Admin,<br>คอร์ส ' . $cname . ' มีการเปลี่ยนแปลง <br>เมื่อวันที่และเวลา: ' . date('Y-m-d H:i:s') . '<br>บริษัท: ' . $fetch_company['com_code'] .
                    '<br>แก้ไขโดย: ' . $sess['fullname_th'],
                  'Course ' . $cname . ' has updated information.',
                  $fetch_setmail
                );
              } else {
                $this->db->sendEmail(
                  'it.bangkok@verztec.com',
                  'Dear Admin,<br>Course ' . $cname . ' has changed. <br>Date and time: ' . date('Y-m-d H:i:s') . '<br>Company: ' . $fetch_company['com_code'] .
                    '<br>Updated by: ' . $sess['fullname_en'],
                  'Course ' . $cname . ' has updated information.',
                  $fetch_setmail
                );
              }
            }
          }
          if ($badges_name != "") {
            $arr_badges = array(
              'courses_id' => $_REQUEST['cos_id'],
              'badges_name' => $badges_name,
              'badges_desc' => $badges_desc,
              'badges_condition' => $badges_condition,
              'time_create' => date('Y-m-d H:i'),
            );
            if (isset($_FILES['badges_img']) && $_FILES['badges_img'] != "") {
              if (isset($_FILES['badges_img'])) {
                $imageSourcePath = $_FILES['badges_img']['tmp_name'];
                $pathBG = $_FILES['badges_img']['name'];
                if ($pathBG != "") {
                  $array_pathext = explode('.', $pathBG);
                  $extension = end($array_pathext);
                  $badges_img = "cog_" . date('YmdHis') . "." . $extension;
                  $imageTargetPath = ROOT_DIR . "uploads/badges/" . $badges_img;
                  if ($_REQUEST['operation'] == "Edit") {
                    $fetch_img = $this->func_query->query_row('lms_bad', '', '', '', 'courses_id="' . $_REQUEST['cos_id'] . '"');
                    if (countArray($fetch_img) > 0 && $fetch_img['badges_img'] != "") {
                      if (is_file(ROOT_DIR . "uploads/badges/" . $fetch_img['badges_img'])) {
                        audit_unlink(ROOT_DIR . "uploads/badges/" . $fetch_img['badges_img']);
                      }
                    }
                  }
                  if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
                    $arr_badges['badges_img'] = $badges_img;
                  }
                }
              }
            }
            $fetch_chk = $this->func_query->query_row('lms_bad', '', '', '', 'courses_id="' . $_REQUEST['cos_id'] . '"');
            if (countArray($fetch_chk) == 0) {
              $this->db->insert('lms_bad', $arr_badges);
            } else {
              $this->db->where('courses_id', $_REQUEST['cos_id']);
              $this->db->update('lms_bad', $arr_badges);
            }
          }
          $output['status'] = "2";
        } else {
          $output['status'] = "1";
        }
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function insert_documentincos()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $this->load->model('Log_model', 'lg', FALSE);
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_REQUEST) && !empty($sess['emp_c'])) {
      $fil_lang = isset($_REQUEST['fil_lang']) ? $_REQUEST['fil_lang'] : "";
      $name_file_th = isset($_REQUEST['name_file_th']) ? $_REQUEST['name_file_th'] : "";
      $name_file_eng = isset($_REQUEST['name_file_eng']) ? $_REQUEST['name_file_eng'] : "";
      $name_file_jp = isset($_REQUEST['name_file_jp']) ? $_REQUEST['name_file_jp'] : "";
      $cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : "";

      $arr_doccos = array(
        'cos_id' => $cos_id,
        'fil_lang' => $fil_lang,
        'name_file_th' => $name_file_th,
        'name_file_eng' => $name_file_eng,
        'name_file_jp' => $name_file_jp,
        'fil_modifiedby' => $sess['u_id'],
        'fil_modifieddate' => date('Y-m-d H:i')
      );

      $arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
      $this->db->where('cos_id', $cos_id);
      $this->db->update('lms_cos', $arr_update);
      if (isset($_FILES['path_file']) && $_FILES['path_file'] != "") {
        if (isset($_FILES['path_file'])) {
          $imageSourcePath = $_FILES['path_file']['tmp_name'];
          $pathBG = $_FILES['path_file']['name'];
          if ($pathBG != "") {
            $array_pathext = explode('.', $pathBG);
            $extension = end($array_pathext);
            $path_file = "cosdoc_" . date('YmdHis') . "." . $extension;
            $imageTargetPath = ROOT_DIR . "uploads/document/" . $path_file;
            if ($_REQUEST['operation_doccos'] == "Edit") {
              $fetch_img = $this->func_query->query_row('lms_cos_fil', '', '', '', 'fil_cos_id="' . $_REQUEST['fil_cos_id'] . '"');
              if (countArray($fetch_img) > 0 && $fetch_img['path_file'] != "") {
                if (is_file(ROOT_DIR . "uploads/document/" . $fetch_img['path_file'])) {
                  audit_unlink(ROOT_DIR . "uploads/document/" . $fetch_img['path_file']);
                }
              }
            }
            if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
              $arr_doccos['path_file'] = $path_file;
            }
          }
        }
      }
      $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
      $cos_lang = explode(',', $fetch_cos['cos_lang']);
      $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
      $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
      $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
      $cname = "";
      $nameFile = "";
      if ($lang == "thai") {
        if ($fetch_cos['isTH'] == "1") {
          $cname = $fetch_cos['cname_th'];
          $nameFile = $name_file_th;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
          if ($nameFile == "") {
            $nameFile = $name_file_eng;
          }
          if ($nameFile == "") {
            $nameFile = $name_file_jp;
          }
        }
      } else if ($lang == "english") {
        if ($fetch_cos['isENG'] == "1") {
          $cname = $fetch_cos['cname_eng'];
          $nameFile = $name_file_eng;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
          if ($nameFile == "") {
            $nameFile = $name_file_th;
          }
          if ($nameFile == "") {
            $nameFile = $name_file_jp;
          }
        }
      } else {
        if ($fetch_cos['isJP'] == "1") {
          $cname = $fetch_cos['cname_jp'];
          $nameFile = $name_file_jp;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($nameFile == "") {
            $nameFile = $name_file_eng;
          }
          if ($nameFile == "") {
            $nameFile = $name_file_th;
          }
        }
      }

      if ($_REQUEST['fil_cos_id'] == "") {
        $fetch_chk = $this->func_query->numrows(
          'lms_cos_fil',
          '',
          '',
          '',
          'fil_lang="' . $arr_doccos['fil_lang'] . '" and name_file_th="' . $arr_doccos['name_file_th'] . '" and name_file_eng="' . $arr_doccos['name_file_eng'] . '" and name_file_jp="' . $arr_doccos['name_file_jp'] .
            '" and cos_id="' . $arr_doccos['cos_id'] . '" and fil_isDelete="0"'
        );
        if ($fetch_chk == 0) {
          $this->db->insert('lms_cos_fil', $arr_doccos);
          $id = $this->db->insert_id();
          if ($id != "") {
            // $this->lg->record('Course', 'Create Document: '.$arr_doccos['path_file'].'('.$id.') of Course: '.$cname.' ('.$cos_id.')');
            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        $this->db->where('fil_cos_id', $_REQUEST['fil_cos_id']);
        $this->db->update('lms_cos_fil', $arr_doccos);
        $output['status'] = "2";
        // $this->lg->record('Course', 'Update name Document: '.$nameFile.'('.$_REQUEST['fil_cos_id'].') of Course: '.$cname.' ('.$cos_id.')');
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function insert_periodandpermission()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_POST) && !empty($sess['emp_c'])) {
      $cos_id = isset($_POST['course_id_pp']) ? $_POST['course_id_pp'] : "";
      $time_start = isset($_POST['time_start']) ? $_POST['time_start'] . ":00" : "00:00:00";
      $time_end = isset($_POST['time_end']) ? $_POST['time_end'] . ":00" : "00:00:00";
      $date_start = isset($_POST['date_start_var']) && $_POST['date_start_var'] != "" ? $_POST['date_start_var'] : "0000-00-00 00:00:00";
      $date_end = isset($_POST['date_end_var']) && $_POST['date_end_var'] != "" ? $_POST['date_end_var'] : "0000-00-00 00:00:00";
      $point_redeem = isset($_POST['point_redeem']) ? $_POST['point_redeem'] : "";
      $get_point = isset($_POST['get_point']) ? $_POST['get_point'] : "";
      $posi_var = isset($_POST['posi_var']) ? explode(",", $_POST['posi_var']) : array();
      $cosde_status = isset($_POST['cosde_status']) ? $_POST['cosde_status'] : "0";
      $cosde_id = isset($_POST['cosde_id']) ? $_POST['cosde_id'] : "";
      $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
      $is_public_course = isset($fetch_cos['cos_public']) && $fetch_cos['cos_public'] == "1";
      $is_public_course_pending_approval = isset($fetch_cos['cos_approve']) && $fetch_cos['cos_approve'] == "0";
      $arr_period = array(
        'cos_id' => $cos_id,
        'date_start' => $date_start,
        'date_end' => $date_end,
        'point_redeem' => $point_redeem,
        'get_point' => $get_point,
        'cosde_status' => $cosde_status,
        'cosde_modifiedby' => $sess['u_id'],
        'cosde_modifieddate' => date('Y-m-d H:i:s')
      );

      $has_date_start = $date_start != "0000-00-00 00:00:00";
      $has_date_end = $date_end != "0000-00-00 00:00:00";
      if ($has_date_start && $has_date_end && strtotime($date_end) < strtotime($date_start)) {
        $output['status'] = "0";
        echo json_encode($output);
        return;
      }
      if (!$is_public_course && !$is_public_course_pending_approval && ((!$has_date_start && $has_date_end) || ($has_date_start && !$has_date_end))) {
        $output['status'] = "0";
        echo json_encode($output);
        return;
      }

      $fetch_les = $this->func_query->query_result('lms_les', '', '', '', 'cos_id="' . $cos_id . '" and time_start!="0000-00-00 00:00:00" and time_end!="0000-00-00 00:00:00" and les_isDelete="0"');
      $fetch_qiz = $this->func_query->query_result('lms_qiz', '', '', '', 'cos_id="' . $cos_id . '" and period_open!="0000-00-00 00:00:00" and period_end!="0000-00-00 00:00:00" and quiz_isDelete="0"');
      $fetch_sv = $this->func_query->query_result('lms_survey', '', '', '', 'cos_id="' . $cos_id . '" and survey_open!="0000-00-00 00:00:00" and survey_end!="0000-00-00 00:00:00" and sv_isDelete="0"');

      $arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
      $this->db->where('cos_id', $cos_id);
      $this->db->update('lms_cos', $arr_update);

      $cos_lang = explode(',', $fetch_cos['cos_lang']);
      $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
      $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
      $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
      $cname = "";
      if ($lang == "thai") {
        if ($fetch_cos['isTH'] == "1") {
          $cname = $fetch_cos['cname_th'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
        }
      } else if ($lang == "english") {
        if ($fetch_cos['isENG'] == "1") {
          $cname = $fetch_cos['cname_eng'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
        }
      } else {
        if ($fetch_cos['isJP'] == "1") {
          $cname = $fetch_cos['cname_jp'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
        }
      }

      if (countArray($fetch_les) > 0) {
        foreach ($fetch_les as $key => $value) {
          $arr_update = array();
          if ($value['time_start'] != $date_start) {
            $arr_update['time_start'] = $date_start;
          }
          if ($value['time_end'] != $date_end) {
            $arr_update['time_end'] = $date_end;
          }
          if (countArray($arr_update) > 0) {
            $this->db->where('les_id', $value['les_id']);
            $this->db->update('lms_les', $arr_update);
          }
        }
      }
      if (countArray($fetch_qiz) > 0) {
        foreach ($fetch_qiz as $key => $value) {
          $arr_update = array();
          if ($value['period_open'] != $date_start) {
            $arr_update['period_open'] = $date_start;
          }
          if ($value['period_end'] != $date_end) {
            $arr_update['period_end'] = $date_end;
          }
          if (countArray($arr_update) > 0) {
            $this->db->where('qiz_id', $value['qiz_id']);
            $this->db->update('lms_qiz', $arr_update);
          }
        }
      }
      if (countArray($fetch_sv) > 0) {
        foreach ($fetch_sv as $key => $value) {
          $arr_update = array();
          if ($value['survey_open'] != $date_start) {
            $arr_update['survey_open'] = $date_start;
          }
          if ($value['survey_end'] != $date_end) {
            $arr_update['survey_end'] = $date_end;
          }
          if (countArray($arr_update) > 0) {
            $this->db->where('sv_id', $value['sv_id']);
            $this->db->update('lms_survey', $arr_update);
          }
        }
      }
      if ($_REQUEST['operation_pp'] == "Add") {
        $fetch_chk = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $cos_id . '" and cosde_isDelete="0"');

        if (countArray($fetch_chk) == 0) {
          $arr_period['cosde_createby'] = $sess['u_id'];
          $arr_period['cosde_createdate'] = date('Y-m-d H:i');
          $this->db->insert('lms_cos_detail', $arr_period);
          $cosde_id = $this->db->insert_id();
          if ($cosde_id != "" && countArray($posi_var) > 0) {
            for ($i = 0; $i < countArray($posi_var); $i++) {
              if (isset($posi_var[$i]) && $posi_var[$i] != "") {
                $arr_ug = array(
                  'cosde_id' =>  $cosde_id,
                  'posi_id' =>  $posi_var[$i],
                  'cosdepos_date' =>  date('Y-m-d H:i')
                );
                $this->db->insert('lms_cos_detail_ug', $arr_ug);
              }
            }
          }
        } else {
          $this->db->where('cosde_id', $fetch_chk['cosde_id']);
          $this->db->update('lms_cos_detail', $arr_period);
          // $this->lg->record('course', 'Update Period: '.$arr_period['date_start'].' - '.$arr_period['date_end'].' of course: '.$cname.'('.$cos_id.')'.'');
          if ($cosde_id != "" && countArray($posi_var) > 0) {
            $this->db->where('cosde_id', $cosde_id);
            $this->db->delete('lms_cos_detail_ug');
            if (!empty($posi_var)) {
              for ($i = 0; $i < countArray($posi_var); $i++) {
                if (isset($posi_var[$i]) && $posi_var[$i] != "") {
                  $arr_ug = array(
                    'cosde_id' =>  $cosde_id,
                    'posi_id' =>  $posi_var[$i],
                    'cosdepos_date' =>  date('Y-m-d H:i')
                  );
                  $this->db->insert('lms_cos_detail_ug', $arr_ug);
                }
              }
            }
          }
        }
        $output['status'] = "2";
      } else {
        $this->db->where('cosde_id', $cosde_id);
        $this->db->update('lms_cos_detail', $arr_period);
        // $this->lg->record('course', 'Update Period: '.$arr_period['date_start'].' - '.$arr_period['date_end'].' of course: '.$cname.'('.$cos_id.')'.'');
        $count_posi = is_array($posi_var) ? countArray($posi_var) : 0;
        if ($cosde_id != "") {
          $this->db->where('cosde_id', $cosde_id);
          $this->db->delete('lms_cos_detail_ug');
          if (!empty($posi_var)) {
            for ($i = 0; $i < countArray($posi_var); $i++) {
              if (isset($posi_var[$i]) && $posi_var[$i] != "") {
                $arr_ug = array(
                  'cosde_id' =>  $cosde_id,
                  'posi_id' =>  $posi_var[$i],
                  'cosdepos_date' =>  date('Y-m-d H:i')
                );
                $this->db->insert('lms_cos_detail_ug', $arr_ug);
              }
            }
          }
        }
        $output['status'] = "2";
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function insert_sv_permission()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_POST) && !empty($sess['emp_c'])) {
      $sv_id = isset($_POST['sv_id_pp']) ? $_POST['sv_id_pp'] : "";
      $posi_var = isset($_POST['posi_var']) ? explode(",", $_POST['posi_var']) : array();
      $this->db->where('sv_id', $sv_id);
      $this->db->delete('lms_sv_pm');
      if (!empty($posi_var)) {
        for ($i = 0; $i < countArray($posi_var); $i++) {
          if (isset($posi_var[$i]) && $posi_var[$i] != "") {
            $arr_ug = array(
              'sv_id' =>  $sv_id,
              'posi_id' =>  $posi_var[$i],
              'svpm_date' =>  date('Y-m-d H:i')
            );
            $this->db->insert('lms_sv_pm', $arr_ug);
          }
        }
      }
      $output['status'] = "2";
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }


  private function getYoutubeEmbedUrl($url)
  {

    $urlParts   = explode('/', $url);
    $vidid      = explode('&', str_replace('watch?v=', '', end($urlParts)));

    return '//www.youtube.com/embed/' . $vidid[0];
  }

  public function insert_quiz()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    // var_dump($_REQUEST);
    if (isset($_REQUEST) && !empty($sess['emp_c'])) {

      $cos_id = isset($_REQUEST['course_id_quiz']) ? $_REQUEST['course_id_quiz'] : "";
      $quiz_lang = isset($_REQUEST['quiz_lang']) ? $_REQUEST['quiz_lang'] : "";
      $quiz_name_th = isset($_REQUEST['quiz_name_th']) ? $_REQUEST['quiz_name_th'] : "";
      $quiz_info_th = isset($_REQUEST['quiz_info_th']) ? $_REQUEST['quiz_info_th'] : "";
      $quiz_name_eng = isset($_REQUEST['quiz_name_eng']) ? $_REQUEST['quiz_name_eng'] : "";
      $quiz_info_eng = isset($_REQUEST['quiz_info_eng']) ? $_REQUEST['quiz_info_eng'] : "";
      $quiz_name_jp = isset($_REQUEST['quiz_name_jp']) ? $_REQUEST['quiz_name_jp'] : "";
      $quiz_info_jp = isset($_REQUEST['quiz_info_jp']) ? $_REQUEST['quiz_info_jp'] : "";
      $time_start = isset($_REQUEST['time_start_quiz']) ? $_REQUEST['time_start_quiz'] : "00:00:00";
      $time_end = isset($_REQUEST['time_end_quiz']) ? $_REQUEST['time_end_quiz'] : "00:00:00";
      $period_open = isset($_REQUEST['period_open_var']) && $_REQUEST['period_open_var'] != "" ? $_REQUEST['period_open_var'] . " " . $time_start : "";
      $period_end = isset($_REQUEST['period_end_var']) && $_REQUEST['period_end_var'] != "" ? $_REQUEST['period_end_var'] . " " . $time_end : "";
      $quiz_answer = isset($_REQUEST['quiz_answer']) ? $_REQUEST['quiz_answer'] : "0";
      $quiz_limit  = isset($_REQUEST['quiz_limit']) == 'on' ? "1" : "0";
      $quiz_limitval = isset($_REQUEST['quiz_limitval']) ? $_REQUEST['quiz_limitval'] : "";
      $qize_id = isset($_REQUEST['qize_id']) ? $_REQUEST['qize_id'] : "";
      $quiz_numofshown = isset($_REQUEST['quiz_numofshown']) ? $_REQUEST['quiz_numofshown'] : "";
      $totalquiz = isset($_REQUEST['totalquiz']) ? $_REQUEST['totalquiz'] : "";
      $quiz_maxscore = isset($_REQUEST['quiz_maxscore']) ? $_REQUEST['quiz_maxscore'] : "";
      $quiz_random = isset($_REQUEST['quiz_random']) ? $_REQUEST['quiz_random'] : "0";
      $quiz_random_choice = isset($_REQUEST['quiz_random_choice']) ? $_REQUEST['quiz_random_choice'] : "0";
      $quiz_show = isset($_REQUEST['quiz_show']) ? $_REQUEST['quiz_show'] : "0";
      $quiz_ishint = isset($_REQUEST['quiz_ishint']) ? $_REQUEST['quiz_ishint'] : "0";
      $quiz_model = isset($_REQUEST['quiz_model']) ? $_REQUEST['quiz_model'] : "0";
      $quiz_grade = isset($_REQUEST['quiz_grade']) ? $_REQUEST['quiz_grade'] : "0";
      $quiz_type = isset($_REQUEST['quiz_type']) ? $_REQUEST['quiz_type'] : "1";

      $data = array(
        'cos_id' => $cos_id,
        'quiz_lang' => $quiz_lang,
        'quiz_name_th' => $quiz_name_th,
        'quiz_info_th' => $quiz_info_th,
        'quiz_name_eng' => $quiz_name_eng,
        'quiz_info_eng' => $quiz_info_eng,
        'quiz_name_jp' => $quiz_name_jp,
        'quiz_info_jp' => $quiz_info_jp,
        'period_open' => $period_open,
        'period_end' => $period_end,
        'quiz_random' => $quiz_random,
        'quiz_random_choice' => $quiz_random_choice,
        'quiz_show' => $quiz_show,
        'quiz_grade' => $quiz_grade,
        'quiz_type' => $quiz_type,
        'quiz_answer' => $quiz_answer,
        'quiz_limit' => $quiz_limit,
        'quiz_limitval' => $quiz_limitval,
        'quiz_maxscore' => $quiz_maxscore,
        'quiz_ishint' => $quiz_ishint,
        'quiz_model' => $quiz_model,
        'quiz_numofshown' => $quiz_numofshown,
        'quiz_modifiedby' => $sess['u_id'],
        'quiz_modifieddate' => date('Y-m-d H:i')
      );
      $arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
      $this->db->where('cos_id', $cos_id);
      $this->db->update('lms_cos', $arr_update);
      $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
      $cos_lang = explode(',', $fetch_cos['cos_lang']);
      $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
      $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
      $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
      $cname = "";
      $qizname = "";
      if ($lang == "thai") {
        if ($fetch_cos['isTH'] == "1") {
          $cname = $fetch_cos['cname_th'];
          $qizname = $quiz_name_th;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
          if ($qizname == "") {
            $qizname = $quiz_name_eng;
          }
          if ($qizname == "") {
            $qizname = $quiz_name_jp;
          }
        }
      } else if ($lang == "english") {
        if ($fetch_cos['isENG'] == "1") {
          $cname = $fetch_cos['cname_eng'];
          $qizname = $quiz_name_eng;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
          if ($qizname == "") {
            $qizname = $quiz_name_th;
          }
          if ($qizname == "") {
            $qizname = $quiz_name_jp;
          }
        }
      } else {
        if ($fetch_cos['isJP'] == "1") {
          $cname = $fetch_cos['cname_jp'];
          $qizname = $quiz_name_jp;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($qizname == "") {
            $qizname = $quiz_name_eng;
          }
          if ($qizname == "") {
            $qizname = $quiz_name_th;
          }
        }
      }

      if ($_REQUEST['operation_quiz'] == "Add") {
        $data['quiz_createby'] = $sess['u_id'];
        $data['quiz_createdate'] = date('Y-m-d H:i');

        $fetch_chk = $this->func_query->numrows(
          'lms_qiz',
          '',
          '',
          '',
          '(quiz_name_th="' . $data['quiz_name_th'] . '" and quiz_name_eng="' . $data['quiz_name_eng'] . '" and quiz_name_jp="' . $data['quiz_name_jp'] . '") and cos_id="' . $data['cos_id'] . '" and quiz_isDelete="0"'
        );
        if ($fetch_chk == 0) {
          $this->db->insert('lms_qiz', $data);
          $id = $this->db->insert_id();
          if ($id != "") {
            // $this->lg->record('Quiz', 'Create Quiz: '.$qizname.'('.$id.') of Course: '.$cname.'('.$cos_id.')');
            if ($_REQUEST['qize_id'] != "") {
              $qize_id = $_REQUEST['qize_id'];
              $numques = 0;
              $fetch_data = $this->func_query->query_result('lms_quese', '', '', '', 'qize_id="' . $qize_id . '"');
              if (countArray($fetch_data) > 0) {
                foreach ($fetch_data as $key_qn => $value_qn) {
                  $var_rechk_type = 1;
                  if ($quiz_random_choice == "1" || $quiz_ishint == "1" || $quiz_model == "1") {
                    if ($value_qn['quese_type'] == "sa" || $value_qn['quese_type'] == "sub") {
                      unset($fetch_data[$key_qn]);
                    }
                  }
                }
              }
              if (countArray($fetch_data) > 0) {
                foreach ($fetch_data as $key_qn => $value_qn) {
                  $data_qn = array(
                    'qiz_id' => $id,
                    'ques_type' => $value_qn['quese_type'],
                    'ques_score' => $value_qn['quese_score'],
                    'ques_name_th' => $value_qn['quese_name_th'],
                    'ques_info_th' => $value_qn['quese_info_th'],
                    'ques_name_eng' => $value_qn['quese_name_eng'],
                    'ques_info_eng' => $value_qn['quese_info_eng'],
                    'ques_name_jp' => $value_qn['quese_name_jp'],
                    'ques_info_jp' => $value_qn['quese_info_jp'],
                    'ques_createby' => $sess['u_id'],
                    'ques_createdate' => date('Y-m-d H:i'),
                    'ques_modifiedby' => $sess['u_id'],
                    'ques_modifieddate' => date('Y-m-d H:i')
                  );
                  $this->db->insert('lms_ques', $data_qn);
                  $ques_id = $this->db->insert_id();
                  if ($value_qn['quese_type'] == "multi" || $value_qn['quese_type'] == "2choice") {
                    $fetch_muli = $this->func_query->query_row('lms_quese_mul', '', '', '', 'quese_id="' . $value_qn['quese_id'] . '"');
                    if (countArray($fetch_muli) > 0) {
                      $data_mul = array(
                        'ques_id'  => $ques_id,
                        'mul_c1_th'  => $fetch_muli['mule_c1_th'],
                        'mul_c2_th'  => $fetch_muli['mule_c2_th'],
                        'mul_c3_th'  => $fetch_muli['mule_c3_th'],
                        'mul_c4_th'  => $fetch_muli['mule_c4_th'],
                        'mul_c5_th'  => $fetch_muli['mule_c5_th'],
                        'mul_c1_eng'  => $fetch_muli['mule_c1_eng'],
                        'mul_c2_eng'  => $fetch_muli['mule_c2_eng'],
                        'mul_c3_eng'  => $fetch_muli['mule_c3_eng'],
                        'mul_c4_eng'  => $fetch_muli['mule_c4_eng'],
                        'mul_c5_eng'  => $fetch_muli['mule_c5_eng'],
                        'mul_c1_jp'  => $fetch_muli['mule_c1_jp'],
                        'mul_c2_jp'  => $fetch_muli['mule_c2_jp'],
                        'mul_c3_jp'  => $fetch_muli['mule_c3_jp'],
                        'mul_c4_jp'  => $fetch_muli['mule_c4_jp'],
                        'mul_c5_jp'  => $fetch_muli['mule_c5_jp'],
                        'mul_answer'  => $fetch_muli['mule_answer'],
                        'mul_createby'  => $sess['u_id'],
                        'mul_createdate'  => date('Y-m-d H:i'),
                        'mul_modifiedby'  => $sess['u_id'],
                        'mul_modifieddate'  => date('Y-m-d H:i')
                      );
                      $this->db->insert('lms_ques_mul', $data_mul);
                    }
                  }
                  $numques++;
                }
              }
              $data = array(
                'quiz_numofshown' => $numques
              );
              $this->db->where('qiz_id', $id);
              $this->db->update('lms_qiz', $data);
            }
            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        if ($_REQUEST['qize_id'] != "") {
          $qize_id = $_REQUEST['qize_id'];
          $numques = 0;
          $fetch_data = $this->func_query->query_result('lms_quese', '', '', '', 'qize_id="' . $qize_id . '"');
          foreach ($fetch_data as $key_qn => $value_qn) {
            $data_qn = array(
              'qiz_id' => $_REQUEST['qiz_id'],
              'ques_type' => $value_qn['quese_type'],
              'ques_score' => $value_qn['quese_score'],
              'ques_name_th' => $value_qn['quese_name_th'],
              'ques_info_th' => $value_qn['quese_info_th'],
              'ques_name_eng' => $value_qn['quese_name_eng'],
              'ques_info_eng' => $value_qn['quese_info_eng'],
              'ques_name_jp' => $value_qn['quese_name_jp'],
              'ques_info_jp' => $value_qn['quese_info_jp'],
              'ques_createby' => $sess['u_id'],
              'ques_createdate' => date('Y-m-d H:i'),
              'ques_modifiedby' => $sess['u_id'],
              'ques_modifieddate' => date('Y-m-d H:i')
            );
            $fetch_data_detail = $this->func_query->query_row('lms_ques', '', '', '', 'qiz_id="' . $_REQUEST['qiz_id'] . '" and ques_isDelete="0" and (ques_name_th="' . $data_qn['ques_name_th'] . '" and ques_name_eng="' . $data_qn['ques_name_eng'] . '" and ques_name_jp="' . $data_qn['ques_name_jp'] . '")');
            if (!isset($fetch_data_detail['ques_id'])) {
              $this->db->insert('lms_ques', $data_qn);
              $ques_id = $this->db->insert_id();
              if ($value_qn['quese_type'] == "multi" || $value_qn['quese_type'] == "2choice") {
                $fetch_muli = $this->func_query->query_row('lms_quese_mul', '', '', '', 'quese_id="' . $value_qn['quese_id'] . '"');
                if (countArray($fetch_muli) > 0) {
                  $data_mul = array(
                    'ques_id'  => $ques_id,
                    'mul_c1_th'  => $fetch_muli['mule_c1_th'],
                    'mul_c2_th'  => $fetch_muli['mule_c2_th'],
                    'mul_c3_th'  => $fetch_muli['mule_c3_th'],
                    'mul_c4_th'  => $fetch_muli['mule_c4_th'],
                    'mul_c5_th'  => $fetch_muli['mule_c5_th'],
                    'mul_c1_eng'  => $fetch_muli['mule_c1_eng'],
                    'mul_c2_eng'  => $fetch_muli['mule_c2_eng'],
                    'mul_c3_eng'  => $fetch_muli['mule_c3_eng'],
                    'mul_c4_eng'  => $fetch_muli['mule_c4_eng'],
                    'mul_c5_eng'  => $fetch_muli['mule_c5_eng'],
                    'mul_c1_jp'  => $fetch_muli['mule_c1_jp'],
                    'mul_c2_jp'  => $fetch_muli['mule_c2_jp'],
                    'mul_c3_jp'  => $fetch_muli['mule_c3_jp'],
                    'mul_c4_jp'  => $fetch_muli['mule_c4_jp'],
                    'mul_c5_jp'  => $fetch_muli['mule_c5_jp'],
                    'mul_answer'  => $fetch_muli['mule_answer'],
                    'mul_createby'  => $sess['u_id'],
                    'mul_createdate'  => date('Y-m-d H:i'),
                    'mul_modifiedby'  => $sess['u_id'],
                    'mul_modifieddate'  => date('Y-m-d H:i')
                  );
                  $this->db->insert('lms_ques_mul', $data_mul);
                }
              }
              $numques++;
            } else {
              $this->db->where('ques_id', $fetch_data_detail['ques_id']);
              $this->db->update('lms_ques', $data_qn);
              if ($value_qn['quese_type'] == "multi" || $value_qn['quese_type'] == "2choice") {
                $fetch_muli = $this->func_query->query_row('lms_quese_mul', '', '', '', 'quese_id="' . $value_qn['quese_id'] . '"');
                if (isset($fetch_muli)) {
                  $fetch_muli_chk = $this->func_query->numrows('lms_ques_mul', '', '', '', 'ques_id="' . $fetch_data_detail['ques_id'] . '"');
                  $data_mul = array(
                    'ques_id'  => $fetch_data_detail['ques_id'],
                    'mul_c1_th'  => $fetch_muli['mule_c1_th'],
                    'mul_c2_th'  => $fetch_muli['mule_c2_th'],
                    'mul_c3_th'  => $fetch_muli['mule_c3_th'],
                    'mul_c4_th'  => $fetch_muli['mule_c4_th'],
                    'mul_c5_th'  => $fetch_muli['mule_c5_th'],
                    'mul_c1_eng'  => $fetch_muli['mule_c1_eng'],
                    'mul_c2_eng'  => $fetch_muli['mule_c2_eng'],
                    'mul_c3_eng'  => $fetch_muli['mule_c3_eng'],
                    'mul_c4_eng'  => $fetch_muli['mule_c4_eng'],
                    'mul_c5_eng'  => $fetch_muli['mule_c5_eng'],
                    'mul_c1_jp'  => $fetch_muli['mule_c1_jp'],
                    'mul_c2_jp'  => $fetch_muli['mule_c2_jp'],
                    'mul_c3_jp'  => $fetch_muli['mule_c3_jp'],
                    'mul_c4_jp'  => $fetch_muli['mule_c4_jp'],
                    'mul_c5_jp'  => $fetch_muli['mule_c5_jp'],
                    'mul_answer'  => $fetch_muli['mule_answer'],
                    'mul_createby'  => $sess['u_id'],
                    'mul_createdate'  => date('Y-m-d H:i'),
                    'mul_modifiedby'  => $sess['u_id'],
                    'mul_modifieddate'  => date('Y-m-d H:i')
                  );

                  if ($fetch_muli_chk == 0) {
                    $this->db->insert('lms_ques_mul', $data_mul);
                  } else {
                    $this->db->where('ques_id', $fetch_data_detail['ques_id']);
                    $this->db->update('lms_ques_mul', $data_mul);
                  }
                }
              }
              $numques++;
            }
          }
          $data = array(
            'quiz_numofshown' => $numques
          );
          $this->db->where('qiz_id', $_REQUEST['qiz_id']);
          $this->db->update('lms_qiz', $data);
        } else {
          $this->db->where('qiz_id', $_REQUEST['qiz_id']);
          $this->db->update('lms_qiz', $data);
        }
        $output['status'] = "2";
        // $this->lg->record('Quiz', 'Update Quiz: '.$qizname.'('.$_REQUEST['qiz_id'].') of Course: '.$cname.'('.$cos_id.')');
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function insert_question()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_REQUEST) && !empty($sess['emp_c'])) {
      $qiz_id = isset($_REQUEST['qiz_id_question']) ? $_REQUEST['qiz_id_question'] : "";
      $cos_id = isset($_REQUEST['cos_id_question']) ? $_REQUEST['cos_id_question'] : "";
      $ques_id = isset($_REQUEST['ques_id']) ? $_REQUEST['ques_id'] : "";
      $ques_name_th = isset($_REQUEST['ques_name_th']) ? $_REQUEST['ques_name_th'] : "";
      $ques_name_eng = isset($_REQUEST['ques_name_eng']) ? $_REQUEST['ques_name_eng'] : "";
      $ques_name_jp = isset($_REQUEST['ques_name_jp']) ? $_REQUEST['ques_name_jp'] : "";
      $ques_info_th = isset($_REQUEST['ques_info_th']) ? $_REQUEST['ques_info_th'] : "";
      $ques_info_eng = isset($_REQUEST['ques_info_eng']) ? $_REQUEST['ques_info_eng'] : "";
      $ques_info_jp = isset($_REQUEST['ques_info_jp']) ? $_REQUEST['ques_info_jp'] : "";
      $ques_type = isset($_REQUEST['ques_type']) ? $_REQUEST['ques_type'] : "";
      $ques_score = isset($_REQUEST['ques_score']) ? $_REQUEST['ques_score'] : "";
      $mul_answer = isset($_REQUEST['mul_answer']) ? $_REQUEST['mul_answer'] : "";
      $mul_c1_th = isset($_REQUEST['mul_c1_th']) ? $_REQUEST['mul_c1_th'] : "";
      $mul_c2_th = isset($_REQUEST['mul_c2_th']) ? $_REQUEST['mul_c2_th'] : "";
      $mul_c3_th = isset($_REQUEST['mul_c3_th']) ? $_REQUEST['mul_c3_th'] : "";
      $mul_c4_th = isset($_REQUEST['mul_c4_th']) ? $_REQUEST['mul_c4_th'] : "";
      $mul_c5_th = isset($_REQUEST['mul_c5_th']) ? $_REQUEST['mul_c5_th'] : "";
      $mul_c1_eng = isset($_REQUEST['mul_c1_eng']) ? $_REQUEST['mul_c1_eng'] : "";
      $mul_c2_eng = isset($_REQUEST['mul_c2_eng']) ? $_REQUEST['mul_c2_eng'] : "";
      $mul_c3_eng = isset($_REQUEST['mul_c3_eng']) ? $_REQUEST['mul_c3_eng'] : "";
      $mul_c4_eng = isset($_REQUEST['mul_c4_eng']) ? $_REQUEST['mul_c4_eng'] : "";
      $mul_c5_eng = isset($_REQUEST['mul_c5_eng']) ? $_REQUEST['mul_c5_eng'] : "";
      $mul_c1_jp = isset($_REQUEST['mul_c1_jp']) ? $_REQUEST['mul_c1_jp'] : "";
      $mul_c2_jp = isset($_REQUEST['mul_c2_jp']) ? $_REQUEST['mul_c2_jp'] : "";
      $mul_c3_jp = isset($_REQUEST['mul_c3_jp']) ? $_REQUEST['mul_c3_jp'] : "";
      $mul_c4_jp = isset($_REQUEST['mul_c4_jp']) ? $_REQUEST['mul_c4_jp'] : "";
      $mul_c5_jp = isset($_REQUEST['mul_c5_jp']) ? $_REQUEST['mul_c5_jp'] : "";

      $ques_hintname_th = isset($_REQUEST['ques_hintname_th']) ? $_REQUEST['ques_hintname_th'] : "";
      $ques_hintdetail_th = isset($_REQUEST['ques_hintdetail_th']) ? $_REQUEST['ques_hintdetail_th'] : "";
      $ques_hintname_eng = isset($_REQUEST['ques_hintname_eng']) ? $_REQUEST['ques_hintname_eng'] : "";
      $ques_hintdetail_eng = isset($_REQUEST['ques_hintdetail_eng']) ? $_REQUEST['ques_hintdetail_eng'] : "";
      $ques_hintname_jp = isset($_REQUEST['ques_hintname_jp']) ? $_REQUEST['ques_hintname_jp'] : "";
      $ques_hintdetail_jp = isset($_REQUEST['ques_hintdetail_jp']) ? $_REQUEST['ques_hintdetail_jp'] : "";
      $ques_show = isset($_REQUEST['ques_show']) ? $_REQUEST['ques_show'] : "0";
      $ques_upload_required = isset($_REQUEST['ques_upload_required']) ? "1" : "0";
      $ques_upload_type = isset($_REQUEST['ques_upload_type']) && in_array($_REQUEST['ques_upload_type'], array('image', 'video', 'both')) ? $_REQUEST['ques_upload_type'] : "both";
      $ques_upload_max_mb = isset($_REQUEST['ques_upload_max_mb']) ? intval($_REQUEST['ques_upload_max_mb']) : 10;
      $ques_upload_max_mb = $ques_upload_max_mb > 0 ? $ques_upload_max_mb : 10;
      $ques_upload_note = isset($_REQUEST['ques_upload_note']) ? $_REQUEST['ques_upload_note'] : "";
      $fetch_qiz = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id="' . $qiz_id . '"');
      $arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
      $this->db->where('cos_id', $fetch_qiz['cos_id']);
      $this->db->update('lms_cos', $arr_update);
      $data = array(
        'qiz_id' => $qiz_id,
        'ques_type' => $ques_type,
        'ques_name_th' => $ques_name_th,
        'ques_name_eng' => $ques_name_eng,
        'ques_name_jp' => $ques_name_jp,
        'ques_info_th' => $ques_info_th,
        'ques_info_eng' => $ques_info_eng,
        'ques_info_jp' => $ques_info_jp,
        'ques_hintname_th' => $ques_hintname_th,
        'ques_hintdetail_th' => $ques_hintdetail_th,
        'ques_hintname_eng' => $ques_hintname_eng,
        'ques_hintdetail_eng' => $ques_hintdetail_eng,
        'ques_hintname_jp' => $ques_hintname_jp,
        'ques_hintdetail_jp' => $ques_hintdetail_jp,
        'ques_score' => $ques_score,
        'ques_status' => $ques_show,
        'ques_upload_required' => $ques_upload_required,
        'ques_upload_type' => $ques_upload_type,
        'ques_upload_max_mb' => $ques_upload_max_mb,
        'ques_upload_note' => $ques_upload_note,
        'ques_modifiedby' => $sess['u_id'],
        'ques_modifieddate' => date('Y-m-d H:i')
      );
      $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $fetch_qiz['cos_id'] . '"');
      $cos_lang = explode(',', $fetch_cos['cos_lang']);
      $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
      $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
      $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
      $qizname = "";
      $quesname = "";
      if ($lang == "thai") {
        if ($fetch_cos['isTH'] == "1") {
          $qizname = $fetch_qiz['quiz_name_th'];
          $quesname = $ques_name_th;
        } else {
          if ($qizname == "") {
            $qizname = $fetch_qiz['quiz_name_eng'];
          }
          if ($qizname == "") {
            $qizname = $fetch_qiz['quiz_name_jp'];
          }
          if ($quesname == "") {
            $quesname = $ques_name_eng;
          }
          if ($quesname == "") {
            $quesname = $ques_name_jp;
          }
        }
      } else if ($lang == "english") {
        if ($fetch_cos['isENG'] == "1") {
          $qizname = $fetch_qiz['quiz_name_eng'];
          $quesname = $ques_name_eng;
        } else {
          if ($qizname == "") {
            $qizname = $fetch_qiz['quiz_name_th'];
          }
          if ($qizname == "") {
            $qizname = $fetch_qiz['quiz_name_jp'];
          }
          if ($quesname == "") {
            $quesname = $ques_name_th;
          }
          if ($quesname == "") {
            $quesname = $ques_name_jp;
          }
        }
      } else {
        if ($fetch_cos['isJP'] == "1") {
          $qizname = $fetch_qiz['quiz_name_jp'];
          $quesname = $ques_name_jp;
        } else {
          if ($qizname == "") {
            $qizname = $fetch_qiz['quiz_name_eng'];
          }
          if ($qizname == "") {
            $qizname = $fetch_qiz['quiz_name_th'];
          }
          if ($quesname == "") {
            $quesname = $ques_name_eng;
          }
          if ($quesname == "") {
            $quesname = $ques_name_th;
          }
        }
      }

      if (isset($_FILES['ques_hintimg']) && $_FILES['ques_hintimg'] != "") {
        if (isset($_FILES['ques_hintimg'])) {
          $imageSourcePath = $_FILES['ques_hintimg']['tmp_name'];
          $pathBG = $_FILES['ques_hintimg']['name'];
          if ($pathBG != "") {
            $array_pathext = explode('.', $pathBG);
            $extension = end($array_pathext);
            $ques_hintimg = "Hint_" . date('YmdHis') . "." . $extension;
            $imageTargetPath = ROOT_DIR . "uploads/hint/" . $ques_hintimg;
            if ($_REQUEST['operation_question'] == "Edit") {
              $fetch_img = $this->func_query->query_row('lms_ques', '', '', '', 'ques_id="' . $_REQUEST['ques_id'] . '"');
              if (countArray($fetch_img) > 0 && $fetch_img['ques_hintimg'] != "") {
                if (is_file(ROOT_DIR . "uploads/hint/" . $fetch_img['ques_hintimg'])) {
                  audit_unlink(ROOT_DIR . "uploads/hint/" . $fetch_img['ques_hintimg']);
                }
              }
            }
            if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
              $data['ques_hintimg'] = $ques_hintimg;
            }
          }
        }
      }
      if ($_REQUEST['operation_question'] == "Add") {
        $data['ques_createby'] = $sess['u_id'];
        $data['ques_createdate'] = date('Y-m-d H:i');

        $where_ques = "";
        if ($data['ques_name_th'] != "") {
          $where_ques .= "ques_name_th = '" . htmlentities($data['ques_name_th']) . "'";
        }
        if ($data['ques_name_eng'] != "") {
          if ($where_ques != "") {
            $where_ques .= ' and ';
          }
          $where_ques .= "ques_name_eng = '" . htmlentities($data['ques_name_eng']) . "'";
        }
        if ($data['ques_name_jp'] != "") {
          if ($where_ques != "") {
            $where_ques .= ' and ';
          }
          $where_ques .= "ques_name_jp = '" . htmlentities($data['ques_name_jp']) . "'";
        }
        $where = 'qiz_id="' . $data['qiz_id'] . '" and ques_type="' . $data['ques_type'] . '" and (' . $where_ques . ') and ques_isDelete="0"';
        $fetch_chk = $this->func_query->numrows('lms_ques', '', '', '', $where);
        if ($fetch_chk == 0) {
          $this->db->insert('lms_ques', $data);
          $id = $this->db->insert_id();
          if ($id != "") {
            $fetch_qiz = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id="' . $qiz_id . '"');
            if (countArray($fetch_qiz) > 0 && $ques_show == "1") {
              $quiz_numofshown = intval($fetch_qiz['quiz_numofshown']) + 1;
              $dataupdate = array(
                'quiz_numofshown' => $quiz_numofshown,
                'quiz_modifiedby' => $sess['u_id'],
                'quiz_modifieddate' => date('Y-m-d H:i')
              );
              $this->db->where('qiz_id', $qiz_id);
              $this->db->update('lms_qiz', $dataupdate);
            }
            if ($ques_type == "multi" || $ques_type == "2choice") {
              $mul_answer = $mul_answer != "" ? implode(',', $mul_answer) : "";
              $data_mul = array(
                'ques_id'  => $id,
                'mul_c1_th'  => $mul_c1_th,
                'mul_c2_th'  => $mul_c2_th,
                'mul_c3_th'  => $mul_c3_th,
                'mul_c4_th'  => $mul_c4_th,
                'mul_c5_th'  => $mul_c5_th,
                'mul_c1_eng'  => $mul_c1_eng,
                'mul_c2_eng'  => $mul_c2_eng,
                'mul_c3_eng'  => $mul_c3_eng,
                'mul_c4_eng'  => $mul_c4_eng,
                'mul_c5_eng'  => $mul_c5_eng,
                'mul_c1_jp'  => $mul_c1_jp,
                'mul_c2_jp'  => $mul_c2_jp,
                'mul_c3_jp'  => $mul_c3_jp,
                'mul_c4_jp'  => $mul_c4_jp,
                'mul_c5_jp'  => $mul_c5_jp,
                'mul_answer'  => $mul_answer,
                'mul_createby'  => $sess['u_id'],
                'mul_createdate'  => date('Y-m-d H:i'),
                'mul_modifiedby'  => $sess['u_id'],
                'mul_modifieddate'  => date('Y-m-d H:i')
              );
              $this->db->insert('lms_ques_mul', $data_mul);
            }
            // $this->lg->record('Quiz', 'Create Question: '.htmlentities($quesname).'('.$id.') of Quiz : '.$qizname.'('.$qiz_id.')');
            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        $this->db->where('ques_id', $_REQUEST['ques_id']);
        $this->db->update('lms_ques', $data);

        /*$fetch_qiz = $this->func_query->query_row('lms_qiz','','','','qiz_id="'.$qiz_id.'"');
                if(countArray($fetch_qiz)>0&&$ques_show=="1"){
                  $quiz_numofshown = intval($fetch_qiz['quiz_numofshown'])-1;
                  if($quiz_numofshown<0){
                    $quiz_numofshown = 0;
                  }
                  $dataupdate = array(
                      'quiz_numofshown' => $quiz_numofshown,
                      'quiz_modifiedby' => $sess['u_id'],
                      'quiz_modifieddate' => date('Y-m-d H:i')
                  );
                  $this->db->where('qiz_id',$qiz_id);
                  $this->db->update('lms_qiz',$dataupdate);
                }*/
        if ($ques_type == "multi" || $ques_type == "2choice") {
          $fetch_mul = $this->func_query->query_row('lms_ques_mul', '', '', '', 'ques_id="' . $_REQUEST['ques_id'] . '"');
          $mul_answer = $mul_answer != "" ? implode(',', $mul_answer) : "";
          $data_mul = array(
            'ques_id'  => $_REQUEST['ques_id'],
            'mul_c1_th'  => $mul_c1_th,
            'mul_c2_th'  => $mul_c2_th,
            'mul_c3_th'  => $mul_c3_th,
            'mul_c4_th'  => $mul_c4_th,
            'mul_c5_th'  => $mul_c5_th,
            'mul_c1_eng'  => $mul_c1_eng,
            'mul_c2_eng'  => $mul_c2_eng,
            'mul_c3_eng'  => $mul_c3_eng,
            'mul_c4_eng'  => $mul_c4_eng,
            'mul_c5_eng'  => $mul_c5_eng,
            'mul_c1_jp'  => $mul_c1_jp,
            'mul_c2_jp'  => $mul_c2_jp,
            'mul_c3_jp'  => $mul_c3_jp,
            'mul_c4_jp'  => $mul_c4_jp,
            'mul_c5_jp'  => $mul_c5_jp,
            'mul_answer'  => $mul_answer,
            'mul_modifiedby'  => $sess['u_id'],
            'mul_modifieddate'  => date('Y-m-d H:i')
          );
          if (countArray($fetch_mul) > 0) {
            $this->db->where('ques_id', $_REQUEST['ques_id']);
            $this->db->update('lms_ques_mul', $data_mul);
          } else {
            $data_mul['mul_createby'] = $sess['u_id'];
            $data_mul['mul_createdate'] = date('Y-m-d H:i');
            $this->db->insert('lms_ques_mul', $data_mul);
          }
        } else {
          $this->db->where('ques_id', $_REQUEST['ques_id']);
          $this->db->delete('lms_ques_mul');
        }
        // $this->lg->record('Quiz', 'Update Question: '.htmlentities($quesname).'('.$_REQUEST['ques_id'].') of Quiz : '.$qizname.'('.$qiz_id.')');
        $output['status'] = "2";
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function insert_question_survey()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_REQUEST) && !empty($sess['emp_c'])) {
      $sv_id = isset($_REQUEST['sv_id_question_create']) ? $_REQUEST['sv_id_question_create'] : "";
      $svde_id = isset($_REQUEST['svde_id']) ? $_REQUEST['svde_id'] : "";
      $svde_header_th = isset($_REQUEST['svde_header_th']) ? str_replace("`", "", $_REQUEST['svde_header_th']) : "";
      $svde_header_eng = isset($_REQUEST['svde_header_eng']) ? str_replace("`", "", $_REQUEST['svde_header_eng']) : "";
      $svde_header_jp = isset($_REQUEST['svde_header_jp']) ? str_replace("`", "", $_REQUEST['svde_header_jp']) : "";
      $svde_name_th = isset($_REQUEST['svde_name_th']) ? str_replace("`", "", $_REQUEST['svde_name_th']) : "";
      $svde_info_th = isset($_REQUEST['svde_info_th']) ? str_replace("`", "", $_REQUEST['svde_info_th']) : "";
      $svde_name_eng = isset($_REQUEST['svde_name_eng']) ? str_replace("`", "", $_REQUEST['svde_name_eng']) : "";
      $svde_info_eng = isset($_REQUEST['svde_info_eng']) ? str_replace("`", "", $_REQUEST['svde_info_eng']) : "";
      $svde_name_jp = isset($_REQUEST['svde_name_jp']) ? str_replace("`", "", $_REQUEST['svde_name_jp']) : "";
      $svde_info_jp = isset($_REQUEST['svde_info_jp']) ? str_replace("`", "", $_REQUEST['svde_info_jp']) : "";
      $svde_type = isset($_REQUEST['svde_type']) ? $_REQUEST['svde_type'] : "";
      $svde_status = isset($_REQUEST['svde_status']) ? $_REQUEST['svde_status'] : "0";
      $svde_isMultichoice = isset($_REQUEST['svde_isMultichoice']) ? $_REQUEST['svde_isMultichoice'] : "0";
      $svde_isSpecify = isset($_REQUEST['svde_isSpecify']) ? $_REQUEST['svde_isSpecify'] : "0";
      $svde_specify_name_th = isset($_REQUEST['svde_specify_name_th']) ? str_replace("`", "", $_REQUEST['svde_specify_name_th']) : "";
      $svde_specify_name_eng = isset($_REQUEST['svde_specify_name_eng']) ? str_replace("`", "", $_REQUEST['svde_specify_name_eng']) : "";
      $svde_specify_name_jp = isset($_REQUEST['svde_specify_name_jp']) ? str_replace("`", "", $_REQUEST['svde_specify_name_jp']) : "";

      $arrSearchString = array("\n", "\r", "`");
      $mul_c1_th = isset($_REQUEST['mul_c1_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c1_th']) : "";
      $mul_c2_th = isset($_REQUEST['mul_c2_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c2_th']) : "";
      $mul_c3_th = isset($_REQUEST['mul_c3_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c3_th']) : "";
      $mul_c4_th = isset($_REQUEST['mul_c4_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c4_th']) : "";
      $mul_c5_th = isset($_REQUEST['mul_c5_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c5_th']) : "";
      $mul_c6_th = isset($_REQUEST['mul_c6_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c6_th']) : "";
      $mul_c7_th = isset($_REQUEST['mul_c7_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c7_th']) : "";
      $mul_c8_th = isset($_REQUEST['mul_c8_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c8_th']) : "";
      $mul_c9_th = isset($_REQUEST['mul_c9_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c9_th']) : "";
      $mul_c10_th = isset($_REQUEST['mul_c10_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c10_th']) : "";
      $mul_c11_th = isset($_REQUEST['mul_c11_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c11_th']) : "";
      $mul_c12_th = isset($_REQUEST['mul_c12_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c12_th']) : "";
      $mul_c13_th = isset($_REQUEST['mul_c13_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c13_th']) : "";
      $mul_c14_th = isset($_REQUEST['mul_c14_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c14_th']) : "";
      $mul_c15_th = isset($_REQUEST['mul_c15_th']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c15_th']) : "";
      $mul_c1_eng = isset($_REQUEST['mul_c1_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c1_eng']) : "";
      $mul_c2_eng = isset($_REQUEST['mul_c2_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c2_eng']) : "";
      $mul_c3_eng = isset($_REQUEST['mul_c3_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c3_eng']) : "";
      $mul_c4_eng = isset($_REQUEST['mul_c4_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c4_eng']) : "";
      $mul_c5_eng = isset($_REQUEST['mul_c5_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c5_eng']) : "";
      $mul_c6_eng = isset($_REQUEST['mul_c6_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c6_eng']) : "";
      $mul_c7_eng = isset($_REQUEST['mul_c7_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c7_eng']) : "";
      $mul_c8_eng = isset($_REQUEST['mul_c8_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c8_eng']) : "";
      $mul_c9_eng = isset($_REQUEST['mul_c9_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c9_eng']) : "";
      $mul_c10_eng = isset($_REQUEST['mul_c10_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c10_eng']) : "";
      $mul_c11_eng = isset($_REQUEST['mul_c11_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c11_eng']) : "";
      $mul_c12_eng = isset($_REQUEST['mul_c12_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c12_eng']) : "";
      $mul_c13_eng = isset($_REQUEST['mul_c13_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c13_eng']) : "";
      $mul_c14_eng = isset($_REQUEST['mul_c14_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c14_eng']) : "";
      $mul_c15_eng = isset($_REQUEST['mul_c15_eng']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c15_eng']) : "";
      $mul_c1_jp = isset($_REQUEST['mul_c1_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c1_jp']) : "";
      $mul_c2_jp = isset($_REQUEST['mul_c2_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c2_jp']) : "";
      $mul_c3_jp = isset($_REQUEST['mul_c3_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c3_jp']) : "";
      $mul_c4_jp = isset($_REQUEST['mul_c4_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c4_jp']) : "";
      $mul_c5_jp = isset($_REQUEST['mul_c5_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c5_jp']) : "";
      $mul_c6_jp = isset($_REQUEST['mul_c6_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c6_jp']) : "";
      $mul_c7_jp = isset($_REQUEST['mul_c7_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c7_jp']) : "";
      $mul_c8_jp = isset($_REQUEST['mul_c8_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c8_jp']) : "";
      $mul_c9_jp = isset($_REQUEST['mul_c9_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c9_jp']) : "";
      $mul_c10_jp = isset($_REQUEST['mul_c10_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c10_jp']) : "";
      $mul_c11_jp = isset($_REQUEST['mul_c11_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c11_jp']) : "";
      $mul_c12_jp = isset($_REQUEST['mul_c12_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c12_jp']) : "";
      $mul_c13_jp = isset($_REQUEST['mul_c13_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c13_jp']) : "";
      $mul_c14_jp = isset($_REQUEST['mul_c14_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c14_jp']) : "";
      $mul_c15_jp = isset($_REQUEST['mul_c15_jp']) ? str_replace($arrSearchString, "", $_REQUEST['mul_c15_jp']) : "";
      $fetch_survey = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $sv_id . '"');
      /*$arr_update = array('cos_modifieddate'=>date('Y-m-d H:i:s'));
        $this->db->where('cos_id',$fetch_survey['cos_id']);
        $this->db->update('lms_cos',$arr_update);*/
      if ($fetch_survey['sv_isHeader'] == "0") {
        $svde_header_th = "";
        $svde_header_eng = "";
        $svde_header_jp = "";
      }
      $data = array(
        'sv_id' => $sv_id,
        'svde_header_th' => $svde_header_th,
        'svde_header_eng' => $svde_header_eng,
        'svde_header_jp' => $svde_header_jp,
        'svde_name_th' => $svde_name_th,
        'svde_info_th' => $svde_info_th,
        'svde_name_eng' => $svde_name_eng,
        'svde_info_eng' => $svde_info_eng,
        'svde_name_jp' => $svde_name_jp,
        'svde_info_jp' => $svde_info_jp,
        'svde_type' => $svde_type,
        'svde_isMultichoice' => $svde_isMultichoice,
        'svde_isSpecify' => $svde_isSpecify,
        'svde_specify_name_th' => $svde_specify_name_th,
        'svde_specify_name_eng' => $svde_specify_name_eng,
        'svde_specify_name_jp' => $svde_specify_name_jp,
        'svde_status' => $svde_status,
        'svde_modifiedby' => $sess['u_id'],
        'svde_modifieddate' => date('Y-m-d H:i')
      );
      $fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $sv_id . '"');
      $sv_title = '';
      $svde_name = '';
      if ($lang == "thai") {
        $sv_title = $fetch_sv['sv_title_th'] != "" ? $fetch_sv['sv_title_th'] : $fetch_sv['sv_title_eng'];
        $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
        $svde_name = $data['svde_name_th'] != "" ? $data['svde_name_th'] : $data['svde_name_eng'];
        $svde_name = $svde_name != "" ? $svde_name : $data['svde_name_jp'];
      } else if ($lang == "english") {
        $sv_title = $fetch_sv['sv_title_eng'] != "" ? $fetch_sv['sv_title_eng'] : $fetch_sv['sv_title_th'];
        $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
        $svde_name = $data['svde_name_eng'] != "" ? $data['svde_name_eng'] : $data['svde_name_th'];
        $svde_name = $svde_name != "" ? $svde_name : $data['svde_name_jp'];
      } else {
        $sv_title = $fetch_sv['sv_title_jp'] != "" ? $fetch_sv['sv_title_jp'] : $fetch_sv['sv_title_eng'];
        $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_th'];
        $svde_name = $data['svde_name_jp'] != "" ? $data['svde_name_jp'] : $data['svde_name_eng'];
        $svde_name = $svde_name != "" ? $svde_name : $data['svde_name_th'];
      }

      if ($_REQUEST['operation_question'] == "Add") {
        $data['svde_createby'] = $sess['u_id'];
        $data['svde_createdate'] = date('Y-m-d H:i');

        $fetch_chk = $this->func_query->numrows('lms_svde', '', '', '', '(svde_name_th = "' . htmlentities($data['svde_name_th'], ENT_QUOTES) . '" and svde_name_eng = "' . htmlentities($data['svde_name_eng'], ENT_QUOTES) . '" and svde_name_jp = "' . htmlentities($data['svde_name_jp'], ENT_QUOTES) . '") and sv_id = "' . $data['sv_id'] . '" and svde_isDelete = "0"');
        if ($fetch_chk == 0) {
          $this->db->insert('lms_svde', $data);
          $id = $this->db->insert_id();
          if ($id != "") {
            if ($svde_type == "multi" || $svde_type == "2choice") {
              $data_mul = array(
                'svde_id'  => $id,
                'mul_c1_th'  => $mul_c1_th,
                'mul_c2_th'  => $mul_c2_th,
                'mul_c3_th'  => $mul_c3_th,
                'mul_c4_th'  => $mul_c4_th,
                'mul_c5_th'  => $mul_c5_th,
                'mul_c6_th'  => $mul_c6_th,
                'mul_c7_th'  => $mul_c7_th,
                'mul_c8_th'  => $mul_c8_th,
                'mul_c9_th'  => $mul_c9_th,
                'mul_c10_th'  => $mul_c10_th,
                'mul_c11_th'  => $mul_c11_th,
                'mul_c12_th'  => $mul_c12_th,
                'mul_c13_th'  => $mul_c13_th,
                'mul_c14_th'  => $mul_c14_th,
                'mul_c15_th'  => $mul_c15_th,
                'mul_c1_eng'  => $mul_c1_eng,
                'mul_c2_eng'  => $mul_c2_eng,
                'mul_c3_eng'  => $mul_c3_eng,
                'mul_c4_eng'  => $mul_c4_eng,
                'mul_c5_eng'  => $mul_c5_eng,
                'mul_c6_eng'  => $mul_c6_eng,
                'mul_c7_eng'  => $mul_c7_eng,
                'mul_c8_eng'  => $mul_c8_eng,
                'mul_c9_eng'  => $mul_c9_eng,
                'mul_c10_eng'  => $mul_c10_eng,
                'mul_c11_eng'  => $mul_c11_eng,
                'mul_c12_eng'  => $mul_c12_eng,
                'mul_c13_eng'  => $mul_c13_eng,
                'mul_c14_eng'  => $mul_c14_eng,
                'mul_c15_eng'  => $mul_c15_eng,
                'mul_c1_jp'  => $mul_c1_jp,
                'mul_c2_jp'  => $mul_c2_jp,
                'mul_c3_jp'  => $mul_c3_jp,
                'mul_c4_jp'  => $mul_c4_jp,
                'mul_c5_jp'  => $mul_c5_jp,
                'mul_c6_jp'  => $mul_c6_jp,
                'mul_c7_jp'  => $mul_c7_jp,
                'mul_c8_jp'  => $mul_c8_jp,
                'mul_c9_jp'  => $mul_c9_jp,
                'mul_c10_jp'  => $mul_c10_jp,
                'mul_c11_jp'  => $mul_c11_jp,
                'mul_c12_jp'  => $mul_c12_jp,
                'mul_c13_jp'  => $mul_c13_jp,
                'mul_c14_jp'  => $mul_c14_jp,
                'mul_c15_jp'  => $mul_c15_jp,
                'mul_createby'  => $sess['u_id'],
                'mul_createdate'  => date('Y-m-d H:i'),
                'mul_modifiedby'  => $sess['u_id'],
                'mul_modifieddate'  => date('Y-m-d H:i')
              );
              $this->db->insert('lms_svde_mul', $data_mul);
            }
            // $this->lg->record('publicSurvey', 'Create Question: '.htmlentities($svde_name, ENT_QUOTES).'('.$id.') of Public Survey : '.$sv_title.'('.$sv_id.')');
            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        $this->db->where('svde_id', $_REQUEST['svde_id']);
        $this->db->update('lms_svde', $data);
        $fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $sv_id . '"');
        // $this->lg->record('publicSurvey', 'Update Question: '.htmlentities($svde_name, ENT_QUOTES).'('.$_REQUEST['svde_id'].') of Public Survey : '.$sv_title.'('.$sv_id.')');

        if ($svde_type == "multi" || $svde_type == "2choice") {
          $fetch_mul = $this->func_query->query_row('lms_svde_mul', '', '', '', 'svde_id="' . $_REQUEST['svde_id'] . '"');
          $data_mul = array(
            'svde_id'  => $_REQUEST['svde_id'],
            'mul_c1_th'  => $mul_c1_th,
            'mul_c2_th'  => $mul_c2_th,
            'mul_c3_th'  => $mul_c3_th,
            'mul_c4_th'  => $mul_c4_th,
            'mul_c5_th'  => $mul_c5_th,
            'mul_c6_th'  => $mul_c6_th,
            'mul_c7_th'  => $mul_c7_th,
            'mul_c8_th'  => $mul_c8_th,
            'mul_c9_th'  => $mul_c9_th,
            'mul_c10_th'  => $mul_c10_th,
            'mul_c11_th'  => $mul_c11_th,
            'mul_c12_th'  => $mul_c12_th,
            'mul_c13_th'  => $mul_c13_th,
            'mul_c14_th'  => $mul_c14_th,
            'mul_c15_th'  => $mul_c15_th,
            'mul_c1_eng'  => $mul_c1_eng,
            'mul_c2_eng'  => $mul_c2_eng,
            'mul_c3_eng'  => $mul_c3_eng,
            'mul_c4_eng'  => $mul_c4_eng,
            'mul_c5_eng'  => $mul_c5_eng,
            'mul_c6_eng'  => $mul_c6_eng,
            'mul_c7_eng'  => $mul_c7_eng,
            'mul_c8_eng'  => $mul_c8_eng,
            'mul_c9_eng'  => $mul_c9_eng,
            'mul_c10_eng'  => $mul_c10_eng,
            'mul_c11_eng'  => $mul_c11_eng,
            'mul_c12_eng'  => $mul_c12_eng,
            'mul_c13_eng'  => $mul_c13_eng,
            'mul_c14_eng'  => $mul_c14_eng,
            'mul_c15_eng'  => $mul_c15_eng,
            'mul_c1_jp'  => $mul_c1_jp,
            'mul_c2_jp'  => $mul_c2_jp,
            'mul_c3_jp'  => $mul_c3_jp,
            'mul_c4_jp'  => $mul_c4_jp,
            'mul_c5_jp'  => $mul_c5_jp,
            'mul_c6_jp'  => $mul_c6_jp,
            'mul_c7_jp'  => $mul_c7_jp,
            'mul_c8_jp'  => $mul_c8_jp,
            'mul_c9_jp'  => $mul_c9_jp,
            'mul_c10_jp'  => $mul_c10_jp,
            'mul_c11_jp'  => $mul_c11_jp,
            'mul_c12_jp'  => $mul_c12_jp,
            'mul_c13_jp'  => $mul_c13_jp,
            'mul_c14_jp'  => $mul_c14_jp,
            'mul_c15_jp'  => $mul_c15_jp,
            'mul_modifiedby'  => $sess['u_id'],
            'mul_modifieddate'  => date('Y-m-d H:i')
          );
          if (countArray($fetch_mul) > 0) {
            $this->db->where('svde_id', $_REQUEST['svde_id']);
            $this->db->update('lms_svde_mul', $data_mul);
          } else {
            $data_mul['mul_createby'] = $sess['u_id'];
            $data_mul['mul_createdate'] = date('Y-m-d H:i');
            $this->db->insert('lms_svde_mul', $data_mul);
          }
        } else {
          $this->db->where('svde_id', $_REQUEST['svde_id']);
          $this->db->delete('lms_svde_mul');
        }
        $output['status'] = "2";
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function insert_videocourse()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $this->load->model('Fetchdata_model', 'fetch', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata("user");
    $emp_c = $sess['emp_c'];
    $this->fetch->loadDB();
    $output = array();
    if (isset($_REQUEST) && !empty($sess['emp_c'])) {
      $cosv_lang = isset($_REQUEST['cosv_lang']) ? implode(',', $_REQUEST['cosv_lang']) : "";
      $cosv_type = isset($_REQUEST['type_media_cosv']) ? $_REQUEST['type_media_cosv'] : "";
      $url_media = isset($_REQUEST['url_media_cosv']) ? $_REQUEST['url_media_cosv'] : "";
      $cosv_th = isset($_REQUEST['cosv_th']) ? $_REQUEST['cosv_th'] : "";
      $cosv_eng = isset($_REQUEST['cosv_eng']) ? $_REQUEST['cosv_eng'] : "";
      $cosv_jp = isset($_REQUEST['cosv_jp']) ? $_REQUEST['cosv_jp'] : "";
      $cosv_id = isset($_REQUEST['cosv_id']) ? $_REQUEST['cosv_id'] : "";
      $operation = isset($_REQUEST['operation_cosv']) ? $_REQUEST['operation_cosv'] : "";


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
      $data = array(
        'cos_id' => $_REQUEST['course_id_cosv'],
        'cosv_type' => $cosv_type,
        'cosv_lang' => $cosv_lang,
        'cosv_modifiedby' => $sess['u_id'],
        'cosv_modifieddate' => date('Y-m-d H:i')
      );
      if ($cosv_type == "1") {
        if ($url_media != "") {
          $arrurl = explode(",", $url_media);
          for ($num_url = 0; $num_url < countArray($arrurl); $num_url++) {
            $url = $this->getYoutubeEmbedUrl($arrurl[$num_url]);
            $fetch_numchk = $this->func_query->numrows('lms_cos_video', '', '', '', 'cos_id="' . $_REQUEST['course_id_cosv'] . '" and cosv_type="' . $cosv_type . '" and cosv_video="' . $url . '" and cosv_isDelete="0"');
            if ($fetch_numchk == 0) {
              $id_youtube = substr($url, 24);
              $input = 'https://img.youtube.com/vi/' . $id_youtube . '/hqdefault.jpg';

              $dirimg = ROOT_DIR . 'uploads/thumbnail/';            // directory in which the image will be saved
              $localfile = 'thumbnailCos_' . date('dmYHis') . '.jpg';         // set image name the same as the file name of the source
              // create the file with the image on the server
              $varyoutube =  json_decode(getContentUrl('http://www.youtube.com/oembed?format=json&url=https://www.youtube.com/watch?v=' . $id_youtube), true);
              if (isset($varyoutube['thumbnail_url']) && $varyoutube['thumbnail_url'] != "") {

                $r = file_put_contents($dirimg . $localfile, getContentUrl($varyoutube['thumbnail_url']));
                if (!$r) {
                  $localfile = "default_cover.jpg";
                }
              } else {
                $localfile = "default_cover.jpg";
              }
              /*$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id_youtube);*/
              $title = isset($ytarr['title']) ? $ytarr['title'] : "";
              /*parse_str($content, $ytarr);*/

              $data['cosv_th'] = $title;
              $data['cosv_eng'] = $title;
              $data['cosv_jp'] = $title;
              $data['cosv_thumbnail'] = $localfile;
              $data['cosv_video'] = $url;
            }
          }
        }
      } else {
        $data['cosv_th'] = $cosv_th;
        $data['cosv_eng'] = $cosv_eng;
        $data['cosv_jp'] = $cosv_jp;

        if (isset($_FILES['cosv_thumbnail']) && $_FILES['cosv_thumbnail'] != "") {
          if (isset($_FILES['cosv_thumbnail'])) {
            $imageSourcePath = $_FILES['cosv_thumbnail']['tmp_name'];
            $path_parts = pathinfo($_FILES['cosv_thumbnail']['name']);
            if (isset($path_parts['extension'])) {
              $cosv_thumbnail = "cosv_thumbnail_" . date('YmdHis') . "." . $path_parts['extension'];

              $imageTargetPath = ROOT_DIR . "uploads/thumbnail/" . $cosv_thumbnail;
              if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
                $data['cosv_thumbnail'] = $cosv_thumbnail;
                if ($operation == "Edit") {
                  $fetch_chk = $this->func_query->query_row('lms_cos_video', '', '', '', 'cosv_id="' . $cosv_id . '"');
                  if ($fetch_chk['cosv_thumbnail'] != "") {
                    if (is_file(ROOT_DIR . "uploads/thumbnail/" . $fetch_chk['cosv_thumbnail'])) {
                      audit_unlink(ROOT_DIR . "uploads/thumbnail/" . $fetch_chk['cosv_thumbnail']);
                    }
                  }
                }
              }
            }
          }
        }

        if (isset($_FILES['cosv_video']) && $_FILES['cosv_video'] != "") {
          if (isset($_FILES['cosv_video'])) {
            $imageSourcePath = $_FILES['cosv_video']['tmp_name'];
            $path_parts = pathinfo($_FILES['cosv_video']['name']);
            if (isset($path_parts['extension'])) {
              $cosv_video = "cosv_video_" . date('YmdHis') . "." . $path_parts['extension'];

              $imageTargetPath = ROOT_DIR . "uploads/cosvideo/" . $cosv_video;
              if (audit_move_uploaded_file($imageSourcePath, $imageTargetPath)) {
                $data['cosv_video'] = $cosv_video;
                if ($operation == "Edit") {
                  $fetch_chk = $this->func_query->query_row('lms_cos_video', '', '', '', 'cosv_id="' . $cosv_id . '"');
                  if ($fetch_chk['cosv_video'] != "") {
                    if (is_file(ROOT_DIR . "uploads/cosvideo/" . $fetch_chk['cosv_video'])) {
                      audit_unlink(ROOT_DIR . "uploads/cosvideo/" . $fetch_chk['cosv_video']);
                    }
                  }
                }
              }
            }
          }
        }
      }
      $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $data['cos_id'] . '"');
      $cos_lang = explode(',', $fetch_cos['cos_lang']);
      $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
      $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
      $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
      $cname = "";
      if ($lang == "thai") {
        if ($fetch_cos['isTH'] == "1") {
          $cname = $fetch_cos['cname_th'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
        }
      } else if ($lang == "english") {
        if ($fetch_cos['isENG'] == "1") {
          $cname = $fetch_cos['cname_eng'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
        }
      } else {
        if ($fetch_cos['isJP'] == "1") {
          $cname = $fetch_cos['cname_jp'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
        }
      }
      if ($_REQUEST['operation_cosv'] == "Add") {
        $data['cosv_createby'] = $sess['u_id'];
        $data['cosv_createdate'] = date('Y-m-d H:i');

        if ($cosv_type == "1") {
          $fetch_chk = $this->func_query->numrows('lms_cos_video', '', '', '', ' cosv_video="' . $data['cosv_video'] . '" and cosv_lang="' . $cosv_lang . '" and cos_id="' . $data['cos_id'] . '" and cosv_isDelete="0"');
        } else {
          $fetch_chk = $this->func_query->numrows('lms_cos_video', '', '', '', ' cosv_th="' . $data['cosv_th'] . '" and cos_id="' . $data['cos_id'] . '" and cosv_isDelete="0"');
        }
        if ($fetch_chk == 0) {
          $this->db->insert('lms_cos_video', $data);
          $id = $this->db->insert_id();
          if ($id != "") {
            // $this->lg->record('Course', 'Create VDO: '.$data['cosv_video'].'('.$id.') of Course: '.$cname.'('.$data['cos_id'].')');
            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        $this->db->where('cosv_id', $cosv_id);
        $this->db->update('lms_cos_video', $data);
        $output['status'] = "2";
        // $this->lg->record('Course', 'Update VDO: '.$data['cosv_video'].'('.$cosv_id.') of Course: '.$cname.'('.$data['cos_id'].')');
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function insert_emptocourse()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $this->load->model('Course_model', 'course', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $this->course->loadDB();
    $msg = "";
    $useri = $this->input->post('useri');
    $cos_id = $this->input->post('cos_id');
    $data = array(
      'useri' => $useri,
      'cos_id' => $cos_id
    );
    $msg = $this->course->create_emptocourse($data);
    $lang = "english";

    $arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
    $this->db->where('cos_id', $cos_id);
    $this->db->update('lms_cos', $arr_update);

    $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
    $fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $cos_id . '"');
    $chkappenable = 1;
    if (date('Y-m-d') < date('Y-m-d', strtotime($fetch_cos_detail['date_start'] ?? ''))) {
      $chkappenable = 0;
    }
    if ($fetch_cos['cos_approve'] == "1" && $fetch_cos['cos_public'] == "1" && $msg == "2" && $chkappenable == 1) {
      $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
      $date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
      if ($lang != "thai") {
        $date = date('d F Y');
      }


      $cos_lang = explode(',', $fetch_cos['cos_lang']);
      $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
      $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
      $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
      $cname = "";
      if ($lang == "thai") {
        if ($fetch_cos['isTH'] == "1") {
          $cname = $fetch_cos['cname_th'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
        }
      } else if ($lang == "english") {
        if ($fetch_cos['isENG'] == "1") {
          $cname = $fetch_cos['cname_eng'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
        }
      } else {
        if ($fetch_cos['isJP'] == "1") {
          $cname = $fetch_cos['cname_jp'];
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
        }
      }

      $period = "Unlimited time"; //label('UnlimitedTime');
      $fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $cos_id . '" and cosde_status="1" and cosde_isDelete="0"');
      if (countArray($fetch_cos_detail) > 0) {
        if ($fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00") {
          // if($lang=="thai"){
          //     $periodstart = $fetch_cos_detail['date_start']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_cos_detail['date_start'])).$thaimonth[intval(date('m',strtotime($fetch_cos_detail['date_start'])))]." ".(date('Y',strtotime($fetch_cos_detail['date_start']))+543)." ".date('H:i',strtotime($fetch_cos_detail['date_start'])):"";
          //     $periodend = $fetch_cos_detail['date_end']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_cos_detail['date_end'])).$thaimonth[intval(date('m',strtotime($fetch_cos_detail['date_end'])))]." ".(date('Y',strtotime($fetch_cos_detail['date_end']))+543)." ".date('H:i',strtotime($fetch_cos_detail['date_end'])):"";
          // }else{
          //     $periodstart = $fetch_cos_detail['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos_detail['date_start'])):"";
          //     $periodend = $fetch_cos_detail['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_cos_detail['date_end'])):"";
          // }
          $periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
          $periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";

          if ($periodstart != "" && $periodend != "") {
            $period = $periodstart . " - " . $periodend;
          }
        }
      }
      $date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
      if ($lang != "thai") {
        $date = date('d F Y');
      }

      $fetch_user = $this->func_query->query_row(
        'lms_emp',
        'lms_usp',
        'lms_usp.emp_id = lms_emp.emp_id',
        '',
        'lms_usp.useri="' . $data['useri'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")'
      );
      $fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="10"');
      // $this->lg->record('Course', 'Assign learner: '.$fetch_user['useri'].' to course : '.$cname.'('.$cos_id.')');

      if (countArray($fetch_formatmail) > 0 && countArray($fetch_user) > 0) {
        $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
        $subject_th = $fetch_formatmail['smf_subject_th'];
        $subject_en = $fetch_formatmail['smf_subject_en'];
        $message_th = $fetch_formatmail['smf_message_th'];
        $message_en = $fetch_formatmail['smf_message_en'];
        $cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
        if ($subject_th != "") {
          $subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
          $subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
          $subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
          $subject_th = str_replace("#coursename", $cname, $subject_th);
          $subject_th = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $cos_id, $subject_th);
          $subject_th = str_replace("#date", $date, $subject_th);
          $subject_th = str_replace("#time", date('H:i'), $subject_th);
          $subject_th = str_replace("#perioddate", $period, $subject_th);
          $subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
          $subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
        }
        if ($subject_en != "") {
          $subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
          $subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
          $subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
          $subject_en = str_replace("#coursename", $cname, $subject_en);
          $subject_en = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $cos_id, $subject_en);
          $subject_en = str_replace("#date", $date, $subject_en);
          $subject_en = str_replace("#time", date('H:i'), $subject_en);
          $subject_en = str_replace("#perioddate", $period, $subject_en);
          $subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
          $subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
        }
        if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
          $img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
        } else {
          $img_val = '';
        }
        if ($message_th != "") {
          $message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
          $message_th = str_replace("#username", $fetch_user['useri'], $message_th);
          $message_th = str_replace("#email", $fetch_user['email'], $message_th);
          $message_th = str_replace("#coursename", $cname, $message_th);
          $message_th = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $cos_id, $message_th);
          $message_th = str_replace("#date", $date, $message_th);
          $message_th = str_replace("#time", date('H:i'), $message_th);
          $message_th = str_replace("#perioddate", $period, $message_th);
          $message_th = str_replace("#image", $img_val, $message_th);
          $message_th = str_replace("#durationofstudy", $cos_hour, $message_th);
          $message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
        }
        if ($message_en != "") {
          $message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
          $message_en = str_replace("#username", $fetch_user['useri'], $message_en);
          $message_en = str_replace("#email", $fetch_user['email'], $message_en);
          $message_en = str_replace("#coursename", $cname, $message_en);
          $message_en = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $cos_id, $message_en);
          $message_en = str_replace("#date", $date, $message_en);
          $message_en = str_replace("#time", date('H:i'), $message_en);
          $message_en = str_replace("#perioddate", $period, $message_en);
          $message_en = str_replace("#image", $img_val, $message_en);
          $message_en = str_replace("#durationofstudy", $cos_hour, $message_en);
          $message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
        }
        if ($lang == "thai") {
          $this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
        } else {
          $this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
        }
      }
    }
    echo $msg;
  }

  public function insert_emptosurvey()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $sess = $this->session->userdata('user');
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $this->func_query->loadDB();
    $msg = "";
    $useri = $this->input->post('useri');
    $sv_id = $this->input->post('sv_id');
    $output = array();

    $lang = "english";
    $fetch_chk = $this->func_query->query_row('lms_usp', 'lms_emp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.useri = "' . $useri . '" and u_isDelete="0" and (lms_usp.inactivedate="0000-00-00" or lms_usp.inactivedate >= "' . date('Y-m-d H:i') . '")');
    if (countArray($fetch_chk) > 0) {
      $fetch_sv = $this->func_query->query_row('lms_sv_tc', '', '', '', 'sv_id="' . $sv_id . '" and emp_id="' . $fetch_chk['emp_id'] . '" and svtc_isDelete="0"');
      if (countArray($fetch_sv) == 0) {
        $data_insert = array(
          'sv_id' => $sv_id,
          'emp_id' => $fetch_chk['emp_id'],
          'svtc_createby' => $sess['u_id'],
          'svtc_createdate' => date('Y-m-d H:i'),
          'svtc_modifiedby' => $sess['u_id'],
          'svtc_modifieddate' => date('Y-m-d H:i')
        );

        $chkcompany = "1";
        if ($sess['ug_id'] != "1" && $sess['com_admin'] == "com_associated") {
          if ($fetch_chk['com_id'] != $sess['com_id']) {
            $chkcompany = "0";
          }
        }
        if ($chkcompany == "1") {

          $this->db->insert('lms_sv_tc', $data_insert);

          $fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $_REQUEST['sv_id'] . '"');
          $chkappenable = 1;
          if ($fetch_sv['sv_open'] != "0000-00-00 00:00:00" && date('Y-m-d') < date('Y-m-d', strtotime($fetch_sv['sv_open']))) {
            $chkappenable = 0;
          }
          $sv_title = "";
          $sv_lang = explode(',', $fetch_sv['sv_lang']);
          $fetch_sv['isTH'] = in_array('th', $sv_lang) ? "1" : "0";
          $fetch_sv['isENG'] = in_array('eng', $sv_lang) ? "1" : "0";
          $fetch_sv['isJP'] = in_array('jp', $sv_lang) ? "1" : "0";
          if ($lang == "thai") {

            $fetch_sv['select_lang'] = 'th';
            $fetch_sv['is_lang_user_th'] = 'selected';
            if ($fetch_sv['isTH'] == "1") {
              $sv_title = $fetch_sv['sv_title_th'];
            } else {
              if ($sv_title == "" && $fetch_sv['isENG'] == "1") {
                $sv_title = $fetch_sv['sv_title_eng'];
              }
              if ($sv_title == "" && $fetch_sv['isJP'] == "1") {
                $sv_title = $fetch_sv['sv_title_jp'];
              }
            }
          } else if ($lang == "english") {
            $fetch_sv['select_lang'] = 'eng';
            $fetch_sv['is_lang_user_eng'] = 'selected';
            if ($fetch_sv['isENG'] == "1") {
              $sv_title = $fetch_sv['sv_title_eng'];
            } else {
              if ($sv_title == "" && $fetch_sv['isTH'] == "1") {
                $sv_title = $fetch_sv['sv_title_th'];
              }
              if ($sv_title == "" && $fetch_sv['isJP'] == "1") {
                $sv_title = $fetch_sv['sv_title_jp'];
              }
            }
          } else {
            $fetch_sv['select_lang'] = 'jp';
            $fetch_sv['is_lang_user_jp'] = 'selected';
            if ($fetch_sv['isJP'] == "1") {
              $sv_title = $fetch_sv['sv_title_jp'];
            } else {
              if ($sv_title == "" && $fetch_sv['isENG'] == "1") {
                $sv_title = $fetch_sv['sv_title_eng'];
              }
              if ($sv_title == "" && $fetch_sv['isTH'] == "1") {
                $sv_title = $fetch_sv['sv_title_th'];
              }
            }
          }
          if ($fetch_sv['sv_approve'] == "1" && $chkappenable == 1) {
            $fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_emp.emp_id="' . $fetch_chk['emp_id'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")');
            // if($lang=="thai"){
            //     $periodstart = $fetch_sv['sv_open']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_sv['sv_open'])).$thaimonth[intval(date('m',strtotime($fetch_sv['sv_open'])))]." ".(date('Y',strtotime($fetch_sv['sv_open']))+543)." ".date('H:i',strtotime($fetch_sv['sv_open'])):"";
            //     $periodend = $fetch_sv['sv_end']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_sv['sv_end'])).$thaimonth[intval(date('m',strtotime($fetch_sv['sv_end'])))]." ".(date('Y',strtotime($fetch_sv['sv_end']))+543)." ".date('H:i',strtotime($fetch_sv['sv_end'])):"";
            // }else{
            //     $periodstart = $fetch_sv['sv_open']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_sv['sv_open'])):"";
            //     $periodend = $fetch_sv['sv_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_sv['sv_end'])):"";
            // }
            $periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_open'])) : "";
            $periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_end'])) : "";
            $period = "Unlimited time"; //label('UnlimitedTime');
            if ($periodstart != "" && $periodend != "") {
              $period = $periodstart . " - " . $periodend;
            }
            $date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
            if ($lang != "thai") {
              $date = date('d F Y');
            }
            $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
            $fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="11"');
            if (countArray($fetch_formatmail) > 0 && countArray($fetch_user) > 0) {
              $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
              $subject_th = $fetch_formatmail['smf_subject_th'];
              $subject_en = $fetch_formatmail['smf_subject_en'];
              $message_th = $fetch_formatmail['smf_message_th'];
              $message_en = $fetch_formatmail['smf_message_en'];
              if ($subject_th != "") {
                $subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
                $subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
                $subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
                $subject_th = str_replace("#coursename", $sv_title, $subject_th);
                $subject_th = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $_REQUEST['sv_id'], $subject_th);
                $subject_th = str_replace("#date", $date, $subject_th);
                $subject_th = str_replace("#time", date('H:i'), $subject_th);
                $subject_th = str_replace("#perioddate", $period, $subject_th);
                $subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
              }
              if ($subject_en != "") {
                $subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
                $subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
                $subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
                $subject_en = str_replace("#coursename", $sv_title, $subject_en);
                $subject_en = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $_REQUEST['sv_id'], $subject_en);
                $subject_en = str_replace("#date", $date, $subject_en);
                $subject_en = str_replace("#time", date('H:i'), $subject_en);
                $subject_en = str_replace("#perioddate", $period, $subject_en);
                $subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
              }
              if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
                $img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
              } else {
                $img_val = '';
              }
              if ($message_th != "") {
                $message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
                $message_th = str_replace("#username", $fetch_user['useri'], $message_th);
                $message_th = str_replace("#email", $fetch_user['email'], $message_th);
                $message_th = str_replace("#coursename", $sv_title, $message_th);
                $message_th = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $_REQUEST['sv_id'], $message_th);
                $message_th = str_replace("#date", $date, $message_th);
                $message_th = str_replace("#time", date('H:i'), $message_th);
                $message_th = str_replace("#perioddate", $period, $message_th);
                $message_th = str_replace("#image", $img_val, $message_th);
                $message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
              }
              if ($message_en != "") {
                $message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
                $message_en = str_replace("#username", $fetch_user['useri'], $message_en);
                $message_en = str_replace("#email", $fetch_user['email'], $message_en);
                $message_en = str_replace("#coursename", $sv_title, $message_en);
                $message_en = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $_REQUEST['sv_id'], $message_en);
                $message_en = str_replace("#date", $date, $message_en);
                $message_en = str_replace("#time", date('H:i'), $message_en);
                $message_en = str_replace("#perioddate", $period, $message_en);
                $message_en = str_replace("#image", $img_val, $message_en);
                $message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
              }
              if ($lang == "thai") {
                $this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
              } else {
                $this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
              }
            }
          }
          // $this->lg->record('PublicSurvey', 'Assign user: '.$fetch_user['useri'].' to survey : '.$sv_title.'('.$_REQUEST['sv_id'].')');
          $output['status'] = "2";
        } else {
          $output['status'] = "0";
        }
      } else {
        $output['status'] = "1";
      }
    } else {
      $output['status'] = "0";
    }
    echo json_encode($output);
  }

  public function upload_student()
  {
    require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $this->load->model('Course_model', 'course', TRUE);
    $this->load->model('Log_model', 'lg', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    date_default_timezone_set("Asia/Bangkok");
    $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $this->course->loadDB();
    $arr_output = array();
    $result_str = "";
    if (countArray($_REQUEST) > 0) {
      if ($_REQUEST['operation_student'] == "Add") {
        $cos_id = $_REQUEST['course_id_student'];
        /*$excel_file = $_FILES["importstudent"]["name"];
        $excel_file = str_replace("&","",$_FILES["importstudent"]["name"]);
        $excel_file = str_replace(" ","_",$excel_file);
        $config['upload_path']          = './uploads/excel/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['file_name'] = $excel_file;
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('importstudent'))
        {*/

        $lang = "english";
        $importstudent = $_FILES["importstudent"]["name"];

        $excel_file = $_FILES['importstudent']['tmp_name'];
        $path_parts = pathinfo($importstudent);
        if (isset($path_parts['extension'])) {
          $excel_path = "importques_" . date('YmdHis') . "." . $path_parts['extension'];

          $excelTargetPath = ROOT_DIR . "uploads/excel/" . $excel_path;
          if (audit_move_uploaded_file($excel_file, $excelTargetPath)) {
            $path = './uploads/excel/' . $excel_path;
            $objPHPExcel = PHPExcel_IOFactory::load($path);
            $result_arr = array();
            $result_arr['success_count'] = 0;
            $result_arr['duplicate_count'] = 0;
            $result_arr['error_count'] = 0;
            $result_arr['success_data'] = array();
            $result_arr['duplicate_data'] = array();
            $result_arr['error_data'] = array();

            $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');
            $fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $cos_id . '"');
            $cos_lang = explode(',', $fetch_cos['cos_lang']);
            $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
            $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
            $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
            $cname = "";
            if ($lang == "thai") {
              if ($fetch_cos['isTH'] == "1") {
                $cname = $fetch_cos['cname_th'];
              } else {
                if ($cname == "") {
                  $cname = $fetch_cos['cname_eng'];
                }
                if ($cname == "") {
                  $cname = $fetch_cos['cname_jp'];
                }
              }
            } else if ($lang == "english") {
              if ($fetch_cos['isENG'] == "1") {
                $cname = $fetch_cos['cname_eng'];
              } else {
                if ($cname == "") {
                  $cname = $fetch_cos['cname_th'];
                }
                if ($cname == "") {
                  $cname = $fetch_cos['cname_jp'];
                }
              }
            } else {
              if ($fetch_cos['isJP'] == "1") {
                $cname = $fetch_cos['cname_jp'];
              } else {
                if ($cname == "") {
                  $cname = $fetch_cos['cname_eng'];
                }
                if ($cname == "") {
                  $cname = $fetch_cos['cname_th'];
                }
              }
            }
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
              $highestRow         = $worksheet->getHighestRow(); // e.g. 10
              $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
              $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
              $output_array = array();

              for ($row = 2; $row <= $highestRow; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                  $cell = $worksheet->getCellByColumnAndRow($col, $row);
                  $val = $cell->getValue();
                  $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                  if ($col == 0) {
                    if ($val != "") {
                      if (!in_array($val, $output_array)) {
                        array_push($output_array, $val);
                      }
                    }
                  }
                }
              }
              if (countArray($output_array) > 0) {
                for ($i = 0; $i < countArray($output_array); $i++) {
                  if ($output_array[$i] != "" && $output_array[$i] != "Employee ID") {
                    $data = array(
                      'useri' => $output_array[$i],
                      'cos_id' => $cos_id
                    );
                    $msg = $this->course->create_emptocourse($data);
                    $chkappenable = 1;
                    if (isset($fetch_cos_detail['date_start']) && !checkDatetimeIsNull($fetch_cos_detail['date_start'])) {
                        if (date('Y-m-d') < date('Y-m-d', strtotime($fetch_cos_detail['date_start']))) {
                            $chkappenable = 0;
                        }
                    }
                    if ($fetch_cos['cos_approve'] == "1" && $fetch_cos['cos_public'] == "1" && $msg == "2" && $chkappenable == 1) {
                      $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
                      $date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
                      if ($lang != "thai") {
                        $date = date('d F Y');
                      }

                      $period = "Unlimited time"; //label('UnlimitedTime');
                      $fetch_cos_detail = $this->func_query->query_row('lms_cos_detail', '', '', '', 'cos_id="' . $cos_id . '" and cosde_status="1" and cosde_isDelete="0"');
                      if (countArray($fetch_cos_detail) > 0) {
                        if ($fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" && $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00") {
                          if ($lang == "thai") {
                            $periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_cos_detail['date_start'])) . $thaimonth[intval(date('m', strtotime($fetch_cos_detail['date_start'])))] . " " . (date('Y', strtotime($fetch_cos_detail['date_start'])) + 543) . " " . date('H:i', strtotime($fetch_cos_detail['date_start'])) : "";
                            $periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d ', strtotime($fetch_cos_detail['date_end'])) . $thaimonth[intval(date('m', strtotime($fetch_cos_detail['date_end'])))] . " " . (date('Y', strtotime($fetch_cos_detail['date_end'])) + 543) . " " . date('H:i', strtotime($fetch_cos_detail['date_end'])) : "";
                          } else {
                            $periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
                            $periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";
                          }

                          $periodstart = $fetch_cos_detail['date_start'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_start'])) : "";
                          $periodend = $fetch_cos_detail['date_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_cos_detail['date_end'])) : "";
                          if ($periodstart != "" && $periodend != "") {
                            $period = $periodstart . " - " . $periodend;
                          }
                        }
                      }
                      $date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
                      if ($lang != "thai") {
                        $date = date('d F Y');
                      }
                      $fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_usp.useri="' . $data['useri'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")');
                      $fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="10"');
                      if (countArray($fetch_formatmail) > 0 && countArray($fetch_user) > 0) {
                        $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
                        $subject_th = $fetch_formatmail['smf_subject_th'];
                        $subject_en = $fetch_formatmail['smf_subject_en'];
                        $message_th = $fetch_formatmail['smf_message_th'];
                        $message_en = $fetch_formatmail['smf_message_en'];
                        $cos_hour = intval($fetch_cos['cos_hour']) > 0 ? $fetch_cos['cos_hour'] : "No information";
                        if ($subject_th != "") {
                          $subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
                          $subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
                          $subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
                          $subject_th = str_replace("#coursename", $cname, $subject_th);
                          $subject_th = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $cos_id, $subject_th);
                          $subject_th = str_replace("#date", $date, $subject_th);
                          $subject_th = str_replace("#time", date('H:i'), $subject_th);
                          $subject_th = str_replace("#perioddate", $period, $subject_th);
                          $subject_th = str_replace("#durationofstudy", $cos_hour, $subject_th);
                          $subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
                        }
                        if ($subject_en != "") {
                          $subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
                          $subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
                          $subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
                          $subject_en = str_replace("#coursename", $cname, $subject_en);
                          $subject_en = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $cos_id, $subject_en);
                          $subject_en = str_replace("#date", $date, $subject_en);
                          $subject_en = str_replace("#time", date('H:i'), $subject_en);
                          $subject_en = str_replace("#perioddate", $period, $subject_en);
                          $subject_en = str_replace("#durationofstudy", $cos_hour, $subject_en);
                          $subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
                        }
                        if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
                          $img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
                        } else {
                          $img_val = '';
                        }
                        if ($message_th != "") {
                          $message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
                          $message_th = str_replace("#username", $fetch_user['useri'], $message_th);
                          $message_th = str_replace("#email", $fetch_user['email'], $message_th);
                          $message_th = str_replace("#coursename", $cname, $message_th);
                          $message_th = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $cos_id, $message_th);
                          $message_th = str_replace("#date", $date, $message_th);
                          $message_th = str_replace("#time", date('H:i'), $message_th);
                          $message_th = str_replace("#perioddate", $period, $message_th);
                          $message_th = str_replace("#image", $img_val, $message_th);
                          $message_th = str_replace("#durationofstudy", $cos_hour, $message_th);
                          $message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
                        }
                        if ($message_en != "") {
                          $message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
                          $message_en = str_replace("#username", $fetch_user['useri'], $message_en);
                          $message_en = str_replace("#email", $fetch_user['email'], $message_en);
                          $message_en = str_replace("#coursename", $cname, $message_en);
                          $message_en = str_replace("#link_frontend", base_url() . "coursemain/detail/" . $cos_id, $message_en);
                          $message_en = str_replace("#date", $date, $message_en);
                          $message_en = str_replace("#time", date('H:i'), $message_en);
                          $message_en = str_replace("#perioddate", $period, $message_en);
                          $message_en = str_replace("#image", $img_val, $message_en);
                          $message_en = str_replace("#durationofstudy", $cos_hour, $message_en);
                          $message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
                        }
                        if ($lang == "thai") {
                          $this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
                        } else {
                          $this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
                        }
                      }
                    }
                    if ($msg == "1") {
                      $result_arr['duplicate_count']++;
                      array_push($result_arr['duplicate_data'], $output_array[$i]);
                    } else if ($msg == "2") {
                      $result_arr['success_count']++;
                      array_push($result_arr['success_data'], $output_array[$i]);
                    } else {
                      $result_arr['error_count']++;
                      if ($msg == "4") {
                        array_push($result_arr['error_data'], $output_array[$i] . " (" . label('lrn_p_regis_sub') . ")");
                      } else {
                        if ($msg == "3835") {
                          array_push($result_arr['error_data'], $output_array[$i] . " (" . label('add_emptocourse_error') . ")");
                        } else {
                          array_push($result_arr['error_data'], $output_array[$i]);
                        }
                      }
                    }
                  }
                }
              }

              $result_str = "";
              $resultCount_str = "";
              $result_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
              $resultCount_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
              if (countArray($result_arr['success_data']) > 0) {
                $result_str .= "<ol>";
                for ($i = 0; $i < countArray($result_arr['success_data']); $i++) {
                  if ($result_arr['success_data'][$i] != "") {
                    $result_str .= "<li>" . $result_arr['success_data'][$i] . "</li>";
                  }
                }
                $result_str .= "</ol><hr><br>";
              }
              $result_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
              $resultCount_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
              if (countArray($result_arr['duplicate_data']) > 0) {
                $result_str .= "<ol>";
                for ($i = 0; $i < countArray($result_arr['duplicate_data']); $i++) {
                  if ($result_arr['duplicate_data'][$i] != "") {
                    $result_str .= "<li>" . $result_arr['duplicate_data'][$i] . "</li>";
                  }
                }
                $result_str .= "</ol><hr><br>";
              }
              $result_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
              $resultCount_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
              if (countArray($result_arr['error_data']) > 0) {
                $result_str .= "<ol>";
                for ($i = 0; $i < countArray($result_arr['error_data']); $i++) {
                  if ($result_arr['error_data'][$i] != "") {
                    $result_str .= "<li>" . $result_arr['error_data'][$i] . "</li>";
                  }
                }
                $result_str .= "</ol><br>";
              }
            }
            // $this->lg->record('Course', 'Import learner to course : '.$cname.'('.$cos_id.') Result:'.$resultCount_str);
            $arr_output['status'] = "2";
            $arr_output['result'] = $result_str;
          } else {
            $arr_output['status'] = "0";
          }
        } else {
          $arr_output['status'] = "0";
        }
      } else {
        $arr_output['status'] = "0";
      }
    } else {
      $arr_output['status'] = "0";
    }
    echo json_encode($arr_output);
  }

  public function create_emptosurvey($arr, $status = 0)
  {

    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $sess = $this->session->userdata('user');
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $this->func_query->loadDB();
    $msg = "";
    $useri = $arr['useri'];
    $sv_id = $arr['sv_id'];
    $output = array();
    $fetch_chk = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_emp.emp_id = lms_usp.emp_id', '', 'lms_usp.useri="' . $useri . '" and status="1" and emp_isDelete="0" and (lms_usp.inactivedate="0000-00-00" or lms_usp.inactivedate >= "' . date('Y-m-d H:i') . '")');
    if (countArray($fetch_chk) > 0) {

      $chkcompany = "1";
      if ($sess['ug_id'] != "1" && $sess['com_admin'] == "com_associated") {
        if ($fetch_chk['com_id'] != $sess['com_id']) {
          $chkcompany = "0";
        }
      }
      if ($chkcompany == "1") {
        $fetch_sv = $this->func_query->query_row('lms_sv_tc', '', '', '', 'sv_id="' . $sv_id . '" and emp_id="' . $fetch_chk['emp_id'] . '" and svtc_isDelete="0"');
        if (countArray($fetch_sv) == 0) {
          $data_insert = array(
            'sv_id' => $sv_id,
            'emp_id' => $fetch_chk['emp_id'],
            'svtc_createby' => $sess['u_id'],
            'svtc_createdate' => date('Y-m-d H:i'),
            'svtc_modifiedby' => $sess['u_id'],
            'svtc_modifieddate' => date('Y-m-d H:i')
          );
          $this->db->insert('lms_sv_tc', $data_insert);

          $fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $sv_id . '"');
          $fetch_user = $this->func_query->query_row('lms_emp', 'lms_usp', 'lms_usp.emp_id = lms_emp.emp_id', '', 'lms_emp.emp_id="' . $fetch_chk['emp_id'] . '"');
          // if($lang=="thai"){
          //     $periodstart = $fetch_sv['sv_open']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_sv['sv_open'])).$thaimonth[intval(date('m',strtotime($fetch_sv['sv_open'])))]." ".(date('Y',strtotime($fetch_sv['sv_open']))+543)." ".date('H:i',strtotime($fetch_sv['sv_open'])):"";
          //     $periodend = $fetch_sv['sv_end']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_sv['sv_end'])).$thaimonth[intval(date('m',strtotime($fetch_sv['sv_end'])))]." ".(date('Y',strtotime($fetch_sv['sv_end']))+543)." ".date('H:i',strtotime($fetch_sv['sv_end'])):"";
          // }else{
          //     $periodstart = $fetch_sv['sv_open']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_sv['sv_open'])):"";
          //     $periodend = $fetch_sv['sv_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_sv['sv_end'])):"";
          // }
          $periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_open'])) : "";
          $periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_end'])) : "";
          $period = "Unlimited time"; //label('UnlimitedTime');
          if ($periodstart != "" && $periodend != "") {
            $period = $periodstart . " - " . $periodend;
          }
          if ($lang == "thai") {
            $sv_title = $fetch_sv['sv_title_th'] != "" ? $fetch_sv['sv_title_th'] : $fetch_sv['sv_title_eng'];
            $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
          } else if ($lang == "english") {
            $sv_title = $fetch_sv['sv_title_eng'] != "" ? $fetch_sv['sv_title_eng'] : $fetch_sv['sv_title_th'];
            $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
          } else {
            $sv_title = $fetch_sv['sv_title_jp'] != "" ? $fetch_sv['sv_title_jp'] : $fetch_sv['sv_title_eng'];
            $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_th'];
          }
          // $date = date('d ').$thaimonth[intval(date('m'))]." ".(date('Y')+543);
          //if($lang!="thai"){
          $date = date('d F Y');
          //   }
          $chkappenable = 1;
          if ($fetch_sv['sv_open'] != "0000-00-00 00:00:00" && date('Y-m-d') < date('Y-m-d', strtotime($fetch_sv['sv_open']))) {
            $chkappenable = 0;
          }
          if ($fetch_sv['sv_approve'] == "1" && $chkappenable == 1) {
            $fetch_setmail = $this->func_query->query_row('lms_setting_mail', '', '', '', 'sm_id="1"');
            $fetch_formatmail = $this->func_query->query_row('lms_sendmail_form', '', '', '', 'smf_show="1" and smf_type="11"');
            if (countArray($fetch_formatmail) > 0) {
              $fetch_company = $this->func_query->query_row('lms_company', '', '', '', 'com_id="' . $fetch_user['com_id'] . '"');
              $subject_th = $fetch_formatmail['smf_subject_th'];
              $subject_en = $fetch_formatmail['smf_subject_en'];
              $message_th = $fetch_formatmail['smf_message_th'];
              $message_en = $fetch_formatmail['smf_message_en'];
              if ($subject_th != "") {
                $subject_th = str_replace("#fullname", $fetch_user['fullname_th'], $subject_th);
                $subject_th = str_replace("#username", $fetch_user['useri'], $subject_th);
                $subject_th = str_replace("#email", $fetch_user['email'], $subject_th);
                $subject_th = str_replace("#coursename", $sv_title, $subject_th);
                $subject_th = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $sv_id, $subject_th);
                $subject_th = str_replace("#date", $date, $subject_th);
                $subject_th = str_replace("#time", date('H:i'), $subject_th);
                $subject_th = str_replace("#perioddate", $period, $subject_th);
                $subject_th = str_replace("#companyname", $fetch_company['com_code'], $subject_th);
              }
              if ($subject_en != "") {
                $subject_en = str_replace("#fullname", $fetch_user['fullname_en'], $subject_en);
                $subject_en = str_replace("#username", $fetch_user['useri'], $subject_en);
                $subject_en = str_replace("#email", $fetch_user['email'], $subject_en);
                $subject_en = str_replace("#coursename", $sv_title, $subject_en);
                $subject_en = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $sv_id, $subject_en);
                $subject_en = str_replace("#date", $date, $subject_en);
                $subject_en = str_replace("#time", date('H:i'), $subject_en);
                $subject_en = str_replace("#perioddate", $period, $subject_en);
                $subject_en = str_replace("#companyname", $fetch_company['com_code'], $subject_en);
              }
              if (isset($fetch_formatmail['smf_importimage']) && $fetch_formatmail['smf_importimage'] != "") {
                $img_val = '<img src="' . base_url() . '/uploads/formatmail_img/' . $fetch_formatmail['smf_importimage'] . '" style="max-width:800px">';
              } else {
                $img_val = '';
              }
              if ($message_th != "") {
                $message_th = str_replace("#fullname", $fetch_user['fullname_th'], $message_th);
                $message_th = str_replace("#username", $fetch_user['useri'], $message_th);
                $message_th = str_replace("#email", $fetch_user['email'], $message_th);
                $message_th = str_replace("#coursename", $sv_title, $message_th);
                $message_th = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $sv_id, $message_th);
                $message_th = str_replace("#date", $date, $message_th);
                $message_th = str_replace("#time", date('H:i'), $message_th);
                $message_th = str_replace("#perioddate", $period, $message_th);
                $message_th = str_replace("#image", $img_val, $message_th);
                $message_th = str_replace("#companyname", $fetch_company['com_code'], $message_th);
              }
              if ($message_en != "") {
                $message_en = str_replace("#fullname", $fetch_user['fullname_en'], $message_en);
                $message_en = str_replace("#username", $fetch_user['useri'], $message_en);
                $message_en = str_replace("#email", $fetch_user['email'], $message_en);
                $message_en = str_replace("#coursename", $sv_title, $message_en);
                $message_en = str_replace("#link_frontend", base_url() . "survey/surveyDetail/" . $sv_id, $message_en);
                $message_en = str_replace("#date", $date, $message_en);
                $message_en = str_replace("#time", date('H:i'), $message_en);
                $message_en = str_replace("#perioddate", $period, $message_en);
                $message_en = str_replace("#image", $img_val, $message_en);
                $message_en = str_replace("#companyname", $fetch_company['com_code'], $message_en);
              }
              $lang = "english";
              if ($lang == "thai") {
                $this->db->sendEmail($fetch_user['email'], $message_th, $subject_th, $fetch_setmail);
              } else {
                $this->db->sendEmail($fetch_user['email'], $message_en, $subject_en, $fetch_setmail);
              }
            }
          }
          // $this->lg->record('PublicSurvey', 'Assign user: '.$fetch_user['useri'].' to survey : '.$sv_title.'('.$sv_id.')');
          $msg = "2";
        } else {
          $msg = "1";
        }
      } else {
        $msg = "0";
      }
    } else {
      $msg = "0";
    }
    if ($status == 0) {
      echo $msg;
    } else {
      return $msg;
    }
  }

  public function upload_user_survey()
  {
    require_once(APPPATH . 'libraries/FPDF/Classes/PHPExcel.php');
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $user = $this->session->userdata('user');
    $this->load->model('Course_model', 'course', TRUE);
    $this->load->model('Function_query_model', 'func_query', TRUE);
    $this->load->model('Log_model', 'lg', FALSE);
    $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $this->course->loadDB();
    $arr_output = array();
    $result_str = "";
    if (countArray($_REQUEST) > 0) {
      if ($_REQUEST['operation_student'] == "Add") {
        $sv_id = $_REQUEST['sv_idimport'];
        /*$excel_file = $_FILES["importstudent"]["name"];
        $excel_file = str_replace("&","",$_FILES["importstudent"]["name"]);
        $excel_file = str_replace(" ","_",$excel_file);
        $config['upload_path']          = './uploads/excel/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['file_name'] = $excel_file;
        $this->load->library('upload', $config);*/

        $importstudent = $_FILES["importstudent"]["name"];

        $excel_file = $_FILES['importstudent']['tmp_name'];
        $path_parts = pathinfo($importstudent);
        $excel_path = "importstudent_" . date('YmdHis') . "." . $path_parts['extension'];

        $excelTargetPath = ROOT_DIR . "uploads/excel/" . $excel_path;
        if (audit_move_uploaded_file($excel_file, $excelTargetPath)) {
          $path = './uploads/excel/' . basename($excel_path);
          $objPHPExcel = PHPExcel_IOFactory::load($path);
          $result_arr = array();
          $result_arr['success_count'] = 0;
          $result_arr['duplicate_count'] = 0;
          $result_arr['error_count'] = 0;
          $result_arr['success_data'] = array();
          $result_arr['duplicate_data'] = array();
          $result_arr['error_data'] = array();
          $arr_email = array();

          $fetch_sv = $this->func_query->query_row('lms_sv', '', '', '', 'sv_id="' . $sv_id . '"');

          // if($lang=="thai"){
          //   $periodstart = $fetch_sv['sv_open']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_sv['sv_open'])).$thaimonth[intval(date('m',strtotime($fetch_sv['sv_open'])))]." ".(date('Y',strtotime($fetch_sv['sv_open']))+543)." ".date('H:i',strtotime($fetch_sv['sv_open'])):"";
          //   $periodend = $fetch_sv['sv_end']!="0000-00-00 00:00:00"?date('d ',strtotime($fetch_sv['sv_end'])).$thaimonth[intval(date('m',strtotime($fetch_sv['sv_end'])))]." ".(date('Y',strtotime($fetch_sv['sv_end']))+543)." ".date('H:i',strtotime($fetch_sv['sv_end'])):"";
          // }else{
          //   $periodstart = $fetch_sv['sv_open']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_sv['sv_open'])):"";
          //   $periodend = $fetch_sv['sv_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($fetch_sv['sv_end'])):"";
          // }

          $periodstart = $fetch_sv['sv_open'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_open'])) : "";
          $periodend = $fetch_sv['sv_end'] != "0000-00-00 00:00:00" ? date('d F Y H:i', strtotime($fetch_sv['sv_end'])) : "";
          $period = "Unlimited time"; //label('UnlimitedTime');
          if ($periodstart != "" && $periodend != "") {
            $period = $periodstart . " - " . $periodend;
          }
          if ($lang == "thai") {
            $sv_title = $fetch_sv['sv_title_th'] != "" ? $fetch_sv['sv_title_th'] : $fetch_sv['sv_title_eng'];
            $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
          } else if ($lang == "english") {
            $sv_title = $fetch_sv['sv_title_eng'] != "" ? $fetch_sv['sv_title_eng'] : $fetch_sv['sv_title_th'];
            $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_jp'];
          } else {
            $sv_title = $fetch_sv['sv_title_jp'] != "" ? $fetch_sv['sv_title_jp'] : $fetch_sv['sv_title_eng'];
            $sv_title = $sv_title != "" ? $sv_title : $fetch_sv['sv_title_th'];
          }

          foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            $output_array = array();
            for ($row = 1; $row <= $highestRow; ++$row) {
              for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                if ($col == 0) {
                  if ($val != "") {
                    if (!in_array($val, $output_array)) {
                      array_push($output_array, $val);
                    }
                  }
                }
              }
            }
            if (countArray($output_array) > 0) {
              for ($i = 1; $i < countArray($output_array); $i++) {
                if ($output_array[$i] != "" && $output_array[$i] != "Username (E-mail)" && !in_array($output_array[$i], $arr_email)) {
                  array_push($arr_email, $output_array[$i]);
                  $data = array(
                    'useri' => $output_array[$i],
                    'sv_id' => $sv_id
                  );
                  $msg = $this->create_emptosurvey($data, '1');
                  if ($msg == "1") {
                    $result_arr['duplicate_count']++;
                    array_push($result_arr['duplicate_data'], $output_array[$i]);
                  } else if ($msg == "2") {
                    $fetch_user = $this->func_query->query_row(
                      'lms_emp',
                      'lms_usp',
                      'lms_usp.emp_id = lms_emp.emp_id',
                      '',
                      'lms_usp.useri="' . $data['useri'] . '" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0" and (lms_usp.inactivedate > "' . date('Y-m-d H:i') . '" or lms_usp.inactivedate = "0000-00-00 00:00:00")'
                    );
                    $date = date('d ') . $thaimonth[intval(date('m'))] . " " . (date('Y') + 543);
                    if ($lang != "thai") {
                      $date = date('d F Y');
                    }
                    $chkappenable = 1;
                    if ($fetch_sv['sv_open'] != "0000-00-00 00:00:00" && date('Y-m-d') < date('Y-m-d', strtotime($fetch_sv['sv_open']))) {
                      $chkappenable = 0;
                    }
                    /* if($fetch_sv['sv_approve']=="1"&&$chkappenable==1){
                        $fetch_setmail = $this->func_query->query_row('lms_setting_mail','','','','sm_id="1"');
                        $fetch_formatmail = $this->func_query->query_row('lms_sendmail_form','','','','smf_show="1" and smf_type="11"');
                        if(countArray($fetch_formatmail)>0&&countArray($fetch_user)>0){
                          $fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$fetch_user['com_id'].'"');
                          $subject_th = $fetch_formatmail['smf_subject_th'];
                          $subject_en = $fetch_formatmail['smf_subject_en'];
                          $message_th = $fetch_formatmail['smf_message_th'];
                          $message_en = $fetch_formatmail['smf_message_en'];
                            if($subject_th!=""){
                              $subject_th = str_replace("#fullname",$fetch_user['fullname_th'],$subject_th);
                              $subject_th = str_replace("#username",$fetch_user['useri'],$subject_th);
                              $subject_th = str_replace("#email",$fetch_user['email'],$subject_th);
                              $subject_th = str_replace("#coursename",$sv_title,$subject_th);
                              $subject_th = str_replace("#link_frontend",base_url()."survey/surveyDetail/".$sv_id,$subject_th);
                              $subject_th = str_replace("#date",$date,$subject_th);
                              $subject_th = str_replace("#time",date('H:i'),$subject_th);
                              $subject_th = str_replace("#perioddate",$period,$subject_th);
                              $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
                            }
                            if($subject_en!=""){
                              $subject_en = str_replace("#fullname",$fetch_user['fullname_en'],$subject_en);
                              $subject_en = str_replace("#username",$fetch_user['useri'],$subject_en);
                              $subject_en = str_replace("#email",$fetch_user['email'],$subject_en);
                              $subject_en = str_replace("#coursename",$sv_title,$subject_en);
                              $subject_en = str_replace("#link_frontend",base_url()."survey/surveyDetail/".$sv_id,$subject_en);
                              $subject_en = str_replace("#date",$date,$subject_en);
                              $subject_en = str_replace("#time",date('H:i'),$subject_en);
                              $subject_en = str_replace("#perioddate",$period,$subject_en);
                              $subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
                            }
                            if($message_th!=""){
                              $message_th = str_replace("#fullname",$fetch_user['fullname_th'],$message_th);
                              $message_th = str_replace("#username",$fetch_user['useri'],$message_th);
                              $message_th = str_replace("#email",$fetch_user['email'],$message_th);
                              $message_th = str_replace("#coursename",$sv_title,$message_th);
                              $message_th = str_replace("#link_frontend",base_url()."survey/surveyDetail/".$sv_id,$message_th);
                              $message_th = str_replace("#date",$date,$message_th);
                              $message_th = str_replace("#time",date('H:i'),$message_th);
                              $message_th = str_replace("#perioddate",$period,$message_th);
                              $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
                            }
                            if($message_en!=""){
                              $message_en = str_replace("#fullname",$fetch_user['fullname_en'],$message_en);
                              $message_en = str_replace("#username",$fetch_user['useri'],$message_en);
                              $message_en = str_replace("#email",$fetch_user['email'],$message_en);
                              $message_en = str_replace("#coursename",$sv_title,$message_en);
                              $message_en = str_replace("#link_frontend",base_url()."survey/surveyDetail/".$sv_id,$message_en);
                              $message_en = str_replace("#date",$date,$message_en);
                              $message_en = str_replace("#time",date('H:i'),$message_en);
                              $message_en = str_replace("#perioddate",$period,$message_en);
                              $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
                            }
                            if($lang == "thai") {
                            $this->db->sendEmail( $fetch_user['email'] , $message_th, $subject_th,$fetch_setmail);
                            } else {
                            $this->db->sendEmail( $fetch_user['email'] , $message_en, $subject_en,$fetch_setmail);
                            }
                        }
                    }*/
                    $result_arr['success_count']++;
                    array_push($result_arr['success_data'], $output_array[$i]);
                  } else {
                    $result_arr['error_count']++;
                    array_push($result_arr['error_data'], $output_array[$i]);
                  }
                }
              }
            }
            $resultCount_str = "";
            $result_str = "";
            $result_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
            $resultCount_str .= label('result_success') . " : " . $result_arr['success_count'] . "<br>";
            if (countArray($result_arr['success_data']) > 0) {
              $result_str .= "<ol>";
              for ($i = 0; $i < countArray($result_arr['success_data']); $i++) {
                if ($result_arr['success_data'][$i] != "") {
                  $result_str .= "<li>" . $result_arr['success_data'][$i] . "</li>";
                }
              }
              $result_str .= "</ol><hr><br>";
            }
            $result_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
            $resultCount_str .= label('result_duplicate') . " : " . $result_arr['duplicate_count'] . "<br>";
            if (countArray($result_arr['duplicate_data']) > 0) {
              $result_str .= "<ol>";
              for ($i = 0; $i < countArray($result_arr['duplicate_data']); $i++) {
                if ($result_arr['duplicate_data'][$i] != "") {
                  $result_str .= "<li>" . $result_arr['duplicate_data'][$i] . "</li>";
                }
              }
              $result_str .= "</ol><hr><br>";
            }
            $result_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
            $resultCount_str .= label('result_fail') . " : " . $result_arr['error_count'] . "<br>";
            if (countArray($result_arr['error_data']) > 0) {
              $result_str .= "<ol>";
              for ($i = 0; $i < countArray($result_arr['error_data']); $i++) {
                if ($result_arr['error_data'][$i] != "") {
                  $result_str .= "<li>" . $result_arr['error_data'][$i] . "</li>";
                }
              }
              $result_str .= "</ol><br>";
            }
          }

          // $this->lg->record('PublicSurvey', 'Import user to survey : '.$sv_title.'('.$sv_id.') Result:'.$resultCount_str);
          $arr_output['status'] = "2";
          $arr_output['result'] = $result_str;
        } else {
          $arr_output['status'] = "0";
        }
      } else {
        $arr_output['status'] = "0";
      }
    } else {
      $arr_output['status'] = "0";
    }
    echo json_encode($arr_output);
  }

  public function insert_survey()
  {
    date_default_timezone_set("Asia/Bangkok");
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    $sess = $this->session->userdata('user');
    $this->load->model('Course_model', 'course', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->course->loadDB();
    $this->lg->loadDB();
    $output = array();
    if (countArray($_REQUEST) > 0) {
      $qn_id = isset($_REQUEST['qn_id']) ? $_REQUEST['qn_id'] : '';
      $course_id_survey = isset($_REQUEST['course_id_survey']) ? $_REQUEST['course_id_survey'] : '';
      $sv_lang = isset($_REQUEST['sv_lang']) ? $_REQUEST['sv_lang'] : '';
      $sv_title_th = isset($_REQUEST['sv_title_th']) ? $_REQUEST['sv_title_th'] : '';
      $sv_explanation_th = isset($_REQUEST['sv_explanation_th']) ? $_REQUEST['sv_explanation_th'] : '';
      $sv_title_eng = isset($_REQUEST['sv_title_eng']) ? $_REQUEST['sv_title_eng'] : '';
      $sv_explanation_eng = isset($_REQUEST['sv_explanation_eng']) ? $_REQUEST['sv_explanation_eng'] : '';
      $sv_title_jp = isset($_REQUEST['sv_title_jp']) ? $_REQUEST['sv_title_jp'] : '';
      $sv_explanation_jp = isset($_REQUEST['sv_explanation_jp']) ? $_REQUEST['sv_explanation_jp'] : '';
      $sv_suggestion_status = isset($_REQUEST['sv_suggestion_status']) ? $_REQUEST['sv_suggestion_status'] : '0';
      $sv_status = isset($_REQUEST['sv_status']) ? $_REQUEST['sv_status'] : '0';
      $time_start = isset($_REQUEST['time_start_survey']) ? $_REQUEST['time_start_survey'] . ":00" : '';
      $time_end = isset($_REQUEST['time_end_survey']) ? $_REQUEST['time_end_survey'] . ":00" : '';
      $survey_open_var = isset($_REQUEST['survey_open_var']) ? $_REQUEST['survey_open_var'] . " " . $time_start : '';
      $survey_end_var = isset($_REQUEST['survey_end_var']) ? $_REQUEST['survey_end_var'] . " " . $time_end : '';
      $data = array(
        'cos_id' => $course_id_survey,
        'sv_lang' => $sv_lang,
        'sv_title_th' => $sv_title_th,
        'sv_explanation_th' => $sv_explanation_th,
        'sv_title_eng' => $sv_title_eng,
        'sv_explanation_eng' => $sv_explanation_eng,
        'sv_title_jp' => $sv_title_jp,
        'sv_explanation_jp' => $sv_explanation_jp,
        'sv_suggestion_status' => $sv_suggestion_status,
        'survey_open' => $survey_open_var,
        'survey_end' => $survey_end_var,
        'sv_status' => $sv_status,
        'sv_modifiedby' => $sess['u_id'],
        'sv_modifieddate' => date('Y-m-d H:i')
      );
      $arr_update = array('cos_modifieddate' => date('Y-m-d H:i:s'));
      $this->db->where('cos_id', $course_id_survey);
      $this->db->update('lms_cos', $arr_update);

      $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $course_id_survey . '"');
      $cos_lang = explode(',', $fetch_cos['cos_lang']);
      $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
      $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
      $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
      $cname = "";
      $sv_title = "";
      if ($lang == "thai") {
        if ($fetch_cos['isTH'] == "1") {
          $cname = $fetch_cos['cname_th'];
          $sv_title = $sv_title_th;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_title_eng;
          }
          if ($sv_title == "") {
            $sv_title = $sv_title_jp;
          }
        }
      } else if ($lang == "english") {
        if ($fetch_cos['isENG'] == "1") {
          $cname = $fetch_cos['cname_eng'];
          $sv_title = $sv_title_eng;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_title_th;
          }
          if ($sv_title == "") {
            $sv_title = $sv_title_jp;
          }
        }
      } else {
        if ($fetch_cos['isJP'] == "1") {
          $cname = $fetch_cos['cname_jp'];
          $sv_title = $sv_title_jp;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_title_eng;
          }
          if ($sv_title == "") {
            $sv_title = $sv_title_th;
          }
        }
      }

      if ($_REQUEST['operation_survey'] == "Add") {
        $data['sv_createby'] = $sess['u_id'];
        $data['sv_createdate'] = date('Y-m-d H:i');
        $data['qn_id'] = $qn_id;

        $fetch_chk = $this->func_query->numrows('lms_survey', '', '', '', '(sv_title_th="' . $data['sv_title_th'] . '" and sv_title_eng="' . $data['sv_title_eng'] . '" and sv_title_jp="' . $data['sv_title_jp'] . '") and cos_id="' . $data['cos_id'] . '" and sv_isDelete="0"');
        if ($fetch_chk == 0) {
          $this->db->insert('lms_survey', $data);
          $id = $this->db->insert_id();
          if ($id != "") {
            // $this->lg->record('survey', 'Create Survey: '.$sv_title.'('.$id.') in course '.$cname.'('.$course_id_survey.')');

            if (isset($_REQUEST['qn_id']) && $_REQUEST['qn_id'] != "") {
              $qn_id = $_REQUEST['qn_id'];
              $fetch_data = $this->func_query->query_result('lms_questionnaire_de', '', '', '', 'qn_id="' . $qn_id . '" and qnde_isDelete="0"');
              foreach ($fetch_data as $key_qn => $value_qn) {
                $data_qn = array(
                  'sv_id' => $id,
                  'svde_heading_th' => $data['sv_title_th'] != "" ? $value_qn['qnde_heading_th'] : "",
                  'svde_detail_th' => $data['sv_title_th'] != "" ? $value_qn['qnde_detail_th'] : "",
                  'svde_heading_eng' => $data['sv_title_eng'] != "" ? $value_qn['qnde_heading_eng'] : "",
                  'svde_detail_eng' => $data['sv_title_eng'] != "" ? $value_qn['qnde_detail_eng'] : "",
                  'svde_heading_jp' => $data['sv_title_jp'] != "" ? $value_qn['qnde_heading_jp'] : "",
                  'svde_detail_jp' => $data['sv_title_jp'] != "" ? $value_qn['qnde_detail_jp'] : "",
                  'svde_createby' => $sess['u_id'],
                  'svde_createdate' => date('Y-m-d H:i'),
                  'svde_modifiedby' => $sess['u_id'],
                  'svde_modifieddate' => date('Y-m-d H:i')
                );
                $this->course->create_survey_detail($data_qn);
              }
            }
            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        $this->course->update_survey($data, $_REQUEST['sv_id']);
        // $this->lg->record('survey', 'Create Survey: '.$sv_title.'('.$_REQUEST['sv_id'].') in course '.$cname.'('.$_REQUEST['course_id_survey'].')');
        if (isset($_REQUEST['qn_id']) && $_REQUEST['qn_id'] != "") {
          $qn_id = $_REQUEST['qn_id'];
          $fetch_data = $this->func_query->query_result('lms_questionnaire_de', '', '', '', 'qn_id="' . $qn_id . '" and qnde_isDelete="0"');
          foreach ($fetch_data as $key_qn => $value_qn) {
            $data_qn = array(
              'sv_id' => $_REQUEST['sv_id'],
              'svde_heading_th' => $data['sv_title_th'] != "" ? $value_qn['qnde_heading_th'] : "",
              'svde_detail_th' => $data['sv_title_th'] != "" ? $value_qn['qnde_detail_th'] : "",
              'svde_heading_eng' => $data['sv_title_eng'] != "" ? $value_qn['qnde_heading_eng'] : "",
              'svde_detail_eng' => $data['sv_title_eng'] != "" ? $value_qn['qnde_detail_eng'] : "",
              'svde_heading_jp' => $data['sv_title_jp'] != "" ? $value_qn['qnde_heading_jp'] : "",
              'svde_detail_jp' => $data['sv_title_jp'] != "" ? $value_qn['qnde_detail_jp'] : "",
              'svde_createby' => $sess['u_id'],
              'svde_createdate' => date('Y-m-d H:i'),
              'svde_modifiedby' => $sess['u_id'],
              'svde_modifieddate' => date('Y-m-d H:i')
            );
            $this->course->create_survey_detail($data_qn);
          }
        }
        $output['status'] = "2";
      }
    }
    echo json_encode($output);
  }


  public function insert_survey_detail()
  {
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
    $this->lang->load($lang, $lang);
    date_default_timezone_set("Asia/Bangkok");
    $sess = $this->session->userdata('user');
    $this->load->model('Course_model', 'course', FALSE);
    $this->load->model('Function_query_model', 'func_query', FALSE);
    $this->load->model('Log_model', 'lg', FALSE);
    $this->lg->loadDB();
    $this->course->loadDB();
    if (countArray($_REQUEST) > 0) {

      $sv_id_detail = isset($_REQUEST['sv_id_detail']) ? $_REQUEST['sv_id_detail'] : '';
      $svde_heading_th = isset($_REQUEST['svde_heading_th']) ? $_REQUEST['svde_heading_th'] : '';
      $svde_detail_th = isset($_REQUEST['svde_detail_th']) ? $_REQUEST['svde_detail_th'] : '';
      $svde_heading_eng = isset($_REQUEST['svde_heading_eng']) ? $_REQUEST['svde_heading_eng'] : '';
      $svde_detail_eng = isset($_REQUEST['svde_detail_eng']) ? $_REQUEST['svde_detail_eng'] : '';
      $svde_heading_jp = isset($_REQUEST['svde_heading_jp']) ? $_REQUEST['svde_heading_jp'] : '';
      $svde_detail_jp = isset($_REQUEST['svde_detail_jp']) ? $_REQUEST['svde_detail_jp'] : '';
      $data = array(
        'sv_id' => $sv_id_detail,
        'svde_heading_th' => $svde_heading_th,
        'svde_detail_th' => $svde_detail_th,
        'svde_heading_eng' => $svde_heading_eng,
        'svde_detail_eng' => $svde_detail_eng,
        'svde_heading_jp' => $svde_heading_jp,
        'svde_detail_jp' => $svde_detail_jp,
        'svde_modifiedby' => $sess['u_id'],
        'svde_modifieddate' => date('Y-m-d H:i')
      );
      $sv_data = $this->func_query->query_row('lms_survey', '', '', '', 'sv_id = "' . $sv_id_detail . '"');
      $fetch_cos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $sv_data['cos_id'] . '"');

      $cos_lang = explode(',', $fetch_cos['cos_lang']);
      $fetch_cos['isTH'] = in_array('th', $cos_lang) ? "1" : "0";
      $fetch_cos['isENG'] = in_array('eng', $cos_lang) ? "1" : "0";
      $fetch_cos['isJP'] = in_array('jp', $cos_lang) ? "1" : "0";
      $cname = "";
      $sv_title = "";
      $question = "";
      if ($lang == "thai") {
        if ($fetch_cos['isTH'] == "1") {
          $cname = $fetch_cos['cname_th'];
          $sv_title = $sv_data['sv_title_th'];
          $svde_heading_th = $svde_heading_th != "" ? "[" . $svde_heading_th . "] " : "";
          $question = $svde_heading_th . $svde_detail_th;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_data['sv_title_eng'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_data['sv_title_jp'];
          }
          if ($question == "") {
            $svde_heading_eng = $svde_heading_eng != "" ? "[" . $svde_heading_eng . "] " : "";
            $question = $svde_heading_eng . $svde_detail_eng;
          }
          if ($question == "") {
            $svde_heading_jp = $svde_heading_jp != "" ? "[" . $svde_heading_jp . "] " : "";
            $question = $svde_heading_jp . $svde_detail_jp;
          }
        }
      } else if ($lang == "english") {
        if ($fetch_cos['isENG'] == "1") {
          $cname = $fetch_cos['cname_eng'];
          $sv_title = $sv_data['sv_title_eng'];
          $svde_heading_eng = $svde_heading_eng != "" ? "[" . $svde_heading_eng . "] " : "";
          $question = $svde_heading_eng . $svde_detail_eng;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_jp'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_data['sv_title_th'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_data['sv_title_jp'];
          }
          if ($question == "") {
            $svde_heading_th = $svde_heading_th != "" ? "[" . $svde_heading_th . "] " : "";
            $question = $svde_heading_th . $svde_detail_th;
          }
          if ($question == "") {
            $svde_heading_jp = $svde_heading_jp != "" ? "[" . $svde_heading_jp . "] " : "";
            $question = $svde_heading_jp . $svde_detail_jp;
          }
        }
      } else {
        if ($fetch_cos['isJP'] == "1") {
          $cname = $fetch_cos['cname_jp'];
          $sv_title = $sv_data['sv_title_jp'];
          $svde_heading_jp = $svde_heading_jp != "" ? "[" . $svde_heading_jp . "] " : "";
          $question = $svde_heading_jp . $svde_detail_jp;
        } else {
          if ($cname == "") {
            $cname = $fetch_cos['cname_eng'];
          }
          if ($cname == "") {
            $cname = $fetch_cos['cname_th'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_data['sv_title_eng'];
          }
          if ($sv_title == "") {
            $sv_title = $sv_data['sv_title_th'];
          }
          if ($question == "") {
            $svde_heading_eng = $svde_heading_eng != "" ? "[" . $svde_heading_eng . "] " : "";
            $question = $svde_heading_eng . $svde_detail_eng;
          }
          if ($question == "") {
            $svde_heading_th = $svde_heading_th != "" ? "[" . $svde_heading_th . "] " : "";
            $question = $svde_heading_th . $svde_detail_th;
          }
        }
      }
      if ($_REQUEST['operation_survey_detail'] == "Add") {
        $data['svde_createby'] = $sess['u_id'];
        $data['svde_createdate'] = date('Y-m-d H:i');

        $fetch_chk = $this->func_query->numrows(
          'lms_survey_de',
          '',
          '',
          '',
          'svde_heading_th="' . $data['svde_heading_th'] . '" and svde_detail_th="' . $data['svde_detail_th'] . '" and svde_heading_eng="' . $data['svde_heading_eng'] . '" and svde_detail_eng="' . $data['svde_detail_eng'] .
            '" and svde_heading_jp="' . $data['svde_heading_jp'] . '" and svde_detail_jp="' . $data['svde_detail_jp'] . '" and sv_id="' . $data['sv_id'] . '" and svde_isDelete="0"'
        );
        if ($fetch_chk == 0) {
          $this->db->insert('lms_survey_de', $data);
          $id = $this->db->insert_id();
          if ($id != "") {
            // $this->lg->record('survey', 'Create question: '.$question.'('.$id.') of Survey : '.$sv_title.'('.$sv_id_detail.')');
            $output['status'] = "2";
          } else {
            $output['status'] = "3";
          }
        } else {
          $output['status'] = "1";
        }
      } else {
        $this->course->update_survey_detail($data, $_REQUEST['svde_id']);
        // $this->lg->record('survey', 'Update question: '.$question.'('.$_REQUEST['svde_id'].') of Survey : '.$sv_title.'('.$sv_id_detail.')');
        $output['status'] = "2";
      }
    }
    echo json_encode($output);
  }
}
