<?php
class Fetchdata_model extends CI_Model {

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function loadDB()
        {
          $this->load->database();
        }

        public function closeDB()
        {
          $this->db->close();
        }

        public function checknumber($number){ 
            if($number % 2 == 0){ 
                return "Even";  
            } 
            else{ 
                return "Odd"; 
            } 
        } 

        private function has_course_period_date($value)
        {
          return !empty($value) && $value != "0000-00-00 00:00:00";
        }

        private function format_course_period_date($value, $lang)
        {
          if (!$this->has_course_period_date($value)) {
            return "";
          }
          if ($lang == "thai") {
            return date('d/m/', strtotime($value)) . (date('Y', strtotime($value)) + 543) . " " . date('H:i', strtotime($value));
          }
          return date('d/m/Y H:i', strtotime($value));
        }

        private function format_course_period_range($date_start, $date_end, $lang)
        {
          $periodstart = $this->format_course_period_date($date_start, $lang);
          $periodend = $this->format_course_period_date($date_end, $lang);
          $noEndDate = label('course_no_end_date');
          if ($periodstart != "" && $periodend != "") {
            return $periodstart . " - " . $periodend;
          }
          if ($periodstart != "") {
            return $periodstart . " - " . $noEndDate;
          }
          if ($periodend != "") {
            return $periodend;
          }
          return "-";
        }


        public function fetch_data_coursegroup() {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $page = "managecourse/course_groups";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $com_id = isset($_REQUEST['com_id'])?$_REQUEST['com_id']:"";
          $where = 'lms_cog.com_id="'.$com_id.'" and lms_cog.cg_isDelete="0" and lms_company.com_isDelete="0" and lms_company.com_status="1"';
          if($user['ug_approve']!="1"){
            $where .= ' and cg_approve="1"';
          }
          $num_approve = 0;
          $rechk_approve = $this->func_query->query_result('lms_cog','lms_company','lms_company.com_id = lms_cog.com_id','','lms_cog.com_id="'.$user['com_id'].'" and lms_cog.cg_isDelete="0" and lms_company.com_isDelete="0" and lms_company.com_status="1"');
          if(countArray($rechk_approve)>0){
            foreach ($rechk_approve as $key_approve => $value_approve) {
              $arr_approve = explode(',', $value_approve['cg_approve_by']);
              if(countArray($arr_approve)>0&&in_array($user['u_id'], $arr_approve)){
                $num_approve++;
              }
            }
          }
          $is_approve = $num_approve>0?1:0;
          $fetch = $this->func_query->query_result('lms_cog','lms_company','lms_company.com_id = lms_cog.com_id','',$where,'cg_approve DESC,c_by DESC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            $approve = '<button type="button" title="'.label('d_waitapprove').'" id="'.$value['cg_id'].'" class="btn btn-secondary btn-xs active approve"><i class="mdi mdi-alert text-warning"></i></button>';
            $update = '<button type="button" name="update" id="'.$value['cg_id'].'" title="'.label('m_edit').'" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
            $delete = '<button type="button" name="delete" id="'.$value['cg_id'].'" class="btn btn-danger btn-xs delete" title="'.label('delete').'"><i class="mdi mdi-window-close"></i></button>';
            $output = array();

           // $output['5'] = $value['u_date']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['u_date'])):"<center>-</center>";
            
            if($btn_update!="1"){
                $update = "";
            }
            if($btn_delete!="1"){
                $delete = "";
            }
            $cg_approve_by = explode(",",$value['cg_approve_by']);
            $approve_status = label('d_waitapprove');
            $fetch_approve = $this->func_query->query_row('lms_cog_approve','','','','cg_id ="'.$value['cg_id'].'"','coga_id DESC');
            if(isset($fetch_approve['coga_approve'])){

                if($fetch_approve['coga_approve']=="1"){
                    $approve_status = label('d_approved');
                    $approve = "";
                    if(!in_array($user['ug_id'], array('1','2','6'))){
                      if($user['u_id']!=$value['c_by']){
                        $delete = "";
                        $update = "";
                      }
                    }
                    if($user['ug_id']!="1"){
                      $update = "";
                    }
                }else if($fetch_approve['coga_approve']=="2"){
                  if(!in_array($user['ug_id'], array('1','2','6'))){
                      $delete = "";
                      $update = "";
                  }
                  if(!in_array($user['u_id'], $cg_approve_by)){
                      $approve_status = label('d_waitapprove');
                      $approve = '';
                  }else{
                      $approve = '<button type="button" title="'.label('d_waitapprove').'" id="'.$value['cg_id'].'" class="btn btn-secondary btn-xs active approve"><i class="mdi mdi-alert text-warning"></i></button>';
                  }
                }else{
                  $approve = "";
                  $approve_status = label('d_rejected');
                  if(!in_array($user['ug_id'], array('1','2','6'))){//$user['u_id']!="1"
                    if($user['u_id']!=$value['c_by']){
                      if(!in_array($user['u_id'], $cg_approve_by)){
                        $delete = "";
                        $update = "";
                      }
                    }
                  }
                }
            }else{
              if(!in_array($user['u_id'], $cg_approve_by)){
                  $approve_status = label('d_waitapprove');
                  $approve = '';
              }else{
                  $approve = '<button type="button" title="'.label('d_waitapprove').'" id="'.$value['cg_id'].'" class="btn btn-secondary btn-xs active approve"><i class="mdi mdi-alert text-warning"></i></button>';
              }
              
            }
            $numloop = 1;
            $num_chk = 0;
            if($update==""){
              $num_chk++;
            }
            if($delete==""){
              $num_chk++;
            }
            if($approve==""){
              $num_chk++;
            }
            if($num_chk<3){
              $button_val = "<center>".$update." ".$delete." ".$approve."</center>";
            }else{
              $button_val = "<center>-</center>";
            }
            $output['buttonall'] = $button_val;
           // $output[$numloop] = "<span style='float:right;'>".$num."</span>";$num++;$numloop++;
            /*$output[$numloop] = "<center>".$value['cgcode']."</center>";$numloop++;*/
            $output['cgtitle_en'] = $value['cgtitle_en'];$numloop++;
            $output['cgtitle_th'] = $value['cgtitle_th'];$numloop++;
            $output['cgtitle_jp'] = $value['cgtitle_jp'];$numloop++;
            if($approve_status != label('d_rejected')){
            	if($value['cg_status']=="1"){ 
								$output['cg_status'] = "<center>".label('open')."</center>"; 
							}else{ 
								$output['cg_status'] = "<center>".label('close')."</center>"; 
							}$numloop++;
            }else{
              $output['cg_status'] = "<center>-</center>";$numloop++;
            }

            /*if(intval($value['cg_approve'])==1){
                $approve = "-";
            }*/
            //if($is_approve=="1"){
              $output['approve_status'] = "<center>".$approve_status."</center>";$numloop++;
            //}
              $cos_approveby = "-";
              $cos_approvedate = "<center>-</center>";
            $coga_createdateori = "";
            $fetch_chkapprove = $this->func_query->query_row('lms_cog_approve','','','','cg_id="'.$value['cg_id'].'"','coga_id DESC');
            if(isset($fetch_chkapprove)&&$fetch_chkapprove['coga_approve']=="1"){
              if($lang=="thai"){
              $cos_approvedate = $fetch_chkapprove['coga_createdate']!="0000-00-00 00:00:00"?date('d/m',strtotime($fetch_chkapprove['coga_createdate']))."/".(date('Y',strtotime($fetch_chkapprove['coga_createdate']))+543)." ".date('H:i',strtotime($fetch_chkapprove['coga_createdate'])):"<center>-</center>";
              }else{
              $cos_approvedate = $fetch_chkapprove['coga_createdate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($fetch_chkapprove['coga_createdate'])):"<center>-</center>";
              }
              $coga_createdateori = $fetch_chkapprove['coga_createdate']!="0000-00-00 00:00:00"?$fetch_chkapprove['coga_createdate']:"";
              if($fetch_chkapprove['coga_createby']!=""){
                $fetch_approver = $this->func_query->query_row('lms_usp','lms_emp','lms_usp.emp_id = lms_emp.emp_id','','lms_usp.u_id = "'.$fetch_chkapprove['coga_createby'].'"');
                if(isset($fetch_approver)){
                  $cos_approveby = $lang=="thai"?$fetch_approver['fullname_th']:$fetch_approver['fullname_en'];
                }
              }
            }      

              $arrpprovedate = array(
                'display' => $cos_approvedate,
                'timestamp' => strtotime($coga_createdateori),
              );

              //$output['cos_modified'] = $arr_modified;
            $output['cos_approveby'] = '<center>'.$cos_approveby.'</center>';$numloop++;
            $output['cos_approvedate'] = $arrpprovedate;$numloop++;  
            $count++;
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_courseongoing($com_id,$type) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $this->manage->loadDB();
          $page = "dashboard";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $sess = $this->session->userdata('user');
          $date_now = date('Y-m-d H:i');
          /*if($type=="1"){
            $fetch = $this->func_query->query_result('lms_cos','lms_cos_detail','lms_cos_detail.cos_id = lms_cos.cos_id','LEFT','lms_cos.com_id="'.$com_id.'" and lms_cos.cos_approve="1" and lms_cos.cos_public="1" and lms_cos.cos_isDelete="0" and lms_cos_detail.cosde_isDelete="0" and ((lms_cos_detail.date_start="0000-00-00 00:00:00" and lms_cos_detail.date_end="0000-00-00 00:00:00") or ( lms_cos_detail.date_end >= "'.$date_now.'"))','lms_cos.cos_id DESC','','','','lms_cos.cos_id');
          }else{*/
            $fetch = $this->func_query->query_result('lms_cos','','','','lms_cos.cos_approve="1" and lms_cos.cos_public="1" and lms_cos.cos_status="1" and lms_cos.cos_isDelete="0"','lms_cos.cos_id DESC','','','','lms_cos.cos_id');

            if(countArray($fetch)>0){
              foreach ($fetch as $key_list => $value_list) {
                $value_chk = 1;
                $fetch[$key_list]['date_start'] = "0000-00-00 00:00:00";
                $fetch[$key_list]['date_end'] = "0000-00-00 00:00:00";
                $fetch_detail = $this->func_query->query_row('lms_cos_detail','','','','cos_id="'.$value_list['cos_id'].'" and lms_cos_detail.cosde_isDelete="0"');
                if(isset($fetch_detail['date_start'])){
                  if((!empty($fetch_detail['date_start'])&&$fetch_detail['date_start']!="0000-00-00 00:00:00"&&date('Y-m-d H:i',strtotime($fetch_detail['date_start']))>date('Y-m-d H:i'))||(!empty($fetch_detail['date_end'])&&$fetch_detail['date_end']!="0000-00-00 00:00:00"&&date('Y-m-d H:i',strtotime($fetch_detail['date_end']))<date('Y-m-d H:i'))){
                    $value_chk = 0;
                  }else{
                    $fetch[$key_list]['date_start'] = $fetch_detail['date_start'];
                    $fetch[$key_list]['date_end'] = $fetch_detail['date_end'];
                  }
                }
                if($value_chk==1){
                  $fetch_status = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0"');
                  if(isset($fetch_status['cosen_status'])){
                    if($fetch_status['cosen_status']=="1" && $fetch_status['cosen_status_sub']=="1"){
                      unset($fetch[$key_list]);
                    }
                  }
                  if(isset($fetch[$key_list])){
                              $result_chkcg = $this->func_query->numrows('lms_cosincg','lms_cog','lms_cosincg.cg_id = lms_cog.cg_id','','lms_cosincg.course_id="'.$value_list['cos_id'].'" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
                              if($result_chkcg==0){
                                unset($fetch[$key_list]);
                              }
                  }
                }else{
                  unset($fetch[$key_list]);
                }
              }
            }
         // }
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            $cos_lang = explode(',', $value['cos_lang']);
            $value['isTH'] = in_array('th',$cos_lang)?"1":"0";
            $value['isENG'] = in_array('eng',$cos_lang)?"1":"0";
            $value['isJP'] = in_array('jp',$cos_lang)?"1":"0";
            $cname = "";
            if($lang=="thai"){
                if($value['isTH']=="1"){
                  $cname = $value['cname_th'];
                }else{
                  if($cname==""){
                    $cname = $value['cname_eng'];
                  }
                  if($cname==""){
                    $cname = $value['cname_jp'];
                  }
                }
            }else if($lang=="english"){
                if($value['isENG']=="1"){
                  $cname = $value['cname_eng'];
                }else{
                  if($cname==""){
                    $cname = $value['cname_th'];
                  }
                  if($cname==""){
                    $cname = $value['cname_jp'];
                  }
                }
            }else{
                if($value['isJP']=="1"){
                  $cname = $value['cname_jp'];
                }else{
                  if($cname==""){
                    $cname = $value['cname_eng'];
                  }
                  if($cname==""){
                    $cname = $value['cname_th'];
                  }
                }
            }
            $detail = '<button type="button" name="detail_cos" id="'.$value['cos_id'].'" title="'.label('go_to_course').'" class="btn mdi-btn waves-effect waves-light btn-warning detail_cos"><span class="icon is-medium"><i class="mdi mdi-24px mdi-share mdi-light"></i></span></button>';
            $fetch_chkenroll = $this->func_query->numrows('lms_cos_enroll','','','','cos_id="'.$value['cos_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0"');
            if($fetch_chkenroll==0){
            	$detail = '<button type="button" name="btn_register" id="'.$value['cos_id'].'" title="'.label('lrn_btn_register').'"'.
												' class="btn mdi-btn waves-effect waves-light btn-danger btn_register"><span class="icon is-medium"><i class="mdi mdi-24px mdi-file-document-box mdi-light"></i></span></button>';
            }
            if($lang=="thai"){
              //$value['date_end']!="0000-00-00 00:00:00"?date('d',strtotime($value['date_end']))." ".$thaimonth[intval(date('m',strtotime($value['date_end'])))]." ".(date('Y',strtotime($value['date_end']))+543)." ".date('H:i',strtotime($value['date_end'])):label('UnlimitedTime');
              //$value['date_end']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value['date_end'])) 
              $date_end = $value['date_end']!="0000-00-00 00:00:00"?date('d/m',strtotime($value['date_end']))."/".(date('Y',strtotime($value['date_end']))+543)." ".date('H:i',strtotime($value['date_end'])):label('UnlimitedTime');
            }else{
              $date_end = $value['date_end']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['date_end'])):label('UnlimitedTime');
            }
            $output = array();
            $output['0'] = '<b title="'.$cname.'">'.$cname.'</b>';
            $output['1'] = $date_end;
            $output['2'] = "<center>".$detail."</center>";
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_courseincoming($com_id,$type) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $this->manage->loadDB();
          $page = "dashboard";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $sess = $this->session->userdata('user');
          $date_now = date('Y-m-d H:i');
          if($type=="1"){
          	$fetch = $this->func_query->query_result(
							'lms_cos',
							'lms_cos_detail',
							'lms_cos_detail.cos_id = lms_cos.cos_id','',
							'lms_cos.com_id="'.$com_id.'" and lms_cos.cos_approve="1" and lms_cos.cos_public="1" and lms_cos.cos_isDelete="0" and lms_cos.cos_status="1" and 
							lms_cos_detail.cosde_isDelete="0" and lms_cos_detail.date_start > "'.$date_now.'"');
          }else{
            $fetch = $this->func_query->query_result(
							'lms_cos',
							'lms_cos_detail',
							'lms_cos_detail.cos_id = lms_cos.cos_id','',
							'lms_cos.com_id="'.$com_id.'" and lms_cos.cos_approve="1" and lms_cos.cos_public="1" and lms_cos.cos_isDelete="0" and lms_cos.cos_status="1" and
							 lms_cos_detail.cosde_isDelete="0" and lms_cos_detail.date_start > "'.$date_now.'"');

            if(countArray($fetch)>0){
              foreach ($fetch as $key_list => $value_list) {
                $fetch_status = $this->func_query->numrows('lms_cos_enroll','','','','cos_id="'.$value_list['cos_id'].'" and emp_id="'.$sess['emp_id'].'" and cosen_isDelete="0"');
                if($fetch_status==0){
                  $fetch_chk_ug = $this->func_query->query_result(
										'lms_cos_detail',
										'lms_cos_detail_ug',
										'lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id','',
										'lms_cos_detail_ug.posi_id = "'.$sess['posi_id'].'" and lms_cos_detail.cos_id = "'.$value_list['cos_id'].'"');
                  if(countArray($fetch_chk_ug)==0){
                    unset($fetch[$key_list]);
                  }
                }
              }
            }
          }

            if(countArray($fetch)>0){
              foreach ($fetch as $key_list => $value_list) {
                  if(isset($fetch[$key_list])){
											$result_chkcg = $this->func_query->numrows(
												'lms_cosincg',
												'lms_cog',
												'lms_cosincg.cg_id = lms_cog.cg_id','',
												'lms_cosincg.course_id="'.$value_list['cos_id'].'" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
											if($result_chkcg==0){
												unset($fetch[$key_list]);
											}
                  }
              }
            }
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            $cos_lang = explode(',', $value['cos_lang']);
            $value['isTH'] = in_array('th',$cos_lang)?"1":"0";
            $value['isENG'] = in_array('eng',$cos_lang)?"1":"0";
            $value['isJP'] = in_array('jp',$cos_lang)?"1":"0";
            $cname = "";
            if($lang=="thai"){
                if($value['isTH']=="1"){
                  $cname = $value['cname_th'];
                }else{
                  if($cname==""){
                    $cname = $value['cname_eng'];
                  }
                  if($cname==""){
                    $cname = $value['cname_jp'];
                  }
                }
            }else if($lang=="english"){
                if($value['isENG']=="1"){
                  $cname = $value['cname_eng'];
                }else{
                  if($cname==""){
                    $cname = $value['cname_th'];
                  }
                  if($cname==""){
                    $cname = $value['cname_jp'];
                  }
                }
            }else{
                if($value['isJP']=="1"){
                  $cname = $value['cname_jp'];
                }else{
                  if($cname==""){
                    $cname = $value['cname_eng'];
                  }
                  if($cname==""){
                    $cname = $value['cname_th'];
                  }
                }
            }
            if($lang=="thai"){
              /*$date_start = $value['date_start']!="0000-00-00 00:00:00"?date('d',strtotime($value['date_start']))." ".$thaimonth[intval(date('m',strtotime($value['date_start'])))]." ".(date('Y',strtotime($value['date_start']))+543)." ".date('H:i',strtotime($value['date_start'])):label('UnlimitedTime');
            }else{
              $date_start = $value['date_start']!="0000-00-00 00:00:00"?date('d F Y H:i',strtotime($value['date_start'])):label('UnlimitedTime');
            }*/$date_start = $value['date_start']!="0000-00-00 00:00:00"?date('d/m',strtotime($value['date_start']))."/".(date('Y',strtotime($value['date_start']))+543)." ".date('H:i',strtotime($value['date_start'])):label('UnlimitedTime');
            }else{
              $date_start = $value['date_start']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['date_start'])):label('UnlimitedTime');
            }
            $output = array();
            $output['0'] = '<b title="'.$cname.'">'.$cname.'</b>';
            $output['1'] = $date_start;
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }


        public function fetch_data_publicsurvey_report($com_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $page = "survey/report_survey";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');

          $where = 'lms_sv.sv_isDelete="0" and sv_approve="1"';
          if($com_id!=""){
            $where .= ' and lms_sv.com_id="'.$com_id.'"';
          }
          if($user['ug_viewdata']=="3"){
            $where .= ' and sv_createby="'.$user['u_id'].'"';
          }
          $fetch = $this->func_query->query_result('lms_sv','lms_company','lms_sv.com_id = lms_company.com_id','',$where,'sv_id DESC','sv_id,lms_company.com_name_th,lms_company.com_name_eng,lms_sv.sv_title_th,lms_sv.sv_title_eng,lms_sv.sv_title_jp,(select count(svtc_id) from lms_sv_tc where lms_sv_tc.sv_id = lms_sv.sv_id and svtc_isDelete="0") as total_tc,(select count(svtc_id) from lms_sv_tc where lms_sv_tc.sv_id = lms_sv.sv_id and svtc_isDelete="0" and svtc_status="0") as unsuccess_tc,(select count(svtc_id) from lms_sv_tc where lms_sv_tc.sv_id = lms_sv.sv_id and svtc_isDelete="0" and svtc_status="1") as success_tc');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            $detail = '<button type="button" name="detail" id="'.$value['sv_id'].'" title="'.label('detail').'" class="btn btn-info btn-xs detail"><i class="mdi mdi-format-list-bulleted"></i></button>';
            $output = array();
            $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
            $output['1'] = $lang=="thai"?$value['com_name_th']:$value['com_name_eng'];
                  if($lang=="thai"){ 
                    $sv_title = $value['sv_title_th']!=""?$value['sv_title_th']:$value['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$value['sv_title_jp'];
                  }else if($lang=="english"){ 
                    $sv_title = $value['sv_title_eng']!=""?$value['sv_title_eng']:$value['sv_title_th'];
                    $sv_title = $sv_title!=""?$sv_title:$value['sv_title_jp'];
                  }else{
                    $sv_title = $value['sv_title_jp']!=""?$value['sv_title_jp']:$value['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$value['sv_title_th'];
                  }
            $output['2'] = $sv_title;
            // $total_tc = $this->func_query->numrows('lms_sv_tc','','','','sv_id="'.$value['sv_id'].'" and svtc_isDelete="0"');
            // $unsuccess_tc = $this->func_query->numrows('lms_sv_tc','','','','sv_id="'.$value['sv_id'].'" and svtc_isDelete="0" and svtc_status="0"');
            // $success_tc = $this->func_query->numrows('lms_sv_tc','','','','sv_id="'.$value['sv_id'].'" and svtc_isDelete="0" and svtc_status="1"');
            $total_tc = floatval($value['total_tc']);
            $unsuccess_tc = floatval($value['unsuccess_tc']);
            $success_tc = floatval($value['success_tc']);
            if($total_tc==0){
              $detail = "-";
            }
            $output['3'] = "<span style='float:right;'>".number_format($total_tc)."</span>";
            $output['4'] = "<span style='float:right;'>".number_format($success_tc)."</span>";
            $output['5'] = "<span style='float:right;'>".number_format($unsuccess_tc)."</span>";
            $output['6'] = "<center>".$detail."</center>";
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_publicsurvey($com_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $page = "survey/list_survey";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');

          $where = 'sv_isDelete="0" and lms_sv.com_id="'.$com_id.'" and lms_company.com_isDelete="0" and lms_company.com_status="1"';
          $fetch_ug = $this->func_query->query_row('lms_usp_gp','','','','ug_id="'.$user['ug_id'].'"');
          if(isset($fetch_ug['ug_viewdata']) && $fetch_ug['ug_viewdata']=="3"){
            if($fetch_ug['ug_approve']!="1"){
                $where .= ' and sv_createby="'.$user['u_id'].'"';
            } else {
                $where .= ' and (sv_createby="'.$user['u_id'].'" or (sv_public="1" and sv_approve="0" and sv_isDelete="0" and sv_status = 1))';
            }
          }
          $fetch = $this->func_query->query_result('lms_sv','lms_company','lms_company.com_id = lms_sv.com_id','',$where,'sv_approve ASC,sv_approvedate DESC,sv_id DESC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            $isPass = true;
            $fetch_approve = $this->func_query->query_row('lms_sv_approve','','','','sv_id ="'.$value['sv_id'].'"','sva_id DESC');
            if($fetch_ug['ug_approve']=="1" && $value["sv_createby"] != $user['u_id']){
              $sv_userapprove = explode(",",$value['sv_userapprove']);
              if(isset($fetch_approve['sva_approve']) && $fetch_approve['sva_approve']=="2"){
                if(!in_array($user['emp_id'], $sv_userapprove) && $value['sv_approveby'] != $user['u_id']){
                  $isPass = false;
                }
              }
            }
            if ($isPass) {
            /* $demo = '<button type="button" name="demo_course" id="'.$value['cos_id'].'" title="'.label('sample_course').'" class="btn btn-primary btn-xs demo_course"><i class="mdi mdi-eye"></i></button>';
              */
              $fetch_createby = $this->func_query->query_row('lms_usp','lms_emp','lms_usp.emp_id = lms_emp.emp_id','','lms_usp.u_id = "'.$value['sv_createby'].'"');
              $detail_survey = '';
              $demo_sv = '<button type="button" name="demo_sv" id="'.$value['sv_id'].'" title="'.label('sv_b_demo').'" class="btn btn-primary btn-xs demo_sv"><i class="mdi mdi-eye"></i></button>';
              $question = '<button type="button" name="question" id="'.$value['sv_id'].'" title="'.label('question').'" class="btn btn-info btn-xs question"><i class="mdi mdi-comment-question-outline"></i></button>';
              $approve = '<button type="button" name="approve" id="'.$value['sv_id'].'" title="'.label('d_waitapprove').'" class="btn btn-secondary btn-xs active approve"><i class="mdi mdi-alert text-warning"></i></button>';
              $update = '<button type="button" name="update" id="'.$value['sv_id'].'" title="'.label('m_edit').'" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
              $delete = '<button type="button" name="delete" id="'.$value['sv_id'].'" class="btn btn-danger btn-xs delete" title="'.label('delete').'"><i class="mdi mdi-window-close"></i></button>';
              $publicsv = '<button type="button" name="publicsv" id="'.$value['sv_id'].'" class="btn btn-default btn-xs publicsv"  style="background-color: #34495e;color: #ecf0f1;" title="'.label('public').'"><i class="mdi mdi-web"></i></button>';
              $list_user = '<button type="button" name="list_user" id="'.$value['sv_id'].'" class="btn btn-default btn-xs list_user" style="background-color:#00d2d3;color:#ecf0f1;" title="'.label('list_userofsv').'"><i class="mdi mdi-format-list-bulleted"></i></button>';
              $output = array();
              $output['sv_createby'] = isset($fetch_createby['fullname_th']) ? ($lang=="thai"?$fetch_createby['fullname_th']:$fetch_createby['fullname_en']) : "";
              $output['num'] = "<span style='float:right;'>".$num."</span>";$num++;


                /*   if($lang=="thai"){ 
                      $sv_title = $value['sv_title_th']!=""?$value['sv_title_th']:$value['sv_title_eng'];
                      $sv_title = $sv_title!=""?$sv_title:$value['sv_title_jp'];
                    }else if($lang=="english"){ 
                      $sv_title = $value['sv_title_eng']!=""?$value['sv_title_eng']:$value['sv_title_th'];
                      $sv_title = $sv_title!=""?$sv_title:$value['sv_title_jp'];
                    }else{
                      $sv_title = $value['sv_title_jp']!=""?$value['sv_title_jp']:$value['sv_title_eng'];
                      $sv_title = $sv_title!=""?$sv_title:$value['sv_title_th'];
                    }
  */
                    $sv_title = "";
                    $sv_lang_txt = "";

                    $sv_lang = explode(',', $value['sv_lang']);
                    $value['isTH'] = in_array('th',$sv_lang)?"1":"0";
                    $value['isENG'] = in_array('eng',$sv_lang)?"1":"0";
                    $value['isJP'] = in_array('jp',$sv_lang)?"1":"0";
                    if($lang=="thai"){

                        $value['select_lang'] = 'th';
                        $value['is_lang_user_th'] = 'selected';
                        if($value['isTH']=="1"){
                          $sv_title = $value['sv_title_th'];
                        }else{
                          if($sv_title==""&&$value['isENG']=="1"){
                            $sv_title = $value['sv_title_eng'];
                          }
                          if($sv_title==""&&$value['isJP']=="1"){
                            $sv_title = $value['sv_title_jp'];
                          }
                        }
                    }else if($lang=="english"){

                        $value['select_lang'] = 'eng';
                        $value['is_lang_user_eng'] = 'selected';
                        if($value['isENG']=="1"){
                          $sv_title = $value['sv_title_eng'];
                        }else{
                          if($sv_title==""&&$value['isTH']=="1"){
                            $sv_title = $value['sv_title_th'];
                          }
                          if($sv_title==""&&$value['isJP']=="1"){
                            $sv_title = $value['sv_title_jp'];
                          }
                        }
                    }else{
                        $value['select_lang'] = 'jp';
                        $value['is_lang_user_jp'] = 'selected';
                        if($value['isJP']=="1"){
                          $sv_title = $value['sv_title_jp'];
                        }else{
                          if($sv_title==""&&$value['isENG']=="1"){
                            $sv_title = $value['sv_title_eng'];
                          }
                          if($sv_title==""&&$value['isTH']=="1"){
                            $sv_title = $value['sv_title_th'];
                          }
                        }
                    }
                    $sv_lang_txt="";
                          if($value['isENG']=="1"){
                            $sv_lang_txt = $sv_lang_txt!=""?$sv_lang_txt.",":"";
                            $sv_lang_txt .= "EN";
                          }
                          if($value['isTH']=="1"){
                            $sv_lang_txt = $sv_lang_txt!=""?$sv_lang_txt.",":"";
                            $sv_lang_txt .= "TH";
                          }
                          if($value['isJP']=="1"){
                            $sv_lang_txt = $sv_lang_txt!=""?$sv_lang_txt.",":"";
                            $sv_lang_txt .= "JP";
                          }
              $output['sv_lang_txt'] = $sv_lang_txt;
              $output['sv_title'] = $sv_title;
              $sv_period = label('UnlimitedTime');
              if($value['sv_open']!="0000-00-00 00:00:00"&&$value['sv_end']!="0000-00-00 00:00:00"){
                if($lang=="thai"){
                  $sv_open = $value['sv_open']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['sv_open'])).(date('Y',strtotime($value['sv_open']))+543)." ".date('H:i',strtotime($value['sv_open'])):"";
                  $sv_end = $value['sv_end']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['sv_end'])).(date('Y',strtotime($value['sv_end']))+543)." ".date('H:i',strtotime($value['sv_end'])):"";
                }else{
                  $sv_open = $value['sv_open']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['sv_open'])):"";
                  $sv_end = $value['sv_end']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['sv_end'])):"";
                }
                $sv_period = $sv_open." - ".$sv_end;
              }
              $output['sv_period'] = "<center>".$sv_period."</center>";
              if(intval($value['sv_public'])==0){
                $sv_approve = label('d_waitcreate');
              }else{
                $sv_approve = label('d_waitapprove');
                if($user['u_id']!="1"){
                  $update = "";
                    if($user['ug_id']!="1"){
                      $delete = "";
                    }
                  $question = "";
                  //$list_user = "";
                }
                if($value['sv_approve']=="1"){
                  $sv_approve = label('d_approved');
                }else if($value['sv_approve']=="2"){
                  $sv_approve = label('d_rejected');
                }
              }

              $sv_userapprove = explode(",",$value['sv_userapprove']);
              $sv_approve = label('d_waitapprove');
              if(isset($fetch_approve)){
                  if($fetch_approve['sva_approve']=="1"){
                      $sv_approve = label('d_approved');
                      $approve = "";
                  }else if($fetch_approve['sva_approve']=="2"){
                    if(!in_array($user['emp_id'], $sv_userapprove)){
                      $sv_approve = label('d_waitapprove');
                    }else{
                        $approve = '<button type="button" title="'.label('d_waitapprove').'" id="'.$value['sv_id'].'" class="btn btn-secondary btn-xs active approve"><i class="mdi mdi-alert text-warning"></i></button>';
                    }
                  }else if($fetch_approve['sva_approve']=="3"){
                        $approve = "";
                        $sv_approve = label('d_waitcreate');
                  }else{
                        $approve = "";
                        $sv_approve = label('d_rejected');
                  }
              }else{
                if(intval($value['sv_public'])==0){
                  $sv_approve = label('d_waitcreate');
                }              
              }

              $sv_approveby = "<center>-</center>";
              $sv_approvedate = "";
              $sv_approvedateori = "";
              if($value['sv_approveby']!=""){
                $fetch_approver = $this->func_query->query_row('lms_usp','lms_emp','lms_usp.emp_id = lms_emp.emp_id','','lms_usp.u_id = "'.$value['sv_approveby'].'"');
                if(isset($fetch_approver)){
                  $sv_approveby = $lang=="thai"?$fetch_approver['fullname_th']:$fetch_approver['fullname_en'];
                }
                $sv_approvedateori = $value['sv_approvedate']!="0000-00-00 00:00:00"?$value['sv_approvedate']:"";
                if($lang=="thai"){
                  $sv_approvedate = $value['sv_approvedate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['sv_approvedate'])).(date('Y',strtotime($value['sv_approvedate']))+543)." ".date('H:i',strtotime($value['sv_approvedate'])):"";
                }else{
                  $sv_approvedate = $value['sv_approvedate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['sv_approvedate'])):"";
                }
              }
              //$status_sv = $value['sv_status']=="1"?label('enable'):label('disable');
              $output['sv_approve'] = $sv_approve;
              $arr_user = $value['sv_userapprove']!=""?explode(',', $value['sv_userapprove']):array();
              if(countArray($arr_user)>0){
                if(!in_array($user['emp_id'], $arr_user)){
                    $approve = "";
                }
              }else{
                $approve = "";
              }
              //if($user['ug_approve']=="1"&&$user['ug_id']=="1"){
                $num_question = $this->func_query->numrows('lms_svde','','','','sv_id="'.$value['sv_id'].'" and svde_isDelete="0"');
                if($num_question==0){
                  $approve = "";
                  //$list_user = "";
                  $publicsv = "";
                }
                if($value['sv_approve']=="1"){
                  $approve = '';//'<button type="button" class="btn btn-success btn-xs"><i class="mdi mdi-check"></i></button>';
                  $publicsv = "";
                  if($user['u_id']!="1"){
                    if($user['ug_id']!="1"){
                      $delete = "";
                    }
                    $update = "";
                    $question = "";
                    $detail_survey = '<button type="button" name="detail_survey_cannot_edit" id="'.$value['sv_id'].'" title="'.label('sv_summary').'" class="btn btn-info btn-xs detail_survey_cannot_edit"><i class="mdi mdi-magnify"></i></button>';
                  }
                }else{
                  if($value['sv_public']=="0"){
                    $approve = "";
                  }else{
                    $publicsv = "";
                    
                    if(countArray($arr_user)>0){
                      if(in_array($user['emp_id'], $arr_user) || $user['ug_id']=="1"){
                        $detail_survey = '<button type="button" name="detail_survey_cannot_edit" id="'.$value['sv_id'].'" title="'.label('sv_summary').'" class="btn btn-info btn-xs detail_survey_cannot_edit"><i class="mdi mdi-magnify"></i></button>';
                      }
                    }
                  }
                  $sv_approveby = "<center>-</center>";
                  $sv_approvedate = "";
                }
                $arr = array(
                  'display' => $sv_approvedate,
                  'timestamp' => strtotime($sv_approvedateori),
                );
                $output['sv_approveby'] = $sv_approveby;
                $output['sv_approvedate'] = $arr;
                //$output['approve'] = "<center>".$approve."</center>";
                
                $status_cos = label('open');
                if($value['sv_end']!="0000-00-00 00:00:00"&&date('Y-m-d H:i',strtotime($value['sv_end']))<date('Y-m-d H:i')){
                    $status_cos = label('close');
                }
                if($sv_approve==label('d_waitcreate')||$sv_approve==label('d_waitapprove')){
                    $status_cos = "-";
                }
                $numrechk_svde = $this->func_query->numrows('lms_svde','','','','sv_id = "'.$value['sv_id'].'" and svde_isDelete="0"');
                if($numrechk_svde==0){
                    $status_cos = "-";
                }
                $output['status_cos'] = '<center>'.$status_cos.'</center>';
                if($lang=="thai"){
                $sv_modifieddate = $value['sv_modifieddate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['sv_modifieddate'])).(date('Y',strtotime($value['sv_modifieddate']))+543)." ".date('H:i',strtotime($value['sv_modifieddate'])):"<center>-</center>";
                }else{
                $sv_modifieddate = $value['sv_modifieddate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['sv_modifieddate'])):"<center>-</center>";
                }
                $sv_modifieddateori = $value['sv_modifieddate']!="0000-00-00 00:00:00"?$value['sv_modifieddate']:"";
                $arrsv_modifieddateori = array(
                  'display' => $sv_modifieddate,
                  'timestamp' => strtotime($sv_modifieddateori),
                );

                $output['sv_modifieddate'] = $arrsv_modifieddateori;
              /*}else{
                $output['6'] = $sv_approveby;
                $output['7'] = $sv_approvedate;
                $output['8'] = $value['sv_modifieddate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['sv_modifieddate'])):"<center>-</center>";
              }*/
              $fetch_chkdetail = $this->func_query->numrows('lms_svde','','','','sv_id="'.$value['sv_id'].'" and svde_status="1" and svde_isDelete="0"');
              if($fetch_chkdetail==0){
                $publicsv="";
                $approve="";
              }
              
              if($btn_update!="1"){
                  $update = "";
              }
              if($btn_delete!="1"){
                  $delete = "";
              }
              if($publicsv!=""){
                if($value['sv_status']=="0"){
                  $publicsv = "";
                }
              }
              $countbtn = 0;
              if($demo_sv!=""&&$demo_sv!="-"){$countbtn++;}
              if($list_user!=""&&$list_user!="-"){$countbtn++;}
              if($publicsv!=""&&$publicsv!="-"){$countbtn++;}
              if($question!=""&&$question!="-"){$countbtn++;}
              if($update!=""&&$update!="-"){$countbtn++;}
              if($delete!=""&&$delete!="-"){$countbtn++;}
              if($approve!=""&&$approve!="-"){$countbtn++;}
              if($detail_survey!="" && $detail_survey != "-") {$countbtn++;}
              //if($this->checknumber($countbtn)=="Odd"){
              $output['buttonall'] = $demo_sv." ".$list_user." ".$detail_survey." ".$publicsv." ".$question." ".$update." ".$delete." ".$approve;
              /*}else{
              $output['0'] = "<center>".$demo_sv." ".$list_user." ".$publicsv." ".$question." ".$update." ".$delete." ".$approve."</center>";
              }*/
              array_push($fetch_arr, $output);
            }
          }
          return $fetch_arr;
        }

				private function str_replace_func($value=""){
						$value = str_replace("<p>","",$value);
						$value = str_replace("</p>","",$value);
						return $value;
				}

        public function fetch_data_publicsurvey_detail($sv_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $page = "survey/list_survey";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');

          $where = 'svde_isDelete="0" and sv_id="'.$sv_id.'"';
          $fetch = $this->func_query->query_result('lms_svde','','','',$where,'svde_id ASC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
           /* $demo = '<button type="button" name="demo_course" id="'.$value['cos_id'].'" title="'.label('sample_course').'" class="btn btn-primary btn-xs demo_course"><i class="mdi mdi-eye"></i></button>';
            */
            $update = '<button type="button" name="update_question" id="'.$value['svde_id'].'" title="'.label('m_edit').'" class="btn btn-warning btn-xs update_question"><i class="mdi mdi-lead-pencil"></i></button>';
            $delete = '<button type="button" name="delete_question" id="'.$value['svde_id'].'" class="btn btn-danger btn-xs delete_question" title="'.label('delete').'"><i class="mdi mdi-window-close"></i></button>';
            $output = array();
            $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
            $svde_type = "";
            if($value['svde_type']=="sa"){
              $svde_type = label('qt_sa');
            }else if($value['svde_type']=="sub"){
              $svde_type = label('qt_sub');
            }else if($value['svde_type']=="2choice"){
              $svde_type = label('qt_twoChoice');
            }else if($value['svde_type']=="multi"){
              $svde_type = label('qt_multi');
              if($value['svde_isMultichoice']=="1"){
                $svde_type .= "<br><b style='color:red;'>".label('isMultichoice')."</b>";
              }
            }else if($value['svde_type']=="scale"){
              $svde_type = label('qt_scale');
            }

						if($lang=="thai"){ 
							$svde_name = $value['svde_name_th']!=""?$value['svde_name_th']:$value['svde_name_eng'];
							$svde_name = $svde_name!=""?$svde_name:$value['svde_name_jp'];
							$svde_header = $value['svde_header_th']!=""?$value['svde_header_th']:$value['svde_header_eng'];
							$svde_header = $svde_header!=""?$svde_header:$value['svde_header_jp'];
						}else if($lang=="english"){ 
							$svde_name = $value['svde_name_eng']!=""?$value['svde_name_eng']:$value['svde_name_th'];
							$svde_name = $svde_name!=""?$svde_name:$value['svde_name_jp'];
							$svde_header = $value['svde_header_eng']!=""?$value['svde_header_eng']:$value['svde_header_th'];
							$svde_header = $svde_header!=""?$svde_header:$value['svde_header_jp'];
						}else{
							$svde_name = $value['svde_name_jp']!=""?$value['svde_name_jp']:$value['svde_name_eng'];
							$svde_name = $svde_name!=""?$svde_name:$value['svde_name_th'];
							$svde_header = $value['svde_header_jp']!=""?$value['svde_header_jp']:$value['svde_header_eng'];
							$svde_header = $svde_header!=""?$svde_header:$value['svde_header_th'];
						}
            $output['2'] = "<center>".$svde_header."</center>";
            $output['3'] = "<center>".$svde_type."</center>";
            $output['4'] = strip_tags($svde_name);
            $svde_choice = "<center>-</center>";
            $svde_choice = "";
            $fetch_choice = $this->func_query->query_result('lms_svde_mul','','','','svde_id="'.$value['svde_id'].'" and mul_isDelete="0"');
            if(countArray($fetch_choice)>0&&($value['svde_type']=="2choice"||$value['svde_type']=="multi")){
              
              foreach ($fetch_choice as $key_choice => $value_choice) {

                      if($lang=="thai"){ 
                        $mul_c1 = $value_choice['mul_c1_th']!=""?$value_choice['mul_c1_th']:$value_choice['mul_c1_eng'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$value_choice['mul_c1_jp'];
                        $mul_c2 = $value_choice['mul_c2_th']!=""?$value_choice['mul_c2_th']:$value_choice['mul_c2_eng'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$value_choice['mul_c2_jp'];
                        $mul_c3 = $value_choice['mul_c3_th']!=""?$value_choice['mul_c3_th']:$value_choice['mul_c3_eng'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$value_choice['mul_c3_jp'];
                        $mul_c4 = $value_choice['mul_c4_th']!=""?$value_choice['mul_c4_th']:$value_choice['mul_c4_eng'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$value_choice['mul_c4_jp'];
                        $mul_c5 = $value_choice['mul_c5_th']!=""?$value_choice['mul_c5_th']:$value_choice['mul_c5_eng'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$value_choice['mul_c5_jp'];
                        $mul_c6 = $value_choice['mul_c6_th']!=""?$value_choice['mul_c6_th']:$value_choice['mul_c6_eng'];
                        $mul_c6 = $mul_c6!=""?$mul_c6:$value_choice['mul_c6_jp'];
                        $mul_c7 = $value_choice['mul_c7_th']!=""?$value_choice['mul_c7_th']:$value_choice['mul_c7_eng'];
                        $mul_c7 = $mul_c7!=""?$mul_c7:$value_choice['mul_c7_jp'];
                        $mul_c8 = $value_choice['mul_c8_th']!=""?$value_choice['mul_c8_th']:$value_choice['mul_c8_eng'];
                        $mul_c8 = $mul_c8!=""?$mul_c8:$value_choice['mul_c8_jp'];
                        $mul_c9 = $value_choice['mul_c9_th']!=""?$value_choice['mul_c9_th']:$value_choice['mul_c9_eng'];
                        $mul_c9 = $mul_c9!=""?$mul_c9:$value_choice['mul_c9_jp'];
                        $mul_c10 = $value_choice['mul_c10_th']!=""?$value_choice['mul_c10_th']:$value_choice['mul_c10_eng'];
                        $mul_c10 = $mul_c10!=""?$mul_c10:$value_choice['mul_c10_jp'];
                        $mul_c11 = $value_choice['mul_c11_th']!=""?$value_choice['mul_c11_th']:$value_choice['mul_c11_eng'];
                        $mul_c11 = $mul_c11!=""?$mul_c11:$value_choice['mul_c11_jp'];
                        $mul_c12 = $value_choice['mul_c12_th']!=""?$value_choice['mul_c12_th']:$value_choice['mul_c12_eng'];
                        $mul_c12 = $mul_c12!=""?$mul_c12:$value_choice['mul_c12_jp'];
                        $mul_c13 = $value_choice['mul_c13_th']!=""?$value_choice['mul_c13_th']:$value_choice['mul_c13_eng'];
                        $mul_c13 = $mul_c13!=""?$mul_c13:$value_choice['mul_c13_jp'];
                        $mul_c14 = $value_choice['mul_c14_th']!=""?$value_choice['mul_c14_th']:$value_choice['mul_c14_eng'];
                        $mul_c14 = $mul_c14!=""?$mul_c14:$value_choice['mul_c14_jp'];
                        $mul_c15 = $value_choice['mul_c15_th']!=""?$value_choice['mul_c15_th']:$value_choice['mul_c15_eng'];
                        $mul_c15 = $mul_c15!=""?$mul_c15:$value_choice['mul_c15_jp'];
                      }else if($lang=="english"){ 
                        $mul_c1 = $value_choice['mul_c1_eng']!=""?$value_choice['mul_c1_eng']:$value_choice['mul_c1_th'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$value_choice['mul_c1_jp'];
                        $mul_c2 = $value_choice['mul_c2_eng']!=""?$value_choice['mul_c2_eng']:$value_choice['mul_c2_th'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$value_choice['mul_c2_jp'];
                        $mul_c3 = $value_choice['mul_c3_eng']!=""?$value_choice['mul_c3_eng']:$value_choice['mul_c3_th'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$value_choice['mul_c3_jp'];
                        $mul_c4 = $value_choice['mul_c4_eng']!=""?$value_choice['mul_c4_eng']:$value_choice['mul_c4_th'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$value_choice['mul_c4_jp'];
                        $mul_c5 = $value_choice['mul_c5_eng']!=""?$value_choice['mul_c5_eng']:$value_choice['mul_c5_th'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$value_choice['mul_c5_jp'];
                        $mul_c6 = $value_choice['mul_c6_eng']!=""?$value_choice['mul_c6_eng']:$value_choice['mul_c6_th'];
                        $mul_c6 = $mul_c6!=""?$mul_c6:$value_choice['mul_c6_jp'];
                        $mul_c7 = $value_choice['mul_c7_eng']!=""?$value_choice['mul_c7_eng']:$value_choice['mul_c7_th'];
                        $mul_c7 = $mul_c7!=""?$mul_c7:$value_choice['mul_c7_jp'];
                        $mul_c8 = $value_choice['mul_c8_eng']!=""?$value_choice['mul_c8_eng']:$value_choice['mul_c8_th'];
                        $mul_c8 = $mul_c8!=""?$mul_c8:$value_choice['mul_c8_jp'];
                        $mul_c9 = $value_choice['mul_c9_eng']!=""?$value_choice['mul_c9_eng']:$value_choice['mul_c9_th'];
                        $mul_c9 = $mul_c9!=""?$mul_c9:$value_choice['mul_c9_jp'];
                        $mul_c10 = $value_choice['mul_c10_eng']!=""?$value_choice['mul_c10_eng']:$value_choice['mul_c10_th'];
                        $mul_c10 = $mul_c10!=""?$mul_c10:$value_choice['mul_c10_jp'];
                        $mul_c11 = $value_choice['mul_c11_eng']!=""?$value_choice['mul_c11_eng']:$value_choice['mul_c11_th'];
                        $mul_c11 = $mul_c11!=""?$mul_c11:$value_choice['mul_c11_jp'];
                        $mul_c12 = $value_choice['mul_c12_eng']!=""?$value_choice['mul_c12_eng']:$value_choice['mul_c12_th'];
                        $mul_c12 = $mul_c12!=""?$mul_c12:$value_choice['mul_c12_jp'];
                        $mul_c13 = $value_choice['mul_c13_eng']!=""?$value_choice['mul_c13_eng']:$value_choice['mul_c13_th'];
                        $mul_c13 = $mul_c13!=""?$mul_c13:$value_choice['mul_c13_jp'];
                        $mul_c14 = $value_choice['mul_c14_eng']!=""?$value_choice['mul_c14_eng']:$value_choice['mul_c14_th'];
                        $mul_c14 = $mul_c14!=""?$mul_c14:$value_choice['mul_c14_jp'];
                        $mul_c15 = $value_choice['mul_c15_eng']!=""?$value_choice['mul_c15_eng']:$value_choice['mul_c15_th'];
                        $mul_c15 = $mul_c15!=""?$mul_c15:$value_choice['mul_c15_jp'];
                      }else{
                        $mul_c1 = $value_choice['mul_c1_jp']!=""?$value_choice['mul_c1_jp']:$value_choice['mul_c1_eng'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$value_choice['mul_c1_th'];
                        $mul_c2 = $value_choice['mul_c2_jp']!=""?$value_choice['mul_c2_jp']:$value_choice['mul_c2_eng'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$value_choice['mul_c2_th'];
                        $mul_c3 = $value_choice['mul_c3_jp']!=""?$value_choice['mul_c3_jp']:$value_choice['mul_c3_eng'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$value_choice['mul_c3_th'];
                        $mul_c4 = $value_choice['mul_c4_jp']!=""?$value_choice['mul_c4_jp']:$value_choice['mul_c4_eng'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$value_choice['mul_c4_th'];
                        $mul_c5 = $value_choice['mul_c5_jp']!=""?$value_choice['mul_c5_jp']:$value_choice['mul_c5_eng'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$value_choice['mul_c5_th'];
                        $mul_c6 = $value_choice['mul_c6_jp']!=""?$value_choice['mul_c6_jp']:$value_choice['mul_c6_eng'];
                        $mul_c6 = $mul_c6!=""?$mul_c6:$value_choice['mul_c6_th'];
                        $mul_c7 = $value_choice['mul_c7_jp']!=""?$value_choice['mul_c7_jp']:$value_choice['mul_c7_eng'];
                        $mul_c7 = $mul_c7!=""?$mul_c7:$value_choice['mul_c7_th'];
                        $mul_c8 = $value_choice['mul_c8_jp']!=""?$value_choice['mul_c8_jp']:$value_choice['mul_c8_eng'];
                        $mul_c8 = $mul_c8!=""?$mul_c8:$value_choice['mul_c8_th'];
                        $mul_c9 = $value_choice['mul_c9_jp']!=""?$value_choice['mul_c9_jp']:$value_choice['mul_c9_eng'];
                        $mul_c9 = $mul_c9!=""?$mul_c9:$value_choice['mul_c9_th'];
                        $mul_c10 = $value_choice['mul_c10_jp']!=""?$value_choice['mul_c10_jp']:$value_choice['mul_c10_eng'];
                        $mul_c10 = $mul_c10!=""?$mul_c10:$value_choice['mul_c10_th'];
                        $mul_c11 = $value_choice['mul_c11_jp']!=""?$value_choice['mul_c11_jp']:$value_choice['mul_c11_eng'];
                        $mul_c11 = $mul_c11!=""?$mul_c11:$value_choice['mul_c11_th'];
                        $mul_c12 = $value_choice['mul_c12_jp']!=""?$value_choice['mul_c12_jp']:$value_choice['mul_c12_eng'];
                        $mul_c12 = $mul_c12!=""?$mul_c12:$value_choice['mul_c12_th'];
                        $mul_c13 = $value_choice['mul_c13_jp']!=""?$value_choice['mul_c13_jp']:$value_choice['mul_c13_eng'];
                        $mul_c13 = $mul_c13!=""?$mul_c13:$value_choice['mul_c13_th'];
                        $mul_c14 = $value_choice['mul_c14_jp']!=""?$value_choice['mul_c14_jp']:$value_choice['mul_c14_eng'];
                        $mul_c14 = $mul_c14!=""?$mul_c14:$value_choice['mul_c14_th'];
                        $mul_c15 = $value_choice['mul_c15_jp']!=""?$value_choice['mul_c15_jp']:$value_choice['mul_c15_eng'];
                        $mul_c15 = $mul_c15!=""?$mul_c15:$value_choice['mul_c15_th'];
                      }
                      if($mul_c1!=""){
                        $svde_choice .= "1.".$this->str_replace_func($mul_c1)."<br>";
                      }
                      if($mul_c2!=""){
                        $svde_choice .= "2.".$this->str_replace_func($mul_c2)."<br>";
                      }
                    if($value['svde_type']=="multi"){
                      if($mul_c3!=""){
                        $svde_choice .= "3.".$this->str_replace_func($mul_c3)."<br>";
                      }
                      if($mul_c4!=""){
                        $svde_choice .= "4.".$this->str_replace_func($mul_c4)."<br>";
                      }
                      if($mul_c5!=""){
                        $svde_choice .= "5.".$this->str_replace_func($mul_c5)."<br>";
                      }
                      if($mul_c6!=""){
                        $svde_choice .= "6.".$this->str_replace_func($mul_c6)."<br>";
                      }
                      if($mul_c7!=""){
                        $svde_choice .= "7.".$this->str_replace_func($mul_c7)."<br>";
                      }
                      if($mul_c8!=""){
                        $svde_choice .= "8.".$this->str_replace_func($mul_c8)."<br>";
                      }
                      if($mul_c9!=""){
                        $svde_choice .= "9.".$this->str_replace_func($mul_c9)."<br>";
                      }
                      if($mul_c10!=""){
                        $svde_choice .= "10.".$this->str_replace_func($mul_c10)."<br>";
                      }
                      if($mul_c11!=""){
                        $svde_choice .= "11.".$this->str_replace_func($mul_c11)."<br>";
                      }
                      if($mul_c12!=""){
                        $svde_choice .= "12.".$this->str_replace_func($mul_c12)."<br>";
                      }
                      if($mul_c13!=""){
                        $svde_choice .= "13.".$this->str_replace_func($mul_c13)."<br>";
                      }
                      if($mul_c14!=""){
                        $svde_choice .= "14.".$this->str_replace_func($mul_c14)."<br>";
                      }
                      if($mul_c15!=""){
                        $svde_choice .= "15.".$this->str_replace_func($mul_c15)."<br>";
                      }
                    }
              }
                    if($value['svde_type']=="multi"){
                      if($lang=="thai"){ 
                        $svde_specify_name = $value['svde_specify_name_th']!=""?$value['svde_specify_name_th']:$value['svde_specify_name_eng'];
                        $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value['svde_specify_name_jp'];
                      }else if($lang=="english"){ 
                        $svde_specify_name = $value['svde_specify_name_eng']!=""?$value['svde_specify_name_eng']:$value['svde_specify_name_th'];
                        $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value['svde_specify_name_jp'];
                      }else{
                        $svde_specify_name = $value['svde_specify_name_jp']!=""?$value['svde_specify_name_jp']:$value['svde_specify_name_eng'];
                        $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value['svde_specify_name_th'];
                      }
                      if($value['svde_isSpecify']=="1"&&$svde_specify_name!=""){

                        $svde_choice .= $svde_specify_name." : ...";
                      }
                    }
            }

            $output['5'] = $svde_choice;

              if($lang=="thai"){
              $output['6'] = $value['svde_modifieddate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['svde_modifieddate'])).(date('Y',strtotime($value['svde_modifieddate']))+543)." ".date('H:i',strtotime($value['svde_modifieddate'])):"<center>-</center>";
              }else{
              $output['6'] = $value['svde_modifieddate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['svde_modifieddate'])):"<center>-</center>";
              }
            $output['0'] = "<center>".$update." ".$delete."</center>";
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_publicsurvey_detail_view($sv_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          date_default_timezone_set("Asia/Bangkok");

          $where = 'svde_isDelete="0" and sv_id="'.$sv_id.'"';
          $fetch = $this->func_query->query_result('lms_svde','','','',$where,'svde_id ASC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            $output = array();
            $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
            $svde_type = "";
            if($value['svde_type']=="sa"){
              $svde_type = label('qt_sa');
            }else if($value['svde_type']=="sub"){
              $svde_type = label('qt_sub');
            }else if($value['svde_type']=="2choice"){
              $svde_type = label('qt_twoChoice');
            }else if($value['svde_type']=="multi"){
              $svde_type = label('qt_multi');
              if($value['svde_isMultichoice']=="1"){
                $svde_type .= "<br><b style='color:red;'>".label('isMultichoice')."</b>";
              }
            }else if($value['svde_type']=="scale"){
              $svde_type = label('qt_scale');
            }

						if($lang=="thai"){ 
							$svde_name = $value['svde_name_th']!=""?$value['svde_name_th']:$value['svde_name_eng'];
							$svde_name = $svde_name!=""?$svde_name:$value['svde_name_jp'];
							$svde_header = $value['svde_header_th']!=""?$value['svde_header_th']:$value['svde_header_eng'];
							$svde_header = $svde_header!=""?$svde_header:$value['svde_header_jp'];
						}else if($lang=="english"){ 
							$svde_name = $value['svde_name_eng']!=""?$value['svde_name_eng']:$value['svde_name_th'];
							$svde_name = $svde_name!=""?$svde_name:$value['svde_name_jp'];
							$svde_header = $value['svde_header_eng']!=""?$value['svde_header_eng']:$value['svde_header_th'];
							$svde_header = $svde_header!=""?$svde_header:$value['svde_header_jp'];
						}else{
							$svde_name = $value['svde_name_jp']!=""?$value['svde_name_jp']:$value['svde_name_eng'];
							$svde_name = $svde_name!=""?$svde_name:$value['svde_name_th'];
							$svde_header = $value['svde_header_jp']!=""?$value['svde_header_jp']:$value['svde_header_eng'];
							$svde_header = $svde_header!=""?$svde_header:$value['svde_header_th'];
						}
            $output['1'] = "<center>".$svde_header."</center>";
            $output['2'] = "<center>".$svde_type."</center>";
            $output['3'] = strip_tags($svde_name);
            
            $svde_choice = "";
            $fetch_choice = $this->func_query->query_result('lms_svde_mul','','','','svde_id="'.$value['svde_id'].'" and mul_isDelete="0"');
            if(countArray($fetch_choice)>0&&($value['svde_type']=="2choice"||$value['svde_type']=="multi")){
              
              foreach ($fetch_choice as $key_choice => $value_choice) {

                      if($lang=="thai"){ 
                        $mul_c1 = $value_choice['mul_c1_th']!=""?$value_choice['mul_c1_th']:$value_choice['mul_c1_eng'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$value_choice['mul_c1_jp'];
                        $mul_c2 = $value_choice['mul_c2_th']!=""?$value_choice['mul_c2_th']:$value_choice['mul_c2_eng'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$value_choice['mul_c2_jp'];
                        $mul_c3 = $value_choice['mul_c3_th']!=""?$value_choice['mul_c3_th']:$value_choice['mul_c3_eng'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$value_choice['mul_c3_jp'];
                        $mul_c4 = $value_choice['mul_c4_th']!=""?$value_choice['mul_c4_th']:$value_choice['mul_c4_eng'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$value_choice['mul_c4_jp'];
                        $mul_c5 = $value_choice['mul_c5_th']!=""?$value_choice['mul_c5_th']:$value_choice['mul_c5_eng'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$value_choice['mul_c5_jp'];
                        $mul_c6 = $value_choice['mul_c6_th']!=""?$value_choice['mul_c6_th']:$value_choice['mul_c6_eng'];
                        $mul_c6 = $mul_c6!=""?$mul_c6:$value_choice['mul_c6_jp'];
                        $mul_c7 = $value_choice['mul_c7_th']!=""?$value_choice['mul_c7_th']:$value_choice['mul_c7_eng'];
                        $mul_c7 = $mul_c7!=""?$mul_c7:$value_choice['mul_c7_jp'];
                        $mul_c8 = $value_choice['mul_c8_th']!=""?$value_choice['mul_c8_th']:$value_choice['mul_c8_eng'];
                        $mul_c8 = $mul_c8!=""?$mul_c8:$value_choice['mul_c8_jp'];
                        $mul_c9 = $value_choice['mul_c9_th']!=""?$value_choice['mul_c9_th']:$value_choice['mul_c9_eng'];
                        $mul_c9 = $mul_c9!=""?$mul_c9:$value_choice['mul_c9_jp'];
                        $mul_c10 = $value_choice['mul_c10_th']!=""?$value_choice['mul_c10_th']:$value_choice['mul_c10_eng'];
                        $mul_c10 = $mul_c10!=""?$mul_c10:$value_choice['mul_c10_jp'];
                        $mul_c11 = $value_choice['mul_c11_th']!=""?$value_choice['mul_c11_th']:$value_choice['mul_c11_eng'];
                        $mul_c11 = $mul_c11!=""?$mul_c11:$value_choice['mul_c11_jp'];
                        $mul_c12 = $value_choice['mul_c12_th']!=""?$value_choice['mul_c12_th']:$value_choice['mul_c12_eng'];
                        $mul_c12 = $mul_c12!=""?$mul_c12:$value_choice['mul_c12_jp'];
                        $mul_c13 = $value_choice['mul_c13_th']!=""?$value_choice['mul_c13_th']:$value_choice['mul_c13_eng'];
                        $mul_c13 = $mul_c13!=""?$mul_c13:$value_choice['mul_c13_jp'];
                        $mul_c14 = $value_choice['mul_c14_th']!=""?$value_choice['mul_c14_th']:$value_choice['mul_c14_eng'];
                        $mul_c14 = $mul_c14!=""?$mul_c14:$value_choice['mul_c14_jp'];
                        $mul_c15 = $value_choice['mul_c15_th']!=""?$value_choice['mul_c15_th']:$value_choice['mul_c15_eng'];
                        $mul_c15 = $mul_c15!=""?$mul_c15:$value_choice['mul_c15_jp'];
                      }else if($lang=="english"){ 
                        $mul_c1 = $value_choice['mul_c1_eng']!=""?$value_choice['mul_c1_eng']:$value_choice['mul_c1_th'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$value_choice['mul_c1_jp'];
                        $mul_c2 = $value_choice['mul_c2_eng']!=""?$value_choice['mul_c2_eng']:$value_choice['mul_c2_th'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$value_choice['mul_c2_jp'];
                        $mul_c3 = $value_choice['mul_c3_eng']!=""?$value_choice['mul_c3_eng']:$value_choice['mul_c3_th'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$value_choice['mul_c3_jp'];
                        $mul_c4 = $value_choice['mul_c4_eng']!=""?$value_choice['mul_c4_eng']:$value_choice['mul_c4_th'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$value_choice['mul_c4_jp'];
                        $mul_c5 = $value_choice['mul_c5_eng']!=""?$value_choice['mul_c5_eng']:$value_choice['mul_c5_th'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$value_choice['mul_c5_jp'];
                        $mul_c6 = $value_choice['mul_c6_eng']!=""?$value_choice['mul_c6_eng']:$value_choice['mul_c6_th'];
                        $mul_c6 = $mul_c6!=""?$mul_c6:$value_choice['mul_c6_jp'];
                        $mul_c7 = $value_choice['mul_c7_eng']!=""?$value_choice['mul_c7_eng']:$value_choice['mul_c7_th'];
                        $mul_c7 = $mul_c7!=""?$mul_c7:$value_choice['mul_c7_jp'];
                        $mul_c8 = $value_choice['mul_c8_eng']!=""?$value_choice['mul_c8_eng']:$value_choice['mul_c8_th'];
                        $mul_c8 = $mul_c8!=""?$mul_c8:$value_choice['mul_c8_jp'];
                        $mul_c9 = $value_choice['mul_c9_eng']!=""?$value_choice['mul_c9_eng']:$value_choice['mul_c9_th'];
                        $mul_c9 = $mul_c9!=""?$mul_c9:$value_choice['mul_c9_jp'];
                        $mul_c10 = $value_choice['mul_c10_eng']!=""?$value_choice['mul_c10_eng']:$value_choice['mul_c10_th'];
                        $mul_c10 = $mul_c10!=""?$mul_c10:$value_choice['mul_c10_jp'];
                        $mul_c11 = $value_choice['mul_c11_eng']!=""?$value_choice['mul_c11_eng']:$value_choice['mul_c11_th'];
                        $mul_c11 = $mul_c11!=""?$mul_c11:$value_choice['mul_c11_jp'];
                        $mul_c12 = $value_choice['mul_c12_eng']!=""?$value_choice['mul_c12_eng']:$value_choice['mul_c12_th'];
                        $mul_c12 = $mul_c12!=""?$mul_c12:$value_choice['mul_c12_jp'];
                        $mul_c13 = $value_choice['mul_c13_eng']!=""?$value_choice['mul_c13_eng']:$value_choice['mul_c13_th'];
                        $mul_c13 = $mul_c13!=""?$mul_c13:$value_choice['mul_c13_jp'];
                        $mul_c14 = $value_choice['mul_c14_eng']!=""?$value_choice['mul_c14_eng']:$value_choice['mul_c14_th'];
                        $mul_c14 = $mul_c14!=""?$mul_c14:$value_choice['mul_c14_jp'];
                        $mul_c15 = $value_choice['mul_c15_eng']!=""?$value_choice['mul_c15_eng']:$value_choice['mul_c15_th'];
                        $mul_c15 = $mul_c15!=""?$mul_c15:$value_choice['mul_c15_jp'];
                      }else{
                        $mul_c1 = $value_choice['mul_c1_jp']!=""?$value_choice['mul_c1_jp']:$value_choice['mul_c1_eng'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$value_choice['mul_c1_th'];
                        $mul_c2 = $value_choice['mul_c2_jp']!=""?$value_choice['mul_c2_jp']:$value_choice['mul_c2_eng'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$value_choice['mul_c2_th'];
                        $mul_c3 = $value_choice['mul_c3_jp']!=""?$value_choice['mul_c3_jp']:$value_choice['mul_c3_eng'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$value_choice['mul_c3_th'];
                        $mul_c4 = $value_choice['mul_c4_jp']!=""?$value_choice['mul_c4_jp']:$value_choice['mul_c4_eng'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$value_choice['mul_c4_th'];
                        $mul_c5 = $value_choice['mul_c5_jp']!=""?$value_choice['mul_c5_jp']:$value_choice['mul_c5_eng'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$value_choice['mul_c5_th'];
                        $mul_c6 = $value_choice['mul_c6_jp']!=""?$value_choice['mul_c6_jp']:$value_choice['mul_c6_eng'];
                        $mul_c6 = $mul_c6!=""?$mul_c6:$value_choice['mul_c6_th'];
                        $mul_c7 = $value_choice['mul_c7_jp']!=""?$value_choice['mul_c7_jp']:$value_choice['mul_c7_eng'];
                        $mul_c7 = $mul_c7!=""?$mul_c7:$value_choice['mul_c7_th'];
                        $mul_c8 = $value_choice['mul_c8_jp']!=""?$value_choice['mul_c8_jp']:$value_choice['mul_c8_eng'];
                        $mul_c8 = $mul_c8!=""?$mul_c8:$value_choice['mul_c8_th'];
                        $mul_c9 = $value_choice['mul_c9_jp']!=""?$value_choice['mul_c9_jp']:$value_choice['mul_c9_eng'];
                        $mul_c9 = $mul_c9!=""?$mul_c9:$value_choice['mul_c9_th'];
                        $mul_c10 = $value_choice['mul_c10_jp']!=""?$value_choice['mul_c10_jp']:$value_choice['mul_c10_eng'];
                        $mul_c10 = $mul_c10!=""?$mul_c10:$value_choice['mul_c10_th'];
                        $mul_c11 = $value_choice['mul_c11_jp']!=""?$value_choice['mul_c11_jp']:$value_choice['mul_c11_eng'];
                        $mul_c11 = $mul_c11!=""?$mul_c11:$value_choice['mul_c11_th'];
                        $mul_c12 = $value_choice['mul_c12_jp']!=""?$value_choice['mul_c12_jp']:$value_choice['mul_c12_eng'];
                        $mul_c12 = $mul_c12!=""?$mul_c12:$value_choice['mul_c12_th'];
                        $mul_c13 = $value_choice['mul_c13_jp']!=""?$value_choice['mul_c13_jp']:$value_choice['mul_c13_eng'];
                        $mul_c13 = $mul_c13!=""?$mul_c13:$value_choice['mul_c13_th'];
                        $mul_c14 = $value_choice['mul_c14_jp']!=""?$value_choice['mul_c14_jp']:$value_choice['mul_c14_eng'];
                        $mul_c14 = $mul_c14!=""?$mul_c14:$value_choice['mul_c14_th'];
                        $mul_c15 = $value_choice['mul_c15_jp']!=""?$value_choice['mul_c15_jp']:$value_choice['mul_c15_eng'];
                        $mul_c15 = $mul_c15!=""?$mul_c15:$value_choice['mul_c15_th'];
                      }
                      if($mul_c1!=""){
                        $svde_choice .= "1.".$this->str_replace_func($mul_c1)."<br>";
                      }
                      if($mul_c2!=""){
                        $svde_choice .= "2.".$this->str_replace_func($mul_c2)."<br>";
                      }
                    if($value['svde_type']=="multi"){
                      if($mul_c3!=""){
                        $svde_choice .= "3.".$this->str_replace_func($mul_c3)."<br>";
                      }
                      if($mul_c4!=""){
                        $svde_choice .= "4.".$this->str_replace_func($mul_c4)."<br>";
                      }
                      if($mul_c5!=""){
                        $svde_choice .= "5.".$this->str_replace_func($mul_c5)."<br>";
                      }
                      if($mul_c6!=""){
                        $svde_choice .= "6.".$this->str_replace_func($mul_c6)."<br>";
                      }
                      if($mul_c7!=""){
                        $svde_choice .= "7.".$this->str_replace_func($mul_c7)."<br>";
                      }
                      if($mul_c8!=""){
                        $svde_choice .= "8.".$this->str_replace_func($mul_c8)."<br>";
                      }
                      if($mul_c9!=""){
                        $svde_choice .= "9.".$this->str_replace_func($mul_c9)."<br>";
                      }
                      if($mul_c10!=""){
                        $svde_choice .= "10.".$this->str_replace_func($mul_c10)."<br>";
                      }
                      if($mul_c11!=""){
                        $svde_choice .= "11.".$this->str_replace_func($mul_c11)."<br>";
                      }
                      if($mul_c12!=""){
                        $svde_choice .= "12.".$this->str_replace_func($mul_c12)."<br>";
                      }
                      if($mul_c13!=""){
                        $svde_choice .= "13.".$this->str_replace_func($mul_c13)."<br>";
                      }
                      if($mul_c14!=""){
                        $svde_choice .= "14.".$this->str_replace_func($mul_c14)."<br>";
                      }
                      if($mul_c15!=""){
                        $svde_choice .= "15.".$this->str_replace_func($mul_c15)."<br>";
                      }
                    }
              }
                    if($value['svde_type']=="multi"){
                      if($lang=="thai"){ 
                        $svde_specify_name = $value['svde_specify_name_th']!=""?$value['svde_specify_name_th']:$value['svde_specify_name_eng'];
                        $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value['svde_specify_name_jp'];
                      } elseif($lang=="english") { 
                        $svde_specify_name = $value['svde_specify_name_eng']!=""?$value['svde_specify_name_eng']:$value['svde_specify_name_th'];
                        $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value['svde_specify_name_jp'];
                      } else {
                        $svde_specify_name = $value['svde_specify_name_jp']!=""?$value['svde_specify_name_jp']:$value['svde_specify_name_eng'];
                        $svde_specify_name = $svde_specify_name!=""?$svde_specify_name:$value['svde_specify_name_th'];
                      }
                      if($value['svde_isSpecify']=="1"&&$svde_specify_name!=""){

                        $svde_choice .= $svde_specify_name." : ...";
                      }
                    }
            }

            $output['4'] = $svde_choice;
            $output['5'] = !checkDatetimeIsNull($value['svde_modifieddate']) ? ($lang=="thai" ? date('d/m/',strtotime($value['svde_modifieddate'])).(date('Y',strtotime($value['svde_modifieddate']))+543)." ".date('H:i',strtotime($value['svde_modifieddate'])) : date('d/m/Y H:i',strtotime($value['svde_modifieddate']))) :"<center>-</center>";
            
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_publicsurvey_listuser($sv_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $page = "survey/list_survey";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $fetch_main = $this->func_query->query_row('lms_sv','','','','sv_id = "'.$sv_id.'"');
          $where = 'svtc_isDelete="0" and sv_id="'.$sv_id.'" and lms_emp.emp_isDelete="0"';
          $fetch = $this->func_query->query_result('lms_sv_tc','lms_emp','lms_emp.emp_id = lms_sv_tc.emp_id','',$where,'svtc_id DESC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
           /* $demo = '<button type="button" name="demo_course" id="'.$value['cos_id'].'" title="'.label('sample_course').'" class="btn btn-primary btn-xs demo_course"><i class="mdi mdi-eye"></i></button>';
            */
            $fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$value['com_id'].'"');
            $fetch_position = $this->func_query->query_row('lms_usp','lms_position','lms_position.posi_id = lms_usp.posi_id','','lms_usp.emp_id="'.$value['emp_id'].'"');
            $delete = '<button type="button" name="delete_user" id="'.$value['svtc_id'].'" class="btn btn-danger btn-xs delete_user" title="'.label('delete').'"><i class="mdi mdi-window-close"></i></button>';
            $reset = '<button type="button" name="reset_user" id="'.$value['svtc_id'].'" class="btn btn-warning btn-xs reset_user" title="'.label('reset').'"><i class="mdi mdi-backup-restore"></i></button>';
            $sendmail = '<button type="button" name="sendmail_user" id="'.$value['svtc_id'].'" class="btn btn-success btn-xs sendmail_user" title="'.label('sendmail_noti').'"><i class="mdi mdi-email-variant"></i></button>';
            $output = array();
            $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
            $output['2'] = $lang=="thai"?$value['fullname_th']:$value['fullname_en'];
            $output['3'] = $lang=="thai"?$fetch_company['com_name_th']:$fetch_company['com_name_eng'];
            $output['4'] = $lang=="thai"?$fetch_position['posi_name_th']:$fetch_position['posi_name_en'];
              if($lang=="thai"){
              $output['5'] = $value['svtc_firsttime']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['svtc_firsttime'])).(date('Y',strtotime($value['svtc_firsttime']))+543)." ".date('H:i',strtotime($value['svtc_firsttime'])):"<center>-</center>";
              $output['6'] = $value['svtc_finishtime']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['svtc_finishtime'])).(date('Y',strtotime($value['svtc_finishtime']))+543)." ".date('H:i',strtotime($value['svtc_finishtime'])):"<center>-</center>";
              }else{
              $output['5'] = $value['svtc_firsttime']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['svtc_firsttime'])):"<center>-</center>";
              $output['6'] = $value['svtc_finishtime']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['svtc_finishtime'])):"<center>-</center>";
              }
            $status = label('not_start');
            if($value['svtc_finishtime']!="0000-00-00 00:00:00"){
                $status = $value['svtc_status']=="0"?label('svUnDone'):label('done');
                if($status==label('done')){
                  $sendmail = '';
                  $delete = '';
                }
            }
            if($status==label('not_start')&&$value['svtc_firsttime']!="0000-00-00 00:00:00"){
              $status = label('inProgress');
            }
            $checkbox = '<div class="checkbox checkbox-success"><input type="checkbox" class="chkall_row" id="selectuser_'.$value['svtc_id'].'" name="selectuser[]" value="'.$value['svtc_id'].'"><label for="selectuser_'.$value['svtc_id'].'"></label>';
            if($value['svtc_finishtime']!="0000-00-00 00:00:00"){
              $checkbox = "-";
              /*$sendmail = "";
              $reset = "";*/
            }
            if($fetch_main['sv_approve']=="0"){
              $sendmail = "";
            }
            $output['7'] = "<center>".$status."</center>";
            $output['8'] = '<center>'.$checkbox.'</center>';
            $output['0'] = "<center>".$sendmail." ".$reset." ".$delete."</center>";
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }


        public function fetch_data_course($com_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $page = "managecourse/courses_all";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');

          $where = 'cos_isDelete="0" and lms_cos.com_id="'.$com_id.'" and lms_company.com_isDelete="0" and lms_company.com_status="1"';
          $fetch_ug = $this->func_query->query_row('lms_usp_gp','','','','ug_id="'.$user['ug_id'].'"');
          /*if($fetch_ug['ug_viewdata']=="2"){
            $where .= ' and lms_cos.com_id="'.$user['com_id'].'"';
          }else*/ 
          if($fetch_ug['ug_approve']!="1"){
            $where .= ' and cos_approve="1"';
          }
          if($fetch_ug['ug_viewdata']=="3"){
              if($fetch_ug['ug_approve']!="1"){
                  $where .= ' and cos_createby="'.$user['u_id'].'"';
              } else {
                  $where .= ' and (cos_createby="'.$user['u_id'].'" or (cos_id in (select lms_cosincg.course_id from lms_cosincg where lms_cosincg.cg_id in (select lms_cog_approve.cg_id from lms_cog_approve where lms_cog_approve.coga_approve = 1 and coga_createby = "'.$user['u_id'].'")) and cos_public = 1 and cos_approve = 0))';
              }
          }
          
          $fetch = $this->func_query->query_result('lms_cos','lms_company','lms_cos.com_id = lms_company.com_id','',$where,'cos_approve ASC,cos_id DESC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          if(countArray($fetch)>0){
            foreach ($fetch as $key_list => $value_list) {
                if(isset($fetch[$key_list])){
                  $result_chkcg = $this->func_query->numrows('lms_cosincg','lms_cog','lms_cosincg.cg_id = lms_cog.cg_id','','lms_cosincg.course_id="'.$value_list['cos_id'].'" and lms_cog.cg_status="1" and lms_cog.cg_approve="1" and lms_cog.cg_isDelete="0"');
                  if($result_chkcg==0){
                    unset($fetch[$key_list]);
                  }
                }
            }
          }
          $arrCreatorAndApproved = array();
          
          $fetchCreatorAndApproved = $this->func_query->query_result('lms_usp','lms_emp','lms_usp.emp_id = lms_emp.emp_id','','', '', 'lms_usp.u_id, lms_emp.fullname_th, lms_emp.fullname_en');
          if (!empty($fetchCreatorAndApproved)) {
            foreach ($fetchCreatorAndApproved as $keyCreatorAndApproved) {
                $arrCreatorAndApproved[$keyCreatorAndApproved["u_id"]] = $lang=="thai" ? $keyCreatorAndApproved['fullname_th'] : $keyCreatorAndApproved['fullname_en'];
            }
          }

          foreach ($fetch as $key => $value) {
            $fetch_cg = $this->func_query->query_result('lms_cog','lms_cosincg','lms_cosincg.cg_id = lms_cog.cg_id','','course_id = "'.$value['cos_id'].'" and status_cg="1"', '', 'cg_approve_by');
            $cg_approve_by = array();
            if(countArray($fetch_cg)>0){
              foreach ($fetch_cg as $key_cg => $value_cg) {
                if($value_cg['cg_approve_by']!=""){
                  $arr_approver = explode(',', $value_cg['cg_approve_by']);
                  if(countArray($arr_approver)>0){
                      for ($i=0; $i < countArray($arr_approver); $i++) { 
                        $fetch_by = $this->func_query->query_row('lms_usp','','','','u_id = "'.$arr_approver[$i].'"', '', 'emp_id');
                        if(countArray($fetch_by)>0){
                            array_push($cg_approve_by, $fetch_by['emp_id']);
                        }
                      }
                  }
                }
              }
            }
            $demo = '<button type="button" name="demo_course" id="'.$value['cos_id'].'" title="'.label('sample_course').'" class="btn btn-primary btn-xs demo_course"><i class="mdi mdi-eye"></i></button>';
            $duplicateCourse = '<button type="button" name="duplicateCourse" id="'.$value['cos_id'].'" title="'.label('duplicateCourse').'" class="btn btn-outline-secondary btn-xs duplicateCourse"><i class="mdi mdi-content-copy"></i></button>';
            $detail_course = '<button type="button" name="detail_course" id="'.$value['cos_id'].'" title="'.label('ceDetailCourse').'" class="btn btn-info btn-xs detail_course"><i class="mdi mdi-note-multiple"></i></button>';
            $approve = '<button type="button" name="approve" id="'.$value['cos_id'].'" title="'.label('d_waitapprove').'" class="btn btn-secondary btn-xs active approve"><i class="mdi mdi-alert text-warning"></i></button>';
            $update = '<button type="button" name="update" id="'.$value['cos_id'].'" title="'.label('m_edit').'" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
            $delete = '<button type="button" name="delete" id="'.$value['cos_id'].'" class="btn btn-danger btn-xs delete" title="'.label('delete').'"><i class="mdi mdi-window-close"></i></button>';
            $output = array();
            $output['num'] = "<span style='float:right;'>".$num."</span>";$num++;
            /*$output['2'] = "<center>".$value['ccode']."</center>";*/
            if($lang=="thai"){ 
              /*$cname = $value['cname_th']!=""?$value['cname_th']:$value['cname_eng'];
              $cname = $cname!=""?$cname:$value['cname_jp'];*/
              //$output['2'] = $cname; 
              if(in_array($user['ug_id'], array('1','2','6'))){
                $output['comcode'] = $value['com_code']!=""?"<center>".$value['com_code']."</center>":"";

              }
            }else if($lang=="english"){ 
              /*$cname = $value['cname_eng']!=""?$value['cname_eng']:$value['cname_th'];
              $cname = $cname!=""?$cname:$value['cname_jp'];*/
              //$output['2'] = $cname; 
              if(in_array($user['ug_id'], array('1','2','6'))){
                $output['comcode'] = $value['com_code']!=""?"<center>".$value['com_code']."</center>":"";
                
              }
            }else{
              /*$cname = $value['cname_jp']!=""?$value['cname_jp']:$value['cname_eng'];
              $cname = $cname!=""?$cname:$value['cname_th'];*/
              //$output['2'] = $cname; 
              if(in_array($user['ug_id'], array('1','2','6'))){
                $output['comcode'] = $value['com_code']!=""?"<center>".$value['com_code']."</center>":"";
              }
            }
            $cname = "";
            $cos_lang = explode(',', $value['cos_lang']);
            $value['isTH'] = in_array('th',$cos_lang)?"1":"0";
            $value['isENG'] = in_array('eng',$cos_lang)?"1":"0";
            $value['isJP'] = in_array('jp',$cos_lang)?"1":"0";
            if($lang=="thai"){
                $value['select_lang'] = 'th';
                $value['is_lang_user_th'] = 'selected';
                if($value['isTH']=="1"){
                  $cname = $value['cname_th'];
                }else{
                  if($cname==""&&$value['isENG']=="1"){
                    $cname = $value['cname_eng'];
                  }
                  if($cname==""&&$value['isJP']=="1"){
                    $cname = $value['cname_jp'];
                  }
                }
            }else if($lang=="english"){
                $value['select_lang'] = 'eng';
                $value['is_lang_user_eng'] = 'selected';
                if($value['isENG']=="1"){
                  $cname = $value['cname_eng'];
                }else{
                  if($cname==""&&$value['isTH']=="1"){
                    $cname = $value['cname_th'];
                  }
                  if($cname==""&&$value['isJP']=="1"){
                    $cname = $value['cname_jp'];
                  }
                }
            }else{
                $value['select_lang'] = 'jp';
                $value['is_lang_user_jp'] = 'selected';
                if($value['isJP']=="1"){
                  $cname = $value['cname_jp'];
                }else{
                  if($cname==""&&$value['isENG']=="1"){
                    $cname = $value['cname_eng'];
                  }
                  if($cname==""&&$value['isTH']=="1"){
                    $cname = $value['cname_th'];
                  }
                }
            }
            $output['cos_name'] = $cname; 
            $cos_lang = explode(',', $value['cos_lang']);
            $cos_lang_txt = "";
            /*if(countArray($cos_lang)==3){
              $cos_lang_txt = label('all_lang');
            }else{*/
              /*$numloop = 1;
              for ($i=0; $i < countArray($cos_lang); $i++) { 
                if($cos_lang[$i]=="ength"){
                  $cos_lang_txt .= "EN";
                }else if($cos_lang[$i]==""){
                  $cos_lang_txt .= "TH";
                }else{
                  $cos_lang_txt .= "JP";
                }
                if($numloop<countArray($cos_lang)){
                  $cos_lang_txt .= ",";
                }
                $numloop++;
              }*/
              $cos_lang_arr = explode(',', $value['cos_lang']);
                      if(in_array('eng', $cos_lang_arr)){
                        $cos_lang_txt .= "EN";
                      }
                      if(in_array('th', $cos_lang_arr)){
                        $cos_lang_txt = $cos_lang_txt!=""?$cos_lang_txt.",":"";
                        $cos_lang_txt .= "TH";
                      }
                      if(in_array('jp', $cos_lang_arr)){
                        $cos_lang_txt = $cos_lang_txt!=""?$cos_lang_txt.",":"";
                        $cos_lang_txt .= "JP";
                      }

                      /*if($value['cname_eng']!=""){
                        //$cos_lang_txt .= "EN";
                      }
                      if($value['cname_th']!=""){
                        $cos_lang_txt = $cos_lang_txt!=""?$cos_lang_txt.",":"";
                        //$cos_lang_txt .= "TH";
                      }
                      if($value['cname_jp']!=""){
                        $cos_lang_txt = $cos_lang_txt!=""?$cos_lang_txt.",":"";
                        //$cos_lang_txt .= "JP";
                      }*/
            //}
            $cos_approvedate = $value['cos_approvedate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['cos_approvedate'])):"<center>-</center>";
            $cos_approveby = "<center>-</center>";
            if($value['cos_approveby']!=""){
              if(isset($arrCreatorAndApproved[$value['cos_approveby']])){
                $cos_approveby = $arrCreatorAndApproved[$value['cos_approveby']];
              }
            }
            if(intval($value['cos_public'])==0){
              $cos_approve = label('d_waitcreate');
              $cos_approvedate = "<center>-</center>";
              $cos_approveby = "<center>-</center>";
            }else{
              $cos_approve = label('d_waitapprove');
              if($value['cos_approve']=="1"){
                $cos_approve = label('d_approved');
              }else if($value['cos_approve']=="2"){
                $cos_approve = label('d_rejected');
              }else{
                $isCanEdit = false;
                if ($value['cos_approve']=="1" || $value['cos_public']=="1") {
                  // $fetchCheckCGINCOS = $this->func_query->query_result(
                  //   "lms_cosincg",
                  //   "lms_cog",
                  //   "lms_cosincg.cg_id = lms_cog.cg_id", "",
                  //   "lms_cosincg.course_id = ".$value["cos_id"]
                  // );
                  // if (!empty($fetchCheckCGINCOS) && $value['cos_approve']!="1") {
                  //   foreach ($fetchCheckCGINCOS as $keyCheckCGINCOS) {
                  //     $cgApproveBy = explode(',', $keyCheckCGINCOS['cg_approve_by']);
                  //     if (!empty($cgApproveBy)) {
                  //     for ($i = 0; $i < countArray($cgApproveBy); $i++) {
                  //       if ($user['u_id'] == $cgApproveBy[$i]) {
                  //       $isCanEdit = true;
                  //       }
                  //     }
                  //     }
                  //   }
                  // }
                  if ($user['u_id'] == "1") {
                    $isCanEdit = true;
                  }
                } else {
                  $isCanEdit = true;
                }
                if(!$isCanEdit){
                  $update = "";
                  $delete = "";

                  $detail_course = '<button type="button" name="detail_course_cannot_edit" id="'.$value['cos_id'].'" title="'.label('courseSummary').'" class="btn btn-info btn-xs detail_course_cannot_edit"><i class="mdi mdi-magnify"></i></button>';
                }
                $cos_approvedate = "<center>-</center>";
                $cos_approveby = "<center>-</center>";
              }
            }
            if ($value['cos_approve']=="1" && $value['cos_public']=="1") {
              $detail_course .= ' <button type="button" name="detail_course_cannot_edit" id="'.$value['cos_id'].'" title="'.label('courseSummary').'" class="btn btn-info btn-xs detail_course_cannot_edit"><i class="mdi mdi-magnify"></i></button>';
              if ($user['u_id'] != "1") {
                $update = "";
              }
            }
            $output['cos_lang'] = $cos_lang_txt;
            $output['createby'] = isset($arrCreatorAndApproved[$value['cos_createby']]) ? $arrCreatorAndApproved[$value['cos_createby']] : "";
            $numloop = 5;
            if(in_array($user['ug_id'], array('1','2','6'))){
              $numloop = 6;
            }
            $output['cos_approve'] = '<center>'.$cos_approve.'</center>';$numloop++;
            
            if($btn_update!="1"){
                $update = "";
            }
            if($btn_delete!="1"){
                $delete = "";
            }
            if($user['ug_approve']!="1"){
                $approve = "";
            }
            if(intval($value['cos_approve'])!=0){
                $approve = '';//'<button type="button" class="btn btn-success btn-xs"><i class="mdi mdi-check"></i></button>';
            }else{
                if(intval($value['cos_public'])==0){
                  $approve = "";
                }else{
                  if(!in_array($user['emp_id'], $cg_approve_by)){
                      $approve = "";
                  }
                }
            }

            if(intval($value['cos_public'])!=0&&$user['emp_id']!="1"){
                /*$detail_course = "";
                $update = "";*/
                $delete = "";
            }
            $varchk = 0;
            $fetch_chkqiz = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$value['cos_id'].'" and quiz_status="1" and quiz_isDelete="0"', '', 'qiz_id');
            if(countArray($fetch_chkqiz)>0){
              foreach ($fetch_chkqiz as $key_chkqiz => $value_chkqiz) {
                $fetch_chkques = $this->func_query->numrows('lms_ques','','','','qiz_id="'.$value_chkqiz['qiz_id'].'" and ques_status="1" and ques_isDelete="0"', '', 'ques_id');
                if($fetch_chkques==0){
                  $varchk++;
                }
              }
            }
            $fetch_chksv = $this->func_query->query_result('lms_survey','','','','cos_id="'.$value['cos_id'].'" and sv_status="1" and sv_isDelete="0"', '', 'sv_id');
            if(countArray($fetch_chksv)>0){
              foreach ($fetch_chksv as $key_chksv => $value_chksv) {
                $fetch_chkques = $this->func_query->numrows('lms_survey_de','','','','sv_id="'.$value_chksv['sv_id'].'" and svde_status="1" and svde_isDelete="0"', '', 'svde_id');
                if($fetch_chkques==0){
                  $varchk++;
                }
              }
            }
            if($value['cos_status']=="0"){
              $approve='';
            }
            if($varchk>0){
              $approve='';
            }
            if(in_array($user['ug_name_th'], array("Learner", "Learner (Manager)"))){
              $duplicateCourse = "";
            }
            $cos_approvedate = "";
            $cos_approvedateori = "";
              $output['buttonall'] = $demo." ".$approve." ".$detail_course." ".$update." ".$delete." ".$duplicateCourse;
              if($lang=="thai"){
              $cos_approvedate = $value['cos_approvedate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['cos_approvedate'])).(date('Y',strtotime($value['cos_approvedate']))+543)." ".date('H:i',strtotime($value['cos_approvedate'])):"<center>-</center>";$numloop++;
              }else{
              $cos_approvedate = $value['cos_approvedate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['cos_approvedate'])):"<center>-</center>";$numloop++;
              }
              $cos_approvedateori = $value['cos_approvedate']!="0000-00-00 00:00:00"?$value['cos_approvedate']:"";
              $arr = array(
                'display' => $cos_approvedate,
                'timestamp' => strtotime($cos_approvedateori),
              );
              $output['cos_approveby'] = $cos_approveby;$numloop++;
              $output['cos_approvedate'] = $arr;
              $fetch_chkperiod = $this->func_query->query_row('lms_cos_detail','','','','cos_id="'.$value['cos_id'].'"');
              $status_cos = label('open');
              if(isset($fetch_chkperiod)&&$fetch_chkperiod['date_end']!="0000-00-00 00:00:00"&&date('Y-m-d H:i',strtotime($fetch_chkperiod['date_end']))<date('Y-m-d H:i')){
                  $status_cos = label('close');
                  if($value['cos_status']=="1"){
                    $arr_status = array('cos_status'=>'0');
                    $this->db->where('cos_id',$value['cos_id']);
                    $this->db->update('lms_cos',$arr_status);
                  }
              }else{
                  $status_cos = $value['cos_status']=="1"?label('open'):label('close');
              }
              if($cos_approve == label('d_waitcreate')||$cos_approve == label('d_waitapprove')){
                  $status_cos = "-";
              }
              $output['status_cos'] = '<center>'.$status_cos.'</center>';$numloop++;
              $fetch_detail = $this->func_query->query_row('lms_cos_detail','','','','cos_id = "'.$value['cos_id'].'"');
              $period = '-';
              if(countArray($fetch_detail) > 0){
                  $period = $this->format_course_period_range($fetch_detail['date_start'], $fetch_detail['date_end'], $lang);
              }
              $fetch_enroll = $this->func_query->numrows('lms_cos_enroll','','','','cos_id="'.$value['cos_id'].'" and cosen_isDelete="0"');
              if($lang=="thai"){
              $cos_modifieddate = $value['cos_modifieddate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['cos_modifieddate'])).(date('Y',strtotime($value['cos_modifieddate']))+543)." ".date('H:i',strtotime($value['cos_modifieddate'])):"<center>-</center>";$numloop++;
              }else{
              $cos_modifieddate = $value['cos_modifieddate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['cos_modifieddate'])):"<center>-</center>";$numloop++;
              }

            //$cos_modifieddate = "";
            $cos_modifieddateori = "";

              $cos_modifieddateori = $value['cos_modifieddate']!="0000-00-00 00:00:00"?$value['cos_modifieddate']:"";
              $arr_modified = array(
                'display' => $cos_modifieddate,
                'timestamp' => strtotime($cos_modifieddateori),
              );

              $output['cos_modified'] = $arr_modified;
              $output['cos_period'] = '<center>'.$period.'</center>';$numloop++;
              $output['numofenroll'] = '<center>'.number_format($fetch_enroll).'</center>';$numloop++;
            $count++;
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_cos_document($cos_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $page = "managecourse/courses_all";
          $arr_permission = $this->manage->chk_permission_page();
          $btn_add = $this->manage->chk_permission($page,'ru_add');
          $btn_update = $this->manage->chk_permission($page,'ru_edit');
          $btn_delete = $this->manage->chk_permission($page,'ru_del');
          $btn_view = $this->manage->chk_permission($page,'ru_view');
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');

          $fetch_course = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              // $fetchCheckCGINCOS = $this->func_query->query_result(
              //   "lms_cosincg",
              //   "lms_cog",
              //   "lms_cosincg.cg_id = lms_cog.cg_id", "",
              //   "lms_cosincg.course_id = ".$cos_id
              // );
              // if (!empty($fetchCheckCGINCOS) && $fetch_course['cos_approve']!="1") {
              //   foreach ($fetchCheckCGINCOS as $keyCheckCGINCOS) {
              //       $cgApproveBy = explode(',', $keyCheckCGINCOS['cg_approve_by']);
              //       if (!empty($cgApproveBy)) {
              //         for ($i = 0; $i < countArray($cgApproveBy); $i++) {
              //           if ($user['u_id'] == $cgApproveBy[$i]) {
              //             $isCanEdit = true;
              //           }
              //         }
              //       }
              //   }
              // }
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }

          $where = 'fil_isDelete="0" and cos_id="'.$cos_id.'"';
          $fetch = $this->func_query->query_result('lms_cos_fil','','','',$where,'fil_cos_id DESC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            $update = '<button type="button" name="update_cosdoc" id="'.$value['fil_cos_id'].'" title="'.label('m_edit').'" class="btn btn-warning btn-xs update_cosdoc"><i class="mdi mdi-lead-pencil"></i></button>';
            $delete = '<button type="button" name="delete_cosdoc" id="'.$value['fil_cos_id'].'" class="btn btn-danger btn-xs delete_cosdoc" title="'.label('delete').'"><i class="mdi mdi-window-close"></i></button>';
            $output = array();
            $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;

            if($lang=="thai"){ 
              $name_file = $value['name_file_th']!=""?$value['name_file_th']:$value['name_file_eng'];
              $name_file = $name_file!=""?$name_file:$value['name_file_jp'];
            }else if($lang=="english"){ 
              $name_file = $value['name_file_eng']!=""?$value['name_file_eng']:$value['name_file_th'];
              $name_file = $name_file!=""?$name_file:$value['name_file_jp'];
            }else{
              $name_file = $value['name_file_jp']!=""?$value['name_file_jp']:$value['name_file_eng'];
              $name_file = $name_file!=""?$name_file:$value['name_file_th'];
            }
            $output['2'] = $name_file; 
            $fil_lang = explode(',', $value['fil_lang']);
            $fil_lang_txt = "";
    

                      if($value['name_file_eng']!=""){
                        $fil_lang_txt .= "EN";
                      }
                      if($value['name_file_th']!=""){
                        $fil_lang_txt = $fil_lang_txt!=""?$fil_lang_txt.",":"";
                        $fil_lang_txt .= "TH";
                      }
                      if($value['name_file_jp']!=""){
                        $fil_lang_txt = $fil_lang_txt!=""?$fil_lang_txt.",":"";
                        $fil_lang_txt .= "JP";
                      }
            $output['3'] = $fil_lang_txt;

              if($lang=="thai"){
              $output['4'] = $value['fil_modifieddate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['fil_modifieddate'])).(date('Y',strtotime($value['fil_modifieddate']))+543)." ".date('H:i',strtotime($value['fil_modifieddate'])):"<center>-</center>";
              }else{
              $output['4'] = $value['fil_modifieddate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['fil_modifieddate'])):"<center>-</center>";
              }
            
            if($btn_update!="1"){
                $update = "";
            }
            if($btn_delete!="1"){
                $delete = "";
            }
                  if(!$isCanEdit){
                    $output['0'] = "<center>-</center>";
                  }else{
                    $output['0'] = "<center>".$update." ".$delete."</center>";
                  }
            $count++;
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_cos_document_view($cos_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $page = "managecourse/courses_all";
          date_default_timezone_set("Asia/Bangkok");

          $where = 'fil_isDelete="0" and cos_id="'.$cos_id.'"';
          $fetch = $this->func_query->query_result('lms_cos_fil','','','',$where,'fil_cos_id DESC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            $output = array();
            $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
            $fil_lang_txt = "";
            if($value['name_file_eng']!=""){
              $fil_lang_txt .= "EN";
            }
            if($value['name_file_th']!=""){
              $fil_lang_txt = $fil_lang_txt!=""?$fil_lang_txt.",":"";
              $fil_lang_txt .= "TH";
            }
            if($value['name_file_jp']!=""){
              $fil_lang_txt = $fil_lang_txt!=""?$fil_lang_txt.",":"";
              $fil_lang_txt .= "JP";
            }
            $output['1'] = $fil_lang_txt;

            if ($lang=="thai") {
              $name_file = $value['name_file_th']!=""?$value['name_file_th']:$value['name_file_eng'];
              $name_file = $name_file!=""?$name_file:$value['name_file_jp'];
            } elseif ($lang=="english") { 
              $name_file = $value['name_file_eng']!=""?$value['name_file_eng']:$value['name_file_th'];
              $name_file = $name_file!=""?$name_file:$value['name_file_jp'];
            }else{
              $name_file = $value['name_file_jp']!=""?$value['name_file_jp']:$value['name_file_eng'];
              $name_file = $name_file!=""?$name_file:$value['name_file_th'];
            }
            $output['2'] = $name_file;
            $output['3'] = $value['fil_modifieddate']!="0000-00-00 00:00:00"? ($lang=="thai" ? date('d/m/',strtotime($value['fil_modifieddate'])).(date('Y',strtotime($value['fil_modifieddate']))+543)." ".date('H:i',strtotime($value['fil_modifieddate'])) : date('d/m/Y H:i',strtotime($value['fil_modifieddate']))) :"<center>-</center>";
            
            
            $count++;
            array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }


        public function fetch_data_course_detail($cos_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $this->manage->loadDB();
          $user = $this->session->userdata("user");
          $arr['page'] = "managecourse/courses_all";
          $this->db->select('lms_cos_detail.cosde_id,lms_cos_detail.date_start,lms_cos_detail.date_end');
          $this->db->from('lms_cos_detail');
          $this->db->where('lms_cos_detail.cos_id',$cos_id);
          $this->db->where('lms_cos_detail.cosde_isDelete','0');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();

          $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
          foreach ($fetch as $key => $value) {
              $output = array();
              if($arr['btn_update']=="1"||$arr['btn_delete']=="1"){
                $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
                if($value['date_start']!="0000-00-00 00:00:00"&&$value['date_end']!="0000-00-00 00:00:00"){
                    if($lang=="thai"){
                        $output['2'] = date('d',strtotime($value['date_start']))." ".$thaimonth[intval(date('m',strtotime($value['date_start'])))]." ".(date('Y',strtotime($value['date_start']))+543)." ".date('H:i',strtotime($value['date_start']));
                        $output['3'] = date('d',strtotime($value['date_end']))." ".$thaimonth[intval(date('m',strtotime($value['date_end'])))]." ".(date('Y',strtotime($value['date_end']))+543)." ".date('H:i',strtotime($value['date_end']));
                    }else{
                        $output['2'] = date('d F Y H:i',strtotime($value['date_start']));
                        $output['3'] = date('d F Y H:i',strtotime($value['date_end']));
                    }
                }else{
                    $output['2'] = label('UnlimitedTime');
                    $output['3'] = label('UnlimitedTime');
                }
                    $update = '<button type="button" name="update_period" id="'.$value['cosde_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_period"><i class="mdi mdi-lead-pencil"></i></button>';
                    $delete = '<button type="button" name="delete_period" id="'.$value['cosde_id'].'" class="btn btn-danger btn-xs delete_period" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                    if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                      $update = '';
                    }
                    if($arr['btn_delete']!="1"){
                      $delete = '';
                    }
                  $output['0'] = "<center>".$update." ".$delete."</center>";
              }else{
                $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                if($value['date_start']!="0000-00-00 00:00:00"&&$value['date_end']!="0000-00-00 00:00:00"){
                    if($lang=="thai"){
                        $output['1'] = date('d',strtotime($value['date_start']))." ".$thaimonth[intval(date('m',strtotime($value['date_start'])))]." ".(date('Y',strtotime($value['date_start']))+543)." ".date('H:i',strtotime($value['date_start']));
                        $output['2'] = date('d',strtotime($value['date_end']))." ".$thaimonth[intval(date('m',strtotime($value['date_end'])))]." ".(date('Y',strtotime($value['date_end']))+543)." ".date('H:i',strtotime($value['date_end']));
                    }else{
                        $output['1'] = date('d F Y H:i',strtotime($value['date_start']));
                        $output['2'] = date('d F Y H:i',strtotime($value['date_end']));
                    }
                }else{
                    $output['1'] = label('UnlimitedTime');
                    $output['2'] = label('UnlimitedTime');
                }
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_lesson($cos_id,$status_user) {
          date_default_timezone_set("Asia/Bangkok");
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $arr['page'] = "managecourse/courses_all";
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $where = 'lms_les.cos_id = "'.$cos_id.'" and les_isDelete="0"';
          if($status_user!=""){
            $where .= '((lms_les.time_start="0000-00-00 00:00:00" and lms_les.time_end="0000-00-00 00:00:00") or ("'.date('Y-m-d H:i').'" between lms_les.time_start and lms_les.time_end))';
          }
          $fetch_course = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              // $fetchCheckCGINCOS = $this->func_query->query_result(
              //   "lms_cosincg",
              //   "lms_cog",
              //   "lms_cosincg.cg_id = lms_cog.cg_id", "",
              //   "lms_cosincg.course_id = ".$cos_id
              // );
              // if (!empty($fetchCheckCGINCOS) && $fetch_course['cos_approve']!="1") {
              //   foreach ($fetchCheckCGINCOS as $keyCheckCGINCOS) {
              //       $cgApproveBy = explode(',', $keyCheckCGINCOS['cg_approve_by']);
              //       if (!empty($cgApproveBy)) {
              //         for ($i = 0; $i < countArray($cgApproveBy); $i++) {
              //           if ($user['u_id'] == $cgApproveBy[$i]) {
              //             $isCanEdit = true;
              //           }
              //         }
              //       }
              //   }
              // }
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }

          $fetch = $this->func_query->query_result('lms_les','','','',$where,'lms_les.les_sequences ASC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
          foreach ($fetch as $key => $value) {
              $output = array();
              $time_start_les = "";
              $time_end_les = "";

              if($value['time_start']=="0000-00-00 00:00:00"){
                $value['time_start_var'] = "";
                $value['time_start'] = "";
              }else{
                $value['time_start_var'] = $value['time_start'];
                $value['time_start'] = date('d/F/Y',strtotime($value['time_start']));
              }
              if($value['time_end']=="0000-00-00 00:00:00"){
                $value['time_end_var'] = "";
                $value['time_end'] = "";
              }else{
                $value['time_end_var'] = $value['time_end'];
                $value['time_end'] = date('d/F/Y',strtotime($value['time_end']));
              }
                  if($lang=="thai"){ 
                    $les_name = $value['les_name_th']!=""?$value['les_name_th']:$value['les_name_eng'];
                    $les_name = $les_name!=""?$les_name:$value['les_name_jp'];
                  }else if($lang=="english"){ 
                    $les_name = $value['les_name_eng']!=""?$value['les_name_eng']:$value['les_name_th'];
                    $les_name = $les_name!=""?$les_name:$value['les_name_jp'];
                  }else{
                    $les_name = $value['les_name_jp']!=""?$value['les_name_jp']:$value['les_name_eng'];
                    $les_name = $les_name!=""?$les_name:$value['les_name_th'];
                  }
                  $les_lang = explode(',', $value['les_lang']);
                  $les_lang_txt = "";

                      if($value['les_name_eng']!=""){
                        $les_lang_txt .= "EN";
                      }
                      if($value['les_name_th']!=""){
                        $les_lang_txt = $les_lang_txt!=""?$les_lang_txt.",":"";
                        $les_lang_txt .= "TH";
                      }
                      if($value['les_name_jp']!=""){
                        $les_lang_txt = $les_lang_txt!=""?$les_lang_txt.",":"";
                        $les_lang_txt .= "JP";
                      }
                  //}
              if($status_user==""){
                if($arr['btn_update']=="1"||$arr['btn_delete']=="1"){
                  $update = '<button type="button" name="update_lesson" id="'.$value['les_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_lesson"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_lesson" id="'.$value['les_id'].'" class="btn btn-danger btn-xs delete_lesson" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
                  $numloop_col = 1;
                  if(!$isCanEdit){
                    $output['0'] = "<center>-</center>";
                  }else{
                    $output['0'] = "<center>".$update." ".$delete."</center>";
                  }
                  $output[$numloop_col] = "<span style='float:right;'>".$num."</span>";$num++;$numloop_col++;
                  $output[$numloop_col] = "<center>".$les_lang_txt."</center>";$numloop_col++;
                  $output[$numloop_col] = $les_name; $numloop_col++;
                  

                  if($lang=="thai"){

                    if($value['time_start']!=""){
                      $time_start_les = date('d/m',strtotime($value['time_start_var']))."/".(date('Y',strtotime($value['time_start_var']))+543)." ".date('H:i',strtotime($value['time_start_var']));//date('d ',strtotime($value['time_start_var'])).$thaimonth[intval(date('m',strtotime($value['time_start_var'])))]." ".(date('Y',strtotime($value['time_start_var']))+543)." ".date('H:i',strtotime($value['time_start_var']));
                    }else{
                      $time_start_les = "-";
                    }
                    if($value['time_end']!=""){
                      $time_end_les = date('d/m',strtotime($value['time_end_var']))."/".(date('Y',strtotime($value['time_end_var']))+543)." ".date('H:i',strtotime($value['time_end_var']));//date('d ',strtotime($value['time_end_var'])).$thaimonth[intval(date('m',strtotime($value['time_end_var'])))]." ".(date('Y',strtotime($value['time_end_var']))+543)." ".date('H:i',strtotime($value['time_end_var']));
                    }else{
                      $time_end_les = "-";
                    }
                  }else{

                    if($value['time_start']!=""){
                      $time_start_les = date('d/m/Y H:i',strtotime($value['time_start_var']));
                    }else{
                      $time_start_les = "-";
                    }
                    if($value['time_end']!=""){
                      $time_end_les = date('d/m/Y H:i',strtotime($value['time_end_var']));
                    }else{
                      $time_end_les = "-";
                    }
                  }
                  $output[$numloop_col] = "<center>".$time_start_les."</center>";$numloop_col++;
                  $output[$numloop_col] = "<center>".$time_end_les."</center>";$numloop_col++;
                }else{
                  $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                  $output['1'] = "<center>".$les_lang_txt."</center>";
                  $output['2'] = $les_name;
                  if($lang=="thai"){

                    if($value['time_start']!=""){
                      $time_start_les = date('d/m',strtotime($value['time_start_var']))."/".(date('Y',strtotime($value['time_start_var']))+543)." ".date('H:i',strtotime($value['time_start_var']));//date('d ',strtotime($value['time_start_var'])).$thaimonth[intval(date('m',strtotime($value['time_start_var'])))]." ".(date('Y',strtotime($value['time_start_var']))+543)." ".date('H:i',strtotime($value['time_start_var']));
                    }else{
                      $time_start_les = "-";
                    }
                    if($value['time_end']!=""){
                      $time_end_les = date('d/m',strtotime($value['time_end_var']))."/".(date('Y',strtotime($value['time_end_var']))+543)." ".date('H:i',strtotime($value['time_end_var']));//date('d ',strtotime($value['time_end_var'])).$thaimonth[intval(date('m',strtotime($value['time_end_var'])))]." ".(date('Y',strtotime($value['time_end_var']))+543)." ".date('H:i',strtotime($value['time_end_var']));
                    }else{
                      $time_end_les = "-";
                    }
                  }else{

                    if($value['time_start']!=""){
                      $time_start_les = date('d/F/Y H:i',strtotime($value['time_start_var']));
                    }
                    if($value['time_end']!=""){
                      $time_end_les = date('d/F/Y H:i',strtotime($value['time_end_var']));
                    }
                  }
                  $output['3'] = "<center>".$time_start_les."</center>";
                  $output['4'] = "<center>".$time_end_les."</center>";
                }
              }else{
                $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                $output['1'] = $les_name;
                $status = '<b style="color:#ff0000"><i class="mdi mdi-close-octagon-outline"></i> '.label('not_start').'</b>';
                $fetch_chk = $this->func_query->query_row('lms_les_tc','','','','les_id="'.$value['les_id'].'" and emp_id = "'.$user['emp_id'].'"');
                if(countArray($fetch_chk)>0){
                  if($fetch_chk['learn_status']=="1"){
                    $status = '<b style="color:#e6b800"><i class="mdi mdi-timer-sand"></i> '.label('inProgress').'</b>';
                  }else if($fetch_chk['learn_status']=="2"){
                    $status = '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
                  }else if($fetch_chk['learn_status']=="3"){
                    $status = '<b style="color:orange"><i class="mdi mdi-alert-box"></i> '.label('fail').'</b>';
                  }
                }
                $output['2'] = "<center>".$status."</center>";
                $output['3'] = $value['les_id'];
              }

              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_lesson_view($cos_id, $status_user) {
          date_default_timezone_set("Asia/Bangkok");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $arr['page'] = "managecourse/courses_all";
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $where = 'lms_les.cos_id = "'.$cos_id.'" and les_isDelete="0"';
          if($status_user!=""){
            $where .= '((lms_les.time_start="0000-00-00 00:00:00" and lms_les.time_end="0000-00-00 00:00:00") or ("'.date('Y-m-d H:i').'" between lms_les.time_start and lms_les.time_end))';
          }
          $fetch_course = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }

          $fetch = $this->func_query->query_result('lms_les','','','',$where,'lms_les.les_sequences ASC');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $time_start_les = "";
              $time_end_les = "";

              if($value['time_start']=="0000-00-00 00:00:00"){
                $value['time_start_var'] = "";
                $value['time_start'] = "";
              }else{
                $value['time_start_var'] = $value['time_start'];
                $value['time_start'] = date('d/F/Y',strtotime($value['time_start']));
              }
              if($value['time_end']=="0000-00-00 00:00:00"){
                $value['time_end_var'] = "";
                $value['time_end'] = "";
              }else{
                $value['time_end_var'] = $value['time_end'];
                $value['time_end'] = date('d/F/Y',strtotime($value['time_end']));
              }

              if($lang=="thai"){
                $les_name = $value['les_name_th']!=""?$value['les_name_th']:$value['les_name_eng'];
                $les_name = $les_name!=""?$les_name:$value['les_name_jp'];
              }else if($lang=="english"){
                $les_name = $value['les_name_eng']!=""?$value['les_name_eng']:$value['les_name_th'];
                $les_name = $les_name!=""?$les_name:$value['les_name_jp'];
              }else{
                $les_name = $value['les_name_jp']!=""?$value['les_name_jp']:$value['les_name_eng'];
                $les_name = $les_name!=""?$les_name:$value['les_name_th'];
              }
              $les_lang_txt = "";

              if($value['les_name_eng']!=""){
                $les_lang_txt .= "EN";
              }
              if($value['les_name_th']!=""){
                $les_lang_txt = $les_lang_txt!=""?$les_lang_txt.",":"";
                $les_lang_txt .= "TH";
              }
              if($value['les_name_jp']!=""){
                $les_lang_txt = $les_lang_txt!=""?$les_lang_txt.",":"";
                $les_lang_txt .= "JP";
              }

              $view = '<button type="button" name="view_lesson" id="'.$value['les_id'].'" title="'.label('detail').'" class="btn btn-info btn-xs view_lesson"><i class="mdi mdi-note-multiple"></i></button>';

              $numloop_col = 1;
              $output['0'] = "<center>".$view."</center>";
              $output[$numloop_col] = "<span style='float:right;'>".$num."</span>";$num++;$numloop_col++;
              $output[$numloop_col] = "<center>".$les_lang_txt."</center>";$numloop_col++;
              $output[$numloop_col] = $les_name; $numloop_col++;
              

              if($value['time_start']!=""){
                $time_start_les = $lang=="thai" ? date('d/m',strtotime($value['time_start_var']))."/".(date('Y',strtotime($value['time_start_var']))+543)." ".date('H:i',strtotime($value['time_start_var'])) : date('d/m/Y H:i',strtotime($value['time_start_var']));
              }else{
                $time_start_les = "-";
              }
              if($value['time_end']!=""){
                $time_end_les = $lang=="thai" ? date('d/m',strtotime($value['time_end_var']))."/".(date('Y',strtotime($value['time_end_var']))+543)." ".date('H:i',strtotime($value['time_end_var'])) : date('d/m/Y H:i',strtotime($value['time_end_var']));
              }else{
                $time_end_les = "-";
              }
              
              $output[$numloop_col] = "<center>".$time_start_les."</center>";$numloop_col++;
              $output[$numloop_col] = "<center>".$time_end_les."</center>";$numloop_col++;

              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_quiz($cos_id,$status_user) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $arr['page'] = "managecourse/courses_all";

          $where = 'lms_qiz.cos_id = "'.$cos_id.'" and quiz_isDelete="0"';
          if($status_user!=""){
            $where .= '((lms_qiz.period_open="0000-00-00 00:00:00" and lms_qiz.period_end="0000-00-00 00:00:00") or ("'.date('Y-m-d H:i').'" between lms_qiz.period_open and lms_qiz.period_end))';
          }

          $fetch = $this->func_query->query_result('lms_qiz','','','',$where,'lms_qiz.qiz_id DESC');

          $fetch_course = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              // $fetchCheckCGINCOS = $this->func_query->query_result(
              //   "lms_cosincg",
              //   "lms_cog",
              //   "lms_cosincg.cg_id = lms_cog.cg_id", "",
              //   "lms_cosincg.course_id = ".$cos_id
              // );
              // if (!empty($fetchCheckCGINCOS) && $fetch_course['cos_approve']!="1") {
              //   foreach ($fetchCheckCGINCOS as $keyCheckCGINCOS) {
              //       $cgApproveBy = explode(',', $keyCheckCGINCOS['cg_approve_by']);
              //       if (!empty($cgApproveBy)) {
              //         for ($i = 0; $i < countArray($cgApproveBy); $i++) {
              //           if ($user['u_id'] == $cgApproveBy[$i]) {
              //             $isCanEdit = true;
              //           }
              //         }
              //       }
              //   }
              // }
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }
                 
          $num = 1;$count = 0;
          $fetch_arr = array();

          $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                $cos_lang = explode(',', $fetch_course['cos_lang']);
                                  $fetch_course['isTH'] = in_array('th',$cos_lang)?"1":"0";
                                  $fetch_course['isENG'] = in_array('eng',$cos_lang)?"1":"0";
                                  $fetch_course['isJP'] = in_array('jp',$cos_lang)?"1":"0";
          foreach ($fetch as $key => $value) {
              $output = array();

                                  $quiz_name = "";
                                  if($lang=="thai"){
                                      if($fetch_course['isTH']=="1"){
                                        $quiz_name = $value['quiz_name_th'];
                                      }else{
                                        if($quiz_name==""){
                                          $quiz_name = $value['quiz_name_eng'];
                                        }
                                        if($quiz_name==""){
                                          $quiz_name = $value['quiz_name_jp'];
                                        }
                                      }
                                  }else if($lang=="english"){
                                      if($fetch_course['isENG']=="1"){
                                        $quiz_name = $value['quiz_name_eng'];
                                      }else{
                                        if($quiz_name==""){
                                          $quiz_name = $value['quiz_name_th'];
                                        }
                                        if($quiz_name==""){
                                          $quiz_name = $value['quiz_name_jp'];
                                        }
                                      }
                                  }else{
                                      if($fetch_course['isJP']=="1"){
                                        $quiz_name = $value['quiz_name_jp'];
                                      }else{
                                        if($quiz_name==""){
                                          $quiz_name = $value['quiz_name_eng'];
                                        }
                                        if($quiz_name==""){
                                          $quiz_name = $value['quiz_name_th'];
                                        }
                                      }
                                  }
                  $quiz_lang = explode(',', $value['quiz_lang']);
                  $quiz_lang_txt = "";
              
                      if($value['quiz_name_eng']!=""){
                        $quiz_lang_txt .= "EN";
                      }
                      if($value['quiz_name_th']!=""){
                        $quiz_lang_txt = $quiz_lang_txt!=""?$quiz_lang_txt.",":"";
                        $quiz_lang_txt .= "TH";
                      }
                      if($value['quiz_name_jp']!=""){
                        $quiz_lang_txt = $quiz_lang_txt!=""?$quiz_lang_txt.",":"";
                        $quiz_lang_txt .= "JP";
                      }
                      //$numloop++;
                   // }
                 // }
                $quiz_type = $value['quiz_type']=="1"?label('preExam'):label('finalExam');
              if($status_user==""){
                //if($arr['btn_update']=="1"||$arr['btn_delete']=="1"){
                  $numloop_col = 1;
                  if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                      $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                      $delete = '';
                  }
                  $update = '<button type="button" name="update_quiz" id="'.$value['qiz_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_quiz"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_quiz" id="'.$value['qiz_id'].'" class="btn btn-danger btn-xs delete_quiz" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';
                  if(!$isCanEdit){
                    $output['0'] = '<center><button type="button" name="quiz_detail" id="'.$value['qiz_id'].'" title="'.label('question').'" class="btn btn-info btn-xs quiz_detail"><i class="mdi mdi-comment-question-outline"></i></button></center>';
                  }else{
                    $output['0'] = '<center><button type="button" name="quiz_detail" id="'.$value['qiz_id'].'" title="'.label('question').'" class="btn btn-info btn-xs quiz_detail"><i class="mdi mdi-comment-question-outline"></i></button> '.$update.' '.$delete.'</center>';
                  }
                  $score_total = 0;
                  $fetch_sum = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value['qiz_id'].'"  and ques_status="1" and ques_isDelete="0"');
                  if(countArray($fetch_sum)>0){
                    foreach ($fetch_sum as $key_sum => $value_sum) {
                      $score_total += floatval($value_sum['ques_score']);
                    }
                  }
                  $output[$numloop_col] = "<span style='float:right;'>".$num."</span>";$num++;$numloop_col++;
                  $output[$numloop_col] = '<center>'.$quiz_lang_txt.'</center>';$numloop_col++;
                  $output[$numloop_col] = $quiz_name;$numloop_col++;
                  $output[$numloop_col] = '<center>'.$quiz_type.'</center>';$numloop_col++;
                  $output[$numloop_col] = "<span style='float:right;'>".number_format($score_total)."</span>";$numloop_col++;
                  $output[$numloop_col] = "<span style='float:right;'>".$value['quiz_maxscore']."</span>";$numloop_col++;

                  if($lang=="thai"){

                    if($value['period_open']!=""&&$value['period_open']!="0000-00-00 00:00:00"){
                      $period_open = date('d/m',strtotime($value['period_open']))."/".(date('Y',strtotime($value['period_open']))+543)." ".date('H:i',strtotime($value['period_open']));//date('d ',strtotime($value['time_start_var'])).$thaimonth[intval(date('m',strtotime($value['time_start_var'])))]." ".(date('Y',strtotime($value['time_start_var']))+543)." ".date('H:i',strtotime($value['time_start_var']));
                    }else{
                      $period_open = "-";
                    }
                    if($value['period_end']!=""&&$value['period_end']!="0000-00-00 00:00:00"){
                      $period_end = date('d/m',strtotime($value['period_end']))."/".(date('Y',strtotime($value['period_end']))+543)." ".date('H:i',strtotime($value['period_end']));//date('d ',strtotime($value['time_end_var'])).$thaimonth[intval(date('m',strtotime($value['time_end_var'])))]." ".(date('Y',strtotime($value['time_end_var']))+543)." ".date('H:i',strtotime($value['time_end_var']));
                    }else{
                      $period_end = "-";
                    }
                  }else{

                    if($value['period_open']!=""&&$value['period_open']!="0000-00-00 00:00:00"){
                      $period_open = date('d/m/Y H:i',strtotime($value['period_open']));
                    }else{
                      $period_open = "-";
                    }
                    if($value['period_end']!=""&&$value['period_end']!="0000-00-00 00:00:00"){
                      $period_end = date('d/m/Y H:i',strtotime($value['period_end']));
                    }else{
                      $period_end = "-";
                    }
                  }
                  $output[$numloop_col] = '<center>'.$period_open.'</center>';$numloop_col++;
                  $output[$numloop_col] = '<center>'.$period_end.'</center>';$numloop_col++;
                /*}else{
                  $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                  $output['1'] = '<center>'.$quiz_lang_txt.'</center>';
                  $output['2'] = $quiz_name;
                  $output['3'] = '<center>'.$quiz_type.'</center>';
                  $output['4'] = '<center>'.$value['quiz_maxscore'].'</center>';
                }*/
              }else{
                $score = 0;
                $status = '<b style="color:#ff0000"><i class="mdi mdi-close-octagon-outline"></i> '.label('not_start').'</b>';
                $fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value['qiz_id'].'" and emp_id="'.$user['emp_id'].'"');
                if(countArray($fetch_chk)>0){
                  $score = floatval($fetch_chk['sum_score']);
                  if($fetch_chk['qiz_status']=="1"){
                    $status = '<b style="color:#e6b800"><i class="mdi mdi-timer-sand"></i> '.label('preUnDone').'</b>';
                  }else if($fetch_chk['qiz_status']=="2"){
                    $status = '<b style="color:orange"><i class="mdi mdi-close-box"></i> '.label('cannot-complete').'</b>';
                  }else if($fetch_chk['qiz_status']=="3"){
                    $status = '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
                  }
                }
                $score_total = 0;
                $fetch_sum = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value['qiz_id'].'"  and ques_status="1" and ques_isDelete="0"');
                if(countArray($fetch_sum)>0){
                  foreach ($fetch_sum as $key_sum => $value_sum) {
                    $score_total += floatval($value_sum['ques_score']);
                  }
                  $score = (floatval($fetch_chk['sum_score'])/$score_total)*100;
                }

                $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                $output['1'] = $quiz_name;
                $output['2'] = '<center>'.$quiz_type.'</center>';
                $output['3'] = number_format($score,2)." / 100";
                $output['4'] = $status;
                $output['5'] = $value['qiz_id'];
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }
        

        public function fetch_course_quiz_view($cos_id, $status_user) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $arr['page'] = "managecourse/courses_all";

          $where = 'lms_qiz.cos_id = "'.$cos_id.'" and quiz_isDelete="0"';
          if($status_user!=""){
            $where .= '((lms_qiz.period_open="0000-00-00 00:00:00" and lms_qiz.period_end="0000-00-00 00:00:00") or ("'.date('Y-m-d H:i').'" between lms_qiz.period_open and lms_qiz.period_end))';
          }

          $fetch = $this->func_query->query_result('lms_qiz','','','',$where,'lms_qiz.qiz_id DESC');

          $fetch_course = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }
                 
          $num = 1;$count = 0;
          $fetch_arr = array();

          $cos_lang = explode(',', $fetch_course['cos_lang']);
          $fetch_course['isTH'] = in_array('th',$cos_lang)?"1":"0";
          $fetch_course['isENG'] = in_array('eng',$cos_lang)?"1":"0";
          $fetch_course['isJP'] = in_array('jp',$cos_lang)?"1":"0";
          foreach ($fetch as $key => $value) {
              $output = array();

              $quiz_name = "";
              if ($lang=="thai") {
                  if($fetch_course['isTH']=="1"){
                    $quiz_name = $value['quiz_name_th'];
                  }else{
                    if($quiz_name==""){
                      $quiz_name = $value['quiz_name_eng'];
                    }
                    if($quiz_name==""){
                      $quiz_name = $value['quiz_name_jp'];
                    }
                  }
              } elseif ($lang=="english") {
                  if($fetch_course['isENG']=="1"){
                    $quiz_name = $value['quiz_name_eng'];
                  }else{
                    if($quiz_name==""){
                      $quiz_name = $value['quiz_name_th'];
                    }
                    if($quiz_name==""){
                      $quiz_name = $value['quiz_name_jp'];
                    }
                  }
              } else {
                  if($fetch_course['isJP']=="1"){
                    $quiz_name = $value['quiz_name_jp'];
                  }else{
                    if($quiz_name==""){
                      $quiz_name = $value['quiz_name_eng'];
                    }
                    if($quiz_name==""){
                      $quiz_name = $value['quiz_name_th'];
                    }
                  }
              }
              
              $quiz_lang_txt = "";
              
              if($value['quiz_name_eng']!=""){
                $quiz_lang_txt .= "EN";
              }
              if($value['quiz_name_th']!=""){
                $quiz_lang_txt = $quiz_lang_txt!=""?$quiz_lang_txt.",":"";
                $quiz_lang_txt .= "TH";
              }
              if($value['quiz_name_jp']!=""){
                $quiz_lang_txt = $quiz_lang_txt!=""?$quiz_lang_txt.",":"";
                $quiz_lang_txt .= "JP";
              }
              
              $quiz_type = $value['quiz_type']=="1"?label('preExam'):label('finalExam');
              $numloop_col = 1;
              
              $questionBtn = '<button type="button" name="view_questions" id="'.$value['qiz_id'].'" title="'.label('question').'" class="btn btn-info btn-xs view_questions"><i class="mdi mdi-comment-question-outline"></i></button>';
              $viewQuiz = '<button type="button" name="view_quiz" id="'.$value['qiz_id'].'" title="'.label('detail').'" class="btn btn-info btn-xs view_quiz"><i class="mdi mdi-note-multiple"></i></button>';
              $output['0'] = textCenter($questionBtn.' '.$viewQuiz);

              $score_total = 0;
              $fetch_sum = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$value['qiz_id'].'"  and ques_status="1" and ques_isDelete="0"','', 'ques_id,ques_score');
              if(countArray($fetch_sum)>0){
                foreach ($fetch_sum as $key_sum => $value_sum) {
                  $score_total += floatval($value_sum['ques_score']);
                }
              }
              $output[$numloop_col] = "<span style='float:right;'>".$num."</span>";$num++;$numloop_col++;
              $output[$numloop_col] = '<center>'.$quiz_lang_txt.'</center>';$numloop_col++;
              $output[$numloop_col] = $quiz_name;$numloop_col++;
              $output[$numloop_col] = '<center>'.$quiz_type.'</center>';$numloop_col++;
              $output[$numloop_col] = "<span style='float:right;'>".number_format($score_total)."</span>";$numloop_col++;
              $output[$numloop_col] = "<span style='float:right;'>".$value['quiz_maxscore']."</span>";$numloop_col++;

              if($value['period_open']!=""&&$value['period_open']!="0000-00-00 00:00:00"){
                $period_open = $lang=="thai" ? date('d/m',strtotime($value['period_open']))."/".(date('Y',strtotime($value['period_open']))+543)." ".date('H:i',strtotime($value['period_open'])) : date('d/m/Y H:i',strtotime($value['period_open']));
              }else{
                $period_open = "-";
              }
              if($value['period_end']!=""&&$value['period_end']!="0000-00-00 00:00:00"){
                $period_end = $lang=="thai" ? date('d/m',strtotime($value['period_end']))."/".(date('Y',strtotime($value['period_end']))+543)." ".date('H:i',strtotime($value['period_end'])) : date('d/m/Y H:i',strtotime($value['period_end']));
              }else{
                $period_end = "-";
              }
              
              $output[$numloop_col] = '<center>'.$period_open.'</center>';$numloop_col++;
              $output[$numloop_col] = '<center>'.$period_end.'</center>';$numloop_col++;
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_question($qiz_id)
        {
          $thaimonth = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $arr['page'] = "managecourse/courses_all";
          $fetch = $this->func_query->query_result('lms_ques', '', '', '', 'qiz_id="' . $qiz_id . '" and ques_isDelete="0"');

          $fetch_qiz = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id="' . $qiz_id . '"');
          $cos_id = $fetch_qiz['cos_id'];
          $fetch_course = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              // $fetchCheckCGINCOS = $this->func_query->query_result(
              //   "lms_cosincg",
              //   "lms_cog",
              //   "lms_cosincg.cg_id = lms_cog.cg_id", "",
              //   "lms_cosincg.course_id = ".$cos_id
              // );
              // if (!empty($fetchCheckCGINCOS) && $fetch_course['cos_approve']!="1") {
              //   foreach ($fetchCheckCGINCOS as $keyCheckCGINCOS) {
              //       $cgApproveBy = explode(',', $keyCheckCGINCOS['cg_approve_by']);
              //       if (!empty($cgApproveBy)) {
              //         for ($i = 0; $i < countArray($cgApproveBy); $i++) {
              //           if ($user['u_id'] == $cgApproveBy[$i]) {
              //             $isCanEdit = true;
              //           }
              //         }
              //       }
              //   }
              // }
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }

          $num = 1;
          $count = 0;
          $fetch_arr = array();
          $arr['btn_update'] = $this->manage->chk_permission($arr['page'], 'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'], 'ru_del');
          $summarySa = 0;
          $summarySub = 0;
          foreach ($fetch as $key => $value) {
            $output = array();
            $output['4'] = "";
            $output['1'] = "<span style='float:right;'>" . $num . "</span>";
            if ($value['ques_type'] == "sa") {
              $output['2'] = label('qt_sa');
              $output['qt_sa'] = 1;
              $output['qt_sub'] =  0;
            } else if ($value['ques_type'] == "sub") {
              $output['2'] = label('qt_sub');
              $output['qt_sub'] =  1;
              $output['qt_sa'] =  0;
            } else {
              if ($value['ques_type'] == "2choice") {
                $output['2'] = label('qt_twoChoice');
                $output['qt_sub'] =  0;
                $output['qt_sa'] = 0;
              } else {
                $output['2'] = label('qt_multi');
                $output['qt_sub'] =  0;
                $output['qt_sa'] = 0;
              }
              $fetch_mul = $this->func_query->query_result('lms_ques_mul', '', '', '', 'mul_isDelete="0" and ques_id="' . $value['ques_id'] . '"');
              if (count($fetch_mul) > 0) {
                foreach ($fetch_mul as $key_mul => $value_mul) {

                  if ($lang == "thai") {
                    $mul_c1 = $value_mul['mul_c1_th'] != "" ? $value_mul['mul_c1_th'] : $value_mul['mul_c1_eng'];
                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_mul['mul_c1_jp'];
                    $mul_c2 = $value_mul['mul_c2_th'] != "" ? $value_mul['mul_c2_th'] : $value_mul['mul_c2_eng'];
                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_mul['mul_c2_jp'];
                    $mul_c3 = $value_mul['mul_c3_th'] != "" ? $value_mul['mul_c3_th'] : $value_mul['mul_c3_eng'];
                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_mul['mul_c3_jp'];
                    $mul_c4 = $value_mul['mul_c4_th'] != "" ? $value_mul['mul_c4_th'] : $value_mul['mul_c4_eng'];
                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_mul['mul_c4_jp'];
                    $mul_c5 = $value_mul['mul_c5_th'] != "" ? $value_mul['mul_c5_th'] : $value_mul['mul_c5_eng'];
                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_mul['mul_c5_jp'];
                  } else if ($lang == "english") {
                    $mul_c1 = $value_mul['mul_c1_eng'] != "" ? $value_mul['mul_c1_eng'] : $value_mul['mul_c1_th'];
                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_mul['mul_c1_jp'];
                    $mul_c2 = $value_mul['mul_c2_eng'] != "" ? $value_mul['mul_c2_eng'] : $value_mul['mul_c2_th'];
                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_mul['mul_c2_jp'];
                    $mul_c3 = $value_mul['mul_c3_eng'] != "" ? $value_mul['mul_c3_eng'] : $value_mul['mul_c3_th'];
                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_mul['mul_c3_jp'];
                    $mul_c4 = $value_mul['mul_c4_eng'] != "" ? $value_mul['mul_c4_eng'] : $value_mul['mul_c4_th'];
                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_mul['mul_c4_jp'];
                    $mul_c5 = $value_mul['mul_c5_eng'] != "" ? $value_mul['mul_c5_eng'] : $value_mul['mul_c5_th'];
                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_mul['mul_c5_jp'];
                  } else {
                    $mul_c1 = $value_mul['mul_c1_jp'] != "" ? $value_mul['mul_c1_jp'] : $value_mul['mul_c1_eng'];
                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_mul['mul_c1_th'];
                    $mul_c2 = $value_mul['mul_c2_jp'] != "" ? $value_mul['mul_c2_jp'] : $value_mul['mul_c2_eng'];
                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_mul['mul_c2_th'];
                    $mul_c3 = $value_mul['mul_c3_jp'] != "" ? $value_mul['mul_c3_jp'] : $value_mul['mul_c3_eng'];
                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_mul['mul_c3_th'];
                    $mul_c4 = $value_mul['mul_c4_jp'] != "" ? $value_mul['mul_c4_jp'] : $value_mul['mul_c4_eng'];
                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_mul['mul_c4_th'];
                    $mul_c5 = $value_mul['mul_c5_jp'] != "" ? $value_mul['mul_c5_jp'] : $value_mul['mul_c5_eng'];
                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_mul['mul_c5_th'];
                  }
                  if ($mul_c1 != "") {
                    $output['4'] .= "1." . $this->str_replace_func(strip_tags($mul_c1)) . "<br>";
                  }
                  if ($mul_c2 != "") {
                    $output['4'] .= "2." . $this->str_replace_func(strip_tags($mul_c2)) . "<br>";
                  }
                  if ($value['ques_type'] == "multi") {
                    if ($mul_c3 != "") {
                      $output['4'] .= "3." . $this->str_replace_func(strip_tags($mul_c3)) . "<br>";
                    }
                    if ($mul_c4 != "") {
                      $output['4'] .= "4." . $this->str_replace_func(strip_tags($mul_c4)) . "<br>";
                    }
                    if ($mul_c5 != "") {
                      $output['4'] .= "5." . $this->str_replace_func(strip_tags($mul_c5)) . "<br>";
                    }
                  }
                }
              }
            }
            $statusques = $value['ques_status'] == "1" ? label('open') : label('close');
            $output['5'] = '<center>' . $statusques . '</center>';
            if ($lang == "thai") {
              $ques_name = $value['ques_name_th'] != "" ? $value['ques_name_th'] : $value['ques_name_eng'];
              $ques_name = $ques_name != "" ? $ques_name : $value['ques_name_jp'];
            } else if ($lang == "english") {
              $ques_name = $value['ques_name_eng'] != "" ? $value['ques_name_eng'] : $value['ques_name_th'];
              $ques_name = $ques_name != "" ? $ques_name : $value['ques_name_jp'];
            } else {
              $ques_name = $value['ques_name_jp'] != "" ? $value['ques_name_jp'] : $value['ques_name_eng'];
              $ques_name = $ques_name != "" ? $ques_name : $value['ques_name_th'];
            }
            $output['3'] = $ques_name != "" ? strip_tags($ques_name) : "";
            $update = '<button type="button" name="update_ques" id="' . $value['ques_id'] . '" title="' . label('sedit') . '" class="btn btn-warning btn-xs update_ques"><i class="mdi mdi-lead-pencil"></i></button>';
            $delete = '<button type="button" name="delete_ques" id="' . $value['ques_id'] . '" class="btn btn-danger btn-xs delete_ques" title="' . label('sdelete') . '"><i class="mdi mdi-window-close"></i></button>';
            $rechk_answer = '<button type="button" name="check_ques" id="' . $value['ques_id'] . '" title="' . label('chk_answer') . '" class="btn btn-info btn-xs check_ques"><i class="mdi mdi-check-circle"></i></button>';
            if ($arr['btn_update'] != "1") {
              $update = '';
            }
            if ($arr['btn_delete'] != "1") {
              $delete = '';
            }

            if ($value['ques_type'] == "2choice" || $value['ques_type'] == "multi") {
              $rechk_answer = '';
            }
            $numloop_col = 1;

            if (!$isCanEdit) {
              $rechk_answer = !checkValueIsNullTypeString($rechk_answer) ? $rechk_answer : '-';
              $output['0'] = '<center>' . $rechk_answer . '</center>';
            } else {
              $output['0'] = '<center>' . $rechk_answer .' '. $update .' '. $delete . '</center>';
            }
            $num_chk = 0;
            if ($update == "") {
              $num_chk++;
            }
            if ($delete == "") {
              $num_chk++;
            }
            if ($rechk_answer == "") {
              $num_chk++;
            }
            if ($num_chk >= 3) {
              $output['0'] = "<center>-</center>";
            }

            $count++;
            $num++;

            array_push($fetch_arr, $output);
          }

          return $fetch_arr;
        }

        public function fetch_course_question_view($qiz_id)
        {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $arr['page'] = "managecourse/courses_all";
          $fetch = $this->func_query->query_result('lms_ques', '', '', '', 'qiz_id="' . $qiz_id . '" and ques_isDelete="0"');

          $fetch_qiz = $this->func_query->query_row('lms_qiz', '', '', '', 'qiz_id="' . $qiz_id . '"');
          $cos_id = $fetch_qiz['cos_id'];
          $fetch_course = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }

          $num = 1;
          $count = 0;
          $fetch_arr = array();
          
          foreach ($fetch as $key => $value) {
            $output = array();
            $output['0'] = "<span style='float:right;'>" . $num . "</span>";
            $output['3'] = "";
            if ($value['ques_type'] == "sa") {
              $output['1'] = label('qt_sa');
              $output['qt_sa'] = 1;
              $output['qt_sub'] =  0;
            } elseif ($value['ques_type'] == "sub") {
              $output['1'] = label('qt_sub');
              $output['qt_sub'] =  1;
              $output['qt_sa'] =  0;
            } else {
              if ($value['ques_type'] == "2choice") {
                $output['1'] = label('qt_twoChoice');
                $output['qt_sub'] =  0;
                $output['qt_sa'] = 0;
              } else {
                $output['1'] = label('qt_multi');
                $output['qt_sub'] =  0;
                $output['qt_sa'] = 0;
              }
              $arrChoice = array();
              $textAnswer = array();
              $fetch_mul = $this->func_query->query_result('lms_ques_mul', '', '', '', 'mul_isDelete="0" and ques_id="' . $value['ques_id'] . '"');
              if (!empty($fetch_mul)) {
                foreach ($fetch_mul as $key_mul => $value_mul) {

                  if ($lang == "thai") {
                    $mul_c1 = $value_mul['mul_c1_th'] != "" ? $value_mul['mul_c1_th'] : $value_mul['mul_c1_eng'];
                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_mul['mul_c1_jp'];
                    $mul_c2 = $value_mul['mul_c2_th'] != "" ? $value_mul['mul_c2_th'] : $value_mul['mul_c2_eng'];
                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_mul['mul_c2_jp'];
                    $mul_c3 = $value_mul['mul_c3_th'] != "" ? $value_mul['mul_c3_th'] : $value_mul['mul_c3_eng'];
                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_mul['mul_c3_jp'];
                    $mul_c4 = $value_mul['mul_c4_th'] != "" ? $value_mul['mul_c4_th'] : $value_mul['mul_c4_eng'];
                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_mul['mul_c4_jp'];
                    $mul_c5 = $value_mul['mul_c5_th'] != "" ? $value_mul['mul_c5_th'] : $value_mul['mul_c5_eng'];
                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_mul['mul_c5_jp'];
                  } else if ($lang == "english") {
                    $mul_c1 = $value_mul['mul_c1_eng'] != "" ? $value_mul['mul_c1_eng'] : $value_mul['mul_c1_th'];
                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_mul['mul_c1_jp'];
                    $mul_c2 = $value_mul['mul_c2_eng'] != "" ? $value_mul['mul_c2_eng'] : $value_mul['mul_c2_th'];
                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_mul['mul_c2_jp'];
                    $mul_c3 = $value_mul['mul_c3_eng'] != "" ? $value_mul['mul_c3_eng'] : $value_mul['mul_c3_th'];
                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_mul['mul_c3_jp'];
                    $mul_c4 = $value_mul['mul_c4_eng'] != "" ? $value_mul['mul_c4_eng'] : $value_mul['mul_c4_th'];
                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_mul['mul_c4_jp'];
                    $mul_c5 = $value_mul['mul_c5_eng'] != "" ? $value_mul['mul_c5_eng'] : $value_mul['mul_c5_th'];
                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_mul['mul_c5_jp'];
                  } else {
                    $mul_c1 = $value_mul['mul_c1_jp'] != "" ? $value_mul['mul_c1_jp'] : $value_mul['mul_c1_eng'];
                    $mul_c1 = $mul_c1 != "" ? $mul_c1 : $value_mul['mul_c1_th'];
                    $mul_c2 = $value_mul['mul_c2_jp'] != "" ? $value_mul['mul_c2_jp'] : $value_mul['mul_c2_eng'];
                    $mul_c2 = $mul_c2 != "" ? $mul_c2 : $value_mul['mul_c2_th'];
                    $mul_c3 = $value_mul['mul_c3_jp'] != "" ? $value_mul['mul_c3_jp'] : $value_mul['mul_c3_eng'];
                    $mul_c3 = $mul_c3 != "" ? $mul_c3 : $value_mul['mul_c3_th'];
                    $mul_c4 = $value_mul['mul_c4_jp'] != "" ? $value_mul['mul_c4_jp'] : $value_mul['mul_c4_eng'];
                    $mul_c4 = $mul_c4 != "" ? $mul_c4 : $value_mul['mul_c4_th'];
                    $mul_c5 = $value_mul['mul_c5_jp'] != "" ? $value_mul['mul_c5_jp'] : $value_mul['mul_c5_eng'];
                    $mul_c5 = $mul_c5 != "" ? $mul_c5 : $value_mul['mul_c5_th'];
                  }
                  
                  $arrAnswer = !checkValueIsNullTypeString($value_mul['mul_answer']) ? explode(",", $value_mul['mul_answer']) : array();
                  if ($mul_c1 != "") {
                    $output['3'] .= (in_array("mul_c1", $arrAnswer) ? "<span style='color:red;'>" : "")."1." . $this->str_replace_func(strip_tags($mul_c1)).(in_array("mul_c1", $arrAnswer) ? "</span>" : "") . "<br>";
                    $arrChoice["mul_c1"] = $this->str_replace_func($mul_c1);
                  }
                  if ($mul_c2 != "") {
                    $output['3'] .= (in_array("mul_c2", $arrAnswer) ? "<span style='color:red;'>" : "")."2." . $this->str_replace_func(strip_tags($mul_c2)).(in_array("mul_c2", $arrAnswer) ? "</span>" : "") . "<br>";
                    $arrChoice["mul_c2"] = $this->str_replace_func($mul_c2);
                  }
                  if ($value['ques_type'] == "multi") {
                    if ($mul_c3 != "") {
                      $output['3'] .= (in_array("mul_c3", $arrAnswer) ? "<span style='color:red;'>" : "")."3." . $this->str_replace_func(strip_tags($mul_c3)).(in_array("mul_c3", $arrAnswer) ? "</span>" : "") . "<br>";
                      $arrChoice["mul_c3"] = $this->str_replace_func($mul_c3);
                    }
                    if ($mul_c4 != "") {
                      $output['3'] .= (in_array("mul_c4", $arrAnswer) ? "<span style='color:red;'>" : "")."4." . $this->str_replace_func(strip_tags($mul_c4)).(in_array("mul_c4", $arrAnswer) ? "</span>" : "") . "<br>";
                      $arrChoice["mul_c4"] = $this->str_replace_func($mul_c4);
                    }
                    if ($mul_c5 != "") {
                      $output['3'] .= (in_array("mul_c5", $arrAnswer) ? "<span style='color:red;'>" : "")."5." . $this->str_replace_func(strip_tags($mul_c5)).(in_array("mul_c5", $arrAnswer) ? "</span>" : "") . "<br>";
                      $arrChoice["mul_c5"] = $this->str_replace_func($mul_c5);
                    }
                  }
                  if (!checkValueIsNullTypeString($value_mul['mul_answer'])) {
                    $arrAnswer = explode(",", $value_mul['mul_answer']);
                    if (!empty($arrAnswer)) {
                      foreach ($arrAnswer as $keyAnswer) {
                        if (isset($arrChoice[$keyAnswer])) {
                          array_push($textAnswer, $arrChoice[$keyAnswer]);
                        }
                      }
                    }
                  }
                }
              }
            }
            $statusques = $value['ques_status'] == "1" ? label('open') : label('close');
            $output['4'] = '<center>' . $statusques . '</center>';
            if ($lang == "thai") {
              $ques_name = $value['ques_name_th'] != "" ? $value['ques_name_th'] : $value['ques_name_eng'];
              $ques_name = $ques_name != "" ? $ques_name : $value['ques_name_jp'];
            } else if ($lang == "english") {
              $ques_name = $value['ques_name_eng'] != "" ? $value['ques_name_eng'] : $value['ques_name_th'];
              $ques_name = $ques_name != "" ? $ques_name : $value['ques_name_jp'];
            } else {
              $ques_name = $value['ques_name_jp'] != "" ? $value['ques_name_jp'] : $value['ques_name_eng'];
              $ques_name = $ques_name != "" ? $ques_name : $value['ques_name_th'];
            }
            $output['2'] = $ques_name != "" ? strip_tags($ques_name) : "";
            


            $count++;
            $num++;

            array_push($fetch_arr, $output);
          }

          $this->manage->closeDB();
          return $fetch_arr;
        }

        public function fetch_quiz_question_check($ques_id) {

          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $arr['page'] = "managecourse/courses_all";
          function str_replace_func($value=""){
              $value = str_replace("<p>","",$value);
              $value = str_replace("</p>","",$value);
              return $value;
          }
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $arr_choice = array();
          $fetch_main = $this->func_query->query_row('lms_ques','','','','ques_id="'.$ques_id.'"');
          if($fetch_main['ques_type']=="multi"||$fetch_main['ques_type']=="2choice"){
            $fetch_mul = $this->func_query->query_row('lms_ques_mul','','','','ques_id="'.$ques_id.'"');

                      if($lang=="thai"){ 
                        $mul_c1 = $fetch_mul['mul_c1_th']!=""?$fetch_mul['mul_c1_th']:$fetch_mul['mul_c1_eng'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$fetch_mul['mul_c1_jp'];
                        $mul_c2 = $fetch_mul['mul_c2_th']!=""?$fetch_mul['mul_c2_th']:$fetch_mul['mul_c2_eng'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$fetch_mul['mul_c2_jp'];
                        $mul_c3 = $fetch_mul['mul_c3_th']!=""?$fetch_mul['mul_c3_th']:$fetch_mul['mul_c3_eng'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$fetch_mul['mul_c3_jp'];
                        $mul_c4 = $fetch_mul['mul_c4_th']!=""?$fetch_mul['mul_c4_th']:$fetch_mul['mul_c4_eng'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$fetch_mul['mul_c4_jp'];
                        $mul_c5 = $fetch_mul['mul_c5_th']!=""?$fetch_mul['mul_c5_th']:$fetch_mul['mul_c5_eng'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$fetch_mul['mul_c5_jp'];
                      }else if($lang=="english"){ 
                        $mul_c1 = $fetch_mul['mul_c1_eng']!=""?$fetch_mul['mul_c1_eng']:$fetch_mul['mul_c1_th'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$fetch_mul['mul_c1_jp'];
                        $mul_c2 = $fetch_mul['mul_c2_eng']!=""?$fetch_mul['mul_c2_eng']:$fetch_mul['mul_c2_th'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$fetch_mul['mul_c2_jp'];
                        $mul_c3 = $fetch_mul['mul_c3_eng']!=""?$fetch_mul['mul_c3_eng']:$fetch_mul['mul_c3_th'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$fetch_mul['mul_c3_jp'];
                        $mul_c4 = $fetch_mul['mul_c4_eng']!=""?$fetch_mul['mul_c4_eng']:$fetch_mul['mul_c4_th'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$fetch_mul['mul_c4_jp'];
                        $mul_c5 = $fetch_mul['mul_c5_eng']!=""?$fetch_mul['mul_c5_eng']:$fetch_mul['mul_c5_th'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$fetch_mul['mul_c5_jp'];
                      }else{
                        $mul_c1 = $fetch_mul['mul_c1_jp']!=""?$fetch_mul['mul_c1_jp']:$fetch_mul['mul_c1_eng'];
                        $mul_c1 = $mul_c1!=""?$mul_c1:$fetch_mul['mul_c1_th'];
                        $mul_c2 = $fetch_mul['mul_c2_jp']!=""?$fetch_mul['mul_c2_jp']:$fetch_mul['mul_c2_eng'];
                        $mul_c2 = $mul_c2!=""?$mul_c2:$fetch_mul['mul_c2_th'];
                        $mul_c3 = $fetch_mul['mul_c3_jp']!=""?$fetch_mul['mul_c3_jp']:$fetch_mul['mul_c3_eng'];
                        $mul_c3 = $mul_c3!=""?$mul_c3:$fetch_mul['mul_c3_th'];
                        $mul_c4 = $fetch_mul['mul_c4_jp']!=""?$fetch_mul['mul_c4_jp']:$fetch_mul['mul_c4_eng'];
                        $mul_c4 = $mul_c4!=""?$mul_c4:$fetch_mul['mul_c4_th'];
                        $mul_c5 = $fetch_mul['mul_c5_jp']!=""?$fetch_mul['mul_c5_jp']:$fetch_mul['mul_c5_eng'];
                        $mul_c5 = $mul_c5!=""?$mul_c5:$fetch_mul['mul_c5_th'];
                      }
                $arr_choice['mul_c1']=str_replace_func($mul_c1);
                $arr_choice['mul_c2']=str_replace_func($mul_c2);
                $arr_choice['mul_c3']=str_replace_func($mul_c3);
                $arr_choice['mul_c4']=str_replace_func($mul_c4);
                $arr_choice['mul_c5']=str_replace_func($mul_c5);
          }
          $fetch = $this->func_query->query_result('lms_ques_tc','lms_emp','lms_ques_tc.emp_id = lms_emp.emp_id','','ques_id="'.$ques_id.'" and tc_flag="true" and tc_save="true"','tc_id DESC','','','');
          $num = 1;$count = 0;
          $fetch_arr = array();
          $arr_emp_c = array();
          foreach ($fetch as $key => $value) {
            if(!in_array($value['emp_id'], $arr_emp_c)){
              array_push($arr_emp_c,$value['emp_id']);
            }else{
              unset($fetch[$key]);
            }
          }
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = $value['emp_c'];
              if($lang=="thai"){
                $output['1'] = $value['fullname_th'];
              }else{
                $output['1'] = $value['fullname_en'];
              }
              if($fetch_main['ques_type']=="multi"||$fetch_main['ques_type']=="2choice"){
                $output['2'] = "<span style='overflow-wrap: anywhere;'>".$arr_choice[$value['tc_answer']]."</span>";
                $output['3'] = "<center>-</center>";
                $output['4'] = "<span style='float:right;'>".intval($fetch_main['ques_score'])."</span>";
                $output['5'] = "<span style='float:right;'>".intval($value['tc_score'])."</span>";
              }else{
                if($value['tc_isSavescore']=="1"){
                $output['2'] = "<span style='overflow-wrap: anywhere;'>".$value['tc_answer']."</span>";
                $output['3'] = $value['tc_note']!=""?$value['tc_note']:"<center>-</center>";
                $output['4'] = "<span style='float:right;'>".intval($fetch_main['ques_score'])."</span>";
                $output['5'] = "<span style='float:right;'>".intval($value['tc_score'])."</span>";
                $output['6'] = "-";
                }else{
                $output['2'] = $value['tc_answer'];
                $output['3'] = '<input type="hidden" id="tc_id_'.$value['tc_id'].'" name="tc_id[]" value="'.$value['tc_id'].'"><textarea class="form-control" maxlength="10000" rows="3" id="tc_note_'.$value['tc_id'].'" name="tc_note[]">'.$value['tc_note'].'</textarea>';
                // onchange="changeNote_tc('.$value['tc_id'].')"
                // onkeyup="changeScore_tc('.$value['tc_id'].')" onchange="changeScore_tc('.$value['tc_id'].')"
                $output['4'] = "<span style='float:right;'>".intval($fetch_main['ques_score'])."</span>";
                $output['5'] = '<input type="hidden" id="ori_score_'.$value['tc_id'].'" name="ori_score[]" value="'.$value['tc_score'].'"><input type="hidden" id="ques_score_'.$value['tc_id'].'" name="ques_score_'.$value['tc_id'].'" value="'.intval($fetch_main['ques_score']).'"><input class="form-control" style="text-align: right;" type="text" id="score_'.$value['tc_id'].'" name="tc_score[]" value="'.intval($value['tc_score']).'" onkeypress="validate(event)" onkeyup="changeScore_tc('.$value['tc_id'].');" onchange="changeScore_tc('.$value['tc_id'].')">';
                $output['6'] = '<button type="button" name="save_answer_tc" id="'.$value['tc_id'].'" title="'.label('saveR').'" class="btn btn-success btn-xs save_answer_tc"><i class="mdi mdi-content-save"></i></button>';
                }
              }
              
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }



        public function fetch_data_enroll_detail($cos_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");

          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $arr['page'] = "managecourse/courses_all";

          $fetch = $this->func_query->query_result('lms_cos_enroll','lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id','','lms_cos_enroll.cos_id="'.$cos_id.'" and lms_cos_enroll.cosen_isDelete="0" and lms_emp.emp_isDelete="0"','','lms_emp.emp_id,lms_emp.emp_c,lms_emp.fullname_th,lms_emp.fullname_en,lms_cos_enroll.cosen_score,lms_cos_enroll.cosen_grade,lms_cos_enroll.cosen_status,lms_cos_enroll.cosen_id,lms_cos_enroll.cosen_finishtime,lms_cos_enroll.cosen_cancelnote,lms_cos_enroll.cosen_status_sub,lms_emp.com_id,lms_cos_enroll.cosen_firsttime');

          $fetch_qiz = $this->func_query->numrows('lms_qiz','','','','cos_id="'.$cos_id.'" and quiz_isDelete="0"');
          $fetch_lesson = $this->func_query->numrows('lms_les','','','','cos_id="'.$cos_id.'" and les_isDelete="0"');
          $fetch_cos = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'" ');
          $num = 1;$count = 0;
          $fetch_arr = array();
          $arr['page'] = "managecourse/courses_all";
          $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
          foreach ($fetch as $key => $value) {
              $fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$value['com_id'].'"');
              $output = array();
              $numrow = 0;
              if($arr['btn_delete']=="1"){
                if($value['cosen_status']=="2"){
                  $output[$numrow] = '<center><button type="button" name="Reenroll" id="'.$value['cosen_id'].'" class="btn btn-warning btn-xs Reenroll" title="Re Enroll"><i class="mdi mdi-backup-restore"></i></button></center>';
                }else{
                  $output[$numrow] = '<center><button type="button" name="delete_enroll" id="'.$value['cosen_id'].'" class="btn btn-danger btn-xs delete_enroll" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button></center>';
                }
                $numrow++;
              }
              $output[$numrow] = "<span style='float:right;'>".$num."</span>";$num++;$numrow++;

              if($lang=="thai"){
                $output[$numrow] = $value['fullname_th'];$numrow++;
                $output[$numrow] = $fetch_company['com_name_th'];$numrow++;
              }else{
                $output[$numrow] = $value['fullname_en'];$numrow++;
                $output[$numrow] = $fetch_company['com_name_eng'];$numrow++;
              }
              $status_student = label('not_start');
              if($value['cosen_status_sub']=="1"){
                $status_student = label('r_pass');
              } else if ($value['cosen_status_sub'] == "2") {
                if(checkDatetimeIsNull($value['cosen_firsttime'])){
                  $status_student = label('not_start');
                }else{
                  $status_student = label('inProgress');
                }
                $value['cosen_score'] = "-";
              }
              if($fetch_cos['cos_approve']=="1"){
                if($fetch_qiz>0){
                    $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                    $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                }else{
                  if($fetch_lesson>0){
                    $where_chk = 'les_id in (select lms_les.les_id from lms_les where cos_id="'.$cos_id.'") and cosen_id="'.$value['cosen_id'].'" and learn_status="2"';
                    $fetch_tcles = $this->func_query->numrows('lms_les_tc','','','',$where_chk);
                    if($fetch_lesson>=$fetch_tcles){
                        if($value['cosen_status']=="0"||$value['cosen_status']=="1"){
                          $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                            if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                              $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                            }else{
                              $output[$numrow] = '<input type="number" id="cosen_score'.$value['cosen_id'].'" name="cosen_score'.$value['cosen_id'].'" min="0" max="100" class="form-control" onchange="changeScore('.$value['cosen_id'].')" style="text-align:right;" value="'.number_format(floatval($value['cosen_score'])).'">';
                            }
                        }else{
                          $output[$numrow] = label('r_canceled')." (".$value['cosen_cancelnote'].")";$numrow++;
                          $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                        }
                    }else{
                      $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                      $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                    }
                  }else{
                    if($value['cosen_status']=="0"||$value['cosen_status']=="1"){
                      $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                        if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                          $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                        }else{
                          $output[$numrow] = '<input type="number" id="cosen_score'.$value['cosen_id'].'" name="cosen_score'.$value['cosen_id'].'" min="0" max="100" class="form-control" onchange="changeScore('.$value['cosen_id'].')" style="text-align:right;" value="'.number_format(floatval($value['cosen_score'])).'">';
                        }
                    }else{
                      $output[$numrow] = label('r_canceled')." (".$value['cosen_cancelnote'].")";$numrow++;
                      $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                    }
                  }
                }
              }else{
                  $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                  $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_enroll_detail_view($cos_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");

          $this->load->model('Function_query_model', 'func_query', false);
          $this->func_query->loadDB();
          $arr['page'] = "managecourse/courses_all";

          $fetch = $this->func_query->query_result(
            'lms_cos_enroll',
            'lms_emp',
            'lms_cos_enroll.emp_id = lms_emp.emp_id','',
            'lms_cos_enroll.cos_id="'.$cos_id.'" and lms_cos_enroll.cosen_isDelete="0" and lms_emp.emp_isDelete="0"','',
            'lms_emp.emp_id,lms_emp.emp_c,lms_emp.fullname_th,lms_emp.fullname_en,lms_cos_enroll.cosen_score,lms_cos_enroll.cosen_grade,
             lms_cos_enroll.cosen_status,lms_cos_enroll.cosen_id,lms_cos_enroll.cosen_finishtime,lms_cos_enroll.cosen_cancelnote,
             lms_cos_enroll.cosen_status_sub,lms_emp.com_id,lms_cos_enroll.cosen_firsttime'
          );

          $fetch_qiz = $this->func_query->numrows('lms_qiz','','','','cos_id="'.$cos_id.'" and quiz_isDelete="0"');
          $fetch_lesson = $this->func_query->numrows('lms_les','','','','cos_id="'.$cos_id.'" and les_isDelete="0"');
          $fetch_cos = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'" ', '', 'cos_approve');

          $num = 1;$count = 0;
          $fetch_arr = array();
          $arr['page'] = "managecourse/courses_all";
          
          foreach ($fetch as $key => $value) {
              $fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$value['com_id'].'"');
              $output = array();
              $numrow = 0;
              $output[$numrow] = "<span style='float:right;'>".$num."</span>";$num++;$numrow++;
              $output[$numrow] = $lang=="thai" ? $value['fullname_th'] : $value['fullname_en'];$numrow++;
              $output[$numrow] = $lang=="thai" ? $fetch_company['com_name_th'] : $fetch_company['com_name_eng'];$numrow++;
                
              $status_student = label('not_start');
              if ($value['cosen_status_sub']=="1") {
                $status_student = label('r_pass');
              } elseif ($value['cosen_status_sub'] == "2") {
                $status_student = label('inProgress');
                if (checkDatetimeIsNull($value['cosen_firsttime'])) {
                  $status_student = label('not_start');
                }
                $value['cosen_score'] = "-";
              }

              if($fetch_cos['cos_approve']=="1"){
                if($fetch_qiz>0){
                    $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                    $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                }else{
                  if($fetch_lesson>0){
                    $where_chk = 'les_id in (select lms_les.les_id from lms_les where cos_id="'.$cos_id.'") and cosen_id="'.$value['cosen_id'].'" and learn_status="2"';
                    $fetch_tcles = $this->func_query->numrows('lms_les_tc','','','',$where_chk);
                    if($fetch_lesson>=$fetch_tcles){
                        if($value['cosen_status']=="0"||$value['cosen_status']=="1"){
                          $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                            if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                              $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                            }else{
                              $output[$numrow] = '<input type="number" id="cosen_score'.$value['cosen_id'].'" name="cosen_score'.$value['cosen_id'].'" min="0" max="100" class="form-control" onchange="changeScore('.$value['cosen_id'].')" style="text-align:right;" value="'.number_format(floatval($value['cosen_score'])).'">';
                            }
                        }else{
                          $output[$numrow] = label('r_canceled')." (".$value['cosen_cancelnote'].")";$numrow++;
                          $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                        }
                    }else{
                      $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                      $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                    }
                  }else{
                    if($value['cosen_status']=="0"||$value['cosen_status']=="1"){
                      $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                      $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                    }else{
                      $output[$numrow] = label('r_canceled')." (".$value['cosen_cancelnote'].")";$numrow++;
                      $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
                    }
                  }
                }
              }else{
                  $output[$numrow] = $value['cosen_status']=="0"?label('not_start'):"<center>".$status_student."</center>";$numrow++;
                  $output[$numrow] = "<span style='float:right;'>".$value['cosen_score']."</span>";
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_videocourse($cos_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', false);
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $fetch = $this->func_query->query_result('lms_cos_video','','','','cos_id="'.$cos_id.'" and cosv_isDelete="0"');
          $num = 1;$count = 0;
          $fetch_arr = array();
          $arr['page'] = "managecourse/courses_all";

          $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
                  if($lang=="thai"){ 
                    $cosv = $value['cosv_th']!=""?$value['cosv_th']:$value['cosv_eng'];
                    $cosv = $cosv!=""?$cosv:$value['cosv_jp'];
                  }else if($lang=="english"){ 
                    $cosv = $value['cosv_eng']!=""?$value['cosv_eng']:$value['cosv_th'];
                    $cosv = $cosv!=""?$cosv:$value['cosv_jp'];
                  }else{
                    $cosv = $value['cosv_jp']!=""?$value['cosv_jp']:$value['cosv_eng'];
                    $cosv = $cosv!=""?$cosv:$value['cosv_th'];
                  }
                $output['2'] = $cosv;

              $output['3'] = $value['cosv_video'];

              $cosv_lang = explode(',', $value['cosv_lang']);
              $cosv_lang_txt = "";
    

                      if($value['cosv_eng']!=""){
                        $cosv_lang_txt .= "EN";
                      }
                      if($value['cosv_th']!=""){
                        $cosv_lang_txt = $cosv_lang_txt!=""?$cosv_lang_txt.",":"";
                        $cosv_lang_txt .= "TH";
                      }
                      if($value['cosv_jp']!=""){
                        $cosv_lang_txt = $cosv_lang_txt!=""?$cosv_lang_txt.",":"";
                        $cosv_lang_txt .= "JP";
                      }
        
                   
              $output['4'] = '<center>'.$cosv_lang_txt.'</center>';
              $delete = '<button type="button" name="delete_videocourse" id="'.$value['cosv_id'].'" class="btn btn-danger btn-xs delete_videocourse" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';
              $update = '<button type="button" name="update_videocourse" id="'.$value['cosv_id'].'" class="btn btn-warning btn-xs update_videocourse" title="'.label('sedit').'"><i class="mdi mdi-lead-pencil"></i></button>';

                  if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
                  if($value['cosv_type']=="1"){
                    $update = '';
                  }
                $output['0'] = "<center>".$update." ".$delete."</center>";
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_survey($cos_id,$status_user) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $user = $this->session->userdata('user');
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $arr['page'] = "managecourse/courses_all";
          $fetch = $this->func_query->query_result('lms_survey','','','','lms_survey.cos_id="'.$cos_id.'" and lms_survey.sv_isDelete="0"','sv_id DESC');
          $num = 1;$count = 0;

          $fetch_course = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              // $fetchCheckCGINCOS = $this->func_query->query_result(
              //   "lms_cosincg",
              //   "lms_cog",
              //   "lms_cosincg.cg_id = lms_cog.cg_id", "",
              //   "lms_cosincg.course_id = ".$cos_id
              // );
              // if (!empty($fetchCheckCGINCOS) && $fetch_course['cos_approve']!="1") {
              //   foreach ($fetchCheckCGINCOS as $keyCheckCGINCOS) {
              //       $cgApproveBy = explode(',', $keyCheckCGINCOS['cg_approve_by']);
              //       if (!empty($cgApproveBy)) {
              //         for ($i = 0; $i < countArray($cgApproveBy); $i++) {
              //           if ($user['u_id'] == $cgApproveBy[$i]) {
              //             $isCanEdit = true;
              //           }
              //         }
              //       }
              //   }
              // }
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }

          $fetch_arr = array();
          $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
          foreach ($fetch as $key => $value) {
              $output = array();
              
                $sv_lang = "";

                  if($lang=="thai"){ 
                    $sv_title = $value['sv_title_th']!=""?$value['sv_title_th']:$value['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$value['sv_title_jp'];
                  }else if($lang=="english"){ 
                    $sv_title = $value['sv_title_eng']!=""?$value['sv_title_eng']:$value['sv_title_th'];
                    $sv_title = $sv_title!=""?$sv_title:$value['sv_title_jp'];
                  }else{
                    $sv_title = $value['sv_title_jp']!=""?$value['sv_title_jp']:$value['sv_title_eng'];
                    $sv_title = $sv_title!=""?$sv_title:$value['sv_title_th'];
                  }
                  $sv_lang = explode(',', $value['sv_lang']);
                  $sv_lang_txt = "";
                 
                    $numloop = 1;
                      if($value['sv_title_eng']!=""){
                        $sv_lang_txt .= "EN";
                      }
                      if($value['sv_title_th']!=""){
                        $sv_lang_txt = $sv_lang_txt!=""?$sv_lang_txt.",":"";
                        $sv_lang_txt .= "TH";
                      }
                      if($value['sv_title_jp']!=""){
                        $sv_lang_txt = $sv_lang_txt!=""?$sv_lang_txt.",":"";
                        $sv_lang_txt .= "JP";
                      }
                    
                      $numloop++;
                 
              if($status_user==""){
                $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
                $output['2'] = '<center>'.$sv_lang_txt.'</center>';
                $output['3'] = $sv_title;
                if($lang=="thai"){
                if($value['survey_open']!="0000-00-00 00:00:00"){
                  /*$output['4'] = date('d',strtotime($value['survey_open']))." ".$thaimonth[intval(date('m',strtotime($value['survey_open'])))]." ".(date('Y',strtotime($value['survey_open']))+543)." ".date('H:i',strtotime($value['survey_open']));
                  $output['5'] = date('d',strtotime($value['survey_end']))." ".$thaimonth[intval(date('m',strtotime($value['survey_end'])))]." ".(date('Y',strtotime($value['survey_end']))+543)." ".date('H:i',strtotime($value['survey_end']));*/
                  $output['4'] = date('d/m',strtotime($value['survey_open']))."/".(date('Y',strtotime($value['survey_open']))+543)." ".date('H:i',strtotime($value['survey_open']));
                  $output['5'] = date('d/m',strtotime($value['survey_end']))."/".(date('Y',strtotime($value['survey_end']))+543)." ".date('H:i',strtotime($value['survey_end']));//date('d ',strtotime($value['time_start_var'])).$thaimonth[intval(date('m',strtotime($value['time_start_var'])))]." ".(date('Y',strtotime($value['time_start_var']))+543)." ".date('H:i',strtotime($value['time_start_var']));
                }else{
                  $output['4'] = "";
                  $output['5'] = "";
                }
                }else{
                if($value['survey_open']!="0000-00-00 00:00:00"){
                  $output['4'] = date('d/m/Y H:i',strtotime($value['survey_open']));
                  $output['5'] = date('d/m/Y H:i',strtotime($value['survey_end']));
                }else{
                  $output['4'] = "";
                  $output['5'] = "";
                }                }
                  $update = '<button type="button" name="update_survey" id="'.$value['sv_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_survey"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_survey" id="'.$value['sv_id'].'" class="btn btn-danger btn-xs delete_survey" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }

                  if(!$isCanEdit){
                    $output['0'] = '<center><button type="button" name="survey_detail" id="'.$value['sv_id'].'" title="'.label('question').'" class="btn btn-info btn-xs survey_detail"><i class="mdi mdi-comment-question-outline"></i></button></center>';
                  }else{
                    $output['0'] = '<center><button type="button" name="survey_detail" id="'.$value['sv_id'].'" title="'.label('question').'" class="btn btn-info btn-xs survey_detail"><i class="mdi mdi-comment-question-outline"></i></button> '.$update.' '.$delete.'</center>';
                  }
              }else{
                $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                $output['1'] = $sv_title;
                $status = '<b style="color:#ff0000"><i class="mdi mdi-close-octagon-outline"></i> '.label('not_start').'</b>';
                $fetch_chk = $this->func_query->query_row('lms_qn_user','','','','sv_id="'.$value['sv_id'].'" and emp_id="'.$user['emp_id'].'"');
                if(countArray($fetch_chk)>0){
                  if($fetch_chk['qnu_status']=="0"){
                    $status = '<b style="color:#e6b800"><i class="mdi mdi-timer-sand"></i> '.label('svUnDone').'</b>';
                  }else if($fetch_chk['qnu_status']=="2"){
                    $status = '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
                  }
                }
                $output['2'] = $status;
                $output['3'] = $value['sv_id'];
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_survey_view($cos_id,$status_user) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Function_query_model', 'func_query', false);
          $this->func_query->loadDB();
          $arr['page'] = "managecourse/courses_all";
          $fetch = $this->func_query->query_result('lms_survey','','','','lms_survey.cos_id="'.$cos_id.'" and lms_survey.sv_isDelete="0"','sv_id DESC');
          $num = 1;$count = 0;

          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();

              if ($lang=="thai") {
                $sv_title = $value['sv_title_th']!=""?$value['sv_title_th']:$value['sv_title_eng'];
                $sv_title = $sv_title!=""?$sv_title:$value['sv_title_jp'];
              } elseif ($lang=="english") {
                $sv_title = $value['sv_title_eng']!=""?$value['sv_title_eng']:$value['sv_title_th'];
                $sv_title = $sv_title!=""?$sv_title:$value['sv_title_jp'];
              } else {
                $sv_title = $value['sv_title_jp']!=""?$value['sv_title_jp']:$value['sv_title_eng'];
                $sv_title = $sv_title!=""?$sv_title:$value['sv_title_th'];
              }
              
              $sv_lang_txt = "";
                 
              $numloop = 1;
              if($value['sv_title_eng']!=""){
                $sv_lang_txt .= "EN";
              }
              if($value['sv_title_th']!=""){
                $sv_lang_txt = $sv_lang_txt!=""?$sv_lang_txt.",":"";
                $sv_lang_txt .= "TH";
              }
              if($value['sv_title_jp']!=""){
                $sv_lang_txt = $sv_lang_txt!=""?$sv_lang_txt.",":"";
                $sv_lang_txt .= "JP";
              }
              
              $numloop++;
                 
              $questionBtn = '<button type="button" name="view_questions_of_survey" id="'.$value['sv_id'].'" title="'.label('question').'" class="btn btn-info btn-xs view_questions_of_survey"><i class="mdi mdi-comment-question-outline"></i></button>';
              $viewSurvey = '<button type="button" name="view_survey" id="'.$value['sv_id'].'" title="'.label('detail').'" class="btn btn-info btn-xs view_survey"><i class="mdi mdi-note-multiple"></i></button>';
              $output['0'] = textCenter($questionBtn.' '.$viewSurvey);
              $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
              $output['2'] = '<center>'.$sv_lang_txt.'</center>';
              $output['3'] = $sv_title;
              
              if(!checkDatetimeIsNull($value['survey_open'])){
                $output['4'] = $lang=="thai" ? date('d/m',strtotime($value['survey_open']))."/".(date('Y',strtotime($value['survey_open']))+543)." ".date('H:i',strtotime($value['survey_open'])) : date('d/m/Y H:i',strtotime($value['survey_open']));
                $output['5'] = $lang=="thai" ? date('d/m',strtotime($value['survey_end']))."/".(date('Y',strtotime($value['survey_end']))+543)." ".date('H:i',strtotime($value['survey_end'])) : date('d/m/Y H:i',strtotime($value['survey_end']));
              } else {
                $output['4'] = "";
                $output['5'] = "";
              }

              $count++;
              array_push($fetch_arr, $output);
          }
          $this->func_query->closeDB();
          return $fetch_arr;
        }

        public function fetch_course_survey_detail($sv_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', false);
          $user = $this->session->userdata('user');
          $this->load->model('Function_query_model', 'func_query', false);
          $this->manage->loadDB();
          $arr['page'] = "managecourse/courses_all";
          $this->db->from('lms_survey_de');
          $this->db->where('lms_survey_de.sv_id',$sv_id);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $fetch_sv_id = $this->func_query->query_row('lms_survey','','','','sv_id="'.$sv_id.'"');
          $cos_id = $fetch_sv_id['cos_id'];
          $fetch_course = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');
          $fetch = $this->func_query->query_result('lms_survey_de','','','','sv_id="'.$sv_id.'" and svde_isDelete="0"');

          $isCanEdit = false;
          if ($fetch_course['cos_approve']=="1" || $fetch_course['cos_public']=="1") {
              // $fetchCheckCGINCOS = $this->func_query->query_result(
              //   "lms_cosincg",
              //   "lms_cog",
              //   "lms_cosincg.cg_id = lms_cog.cg_id", "",
              //   "lms_cosincg.course_id = ".$cos_id
              // );
              // if (!empty($fetchCheckCGINCOS) && $fetch_course['cos_approve']!="1") {
              //   foreach ($fetchCheckCGINCOS as $keyCheckCGINCOS) {
              //       $cgApproveBy = explode(',', $keyCheckCGINCOS['cg_approve_by']);
              //       if (!empty($cgApproveBy)) {
              //         for ($i = 0; $i < countArray($cgApproveBy); $i++) {
              //           if ($user['u_id'] == $cgApproveBy[$i]) {
              //             $isCanEdit = true;
              //           }
              //         }
              //       }
              //   }
              // }
              if ($user['u_id'] == "1") {
                $isCanEdit = true;
              }
          } else {
            $isCanEdit = true;
          }

          $num = 1;$count = 0;
          $fetch_arr = array();
          $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
          foreach ($fetch as $key => $value) {
              $output = array();

                  if($lang=="thai"){ 
                    $svde_heading = $value['svde_heading_th']!=""?$value['svde_heading_th']:$value['svde_heading_eng'];
                    $svde_heading = $svde_heading!=""?$svde_heading:$value['svde_heading_jp'];
                    $svde_detail = $value['svde_detail_th']!=""?$value['svde_detail_th']:$value['svde_detail_eng'];
                    $svde_detail = $svde_detail!=""?$svde_detail:$value['svde_detail_jp'];
                  }else if($lang=="english"){ 
                    $svde_heading = $value['svde_heading_eng']!=""?$value['svde_heading_eng']:$value['svde_heading_th'];
                    $svde_heading = $svde_heading!=""?$svde_heading:$value['svde_heading_jp'];
                    $svde_detail = $value['svde_detail_eng']!=""?$value['svde_detail_eng']:$value['svde_detail_th'];
                    $svde_detail = $svde_detail!=""?$svde_detail:$value['svde_detail_jp'];
                  }else{
                    $svde_heading = $value['svde_heading_jp']!=""?$value['svde_heading_jp']:$value['svde_heading_eng'];
                    $svde_heading = $svde_heading!=""?$svde_heading:$value['svde_heading_th'];
                    $svde_detail = $value['svde_detail_jp']!=""?$value['svde_detail_jp']:$value['svde_detail_eng'];
                    $svde_detail = $svde_detail!=""?$svde_detail:$value['svde_detail_th'];
                  }
              if($arr['btn_update']!="1"&&$arr['btn_delete']!="1"){
                $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                $output['1'] = $svde_heading;
                $output['2'] = $svde_detail;
              }else{
                $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
                $output['2'] = $svde_heading;
                $output['3'] = $svde_detail;

                    $update = '<button type="button" name="update_survey_detail" id="'.$value['svde_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_survey_detail"><i class="mdi mdi-lead-pencil"></i></button>';
                    $delete = '<button type="button" name="delete_survey_detail" id="'.$value['svde_id'].'" class="btn btn-danger btn-xs delete_survey_detail" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                    if(!isset($arr['btn_update']) || $arr['btn_update']!="1"){
                      $update = '';
                    }
                    if($arr['btn_delete']!="1"){
                      $delete = '';
                    }

                  if(!$isCanEdit){
                    $output['0'] = "<center>-</center>";
                  }else{
                    $output['0'] = "<center>".$update." ".$delete."</center>";
                  }
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_survey_detail_view($sv_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Function_query_model', 'func_query', false);
          $this->func_query->loadDB();
          $arr['page'] = "managecourse/courses_all";
          
          $fetch = $this->func_query->query_result('lms_survey_de','','','','sv_id="'.$sv_id.'" and svde_isDelete="0"');


          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();

              if ($lang=="thai") {
                $svde_heading = $value['svde_heading_th']!=""?$value['svde_heading_th']:$value['svde_heading_eng'];
                $svde_heading = $svde_heading!=""?$svde_heading:$value['svde_heading_jp'];
                $svde_detail = $value['svde_detail_th']!=""?$value['svde_detail_th']:$value['svde_detail_eng'];
                $svde_detail = $svde_detail!=""?$svde_detail:$value['svde_detail_jp'];
              } elseif ($lang=="english") {
                $svde_heading = $value['svde_heading_eng']!=""?$value['svde_heading_eng']:$value['svde_heading_th'];
                $svde_heading = $svde_heading!=""?$svde_heading:$value['svde_heading_jp'];
                $svde_detail = $value['svde_detail_eng']!=""?$value['svde_detail_eng']:$value['svde_detail_th'];
                $svde_detail = $svde_detail!=""?$svde_detail:$value['svde_detail_jp'];
              } else {
                $svde_heading = $value['svde_heading_jp']!=""?$value['svde_heading_jp']:$value['svde_heading_eng'];
                $svde_heading = $svde_heading!=""?$svde_heading:$value['svde_heading_th'];
                $svde_detail = $value['svde_detail_jp']!=""?$value['svde_detail_jp']:$value['svde_detail_eng'];
                $svde_detail = $svde_detail!=""?$svde_detail:$value['svde_detail_th'];
              }
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              $output['1'] = $svde_heading;
              $output['2'] = $svde_detail;
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetchLogEmail($comId, $statusEvent, $dateStart, $timeStart, $dateEnd, $timeEnd) {
            $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
            $user = $this->session->userdata('user');
            $this->load->model('Function_query_model', 'funcQuery', false);
            $this->funcQuery->loadDB();

            $arrFetch = array();
            if (!checkDateIsNull($dateStart) && !checkDateIsNull($dateEnd) && $comId != "") {
                $arrLogEmail = array();
                $arrEmployees = array();
                $arrUsergroup = array();
                $arrDepartment = array();

                $fetchUsergroups = $this->funcQuery->query_result("lms_usp_gp");
                if (!empty($fetchUsergroups)) {
                    foreach ($fetchUsergroups as $keyUsergroup) {
                        $arrUsergroup[$keyUsergroup["ug_id"]] = $lang == "thai" ? $keyUsergroup["ug_name_th"] : $keyUsergroup["ug_name_en"];
                    }
                }

                $fetchDepartments = $this->funcQuery->query_result("lms_depart");
                if (!empty($fetchDepartments)) {
                    foreach ($fetchDepartments as $keyDepartment) {
                        $arrDepartment[$keyDepartment["dep_id"]] = $lang == "thai" ? $keyDepartment["dep_name_th"] : $keyDepartment["dep_name_en"];
                    }
                }

                $arrCompany = array();
                $fetchCompanys = $this->funcQuery->query_result("lms_company");
                if (!empty($fetchCompanys)) {
                  foreach ($fetchCompanys as $keyCompany) {
                      $arrCompany[$keyCompany["com_id"]] = $keyCompany["com_code"];
                  }
                }
                $where = "";
                if ($comId != "All") {
                  $where = "lms_emp.com_id = ".$comId;
                }
                $fetchEmps = $this->funcQuery->query_result(
                  "lms_emp",
                  "lms_usp",
                  "lms_emp.emp_id = lms_usp.emp_id", "", $where, "",
                  "lms_emp.emp_id, lms_emp.com_id, lms_emp.fullname_th, lms_emp.fullname_en, lms_usp.useri, lms_usp.ug_id, lms_usp.dep_id"
                );
                if (!empty($fetchEmps)) {
                    foreach ($fetchEmps as $keyEmp) {
                      if (isset($arrCompany[$keyEmp["com_id"]])) {
                        $arrEmployees[$keyEmp["useri"]] = array(
                            "company"   => $arrCompany[$keyEmp["com_id"]],
                            "username"  => $keyEmp["useri"],
                            "fullname"  => $lang == "thai" ? $keyEmp["fullname_th"] : $keyEmp["fullname_en"],
                            "depName"   => isset($arrDepartment[$keyEmp["dep_id"]]) ? $arrDepartment[$keyEmp["dep_id"]] : "-",
                            "ugName"    => isset($arrUsergroup[$keyEmp["ug_id"]]) ? $arrUsergroup[$keyEmp["ug_id"]] : "-"
                        );
                      }
                    }
                }

                $fetchLogEmail = $this->funcQuery->query_result(
                  "lms_lg_email", "", "", "",
                  "lgm_date between '".$dateStart."' and '".$dateEnd."'"
                );
                if (!empty($fetchLogEmail)) {
                    foreach ($fetchLogEmail as $keyLogEmail) {
                        if ($keyLogEmail["lgm_json"] != "") {
                            $arrJson = (array) json_decode($keyLogEmail["lgm_json"]);
                            if (!empty($arrJson)) {
                                foreach ($arrJson as $keyJson) {
                                    $dataRow = (array) $keyJson;
                                    if (isset($dataRow["date"])) {
                                        $dataRow["date"] = date("Y-m-d H:i:s", strtotime($dataRow["date"]));

                                        $isPass = true;
                                        if ($statusEvent != "" && $statusEvent != "All") {
                                            if ($statusEvent != $dataRow["event"]) {
                                                $isPass = false;
                                            }
                                        }

                                        if (!(date("Y-m-d H:i:00", strtotime($dateStart." ".$timeStart)) <= $dataRow["date"] && date("Y-m-d H:i:00", strtotime($dateEnd." ".$timeEnd)) >= $dataRow["date"])) {
                                          $isPass = false;
                                        }

                                        if (isset($arrEmployees[$dataRow["email"]]) && $isPass) {
                                          $dataEmp = $arrEmployees[$dataRow["email"]];
  
                                          $dateDisplay = date("d/m/Y H:i:s", strtotime($dataRow["date"]));
                                          if ($lang == "thai") {
                                            $dateDisplay = date('d/m',strtotime($dataRow['date']))."/".(date('Y',strtotime($dataRow['date']))+543)." ".date('H:i:s',strtotime($dataRow['date']));
                                          }
  
                                          $arrLogDate = array(
                                            "display"   => $dateDisplay,
                                            "timestamp" => strtotime($dataRow["date"])
                                          );
  
                                          $output = array(
                                              "company"     => $dataEmp["company"],
                                              "user"        => $dataRow["email"],
                                              "fullname"    => $dataEmp["fullname"],
                                              "usergroup"   => textCenter($dataEmp["ugName"]),
                                              "department"  => textCenter($dataEmp["depName"]),
                                              "ipaddress"   => isset($dataRow["ip"]) ? $dataRow["ip"] : "",
                                              "subject"     => $dataRow["subject"],
                                              "logdate"     => $arrLogDate,
                                              "status"      => label('email_'.$dataRow["event"])
                                          );
                                          array_push($arrFetch, $output);
                                        }
                                    }

                                }
                            }
                        }
                    }
                }
            }

            return $arrFetch;
        }

        public function fetchLogImportUsers($comId, $dateStart, $timeStart, $dateEnd, $timeEnd) {
            $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
            $user = $this->session->userdata('user');
            $this->load->model('Function_query_model', 'funcQuery', false);
            $this->funcQuery->loadDB();

            $arrFetch = array();
            if (!checkDateIsNull($dateStart) && !checkDateIsNull($dateEnd)) {
                $arrLogEmail = array();
                $arrEmployees = array();

                $isPass = true;
                if ($comId != "" && $comId != "All") {
                  $fetchCompany = $this->funcQuery->query_row("lms_company", "", "", "", "com_id = ".$comId);
                  if (!isset($fetchCompany["com_id"])) {
                    $isPass = false;
                  }
                }
                if ($isPass) {
                  $where = "";
                  if ($comId != "" && $comId != "All") {
                    $where = "lms_emp.com_id = ".$comId;
                  }
                  $fetchEmps = $this->funcQuery->query_result(
                    "lms_emp",
                    "lms_usp",
                    "lms_emp.emp_id = lms_usp.emp_id", "", $where, "",
                    "lms_emp.emp_id, lms_emp.com_id, lms_emp.fullname_th, lms_emp.fullname_en, lms_usp.useri"
                  );
                  if (!empty($fetchEmps)) {
                      foreach ($fetchEmps as $keyEmp) {
                          $arrEmployees[$keyEmp["emp_id"]] = array(
                              "username"  => $keyEmp["useri"],
                              "fullname"  => $lang == "thai" ? $keyEmp["fullname_th"] : $keyEmp["fullname_en"]
                          );
                      }
                  }
  
                  $timeStart = $timeStart != "" ? $timeStart : "00:00";
                  $timeEnd = $timeEnd != "" ? $timeEnd : "23:59";
                  $fetchLogEmail = $this->funcQuery->query_result(
                    "lms_lg_import", "", "", "",
                    "lgi_datetime between '".$dateStart." ".$timeStart."' and '".$dateEnd." ".$timeEnd."'"
                  );
                  if (!empty($fetchLogEmail)) {
                      foreach ($fetchLogEmail as $keyLogEmail) {
                        if (isset($arrEmployees[$keyLogEmail["lgi_import_by"]])) {
                          $dataEmp = $arrEmployees[$keyLogEmail["lgi_import_by"]];
                          $detail = '<button type="button" name="detail" id="'.$keyLogEmail['lgi_id'].'" title="'.label('r_viewDetail').'" class="btn btn-info btn-xs view_detail"><i class="mdi mdi-format-list-bulleted"></i></button>';
                          $dateDisplay = date("d/m/Y H:i:s", strtotime($keyLogEmail["lgi_datetime"]));
                          if ($lang == "thai") {
                            $dateDisplay = date('d/m',strtotime($keyLogEmail['lgi_datetime']))."/".(date('Y',strtotime($keyLogEmail['lgi_datetime']))+543)." ".date('H:i:s',strtotime($keyLogEmail['lgi_datetime']));
                          }

                          $arrLogDate = array(
                            "display"   => $dateDisplay,
                            "timestamp" => strtotime($keyLogEmail["lgi_datetime"])
                          );
                          $output = array(
                              "detail"          => textCenter($detail),
                              "user"            => $dataEmp["username"],
                              "fullname"        => $dataEmp["fullname"],
                              "logdate"         => $arrLogDate,
                              "newUser"         => textCenter($keyLogEmail["lgi_new_user"] != "" ? number_format($keyLogEmail["lgi_new_user"]) : 0),
                              "duplicateUser"   => textCenter($keyLogEmail["lgi_duplicate_user"] != "" ? number_format($keyLogEmail["lgi_duplicate_user"]) : 0),
                              "removeUser"      => textCenter($keyLogEmail["lgi_remove_user"] != "" ? number_format($keyLogEmail["lgi_remove_user"]) : 0)
                          );
                          array_push($arrFetch, $output);
                        }
                      }
                  }
                }
            }

            return $arrFetch;
        }
}
