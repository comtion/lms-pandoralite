<?php
/**
 * ark Admin Panel for Codeigniter
 * Author: Abhishek R. Kaushik
 * downloaded from http://devzone.co.in
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

    public function index() {
        $this->session->set_userdata('upload_image_file_manager',true);
        $arr['page']='dash';
        $this->load->model('admin/Langtab_model','langtab',TRUE);
        $arr['langs'] = $this->langtab->get_all();

        $this->load->model('admin/Cat_model','cat',TRUE);
        $arr['cats'] = $this->cat->get_all();

        //print_r( $arr );
        //$this->load->view('admin/ckeditor');
        $this->load->view('admin/vwDashboard',$arr);
    }
    public function add( $page = "" ){
      $this->session->set_userdata('upload_image_file_manager',true);
        $arr['page']= $page ;
        $this->load->model('admin/Langtab_model','langtab',TRUE);
        $arr['langs'] = $this->langtab->get_all();

        $this->load->model('admin/News_model','news',TRUE);
        $arr['cats'] = $this->news->get_all_cat();
        $arr['lang_tab'] = 'thailand';
        $arr['code'] = $this->news->get_code();

        //print_r( $arr );
        //$this->load->view('admin/ckeditor');
        $this->load->view('admin/vwNewsForm',$arr);
    }
    public function edit( $id, $code, $page = "" ){
      $this->session->set_userdata('upload_image_file_manager',true);
        $arr['page'] = $page;
        $this->load->model('admin/Langtab_model','langtab',TRUE);
        $arr['langs'] = $this->langtab->get_all();

        $this->load->model('admin/News_model','news',TRUE);
        $arr['cats'] = $this->news->get_all_cat();

        $arr['news'] = $this->news->get_detail( $id, $code );
        $arr['lang_tab'] = $this->news->get_lang_tab( $id );
        $arr['code'] = $code;

        //print_r( $arr );
        //$this->load->view('admin/ckeditor');
        $this->load->view('admin/vwNewsForm',$arr);
    }
    public function lists( $id,$title ){
        $this->session->set_userdata('page',$title);
        $arr['page'] = $title ;
        $this->load->model('admin/News_model','news',TRUE);
        $arr['lists'] = $this->news->get_list( $id );

        $this->load->view('admin/vwNewsList',$arr);
    }
    public function saveForm(){
      //echo $_POST['cp_tag'];
      $page = isset( $_POST['page'] ) ? $_POST['page'] : '' ;
      $news = array();
      date_default_timezone_set("Asia/Bangkok");
      $id = isset( $_POST['id'] ) ? $_POST['id'] : '' ;
      $cp_code = isset( $_POST['code'] ) ? $_POST['code'] : '' ;

      if( isset( $_FILES['cp_titleimg']) ){
          $imageFile = $_FILES['cp_titleimg'];

          $imageSourcePath = $imageFile['tmp_name'];
          $imageTargetPath = ROOT_DIR."uploads/".$imageFile['name'];

            if( audit_move_uploaded_file( $imageSourcePath,$imageTargetPath ) ){
              $news['cp_titleimg'] = $imageFile['name'] ;
            }
        }//else{ $news['cp_titleimage'] = ''; }

        //print_r( $_POST['cp_category'] );

        $news['cp_category'] = isset( $_POST['cp_category'] ) ? implode(',',$_POST['cp_category']) : '' ;
        //echo $news['cp_category'];
        $news['cp_by'] = isset( $_POST['cp_by'] ) ? $_POST['cp_by'] : '' ;
        $news['cp_titlehead'] = isset( $_POST['cp_titlehead'] ) ? $_POST['cp_titlehead'] : '' ;
        $news['cp_titletext'] = isset( $_POST['cp_titletext'] ) ? $_POST['cp_titletext'] : '' ;
        $news['cp_talkby'] = isset( $_POST['cp_talkby'] ) ? $_POST['cp_talkby'] : '' ;
        $news['cp_content'] = isset( $_POST['cp_content'] ) ? $_POST['cp_content'] : '' ;
        $news['cp_postby'] = 'ผู้ดูแลระบบ' ;
        $news['cp_tag'] = isset( $_POST['cp_tag'] ) ? $_POST['cp_tag'] : '' ;
        $news['cp_video'] = isset( $_POST['cp_video'] ) ? $_POST['cp_video'] : '' ;
        $news['cp_slidetop'] = isset( $_POST['cp_slidetop'] ) ? $_POST['cp_slidetop'] : '' ;
        $news['cp_onoff'] = isset( $_POST['cp_onoff'] ) ? $_POST['cp_onoff'] : '' ;
        $news['cp_sticky'] = isset( $_POST['cp_sticky'] ) ? $_POST['cp_sticky'] : '' ;

        if( $id == "" ){
          $news['cp_code'] = $cp_code ;
          $news['lang'] = isset( $_POST['lang'] ) ? $_POST['lang'] : '' ;
          //$culture['c_by'] = $this->session->userdata('id');
          $news['cp_lastupdate'] = date('Y-m-d H:i') ;

          $this->db->set($news);
          $id = $this->db->insert('tbl_news');

          $query = "select max(cpid) as cpid from tbl_news ";
          $result = $this->db->query( $query );
          $ar_data =  $result->result_array();
          //$code = intval(str_replace('news','', $ar_data[0]['code'])) + 1;
          $id = $ar_data[0]['cpid'];
        }else{
          $news['cp_edittime'] = date('Y-m-d H:i') ;
          $news['cp_editby'] = 'ผู้ดูแลระบบ' ;
          $this->db->where('cpid', $id);
          $this->db->update('tbl_news', $news);
        }
        redirect(base_url().'admin/news/edit/'.$id.'/'.$cp_code.'/'.$page);

    }//end save form

    public function deleteNews( $id ){
      $arr = array();
  		$this->load->model('admin/News_model','news',TRUE);
  		$this->news->deleteNews( $id );

  		$arr['rs'] = true;
      $arr['id'] = $id;
  		echo json_encode($arr);
    }

    /*public function update_code()
        {
          $query = "select * from tbl_news";
          $result = $this->db->query( $query );
          $ar_data =  $result->result_array();

          foreach( $ar_data as $data ){
            //$code_data = array();
            //$query2 = "select max(cp_code) as code from tbl_news ";
            //$result2 = $this->db->query( $query2 );
            //$code_data =  $result2->result_array();
            //print_r($code_data);
            //$code = intval($code_data[0]['code']) + 1;
            //$code = intval(str_replace('news','', $code_data[0]['cp_code'])) + 1;

            //echo $code.'  ';
            $query1 = "update tbl_news set cp_code = '".$data['cpid']."' where cpid = '".$data['cpid']."'";
            $result1 = $this->db->query( $query1);

          }
        }*/
        public function update_code()
            {
              $query = "select * from tbl_news";
              $result = $this->db->query( $query );
              $ar_data =  $result->result_array();

              foreach( $ar_data as $data ){
                $content = str_replace('src="/assets/','src="/cpenews/assets/',$data['cp_content']);
                //$code_data = array();
                //$query2 = "select max(cp_code) as code from tbl_news ";
                //$result2 = $this->db->query( $query2 );
                //$code_data =  $result2->result_array();
                //print_r($code_data);
                //$code = intval($code_data[0]['code']) + 1;
                //$code = intval(str_replace('news','', $code_data[0]['cp_code'])) + 1;

                //echo $code.'  ';
                $query1 = "update tbl_news set cp_content = '".$content."' where cpid = '".$data['cpid']."'";
                $result1 = $this->db->query( $query1);

              }
            }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
