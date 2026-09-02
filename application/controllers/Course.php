<?php header("Content-Type: text/html; charset=utf-8"); ?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		if (isset($_GET["lang"]) && !checkValueIsNullTypeString($_GET["lang"])) {
			$this->session->set_userdata('lang', $_GET["lang"]);
		}
	}

	public function search_course(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();

       	$course_search = $this->course->search_course_data($_REQUEST['txtval_search']);
       	if(countArray($course_search)>0){ ?>
       		<div class="row">
       			<?php foreach ($course_search as $key => $value_cos) { ?>
       					<div class="col-md-3" style="cursor: pointer;">
                            <div class="card" style="height: 250px"onclick="onclickdetail_search('<?php echo $value_cos['id']; ?>')">
                                <img class="card-img-top img-responsive" src="<?php echo REAL_PATH;?>/uploads/course/<?php echo $value_cos['pic']; ?>" alt="">
                                <div class="card-body slimtest1 flex-column">
                                    <h4 class="card-title"><?php if($lang=="thai"){echo $value_cos['cname_th'];}else{echo $value_cos['cname_en'];} ?></h4>
                                </div>
                            </div>
                        </div>
       			<?php } ?>
       		</div>
  <?php }else{ ?>

  <?php }
	}

	public function loaddocument($id) {
		$arr['page'] = "course/loaddocument";
		$this->load->model('User_model', 'login', true);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', true);
		$this->load->model('Course_model', 'course', true);
		$this->load->model('Enroll_model', 'enroll', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Manage_model', 'manage', false);
    	$this->load->model('Coursegroup_model', 'coursegroup', true);

		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
    	$this->coursegroup->loadDB();

    	$this->db->where('lms_fil.id',$id);
    	$this->db->join('lms_les','lms_fil.lessons_id = lms_les.les_id');
    	$this->db->from('lms_fil');
    	$query = $this->db->get();
    	$arr['fil_lesson'] = $query->row_array();

		$this->load->view('frontend/view_document', $arr );
	}
	public function loadCourse() {
		$arr['page'] = "course/loadCourse";
		$this->load->model('User_model', 'login', true);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', true);
		$this->load->model('Course_model', 'course', true);
		$this->load->model('Enroll_model', 'enroll', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Manage_model', 'manage', false);
    	$this->load->model('Coursegroup_model', 'coursegroup', true);

		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
    	$this->coursegroup->loadDB();

			$arr['isNonEnroll'] = "";
        	$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
            $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
			$arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
			if($arr['btn_view']!="1"){
				redirect(base_url().'dashboard') ;
			}
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			if($lang=="thai"){
				$arr['com_name'] = $user['com_name_th'];
			}else{
				$arr['com_name'] = $user['com_name_en'];
			}
			$arr['cgcode'] = '';
			$arr['wcode'] = '';
			$arr['user'] = $user;
			$arr['loadmycos'] = '1';
			$this->load->helper(array('form', 'url'));
				if($arr['cgcode']!=""){
					$arr['courses'] = $this->course->getAllCos($arr['cgcode'],$user['emp_id']);
					$arr['courses_cg'] = $this->course->getcourse_group($arr['cgcode'],$user['emp_id']);
				}else{
					$arr['courses'] = $this->course->getAllCos('');
					$arr['courses_cg'] = $this->course->getcourse_group('');
				}
				$arr['rechk_permission_cg'] = $this->coursegroup->rechk_permission_cg();
					foreach ($arr['courses_cg'] as $key_cg => $value_cg) {
						if(!in_array($value_cg['id'], $arr['rechk_permission_cg'])){
							unset($arr['courses_cg'][$key_cg]);
						}
					}
			//$arr['seats'] = $this->course->countSeatsAllCos();


			//Record Log activity
			$this->lg->record('course', 'enter My course');

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/load_course', $arr );
		}
		$this->login->closeDB();
		$this->course->closeDB();
		$this->enroll->closeDB();
		$this->lg->closeDB();
		$this->foot->closeDB();
	}

	public function available( $cgcode = ""  , $wcode = "")
	{
		$arr['page'] = "course/available";
		$this->load->model('User_model', 'login', true);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', true);
		$this->load->model('Course_model', 'course', true);
		$this->load->model('Enroll_model', 'enroll', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Manage_model', 'manage', false);
    	$this->load->model('Coursegroup_model', 'coursegroup', true);

    	$this->coursegroup->loadDB();

		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			if($lang=="thai"){
				$arr['com_name'] = $user['com_name_th'];
			}else{
				$arr['com_name'] = $user['com_name_en'];
			}
			$arr['cgcode'] = $cgcode;
			$arr['wcode'] = $wcode;
			$arr['user'] = $user;
			$arr['loadmycos'] = '';
			$arr['company_select'] = $this->manage->getCompany($wcode);
			$this->load->helper(array('form', 'url'));
			if($user['Is_admin']=="0"){
				if($arr['cgcode']!=""){
					$arr['courses'] = $this->course->getAllCos($arr['cgcode'],'');
					$arr['courses_cg'] = $this->course->getcourse_group($arr['cgcode'],'');
				}else{
					$arr['courses'] = $this->course->getAllCos('','');
					$arr['courses_cg'] = $this->course->getcourse_group('','');
				}

				$arr['rechk_permission_cg'] = $this->coursegroup->rechk_permission_cos();
					foreach ($arr['courses'] as $key_cg => $value_cg) {
						if(!in_array($value_cg['id'], $arr['rechk_permission_cg'])){
							unset($arr['courses'][$key_cg]);
						}
					}
			}else{
				if($arr['cgcode']!=""){
					$arr['courses'] = $this->course->getAllCos($arr['cgcode'],'');
					$arr['courses_cg'] = $this->course->getcourse_group($arr['cgcode'],'');
				}else{
					$arr['courses'] = $this->course->getAllCos('','');
					$arr['courses_cg'] = $this->course->getcourse_group('','');
				}
			}
			//$arr['seats'] = $this->course->countSeatsAllCos();

			$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
            $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
			$arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
			if($arr['btn_view']!="1"){
				redirect(base_url().'dashboard') ;
			}
			//print_r($arr);
			//Record Log activity
			$this->lg->record('course', 'enter available course');

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/courseAvail', $arr );
		}
		$this->login->closeDB();
		$this->course->closeDB();
		$this->enroll->closeDB();
		$this->lg->closeDB();
		$this->foot->closeDB();
	}

	public function load_course_data(){
		$txt_search = isset($_REQUEST['value'])?$_REQUEST['value']:"";
		$cgcode = isset($_REQUEST['cgcode'])?$_REQUEST['cgcode']:"";
		$wcode = isset($_REQUEST['wcode'])?$_REQUEST['wcode']:"";
		$isNonEnroll = isset($_REQUEST['isNonEnroll'])?$_REQUEST['isNonEnroll']:"";

		$this->load->model('User_model', 'login', true);

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$this->load->model('User_model', 'login', true);
		$this->load->model('Course_model', 'course', true);
		$this->load->model('Enroll_model', 'enroll', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Manage_model', 'manage', false);
    	$this->load->model('Coursegroup_model', 'coursegroup', true);
		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
    	$user = $this->session->userdata('user');

    	$this->coursegroup->loadDB();
    	$emp_id = "";
    	if($isNonEnroll=="0"){
    		$emp_id = $user['emp_id'];
    	}
			$company_select = $this->manage->getCompany($wcode);
			$this->load->helper(array('form', 'url'));
			//if($user['Is_admin']=="0"){
				if($cgcode!=""){
					$courses = $this->course->getAllCos($cgcode,$emp_id,$txt_search);
					$courses_cg = $this->course->getcourse_group($cgcode,$txt_search);
				}else{
					$courses = $this->course->getAllCos('',$emp_id,$txt_search);
					$courses_cg = $this->course->getcourse_group('',$txt_search);
				}
				//print_r($courses);
				$rechk_permission_cg = $this->coursegroup->rechk_permission_cos();
					foreach ($courses as $key_cg => $value_cg) {
						if(!in_array($value_cg['id'], $rechk_permission_cg)){
							unset($courses[$key_cg]);
						}
					}
			/*}else{
				if($cgcode!=""){
					$courses = $this->course->getAllCos($cgcode,'',$txt_search);
					$courses_cg = $this->course->getcourse_group($cgcode,'');
				}else{
					$courses = $this->course->getAllCos('','',$txt_search);
					$courses_cg = $this->course->getcourse_group('','');
				}
			}*/
			$page = "course/available";

			$btn_add = $this->manage->chk_permission($page,'ru_add');
			$btn_update = $this->manage->chk_permission($page,'ru_edit');
            $btn_delete = $this->manage->chk_permission($page,'ru_del');
			$btn_view = $this->manage->chk_permission($page,'ru_view');
			$btn_print = $this->manage->chk_permission($page,'ru_print');

			$arrcg_id = array();
			foreach ($courses as $value_cos) {
				foreach ($value_cos['cg_code'] as $key_cg => $value_cg) {
					if(!in_array($value_cg,$arrcg_id)){
							array_push($arrcg_id, $value_cg);
					}
				}
			}
			foreach ($courses_cg as $key_cg => $value_cg) {
				if(!in_array($value_cg['id'],$arrcg_id)){
					unset($courses_cg[$key_cg]);
				}
			}
			  function isMobile() {
			      return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
			  }
			?>

            <?php if(countArray($courses_cg)>0){ ?>
            <div class="<?php if(isMobile()){ ?>col-12<?php }else{?>col-3<?php } ?> card card-body" id="div_menu">
				<?php if(isMobile()){ ?>
                    <button class="btn btn-outline-secondary float-left" id="onclose_divmenu_btn" type="button" onclick="onopen_divmenu()" title="Show Menu"><i style="font-size: 30px" class="mdi mdi-arrow-left-bold-hexagon-outline"></i></button>
                    <button id="x" class="btn btn-outline-danger" onclick="onclose_divmenu()"><i class="mdi mdi-close-box"></i></button>
                <?php } ?>
                <?php if(countArray($courses_cg)>0){ ?>
                    <div class="stickyside">
                        <label align="left"><?php echo label('Choosecoursegroup'); ?></label>
                        <div class="checkbox checkbox-success">
                            <input type="checkbox" id="chkcg_all" name="chkcg_all" onclick="oncheckboxall()" value="1" checked>
                            <label for="chkcg_all" style="font-size: 14px;"><?php echo label('r_company'); ?></label>
                        </div>
                        <?php foreach ($courses_cg as $key_cg => $value_cg) { ?>
                            <div class="checkbox checkbox-success">
                                <input type="checkbox" id="chkcg_<?php echo $value_cg['id']; ?>" onclick="oncheckbox('<?php echo $value_cg['id']; ?>')" name="chkcg[]" class="checkall" value="<?php echo $value_cg['id']; ?>" <?php if($cgcode==$value_cg['id']){echo "checked";}else{echo "checked";} ?>>
                                <label for="chkcg_<?php echo $value_cg['id'] ?>" style="font-size: 14px;">
                                <?php if($lang=="thai"){echo $value_cg['cgtitle_th'];}else{echo $value_cg['cgtitle_en'];} ?>
                            	</label>
                    		</div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php } ?>

            <?php if(countArray($courses_cg)>0){ ?>
            <div class="<?php if(isMobile()){ ?>col-12<?php }else{?>col-9<?php } ?>" id="div_data_detail">
                <div class="card">
                    <div class="card-body">
                    <?php foreach ($courses_cg as $key_cg => $value_cg) { ?>
                        <div class="card" id="div_cg_main<?php echo $value_cg['id']; ?>">
                            <div class="card-header" align="left"><?php if($lang=="thai"){echo $value_cg['cgtitle_th'];}else{echo $value_cg['cgtitle_en'];} ?></div>
                            <div class="card-body slimtest1 row" align="left">
                            <?php $num_count = 0;
                                foreach ($courses as $key_cos => $value_cos) {
                                    if(in_array($value_cg['id'], $value_cos['cg_code'])){ $num_count++; ?>
                                        <div class="col-md-4" style="cursor: pointer;">
                                            <div class="card" style="height: 400px" <?php if(countArray($value_cos['enroll'])>0){ ?> onclick="onclickdetail('<?php echo $value_cos['id']; ?>')" <?php } ?>>
                                                <div class="top-right" <?php if(intval($value_cos['count_cert'])==0){ ?> style="display: none;"<?php } ?>>
                                                    <span style="position: absolute; padding: 5px 10px; top: 0px; left: 50%; width: 50px; text-align: center; margin-left: -25px;font-size: 28px;color: #fff"><i class="mdi mdi-certificate"></i></span>
                                                    <i class="fas fa-bookmark" style="font-size: 50px;color: #e74c3c;"></i>
                                                </div>
                                                <img class="card-img-top img-responsive" style="height: 150px" src="<?php echo REAL_PATH;?>/uploads/course/<?php echo $value_cos['pic']; ?>" onerror="this.src='<?php echo REAL_PATH;?>/uploads/course/default_profile.jpg'" alt="">
                                                <div class="card-body slimtest1 flex-column">
                                                    <h4 class="card-title"><?php if($lang=="thai"){echo $value_cos['cname_th'];}else{echo $value_cos['cname_en'];} ?></h4>
                                                    <?php if((isset($value_cos['tc_name_th'])&&isset($value_cos['tc_name_en']))&&($value_cos['tc_name_th']!=""&&$value_cos['tc_name_en']!="")){ ?>
                                                        <span class="label label-info label-rounded" style="background-color: <?php echo $value_cos['tc_color']; ?>"><?php if($lang=="thai"){echo $value_cos['tc_name_th'];}else{echo $value_cos['tc_name_en'];} ?></span>
                                                    <?php } ?>
                                                    <?php if(countArray($value_cos['enroll'])==0){ ?>
                                                    <input type="hidden" id="point_redeem_hide<?php echo $value_cos['id']; ?>" name="point_redeem_hide<?php echo $value_cos['id']; ?>" value="<?php if(isset($value_cos['role_cos']['point_redeem'])){echo $value_cos['role_cos']['point_redeem'];} ?>">
                                                    <input type="hidden" id="enroll_seat_hide<?php echo $value_cos['id']; ?>" name="enroll_seat_hide<?php echo $value_cos['id']; ?>" value="<?php echo $value_cos['enroll_seat']; ?>">
                                                    <input type="hidden" id="seat_count_hide<?php echo $value_cos['id']; ?>" name="seat_count_hide<?php echo $value_cos['id']; ?>" value="<?php echo $value_cos['seat_count']; ?>">
                                                    <div class="mt-auto">
                                                        <button style="font-size: 14px;position: absolute;right:0;" id="<?php echo $value_cos['id']; ?>" class="btn btn-outline-info btn-flat m-b-20 btn_register"><i class="mdi mdi-file-document-box"></i> <?php echo label('register'); ?></button>
                                                    </div>
                                                    <?php }else{ ?>
                                                    <div class="mt-auto">
                                                        <label class="<?php if($value_cos['enroll'][0]['cosen_status_sub']=="0"){echo "text-warning";}else if($value_cos['enroll'][0]['cosen_status_sub']=="1"){echo "text-info";}else{echo "text-danger";} ?>"><?php echo label('status')." : "; ?>
                                                        <?php   if($value_cos['enroll'][0]['cosen_status_sub']=="0"){
                                                                    echo label('not_start');
                                                                }else if($value_cos['enroll'][0]['cosen_status_sub']=="1"){
                                                                    echo label('done');
                                                                }else if($value_cos['enroll'][0]['cosen_status_sub']=="2"){
                                                                    echo label('noProgress');
                                                                }else{
                                                                    if(empty($value_cos['enroll'])){
                                                                        echo label('r_notregister');
                                                                    }else{
                                                                        echo label('cancel');
                                                                    }
                                                                }
                                                                        ?>
                                                        </label>
                                                    </div>
                                                    <?php if(empty($value_cos['enroll'])){ ?>
                                                    <div class="mt-auto">
                                                        <button style="font-size: 14px;position: absolute;right:0;" id="<?php echo $value_cos['id']; ?>" class="btn btn-outline-info btn-flat m-b-5 btn_register"><i class="mdi mdi-file-document-box"></i> <?php echo label('register'); ?></button>
                                                    </div>
                                                    <?php }
                                                        } ?>
                                                </div>
                                                <div class="card-footer row">
                                                    <div class="col-md-12" align="left">
                                                        <i class="mdi mdi-account-multiple-outline"></i> <?php echo $value_cos['enroll_seat']; ?>/<?php if(intval($value_cos['seat_count'])>0){ echo $value_cos['seat_count']; }else{ ?><i class="mdi mdi-infinity"></i> <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                        } ?>
                                    <?php if($num_count==0){?>
                                            <h4 align="center"><i class="mdi mdi-exclamation"></i> <?php echo label('wg_datanotfound');?></h4>
                                    <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php }else{ ?>
            <div class="col-md-12 card">
                    <div class="card-body">
                        <h4 align="center"><i class="mdi mdi-exclamation"></i> <?php echo label('wg_datanotfound');?></h4>
                    </div>
            </div>
            <?php } ?>
			<?php 

	}

	public function nonenroll( $cgcode = ""  , $wcode = "")
	{
		$arr['page'] = "course/available";
		$this->load->model('User_model', 'login', true);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', true);
		$this->load->model('Course_model', 'course', true);
		$this->load->model('Enroll_model', 'enroll', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Manage_model', 'manage', false);
    	$this->load->model('Coursegroup_model', 'coursegroup', true);

    	$this->coursegroup->loadDB();

		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			if($lang=="thai"){
				$arr['com_name'] = $user['com_name_th'];
			}else{
				$arr['com_name'] = $user['com_name_en'];
			}
			$arr['cgcode'] = $cgcode;
			$arr['wcode'] = $wcode;
			$arr['user'] = $user;
			$arr['loadmycos'] = '';
			$arr['company_select'] = $this->manage->getCompany($wcode);
			$this->load->helper(array('form', 'url'));
			if($user['Is_admin']=="0"||($user['Is_admin']=="1"&&$user['ug_for']=="CUSTOMER")){
				if($arr['cgcode']!=""){
					$arr['courses'] = $this->course->getAllCos($arr['cgcode'],'nonenroll');
					$arr['courses_cg'] = $this->course->getcourse_group($arr['cgcode']);
				}else{
					$arr['courses'] = $this->course->getAllCos('','nonenroll');
					$arr['courses_cg'] = $this->course->getcourse_group('');
				}

				$arr['rechk_permission_cg'] = $this->coursegroup->rechk_permission_cg();
					foreach ($arr['courses_cg'] as $key_cg => $value_cg) {
						if(!in_array($value_cg['id'], $arr['rechk_permission_cg'])){
							unset($arr['courses_cg'][$key_cg]);
						}
					}
			}else{
				$arr['courses'] = array();
				$arr['courses_cg'] = array();
			}
			$arr['isNonEnroll'] = "1";
			//$arr['seats'] = $this->course->countSeatsAllCos();

			$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
            $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
			$arr['btn_print'] = $this->manage->chk_permission($arr['page'],'ru_print');
			if($arr['btn_view']!="1"){
				redirect(base_url().'dashboard') ;
			}
			//print_r($arr);
			//Record Log activity
			$this->lg->record('course', 'enter nonenroll course');

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/load_course', $arr );
		}
		$this->login->closeDB();
		$this->course->closeDB();
		$this->enroll->closeDB();
		$this->lg->closeDB();
		$this->foot->closeDB();
	}

	public function analytic_course($cos_id){
		$arr['page'] = "course/analytic_course";
		$this->load->model('User_model', 'login', true);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', true);
		$this->load->model('Course_model', 'course', true);
		$this->load->model('Enroll_model', 'enroll', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Manage_model', 'manage', false);

    	$this->load->model('Coursegroup_model', 'coursegroup', true);

    	$this->coursegroup->loadDB();
		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			if($lang=="thai"){
				$arr['com_name'] = $user['com_name_th'];
			}else{
				$arr['com_name'] = $user['com_name_en'];
			}
			$arr['user'] = $user;
			$arr['cos_id'] = $cos_id;

			$this->load->helper(array('form', 'url'));
			$arr['registered_seats'] = $this->course->count_seat_register($cos_id);
			$arr['course_regis'] = $this->course->course_regis($cos_id);
			$arr['month_select'] = $this->course->month_select($cos_id);
			if(countArray($arr['month_select'])==0){
				$arr['month_select'][0] = date('Y-m');
			}
			$arr['this_month'] = $this->course->count_seat_register_thismonth($cos_id,$arr['month_select'][0]);
			$arr['chart_total'] = $this->course->chart_total($cos_id);
			$arr['courses'] = $this->course->query_data_onupdate($cos_id, 'lms_cos','cos_id');
			$arr['coursescg'] = $this->course->query_data_onupdate_result($cos_id, 'lms_cosincg','lms_cosincg.course_id');

			if($user['Is_admin']=="0"){
				if($arr['cgcode']!=""){
					$arr['courses_cg'] = $this->course->getcourse_group($arr['cgcode'],$user['emp_id']);
				}else{
					$arr['courses_cg'] = $this->course->getcourse_group('');
				}
				$arr['rechk_permission_cg'] = $this->coursegroup->rechk_permission_cg();
					foreach ($arr['courses_cg'] as $key_cg => $value_cg) {
						if(!in_array($value_cg['id'], $arr['rechk_permission_cg'])){
							unset($arr['courses_cg'][$key_cg]);
						}
					}
			}else{
				$arr['courses_cg'] = array();
			}
			//Record Log activity
			$this->lg->record('course', 'enter analytic course'.$arr['courses']['cname_th']);

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/analytic_course', $arr );
		}
		$this->login->closeDB();
		$this->course->closeDB();
		$this->enroll->closeDB();
		$this->lg->closeDB();
		$this->foot->closeDB();
	}

	public function course_select_month(){
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : '';
		$month_select = isset($_REQUEST['month_select']) ? $_REQUEST['month_select'] : '';
		$result['this_month'] = $this->course->count_seat_register_thismonth($cos_id,$month_select);
      	echo json_encode($result);
      	exit();
	}

	public function detail($cos_id,$loadmycos=''){
		$arr['page'] = "course/detail";
		$this->load->model('User_model', 'login', true);
		!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', true);
		$this->load->model('Course_model', 'course', true);
		$this->load->model('Enroll_model', 'enroll', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Manage_model', 'manage', false);
    	$this->load->model('Coursegroup_model', 'coursegroup', true);
    	$this->load->model('Function_query_model', 'func_query', true);

    	$this->coursegroup->loadDB();
		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
		if($this->login->checkSession($arr['page'])){
			$user = $this->session->userdata('user');
			$arr['emp_c'] = $user['emp_c'];
			$arr['com_admin'] = $user['com_admin'];
			$arr['com_id'] = $user['com_id'];
			if($lang=="thai"){
				$arr['com_name'] = $user['com_name_th'];
			}else{
				$arr['com_name'] = $user['com_name_en'];
			}
			$arr['user'] = $user;
			$arr['cos_id'] = $cos_id;
			$arr['loadmycos'] = isset($loadmycos) ? $loadmycos : '';

			$arr['is_Enroll'] = $this->course->rechk_enroll($cos_id,$user['emp_id']);
			if(countArray($arr['is_Enroll'])==0){
				redirect( base_url()."course/loadCourse");
			}
			$arr['is_QuizBeforeClass'] = $this->course->IsQuizBeforeClass($cos_id,$user['emp_id']);
			if($arr['is_QuizBeforeClass']!="-"){
				redirect( base_url()."pretest/detail/".$arr['is_QuizBeforeClass']);
			}
			//echo $arr['is_QuizBeforeClass'];
			$this->load->helper(array('form', 'url'));
			$arr['rechk_endcos'] = $this->course->recheck_endcos($cos_id);
			$arr['courses'] = $this->course->query_data_onupdate($cos_id, 'lms_cos','cos_id');
			$arr['enroll_detail'] = $this->course->query_data_onupdate('','lms_cos_enroll','lms_cos_enroll.cos_id='.$cos_id.' and lms_cos_enroll.emp_id='.$user['emp_id']);
			$arr['qiz_challenge'] = $this->course->query_data_onupdate_result($cos_id, 'lms_cos_enroll','cos_id');
			$arr['courses_doc'] = $this->course->query_data_onupdate_result($cos_id, 'lms_cos_fil','cos_id');
			$arr['menu_lesson'] = $this->course->recheck_total_select('lms_les','cos_id',$cos_id,'status','les_id,les_name_th,les_name_en','lesson');
			$arr['menu_scorm'] = array();
			$arr['menu_scorm_quiz'] = $this->course->recheck_total_select('lms_les','cos_id',$cos_id,'status','les_id,les_name_th,les_name_en','scormquiz');
			$arr['survey_chk'] = $this->course->recheck_total('lms_survey','cos_id',$cos_id,'sv_status');
			$arr['qiz_chk'] = $this->course->recheck_total('lms_qiz','cos_id',$cos_id,'quiz_status');

			$arr['menu_qiz_pre'] = $this->course->recheck_total_select('lms_qiz','cos_id',$cos_id,'quiz_status','qiz_id,quiz_name_th,quiz_name_en,quiz_type,quiz_maxscore','','1');
			$arr['menu_qiz_post'] = $this->course->recheck_total_select('lms_qiz','cos_id',$cos_id,'quiz_status','qiz_id,quiz_name_th,quiz_name_en,quiz_type,quiz_maxscore','','2');
			$arr['menu_survey'] = $this->course->query_data_onupdate_result($cos_id, 'lms_survey','cos_id');
			$arr['courses_video'] = $this->course->query_data_onupdate_result($cos_id, 'lms_cos_video','cos_id');

			$arr['rechk_permission_cg'] = $this->coursegroup->rechk_permission_course_people();
			if(!in_array($cos_id, $arr['rechk_permission_cg'])){
				echo"<script language='JavaScript'>";
				echo"window.location='".base_url()."course/page/".$cos_id."/';";
				echo"</script>";
			}
			if(countArray($arr['menu_lesson'])>0){
				foreach ($arr['menu_lesson'] as $key_les => $value_les) {
					$arr['menu_lesson'][$key_les]['les_status'] = "";

					$this->db->where('les_id',$value_les['les_id']);
					$this->db->where('emp_id',$user['emp_id']);
					$this->db->from('lms_les_tc');
					$query_tc = $this->db->get();
					$num_tc = $query_tc->num_rows();
					if($num_tc>0){
						$fetch_tc = $query_tc->row_array();
						$arr['menu_lesson'][$key_les]['les_status'] = $fetch_tc['learn_status'];
					}
				}
			}
			if(countArray($arr['menu_qiz_pre'])>0){
				foreach ($arr['menu_qiz_pre'] as $key_qiz => $value_qiz) {
					$arr['menu_qiz_pre'][$key_qiz]['sum_score'] = "";
					$arr['menu_qiz_pre'][$key_qiz]['qiz_status'] = "00";
					$this->db->where('qiz_id',$value_qiz['qiz_id']);
					$this->db->where('emp_id',$user['emp_id']);
					$this->db->from('lms_qiz_tc');
					$query_tc = $this->db->get();
					$num_tc = $query_tc->num_rows();
					if($num_tc>0){
						$fetch_tc = $query_tc->row_array();

                            $this->db->from('lms_ques_tc');
                            $this->db->where('lms_ques_tc.qiz_id', $value_qiz['qiz_id']);
                            $this->db->where('lms_ques_tc.emp_id', $user['emp_id']);
                            $query_questc = $this->db->get();
                            $fetch_questc = $query_questc->result_array();
                            $score_total = 0;
                            foreach ($fetch_questc as $key => $value) {
                                $this->db->from('lms_ques');
                                $this->db->where('lms_ques.qiz_id', $value_qiz['qiz_id']);
                                $this->db->where('lms_ques.ques_id', $value['ques_id']);
                                $query_score_ques = $this->db->get();
                                $fetch_score_ques = $query_score_ques->row_array();
                                $score_total += floatval($fetch_score_ques['ques_score']);
                            }
                            if($score_total>0&&floatval($fetch_tc['sum_score'])>0){
                            	$sum_score = (floatval($fetch_tc['sum_score'])/$score_total)*100;
                            }else{
                            	$sum_score = 0;
                            }
						$arr['menu_qiz_pre'][$key_qiz]['sum_score'] = $sum_score;
						$arr['menu_qiz_pre'][$key_qiz]['qiz_status'] = $fetch_tc['qiz_status'];
					}
				}
			}
			if(countArray($arr['menu_qiz_post'])>0){
				foreach ($arr['menu_qiz_post'] as $key_qiz => $value_qiz) {
					$arr['menu_qiz_post'][$key_qiz]['sum_score'] = "";
					$arr['menu_qiz_post'][$key_qiz]['qiz_status'] = "00";
					$this->db->where('qiz_id',$value_qiz['qiz_id']);
					$this->db->where('emp_id',$user['emp_id']);
					$this->db->from('lms_qiz_tc');
					$query_tc = $this->db->get();
					$num_tc = $query_tc->num_rows();
					if($num_tc>0){
						$fetch_tc = $query_tc->row_array();

                            $this->db->from('lms_ques_tc');
                            $this->db->where('lms_ques_tc.qiz_id', $value_qiz['qiz_id']);
                            $this->db->where('lms_ques_tc.emp_id', $user['emp_id']);
                            $query_questc = $this->db->get();
                            $fetch_questc = $query_questc->result_array();
                            $score_total = 0;
                            foreach ($fetch_questc as $key => $value) {
                                $this->db->from('lms_ques');
                                $this->db->where('lms_ques.qiz_id', $value_qiz['qiz_id']);
                                $this->db->where('lms_ques.ques_id', $value['ques_id']);
                                $query_score_ques = $this->db->get();
                                $fetch_score_ques = $query_score_ques->row_array();
                                $score_total += floatval($fetch_score_ques['ques_score']);
                            }
                            if($score_total>0&&floatval($fetch_tc['sum_score'])>0){
                            	$sum_score = (floatval($fetch_tc['sum_score'])/$score_total)*100;
                            }else{
                            	$sum_score = 0;
                            }
						$arr['menu_qiz_post'][$key_qiz]['sum_score'] = $sum_score;
						$arr['menu_qiz_post'][$key_qiz]['qiz_status'] = $fetch_tc['qiz_status'];
					}
				}
			}
			/*$arr['les_chk'] = $this->course->recheck_total('lms_les','cos_id',$cos_id,'status');
			$arr['les_status'] = $this->course->recheck_status('lesson',$cos_id);
			$arr['qiz_status'] = $this->course->recheck_status('qiz',$cos_id);
			$arr['survey_chk'] = $this->course->recheck_total('lms_survey','cos_id',$cos_id,'sv_status');
			$arr['survey_status'] = $this->course->recheck_status('survey',$cos_id);*/
			$arr['cert_chk'] = $this->course->recheck_total('lms_bad','courses_id',$cos_id,'');
			//$arr['seats'] = $this->course->countSeatsAllCos();
			if($arr['enroll_detail']['cosen_firsttime']!="0000-00-00 00:00:00"){

                $count_qiz = $this->func_query->numrows('lms_qiz','','','','cos_id="'.$cos_id.'" and quiz_status = "1"');
                $count_qiz_tc = $this->func_query->numrows('lms_qiz_tc','lms_qiz','lms_qiz.qiz_id = lms_qiz_tc.qiz_id','','lms_qiz.cos_id="'.$cos_id.'" and lms_qiz_tc.emp_id = "'.$user['emp_id'].'" and lms_qiz_tc.qiz_status="3"');
                $count_les = $this->func_query->numrows('lms_les','','','','cos_id="'.$cos_id.'" and status = "1"');
                $count_les_tc = $this->func_query->numrows('lms_les_tc','lms_les','lms_les.les_id = lms_les_tc.les_id','','lms_les.cos_id="'.$cos_id.'" and lms_les_tc.emp_id = "'.$user['emp_id'].'" and lms_les_tc.learn_status = "2"');
                if(($count_qiz==$count_qiz_tc)&&($count_les==$count_les_tc)){
                  $this->course->end_course($cos_id);
                }
				
			}

			//Record Log activity
			$this->lg->record('course', 'enter course : '.$arr['courses']['cname_th']);

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/courseDetail', $arr );
		}else{
			redirect(base_url().'dashboard') ;
		}
		$this->login->closeDB();
		$this->course->closeDB();
		$this->enroll->closeDB();
		$this->lg->closeDB();
		$this->foot->closeDB();
	}

	public function page($cos_id,$loadmycos=''){
		$arr['page'] = "course/page";

		$this->load->model('User_model', 'login', true);
		//!$this->login->checkSession($arr['page']) ? : $arr['page'];

		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);


		$arr['lang'] = $lang;
		$this->load->model('User_model', 'login', true);
		$this->load->model('Course_model', 'course', true);
		$this->load->model('Enroll_model', 'enroll', true);
		$this->load->model('Log_model', 'lg', false);
		$this->load->model('Footer_model', 'foot', false);
		$this->load->model('Manage_model', 'manage', false);

		$this->manage->loadDB();
		$this->login->loadDB();
		$this->course->loadDB();
		$this->enroll->loadDB();
		$this->lg->loadDB();
		$this->foot->loadDB();
        $arr['arr_permission'] = $this->manage->chk_permission_page();
			$user = $this->session->userdata('user');
			if(!empty($user)){
				$arr['emp_c'] = $user['emp_c'];
				$arr['com_admin'] = $user['com_admin'];
				$arr['com_id'] = $user['com_id'];
				if($lang=="thai"){
					$arr['com_name'] = $user['com_name_th'];
				}else{
					$arr['com_name'] = $user['com_name_en'];
				}
				$arr['user'] = $user;
			}
        //$arr['arr_permission'] = $this->manage->chk_permission_page();

			$arr['cos_id'] = $cos_id;
			$arr['loadmycos'] = isset($loadmycos) ? $loadmycos : '';

			$this->load->helper(array('form', 'url'));
			$arr['courses'] = $this->course->query_data_onupdate($cos_id, 'lms_cos','cos_id');
			$arr['qiz_challenge'] = $this->course->query_data_onupdate_result($cos_id, 'lms_cos_enroll','cos_id');
			$arr['courses_doc'] = $this->course->query_data_onupdate_result($cos_id, 'lms_cos_fil','cos_id');
			$arr['courses_video'] = $this->course->query_data_onupdate_result($cos_id, 'lms_cos_video','cos_id');

			//Record Log activity
			$this->lg->record('course', 'enter page course from home page : '.$arr['courses']['cname_th']);

			$arr['foote'] = $this->foot->getfooter();
			$this->load->view('frontend/course_page', $arr );

		$this->login->closeDB();
		$this->course->closeDB();
		$this->enroll->closeDB();
		$this->lg->closeDB();
		$this->foot->closeDB();
	}

  	public function insert_question(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();

		if(countArray($_REQUEST)>0){
			$qiz_id_question = isset($_REQUEST['qiz_id_question']) ? $_REQUEST['qiz_id_question'] : '';
			$ques_type = isset($_REQUEST['ques_type']) ? $_REQUEST['ques_type'] : '';
			$ques_name_th = isset($_REQUEST['ques_name_th']) ? $_REQUEST['ques_name_th'] : '';
			$ques_name_en = isset($_REQUEST['ques_name_en']) ? $_REQUEST['ques_name_en'] : '';
			$ques_info_th = isset($_REQUEST['ques_info_th']) ? $_REQUEST['ques_info_th'] : '';
			$ques_info_en = isset($_REQUEST['ques_info_en']) ? $_REQUEST['ques_info_en'] : '';
			$ques_score = isset($_REQUEST['ques_score']) ? $_REQUEST['ques_score'] : '';
			$ques_show = isset($_REQUEST['ques_show']) ? $_REQUEST['ques_show'] : '';
			$data = array(
				'qiz_id' => $qiz_id_question,
				'ques_type' => $ques_type,
				'ques_name_th' => $ques_name_th,
				'ques_name_en' => $ques_name_en,
				'ques_info_th' => $ques_info_th,
				'ques_info_en' => $ques_info_en,
				'time_mod' => date('Y-m-d H:i'),
				'ques_score' => $ques_score,
				'ques_show' => $ques_show
			);
			$mul_answer = "";
			$num_arr = 0;
			if(isset($_REQUEST['mul_answer'])&&countArray($_REQUEST['mul_answer'])>0){
				foreach ($_REQUEST['mul_answer'] as $key) {
					$mul_answer .= $key;
					$num_arr++;
					if($num_arr<countArray($_REQUEST['mul_answer'])){
						$mul_answer .= ",";
					}
				}
			}

			$mul_c1_th = isset($_REQUEST['mul_c1_th']) ? $_REQUEST['mul_c1_th'] : '';
			$mul_c2_th = isset($_REQUEST['mul_c2_th']) ? $_REQUEST['mul_c2_th'] : '';
			$mul_c3_th = isset($_REQUEST['mul_c3_th']) ? $_REQUEST['mul_c3_th'] : '';
			$mul_c4_th = isset($_REQUEST['mul_c4_th']) ? $_REQUEST['mul_c4_th'] : '';
			$mul_c5_th = isset($_REQUEST['mul_c5_th']) ? $_REQUEST['mul_c5_th'] : '';
			$mul_c1_en = isset($_REQUEST['mul_c1_en']) ? $_REQUEST['mul_c1_en'] : '';
			$mul_c2_en = isset($_REQUEST['mul_c2_en']) ? $_REQUEST['mul_c2_en'] : '';
			$mul_c3_en = isset($_REQUEST['mul_c3_en']) ? $_REQUEST['mul_c3_en'] : '';
			$mul_c4_en = isset($_REQUEST['mul_c4_en']) ? $_REQUEST['mul_c4_en'] : '';
			$mul_c5_en = isset($_REQUEST['mul_c5_en']) ? $_REQUEST['mul_c5_en'] : '';
			$data_detail = array(
				'mul_c1_th' => $mul_c1_th,
				'mul_c2_th' => $mul_c2_th,
				'mul_c3_th' => $mul_c3_th,
				'mul_c4_th' => $mul_c4_th,
				'mul_c5_th' => $mul_c5_th,
				'mul_c1_en' => $mul_c1_en,
				'mul_c2_en' => $mul_c2_en,
				'mul_c3_en' => $mul_c3_en,
				'mul_c4_en' => $mul_c4_en,
				'mul_c5_en' => $mul_c5_en,
				'mul_answer' => $mul_answer
			);

			if($_REQUEST['operation_question']=="Add"){
				$data['time_create'] = date('Y-m-d H:i');
				$id = $this->course->create_question($data);
				if($_REQUEST['ques_type']=="multi"){
					$data_detail['ques_id'] = $id;
					$this->course->create_question_multi($data_detail);
				}
				$this->lg->record('qiz', 'Create Question : '.$ques_name_th);
			}else{
				$this->course->update_question($data,$_REQUEST['ques_id']);
				if($_REQUEST['ques_type']=="multi"){
					$data_detail['ques_id'] = $_REQUEST['ques_id'];
					$this->course->create_question_multi($data_detail);
				}
				$this->lg->record('qiz', 'Update Question : '.$ques_name_th);
			}
			//$this->course->cal_score_quiz($_REQUEST['qiz_id_question']);
			$msg = "2";
		}
		echo $msg;
	}

	public function update_rating(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();

		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : '';
		$value_rating = isset($_REQUEST['value_rating']) ? $_REQUEST['value_rating'] : '';
			$data = array('cosen_rating'=>$value_rating);
			$this->db->where('cos_id',$cos_id);
			$this->db->where('emp_id',$user['emp_id']);
			$this->db->update('lms_cos_enroll',$data);
		$arr = array();
		for ($i=1; $i <=5 ; $i++) {
			$this->db->from('lms_cos_enroll');
			$this->db->where('cos_id',$cos_id);
			$this->db->where('cosen_rating',$i);
			$query = $this->db->get();
			$num = $query->num_rows();
			$arr[$i]=$num;
		}
		$max = 0;
		$n = 0;
		foreach ($arr as $rate => $count) {
		    echo 'Seen ', $count, ' ratings of ', $rate, "\n";
		    $max += $rate * $count;
		    $n += $count;
		}
		$average = $max / $n;
		$update_cos = array('cos_rating' => $average);
		$this->db->where('id',$cos_id);
		$this->db->update('lms_cos',$update_cos);
	}


  	public function register_course(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		$msg = "";
		$cos_id = $this->input->post('cos_id');
		$status = $this->input->post('status');
		$msg = $this->course->register_course($cos_id,$status);
		echo $msg;
	}

  	public function insert_emptocourse(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		$msg = "";
		$emp_c = $this->input->post('emp_c');
		$cos_id = $this->input->post('cos_id');
			$data = array(
				'emp_c' => $emp_c,
				'cos_id' => $cos_id
			);
			$msg = $this->course->create_emptocourse($data);
		echo $msg;
	}

  	public function update_score(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		$msg = "";
		$cosen_id = $this->input->post('cosen_id');
		$cosen_score = $this->input->post('cosen_score');
			$data = array(
				'cosen_score' => $cosen_score
			);
			$msg = $this->course->update_score($data,$cosen_id);
		echo $msg;
	}

  	public function update_les_log(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Scorm_model', 'scorm', false);
		$this->scorm->loadDB();
		$msg = "";
		$msg = $this->scorm->updateTC_cos($_REQUEST['id'],'completed');
		echo $msg;
	}

  	public function update_score_qiz(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		$msg = "";
		$id = $this->input->post('id');
		$sum_score = $this->input->post('sum_score');
			$data = array(
				'sum_score' => $sum_score
			);
			$msg = $this->course->update_score_qiz($data,$id);
		echo $msg;
	}

  	public function insert_update_scoreall(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		$msg = "";
		$cosen_score_all = $this->input->post('cosen_score_all');
		$cos_id = $this->input->post('cos_id');
			$msg = $this->course->update_scoreall($cosen_score_all,$cos_id);

            $this->load->model('Log_model', 'lg', false);
            $this->lg->loadDB();
            $courses = $this->course->query_data_onupdate($cos_id, 'lms_cos','cos_id');
            $this->lg->record('course', 'Update Score all employee in course '.$courses['cname_th']);
		echo '2';
	}

  	public function insert_update_scoreall_quiz(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		$msg = "";
		$qiz_score_all = $this->input->post('qiz_score_all');
		$qiz_id = $this->input->post('qiz_id');
			$msg = $this->course->update_scoreall_qiz($qiz_score_all,$qiz_id);
		echo '2';
	}

  	public function insert_quiz(){
  		date_default_timezone_set("Asia/Bangkok");
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$course_id_quiz = isset($_REQUEST['course_id_quiz']) ? $_REQUEST['course_id_quiz'] : '';
			$quiz_name_th = isset($_REQUEST['quiz_name_th']) ? $_REQUEST['quiz_name_th'] : '';
			$quiz_info_th = isset($_REQUEST['quiz_info_th']) ? $_REQUEST['quiz_info_th'] : '';
			$quiz_name_en = isset($_REQUEST['quiz_name_en']) ? $_REQUEST['quiz_name_en'] : '';
			$quiz_info_en = isset($_REQUEST['quiz_info_en']) ? $_REQUEST['quiz_info_en'] : '';
			$quiz_random = isset($_REQUEST['quiz_random']) ? $_REQUEST['quiz_random'] : '';
			$quiz_show = isset($_REQUEST['quiz_show']) ? $_REQUEST['quiz_show'] : '';
			$quiz_grade = isset($_REQUEST['quiz_grade']) ? $_REQUEST['quiz_grade'] : '';
			$quiz_type = isset($_REQUEST['quiz_type']) ? $_REQUEST['quiz_type'] : '';
			$quiz_answer = isset($_REQUEST['quiz_answer']) ? $_REQUEST['quiz_answer'] : '';
			$quiz_limit = isset($_REQUEST['quiz_limit']) ? $_REQUEST['quiz_limit'] : '';
			$quiz_limitval = isset($_REQUEST['quiz_limitval']) ? $_REQUEST['quiz_limitval'] : '';
			$quiz_maxscore = isset($_REQUEST['quiz_maxscore']) ? $_REQUEST['quiz_maxscore'] : '';
			$period_open_var = isset($_REQUEST['period_open_var']) ? $_REQUEST['period_open_var'] : '';
			$period_end_var = isset($_REQUEST['period_end_var']) ? $_REQUEST['period_end_var'] : '';
			$data = array(
				'cos_id' => $course_id_quiz,
				'quiz_name_th' => $quiz_name_th,
				'quiz_info_th' => $quiz_info_th,
				'quiz_name_en' => $quiz_name_en,
				'quiz_info_en' => $quiz_info_en,
				'time_mod' => date('Y-m-d H:i'),
				'quiz_random' => $quiz_random,
				'quiz_show' => $quiz_show,
				'quiz_grade' => $quiz_grade,
				'quiz_type' => $quiz_type,
				'quiz_answer' => $quiz_answer,
				'quiz_limit' => $quiz_limit,
				'quiz_limitval' => $quiz_limitval,
				'quiz_maxscore' => $quiz_maxscore,
				'period_open' => $period_open_var,
				'period_end' => $period_end_var
			);
			if($_REQUEST['operation_quiz']=="Add"){
				$data['emp_id'] = $user['emp_id'];
				$data['time_create'] = date('Y-m-d H:i');
				$id = $this->course->create_quiz($data);
				if($_REQUEST['qize_id']!=""){
					$this->db->from('lms_quese');
		            $this->db->where('qize_id', $_REQUEST['qize_id']);
		            $this->db->where('quese_status', '1');
		            $query_quese = $this->db->get();
		            $fetch_quese = $query_quese->result_array();
		            if(countArray($fetch_quese)>0){
		            	foreach ($fetch_quese as $key_quese => $value_quese) {
		                    $data_insert_qiz = array(
		                        'qiz_id' => $id,
		                        'ques_type' => $value_quese['quese_type'],
		                        'ques_name_th' => $value_quese['quese_name_th'],
		                        'ques_name_en' => $value_quese['quese_name_en'],
		                        'ques_info_th' => $value_quese['quese_info_th'],
		                        'ques_info_en' => $value_quese['quese_info_en'],
		                        'ques_score' => $value_quese['quese_score'],
		                        'ques_show' => $value_quese['quese_show'],
		                        'time_create' => date('Y-m-d H:i'),
		                        'time_mod' => date('Y-m-d H:i')
		                    );
							$ques_id = $this->course->create_question($data_insert_qiz);
		                    if($value_quese['quese_type']=="multi"){
								$this->db->from('lms_quese_mul');
					            $this->db->where('quese_id', $value_quese['quese_id']);
					            $query_quese_mul = $this->db->get();
					            $fetch_quese_mul = $query_quese_mul->row_array();
					            if(isset($fetch_quese_mul)){
				                    $data_insert_quese_mul = array(
				                        'ques_id' => $ques_id,
				                        'mul_c1_th' => $fetch_quese_mul['mule_c1_th'],
				                        'mul_c2_th' => $fetch_quese_mul['mule_c2_th'],
				                        'mul_c3_th' => $fetch_quese_mul['mule_c3_th'],
				                        'mul_c4_th' => $fetch_quese_mul['mule_c4_th'],
				                        'mul_c5_th' => $fetch_quese_mul['mule_c5_th'],
				                        'mul_answer' => $fetch_quese_mul['mule_answer'],
				                        'mul_c1_en' => $fetch_quese_mul['mule_c1_en'],
				                        'mul_c2_en' => $fetch_quese_mul['mule_c2_en'],
				                        'mul_c3_en' => $fetch_quese_mul['mule_c3_en'],
				                        'mul_c4_en' => $fetch_quese_mul['mule_c4_en'],
				                        'mul_c5_en' => $fetch_quese_mul['mule_c5_en']
				                    );
									$this->course->create_question_multi($data_insert_quese_mul);
					            }
		                    }
		            	}
		            }
				}
					  $this->db->from('lms_cos_enroll');
                      $this->db->where('cos_id', $_REQUEST['course_id_quiz']);
                      $query_rechk = $this->db->get();
                      $row_chk = $query_rechk->result_array();
                      if(countArray($row_chk)>0){
                      	  foreach ($row_chk as $key_rechk => $value_rechk) {
		                      $this->db->from('lms_qiz');
		                      $this->db->where('cos_id', $_REQUEST['course_id_quiz']);
		                      $this->db->where('quiz_status', '1');
		                      $query_chk = $this->db->get();
		                      $fetch_chk = $query_chk->result_array();
		                      if(countArray($fetch_chk)>0){
		                        foreach ($fetch_chk as $key_chk => $value_chk) {

		                          $this->db->from('lms_qiz_tc');
		                          $this->db->where('qiz_id', $value_chk['qiz_id']);
		                          $this->db->where('emp_id', $value_rechk['emp_id']);
		                          $query_check = $this->db->get();
		                          $fetch_check = $query_check->result_array();
		                          if(countArray($fetch_check)==0){
		                            $data_insert_qiz = array(
		                              'qiz_id' => $value_chk['qiz_id'],
		                              'emp_id' => $value_rechk['emp_id'],
		                              'time_mod' => date('Y-m-d H:i')
		                            );
		                            $this->db->insert('lms_qiz_tc', $data_insert_qiz);
		                          }
		                        }
		                      }
		                  }
	                  }

	            $this->load->model('Log_model', 'lg', false);
	            $this->lg->loadDB();
	            $courses = $this->course->query_data_onupdate($_REQUEST['course_id_quiz'], 'lms_cos','cos_id');
	            $this->lg->record('course', 'Create Quiz : '.$quiz_name_th.' in course '.$courses['cname_th'].'');
			}else{
				$data['quiz_numofshown'] = $_REQUEST['quiz_numofshown'];
				$this->course->update_quiz($data,$_REQUEST['qiz_id']);
				//$this->course->cal_score_quiz($_REQUEST['qiz_id']);
	            $this->load->model('Log_model', 'lg', false);
	            $this->lg->loadDB();
	            $courses = $this->course->query_data_onupdate($_REQUEST['course_id_quiz'], 'lms_cos','cos_id');
	            $this->lg->record('course', 'Update Quiz : '.$quiz_name_th.' in course '.$courses['cname_th'].'');
			}
			$msg = "2";
		}
		echo $msg;
	}

  	public function insert_survey_detail(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Function_query_model', 'func_query', false);
	    $this->load->model('Log_model', 'lg', false);
	    $this->lg->loadDB();
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){

			$sv_id_detail = isset($_REQUEST['sv_id_detail']) ? $_REQUEST['sv_id_detail'] : '';
			$svde_heading_th = isset($_REQUEST['svde_heading_th']) ? $_REQUEST['svde_heading_th'] : '';
			$svde_heading_en = isset($_REQUEST['svde_heading_en']) ? $_REQUEST['svde_heading_en'] : '';
			$svde_detail_th = isset($_REQUEST['svde_detail_th']) ? $_REQUEST['svde_detail_th'] : '';
			$svde_detail_en = isset($_REQUEST['svde_detail_en']) ? $_REQUEST['svde_detail_en'] : '';
			$data = array(
				'sv_id' => $sv_id_detail,
				'svde_heading_th' => $svde_heading_th,
				'svde_heading_en' => $svde_heading_en,
				'svde_detail_th' => $svde_detail_th,
				'svde_detail_en' => $svde_detail_en
			);
	        $sv_data = $this->func_query->query_row('lms_survey','','','','sv_id = "'.$sv_id_detail.'"');
	        $courses = $this->course->query_data_onupdate($sv_data['cos_id'], 'lms_cos','cos_id');
			if($_REQUEST['operation_survey_detail']=="Add"){
	            $this->lg->record('course', 'Create question Survey : '.$sv_data['sv_title_th'].' in course '.$courses['cname_th'].'');
				$id = $this->course->create_survey_detail($data);
			}else{
				$this->course->update_survey_detail($data,$_REQUEST['svde_id']);
	            $this->lg->record('course', 'Update question Survey : '.$sv_data['sv_title_th'].' in course '.$courses['cname_th'].'');
			}
			$msg = "2";
		}
		echo $msg;
	}

  	public function enroll_cancel(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->load->model('Log_model', 'lg', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$data = array(
				'cosen_cancelnote' => $_REQUEST['cosen_cancelnote'],
				'cosen_status' => '2',
				'cosen_isDelete' => '1',
				'cosen_modifiedby' => $user['u_id'],
				'cosen_modifieddate' => date('Y-m-d H:i'),
			);
			$this->db->where('cosen_id',$_REQUEST['cosen_id_enroll']);
			$this->db->update('lms_cos_enroll',$data);
			$fetch_enroll = $this->func_query->query_row('lms_cos_enroll','lms_cos','lms_cos_enroll.cos_id = lms_cos.cos_id','','cosen_id="'.$_REQUEST['cosen_id_enroll'].'"');
			if(isset($fetch_enroll)){
				$this->db->where('cosen_id',$_REQUEST['cosen_id_enroll']);
				$this->db->delete('lms_les_tc');
				$this->db->where('cosen_id',$_REQUEST['cosen_id_enroll']);
				$this->db->delete('lms_med_tc');
				$this->db->where('cosen_id',$_REQUEST['cosen_id_enroll']);
				$this->db->delete('lms_fil_log');
				$this->db->where('cosen_id',$_REQUEST['cosen_id_enroll']);
				$this->db->delete('lms_qiz_tc');
				$this->db->where('cosen_id',$_REQUEST['cosen_id_enroll']);
				$this->db->delete('lms_ques_tc');
				$this->db->where('cosen_id',$_REQUEST['cosen_id_enroll']);
				$this->db->delete('lms_qn_user');
                $fetch_user = $this->func_query->query_row('lms_emp','lms_usp','lms_usp.emp_id = lms_emp.emp_id','','lms_usp.emp_id="'.$fetch_enroll['emp_id'].'" and lms_usp.u_isDelete="0" and lms_emp.emp_isDelete="0"');
                $this->lg->record('Course', 'Remove user from a course : '.$fetch_enroll['cname_th'].'(Name: '.$fetch_user['fullname_th'].')');
			}
			//$this->course->update_score($data,$_REQUEST['cosen_id_enroll']);
			$msg = "2";
		}
		echo $msg;
	}

  	public function insert_survey(){
  		date_default_timezone_set("Asia/Bangkok");
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$qn_id = "";
			if($_REQUEST['qn_id']!="000"){
				$qn_id = $_REQUEST['qn_id'];
			}
			$course_id_survey = isset($_REQUEST['course_id_survey']) ? $_REQUEST['course_id_survey'] : '';
			$sv_title_th = isset($_REQUEST['sv_title_th']) ? $_REQUEST['sv_title_th'] : '';
			$sv_title_en = isset($_REQUEST['sv_title_en']) ? $_REQUEST['sv_title_en'] : '';
			$sv_explanation_th = isset($_REQUEST['sv_explanation_th']) ? $_REQUEST['sv_explanation_th'] : '';
			$sv_explanation_en = isset($_REQUEST['sv_explanation_en']) ? $_REQUEST['sv_explanation_en'] : '';
			$sv_suggestion_status = isset($_REQUEST['sv_suggestion_status']) ? $_REQUEST['sv_suggestion_status'] : '';
			$survey_open_var = isset($_REQUEST['survey_open_var']) ? $_REQUEST['survey_open_var'] : '';
			$survey_end_var = isset($_REQUEST['survey_end_var']) ? $_REQUEST['survey_end_var'] : '';
			$data = array(
				'cos_id' => $course_id_survey,
				'sv_title_th' => $sv_title_th,
				'sv_title_en' => $sv_title_en,
				'sv_explanation_th' => $sv_explanation_th,
				'sv_explanation_en' => $sv_explanation_en,
				'time_mod' => date('Y-m-d H:i'),
				'sv_suggestion_status' => $sv_suggestion_status,
				'survey_open' => $survey_open_var,
				'survey_end' => $survey_end_var
			);
			if($_REQUEST['operation_survey']=="Add"){
				$data['time_create'] = date('Y-m-d H:i');
				$data['qn_id'] = $qn_id;
				$id = $this->course->create_survey($data);
				if($_REQUEST['qn_id']!="000"){
					$qn_id = $_REQUEST['qn_id'];
					$fetch_data = $this->query_data('lms_questionnaire_de','qn_id',$qn_id);
					foreach ($fetch_data as $key_qn => $value_qn) {
						$data_qn = array(
							'sv_id' => $id,
							'svde_heading_th' => $value_qn['qnde_heading_th'],
							'svde_detail_th' => $value_qn['qnde_detail_th'],
							'svde_heading_en' => $value_qn['qnde_heading_en'],
							'svde_detail_en' => $value_qn['qnde_detail_en']
						);
						$this->course->create_survey_detail($data_qn);
					}
				}
	            $this->load->model('Log_model', 'lg', false);
	            $this->lg->loadDB();
	            $courses = $this->course->query_data_onupdate($_REQUEST['course_id_survey'], 'lms_cos','cos_id');
	            $this->lg->record('course', 'Create Survey : '.$sv_title_th.' in course '.$courses['cname_th'].'');
			}else{
				$this->course->update_survey($data,$_REQUEST['sv_id']);
	            $courses = $this->course->query_data_onupdate($_REQUEST['course_id_survey'], 'lms_cos','cos_id');
	            $this->lg->record('course', 'Update Survey : '.$sv_title_th.' in course '.$courses['cname_th'].'');
			}
			$msg = "2";
		}
		echo $msg;
	}

  	public function insert_course(){
  		date_default_timezone_set("Asia/Bangkok");
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
	    $this->load->model('Log_model', 'lg', false);
	    $this->lg->loadDB();
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			if(isset($_FILES['image'])&&$_FILES['image']!=""){
				if( isset( $_FILES['image']) ){
					$imageSourcePath = $_FILES['image']['tmp_name'];
					$imageTargetPath = ROOT_DIR."uploads/course/".$_REQUEST['ccode']."_".$_REQUEST['com_id']."_".date('YmdHis').".jpg";
					if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
						$_REQUEST['pic'] = $_REQUEST['ccode']."_".$_REQUEST['com_id']."_".date('YmdHis').".jpg" ;
					}else{
						$_REQUEST['pic'] = $_REQUEST['image_ori'];
					}
				}else{
					$_REQUEST['pic'] = $_REQUEST['image_ori'];
				}
			}else{
				$_REQUEST['pic'] = $_REQUEST['image_ori'];
			}

			if(isset($_FILES['badges_img'])&&$_FILES['badges_img']!=""){
				if( isset( $_FILES['badges_img']) ){
					$imageSourcePath = $_FILES['badges_img']['tmp_name'];
					$imageTargetPath = ROOT_DIR."uploads/badges/"."badges_".$_REQUEST['ccode']."_".date('YmdHis').".jpg";
					if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
						$_REQUEST['badges_img'] = "badges_".$_REQUEST['ccode']."_".date('YmdHis').".jpg" ;
					}else{
						$_REQUEST['badges_img'] = $_REQUEST['badges_img_ori'];
					}
				}else{
					$_REQUEST['badges_img'] = $_REQUEST['badges_img_ori'];
				}
			}else{
				$_REQUEST['badges_img'] = $_REQUEST['badges_img_ori'];
			}
			$ccode = isset($_REQUEST['ccode']) ? $_REQUEST['ccode'] : '';
			$wg_id = isset($_REQUEST['wg_id']) ? $_REQUEST['wg_id'] : '';
			$tc_id = isset($_REQUEST['tc_id']) ? $_REQUEST['tc_id'] : '';
			$com_id = isset($_REQUEST['com_id']) ? $_REQUEST['com_id'] : '';
			$cname_th = isset($_REQUEST['cname_th']) ? $_REQUEST['cname_th'] : '';
			$cdesc_th = isset($_REQUEST['cdesc_th']) ? $_REQUEST['cdesc_th'] : '';
			$sub_description_th = isset($_REQUEST['sub_description_th']) ? $_REQUEST['sub_description_th'] : '';
			$cname_en = isset($_REQUEST['cname_en']) ? $_REQUEST['cname_en'] : '';
			$cdesc_en = isset($_REQUEST['cdesc_en']) ? $_REQUEST['cdesc_en'] : '';
			$sub_description_en = isset($_REQUEST['sub_description_en']) ? $_REQUEST['sub_description_en'] : '';
			$pic = isset($_REQUEST['pic']) ? $_REQUEST['pic'] : '';
			$goal_score = isset($_REQUEST['goal_score']) ? $_REQUEST['goal_score'] : '';
			$seat_count = isset($_REQUEST['seat_count']) ? $_REQUEST['seat_count'] : '';
			$condition = isset($_REQUEST['condition']) ? $_REQUEST['condition'] : '';
			$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
			$hour = isset($_REQUEST['hour']) ? $_REQUEST['hour'] : '';
			//$cg_id = $this->input->post('cg_id');
			$badges_name = isset($_REQUEST['badges_name']) ? $_REQUEST['badges_name'] : '';
			$badges_condition = isset($_REQUEST['badges_condition']) ? $_REQUEST['badges_condition'] : '';
			$badges_desc = isset($_REQUEST['badges_desc']) ? $_REQUEST['badges_desc'] : '';
			$badges_img = isset($_REQUEST['badges_img']) ? $_REQUEST['badges_img'] : '';
			$mina = isset($_REQUEST['mina']) ? $_REQUEST['mina'] : '';
			$minb = isset($_REQUEST['minb']) ? $_REQUEST['minb'] : '';
			$minc = isset($_REQUEST['minc']) ? $_REQUEST['minc'] : '';
			$mind = isset($_REQUEST['mind']) ? $_REQUEST['mind'] : '';
			$cos_public = isset($_REQUEST['chk_cos_public']) ? $_REQUEST['chk_cos_public'] : '0';
			$data = array(
				'ccode' => $ccode,
				'wg_id' => $wg_id,
				'tc_id' => $tc_id,
				'com_id' => $com_id,
				'cname_th' => $cname_th,
				'cdesc_th' => $cdesc_th,
				'sub_description_th' => $sub_description_th,
				'cname_en' => $cname_en,
				'cdesc_en' => $cdesc_en,
				'sub_description_en' => $sub_description_en,
				'goal_score' => $goal_score,
				'seat_count' => $seat_count,
				'condition' => $condition,
				'status' => $status,
				'hour' => $hour,
				'cos_public' => $cos_public,
				'time_mod' => date("Y-m-d H:i")
			);
			$data_badges = array(
				'badges_name' => $badges_name,
				'badges_condition' => $badges_condition,
				'badges_desc' => $badges_desc,
				'badges_img' => $badges_img,
				'time_create' => date('Y-m-d H:i')
			);
			$data_grade = array(
				'mina' => $mina,
				'minb' => $minb,
				'minc' => $minc,
				'mind' => $mind
			);
			if($pic!=""){
				$data['pic'] = $pic;
			}
			if($_REQUEST['operation']=="Add"){
				$data['time_create'] = date("Y-m-d H:i");
				$data['emp_create'] = $user['emp_id'];
				$msg = $this->course->create_course($data,$_REQUEST['cg_id']);
				$rechk_id = $this->course->rechk_id_data($_REQUEST['ccode'],$_REQUEST['com_id'],$_REQUEST['cname_th'],$_REQUEST['cname_en']);
				if($rechk_id!=""){
					if($_REQUEST['badges_name']!=""){
						$this->course->create_badges($data_badges,$rechk_id);
					}
					$this->course->create_grade($data_grade,$rechk_id);
				}
	            $this->lg->record('course', 'Create course : '.$cname_th.'');
			}else{
				$msg = $this->course->update_course($data,$_REQUEST['cg_id'],$_REQUEST['id']);
	            $this->lg->record('course', 'Update course : '.$cname_th.'');

						$name_fileth = isset($_REQUEST['name_fileth']) ? $_REQUEST['name_fileth'] : '';
						$name_fileen = isset($_REQUEST['name_fileen']) ? $_REQUEST['name_fileen'] : '';
						$document_cos_file_original = isset($_REQUEST['document_cos_file_original']) ? $_REQUEST['document_cos_file_original'] : '';
						$fil_cos_id = isset($_REQUEST['fil_cos_id']) ? $_REQUEST['fil_cos_id'] : '';
					if(!empty($_FILES['document_cos_file'])){
						$document_file = $this->reArrayFiles($_FILES['document_cos_file']);
							//print_r($document_file);
						$num_doc = 1;
						if($fil_cos_id!=""){
							if(countArray($document_file)>0){
								foreach($document_file as $val)
								{
									if($val['name']!=""){
									$path_parts = pathinfo($val['name']);
										if(countArray($path_parts)>0){
										    $newname = 'DocumentCos_'.date('dmYHis').$num_doc.".".$path_parts['extension'];
										    if(audit_move_uploaded_file($val['tmp_name'],ROOT_DIR."uploads/document/".$newname)){
										       	$each = array(
													'cos_id' => $_REQUEST['id'],
													'path_file' => $newname,
													'name_fileth' => $name_fileth,
													'name_fileen' => $name_fileen,
													'fil_cos_id' => $fil_cos_id
												);
												$this->course->insert_data_document_cos($each);
										    }
										}
								    $num_doc++;
									}else{
												$each = array(
													'cos_id' => $_REQUEST['id'],
													'path_file' => $document_cos_file_original,
													'name_fileth' => $name_fileth,
													'name_fileen' => $name_fileen,
													'fil_cos_id' => $fil_cos_id
												);
												$this->course->insert_data_document_cos($each);
									}
								}
							}else{
										       	$each = array(
													'cos_id' => $_REQUEST['id'],
													'path_file' => $document_cos_file_original,
													'name_fileth' => $name_fileth,
													'name_fileen' => $name_fileen,
													'fil_cos_id' => $fil_cos_id
												);
												$this->course->insert_data_document_cos($each);
							}
						}else{
							if(countArray($document_file)>0){
								foreach($document_file as $val)
								{
									if($val['name']!=""){
									$path_parts = pathinfo($val['name']);
										if(countArray($path_parts)>0){
										    $newname = 'DocumentCos_'.date('dmYHis').$num_doc.".".$path_parts['extension'];
										    if(audit_move_uploaded_file($val['tmp_name'],ROOT_DIR."uploads/document/".$newname)){
										       	$each = array(
													'cos_id' => $_REQUEST['id'],
													'path_file' => $newname,
													'name_fileth' => $name_fileth,
													'name_fileen' => $name_fileen
												);
												$this->course->insert_data_document_cos($each);
										    }
										}
								    $num_doc++;
									}
								}
							}
						}
					}else{
						$each = array(
							'cos_id' => $_REQUEST['id'],
							'path_file' => $document_cos_file_original,
							'name_fileth' => $name_fileth,
							'name_fileen' => $name_fileen,
							'fil_cos_id' => $fil_cos_id
						);
						$this->course->insert_data_document_cos($each);
					}
				if($_REQUEST['badges_name']!=""){
					$rechk_bad_id = $this->course->rechk_bad_id_data($_REQUEST['id']);
					if($rechk_bad_id!=""){
						$this->course->update_badges($data_badges,$_REQUEST['id']);
					}else{
						$this->course->create_badges($data_badges,$_REQUEST['id']);
					}
				}
					$rechk_bad_id = $this->course->rechk_cug_id_data($_REQUEST['id']);
					if($rechk_bad_id!=""){
						$this->course->update_grade($data_grade,$_REQUEST['id']);
					}else{
						$this->course->create_grade($data_grade,$_REQUEST['id']);
					}
			}
		}
		echo $msg;
	}


  	public function insert_period_and_permission(){
  		date_default_timezone_set("Asia/Bangkok");
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Log_model', 'lg', false);
	    $this->lg->loadDB();
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			//$cg_id = $this->input->post('cg_id');
			$course_id_pp = isset($_REQUEST['course_id_pp']) ? $_REQUEST['course_id_pp'] : '';
			$point_redeem = isset($_REQUEST['point_redeem']) ? $_REQUEST['point_redeem'] : '';
			$get_point = isset($_REQUEST['get_point']) ? $_REQUEST['get_point'] : '';
			$date_start_var = isset($_REQUEST['date_start_var']) && $_REQUEST['date_start_var'] !== '' ? $_REQUEST['date_start_var'] : '0000-00-00 00:00:00';
			$date_end_var = isset($_REQUEST['date_end_var']) && $_REQUEST['date_end_var'] !== '' ? $_REQUEST['date_end_var'] : '0000-00-00 00:00:00';
			$has_date_start = $date_start_var != '0000-00-00 00:00:00';
			$has_date_end = $date_end_var != '0000-00-00 00:00:00';
			if ($has_date_start && $has_date_end && strtotime($date_end_var) < strtotime($date_start_var)) {
				echo "0";
				return;
			}
			$course = $this->course->query_data_onupdate($course_id_pp, 'lms_cos','cos_id');
			$is_public_course = isset($course['cos_public']) && $course['cos_public'] == '1';
			$is_public_course_pending_approval = isset($course['cos_approve']) && $course['cos_approve'] == '0';
			if (!$is_public_course && !$is_public_course_pending_approval && ((!$has_date_start && $has_date_end) || ($has_date_start && !$has_date_end))) {
				echo "0";
				return;
			}
			$data = array(
				'cos_id' => $course_id_pp,
				'point_redeem' => $point_redeem,
				'get_point' => $get_point,
				'date_start' => $date_start_var,
				'date_end' => $date_end_var
			);
			$ug_id = isset($_REQUEST['posi']) ? $_REQUEST['posi'] : '';
			if($_REQUEST['operation_pp']=="Add"){
				$id = $this->course->create_period_and_permission($data);
				if($id!="-"&&($ug_id!=""||$is_public_course)){
					if (is_array($ug_id)) {
						foreach ($ug_id as $key) {
							$data_posi = array(
								'cosde_id' => $id,
								'ug_id' => $key,
								'cosdepos_date' => date('Y-m-d H:i')
							);
							$this->course->create_permission_posi($data_posi);
						}
					}
					$msg = "2";
				}else{
					$msg = "1";
				}
	            $courses = $this->course->query_data_onupdate($course_id_pp, 'lms_cos','cos_id');
	            $this->lg->record('course', 'Create period and permission in course '.$courses['cname_th'].'');
			}else{
				$this->course->update_period_and_permission($data,$_REQUEST['cosde_id']);
				$this->course->clear_permission_posi($_REQUEST['cosde_id']);
	            $courses = $this->course->query_data_onupdate($course_id_pp, 'lms_cos','cos_id');
	            $this->lg->record('course', 'Update period and permission in course '.$courses['cname_th'].'');
				if($ug_id!=""){
					foreach ($ug_id as $key) {
						$data_posi = array(
							'cosde_id' => $_REQUEST['cosde_id'],
							'ug_id' => $key,
							'cosdepos_date' => date('Y-m-d H:i')
						);
						$this->course->create_permission_posi($data_posi);
					}
				}
				$msg = "2";
			}
		}
		echo $msg;
	}
	private function getYoutubeEmbedUrl($url){

	    $urlParts   = explode('/', $url);
	    $vidid      = explode( '&', str_replace('watch?v=', '', end($urlParts) ) );

	    return '//www.youtube.com/embed/' . $vidid[0] ;
	}

  	public function insert_videocourse(){
  		date_default_timezone_set("Asia/Bangkok");
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		function getContentUrl($url) {
           // http://coursesweb.net/php-mysql/
            // Seting options for cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);    // Follows redirect responses
            // gets the file content, trigger error if false
            $file = curl_exec($ch);
            if($file === false) trigger_error(curl_error($ch));
            curl_close ($ch);
            return $file;

        }
		if(countArray($_REQUEST)>0){
			if($_REQUEST['operation_cosv']=="Add"){
					if($_REQUEST['type_media_cosv']=="1"){
						if($_REQUEST['url_media_cosv']!=""){
							$arrurl = explode(",", $_REQUEST['url_media_cosv']);
								for($num_url=0;$num_url<countArray($arrurl);$num_url++){
									$url = $this->getYoutubeEmbedUrl($arrurl[$num_url]);
									$var_chkupload = $this->course->chk_uploadfileyoutube_cosv($_REQUEST['course_id_cosv'],$url);
									if($var_chkupload==0){
										$id_youtube = substr($url,24);
										$input = 'https://img.youtube.com/vi/'.$id_youtube.'/maxresdefault.jpg';

					                    $dirimg = ROOT_DIR.'uploads/thumbnail/';            // directory in which the image will be saved
					                    $localfile = 'thumbnailCos_'.date('dmYHis').'.jpg';         // set image name the same as the file name of the source
					                    // create the file with the image on the server
					                    $r = file_put_contents($dirimg.$localfile, getContentUrl($input));
										$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id_youtube);
										parse_str($content, $ytarr);
										$each = array(
											'cos_id' => $_REQUEST['course_id_cosv'],
											'cosv_th' => $ytarr['title'],
											'cosv_en' => $ytarr['title'],
											'cosv_thumbnail' => $localfile,
											'cosv_type' => 'url',
											'cosv_video' => $url
										);
										$this->course->insert_data_video($each,$_REQUEST['course_id_cosv'],'url',$url);
									}
								}
						}
					}else if($_REQUEST['type_media_cosv']=="2"){
							if(!empty($_FILES['cosv_video'])){
									$thumbnail = "";
									if(!empty($_FILES['cosv_thumbnail'])){
							    		if($_FILES['cosv_thumbnail']['tmp_name']!=""){
								        	$thumbnail = 'thumbnailCos_'.date('dmYHis').'.jpg';
								        	if(!audit_move_uploaded_file($_FILES['cosv_thumbnail']['tmp_name'],ROOT_DIR."uploads/thumbnail/".$thumbnail)){
								        		$thumbnail = "";
								        	}
							    		}
									}
							    	if($_FILES['cosv_video']['tmp_name']!=""){
								        $newname = 'MediaCos_'.date('dmYHis').'.mp4';
								        if(audit_move_uploaded_file($_FILES['cosv_video']['tmp_name'],ROOT_DIR."uploads/cosvideo/".$newname)){
								        	$each = array(
												'cos_id' => $_REQUEST['course_id_cosv'],
												'cosv_th' => $_REQUEST['cosv_th'],
												'cosv_en' => $_REQUEST['cosv_en'],
												'cosv_thumbnail' => $thumbnail,
												'cosv_type' => 'upload',
												'cosv_video' => $newname
											);
											$this->course->insert_data_video($each,$_REQUEST['course_id_cosv'],'upload',$newname);
								        }
							    	}
							}
					}
					$this->load->model('Log_model', 'lg', false);
		            $this->lg->loadDB();
		            $courses = $this->course->query_data_onupdate($_REQUEST['course_id_cosv'], 'lms_cos','cos_id');
		            $this->lg->record('course', 'Upload video course '.$courses['cname_th'].'');
			}else{
			if($_REQUEST['type_media_cosv']=="1"){
				if($_REQUEST['url_media_cosv']!=""){
					$arrurl = explode(",", $_REQUEST['url_media_cosv']);
					$arr_checkmed = $this->course->check_media_cos($_REQUEST['course_id_cosv'],'url');
					foreach ($arr_checkmed as $key) {
						if(!in_array($key['video'], $arrurl)){
							$this->course->delete_cosv($_REQUEST['course_id_cosv'],'url',$key['video']);
						}
					}
					for($num_url=0;$num_url<countArray($arrurl);$num_url++){
						$url = $this->getYoutubeEmbedUrl($arrurl[$num_url]);
						$var_chkupload = $this->course->chk_uploadfileyoutube_cosv($_REQUEST['course_id_cosv'],$url);
						if($var_chkupload==0){
							$id_youtube = substr($url,24);
							$input = 'https://img.youtube.com/vi/'.$id_youtube.'/maxresdefault.jpg';

			                $dirimg = ROOT_DIR.'uploads/thumbnail/';            // directory in which the image will be saved
			                $localfile = 'thumbnailCos_'.date('dmYHis').'.jpg';         // set image name the same as the file name of the source
			                // create the file with the image on the server
			                $r = file_put_contents($dirimg.$localfile, getContentUrl($input));
							$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id_youtube);
							parse_str($content, $ytarr);
							$each = array(
								'cos_id' => $_REQUEST['course_id_cosv'],
								'cosv_th' => $ytarr['title'],
								'cosv_en' => $ytarr['title'],
								'cosv_thumbnail' => $localfile,
								'cosv_type' => 'url',
								'cosv_video' => $url
							);
							$this->course->insert_data_video($each,$_REQUEST['course_id_cosv'],'url',$url);
						}
					}
				}
			}else if($_REQUEST['type_media_cosv']=="2"){
				if(!empty($_FILES['cosv_video'])){
						$thumbnail = "";
						if(!empty($_FILES['cosv_thumbnail'])){
				    		if($_FILES['cosv_thumbnail']['tmp_name']!=""){
					        	$thumbnail = 'thumbnailCos_'.date('dmYHis').'.jpg';
					        	if(!audit_move_uploaded_file($_FILES['cosv_thumbnail']['tmp_name'],ROOT_DIR."uploads/thumbnail/".$thumbnail)){
					        		$thumbnail = "";
					        	}
				    		}
						}
				    	if($_FILES['cosv_video']['tmp_name']!=""){
					        $newname = 'MediaCos_'.date('dmYHis').'.mp4';
					        if(audit_move_uploaded_file($_FILES['cosv_video']['tmp_name'],ROOT_DIR."uploads/cosvideo/".$newname)){
					        	$each = array(
									'cos_id' => $_REQUEST['course_id_cosv'],
									'cosv_th' => $_REQUEST['cosv_th'],
									'cosv_en' => $_REQUEST['cosv_en'],
									'cosv_thumbnail' => $thumbnail,
									'cosv_type' => 'upload',
									'cosv_video' => $newname
								);
								$this->course->insert_data_video($each,$_REQUEST['course_id_cosv'],'upload',$newname);
					        }
				    	}
				}
			}

				$this->load->model('Log_model', 'lg', false);
		        $this->lg->loadDB();
		        $courses = $this->course->query_data_onupdate($_REQUEST['course_id_cosv'], 'lms_cos','cos_id');
		        $this->lg->record('course', 'Upload video course '.$courses['cname_th'].'');
			}
			$msg = "2";
		}
		echo $msg;
	}

  	public function insert_lesson(){
  		date_default_timezone_set("Asia/Bangkok");
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Log_model', 'lg', false);
		$this->lg->loadDB();
		$this->course->loadDB();
		function getContentUrl($url) {
           // http://coursesweb.net/php-mysql/
            // Seting options for cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);    // Follows redirect responses
            // gets the file content, trigger error if false
            $file = curl_exec($ch);
            if($file === false) trigger_error(curl_error($ch));
            curl_close ($ch);
            return $file;

          }
		if(countArray($_REQUEST)>0){
			//$cg_id = $this->input->post('cg_id');
			$course_id_lesson = isset($_REQUEST['course_id_lesson']) ? $_REQUEST['course_id_lesson'] : '';
			$les_name_th = isset($_REQUEST['les_name_th']) ? $_REQUEST['les_name_th'] : '';
			$les_name_en = isset($_REQUEST['les_name_en']) ? $_REQUEST['les_name_en'] : '';
			$les_info_th = isset($_REQUEST['les_info_th']) ? $_REQUEST['les_info_th'] : '';
			$les_info_en = isset($_REQUEST['les_info_en']) ? $_REQUEST['les_info_en'] : '';
			$status_les = isset($_REQUEST['status_les']) ? $_REQUEST['status_les'] : '';
			$les_type = isset($_REQUEST['les_type']) ? $_REQUEST['les_type'] : '';
			$scm_type = isset($_REQUEST['scm_type']) ? $_REQUEST['scm_type'] : '';
			$time_start_var = isset($_REQUEST['time_start_var']) ? $_REQUEST['time_start_var'] : '';
			$time_end_var = isset($_REQUEST['time_end_var']) ? $_REQUEST['time_end_var'] : '';
			$data = array(
				'cos_id' => $course_id_lesson,
				'les_name_th' => $les_name_th,
				'les_name_en' => $les_name_en,
				'les_info_th' => $les_info_th,
				'les_info_en' => $les_info_en,
				'status' => $status_les,
				'les_type' => $les_type,
				'scm_type' => $scm_type,
				'time_start' => $time_start_var,
				'time_end' => $time_end_var,
				'time_mod' => date('Y-m-d H:i')
			);
			if($_REQUEST['operation_lesson']=="Add"){
				$data['time_create'] = date('Y-m-d H:i');
				$lesson_id = $this->course->create_lesson($data);

		        $courses = $this->course->query_data_onupdate($course_id_lesson, 'lms_cos','cos_id');
		        $this->lg->record('course', 'Create Lesson : '.$les_name_th.' of course '.$courses['cname_th'].'');
				if($_REQUEST['les_type']=="1"){
					if($_REQUEST['type_media']=="1"){
						if($_REQUEST['url_media']!=""){
							$arrurl = explode(",", $_REQUEST['url_media']);
							for($num_url=0;$num_url<countArray($arrurl);$num_url++){
								$url = $this->getYoutubeEmbedUrl($arrurl[$num_url]);
								$id_youtube = substr($url,24);
								$input = 'https://img.youtube.com/vi/'.$id_youtube.'/maxresdefault.jpg';

			                    $dirimg = ROOT_DIR.'uploads/thumbnail/';            // directory in which the image will be saved
			                    $localfile = 'thumbnail_'.date('dmYHis').'.jpg';         // set image name the same as the file name of the source
			                    // create the file with the image on the server
			                    $r = file_put_contents($dirimg.$localfile, getContentUrl($input));
								$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id_youtube);
								$title = isset($ytarr['title'])?$ytarr['title']:"";
								parse_str($content, $ytarr);
								$each = array(
									'lessons_id' => $lesson_id,
									'med_th' => $title,
									'med_en' => $title,
									'thumbnail_med' => $localfile,
									'type' => 'url',
									'video' => $url
								);
								$this->course->insert_data_media($each,$lesson_id,'url',$url);
							}
						}
					}else if($_REQUEST['type_media']=="2"){
						if(!empty($_FILES['media_file'])){
									$thumbnail = "";
									if(!empty($_FILES['thumbnail_med'])){
							    		if($_FILES['thumbnail_med']['tmp_name']!=""){
								        	$thumbnail = 'thumbnail_'.date('dmYHis').'.jpg';
								        	if(!audit_move_uploaded_file($_FILES['thumbnail_med']['tmp_name'],ROOT_DIR."uploads/thumbnail/".$thumbnail)){
								        		$thumbnail = "";
								        	}
							    		}
									}
						        $newname = 'MediaLesson_'.date('dmYHis').'.mp4';
						        if(audit_move_uploaded_file($_FILES['media_file']['tmp_name'],ROOT_DIR."uploads/media/".$newname)){
						        	$each = array(
										'lessons_id' => $lesson_id,
										'med_th' => $_REQUEST['med_th'],
										'med_en' => $_REQUEST['med_en'],
										'thumbnail_med' => $thumbnail,
										'type' => 'upload',
										'video' => $newname
									);
									$this->course->insert_data_media($each,$lesson_id,'upload',$newname);
						        }
						}
					}

					if(!empty($_FILES['path_file'])){

						$path_file = $this->reArrayFiles($_FILES['path_file']);
							//print_r($path_file);
						$name_fileth = $_REQUEST['name_fileth'];
						$name_fileen = $_REQUEST['name_fileen'];
						$path_file_ori = $_REQUEST['path_file_ori'];
						$id_fil = $_REQUEST['id_fil'];
						$num_doc = 1;$num_count = 0;
						if(countArray($path_file)>0){
							foreach($path_file as $val)
							{
								if($val['name']!=""){
									if($id_fil[$num_count]==""){
										$path_parts = pathinfo($val['name']);
										if(countArray($path_parts)>0){
										    $newname = 'DocumentLesson_'.date('dmYHis').$num_doc.".".$path_parts['extension'];
										    if(audit_move_uploaded_file($val['tmp_name'],ROOT_DIR."uploads/document/".$newname)){
										       	$each = array(
													'lessons_id' => $lesson_id,
													'path_file' => $newname,
													'name_fileth' => $name_fileth[$num_count],
													'name_fileen' => $name_fileen[$num_count]
												);
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
				}else{
					if(!empty($_FILES['scorm_file'])){
						$scmCode = $this->course->create_scorm_id($lesson_id);
						$path = "scorm_".$lesson_id."_".$scmCode;
				        $newDir = ROOT_DIR."uploads/scorm/".$path;
				        mkdir($newDir);
				        $scormFile = $_FILES['scorm_file'];
				        $sourcePath = $scormFile['tmp_name'];
				        $path_parts = pathinfo($scormFile['name']);
				        $targetPath = $newDir."/".$path.".".$path_parts['extension'];
				        if( audit_move_uploaded_file( $sourcePath,$targetPath ) ){
				         	$zip = new ZipArchive;
				          	$openZip = $zip->open($targetPath);
				          	$zip->extractTo($newDir);
				          	$zip->close();
				          	$this->course->update_scorm_id($scmCode,$path);
				        }else{
				        	$this->course->delete_data($scmCode,'id','lms_scm');
				        	rmdir($newDir);
				        }
					}
				}
			}else{
				$lesson_id = $this->course->update_lesson($data,$_REQUEST['les_id']);

		        $courses = $this->course->query_data_onupdate($course_id_lesson, 'lms_cos','cos_id');
		        $this->lg->record('course', 'Update Lesson : '.$les_name_th.' of course '.$courses['cname_th'].'');
				if($lesson_id=="2"){
					$les_id = $_REQUEST['les_id'];
					if($_REQUEST['les_type']=="1"){

							$path = $this->course->check_scorm($_REQUEST['les_id']);
							if($path!=""){
					       		$newDir = ROOT_DIR."uploads/scorm/".$path;
					        	$this->course->delete_data($les_id,'lessons_id','lms_scm');

					       		$newDir = ROOT_DIR."uploads/scorm/".$path;
					       		function emptyDir($dir) {
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

								emptyDir($newDir);
								rmdir($newDir);
							}
						if($_REQUEST['type_media']=="1"){
							if($_REQUEST['url_media']!=""){
								$arrurl = explode(",", $_REQUEST['url_media']);
								$arr_checkmed = $this->course->check_media($les_id,'url');
								foreach ($arr_checkmed as $key) {
									if(!in_array($key['video'], $arrurl)){
										$this->course->delete_med($les_id,'url',$key['video']);
									}
								}
								for($num_url=0;$num_url<countArray($arrurl);$num_url++){
									$url = $this->getYoutubeEmbedUrl($arrurl[$num_url]);
									$var_chkupload = $this->course->chk_uploadfileyoutube($les_id,$url);
									if($var_chkupload==0){
										$id_youtube = substr($url,24);
										$input = 'https://img.youtube.com/vi/'.$id_youtube.'/maxresdefault.jpg';

					                    $dirimg = ROOT_DIR.'uploads/thumbnail/';            // directory in which the image will be saved
					                    $localfile = 'thumbnail_'.date('dmYHis').'.jpg';         // set image name the same as the file name of the source
					                    // create the file with the image on the server
					                    $r = file_put_contents($dirimg.$localfile, getContentUrl($input));
										$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id_youtube);
										$title = isset($ytarr['title'])?$ytarr['title']:"";
										parse_str($content, $ytarr);
										$each = array(
											'lessons_id' => $les_id,
											'med_th' => $title,
											'med_en' => $title,
											'thumbnail_med' => $localfile,
											'type' => 'url',
											'video' => $url
										);
										$this->course->insert_data_media($each,$les_id,'url',$url);
									}
								}
							}
						}else if($_REQUEST['type_media']=="2"){
							if(!empty($_FILES['media_file'])){
									$thumbnail = "";
									if(!empty($_FILES['thumbnail_med'])){
							    		if($_FILES['thumbnail_med']['tmp_name']!=""){
								        	$thumbnail = 'thumbnail_'.date('dmYHis').'.jpg';
								        	if(!audit_move_uploaded_file($_FILES['thumbnail_med']['tmp_name'],ROOT_DIR."uploads/thumbnail/".$thumbnail)){
								        		$thumbnail = "";
								        	}
							    		}
									}
							    	if($_FILES['media_file']['tmp_name']!=""){
								        $newname = 'MediaLesson_'.date('dmYHis').'.mp4';
								        if(audit_move_uploaded_file($_FILES['media_file']['tmp_name'],ROOT_DIR."uploads/media/".$newname)){
								        	$each = array(
												'lessons_id' => $les_id,
												'med_th' => $_REQUEST['med_th'],
												'med_en' => $_REQUEST['med_en'],
												'thumbnail_med' => $thumbnail,
												'type' => 'upload',
												'video' => $newname
											);
											$this->course->insert_data_media($each,$les_id,'upload',$newname);
								        }
							    	}
							}
						}

						if(!empty($_FILES['path_file'])){
							$path_file = $this->reArrayFiles($_FILES['path_file']);
							$name_fileth = isset($_REQUEST['name_fileth']) ? $_REQUEST['name_fileth'] : '';
							$name_fileen = isset($_REQUEST['name_fileen']) ? $_REQUEST['name_fileen'] : '';
							$path_file_ori = isset($_REQUEST['path_file_ori']) ? $_REQUEST['path_file_ori'] : '';
							$id_fil = isset($_REQUEST['id_fil']) ? $_REQUEST['id_fil'] : '';
							$name_fileth = $name_fileth;
							$name_fileen = $name_fileen;
							$path_file_ori = $path_file_ori;
							$id_fil = $id_fil;
							$num_doc = 1;$num_count = 0;
							if(countArray($path_file)>0){
								foreach($path_file as $val)
								{
									if($val['name']!=""){
											$path_parts = pathinfo($val['name']);
											if(countArray($path_parts)>0){
											    $newname = 'DocumentLesson_'.date('dmYHis').$num_doc.".".$path_parts['extension'];
											    if(audit_move_uploaded_file($val['tmp_name'],ROOT_DIR."uploads/document/".$newname)){
											       	$each = array(
														'lessons_id' => $les_id,
														'path_file' => $newname,
														'name_fileth' => $name_fileth[$num_count],
														'name_fileen' => $name_fileen[$num_count]
													);
													$this->course->insert_data_document($each);
											    }
											}
								    	$num_doc++;
									}
									$num_count++;
								}
							}
							if(isset($_REQUEST['id_filedit'])){
								$name_fileth = isset($_REQUEST['name_filethedit']) ? $_REQUEST['name_filethedit'] : '';
								$name_fileen = isset($_REQUEST['name_fileenedit']) ? $_REQUEST['name_fileenedit'] : '';
								$path_file_ori = isset($_REQUEST['path_file_oriedit']) ? $_REQUEST['path_file_oriedit'] : '';
								$id_fil = isset($_REQUEST['id_filedit']) ? $_REQUEST['id_filedit'] : '';
								$name_fileth = $name_fileth;
								$name_fileen = $name_fileen;
								$path_file_ori = $path_file_ori;
								$id_fil = $id_fil;
								$num_doc = 1;$num_count = 0;
								if(countArray($id_fil)>0){
									for($i=0;$i<countArray($id_fil);$i++)
									{
											if($id_fil[$i]!=""){
												$each = array(
															'id' => $id_fil[$i],
															'lessons_id' => $les_id,
															'path_file' => $path_file_ori[$i],
															'name_fileth' => $name_fileth[$i],
															'name_fileen' => $name_fileen[$i]
												);
												$this->course->insert_data_document($each);
											}
									    	$num_doc++;
									}
								}
							}
						}else{

							//$path_file = $this->reArrayFiles($_FILES['path_file']);
								$name_filethedit = isset($_REQUEST['name_filethedit']) ? $_REQUEST['name_filethedit'] : '';
								$name_fileenedit = isset($_REQUEST['name_fileenedit']) ? $_REQUEST['name_fileenedit'] : '';
								$path_file_oriedit = isset($_REQUEST['path_file_oriedit']) ? $_REQUEST['path_file_oriedit'] : '';
								$id_filedit = isset($_REQUEST['id_filedit']) ? $_REQUEST['id_filedit'] : '';
							$name_fileth = $name_filethedit;
							$name_fileen = $name_fileenedit;
							$path_file_ori = $path_file_oriedit;
							$id_fil = $id_filedit;
							$num_doc = 1;$num_count = 0;
							if(countArray($id_fil)>0){
								for($i=0;$i<countArray($id_fil);$i++)
								{
										if(isset($id_fil[$i])&&$id_fil[$i]!=""){
											$each = array(
														'id' => $id_fil[$i],
														'lessons_id' => $les_id,
														'path_file' => $path_file_ori[$i],
														'name_fileth' => $name_fileth[$i],
														'name_fileen' => $name_fileen[$i]
											);
											$this->course->insert_data_document($each);
										}
								    	$num_doc++;
								}
							}
						}
					}else{
						if(!empty($_FILES['scorm_file'])){
							if($_FILES['scorm_file']['name']!=""){

								$scorm_file = $this->reArrayFiles($_FILES['scorm_file']);
								foreach ($scorm_file as $val) {
									if($val['name']!=""){

										$path = $this->course->check_scorm($les_id);
										if($path!=""){
								       		$newDir = ROOT_DIR."uploads/scorm/".$path;
								       		function emptyDir($dir) {
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
											emptyDir($newDir);
											rmdir($newDir);
								        	$this->course->delete_data($les_id,'lessons_id','lms_scm');
								        	$this->course->delete_document($les_id);
								        	$this->course->delete_med_video($les_id,'upload');
								        	$this->course->delete_med_video($les_id,'url');
								        	//rmdir($newDir);
										}

										$scmCode = $this->course->create_scorm_id($les_id);
										$path = "scorm_".$les_id."_".$scmCode;
								        $newDir = ROOT_DIR."uploads/scorm/".$path;
								        mkdir($newDir);
								        $scormFile = $_FILES['scorm_file'];
								        $sourcePath = $scormFile['tmp_name'];
								        $path_parts = pathinfo($scormFile['name']);
								        $targetPath = $newDir."/".$path.".".$path_parts['extension'];
								        if( audit_move_uploaded_file( $sourcePath,$targetPath ) ){
								         	$zip = new ZipArchive;
								          	$openZip = $zip->open($targetPath);
								          	$zip->extractTo($newDir);
								          	$zip->close();
								          	$this->course->update_scorm_id($scmCode,$path);
								        }else{
								        	$this->course->delete_data($scmCode,'id','lms_scm');

								       		function emptyDir($dir) {
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

											emptyDir($newDir);
											rmdir($newDir);
								        }
									}
								}
							}
						}
					}
				}
			}
			$msg = "2";
		}
		echo $msg;
	}
	private function reArrayFiles($file)
	{
	    $file_ary = array();
	    $file_count = countArray($file['name']);
	    $file_key = array_keys($file);

	    for($i=0;$i<$file_count;$i++)
	    {
	        foreach($file_key as $val)
	        {
	            $file_ary[$i][$val] = $file[$val][$i];
	        }
	    }
	    return $file_ary;
	}

	public function fetch_course(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();

		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["u_id"]) ? $this->course->fetch_data_course($user,$_REQUEST['wg_id'],$_REQUEST['cg_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
			"draw"            => $draw,
			"recordsTotal"    => $count,
			"recordsFiltered" => $count,
			"data"            => $query,
			"error"           => $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_course_detail(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_data_course_detail($user,$_REQUEST['cos_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}
	public function count_of_register(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$query = $this->course->fetch_data_enroll_detail($user,$_REQUEST['cos_id']);
		echo countArray($query);
	}

	public function fetch_course_register(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_data_register($user,$_REQUEST['cos_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_course_enroll(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_data_enroll_detail($user, $_REQUEST['cos_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_course_enroll_qiz(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_data_enroll_qiz_detail($user, $_REQUEST['qiz_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_data_learning_subject_overview(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_data_learning_subject_overview($user) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_data_lastcomplete(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$cos_id = isset($_REQUEST['cos_id']) ? $_REQUEST['cos_id'] : '';
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_data_lastcomplete($user, $cos_id) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_lesson_document(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_lesson_document($user, $_REQUEST['les_id'], $status_user) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_cos_document(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_cos_document($user, $_REQUEST['cos_id'], $status_user) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_course_lesson(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_course_lesson($user, $_REQUEST['cos_id'], $status_user) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_videocourse(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_videocourse($user, $_REQUEST['cos_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_course_survey(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_course_survey($user, $_REQUEST['cos_id'], $status_user) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_course_survey_detail(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_course_survey_detail($user, $_REQUEST['sv_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_course_quiz(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$status_user = isset($_REQUEST['status_user']) ? $_REQUEST['status_user'] : '';
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_course_quiz($user, $_REQUEST['cos_id'], $status_user) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_course_question(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_course_question($user, $_REQUEST['quiz']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_quiz_question_check(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_quiz_question_check($user, $_REQUEST['ques_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function fetch_quiz_detail(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_quiz_detail($user, $_REQUEST['qiz_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}


	public function fetch_lesson_media(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_lesson_media($user, $_REQUEST['les_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}


	public function fetch_lesson_media_view(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$isError = isset($user["u_id"]) ? false : label("table_session_lost")."_".label("m_ok");
		$query = isset($user["emp_id"]) ? $this->course->fetch_lesson_media_view($user, $_REQUEST['les_id']) : array();
		$draw = intval($this->input->get("draw"));
		$count = countArray($query);
		$result = array(
		  "draw" 				=> $draw,
		  "recordsTotal" 		=> $count,
		  "recordsFiltered" 	=> $count,
		  "data" 				=> $query,
		  "error"           	=> $isError
		);
		echo json_encode($result);
		exit();
	}

	public function update_course_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['id_update'],'lms_cos','cos_id');
			echo json_encode($result);
		}
	}

	public function update_course_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['cosde_id_update'],'lms_cos_detail','cosde_id');
			$result['date_start_var'] = (!empty($result['date_start']) && $result['date_start'] != '0000-00-00 00:00:00') ? $result['date_start'] : '';
			$result['date_end_var'] = (!empty($result['date_end']) && $result['date_end'] != '0000-00-00 00:00:00') ? $result['date_end'] : '';
			$result['date_start'] = $result['date_start_var'] != '' ? date('d/F/Y H:i:00',strtotime($result['date_start_var'])) : '';
			$result['date_end'] = $result['date_end_var'] != '' ? date('d/F/Y H:i:00',strtotime($result['date_end_var'])) : '';
			$result['timestart'] = $result['date_start_var'] != '' ? date('d/F/Y',strtotime($result['date_start_var'])) : '';
			$result['timeend'] = $result['date_end_var'] != '' ? date('d/F/Y',strtotime($result['date_end_var'])) : '';
			echo json_encode($result);
		}
	}

	public function lesson_media_detail(){
		$this->output->set_content_type('application/json');
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		if (!isset($user['emp_id'])) {
			$this->output->set_status_header(401)->set_output(json_encode(array('success' => false, 'message' => 'Session expired')));
			return;
		}
		$id = (int)$this->input->get('id');
		$media = $this->db->where('id', $id)->where('type', 'upload')->get('lms_med')->row_array();
		if (!$media) {
			$this->output->set_status_header(404)->set_output(json_encode(array('success' => false, 'message' => 'Video not found')));
			return;
		}
		$this->output->set_output(json_encode(array('success' => true, 'data' => array(
			'id' => (int)$media['id'],
			'med_name_th' => isset($media['med_name_th']) ? $media['med_name_th'] : '',
			'med_name_eng' => isset($media['med_name_eng']) ? $media['med_name_eng'] : '',
			'med_name_jp' => isset($media['med_name_jp']) ? $media['med_name_jp'] : '',
			'thumbnail' => isset($media['thumbnail_med']) ? $media['thumbnail_med'] : '',
			'video' => isset($media['video']) ? $media['video'] : ''
		))));
	}

	public function update_lesson_media(){
		$this->output->set_content_type('application/json');
		$this->load->model('Course_model', 'course', true);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		if (!isset($user['emp_id'])) {
			$this->output->set_status_header(401)->set_output(json_encode(array('success' => false, 'message' => 'Session expired')));
			return;
		}
		$id = (int)$this->input->post('id');
		$media = $this->db->where('id', $id)->where('type', 'upload')->get('lms_med')->row_array();
		if (!$media) {
			$this->output->set_status_header(404)->set_output(json_encode(array('success' => false, 'message' => 'Video not found')));
			return;
		}

		$updates = array();
		foreach (array('med_name_th', 'med_name_eng', 'med_name_jp') as $nameField) {
			$value = $this->input->post($nameField);
			if ($value !== null) $updates[$nameField] = trim((string)$value);
		}
		$moved = array();
		$oldFiles = array();

		if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
			if ($_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK || $_FILES['thumbnail']['size'] > 10 * 1024 * 1024 || strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION)) !== 'jpg') {
				$this->output->set_status_header(422)->set_output(json_encode(array('success' => false, 'message' => 'Cover image must be a JPG file up to 10 MB')));
				return;
			}
			$newThumbnail = 'thumbnail_edit_'.date('YmdHis').'_'.mt_rand(1000, 9999).'.jpg';
			$thumbnailPath = ROOT_DIR.'uploads/thumbnail/'.$newThumbnail;
			if (!audit_move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailPath)) {
				$this->output->set_status_header(500)->set_output(json_encode(array('success' => false, 'message' => 'Unable to save cover image')));
				return;
			}
			$updates['thumbnail_med'] = $newThumbnail;
			$moved[] = $thumbnailPath;
			if (!empty($media['thumbnail_med'])) $oldFiles[] = ROOT_DIR.'uploads/thumbnail/'.basename($media['thumbnail_med']);
		}

		if (isset($_FILES['video']) && $_FILES['video']['error'] !== UPLOAD_ERR_NO_FILE) {
			if ($_FILES['video']['error'] !== UPLOAD_ERR_OK || $_FILES['video']['size'] > 512 * 1024 * 1024 || strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION)) !== 'mp4') {
				foreach ($moved as $path) audit_unlink($path);
				$this->output->set_status_header(422)->set_output(json_encode(array('success' => false, 'message' => 'Video must be an MP4 file up to 512 MB')));
				return;
			}
			$newVideo = 'MediaLesson_edit_'.date('YmdHis').'_'.mt_rand(1000, 9999).'.mp4';
			$videoPath = ROOT_DIR.'uploads/media/'.$newVideo;
			if (!audit_move_uploaded_file($_FILES['video']['tmp_name'], $videoPath)) {
				foreach ($moved as $path) audit_unlink($path);
				$this->output->set_status_header(500)->set_output(json_encode(array('success' => false, 'message' => 'Unable to save video')));
				return;
			}
			$updates['video'] = $newVideo;
			$moved[] = $videoPath;
			if (!empty($media['video'])) $oldFiles[] = ROOT_DIR.'uploads/media/'.basename($media['video']);
		}

		$this->db->where('id', $id)->update('lms_med', $updates);
		if ($this->db->error()['code']) {
			foreach ($moved as $path) audit_unlink($path);
			$this->output->set_status_header(500)->set_output(json_encode(array('success' => false, 'message' => 'Unable to update video')));
			return;
		}
		if (!$this->db->affected_rows() && empty($moved)) {
			$this->output->set_output(json_encode(array('success' => true, 'message' => 'No changes')));
			return;
		}
		foreach ($oldFiles as $path) { if (is_file($path)) audit_unlink($path); }
		$this->output->set_output(json_encode(array('success' => true, 'message' => 'Video updated')));
	}

	public function query_fil_lesson(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->course->loadDB();
		$this->manage->loadDB();

		$tr_str = "";

		if(countArray($_REQUEST)>0){
			$fetch_chk = $this->func_query->query_row('lms_les','lms_cos','lms_cos.cos_id = lms_les.cos_id','','les_id="'.$_REQUEST['les_id'].'"');
			$cos_lang = explode(',', $fetch_chk['cos_lang']);
			$this->db->where('lessons_id',$_REQUEST['les_id']);
			$this->db->from('lms_fil');
			$query_fil = $this->db->get();
			$num_fil = $query_fil->num_rows();
			$count_file = 1;
			if($num_fil>0){
				$fetch_fil = $query_fil->result_array();
				foreach ($fetch_fil as $key => $value) {
					$input_th = in_array('th', $cos_lang)?'<td><input type="text" class="form-control" name="name_file_thedit[]" value="'.$value['name_file_th'].'" required id="name_file_thedit"></td>':'';
					$input_eng = in_array('eng', $cos_lang)?'<td><input type="text" class="form-control" name="name_file_engedit[]" value="'.$value['name_file_eng'].'" required id="name_file_engedit"></td>':'';
					$input_jp = in_array('jp', $cos_lang)?'<td><input type="text" class="form-control" name="name_file_jpedit[]" value="'.$value['name_file_jp'].'" required id="name_file_jpedit"></td>':'';

					$tr_str .= '<tr id="row_'.$count_file.'" class="row_document"><td align="center"><button name="del_row_lessonfile" onclick="return false;" id="row_'.$count_file.'" title="'.label('delete').'" class="btn btn-sm btn-danger waves-effect waves-light del_row_lessonfile"><i class="mdi mdi-window-close"></i></button></td>'.$input_th.$input_eng.$input_jp.'<td>'.$value['path_file'].'<input type="hidden" id="path_file_ori_'.$count_file.'" name="path_file_oriedit[]" value="'.$value['path_file'].'"><input type="hidden" id="id_filedit_'.$count_file.'" name="id_filedit[]" value="'.$value['id'].'"></td></tr>';
					$count_file++;
				}
			}
		}
		echo $tr_str;
	}

	public function rechk_status_lesson(){
	    $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
	    $user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		$output = array();
		if(isset($_REQUEST)){
			$les_id = isset($_REQUEST['les_id'])?$_REQUEST['les_id']:"";
			$emp_id = isset($_REQUEST['emp_id'])?$_REQUEST['emp_id']:"";
	        $this->db->from('lms_les_tc');
	        $this->db->where('lms_les_tc.les_id', $les_id);
	        $this->db->where('lms_les_tc.emp_id', $emp_id);
	        $query = $this->db->get();
	        $num = $query->num_rows();
	        if($num>0){
	        	$result = $query->row_array();
	        	$output['les_status'] = $result['learn_status'];
	        }else{
	        	$output['les_status'] = "0";
	        }
		}
	}

	public function update_lesson_detail_data(){
	    $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
	    $user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Lesson_model', 'lesson', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->course->loadDB();
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
    		$arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
			$result = $this->manage->query_data_onupdate($_REQUEST['les_id_update'],'lms_les','les_id');
			$this->db->where('emp_id',$user['emp_id']);
			$this->db->where('les_id',$_REQUEST['les_id_update']);
			$this->db->from('lms_les_tc');
			$query_lestc = $this->db->get();
			$num_lestc = $query_lestc->num_rows();
			if($num_lestc==0){
				$arr_lestc = array(
					'emp_id' => $user['emp_id'],
					'les_id' => $_REQUEST['les_id_update'],
					'learn_status' => '1'
				);
				$this->lesson->createTC($arr_lestc);
			}
			$this->course->firsttime_les($result['cos_id']);

                $count_qiz = $this->func_query->numrows('lms_qiz','','','','cos_id="'.$result['cos_id'].'" and quiz_status = "1"');
                $count_qiz_tc = $this->func_query->numrows('lms_qiz_tc','lms_qiz','lms_qiz.qiz_id = lms_qiz_tc.qiz_id','','lms_qiz.cos_id="'.$result['cos_id'].'" and lms_qiz_tc.emp_id = "'.$user['emp_id'].'" and lms_qiz_tc.qiz_status="3"');
                $count_les = $this->func_query->numrows('lms_les','','','','cos_id="'.$result['cos_id'].'" and status = "1"');
                $count_les_tc = $this->func_query->numrows('lms_les_tc','lms_les','lms_les.les_id = lms_les_tc.les_id','','lms_les.cos_id="'.$result['cos_id'].'" and lms_les_tc.emp_id = "'.$user['emp_id'].'" and lms_les_tc.learn_status = "2"');
                if(($count_qiz==$count_qiz_tc)&&($count_les==$count_les_tc)){
						$this->course->end_course($result['cos_id']);
                }
			$this->db->where('lessons_id',$_REQUEST['les_id_update']);
			$this->db->from('lms_fil');
			$query_fil = $this->db->get();
			$num_fil = $query_fil->num_rows();
			$result['num_fil'] = $num_fil;
			if($lang=="thai"){ 
                    $les_name = $result['les_name_th']!=""?$result['les_name_th']:$result['les_name_eng'];
                    $les_name = $les_name!=""?$les_name:$result['les_name_jp'];
                  }else if($lang=="english"){ 
                    $les_name = $result['les_name_eng']!=""?$result['les_name_eng']:$result['les_name_th'];
                    $les_name = $les_name!=""?$les_name:$result['les_name_jp'];
                  }else{
                    $les_name = $result['les_name_jp']!=""?$result['les_name_jp']:$result['les_name_eng'];
                    $les_name = $les_name!=""?$les_name:$result['les_name_th'];
                  }
			$result['les_name'] = $les_name;
			$result['les_status'] = $result['les_status'];
			$result['les_type'] = $result['les_type'];
			$result['scm_type'] = $result['scm_type'];
			if($result['time_start']=="0000-00-00 00:00:00"){
				$result['time_start_var'] = "";
				$result['time_start'] = "";
			}else{
				$result['time_start_var'] = $result['time_start'];
				$result['time_start'] = date('d/F/Y',strtotime($result['time_start']));
			}
			if($result['time_end']=="0000-00-00 00:00:00"){
				$result['time_end_var'] = "";
				$result['time_end'] = "";
			}else{
				$result['time_end_var'] = $result['time_end'];
				$result['time_end'] = date('d/F/Y',strtotime($result['time_end']));
			}
			if($result['les_type']=="1"){
				$url = $this->query_data_arr($_REQUEST['les_id_update'], 'lms_med','type','url');
				$result['upload'] = $this->query_data_arr($_REQUEST['les_id_update'], 'lms_med','type','upload');
				$result['document'] = $this->query_data_arr($_REQUEST['les_id_update'], 'lms_fil','','');
				$result['url'] = "";
				if(countArray($url)>0){
					$num_url = 0;
					foreach ($url as $key => $value) {
						$result['url'] .= $value['video'];
							$num_url++;
						if($num_url<countArray($url)){
							$result['url'] .= ",";
						}
					}
				}
			}else{
				$result['scorm'] = $this->manage->query_data_onupdate($_REQUEST['les_id_update'],'lms_scm','lessons_id');
			}
			if($lang=="thai"){
				$result['time_modified'] = date('d/',strtotime($result['les_modifieddate'])).$arrMonthThaiTextFull[intval(date('m',strtotime($result['les_modifieddate'])))]."/".(date('Y',strtotime($result['les_modifieddate']))+543)." ".date('H:i',strtotime($result['les_modifieddate']));
				if($result['time_start']!=""){
					$result['time_start_les'] = date('d/',strtotime($result['time_start_var'])).$arrMonthThaiTextFull[intval(date('m',strtotime($result['time_start_var'])))]."/".(date('Y',strtotime($result['time_start_var']))+543);
				}
				if($result['time_end']!=""){
					$result['time_end_les'] = date('d/',strtotime($result['time_end_var'])).$arrMonthThaiTextFull[intval(date('m',strtotime($result['time_end_var'])))]."/".(date('Y',strtotime($result['time_end_var']))+543);
				}
			}else{
				$result['time_modified'] = date('d/F/Y H:i',strtotime($result['les_modifieddate']));
				if($result['time_start']!=""){
					$result['time_start_les'] = date('d/F/Y',strtotime($result['time_start_var']));
				}
				if($result['time_end']!=""){
					$result['time_end_les'] = date('d/F/Y',strtotime($result['time_end_var']));
				}
			}
			echo json_encode($result);
		}
	}

	public function update_quiz_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->course->loadDB();
		$this->manage->loadDB();
		$user = $this->session->userdata('user');
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['qiz_id_update'],'lms_qiz','qiz_id');
			$result_ques = $this->course->recheck_total('lms_ques','qiz_id',$_REQUEST['qiz_id_update'],'');
			$result['result_ques'] = $result_ques;
			$this->course->firsttime_les($result['cos_id']);

                $count_qiz = $this->func_query->numrows('lms_qiz','','','','cos_id="'.$result['cos_id'].'" and quiz_status = "1"');
                $count_qiz_tc = $this->func_query->numrows('lms_qiz_tc','lms_qiz','lms_qiz.qiz_id = lms_qiz_tc.qiz_id','','lms_qiz.cos_id="'.$result['cos_id'].'" and lms_qiz_tc.emp_id = "'.$user['emp_id'].'" and lms_qiz_tc.qiz_status="3"');
                $count_les = $this->func_query->numrows('lms_les','','','','cos_id="'.$result['cos_id'].'" and status = "1"');
                $count_les_tc = $this->func_query->numrows('lms_les_tc','lms_les','lms_les.les_id = lms_les_tc.les_id','','lms_les.cos_id="'.$result['cos_id'].'" and lms_les_tc.emp_id = "'.$user['emp_id'].'" and lms_les_tc.learn_status = "2"');
                if(($count_qiz==$count_qiz_tc)&&($count_les==$count_les_tc)){
                  
					$this->course->end_course($result['cos_id']);
                }
			if($result['period_open']=="0000-00-00 00:00:00"){
				$result['period_open_var'] = "";
				$result['period_open'] = "";
			}else{
				$result['period_open_var'] = $result['period_open'];
				$result['period_open'] = date('d/F/Y',strtotime($result['period_open']));
			}
			if($result['period_end']=="0000-00-00 00:00:00"){
				$result['period_end_var'] = "";
				$result['period_end'] = "";
			}else{
				$result['period_end_var'] = $result['period_end'];
				$result['period_end'] = date('d/F/Y',strtotime($result['period_end']));
			}
			echo json_encode($result);
		}
	}

	public function query_quiz_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['qiz_id_update'],'lms_qiz','qiz_id');
			$result_ques = $this->course->recheck_total('lms_ques','qiz_id',$_REQUEST['qiz_id_update'],'');
			$result['result_ques'] = $result_ques;
			if($result['period_open']=="0000-00-00 00:00:00"){
				$result['period_open_var'] = "";
				$result['period_open'] = "";
			}else{
				$result['period_open_var'] = $result['period_open'];
				$result['period_open'] = date('d/F/Y',strtotime($result['period_open']));
			}
			if($result['period_end']=="0000-00-00 00:00:00"){
				$result['period_end_var'] = "";
				$result['period_end'] = "";
			}else{
				$result['period_end_var'] = $result['period_end'];
				$result['period_end'] = date('d/F/Y',strtotime($result['period_end']));
			}
			echo json_encode($result);
		}
	}


	public function update_ques_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->course->query_data_onques($_REQUEST['qiz_id_rechkques'],$_REQUEST['ques_id_future'],$_REQUEST['ques_id'],$_REQUEST['tc_answer']);
			echo json_encode($result);
		}
	}
	public function update_last_answer(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->course->answer_data_onques_last($_REQUEST['qiz_id_rechkques'],$_REQUEST['ques_id'],$_REQUEST['tc_answer']);
			echo json_encode($result);
		}
	}
	public function update_score_tc(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->course->answer_data_update_score($_REQUEST['tc_id'],$_REQUEST['tc_score']);
			echo json_encode($result);
		}
	}
	
	public function update_scoreall_qiztc(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$ques_id = isset($_REQUEST['ques_idcheck'])?$_REQUEST['ques_idcheck']:"";
			$tc_id = isset($_REQUEST['tc_id'])?$_REQUEST['tc_id']:"";
			$tc_note = isset($_REQUEST['tc_note'])?$_REQUEST['tc_note']:"";
			$tc_score = isset($_REQUEST['tc_score'])?$_REQUEST['tc_score']:"";
			if($tc_id!=""&&countArray($tc_id)>0){
				for ($i=0; $i < countArray($tc_id); $i++) { 
					$tc_id_loop  = isset($tc_id[$i])?$tc_id[$i]:"";
					$tc_note_loop  = isset($tc_note[$i])?$tc_note[$i]:"";
					$tc_score_loop = isset($tc_score[$i])?$tc_score[$i]:"";

					if($tc_id_loop!=""){

						$this->course->answer_data_update_score($tc_id_loop,$tc_score_loop,$tc_note_loop);
					}
				}
			}
			$arr_update = array('ques_isSavescore'=>'1');
			$this->db->where('ques_id',$ques_id);
			$this->db->update('lms_ques',$arr_update);
			$output = array();
			$output['status'] = "2";
			echo json_encode($output);
		}
	}
	
	public function update_scoreall_qiztc_single(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Function_query_model', 'func_query', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$ques_id = isset($_REQUEST['ques_id'])?$_REQUEST['ques_id']:"";
			$tc_id = isset($_REQUEST['tc_id'])?$_REQUEST['tc_id']:"";
			$tc_note = isset($_REQUEST['tc_note'])?$_REQUEST['tc_note']:"";
			$tc_score = isset($_REQUEST['tc_score'])?$_REQUEST['tc_score']:"";
			$output = array();
			$output['cos_id'] = "";
			$output['emp_id'] = "";

					if($tc_id!=""){

						$status_update = $this->course->answer_data_update_score($tc_id,$tc_score,$tc_note);
						if($status_update!=""&&$status_update=="1"){
							$fetch_chk = $this->func_query->Query_row('lms_ques_tc','lms_qiz','lms_ques_tc.qiz_id = lms_qiz.qiz_id','','lms_ques_tc.tc_id="'.$tc_id.'"');
							$output['cos_id'] = $fetch_chk['cos_id'];
							$output['emp_id'] = $fetch_chk['emp_id'];
						}
					}
			/*$arr_update = array('ques_isSavescore'=>'1');
			$this->db->where('ques_id',$ques_id);
			$this->db->update('lms_ques',$arr_update);*/
			$output['status'] = "2";
			echo json_encode($output);
		}
	}

	public function update_note_tc(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->course->answer_data_update_note($_REQUEST['tc_id'],$_REQUEST['tc_note']);
			echo json_encode($result);
		}
	}
	public function onchk_answer(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->course->onchk_answer($_REQUEST['qiz_id_onchk']);
			echo $result;
		}
	}

	public function rechk_qiztc(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->course->rechk_qiztc($_REQUEST['qiz_id_onchk']);
			echo json_encode($result);
		}
	}

	public function update_fil_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['fil_id'],'lms_fil','id');
			if(strpos($result['path_file'], '.xlsx') !== false||strpos($result['path_file'], '.xls') !== false){
				$result['type'] = "xlsx";
				$result['link'] = '';
			}else{
				$result['type'] = "document";
				$result['link'] = base_url().'/uploads/document/'.$result['path_file'];
			}

			echo json_encode($result);
		}
	}

	public function update_fil_cos_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['fil_id'],'lms_cos_fil','fil_cos_id');
			if(strpos($result['path_file'], '.xlsx') !== false||strpos($result['path_file'], '.xls') !== false){
				$result['type'] = "xlsx";
				$result['link'] = '';
			}else{
				$result['type'] = "document";
				$result['link'] = base_url().'/uploads/document/'.$result['path_file'];
			}

			echo json_encode($result);
		}
	}


	public function update_survey_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['sv_id_update'],'lms_survey','sv_id');
			if($result['survey_open']!="0000-00-00 00:00:00"){
				$result['survey_open_var'] = $result['survey_open'];
				$result['survey_open'] = date('d/F/Y',strtotime($result['survey_open']));
			}else{
				$result['survey_open_var'] = "";
				$result['survey_open'] = "";
			}
			if($result['survey_end']!="0000-00-00 00:00:00"){
				$result['survey_end_var'] = $result['survey_end'];
				$result['survey_end'] = date('d/F/Y',strtotime($result['survey_end']));
			}else{
				$result['survey_end_var'] = "";
				$result['survey_end'] = "";
			}
			echo json_encode($result);
		}
	}

	public function update_survey_sv_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['svde_id_update'],'lms_survey_de','svde_id');
			echo json_encode($result);
		}
	}

	public function update_question_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['ques_id_update'],'lms_ques','ques_id');
			if($result['ques_type']=="multi"){
				$result_multi = $this->manage->query_data_onupdate($_REQUEST['ques_id_update'],'lms_ques_mul','ques_id');
				$result['multi'] = $result_multi;
			}
			echo json_encode($result);
		}
	}


    public function query_data_arr($id, $datatable,$fieldname,$type) {
          $this->db->from($datatable);
          if($type!=""){
          	$this->db->where($fieldname, $type);
          }
          $this->db->where('lessons_id', $id);
          $query = $this->db->get();
          return $query->result_array();
    }

    public function query_data($datatable,$fieldname,$type) {
          $this->db->from($datatable);
          if($type!=""){
          	$this->db->where($fieldname, $type);
          }
          $query = $this->db->get();
          return $query->result_array();
    }

    public function query_data_arr_doc($id, $datatable) {
          $this->db->from($datatable);
          $this->db->where('lessons_id', $id);
          $query = $this->db->get();
          return $query->result_array();
    }



    public function query_data_chk_empc() {
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['emp_c'],'lms_emp','emp_c');
			echo json_encode($result);
		}
    }

    public function update_quiz_time_start() {
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->course->update_time_qiz($_REQUEST['qiz_id_update'],$_REQUEST['field'],'lms_qiz_tc');
			echo $result;
		}
    }

    public function update_quiz_chkscore(){
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
        $this->load->model('Function_query_model', 'func_query', false);
        $this->func_query->loadDB();
		if(countArray($_REQUEST)>0){
			$user = $this->session->userdata('user');
			$arr_output = array();
			$sum_score_total = 0;
			$arr_chk = array();
			$this->db->from('lms_ques_tc');
			$this->db->where('emp_id',$user['emp_id']);
			$this->db->where('qiz_id',$_REQUEST['qiz_id']);
			$query = $this->db->get();
			$fetch = $query->result_array();
			foreach ($fetch as $key => $value) {
				$this->db->from('lms_ques');
				$this->db->where('ques_id',$value['ques_id']);
				$this->db->where('qiz_id',$_REQUEST['qiz_id']);
				$query_rechk = $this->db->get();
				$fetch_rechk = $query_rechk->row_array();

				$sum_score_total += floatval($fetch_rechk['ques_score']);
				if(!in_array($fetch_rechk['ques_type'], $arr_chk)){
					array_push($arr_chk, $fetch_rechk['ques_type']);
				}
			}
			if(countArray($arr_chk)>1){
				$arr_output['status_qiz'] = "2";
			}else{
				$this->db->from('lms_qiz_tc');
				$this->db->where('emp_id',$user['emp_id']);
				$this->db->where('qiz_id',$_REQUEST['qiz_id']);
				$query_rechk = $this->db->get();
				$fetch_rechk = $query_rechk->row_array();

                $fetch_course = $this->func_query->query_row('lms_qiz','','','','qiz_id="'.$_REQUEST['qiz_id'].'"');
				$arr_output['status_qiz'] = "1";
				$arr_output['score'] = number_format($fetch_rechk['sum_score'],2);
				$arr_output['sum_score_total'] = number_format($sum_score_total,2);
				$arr_output['quiz_maxscore'] = number_format($fetch_course['quiz_maxscore'],2);
			}
			echo json_encode($arr_output);
		}
    }


	public function update_cert_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['id_update'],'lms_bad','courses_id');
			echo json_encode($result);
		}
	}

	public function update_score_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['id_update'],'lms_cug','course_id');
			echo json_encode($result);
		}
	}

	public function rechkquizandstudent(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){

	        $this->db->from('lms_qiz');
	        $this->db->where('cos_id', $_REQUEST['cos_id']);
	        $query = $this->db->get();
	        $fetch = $query->result_array();
	        if(countArray($fetch)>0){
	        	$this->db->from('lms_cos_enroll');
		        $this->db->where('cos_id', $_REQUEST['cos_id']);
		        $query = $this->db->get();
		        $fetch = $query->result_array();
		        if(countArray($fetch)>0){
		        	echo "1";
		        }else{
		        	echo "0";
		        }
	        }else{
	        	echo "0";
	        }
		}
	}

	public function rechk_survey_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model','course',false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		$this->db->from('lms_qn_user');
		$this->db->where('emp_id',$user['emp_id']);
		$this->db->where('sv_id',$_REQUEST['sv_id_rechkdata']);
		$this->db->where('qnu_status','2');
		$query = $this->db->get();
		$fetch = $query->row_array();
		if(countArray($fetch)>0){
			echo "1";
		}else{
			echo "0";
		}
	}

	public function view_survey_detail_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model','course',false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		function isMobile() {
            return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    	}
		if($_REQUEST['sv_id_view']!=""){
      		$this->db->select('lms_survey.sv_suggestion_status');
			$this->db->from('lms_survey');
			$this->db->where('lms_survey.sv_id',$_REQUEST['sv_id_view']);
	        $query_head = $this->db->get();
	        $fetch_head = $query_head->row_array();

      		$this->db->select('lms_qn_user.qnu_suggestion,lms_qn_user.qnu_id');
			$this->db->from('lms_qn_user');
			$this->db->where('lms_qn_user.sv_id',$_REQUEST['sv_id_view']);
			$this->db->where('lms_qn_user.emp_id',$user['emp_id']);
	        $query_head_user = $this->db->get();
	        $fetch_head_user = $query_head_user->row_array();
	        $qnu_suggestion = "";
	        $qnu_id = "";
	        if(isset($fetch_head_user)){
	        	$qnu_suggestion = $fetch_head_user['qnu_suggestion'];
	        	$qnu_id = $fetch_head_user['qnu_id'];
	        }

      		$this->db->distinct();
      		$this->db->select('lms_survey_de.svde_heading_th,lms_survey_de.svde_heading_en');
			$this->db->from('lms_survey');
			$this->db->join('lms_survey_de','lms_survey.sv_id = lms_survey_de.sv_id');
			$this->db->where('lms_survey.sv_id',$_REQUEST['sv_id_view']);
			$this->db->order_by('lms_survey_de.svde_id','ASC');
	        $query = $this->db->get();
	        $fetch = $query->result_array(); ?>

            <input type="hidden" id="sv_id" name="sv_id" value="<?php echo $_REQUEST['sv_id_view']; ?>">
	        <?php if(isMobile()){ ?>
	        	<?php echo label('squestion')."<br>"; ?>
	        	<?php $num=0;
                    	foreach ($fetch as $key_head => $value_head) { ?>
                    		<label class="text-info">	<?php
                            				if($lang=="thai"){
                            					echo $value_head['svde_heading_th'];
                            				}else{
                            					echo $value_head['svde_heading_en'];
                            				}
                            		?>
                            </label>
                            <?php
						      		$this->db->distinct();
									$this->db->from('lms_survey');
									$this->db->join('lms_survey_de','lms_survey.sv_id = lms_survey_de.sv_id');
									$this->db->where('lms_survey.sv_id',$_REQUEST['sv_id_view']);
                            		if($lang=="thai"){
										$this->db->where('lms_survey_de.svde_heading_th',$value_head['svde_heading_th']);
									}else{
										$this->db->where('lms_survey_de.svde_heading_en',$value_head['svde_heading_en']);
									}
									$this->db->order_by('lms_survey_de.svde_id','ASC');
							        $query_detail = $this->db->get();
							        $fetch_detail = $query_detail->result_array();
							        foreach ($fetch_detail as $key_detail => $value_detail) {

							      		$this->db->select('lms_qn_user_de.qnude_suggestion,lms_qn_user_de.qnude_var');
										$this->db->from('lms_qn_user_de');
										$this->db->where('lms_qn_user_de.svde_id',$value_detail['svde_id']);
										$this->db->where('lms_qn_user_de.qnu_id',$qnu_id);
								        $query_head_user_de = $this->db->get();
								        $fetch_head_user_de = $query_head_user_de->row_array();
								        $qnude_suggestion = "";
								        $qnude_var = "";
								        if(countArray($fetch_head_user_de)>0){
								        	$qnude_suggestion = $fetch_head_user_de['qnude_suggestion'];
								        	$qnude_var = $fetch_head_user_de['qnude_var'];
								        }
							        	?>
							          <?php echo "<br>";
							        		if($lang=="thai"){
							        			echo $value_detail['svde_detail_th'];
							        		}else{
							        			echo $value_detail['svde_detail_en'];
							        		} echo "<br>"; ?>
							        		<input type="hidden" name="svde_id[]" value="<?php echo $value_detail['svde_id']; ?>">
							        		<table width="100%" class="table">
							        			<thead>
							        				<tr>
						                                <th width="20%" align="center"><?php echo label('choice_5')."<br>"."5"; ?></th>
						                                <th width="20%" align="center"><?php echo label('choice_4')."<br>"."4"; ?></th>
						                                <th width="20%" align="center"><?php echo label('choice_3')."<br>"."3"; ?></th>
						                                <th width="20%" align="center"><?php echo label('choice_2')."<br>"."2"; ?></th>
						                                <th width="20%" align="center"><?php echo label('choice_1')."<br>"."1"; ?></th>
							        				</tr>
							        			</thead>
							        			<tbody>
							        				<tr>
										        		<td>
			                                                <label class="custom-control custom-radio">
			                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="5"){echo "checked";} ?> value="5" class="custom-control-input">
			                                                    <span class="custom-control-label"></span>
			                                                </label>
			                                            </td>
										        		<td>
			                                                <label class="custom-control custom-radio">
			                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="4"){echo "checked";} ?> value="4" class="custom-control-input">
			                                                    <span class="custom-control-label"></span>
			                                                </label>
			                                            </td>
										        		<td>
			                                                <label class="custom-control custom-radio">
			                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="3"){echo "checked";} ?> value="3" class="custom-control-input">
			                                                    <span class="custom-control-label"></span>
			                                                </label>
			                                            </td>
										        		<td>
			                                                <label class="custom-control custom-radio">
			                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="2"){echo "checked";} ?> value="2" class="custom-control-input">
			                                                    <span class="custom-control-label"></span>
			                                                </label>
			                                            </td>
										        		<td>
			                                                <label class="custom-control custom-radio">
			                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="1"){echo "checked";} ?> value="1" class="custom-control-input">
			                                                    <span class="custom-control-label"></span>
			                                                </label>
			                                            </td>
							        				</tr>
							        			</tbody>
							        		</table>

	                                        <div class="form-group row col-md-12">
	                                            <label class="control-label text-right"><?php echo label('Suggestion'); ?></label>
	                                            <textarea class="form-control" name="qnude_suggestion[<?php echo $num; ?>]" id="qnude_suggestion" rows="3" style="width: 100%"><?php echo $qnude_suggestion; ?></textarea>
	                                        </div>

							<?php  	$num++;
									} ?>
                <?php 	} ?>
                <hr>
                <?php if($fetch_head['sv_suggestion_status']=="1"){ ?>

                                        <div class="form-group row col-md-12">
                                            <label class="control-label text-right"><?php echo label('Suggestion_another'); ?></label>
                                            <textarea class="form-control" name="qnu_suggestion" id="qnu_suggestion" rows="4" style="width: 100%"><?php echo $qnu_suggestion; ?></textarea>
                                        </div>
              	<?php 	} ?>
	        <?php }else{ ?>
	        	<div class="table-responsive">
                        <table id="myTable_document_ddd" width="100%" class="table">
                            <thead>
                              <tr>
                                <th width="25%" align="center"><?php echo label('squestion'); ?></th>
						        <th width="10%" align="center"><?php echo label('choice_5')."<br>"."5"; ?></th>
						        <th width="10%" align="center"><?php echo label('choice_4')."<br>"."4"; ?></th>
						        <th width="10%" align="center"><?php echo label('choice_3')."<br>"."3"; ?></th>
						        <th width="10%" align="center"><?php echo label('choice_2')."<br>"."2"; ?></th>
						        <th width="10%" align="center"><?php echo label('choice_1')."<br>"."1"; ?></th>
                                <th width="25%" align="center"><?php echo label('Suggestion'); ?></th>
                              </tr>
                            </thead>
                            <tbody>
                          <?php $num=0;
                          		foreach ($fetch as $key_head => $value_head) { ?>
                            		<tr>
                            			<td colspan="7" class="text-info">
                            				<?php
                            				if($lang=="thai"){
                            					echo $value_head['svde_heading_th'];
                            				}else{
                            					echo $value_head['svde_heading_en'];
                            				}
                            				?>
                            			</td>
                            		</tr>
                            		<?php
						      		$this->db->distinct();
									$this->db->from('lms_survey');
									$this->db->join('lms_survey_de','lms_survey.sv_id = lms_survey_de.sv_id');
									$this->db->where('lms_survey.sv_id',$_REQUEST['sv_id_view']);
                            		if($lang=="thai"){
										$this->db->where('lms_survey_de.svde_heading_th',$value_head['svde_heading_th']);
									}else{
										$this->db->where('lms_survey_de.svde_heading_en',$value_head['svde_heading_en']);
									}
									$this->db->order_by('lms_survey_de.svde_id','ASC');
							        $query_detail = $this->db->get();
							        $fetch_detail = $query_detail->result_array();
							        foreach ($fetch_detail as $key_detail => $value_detail) {

							      		$this->db->select('lms_qn_user_de.qnude_suggestion,lms_qn_user_de.qnude_var');
										$this->db->from('lms_qn_user_de');
										$this->db->where('lms_qn_user_de.svde_id',$value_detail['svde_id']);
										$this->db->where('lms_qn_user_de.qnu_id',$qnu_id);
								        $query_head_user_de = $this->db->get();
								        $fetch_head_user_de = $query_head_user_de->row_array();
								        $qnude_suggestion = "";
								        $qnude_var = "";
								        if(isset($fetch_head_user_de)){
								        	$qnude_suggestion = $fetch_head_user_de['qnude_suggestion'];
								        	$qnude_var = $fetch_head_user_de['qnude_var'];
								        }?>
							        	<tr>
							        		<td>
							        		<?php
							        		if($lang=="thai"){
							        			echo $value_detail['svde_detail_th'];
							        		}else{
							        			echo $value_detail['svde_detail_en'];
							        		}
							        		?>
							        		</td>
							        		<input type="hidden" name="svde_id[]" value="<?php echo $value_detail['svde_id']; ?>">
							        		<td>
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="5"){echo "checked";} ?> value="5" class="custom-control-input">
                                                    <span class="custom-control-label"></span>
                                                </label>
                                            </td>
							        		<td>
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="4"){echo "checked";} ?> value="4" class="custom-control-input">
                                                    <span class="custom-control-label"></span>
                                                </label>
                                            </td>
							        		<td>
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="3"){echo "checked";} ?> value="3" class="custom-control-input">
                                                    <span class="custom-control-label"></span>
                                                </label>
                                            </td>
							        		<td>
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="2"){echo "checked";} ?> value="2" class="custom-control-input">
                                                    <span class="custom-control-label"></span>
                                                </label>
                                            </td>
							        		<td>
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" name="qnude_var[<?php echo $num; ?>]" <?php if($qnude_var=="1"){echo "checked";} ?> value="1" class="custom-control-input">
                                                    <span class="custom-control-label"></span>
                                                </label>
                                            </td>
							        		<td><textarea class="form-control" name="qnude_suggestion[<?php echo $num; ?>]" id="qnude_suggestion" rows="3" style="width: 100%"><?php echo $qnude_suggestion; ?></textarea></td>
							        	</tr>
							  <?php $num++;
									} ?>
                          <?php } ?>
                            </tbody>
                        </table>
                </div>
                <?php if($fetch_head['sv_suggestion_status']=="1"){ ?>

                                        <div class="form-group row col-md-12" style="padding-left: 10%;padding-right: 10%">
                                            <label class="control-label text-right"><?php echo label('Suggestion_another'); ?></label>
                                            <textarea class="form-control" name="qnu_suggestion" id="qnu_suggestion" rows="4" style="width: 100%"><?php echo $qnu_suggestion; ?></textarea>
                                        </div>
              	<?php 	} ?>
	       <?php }
		}
	}

	public function permission_course(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model','course',false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		$ug_arr = $this->manage->checkusergroup($_REQUEST['com_id']);
		$detailug_arr = array();
		if($_REQUEST['cosde_id']!=""){
			$detailug_arr = $this->course->checkdetailug($_REQUEST['cosde_id']);
		}
		foreach ($ug_arr as $key_ug => $value_ug) { 
			?>
	                    <div class="col-md-3 col-sm-3" style="margin-bottom:2px;">
	                    	<div class="checkbox checkbox-success"><input type="checkbox" id="chkposi_<?php echo $value_ug['ug_id'] ?>" name="posi[]"  value="<?php echo $value_ug['ug_id'] ?>" <?php if(in_array($value_ug['ug_id'], $detailug_arr)){echo "checked";} ?>><label for="chkposi_<?php echo $value_ug['ug_id'] ?>"><?php if($lang=="thai"){ echo $value_ug['ug_name_th']; }else{ echo $value_ug['ug_name_en']; } ?></label></div>
	                    </div>
  <?php 		
		}
	}
	public function count_fetchdata(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->load->model('Course_model','course',false);
		$this->course->loadDB();
		$fetch = $this->course->num_data($_REQUEST['tablename'],$_REQUEST['field_id'],$_REQUEST['id']);
		echo $fetch;
	}
	public function fetchdata_scorm(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->load->model('Course_model','course',false);
		$this->course->loadDB();
		$fetch = $this->course->check_scorm_id($_REQUEST['id']);
		echo $fetch;
	}

	public function rechk_status_medlesson(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->load->model('Course_model','course',false);
		$this->course->loadDB();
		$this->load->model('Lesson_model','lesson',false);
		$this->load->model('Function_query_model','func_query',false);
		$this->lesson->loadDB();
      	date_default_timezone_set("Asia/Bangkok");
		$med_id = isset($_REQUEST['med_id'])?$_REQUEST['med_id']:"";
		$emp_id = isset($_REQUEST['emp_id'])?$_REQUEST['emp_id']:"";
		$this->db->where('lms_med.id',$med_id);
		$this->db->from('lms_med');
		$this->db->join('lms_les','lms_med.lessons_id = lms_les.les_id');
		$query = $this->db->get();
		$fetch = $query->row_array();
		$this->db->where('med_id',$med_id);
		$this->db->where('emp_id',$emp_id);
		$this->db->from('lms_med_tc');
		$query_chk = $this->db->get();
		$num_chk = $query_chk->num_rows();
		if($num_chk==0){
			$arr_medtc =array(
				'med_id' => $med_id,
				'emp_id' => $emp_id,
				'medtc_datetime' => date('Y-m-d H:i'),
				'medtc_status' => '2'
			);
			$this->db->insert('lms_med_tc',$arr_medtc);
		}

		$this->db->where('lms_med_tc.medtc_status', '2');
		$this->db->where('lms_med_tc.emp_id',$emp_id);
		$this->db->where('lms_med.lessons_id',$fetch['les_id']);
		$this->db->from('lms_med_tc');
		$this->db->join('lms_med','lms_med.id = lms_med_tc.med_id');
		$query_empchk = $this->db->get();
		$num_empchk = $query_empchk->num_rows();

		$this->db->where('lms_med.lessons_id',$fetch['les_id']);
		$this->db->from('lms_med');
		$query_medchk = $this->db->get();
		$num_medchk = $query_medchk->num_rows();

		if($num_empchk==$num_medchk){
			$this->db->where('emp_id',$emp_id);
			$this->db->where('les_id',$fetch['les_id']);
			$this->db->from('lms_les_tc');
			$query_lestc = $this->db->get();
			$num_lestc = $query_lestc->num_rows();
			if($num_lestc>0){
				$fetch_lestc = $query_lestc->row_array();
				$arr_lestc = array(
					'learn_status' => '2'
				);
				$this->db->where('emp_id',$emp_id);
				$this->db->where('les_id',$fetch['les_id']);
				$this->db->update('lms_les_tc',$arr_lestc);
			}else{
				$arr_lestc = array(
					'emp_id' => $emp_id,
					'les_id' => $fetch['les_id'],
					'learn_status' => '2'
				);
				$this->lesson->createTC($arr_lestc);
			}

      			$user = $this->session->userdata('user');
                $count_qiz = $this->func_query->numrows('lms_qiz','','','','cos_id="'.$fetch['cos_id'].'" and quiz_status = "1"');
                $count_qiz_tc = $this->func_query->numrows('lms_qiz_tc','lms_qiz','lms_qiz.qiz_id = lms_qiz_tc.qiz_id','','lms_qiz.cos_id="'.$fetch['cos_id'].'" and lms_qiz_tc.emp_id = "'.$user['emp_id'].'" and lms_qiz_tc.qiz_status="3"');
                $count_les = $this->func_query->numrows('lms_les','','','','cos_id="'.$fetch['cos_id'].'" and status = "1"');
                $count_les_tc = $this->func_query->numrows('lms_les_tc','lms_les','lms_les.les_id = lms_les_tc.les_id','','lms_les.cos_id="'.$fetch['cos_id'].'" and lms_les_tc.emp_id = "'.$user['emp_id'].'" and lms_les_tc.learn_status = "2"');
                if(($count_qiz==$count_qiz_tc)&&($count_les==$count_les_tc)){
                  
					$this->course->end_course($fetch['cos_id']);
                }
		}
		$output['les_id'] = $fetch['les_id'];
		echo json_encode($output);
	}

	public function run_media(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->load->model('Course_model','course',false);
		$this->course->loadDB();
		$fetch = $this->course->result_data('lms_med','lms_med.lessons_id',$_REQUEST['les_id'],'lms_med.type','ASC');
        if(countArray($fetch)>0){ ?>
        	<div class="row">
     <?php	foreach ($fetch as $key => $value) {
     			if($value['thumbnail_med']!=""){
     				$thumbnail_med = base_url().'/uploads/thumbnail/'.$value['thumbnail_med'];
     			}else{
     				$thumbnail_med = base_url().'/assets/images/background/user-info.jpg';
     			}
        		if($value['type']=="url"){ ?>
        			<div class="col-md-4"><br>
        				<div onclick="onplayer_video('<?php echo $value['type']; ?>','<?php echo $value['video']; ?>','<?php echo $value['id']; ?>')" class="onplayer_video" style="width: 100%;height: 150px;background-image: url('<?php echo $thumbnail_med;?>');background-position: center;background-size:cover;display: flex;justify-content:center;align-items: center;cursor: pointer;">
        					<i style="font-size: 60px;" class="fas fa-play-circle playbutton" title="<?php if($lang=='thai'){echo $value['med_th'];}else{echo $value['med_en'];} ?>"></i>
		                    <!--<iframe class="embed-responsive-item" src="<?php echo $value['video']; ?>" allowfullscreen></iframe>-->
		                </div><br>
        			</div>
          <?php }else{ ?>
          			<div class="col-md-4"><br>
        				<div onclick="onplayer_video('<?php echo $value['type']; ?>','<?php echo $value['video']; ?>','<?php echo $value['id']; ?>')" class="onplayer_video" style="width: 100%;height: 150px;background-image: url('<?php echo $thumbnail_med;?>');background-position: center;background-size:cover;display: flex;justify-content:center;align-items: center;cursor: pointer;">
        					<i style="font-size: 60px;" class="fas fa-play-circle playbutton" title="<?php if($lang=='thai'){echo $value['med_th'];}else{echo $value['med_en'];} ?>"></i>
		                    <!--embed-responsive embed-responsive-16by9<video  controls="controls" style="width: 100%" src="<?php echo base_url()."/uploads/media/".$value['video'];?>"></video>-->
		                </div><br>
        			</div>
          <?php	}
	        } ?>
	    	</div><hr>
  <?php }else{
        	echo "";
        }

	}

	public function run_media_course(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->load->model('Course_model','course',false);
		$this->course->loadDB();
		$fetch = $this->course->result_data('lms_cos_video','lms_cos_video.cos_id',$_REQUEST['cos_id'],'lms_cos_video.cosv_type','ASC');
        if(countArray($fetch)>0){ ?>
        	<div class="row">
     <?php	foreach ($fetch as $key => $value) {
     			if($value['cosv_thumbnail']!=""){
     				$thumbnail_med = base_url().'/uploads/thumbnail/'.$value['cosv_thumbnail'];
     			}else{
     				$thumbnail_med = base_url().'/assets/images/background/user-info.jpg';
     			}
     			if($value['cosv_thumbnail']==""){
     				$thumbnail_med = "";
     			}
        		if($value['cosv_type']=="url"){ ?>
        			<div class="col-md-4"><br>
        				<div onclick="onplayer_video_cos('<?php echo $value['cosv_type']; ?>','<?php echo $value['cosv_video']; ?>')" class="onplayer_video" style="width: 100%;height: 150px;background-image: url('<?php echo $thumbnail_med;?>');background-position: center;background-size:cover;display: flex;justify-content:center;align-items: center;cursor: pointer;">
        					<i style="font-size: 60px;" class="fas fa-play-circle playbutton" title="<?php if($lang=='thai'){echo $value['cosv_th'];}else{echo $value['cosv_en'];} ?>"></i>
		                    <!--<iframe class="embed-responsive-item" src="<?php echo $value['video']; ?>" allowfullscreen></iframe>-->
		                </div><br>
        			</div>
          <?php }else{ ?>
          			<div class="col-md-4"><br>
        				<div onclick="onplayer_video_cos('<?php echo $value['cosv_type']; ?>','<?php echo $value['cosv_video']; ?>')" class="onplayer_video" style="width: 100%;height: 150px;background-image: url('<?php echo $thumbnail_med;?>');background-position: center;background-size:cover;display: flex;justify-content:center;align-items: center;cursor: pointer;">
        					<i style="font-size: 60px;" class="fas fa-play-circle playbutton" title="<?php if($lang=='thai'){echo $value['cosv_th'];}else{echo $value['cosv_en'];} ?>"></i>
		                    <!--embed-responsive embed-responsive-16by9<video  controls="controls" style="width: 100%" src="<?php echo base_url()."/uploads/media/".$value['video'];?>"></video>-->
		                </div><br>
        			</div>
          <?php	}
	        } ?>
	    	</div><hr>
  <?php }else{
        	echo "";
        }

	}

	public function li_lesson_course(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model','course',false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		$li_arr = $this->manage->checklesson($_REQUEST['cos_id'],'');
		$detailpos_arr = array();
		foreach ($li_arr as $key_li => $value_li) {

				if($lang=="thai"){ 
                    $les_name = $value_li['les_name_th']!=""?$value_li['les_name_th']:$value_li['les_name_eng'];
                    $les_name = $les_name!=""?$les_name:$value_li['les_name_jp'];
                  }else if($lang=="english"){ 
                    $les_name = $value_li['les_name_eng']!=""?$value_li['les_name_eng']:$value_li['les_name_th'];
                    $les_name = $les_name!=""?$les_name:$value_li['les_name_jp'];
                  }else{
                    $les_name = $value_li['les_name_jp']!=""?$value_li['les_name_jp']:$value_li['les_name_eng'];
                    $les_name = $les_name!=""?$les_name:$value_li['les_name_th'];
                  }
			?>
            <li class="dd-item" data-cosid="<?php echo $_REQUEST['cos_id']; ?>" data-id="<?php echo $value_li['les_id']; ?>" style="width:100%">
                <div class="dd-handle dd-nochildren" style="font-size: 18px;font-family: 'Prompt', sans-serif;"> <?php echo $les_name; ?> </div>
            </li>
  <?php }
	}

	public function insert_fil_log(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		$this->db->from('lms_fil');
		$this->db->join('lms_les','lms_fil.lessons_id = lms_les.les_id');
		$this->db->where('lms_fil.id',$_REQUEST['fil_id']);
		$query = $this->db->get();
		$fetch = $query->row_array();

		  $this->db->select('lms_log_enroll.id_log');
          $this->db->from('lms_cos_enroll');
          $this->db->join('lms_log_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
          $this->db->where('emp_id',$user['emp_id']);
          $this->db->where('cos_id',$fetch['cos_id']);
          $query_chk = $this->db->get();
          $fetch_chk = $query_chk->row_array();
			$data = array(
	              'fil_id' => $_REQUEST['fil_id'],
	              'emp_id' => $user['emp_id'],
	              'id_log' => $fetch_chk['id_log']
	        );
	        $this->db->insert('lms_fil_log', $data);
	}

	public function save_survey(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		$count_qnude_suggestion = countArray($_REQUEST['qnude_suggestion']);
		$count_chk = 0;
		for ($i=0; $i < $count_qnude_suggestion; $i++) {
			if(!isset($_REQUEST['qnude_var'][$i])){
				$_REQUEST['qnude_var'][$i] = "";
			}
		}
		for ($i=0; $i < $count_qnude_suggestion; $i++) {
			if($_REQUEST['qnude_var'][$i]!=""){
				$count_chk++;
			}
		}
		if($count_chk>=$count_qnude_suggestion){


          $this->db->from('lms_survey');
          $this->db->where('sv_id',$_REQUEST['sv_id']);
          $query_sv = $this->db->get();
          $fetch_sv =$query_sv->row_array();
          $this->db->select('lms_log_enroll.id_log');
          $this->db->from('lms_cos_enroll');
          $this->db->join('lms_log_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
          $this->db->where('emp_id',$user['emp_id']);
          $this->db->where('cos_id',$fetch_sv['cos_id']);
          $query_chk = $this->db->get();
          $fetch_chk = $query_chk->row_array();
            $data = array(
              'sv_id' => $_REQUEST['sv_id'],
              'emp_id' => $user['emp_id'],
              'qnu_status' => "2",
	          'id_log' => $fetch_chk['id_log'],
              'qnu_suggestion' => $_REQUEST['qnu_suggestion'],
              'qnu_datetime' => date('Y-m-d H:i')
            );
            $this->db->insert('lms_qn_user', $data);
            $id = $this->db->insert_id();
			for ($i=0; $i < $count_qnude_suggestion; $i++) {
				$data_detail = array(
	              'qnu_id' => $id,
	              'qnude_var' => $_REQUEST['qnude_var'][$i],
	              'qnude_suggestion' => $_REQUEST['qnude_suggestion'][$i],
	              'svde_id' => $_REQUEST['svde_id'][$i]
	            );
            	$this->db->insert('lms_qn_user_de', $data_detail);
			}
			echo "2";

			$this->load->model('Log_model', 'lg', false);
	        $this->lg->loadDB();
	        $survey = $this->course->query_data_onupdate($_REQUEST['sv_id'], 'lms_survey','sv_id');
	        $this->lg->record('course', 'Save Survey : '.$survey['sv_title_th'].'');
		}else{
			echo "0";
		}
	}

	public function query_data_cosdocumentfile(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['fil_cos_id'],'lms_cos_fil','fil_cos_id');
			echo json_encode($result);
		}
	}

	public function checknext_les(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			  $result = $this->manage->query_data_onupdate($_REQUEST['id'],'lms_les','les_id');
	          $this->db->from('lms_les');
	          $this->db->join('lms_cos','lms_les.cos_id = lms_cos.id');
	          $this->db->where('lms_les.les_status', '1');
	          if($user['Is_admin']=="0"){
	          	$this->db->where('lms_cos.com_id', $user['com_id']);
	          }
	          $this->db->where('lms_les.les_id!=', $_REQUEST['id']);
	          $this->db->where('lms_les.les_sequences>', $result['les_sequences']);
	          $this->db->where('((lms_les.time_start="0000-00-00 00:00:00" and lms_les.time_end="0000-00-00 00:00:00")', NULL, false);
			  $this->db->or_where("(lms_les.time_start<='".date('Y-m-d H:i')."' and lms_les.time_end>='".date('Y-m-d H:i')."'))", NULL, false);
	          $this->db->order_by('lms_les.les_sequences','ASC');
		      $query_loop = $this->db->get();
		      $fetch_loop = $query_loop->row_array();
	          echo json_encode($fetch_loop);
		}
	}

	public function checkback_les(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			  $result = $this->manage->query_data_onupdate($_REQUEST['id'],'lms_les','les_id');
	          $this->db->from('lms_les');
	          $this->db->join('lms_cos','lms_les.cos_id = lms_cos.id');
	          $this->db->where('lms_les.les_status', '1');
	          if($user['Is_admin']=="0"){
	          	$this->db->where('lms_cos.com_id', $user['com_id']);
	          }
	          $this->db->where('lms_les.les_id!=', $_REQUEST['id']);
	          $this->db->where('lms_les.les_sequences<', $result['les_sequences']);
	          $this->db->where('((lms_les.time_start="0000-00-00 00:00:00" and lms_les.time_end="0000-00-00 00:00:00")', NULL, false);
			  $this->db->or_where("(lms_les.time_start<='".date('Y-m-d H:i')."' and lms_les.time_end>='".date('Y-m-d H:i')."'))", NULL, false);
	          $this->db->order_by('lms_les.les_sequences','DESC');
		      $query_loop = $this->db->get();
		      $fetch_loop = $query_loop->row_array();
		      echo json_encode($fetch_loop);
		}
	}


	public function checknext_qiz(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$this->db->from('lms_ques_tc');
			$this->db->where('ques_id',$_REQUEST['id']);
			$this->db->where('emp_id',$user['emp_id']);
			$query_chk = $this->db->get();
			$fetch_chk = $query_chk->row_array();
			$tc_number = $fetch_chk['tc_number'];
			$qiz_id = $fetch_chk['qiz_id'];

			$this->db->from('lms_ques_tc');
			$this->db->where('qiz_id',$qiz_id);
			$this->db->where('tc_number>',$tc_number);
			$this->db->where('emp_id',$user['emp_id']);
			$query_getchk = $this->db->get();
			$fetch_getchk = $query_getchk->row_array();
			if(isset($fetch_getchk)){
				$ques_id = $fetch_getchk['ques_id'];
		        $this->db->from('lms_ques');
		        $this->db->where('lms_ques.ques_show', '1');
		        $this->db->where('lms_ques.ques_id', $ques_id);
		        $this->db->where('lms_ques.qiz_id', $_REQUEST['qiz_id']);
		        $this->db->order_by('lms_ques.ques_id','ASC');
			    $query_loop = $this->db->get();
			    $fetch_loop = $query_loop->row_array();
			    echo json_encode($fetch_loop);
			}else{
				echo json_encode("0");
			}
		}
	}

	public function checkback_qiz(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$user = $this->session->userdata('user');
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Manage_model','manage',false);
		$this->manage->loadDB();
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$this->db->from('lms_ques_tc');
			$this->db->where('ques_id',$_REQUEST['id']);
			$this->db->where('emp_id',$user['emp_id']);
			$query_chk = $this->db->get();
			$fetch_chk = $query_chk->row_array();
			$tc_number = intval($fetch_chk['tc_number'])-1;
			$qiz_id = $fetch_chk['qiz_id'];
			$this->db->from('lms_ques_tc');
			$this->db->where('qiz_id',$qiz_id);
			$this->db->where('tc_number',$tc_number);
			$this->db->where('emp_id',$user['emp_id']);
			$query_getchk = $this->db->get();
			$fetch_getchk = $query_getchk->row_array();
			if(isset($fetch_getchk)){
				$ques_id = $fetch_getchk['ques_id'];
		        $this->db->from('lms_ques');
		        $this->db->where('lms_ques.ques_show', '1');
		        $this->db->where('lms_ques.ques_id', $ques_id);
		        $this->db->where('lms_ques.qiz_id', $_REQUEST['qiz_id']);
		        $this->db->order_by('lms_ques.ques_id','ASC');
			    $query_loop = $this->db->get();
			    $fetch_loop = $query_loop->row_array();
				echo json_encode($fetch_loop);
			}else{
				echo json_encode("0");
			}
		}
	}

	public function edit_li_lesson(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Manage_model','manage',false);
		$user = $this->session->userdata('user');
		$this->manage->loadDB();
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$cos_id = "";
			$num = 1;
			$arr_out = array();
			if(isset($_REQUEST['arr_obj'])){
				foreach ($_REQUEST['arr_obj'] as $key => $value) {
				  $cos_id = $value['cosid'];
		          $this->db->from('lms_les');
		          $this->db->where('lms_les.les_status', '1');
		          $this->db->where('lms_les.les_id', $value['id']);
		          $this->db->where('lms_les.cos_id', $value['cosid']);
			      $query_loop = $this->db->get();
			      $fetch_loop = $query_loop->result_array();
			      if(countArray($fetch_loop)>0){
		            $data = array(
		              	'les_sequences' => $num,
					  	'les_modifiedby' => $user['u_id'],
		              	'les_modifieddate' => date('Y-m-d H:i')
		            );
		          	$this->db->where('les_id', $value['id']);
				    $this->db->update('lms_les', $data);
				    $num++;
			      }
				}
				$li_arr = $this->manage->checklesson($cos_id,'');
				foreach ($_REQUEST['arr_obj'] as $key => $value) {
				  $cos_id = $value['cosid'];
		          $this->db->from('lms_les');
		          $this->db->where('lms_les.les_status', '1');
		          $this->db->where('lms_les.les_id', $value['id']);
		          $this->db->where('lms_les.cos_id', $value['cosid']);
			      $query_loop = $this->db->get();
			      $fetch_loop = $query_loop->result_array();
			      if(countArray($fetch_loop)>0){
				    foreach ($li_arr as $key_li => $value_li) {
				    	if($value_li['les_id']==$value['id']){
				    		unset($li_arr[$key_li]);
				    	}
				    }
			      }
				}
				if(countArray($li_arr)>0){
					foreach ($li_arr as $key_li => $value_li) {
			            $data = array(
			            	'les_sequences' => $num,
					    	'les_modifiedby' => $user['u_id'],
			              	'les_modifieddate' => date('Y-m-d H:i')
			            );
			            $this->db->where('les_id', $value_li['les_id']);
					    $this->db->update('lms_les', $data);
					    $num++;
					}
				}
			}
			//$msg = $this->course->delete_data($_REQUEST['id_delete'],$_REQUEST['field'],$_REQUEST['table_name']);
		}
		//echo $msg;
	}

	public function delete_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->load->model('Function_query_model', 'func_query', false);

		date_default_timezone_set("Asia/Bangkok");
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$msg = $this->course->delete_data($_REQUEST['id_delete'],$_REQUEST['field'],$_REQUEST['table_name']);
			if($_REQUEST['table_name']=="lms_fil"){
		        $fetch = $this->func_query->query_row('lms_les','','','','lms_les.les_id in (select lms_fil.lessons_id from lms_fil where lms_fil.id="'.$_REQUEST['id_delete'].'")');
		        $arr_update = array('cos_modifieddate'=>date('Y-m-d H:i:s'));
		        $this->db->where('cos_id',$fetch['cos_id']);
		        $this->db->update('lms_cos',$arr_update);
			}else if($_REQUEST['table_name']=="lms_med"){
		        $fetch = $this->func_query->query_row('lms_les','','','','lms_les.les_id in (select lms_med.lessons_id from lms_med where lms_med.id="'.$_REQUEST['id_delete'].'")');
				if (isset($fetch['cos_id'])) {
					$arr_update = array('cos_modifieddate'=>date('Y-m-d H:i:s'));
					$this->db->where('cos_id',$fetch['cos_id']);
					$this->db->update('lms_cos',$arr_update);
				}
			}
		}
		echo $msg;
	}

	public function delete_data_update(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$msg = $this->course->delete_data_update($_REQUEST['id_delete'],$_REQUEST['field'],$_REQUEST['table_name'],$_REQUEST['field_status']);
		}
		echo $msg;
	}

	public function delete_data_updateval(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		if(countArray($_REQUEST)>0){
			$msg = $this->course->delete_data_updateval($_REQUEST['id_update'],$_REQUEST['field_id'],$_REQUEST['table_name'],$_REQUEST['field'],$_REQUEST['val']);
		}
		echo $msg;
	}

	public function sent_message_course(){
        date_default_timezone_set("Asia/Bangkok");
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Course_model', 'course', false);
		$this->course->loadDB();
		$user = $this->session->userdata('user');
		$cos_id = isset($_REQUEST['cos_id_msg'])? $_REQUEST['cos_id_msg']:"";
		$smc_msg = isset($_REQUEST['smc_msg'])? $_REQUEST['smc_msg']:"";
		$email_cos = isset($_REQUEST['email_cos'])? $_REQUEST['email_cos']:"";
		$data_insert = array(
			'emp_id' => $user['emp_id'],
			'cos_id' => $cos_id,
			'smc_msg' => $smc_msg,
			'smc_createdate' => date('Y-m-d H:i')
		);
		$this->db->insert('lms_sendmail_course',$data_insert);
		$course = $this->course->query_data_onupdate_result($cos_id, 'lms_cos','cos_id');
		$fullname_student = "";
		$cos_name = "";
		if($lang=="thai"){
			$fullname_student = $user['fullname_th'];
			$cos_name = $course[0]['cname_th'];
		}else{
			$fullname_student = $user['fullname_en'];
			$cos_name = $course[0]['cname_en'];
		}
		$message = "<p>ผู้ติดต่อ : ".$fullname_student."</p><br><p>ชื่อวิชา : ".$cos_name."</p><br><p>ข้อความ : ".$smc_msg."</p><br><p>เมื่อวันที่ : ".date('d F Y H:i')."</p>";
		$this->db->sendEmail( $email_cos , $message,'ข้อความติดต่อจากผู้เรียน');
		echo "2";
	}

}
