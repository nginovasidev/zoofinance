<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index(){
		$cek_config_trx = $this->db->query("SELECT * FROM m_config_trx where tgl='".date("Y-m-d")."' ")->row();
		if ($cek_config_trx) {
		} else {
			$this->db->query("INSERT INTO m_config_trx (tgl , created_by) values ('".date("Y-m-d")."' , '1')");
		}
		$this->load->view('main/layout');		
	}

	function dashboard(){
		$data['load_view'] = "dashboard";
		$this->load->view('main/layout', $data);
	}

	function error403(){
		$this->load->view('main/forbidden');
	}
}
