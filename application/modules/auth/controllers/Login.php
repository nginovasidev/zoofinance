<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct() {
        parent::__construct();
    }

	public function index(){
		if ($this->session->userdata('logged_in')) {
			redirect(base_url('main'));
		}else{
			$this->load->view('login');
		}
	}

	function coba(){

		$this->load->view('coba');
	}
}
