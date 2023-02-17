<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Page extends MY_Controller {

	var $modul = "page";

	function __construct() {

		parent::__construct();

      	$this->load->model('Auth_model');
	}

	public function login(){
		// Patched by Adit last update 22 Nov 22
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);

		if($username!=null && $password!=null){
			// $login = $this->db->query('select * from master_user where user_username = "'.$username.'" and user_password = md5("'.$password.'")')->row();
					 $foundUser = $this->db->query("select * from master_user where user_username = ?", array(addslashes($username)));
					 if($foundUser->num_rows() > 0){
						$login = $this->db->query("select * from master_user where user_username=? and user_password =?", array(addslashes($username), md5($password)))->row();
					 }else {
						$login = null;
					 }
			
			if(!is_null($login)){
				if ($login->is_deleted==0) {

					$menu = $this->db->query('select a.*, b.menu_url, b.menu_name, c.module_url, c.module_name, d.menu_name as menu_parent from master_user_privileges a
											  inner join master_menu b on a.menu_id = b.id and b.is_deleted = 0
											  inner join master_module c on b.module_id = c.id and c.is_deleted = 0
											  left join master_menu d on b.menu_id = d.id and d.is_deleted = 0
											  where a.user_type_code = ? and a.v = ?', array($login->user_type_code, 1))->result();

					$this->session->set_userdata('logged_in', true);
					$this->session->set_userdata('id', $login->id);
					$this->session->set_userdata('username', $login->user_username);
					$this->session->set_userdata('name', $login->user_name);
					$this->session->set_userdata('email', $login->user_email);
					$this->session->set_userdata('menu', $menu);

					$response = [
						"success" => TRUE, 
						"title" => "Success", 
						"text" => "Berhasil" 
					];
				}else{
					$response = [
						"success" => false, 
						"title" => "Error", 
						"text" => "Pengguna Sudah Tidak Aktif" 
					];
				}
			}else{
				$response = [
					"success" => false, 
					"title" => "Error", 
					"text" => "Username & Password Salah" 
				];
			}
		}else{
			$response = [
				"success" => false, 
				"title" => "Error", 
				"text" => "Username & Password Salah" 
			];
		}
		
		echo json_encode($response);
	}

	public function logout(){
		$current_time=date("h:m:s");
		$current_date=date("d M Y");
		$user_id = $this->session->userdata('noktp');
		// $data = array('last_login_date' =>$current_date,
		// 			  'last_login_time'=>$current_time );
		// $this->auth_model->updatebyid('user','user_id',$data,$user_id['user_id']);
		$this->session->unset_userdata('logged_in');
	    $this->session->unset_userdata('nip');
	    $this->session->unset_userdata('nama');
	    $this->session->unset_userdata('username');
	    $this->session->unset_userdata('filename');	
	    $this->session->unset_userdata('idjab');	
	    $this->session->sess_destroy();
		// session_destroy();
		redirect(base_url(),'refresh');
	}
}

