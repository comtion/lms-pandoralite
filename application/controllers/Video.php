<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
        $this->lang->load($lang, $lang);
        $this->load->model('Function_query_model', 'funcQuery', FALSE);
        $this->funcQuery->loadDB();
    }

    public function queryMedTc() {
		$sess = $this->session->userdata("user");
        $med_id = isset($_GET['med_id']) ? $_GET['med_id'] : '';
        $cosen_id = isset($_GET['cosen_id']) ? $_GET['cosen_id'] : '';
        $output = array(
            'volume' => 0.5
        );
        $fetchData = $this->funcQuery->query_row('lms_med_tc', '', '', '', 'med_id = "'.$med_id.'" and cosen_id = "'.$cosen_id.'"');
        if (isset($fetchData)) {
            $output['volume'] = isset($fetchData['medtc_volume']) && $fetchData['medtc_volume'] != "" ? intval($fetchData['medtc_volume']) / 100 : 0.5;
        }

        echo json_encode($output);
    }

    public function updateVolume() {
		$sess = $this->session->userdata("user");
        $med_id = isset($_POST['med_id']) ? $_POST['med_id'] : '';
        $cosen_id = isset($_POST['cosen_id']) ? $_POST['cosen_id'] : '';
        $medtc_volume = isset($_POST['medtc_volume']) ? $_POST['medtc_volume'] : '';

        $output = array(
            'status' => 0
        );

        $fetchData = $this->funcQuery->query_row('lms_med_tc', '', '', '', 'med_id = "'.$med_id.'" and cosen_id = "'.$cosen_id.'"');
        if (isset($fetchData)) {
            $arrayUpdate = array(
                'medtc_volume' => ($medtc_volume * 100),
            );
            $this->db->where('med_id = "'.$med_id.'" and cosen_id = "'.$cosen_id.'"');
            $this->db->update('lms_med_tc', $arrayUpdate);
        } else {
            $arrInsert = array(
                'med_id' => $med_id,
                'emp_id' => $sess['emp_id'],
                'cosen_id' => $cosen_id,
                'medtc_volume' => ($medtc_volume * 100),
            );
            $this->db->insert('lms_med_tc', $arrInsert);
        }
        $output['status'] = 2;

        echo json_encode($output);
    }
}
?>