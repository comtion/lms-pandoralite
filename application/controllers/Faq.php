<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

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
		$arr['page'] = "faq";
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    $emp_c = $arr['emp_c'];
    $this->load->model('Log_model', 'recs', FALSE);
    $this->recs->loadDB();
    $this->load->model('Faq_model', 'faq', FALSE);
    $this->faq->loadDB();
    $arr['faqtit'] = $this->faq->getTitle($lang);
    $arr['fQA'] = $this->faq->getQA($lang);
		$arr['manage'] = "";
		$ft = $this->input->post('deleteFT');
		if (isset($ft)){
			$this->faq->deleteTitle($ft);
			$this->recs->record('faq', 'Deleted FAQ title.');
			$this->recs->closeDB();
			$this->faq->closeDB();
			echo "<meta http-equiv='refresh' content='0'>" ;
			echo"<script language='JavaScript'>";
			echo"alert('Successfully Deleted');";
			echo"window.location='".base_url()."faq';";
			echo"</script>";}

		$buttAT = $this->input->post('addtt');
		$ftitle = $this->security->xss_clean($this->input->post('ftitle'));
	   if (isset($buttAT)){
	     $data = array(
	  		'title' => $ftitle,
				'emp_c' => $emp_c,
	  		'lang' => $lang);
	     $this->faq->saveTitle($data);
	     $this->recs->record('faq', 'Created FAQ title.');
	     $this->recs->closeDB();
	     $this->faq->closeDB();
	     echo "<meta http-equiv='refresh' content='0'>" ;
	     echo"<script language='JavaScript'>";
	     echo"alert('Successfully Created');";
	     echo"window.location='".base_url()."faq';";
	     echo"</script>";}

		$ffqa = $this->input->post('deleteFQA');
		if (isset($ffqa)){
			$this->faq->deletefQA($ffqa);
			$this->recs->record('faq', 'Deleted FAQ.');
			$this->recs->closeDB();
			$this->faq->closeDB();
			echo "<meta http-equiv='refresh' content='0'>" ;
			echo"<script language='JavaScript'>";
			echo"alert('Successfully Deleted');";
			echo"window.location='".base_url()."faq';";
			echo"</script>";}

		$manFQA = $this->input->post('manageFQA');
		if (isset($manFQA)){
			$arr['value'] = $manFQA;
			$arr['manage'] = "EditFQA";
		}
		$efq = $this->security->xss_clean($this->input->post('sFQ'));
		$nefq = $this->security->xss_clean($this->input->post('FQF'));
		$nefa = $this->security->xss_clean($this->input->post('FAF'));
		$butteFQ = $this->input->post('eFQ');
		if (isset($butteFQ)){
			$this->faq->editFAQ($efq,$nefq,$nefa);
			$this->recs->record('faq', 'Edited FAQ.');
			$this->recs->closeDB();
			$this->faq->closeDB();
			echo "<meta http-equiv='refresh' content='0'>" ;
			echo"<script language='JavaScript'>";
			echo"alert('Successfully Edited');";
			echo"window.location='".base_url()."faq';";
			echo"</script>";}

		$manT = $this->input->post('manageT');
		if (isset($manT)){
			$arr['value'] = $manT;
			$arr['manage'] = "EditTitle";
		}
		$eft = $this->security->xss_clean($this->input->post('editFT'));
		$neft = $this->security->xss_clean($this->input->post('neditFT'));
		$butteft = $this->input->post('editFTB');
		if (isset($butteft)){
			$this->faq->editTitle($eft,$neft,$lang);
			$this->recs->record('faq', 'Edited FAQ title.');
			$this->recs->closeDB();
			$this->faq->closeDB();
			echo "<meta http-equiv='refresh' content='0'>" ;
			echo"<script language='JavaScript'>";
			echo"alert('Successfully Edited');";
			echo"window.location='".base_url()."faq';";
			echo"</script>";}

			$addF = $this->input->post('addFAQ');
			if (isset($addF)){
				$arr['value'] = $addF;
				$arr['manage'] = "CreateFQA";
			}
			$buttAFAQ = $this->input->post('addfaq');
			$fselectft = $this->security->xss_clean($this->input->post('selectFT'));
			$fquestion = $this->security->xss_clean($this->input->post('fquestion'));
			$fanswer = $this->security->xss_clean($this->input->post('fanswer'));
			if (isset($buttAFAQ)){
				$this->faq->saveFAQ($fquestion,$fanswer,$fselectft,$lang,$emp_c);
				$this->recs->record('faq', 'Created FAQ.');
				$this->recs->closeDB();
				$this->faq->closeDB();
				echo "<meta http-equiv='refresh' content='0'>" ;
				echo"<script language='JavaScript'>";
				echo"alert('Successfully Created');";
				echo"window.location='".base_url()."faq';";
				echo"</script>";}

				$this->load->model('Footer_model', 'foot', FALSE);
				$this->foot->loadDB();
				$arr['foote'] = $this->foot->getfooter();
				$this->foot->closeDB();

     $this->recs->closeDB();
     $this->faq->closeDB();
		 $this->load->view('frontend/faq', $arr );
	}

}
