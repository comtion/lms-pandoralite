<?php header("Content-Type: text/html; charset=utf-8"); ?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function getCourses(){
  		date_default_timezone_set("Asia/Bangkok");
		$this->load->model('Course_model', 'course', FALSE);
		$this->course->loadDB();

		$arr['course'] = $this->course->query_data_onupdate_result('1', 'lms_cos','status');
		foreach ($arr['course'] as $key => $value) {
			$this->db->where('cos_id',$value['id']);
			$this->db->where('status','1');
			$this->db->from('lms_cos_detail');
			$query = $this->db->get();
			$num = $query->num_rows();
			if($num>0){
				$fetch = $query->row_array();
				$date_start = date('Y-m-d',strtotime($fetch['date_start']));
          		$date_end = $fetch['date_end'];
			}else{
				$date_start = "";
        		$date_end = "";
			}
			$arr['course'][$key]['date_start'] = $date_start;
			$arr['course'][$key]['date_end'] = $date_end;

		}
		$this->load->view('frontend/getCourses', $arr );
	}

}
