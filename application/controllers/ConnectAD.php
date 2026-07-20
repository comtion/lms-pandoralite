<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConnectAD extends CI_Controller {

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
		$this->lang->load($lang,$lang);

		$arr['lang'] = $lang;
		$arr['page'] = "testAD";

    $ldap_ip = '172.20.50.253';
    $ldap = ldap_connect($ldap_ip);
    if( $ldap ){

    }

    $user = 'hrelearning';
    $password = 'HR-Elearning08!'; //This password is correct but binding it with this format will give us an error

    $password = utf8_decode($password); //$password = otoxF1o

    $ldap_bind = ldap_bind($ldap, $user, $password); //Now the binding is successfull and $ldap_bind = true

	}
}
