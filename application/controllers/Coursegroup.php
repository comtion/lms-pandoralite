<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coursegroup extends CI_Controller {

	/**
	* Index Page for this controller.
	*
	* Maps to the following URL
	* 		http://example.com/index.php/welcome
	*	- or -
	* 		http://example.com/index.php/welcome/index
	*	- or -
	* Since this controller is set as the default controller in
	* config/routes.php, it's displayed at http://example.com/
	*
	* So any other public methods not prefixed with an underscore will
	* map to /index.php/welcome/<method_name>
	* @see https://codeigniter.com/user_guide/general/urls.html
	*/
  public function index(){}

  public function loadCourseGroup( $wcode = "" ){
    //Check user session : Redirect to login page with destination url when it false;
    $arr = array();
    $arr['page'] = "coursegroup/loadCourseGroup";
    $this->load->model('User_model', 'login', TRUE);
    if($this->login->checkSession($arr['page'])){
		$user = $this->session->userdata('user');
		$arr['emp_c'] = $user['emp_c'];
		$arr['com_admin'] = $user['com_admin'];
		$arr['user'] = $user;
		$arr['com_id'] = $user['com_id'];
    }
    // Load and set default language;
    $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
	$this->lang->load($lang,$lang);
	$arr['lang'] = $lang;

    //Load all work groups data
    $this->load->model('Workgroup_model', 'workgroup', TRUE);
	$this->load->model('Manage_model', 'manage', FALSE);
    $this->load->model('Coursegroup_model', 'coursegroup', TRUE);
    $this->load->model('Log_model', 'lg', FALSE);
    $this->lg->loadDB();
    $this->coursegroup->loadDB();
	$this->manage->loadDB();
    $this->workgroup->loadDB();

    $arr['wcode'] = $wcode;
    $arr['workgroups'] = $this->workgroup->getAllWorkgroup( $wcode );
	$arr['rechk_permission'] = $this->workgroup->rechk_permission_wg();
    $this->lg->record('coursegroup', 'Enter to all coursegroup page ');
	$arr['company_select'] = $this->manage->getCompany($wcode);
        	$arr['arr_permission'] = $this->manage->chk_permission_page();
			$arr['btn_add'] = $this->manage->chk_permission($arr['page'],'ru_add');
			$arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
			$arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
			$arr['btn_view'] = $this->manage->chk_permission($arr['page'],'ru_view');
			if($arr['btn_view']!="1"){
				redirect(base_url().'dashboard') ;
			}
    $arr['coursegroups'] = $this->coursegroup->getAllCoursegroup( $wcode);
	$arr['rechk_permission_cg'] = $this->coursegroup->rechk_permission_cg();
	if($user['Is_admin']=="0"){
		foreach ($arr['workgroups'] as $key_wg => $value_wg) {
			if(!in_array($value_wg['id'], $arr['rechk_permission'])){
				unset($arr['workgroups'][$key_wg]);
			}
		}
		foreach ($arr['coursegroups'] as $key_cg => $value_cg) {
			if(!in_array($value_cg['id'], $arr['rechk_permission_cg'])){
				unset($arr['coursegroups'][$key_cg]);
			}
		}
	}

		$arr['main_menu'] = $this->manage->checkmenu();
		$arr['title'] = $this->manage->get_namemenu($arr['page']);
		$arr['title_main'] = $this->manage->get_namemenu_sub($arr['page']);
		$arr['submenu'] = array();
		$arr['submenu_b'] = array();
		foreach ($arr['main_menu'] as $key_mainmenu => $value_mainmenu) {
			$li_arr_sub = $this->manage->checkmenu_sub($value_mainmenu['mu_id']);
			if(countArray($li_arr_sub)){
				$arr['submenu'][$value_mainmenu['mu_id']] = $li_arr_sub;
				foreach ($li_arr_sub as $key_sub => $value_sub) {
					$li_arr_sub_b = $this->manage->checkmenu_sub($value_sub['mu_id']);
					if(countArray($li_arr_sub_b)>0){
						$arr['submenu_b'][$value_sub['mu_id']] = $li_arr_sub_b;
					}
				}
			}
		}
	$this->manage->closeDB();
    $this->workgroup->closeDB();
    $this->coursegroup->closeDB();
    $this->lg->closeDB();



    //Record Log activity
    //Load footer
    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $this->foot->closeDB();

    //Send to front view
    $this->load->view('frontend/coursegroupAvai', $arr );
  }


  public function load_coursegroup_data(){
  		$txt_search = isset($_REQUEST['value'])? $_REQUEST['value']:"";
  		$wcode = isset($_REQUEST['wcode'])? $_REQUEST['wcode']:"";

    	$page = "coursegroup/loadCourseGroup";
	    $this->load->model('User_model', 'login', TRUE);
	    // Load and set default language;
	    $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');

	    //Load all work groups data
	    $this->load->model('Workgroup_model', 'workgroup', TRUE);
		$this->load->model('Manage_model', 'manage', FALSE);
	    $this->load->model('Coursegroup_model', 'coursegroup', TRUE);
	    $this->load->model('Log_model', 'lg', FALSE);
	    $this->lg->loadDB();
	    $this->coursegroup->loadDB();
		$this->manage->loadDB();
	    $this->workgroup->loadDB();

		$company_select = $this->manage->getCompany();

        $arr_permission = $this->manage->chk_permission_page();
		$btn_update = $this->manage->chk_permission($page,'ru_edit');
		$btn_delete = $this->manage->chk_permission($page,'ru_del');
	    //Load all work groups data

	    $workgroups = $this->workgroup->getAllWorkgroup( $wcode );
		$rechk_permission = $this->workgroup->rechk_permission_wg();
	    $this->lg->record('coursegroup', 'Enter to all coursegroup page ');
		$company_select = $this->manage->getCompany($wcode);
	        	$arr_permission = $this->manage->chk_permission_page();
				$btn_add = $this->manage->chk_permission($page,'ru_add');
				$btn_update = $this->manage->chk_permission($page,'ru_edit');
				$btn_delete = $this->manage->chk_permission($page,'ru_del');
				$btn_view = $this->manage->chk_permission($page,'ru_view');
				if($btn_view!="1"){
					redirect(base_url().'dashboard') ;
				}
	    $coursegroups = $this->coursegroup->getAllCoursegroup( $wcode,$txt_search);
		$rechk_permission_cg = $this->coursegroup->rechk_permission_cg();
		if($user['Is_admin']=="0"){
			foreach ($workgroups as $key_wg => $value_wg) {
				if(!in_array($value_wg['id'], $rechk_permission)){
					unset($workgroups[$key_wg]);
				}
			}
			foreach ($coursegroups as $key_cg => $value_cg) {
				if(!in_array($value_cg['id'], $rechk_permission_cg)){
					unset($coursegroups[$key_cg]);
				}
			}
		}
		$arrwg_id = array();
		foreach ($coursegroups as $cg) {
			if(!in_array($cg['wg_id'],$arrwg_id)){
				array_push($arrwg_id, $cg['wg_id']);
			}
		}
		foreach ($workgroups as $key_wg => $value_wg) {
			if(!in_array($value_wg['id'],$arrwg_id)){
				unset($workgroups[$key_wg]);
			}
		}

		if($user['com_admin']!="CUSTOMER"&&$user['Is_admin']!="0"){ 
			foreach($company_select as $company ) { ?>
                <div class="card">
                        <div class="card-header" align="center"><?php if($lang=="thai"){ echo $company['com_name_th']; }else{ echo $company['com_name_en']; } ?></div>
                        <div class="card-body">
                        <?php $num = 0;
                            foreach($workgroups as $workgroup ) {
                                if($company['com_id']==$workgroup['com_id']){ $num++; ?>
                            <div class="card">
                                <div class="card-header" align="left"><?php if($lang=="thai"){echo $workgroup['wtitle_th'];}else{echo $workgroup['wtitle_en'];} ?></div>
                                <div class="card-body slimtest1" align="left">
                                    <div class="row">
                                    <?php   $num_count = 0;
                                    foreach ($coursegroups as $cg) {
                                        if($cg['wg_id']==$workgroup['id']){ $num_count++;?>
                                        <div class="col-md-3">
                                            <div class="card" style="height: 400px">
                                                <img class="card-img-top img-responsive" width="100%" style="max-height: 140px" src="<?php echo REAL_PATH;?>/uploads/course_group/<?php if($cg['cgthumb']!=""){echo $cg['cgthumb'];}else{echo "default_image.jpg";} ?>" onerror="this.src='<?php echo REAL_PATH;?>/uploads/course/default_profile.jpg'" alt="">
                                                <div class="card-body slimtest1">
                                                    <h4 class="card-title" style="font-family: 'Prompt', sans-serif;"><?php if($lang=="thai"){echo $cg['cgtitle_th'];}else{echo $cg['cgtitle_en'];} ?></h4>
                                                    <?php if($lang=="thai"){echo $cg['cgdesc_th'];}else{echo $cg['cgdesc_en'];} ?>
                                                </div>
                                                <div class="card-footer" align="center">
                                                    <a href="<?php echo base_url().'course/available/'.$cg['id'].'/'.$cg['wg_id'];?>" style="font-size: 14px" class="btn btn-outline-info  btn-block"><i class="mdi mdi-information-outline"></i> <?php echo label('cgchoose'); ?></a>
                                                    <?php if($btn_update=="1"){ ?>
                                                        <a id="<?php echo $cg['id']; ?>" style="font-size: 14px" class="update btn  btn-block btn-outline-warning">
                                                            <i class="mdi mdi-lead-pencil"></i> <?php echo label('edit'); ?>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if($btn_delete=="1"){ ?>
                                                        <a id="<?php echo $cg['id']; ?>" style="font-size: 14px" class="delete btn  btn-block btn-outline-danger">
                                                            <i class="mdi mdi-delete-forever"></i> <?php echo label('delete'); ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                <?php   }
                                    } ?>
                                    </div>
                                    <?php if($num_count==0){?>
                                        <h4 align="center"><i class="mdi mdi-exclamation"></i> <?php echo label('wg_datanotfound');?></h4>
                                    <?php } ?>
                                </div>
                            </div>

                          <?php } 
                            }
                            if($num==0){?>
                                <h4 align="center"><i class="mdi mdi-exclamation"></i> <?php echo label('wg_datanotfound');?></h4>
                      <?php } ?>
                        </div>
                 </div>
	  <?php }
		}else{
			$num = 0;
            foreach($workgroups as $workgroup ) { $num++; ?>
            <div class="card">
                <div class="card-header" align="left"><?php if($lang=="thai"){echo $workgroup['wtitle_th'];}else{echo $workgroup['wtitle_en'];} ?></div>
                    <div class="card-body slimtest1" align="left">
                        <div class="row">
                        <?php   $num_count = 0; 
                        foreach ($coursegroups as $cg) {
                            if($cg['wg_id']==$workgroup['id']){ $num_count++; ?>
                            <div class="col-md-3">
                                <div class="card" style="height: 400px">
                                    <img class="card-img-top img-responsive" width="100%" style="max-height: 140px" src="<?php echo REAL_PATH;?>/uploads/course_group/<?php if($cg['cgthumb']!=""){echo $cg['cgthumb'];}else{echo "default_image.jpg";} ?>" alt="">
                                    <div class="card-body slimtest1">
                                        <h4 class="card-title"><?php if($lang=="thai"){echo $cg['cgtitle_th'];}else{echo $cg['cgtitle_en'];} ?></h4>
                                        <?php if($lang=="thai"){echo $cg['cgdesc_th'];}else{echo $cg['cgdesc_en'];} ?>
                                    </div>
                                    
                                    <div class="card-footer" align="center">
                                        <a href="<?php echo base_url().'course/available/'.$cg['id'].'/'.$cg['wg_id'];?>" style="font-size: 14px" class="btn  btn-block btn-outline-info"><i class="mdi mdi-information-outline"></i> <?php echo label('cgchoose'); ?></a>
                                        <?php if($btn_update=="1"){ ?>
                                            <a id="<?php echo $cg['id']; ?>" style="font-size: 14px" class="update btn  btn-block btn-outline-warning">
                                                <i class="mdi mdi-lead-pencil"></i> <?php echo label('edit'); ?>
                                            </a>
                                        <?php } ?>
                                        <?php if($btn_delete=="1"){ ?>
                                            <a id="<?php echo $cg['id']; ?>" style="font-size: 14px" class="delete btn  btn-block btn-outline-danger">
                                                <i class="mdi mdi-delete-forever"></i> <?php echo label('delete'); ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                    <?php   }
                        } ?>
                        </div>
                        <?php if($num_count==0){?>
                            <h4 align="center"><i class="mdi mdi-exclamation"></i> <?php echo label('wg_datanotfound');?></h4>
                        <?php } ?>
                    </div>
            </div>
      <?php }
      		if($num==0){ ?>
                <h4 align="center"><i class="mdi mdi-exclamation"></i> <?php echo label('wg_datanotfound');?></h4>
      <?php }
		}
  }
  public function insert_coursegroup(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$user = $this->session->userdata('user');
		$this->load->model('Coursegroup_model', 'coursegroup', FALSE);
		$this->coursegroup->loadDB();
		if(countArray($_REQUEST)>0){
			if(isset($_FILES['image'])&&$_FILES['image']!=""){
				if( isset( $_FILES['image']) ){
					$imageSourcePath = $_FILES['image']['tmp_name'];
					$imageTargetPath = ROOT_DIR."uploads/course_group/".$_REQUEST['cgcode']."_".$_REQUEST['wg_id']."_".date('YmdHis').".jpg";
					if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
						$_REQUEST['image'] = $_REQUEST['cgcode']."_".$_REQUEST['wg_id']."_".date('YmdHis').".jpg" ;
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
				'cgcode' => $_REQUEST['cgcode'],
				'cgtitle_th' => $_REQUEST['cgtitle_th'],
				'cgdesc_th' => $_REQUEST['cgdesc_th'],
				'cgtitle_en' => $_REQUEST['cgtitle_en'],
				'cgdesc_en' => $_REQUEST['cgdesc_en'],
				'wg_id' => $_REQUEST['wg_id'],
				'cgthumb' => $_REQUEST['image'],
				'u_date' => date("Y-m-d H:i"),
				'u_by' => $user['emp_c']
			);
			if($_REQUEST['operation']=="Add"){
				$msg = $this->coursegroup->create_coursegroup($data);
			}else{
				$msg = $this->coursegroup->update_coursegroup($data,$_REQUEST['id']);
			}
		}
		echo $msg;
	}


	public function update_coursegroup_data(){
		$lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
		$this->lang->load($lang,$lang);
		$this->load->model('Manage_model', 'manage', FALSE);
		$this->manage->loadDB();
		if(countArray($_REQUEST)>0){
			$result = $this->manage->query_data_onupdate($_REQUEST['id_update'],'lms_cog','id');
			echo json_encode($result);
		}
	}

	public function delete_coursegroup_data(){
	    $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
	    $this->lang->load($lang,$lang);
	    $user = $this->session->userdata('user');
	    $this->load->model('Coursegroup_model', 'coursegroup', FALSE);
	    $this->coursegroup->loadDB();
	    $msg="";
	    if(countArray($_REQUEST)>0){
	      if(countArray($this->coursegroup->rechkUsecoursegroup($_REQUEST['id_delete']))>0){
	        $msg = "1";
	      }else{
	        $data = array(
	          'lms_cog.cg_status' => '0',
	          'lms_cog.u_date' => date("Y-m-d H:i"),
	          'lms_cog.u_by' => $user['emp_c']
	        );
	        $msg = $this->coursegroup->update_coursegroup($data,$_REQUEST['id_delete']);
	      }
	    }
	    echo $msg;
	}

}
