<?php
/**
 * ark Admin Panel for Codeigniter
 * Author: Abhishek R. Kaushik
 * downloaded from http://devzone.co.in
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Links extends CI_Controller {

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

        $this->load->model('admin/Links_model','news',TRUE);
        $arr['cats'] = $this->news->get_all_cat();
        $arr['lang_tab'] = 'thailand';
        $arr['code'] = $this->news->get_code();

        //print_r( $arr );
        //$this->load->view('admin/ckeditor');
        $this->load->view('admin/vwLinksForm',$arr);
    }
    public function edit( $id, $code, $page = "" ){
      $this->session->set_userdata('upload_image_file_manager',true);
        $arr['page'] = $page;
        $this->load->model('admin/Langtab_model','langtab',TRUE);
        $arr['langs'] = $this->langtab->get_all();

        $this->load->model('admin/Links_model','news',TRUE);
        $arr['cats'] = $this->news->get_all_cat();

        $arr['news'] = $this->news->get_detail( $id, $code );
        $arr['lang_tab'] = $this->news->get_lang_tab( $id );
        $arr['code'] = $code;

        //print_r( $arr );
        //$this->load->view('admin/ckeditor');
        $this->load->view('admin/vwLinksForm',$arr);
    }
    public function lists(){
        $this->session->set_userdata('page','link');
        $arr['page'] = 'link' ;
        $this->load->model('admin/Links_model','news',TRUE);
        $arr['lists'] = $this->news->get_list();

        $this->load->view('admin/vwLinksList',$arr);
    }
    public function saveForm(){
      //echo $_POST['cp_tag'];
      $page = isset( $_POST['page'] ) ? $_POST['page'] : '' ;
      $news = array();
      date_default_timezone_set("Asia/Bangkok");
      $id = isset( $_POST['id'] ) ? $_POST['id'] : '' ;
      $code = isset( $_POST['code'] ) ? $_POST['code'] : '' ;

        $news['link_group'] = isset( $_POST['link_group'] ) ? $_POST['link_group'] : '' ;
        $news['name'] = isset( $_POST['name_th'] ) ? $_POST['name_th'] : '' ;
        $news['descirption'] = isset( $_POST['descirption'] ) ? $_POST['descirption'] : '' ;
        $news['link'] = isset( $_POST['link'] ) ? $_POST['link'] : '' ;

        echo $news['name'];

        if( $id == "" ){
          $news['code'] = $code ;
          $news['lang'] = isset( $_POST['lang'] ) ? $_POST['lang'] : '' ;
          //$culture['c_by'] = $this->session->userdata('id');
          $news['c_date'] = date('Y-m-d H:i') ;

          $this->db->set($news);
          $id = $this->db->insert('tbl_link_details');

          $query = "select max(id) as id from tbl_link_details ";
          $result = $this->db->query( $query );
          $ar_data =  $result->result_array();
          //$code = intval(str_replace('news','', $ar_data[0]['code'])) + 1;
          $id = $ar_data[0]['id'];
        }else{
          $news['u_date'] = date('Y-m-d H:i') ;
          $this->db->where('id', $id);
          $this->db->update('tbl_link_details', $news);
        }
        redirect(base_url().'admin/links/edit/'.$id.'/'.$code.'/'.$page);

    }//end save form

    public function deleteNews( $id ){
      $arr = array();
  		$this->load->model('admin/Links_model','news',TRUE);
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
