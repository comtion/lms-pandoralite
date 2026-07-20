<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workgroup extends CI_Controller {

  public function index(){}

  public function loadWorkGroup(){
    //Check user session : Redirect to login page with destination url when it false;
    $arr = array();
    $arr['page'] = "workgroup/loadWorkGroup";
    $this->load->model('User_model', 'login', TRUE);
    // Load and set default language;
    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
	$this->lang->load($lang,$lang);
    if($this->login->checkSession($arr['page'])){
		$user = $this->session->userdata('user');
		$arr['emp_c'] = $user['emp_c'];
		$arr['com_admin'] = $user['com_admin'];
		$arr['com_id'] = $user['com_id'];
		$arr['user'] = $user;
		if($lang=="thai"){
			$arr['com_name'] = $user['com_name_th'];
		}else{
			$arr['com_name'] = $user['com_name_en'];
		}
		
    }
    //!$this->login->checkSession($arr['page']) ? : $arr['page'];

	$arr['lang'] = $lang;

    //Load all work groups data
    $this->load->model('Workgroup_model', 'workgroup', TRUE);
    $this->workgroup->loadDB();
    $arr['workgroups'] = $this->workgroup->getAllWorkgroup( '' );
    $arr['ComIdworkgroups'] = $this->workgroup->rechkComIdWorkgroup();
    $this->workgroup->closeDB();

	$this->load->model('Manage_model', 'manage', FALSE);
	$this->manage->loadDB();
	$arr['company_select'] = $this->manage->getCompany();
    $arr['ComIdworkgroups'] = $this->workgroup->rechkComIdWorkgroup();
	$arr['rechk_permission'] = $this->workgroup->rechk_permission_wg();
	if($user['Is_admin']=="0"){
		foreach ($arr['workgroups'] as $key_wg => $value_wg) {
			if(!in_array($value_wg['id'], $arr['rechk_permission'])){
				unset($arr['workgroups'][$key_wg]);
			}
		}
	}


        $arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
			$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
			if($arr['btn_view']!="1"){
				redirect(base_url().'dashboard') ;
			}
	$this->manage->closeDB();
    //Record Log activity
    $this->load->model('Log_model', 'lg', FALSE);
    $this->lg->loadDB();
    $this->lg->record('workgroup', 'Enter to all workgroup page ');
    $this->lg->closeDB();

    //Load footer
    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $this->foot->closeDB();

    //Send to front view
    $this->load->view('frontend/workgroupAvai', $arr );
  }

  public function load_workgroup_data(){
  		$txt_search = isset($_REQUEST['value'])? $_REQUEST['value']:"";

    	$page = "workgroup/loadWorkGroup";
	    $this->load->model('User_model', 'login', TRUE);
	    // Load and set default language;
	    $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		$company_select = $this->manage->getCompany();

        $arr_permission = $this->manage->chk_permission_page();
		$btn_update = $this->manage->chk_permission($page,'ru_edit');
		$btn_delete = $this->manage->chk_permission($page,'ru_del');
	    //Load all work groups data
	    $this->load->model('Workgroup_model', 'workgroup', TRUE);
	    $this->workgroup->loadDB();
	    $workgroups = $this->workgroup->getAllWorkgroup( '',$txt_search );

	    if($user['com_admin']!="CUSTOMER"&&$user['Is_admin']!="0"){
	    	foreach($company_select as $company ) { ?>
                <div class="card">
                        <div class="card-header"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_en']; } ?></div>

                        <div class="card-body">
                            <div class="row">
                                <?php $num = 0;
                                foreach($workgroups as $workgroup ) {
                                    if($company['com_id']==$workgroup['com_id']){ $num++;
                                ?>
                                <div class="col-md-3">
                                    <div class="card" style="height: 400px">
                                        <img class="card-img-top img-responsive" width="100%" style="max-height: 140px" src="<?php echo REAL_PATH;?>/uploads/work_group/<?php echo $workgroup['wthumb']; ?>" onerror="this.src='<?php echo REAL_PATH;?>/uploads/course/default_profile.jpg'" alt="">
                                        <div class="card-body slimtest1">
                                            <h4 class="card-title" style="font-family: 'Prompt', sans-serif;"><?php if($lang=="thai"){echo $workgroup['wtitle_th'];}else{echo $workgroup['wtitle_en'];} ?></h4>
                                            <?php if($lang=="thai"){echo $workgroup['wdesc_th'];}else{echo $workgroup['wdesc_en'];} ?>
                                        </div>
                                        <div class="card-footer" align="center">
                                            <a href="<?php echo base_url().'coursegroup/loadCoursegroup/'.$workgroup['id'];?>" style="font-size: 14px" class="btn btn-block btn-outline-info"><i class="mdi mdi-information-outline"></i> <?php echo label('wchoose'); ?></a>
                                            <?php if($btn_update=="1"){ ?>
                                                <a id="<?php echo $workgroup['id']; ?>" style="font-size: 14px" class="update btn btn-block btn-outline-warning">
                                                    <i class="mdi mdi-lead-pencil"></i> <?php echo label('edit'); ?>
                                                </a>
                                            <?php } ?>
                                            <?php if($btn_delete=="1"){ ?>
                                                <a id="<?php echo $workgroup['id']; ?>" style="font-size: 14px" class="delete btn btn-block btn-outline-danger">
                                                    <i class="mdi mdi-delete-forever"></i> <?php echo label('delete'); ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                          	  <?php } 
                                }?>
                            </div>
                            <?php if($num==0){?>
                                    <h4 align="center"><i class="mdi mdi-exclamation"></i> <?php echo label('wg_datanotfound');?></h4>
                            <?php } ?>
                        </div>
                </div>
	  <?php }
	    }else{ ?>

                                    <div class="row" >
                                    <?php $num = 0;
                                      foreach($workgroups as $workgroup ) { $num++;?>
                                      <div class="col-md-3">
                                        <div class="card" style="height: 400px">
                                            <img class="card-img-top img-responsive" width="100%" style="max-height: 140px" src="<?php echo REAL_PATH;?>/uploads/work_group/<?php if($workgroup['wthumb']!=""){echo $workgroup['wthumb'];}else{echo "default_image.jpg";} ?>" alt="">
                                            <div class="card-body slimtest1">
                                                <h4 class="card-title"><?php if($lang=="thai"){echo $workgroup['wtitle_th'];}else{echo $workgroup['wtitle_en'];} ?></h4>
                                                <?php if($lang=="thai"){echo $workgroup['wdesc_th'];}else{echo $workgroup['wdesc_en'];} ?>
                                            </div>
                                            <div class="card-footer" align="center">
                                                <a href="<?php echo base_url().'coursegroup/loadCoursegroup/'.$workgroup['id'];?>" style="font-size: 14px" class="btn btn-block btn-outline-info"><i class="mdi mdi-information-outline"></i> <?php echo label('wchoose'); ?></a>
                                                <?php if($btn_update=="1"){ ?>
                                                <a id="<?php echo $workgroup['id']; ?>" style="font-size: 14px" class="update btn btn-block btn-outline-warning">
                                                    <i class="mdi mdi-lead-pencil"></i> <?php echo label('edit'); ?>
                                                </a>
                                                <?php } ?>
                                                <?php if($btn_delete=="1"){ ?>
                                                <a id="<?php echo $workgroup['id']; ?>" style="font-size: 14px" class="delete btn btn-block btn-outline-danger">
                                                    <i class="mdi mdi-delete-forever"></i> <?php echo label('delete'); ?>
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                      </div>
                                    <?php } ?>
                                    </div>
                                        <?php if($num==0){?>
                                                <h4 align="center"><i class="mdi mdi-exclamation"></i> <?php echo label('wg_datanotfound');?></h4>
                                        <?php } ?>
  <?php }
  }

	public function insert_workgroup(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->load->model('Log_model', 'lg', FALSE);
	    $this->lg->loadDB();
		$this->workgroup->loadDB();
		if(countArray($_REQUEST)>0){
			if(isset($_FILES['image'])&&$_FILES['image']!=""){
				if( isset( $_FILES['image']) ){
					$imageSourcePath = $_FILES['image']['tmp_name'];
					$imageTargetPath = ROOT_DIR."uploads/work_group/".$_REQUEST['wcode']."_".$_REQUEST['com_id']."_".date('YmdHis').".jpg";
					if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
						$_REQUEST['image'] = $_REQUEST['wcode']."_".$_REQUEST['com_id']."_".date('YmdHis').".jpg" ;
					}else{
						$_REQUEST['image'] = $_REQUEST['image_ori'];
					}
				}else{
					$_REQUEST['image'] = $_REQUEST['image_ori'];
				}
			}else{
				$_REQUEST['image'] = $_REQUEST['image_ori'];
			}
			$data = array(
				'wcode' => $_REQUEST['wcode'],
				'com_id' => $_REQUEST['com_id'],
				'wtitle_th' => $_REQUEST['wtitle_th'],
				'wtitle_en' => $_REQUEST['wtitle_en'],
				'wdesc_th' => $_REQUEST['wdesc_th'],
				'wdesc_en' => $_REQUEST['wdesc_en'],
				'wthumb' => $_REQUEST['image'],
				'u_date' => date("Y-m-d H:i"),
				'u_by' => $user['emp_c']
			);
			if($_REQUEST['operation']=="Add"){
				$msg = $this->workgroup->create_workgroup($data);
	            $this->lg->record('course', 'Create Workgroup : '.$_REQUEST['wtitle_th'].' By '.$user['fullname_th']);
			}else{
				$msg = $this->workgroup->update_workgroup($data,$_REQUEST['id']);
	            $this->lg->record('course', 'Update Workgroup : '.$_REQUEST['wtitle_th'].' By '.$user['fullname_th']);
			}
		}
		echo $msg;
	}


	public function update_workgroup_data(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['id_update'],'lms_wkg','id');
			echo json_encode($result);
		}
	}



	public function delete_workgroup_data(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->load->model('Course_model', 'course', FALSE);
		$this->load->model('Log_model', 'lg', FALSE);
	    $this->lg->loadDB();
		$this->workgroup->loadDB();
		if(countArray($_REQUEST)>0){
			if(countArray($this->workgroup->rechkUseWorkgroup($_REQUEST['id_delete']))>0){
				$msg = "1";
			}else{
				$data = array(
					'lms_wkg.wstatus' => '0',
					'lms_wkg.u_date' => date("Y-m-d H:i"),
					'lms_wkg.u_by' => $user['emp_c']
				);
				$msg = $this->workgroup->update_workgroup($data,$_REQUEST['id_delete']);
		        $wkg = $this->course->query_data_onupdate($_REQUEST['id_delete'], 'lms_wkg','id');
	            $this->lg->record('course', 'Delete Workgroup : '.$wkg['wtitle_th'].' By '.$user['fullname_th']);
			}
		}
		echo $msg;
	}

	public function recheckcostype(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$com_id = $this->input->post('com_id');
		$tc_id = $this->input->post('tc_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
			$fetch_wg = $this->workgroup->getCostype( $com_id );
		if(countArray($fetch_wg)>0){
			echo "<option value=''>".label('Choosecostype')."</option>";
			foreach ($fetch_wg as $key) {
				$selected_val = "";
				if($key['tc_id']==$tc_id){
					$selected_val = "selected";
				}
				if($lang=="thai"){
					echo "<option value='".$key['tc_id']."' ".$selected_val.">".$key['tc_name_th']."</option>";
				}else{
					echo "<option value='".$key['tc_id']."' ".$selected_val.">".$key['tc_name_en']."</option>";
				}
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function rechecksurvey(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$cos_id = $this->input->post('cos_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$survey_select = $this->workgroup->selectSurvey($cos_id);
		if(countArray($survey_select)>0){
			echo "<option value=''>".label('svplease').label('survey')."</option>";
			foreach ($survey_select as $key) {
				if($lang=="thai"){
					echo "<option value='".$key['sv_id']."'>".$key['sv_title_th']."</option>";
				}else{
					echo "<option value='".$key['sv_id']."'>".$key['sv_title_en']."</option>";
				}
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function recheckworkgroup(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$com_id = $this->input->post('com_id');
		$wg_id = $this->input->post('wg_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		if($com_id==""&&$wg_id!=""){
			$fetch_wg = $this->workgroup->getWorkgroup( $wg_id );
			$com_id = $fetch_wg[0]['com_id'];
		}
		$wg_select = $this->workgroup->selectComId($com_id);
		if(countArray($wg_select)>0){
			echo "<option value=''>".label('Chooseworkgroup')."</option>";
			foreach ($wg_select as $key) {
				$selected_val = "";
				if($key['id']==$wg_id){
					$selected_val = "selected";
				}
				if($lang=="thai"){
					echo "<option value='".$key['id']."' ".$selected_val.">".$key['wtitle_th']."</option>";
				}else{
					echo "<option value='".$key['id']."' ".$selected_val.">".$key['wtitle_en']."</option>";
				}
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function recheckcoursegroup(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$wg_id = $this->input->post('wg_id');
		$course_id = $this->input->post('course_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$cg_id = array();
		if($course_id!=""){
			$cg_array = $this->workgroup->getCourseGroup( $course_id );
			foreach ($cg_array as $key => $value) {
				array_push($cg_id, $value['cg_id']);
			}
		}
		$cg_select = $this->workgroup->selectWorkgoup($wg_id);
		if(countArray($cg_select)>0){
			echo "<option value=''>".label('Choosecoursegroup')."</option>";
			foreach ($cg_select as $key) {
				$selected_val = "";
				if(in_array($key['id'], $cg_id)){
					$selected_val = "selected";
				}
				if($lang=="thai"){
					echo "<option value='".$key['id']."' ".$selected_val.">".$key['cgtitle_th']."</option>";
				}else{
					echo "<option value='".$key['id']."' ".$selected_val.">".$key['cgtitle_en']."</option>";
				}
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function recheckmul_answer(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$ques_id = $this->input->post('ques_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:"multi";

		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		$result_multi = $this->manage->query_data_onupdate($ques_id,'lms_ques_mul','ques_id');
		$arr_multi = explode(",", $result_multi['mul_answer']);
		if($type=="multi"){
		?>
        <option value="mul_c1" <?php if(in_array("mul_c1", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 1"; ?></option>
        <option value="mul_c2" <?php if(in_array("mul_c2", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 2"; ?></option>
        <option value="mul_c3" <?php if(in_array("mul_c3", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 3"; ?></option>
        <option value="mul_c4" <?php if(in_array("mul_c4", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 4"; ?></option>
        <option value="mul_c5" <?php if(in_array("mul_c5", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 5"; ?></option>
		<?php
		}else{ ?>
        <option value="mul_c1" <?php if(in_array("mul_c1", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 1"; ?></option>
        <option value="mul_c2" <?php if(in_array("mul_c2", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 2"; ?></option>
  <?php }
	}

	public function recheckmule_answer(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$quese_id = $this->input->post('quese_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:"multi";
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		$result_multi = $this->manage->query_data_onupdate($quese_id,'lms_quese_mul','quese_id');
		$arr_multi = isset($result_multi['mule_answer']) ? explode(",", $result_multi['mule_answer']) : array();
		if($type=="multi"){
		?>

        <option value="mul_c1" <?php if(in_array("mul_c1", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 1"; ?></option>
        <option value="mul_c2" <?php if(in_array("mul_c2", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 2"; ?></option>
        <option value="mul_c3" <?php if(in_array("mul_c3", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 3"; ?></option>
        <option value="mul_c4" <?php if(in_array("mul_c4", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 4"; ?></option>
        <option value="mul_c5" <?php if(in_array("mul_c5", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 5"; ?></option>
		<?php
		}else{ ?>
        <option value="mul_c1" <?php if(in_array("mul_c1", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 1"; ?></option>
        <option value="mul_c2" <?php if(in_array("mul_c2", $arr_multi)){echo "selected";} ?>><?php echo label('choice')." 2"; ?></option>
  <?php }
	}

	public function recheckcondition(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$com_id = $this->input->post('com_id');
		$condition = $this->input->post('condition');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$condition_select = $this->workgroup->selectCondition($com_id);
		if(countArray($condition_select)>0){
			echo "<option value='000'>".label('none')."</option>";
			foreach ($condition_select as $key) {
				$selected_val = "";
				if($key['id']==$condition){
					$selected_val = "selected";
				}
				if($lang=="thai"){
					echo "<option value='".$key['id']."' ".$selected_val.">".$key['cname_th']."</option>";
				}else{
					echo "<option value='".$key['id']."' ".$selected_val.">".$key['cname_eng']."</option>";
				}
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function select_qize(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$com_id = $this->input->post('com_id');
		$qize_id = $this->input->post('qize_id');
		$quiz_lang = isset($_REQUEST['quiz_lang'])?explode(',', $_REQUEST['quiz_lang']):"";
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->workgroup->loadDB();
		$fetch_qizex = $this->func_query->query_result('lms_qiz_exp','','','','com_id="'.$com_id.'" and qize_isDelete="0" and qize_status="1"');
		if(countArray($fetch_qizex)>0){
			$qize_lang = "";
			foreach ($fetch_qizex as $key_qizex => $value_qizex) {
				$qize_lang = explode(',', $value_qizex['qize_lang']);
				if(countArray($qize_lang)>0){
					$numchk = 0;
					for ($i=0; $i < countArray($qize_lang); $i++) { 
						if(isset($qize_lang[$i])&&in_array($qize_lang[$i], $quiz_lang)){
							$numchk++;
						}
					}
					if($numchk==0){
						unset($fetch_qizex[$key_qizex]);
					}
				}
				if(isset($value_qizex['qize_id'])){
					$fetch_chkques = $this->func_query->numrows('lms_quese','','','','qize_id="'.$value_qizex['qize_id'].'" and quese_isDelete="0"');
					if($fetch_chkques==0){
						unset($fetch_qizex[$key_qizex]);
					}
				}
			}
		}
		if(countArray($fetch_qizex)>0){
			echo "<option value=''>".label('none')."</option>";
			foreach ($fetch_qizex as $key) {
				$selected_val = "";
				if($key['qize_id']==$qize_id){
					$selected_val = "selected";
				}

	              if($lang=="thai"){ 
	                $qize_name = $key['qize_name_th']!=""?$key['qize_name_th']:$key['qize_name_eng'];
	                $qize_name = $qize_name!=""?$qize_name:$key['qize_name_jp'];
	              }else if($lang=="english"){ 
	                $qize_name = $key['qize_name_eng']!=""?$key['qize_name_eng']:$key['qize_name_th'];
	                $qize_name = $qize_name!=""?$qize_name:$key['qize_name_jp'];
	              }else{
	                $qize_name = $key['qize_name_jp']!=""?$key['qize_name_jp']:$key['qize_name_eng'];
	                $qize_name = $qize_name!=""?$qize_name:$key['qize_name_th'];
	              }
				echo "<option value='".$key['qize_id']."' ".$selected_val.">".$qize_name."</option>";
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function select_course(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$com_id = $this->input->post('com_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$quize_select = $this->workgroup->selectcos($com_id);
		if(countArray($quize_select)>0){
			echo "<option value=''>".label('r_company')."</option>";
			foreach ($quize_select as $key) {
	              if($lang=="thai"){ 
	                $cname = $key['cname_th']!=""?$key['cname_th']:$key['cname_eng'];
	                $cname = $cname!=""?$cname:$key['cname_jp'];
	              }else if($lang=="english"){ 
	                $cname = $key['cname_eng']!=""?$key['cname_eng']:$key['cname_th'];
	                $cname = $cname!=""?$cname:$key['cname_jp'];
	              }else{
	                $cname = $key['cname_jp']!=""?$key['cname_jp']:$key['cname_eng'];
	                $cname = $cname!=""?$cname:$key['cname_th'];
	              }
				echo "<option value='".$key['cos_id']."'>".$cname."</option>";
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function select_course_report(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$com_id = $this->input->post('com_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$quize_select = $this->workgroup->selectcos_report($com_id);
		if(countArray($quize_select)>0){
			echo "<option value=''>".label('r_company')."</option>";
			foreach ($quize_select as $key) {
	              if($lang=="thai"){ 
	                $cname = $key['cname_th']!=""?$key['cname_th']:$key['cname_eng'];
	                $cname = $cname!=""?$cname:$key['cname_jp'];
	              }else if($lang=="english"){ 
	                $cname = $key['cname_eng']!=""?$key['cname_eng']:$key['cname_th'];
	                $cname = $cname!=""?$cname:$key['cname_jp'];
	              }else{
	                $cname = $key['cname_jp']!=""?$key['cname_jp']:$key['cname_eng'];
	                $cname = $cname!=""?$cname:$key['cname_th'];
	              }
				echo "<option value='".$key['cos_id']."'>".$cname."</option>";
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function selectCourseAll(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$com_id = $this->input->post('com_id');
		$this->load->model('Workgroup_model', 'workgroup', false);
		$this->workgroup->loadDB();
		$quize_select = $this->workgroup->selectDropdownCourses();
		if(countArray($quize_select)>0){
			echo "<option value=''>".label('r_company')."</option>";
			foreach ($quize_select as $key) {
	              if ($lang=="thai") {
	                $cname = $key['cname_th']!=""?$key['cname_th']:$key['cname_eng'];
	                $cname = $cname!=""?$cname:$key['cname_jp'];
	              } elseif ($lang=="english") {
	                $cname = $key['cname_eng']!=""?$key['cname_eng']:$key['cname_th'];
	                $cname = $cname!=""?$cname:$key['cname_jp'];
	              } else {
	                $cname = $key['cname_jp']!=""?$key['cname_jp']:$key['cname_eng'];
	                $cname = $cname!=""?$cname:$key['cname_th'];
	              }
				echo "<option value='".$key['cos_id']."'>".$cname."</option>";
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function recheckquestionnaire(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$qn_id = $this->input->post('qn_id');
		$cos_id = $this->input->post('cos_id');
		$cos_lang = isset($_REQUEST['cos_lang'])?explode(',', $_REQUEST['cos_lang']):"";
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		//$condition_select = $this->workgroup->selectQuestionnaire();
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$fetch_cos = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');
		$condition_select = $this->func_query->query_result('lms_questionnaire','','','','com_id="'.$fetch_cos['com_id'].'" and qn_isDelete="0" and qn_status="1"');
		if(countArray($condition_select)>0){
			foreach ($condition_select as $key_qizex => $value_qizex) {
				$qn_lang = explode(',', $value_qizex['qn_lang']);
				if(countArray($qn_lang)>0){
					$numchk = 0;
					for ($i=0; $i < countArray($qn_lang); $i++) { 
						if(isset($qn_lang[$i])&&countArray($cos_lang)>0&&in_array($qn_lang[$i], $cos_lang)){
							$numchk++;
						}
					}
					if($numchk==0){
						unset($condition_select[$key_qizex]);
					}
				}
				if(isset($value_qizex['qn_id'])){
					$fetch_chkques = $this->func_query->numrows('lms_questionnaire_de','','','','qn_id="'.$value_qizex['qn_id'].'" and qnde_isDelete="0"');
					if($fetch_chkques==0){
						unset($condition_select[$key_qizex]);
					}
				}
			}
		}
		if(countArray($condition_select)>0){
			echo "<option value=''>".label('svplease')."</option>";
			foreach ($condition_select as $key) {
				$selected_val = "";
				if($key['qn_id']==$qn_id){
					$selected_val = "selected";
				}
	              if($lang=="thai"){ 
	                $qn_title = $key['qn_title_th']!=""?$key['qn_title_th']:$key['qn_title_eng'];
	                $qn_title = $qn_title!=""?$qn_title:$key['qn_title_jp'];
	              }else if($lang=="english"){ 
	                $qn_title = $key['qn_title_eng']!=""?$key['qn_title_eng']:$key['qn_title_th'];
	                $qn_title = $qn_title!=""?$qn_title:$key['qn_title_jp'];
	              }else{
	                $qn_title = $key['qn_title_jp']!=""?$key['qn_title_jp']:$key['qn_title_eng'];
	                $qn_title = $qn_title!=""?$qn_title:$key['qn_title_th'];
	              }
					echo "<option value='".$key['qn_id']."' ".$selected_val.">".$qn_title."</option>";
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function recheckcosid(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$com_id = $this->input->post('com_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$cos_select = $this->workgroup->selectcos_id($com_id);
		if(countArray($cos_select)>0){
			foreach ($cos_select as $key => $value) {
				$this->db->from('lms_cos_sort');
				$this->db->where('cos_id',$value['id']);
				$query = $this->db->get();
				$fetch = $query->row_array();
				if(countArray($fetch)>0){
					unset($cos_select[$key]);
				}
			}
			echo "<option value=''>".label('svplease')." ".label('ceCname')."</option>";
			foreach ($cos_select as $key) {
				if($lang=="thai"){
					echo "<option value='".$key['id']."' >".$key['cname_th']."</option>";
				}else{
					echo "<option value='".$key['id']."' >".$key['cname_eng']."</option>";
				}
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function rechecklesson(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$les_id = $this->input->post('les_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$condition_select = $this->workgroup->selectfil($les_id);
		if(countArray($condition_select)>0){
			echo "<option value=''>".label('lesson_file')."</option>";
			foreach ($condition_select as $key) {
				if($lang=="thai"){
					echo "<option value='".$key['id']."'>".$key['name_fileth']."</option>";
				}else{
					echo "<option value='".$key['id']."'>".$key['name_fileen']."</option>";
				}
			}
			$this->workgroup->closeDB();
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function recheckqiz_enroll(){
		$lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$cos_id = $this->input->post('cos_id');
		$cos_lang = isset($_REQUEST['cos_lang'])?$_REQUEST['cos_lang']:"";
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->load->model('Function_query_model', 'func_query', FALSE);
		$this->workgroup->loadDB();
		$fetch_qiz = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$cos_id.'" and quiz_isDelete="0" and quiz_lang="'.$cos_lang.'"');
		if(countArray($fetch_qiz)>0){
      		echo "<optgroup label='".label('Chooselang')."'>";
			foreach ($fetch_qiz as $key) {
				echo "<option value='".$key['qiz_id']."'>".$key['quiz_name']."</option>";
			}
			$this->workgroup->closeDB();
      		echo "</optgroup>";
		}else{
			echo "<option value=''>".label('wg_datanotfound')."</option>";
		}
	}

	public function getCompanyForWG(){
		$wg_id = $this->input->post('wg_id');
		$this->load->model('Workgroup_model', 'workgroup', FALSE);
		$this->workgroup->loadDB();
		$fetch_wg = $this->workgroup->getWorkgroup( $wg_id );
		echo $fetch_wg[0]['com_id'];
	}	
}
