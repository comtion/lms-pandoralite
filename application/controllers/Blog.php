<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

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

  public function all(){
    $arr['page'] = 'blog/all';
    $this->load->model('User_model', 'login', TRUE);
    !$this->login->checkSession($arr['page']) ? : $arr['page'];
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;
    $this->load->model('Blog_model', 'blg', FALSE);
    $this->blg->loadDB();
    $this->load->model('Log_model', 'recs', FALSE);
    $this->recs->loadDB();
    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $this->load->model('User_model', 'user', FALSE);
    $this->user->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $arr['blogs'] = $this->blg->getBData($sess['role'],$lang);

    $manBH = $this->input->post('hidePG');
    if (isset($manBH)){
      $this->blg->sethide($manBH,"no");
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Set Hide');";
      echo"window.location='".base_url()."blog/all';";
      echo"</script>";
    }

    $manBUH = $this->input->post('unhidePG');
    if (isset($manBUH)){
      $this->blg->sethide($manBUH,"yes");
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Set Show');";
      echo"window.location='".base_url()."blog/all';";
      echo"</script>";
    }

    $buttdep = $this->input->post('deletePG');
    if (isset($buttdep)){
      $this->blg->deleteB($buttdep);
      $this->recs->record('blog', 'Deleted Blog.');
      $this->recs->closeDB();
      $this->user->closeDB();
      $this->blg->closeDB();
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Deleted');";
      echo"window.location='".base_url()."blog/all';";
      echo"</script>";
    }

    $createBl = $this->input->post('createBl');
    if (isset($createBl)){
      $this->create();
      redirect(base_url().'blog/create');
    }


    $beid = $this->input->post('editPG');
    if (isset($beid)){
      $this->edit($beid);
      redirect(base_url().'blog/edit/'.$beid);
    }

    $this->blg->closeDB();
    $this->user->closeDB();
    $this->foot->closeDB();
    $this->load->view('frontend/bloglist', $arr );
  }

//!!!!!!!!!!

  public function my(){
    $arr['page'] = 'blog/my';
    $this->load->model('User_model', 'login', TRUE);
    !$this->login->checkSession($arr['page']) ? : $arr['page'];
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;
    $this->load->model('Blog_model', 'blg', FALSE);
    $this->blg->loadDB();
    $this->load->model('Log_model', 'recs', FALSE);
    $this->recs->loadDB();
    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $this->load->model('User_model', 'user', FALSE);
    $this->user->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $arr['blogs'] = $this->blg->getmyBData($sess['emp_c'],$sess['role'],$lang);

    $manBH = $this->input->post('hidePG');
    if (isset($manBH)){
      $this->blg->sethide($manBH,"no");
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Set Hide');";
      echo"window.location='".base_url()."blog/my';";
      echo"</script>";
    }

    $manBUH = $this->input->post('unhidePG');
    if (isset($manBUH)){
      $this->blg->sethide($manBUH,"yes");
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Set Show');";
      echo"window.location='".base_url()."blog/my';";
      echo"</script>";
    }

    $buttdep = $this->input->post('deletePG');
    if (isset($buttdep)){
      $this->blg->deleteB($buttdep);
      $this->recs->record('blog', 'Deleted Blog.');
      $this->recs->closeDB();
      $this->user->closeDB();
      $this->blg->closeDB();
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Deleted');";
      echo"window.location='".base_url()."blog/my';";
      echo"</script>";
    }

    $createBl = $this->input->post('createBl');
    if (isset($createBl)){
      $this->create();
      redirect(base_url().'blog/create');
    }

    $beid = $this->input->post('editPG');
    if (isset($beid)){
      $this->edit($beid);
      redirect(base_url().'blog/edit/'.$beid);
    }

    $this->blg->closeDB();
    $this->user->closeDB();
    $this->foot->closeDB();
    $this->load->view('frontend/myblog', $arr );
  }

  public function detail($id)
  {
    $arr['page'] = 'blog/detail';
    $this->load->model('User_model', 'login', TRUE);
    !$this->login->checkSession($arr['page']) ? : $arr['page'];
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;
    $this->load->model('Blog_model', 'blg', FALSE);
    $this->blg->loadDB();
    $this->load->model('Log_model', 'recs', FALSE);
    $this->recs->loadDB();
    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $this->load->model('User_model', 'user', FALSE);
    $this->user->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $arr['thisblog'] = $this->blg->getBlog($id);
    $arr['empnam'] = $this->blg->getnameE($arr['thisblog'][0]['emp_c'],$lang);
    $arr['acti'] = "detail";

    $buttdep = $this->input->post('deleteBl');
    if (isset($buttdep)){
      $this->blg->deleteB($id);
      $this->recs->record('blog', 'Deleted Blog.');
      $this->recs->closeDB();
      $this->user->closeDB();
      $this->blg->closeDB();
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Deleted');";
      echo"window.location='".base_url()."blog/all';";
      echo"</script>";
    }

    if(!$this->blg->checkmyblog($sess['emp_c'],$id)){
      if (!$this->blg->isApproved($id) && !in_array($arr['role'], array("superadmin", "admin"))) {
        $this->recs->record('survey', 'Denied from blog id '.$id);
        $this->recs->closeDB();
        $this->user->closeDB();
        $this->blg->closeDB();
        echo "<meta http-equiv='refresh' content='0'>" ;
        echo"<script language='JavaScript'>";
        echo"alert('Access Denied.');";
        echo"window.location='".base_url()."blog/all';";
        echo"</script>";
      }
    }

    $editBl = $this->input->post('editBl');
    if (isset($editBl)){
      $this->edit($id);
      redirect(base_url().'blog/edit/'.$id);
    }

    $this->foot->closeDB();
    $this->user->closeDB();
    $this->blg->closeDB();
    $this->load->view('frontend/blog', $arr );
  }

  public function edit($id)
  {
    $arr['page'] = 'blog/edit';
    $this->load->model('User_model', 'login', TRUE);
    !$this->login->checkSession($arr['page']) ? : $arr['page'];
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;
    $this->load->model('Blog_model', 'blg', FALSE);
    $this->blg->loadDB();
    $this->load->model('Log_model', 'recs', FALSE);
    $this->recs->loadDB();
    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $this->load->model('User_model', 'user', FALSE);
    $this->user->loadDB();
    $this->load->model('Contact_model', 'conta', FALSE);
    $this->conta->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $arr['thisblog'] = $this->blg->getBlog($id);
    $arr['empnam'] = $this->blg->getnameE($arr['thisblog'][0]['emp_c'],$lang);
    $arr['acti'] = "edit";

    $cB = $this->input->post('eBlog');
    $data = array(
      'emp_c' => $arr['thisblog'][0]['emp_c'],
      'title' => $this->security->xss_clean($this->input->post('ttle')),
      'content' => $this->security->xss_clean($this->input->post('bc')),
      'approve' => 'no',
      'lang' => $lang);
    if (isset($cB)){
      $this->blg->editB($id, $data);
      $this->recs->record('blog', 'Edited Blog.');
      if ($arr['role'] != 'superadmin') {
      $aemail = $this->conta->gete();
      $emp_c = $arr['emp_c'];
      $name = $arr['empnam'][0]['prefix'].$arr['empnam'][0]['fname']." ".$arr['empnam'][0]['lname'];
      $email = $arr['empnam'][0]['email'];
      $mess = "This user : ".$emp_c." want to edit a blog name ".$data['title']." ,please check ".base_url()."blog/all";
      $this->sendEmail($emp_c,$name,$email,$mess,$aemail);
     }
      $this->recs->closeDB();
      $this->user->closeDB();
      $this->blg->closeDB();
      $this->conta->closeDB();
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Edited');";
      echo"window.location='".base_url()."blog/detail/$id';";
      echo"</script>";
    }

    $this->foot->closeDB();
    $this->user->closeDB();
    $this->blg->closeDB();
    $this->conta->closeDB();
    $this->load->view('frontend/blog', $arr );
  }

  public function create(){
    $arr['page'] = 'blog/create';
    $this->load->model('User_model', 'login', TRUE);
    !$this->login->checkSession($arr['page']) ? : $arr['page'];
    $sess = $this->session->userdata("user");
    $arr['emp_c'] = $sess['emp_c'];
    $arr['role'] = $sess['role'];
    $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
    $this->lang->load($lang,$lang);
    $arr['lang'] = $lang;
    $this->load->model('Blog_model', 'blg', FALSE);
    $this->blg->loadDB();
    $this->load->model('Log_model', 'recs', FALSE);
    $this->recs->loadDB();
    $this->load->model('Footer_model', 'foot', FALSE);
    $this->foot->loadDB();
    $this->load->model('User_model', 'user', FALSE);
    $this->user->loadDB();
    $this->load->model('Contact_model', 'conta', FALSE);
    $this->conta->loadDB();
    $arr['foote'] = $this->foot->getfooter();
    $arr['empnam'] = $this->blg->getnameE($arr['emp_c'], $lang);
    $arr['acti'] = "create";

    $cB = $this->input->post('cBlog');
    $data = array(
      'emp_c' => $sess['emp_c'],
      'title' => $this->security->xss_clean($this->input->post('ttle')),
      'content' => $this->security->xss_clean($this->input->post('bc')),
      'approve' => 'no',
      'lang' => $lang);
    if (isset($cB)){
      $this->blg->createB($data);
      $this->recs->record('blog', 'Created Blog.');
      if ($arr['role'] != 'superadmin') {
      $aemail = $this->conta->gete();
      $emp_c = $arr['emp_c'];
      $name = $arr['empnam'][0]['prefix'].$arr['empnam'][0]['fname']." ".$arr['empnam'][0]['lname'];
      $email = $arr['empnam'][0]['email'];
      $mess = "This user : ".$emp_c." want to create a new blog name ".$data['title']." ,please check ".base_url()."blog/all";
      $this->sendEmail($emp_c,$name,$email,$mess,$aemail);
      }
      $this->recs->closeDB();
      $this->user->closeDB();
      $this->blg->closeDB();
      $this->conta->closeDB();
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Created');";
      echo"window.location='".base_url()."blog/my';";
      echo"</script>";
    }

    $this->foot->closeDB();
    $this->user->closeDB();
    $this->blg->closeDB();
    $this->conta->closeDB();
    $this->load->view('frontend/blog', $arr );
  }

  public function sendEmail($emp_c,$name,$email,$mess,$aemail){
    require 'class/class.simple_mail.php';
    $sub = "ข้อความจากเว็บไซต์";
    /*$mail_to = $aemail;*/
    $message = '<table width="500" border="0" cellpadding="0" cellspacing="3" bgcolor="#FFFFFF">
      <tr>
        <td width="20">&nbsp;</td>
        <td width="65">&nbsp;</td>
        <td width="509">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="normalText">Name:</td>
        <td class="normalText_Blue">'.$name.'</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="normalText">Email:</td>
        <td class="normalText_Blue">'.$email.'</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="normalText">Message:</td>
        <td class="normalText_Blue">'.$mess.'</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>';
foreach ($aemail as $row) {
    $mail = new SimpleMail();
     //$mail->SMTPAuth = false;
     // SMTP server
     //$mail->Host = "172.20.102.105";
     // set the SMTP port for the outMail server
     // use either 25, 587, 2525 or 8025
     //$mail->Port = 25;
    $mail->setTo($row['email'],'')
    //$mail->setTo('wutikornj@gmail.com','')
      ->setFrom(SERVER_EMAIL, 'Elearning')
      ->setSubject('AsiaplusLMS Auto Reply Contact us')
      ->addGenericHeader('MIME-Version', '1.0')
      ->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
      ->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
      ->setMessage($message);
    $mail->send();
    }
    $send = 1;
    if ($send == 1) {
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('Successfully Send Email.');";
      echo"window.location='".base_url()."blog/my';";
      echo"</script>";
    } else {
      echo "<meta http-equiv='refresh' content='0'>" ;
      echo"<script language='JavaScript'>";
      echo"alert('System busy, Try again later.');";
      echo"window.location='".base_url()."blog/my';";
      echo"</script>";
    }

  }

}
