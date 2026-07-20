<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LearningMap extends CI_Controller
{

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
    public function index()
    {
        $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
        $this->lang->load($lang, $lang);

        $arr['lang'] = $lang;
        $arr['page'] = "learningMap";

        $this->load->model('User_model', 'login', false);
        if ($this->login->checkSession($arr['page'])) {
            $user = $this->session->userdata('user');
            $emp_c = $user['emp_c'];
            $arr['emp_c'] = $user['emp_c'];
            $arr['role'] = $user['role'];
            
            $this->load->model('LearningMap_model', 'lmap', false);
            $this->lmap->loadDB();
            $lmap = $this->lmap->getPath();
            $this->lmap->closeDB();
            
            $arr['lmap'] = [];
            foreach ($lmap as $f_type) {
                $arr['lmap'][$f_type['f_type']]['f_name'] = $f_type['f_name'];
                $arr['lmap'][$f_type['f_type']]['name'] = $f_type['name'];
                $arr['lmap'][$f_type['f_type']]['last_update'] = $f_type['last_update'];
            }
        
            $this->load->model('Footer_model', 'foot', false);
            $this->foot->loadDB();
            $arr['foote'] = $this->foot->getfooter();
            $this->foot->closeDB();

            $this->load->view('frontend/learningMap', $arr);
        }
    }
    
    public function upload()
    {
        $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
        $this->lang->load($lang, $lang);

        $arr['lang'] = $lang;
        $arr['page'] = "learningMap";

        $this->load->model('User_model', 'login', false);
        if ($this->login->checkSession($arr['page'])) {
            $user = $this->session->userdata('user');
            $emp_c = $user['emp_c'];
            
            if (isset($_FILES['img']) && isset($_FILES['pdf'])) {
                if ($this->security->xss_clean($_FILES['img'], true) === false && $this->security->xss_clean($_FILES['pdf'], true) === false) {
                    $message = "Error can\'t upload files!!!";
                    echo "<script type='text/javascript'>
					            		alert('".$message."');window.location= \"".base_url()."learningMap\";
					            		</script>";
                    return;
                }
            
                $this->load->model('LearningMap_model', 'lmap', false);
                $this->lmap->loadDB();
            
                if (!empty($_FILES['img']['name'])) {
                    $name = $_FILES['img']['name'];
                    $f_name = $this->generate_name($name);
                    $this->uploadFile('img', $f_name);
                    $file_info = array(
                      'f_name'=>$f_name,
                      'name'=>$_FILES['img']['name'],
                      'author'=>$emp_c
                    );
                    $this->lmap->updatePath('img', $file_info);
                }
            
                if ($_FILES['pdf']['type'] === 'application/pdf') {
                    $name = $_FILES['pdf']['name'];
                    $f_name = $this->generate_name($name);
                    $this->uploadFile('pdf', $f_name);
                    $file_info = array(
                      'f_name'=>$f_name,
                      'name'=>$name,
                      'author'=>$emp_c
                    );
                    $this->lmap->updatePath('pdf', $file_info);
                } elseif (is_null($_FILES['pdf']['name']) || empty($_FILES['pdf']['name'])) {
                } else {
                    $message = "Error can\'t upload PDF file!!!";
                    echo "<script type='text/javascript'>
					            		alert('".$message."');window.location= \"".base_url()."learningMap\";
					            		</script>";
                    return;
                }
                $this->lmap->closeDB();
            }
        
            redirect(base_url().'learningMap', 'refresh');
        }
    }
    
    private function uploadFile($f_type, $name)
    {
        $inputSourcePath = $_FILES[$f_type]['tmp_name'];
        $inputTargetPath = ROOT_DIR."uploads/learningMap/".$name;
        $inputFileName = $inputTargetPath;
        audit_move_uploaded_file($inputSourcePath, $inputTargetPath);
    }
    
    private function generate_name($name) {
      $type = explode('.', $name);
      $name = $name.date('His');
      return md5($name).'.'.$type[1];
    }
}
