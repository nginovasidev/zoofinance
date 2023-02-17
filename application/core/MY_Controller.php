<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH . "third_party/MX/Controller.php";

class MY_Controller extends MX_Controller
{	

	function __construct(){
		parent::__construct();
		$this->_hmvc_fixes();

		if(!$this->session->userdata('logged_in') && !in_array($this->uri->segment(1), ['','auth'])){
			redirect(base_url());
		}
	}
	
	function _hmvc_fixes(){		
		//fix callback form_validation		
		//https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
	}

	function _view($data = array()){
		$url = $this->uri->segment(2);
		$menu = $this->session->userdata('menu');

		$filter = array_filter($menu, function($arr) use ($url){
			return strtolower($arr->menu_url) == strtolower($url);
		});

		// print_r('<pre>');
		// print_r($menu);
		// print_r('<pre>');

		if(count($filter) == 1){
			$log_data = array(
				'log_action' => 'view',
				'log_url' => $_SERVER['REQUEST_URI'],
				'log_result' => (array_values($filter)[0]->v == "1") ? "Akses diberikan" : "Akses ditolak",
				'user_id' => $this->session->userdata('id'),
				'user_ip' => $_SERVER['REMOTE_ADDR']
			);
			// print_r($log_data);

			// $this->db->insert('master_user_privileges_log', $log_data);

			if(array_values($filter)[0]->v != "1"){
				$data['load_view'] = 'main/error403';
				// $data['page_info'] = json_encode($menu).'<br><br><br>'.json_encode($filter).'<br>'.count($filter).'<br>'.$url;
				$this->load->view('main/layout', $data);
			}else{
				$data['load_view'] = array_values($filter)[0]->menu_url;
				$data['page_title'] = array_values($filter)[0]->menu_name;
				$data['rules'] = array_values($filter)[0];
				// $data['page_info'] = json_encode($menu).'<br><br><br>'.json_encode($filter).'<br>'.count($filter).'<br>'.$url;
				$this->load->view('main/layout', $data);
			}
		}else{
			$log_data = array(
				'log_action' => 'view',
				'log_url' => $_SERVER['REQUEST_URI'],
				'log_result' => "Akses ditolak",
				'user_id' => $this->session->userdata('id'),
				'user_ip' => $_SERVER['REMOTE_ADDR']
			);

			// $this->db->insert('master_user_privileges_log', $log_data);

			$data['load_view'] = 'main/error403';
			$data['page_info'] = json_encode($filter[0]).'<br>'.count($filter);
			$this->load->view('main/layout', $data);
		}
	}

	function _privileges(){
		$request = $this->uri->segment_array()[count($this->uri->segment_array())];
		$arr = explode("_", $request); 
		$referers = explode("/", $_SERVER['HTTP_REFERER']);
		$referer = end($referers);
		$action = end($arr);
		$menu = $this->session->userdata('menu');

		$filter = array_filter($menu, function($arr) use ($referer) {
			return strtolower($arr->menu_url) == strtolower($referer);
		});

		if(count($filter) == 1 && $referer != ""){
			switch ($action) {
				case 'insert':
				case 'save':
					$this->_authInsert(array_values($filter)[0]);
					break;
				case 'edit':
					$this->_authEdit(array_values($filter)[0]);
					break;
				case 'delete':
					$this->_authDelete(array_values($filter)[0]);
					break;
				case 'load':
					$this->_authLoad(array_values($filter)[0]);
					break;
				case 'download':
					$this->_authDownload(array_values($filter)[0]);
					break;
				case 'upload':
					$this->_authUpload(array_values($filter)[0]);
					break;
				default:
					die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini')));
					break;
			}
		}else{
			die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini', 'debug' => $referer.' '.count($filter))));
		}
	}

	function _authInsert($menu){
		$data = array(
			'log_action' => 'insert',
			'log_url' => $_SERVER['REQUEST_URI'],
			'log_result' => ($menu->i == "1") ? "Akses diberikan" : "Akses ditolak",
			'user_id' => $this->session->userdata('id'),
			'user_ip' => $_SERVER['REMOTE_ADDR']
		);

		$this->db->insert('master_user_privileges_log', $data);

		if($menu->i != "1"){
			die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini')));
		}
	}

	function _authEdit($menu){
		$data = array(
			'log_action' => 'edit',
			'log_url' => $_SERVER['REQUEST_URI'],
			'log_result' => ($menu->e == "1") ? "Akses diberikan" : "Akses ditolak",
			'user_id' => $this->session->userdata('id'),
			'user_ip' => $_SERVER['REMOTE_ADDR']
		);

		$this->db->insert('master_user_privileges_log', $data);

		if($menu->e != "1"){
			die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini')));
		}
	}

	function _authDelete($menu){
		$data = array(
			'log_action' => 'delete',
			'log_url' => $_SERVER['REQUEST_URI'],
			'log_result' => ($menu->d == "1") ? "Akses diberikan" : "Akses ditolak",
			'user_id' => $this->session->userdata('id'),
			'user_ip' => $_SERVER['REMOTE_ADDR']
		);

		$this->db->insert('master_user_privileges_log', $data);

		if($menu->d != "1"){
			die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini')));
		}
	}

	function _authAuth($menu){
		$data = array(
			'log_action' => 'authorization',
			'log_url' => $_SERVER['REQUEST_URI'],
			'log_result' => ($menu->a == "1") ? "Akses diberikan" : "Akses ditolak",
			'user_id' => $this->session->userdata('id'),
			'user_ip' => $_SERVER['REMOTE_ADDR']
		);

		$this->db->insert('master_user_privileges_log', $data);

		if($menu->o != "1"){
			die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini')));
		}
	}

	function _authLoad($menu){
		$data = array(
			'log_action' => 'load data',
			'log_url' => $_SERVER['REQUEST_URI'],
			'log_result' => ($menu->v == "1") ? "Akses diberikan" : "Akses ditolak",
			'user_id' => $this->session->userdata('id'),
			'user_ip' => $_SERVER['REMOTE_ADDR']
		);

		$this->db->insert('master_user_privileges_log', $data);

		if($menu->v != "1"){
			die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini')));
		}
	}

	function _authUpload($menu){
		$data = array(
			'log_action' => 'upload',
			'log_url' => $_SERVER['REQUEST_URI'],
			'log_result' => ($menu->i == "1") ? "Akses diberikan" : "Akses ditolak",
			'user_id' => $this->session->userdata('id'),
			'user_ip' => $_SERVER['REMOTE_ADDR']
		);

		$this->db->insert('master_user_privileges_log', $data);

		if($menu->i != "1"){
			die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini')));
		}
	}

	function _authDownload($menu){
		$data = array(
			'log_action' => 'download',
			'log_url' => $_SERVER['REQUEST_URI'],
			'log_result' => ($menu->v == "1") ? "Akses diberikan" : "Akses ditolak",
			'user_id' => $this->session->userdata('id'),
			'user_ip' => $_SERVER['REMOTE_ADDR']
		);

		$this->db->insert('master_user_privileges_log', $data);

		if($menu->v != "1"){
			die(json_encode(array('success' => false, 'message' => 'Anda tidak mempunyai hak akses untuk ini')));
		}
	}

	function _loadDatatable($query, $where, $data, callable $callback = NULL){
		$start = $_POST["start"];
        $length = $_POST["length"];
        $search = $_POST["search"];
        $order = $_POST["order"][0];
        $columns = $_POST["columns"];
        $key = $search["value"];
        $orderColumn = $columns[$order["column"]]["data"];
        $orderDirection = $order["dir"];


        $q = ($key != "" ? $query . " and (" .implode(" or ", array_map(function($x) use ($key) {
                	return $x . " like '%". $key ."%'";
             }, $where)) . ")" : $query) . " order by ".$orderColumn." ".$orderDirection;

        $allData = $this->db->query($query)->num_rows();
        $filteredData = $this->db->query($q)->num_rows();

        $q .= $length > -1 ? " limit ".$start.",".$length : "";

        $result = $this->db->query($q)->result();

		echo json_encode(array("data" => $result, "recordsTotal" => $allData, "recordsFiltered" => $filteredData, 'debug' => array('last_query' => $this->db->last_query(), 'start' => $start, 'length' => $length)));
	}


	function _loadAjax($tableName, $data, $query = NULL){
		$rs = $query == NULL ? $this->db->where('id',$data['id'])->get($tableName)->result() : $this->db->query($query)->result();

		if(!is_null($rs)){
			echo json_encode(array('success' => true, 'data' => $rs, 'last_query' => $this->db->last_query()));
		}else{
			echo json_encode(array('success' => false, 'message' => $this->db->error()));
		}
	}

	function _insert($tableName, $data, callable $callback = NULL){
		if($data['id'] == ""){

			$data['created_by'] = $this->session->userdata('id');

			if($this->db->insert($tableName, $data)){
				$data['idnya'] = $this->db->insert_id();
				echo json_encode(array('success' => true, 'message' => $data ));
			}else{
				echo json_encode(array('success' => false, 'message' => $this->db->error()));
			}
		}else{
			$id = $data['id'];
			$data['last_edited_at'] = date('Y-m-d H:i:s');
			$data['last_edited_by'] = $this->session->userdata('id');
			unset($data['id']);

			if($this->db->where('id',$id)->update($tableName, $data)){
				echo json_encode(array('success' => true, 'message' => $data));
			}else{
				echo json_encode(array('success' => false, 'message' => $this->db->error()));
			}
		}
	}

	function _insertbatch($tableName, $data , callable $callback = NULL){
		// if($data['id'] == ""){

		// 	$data['created_by'] = $this->session->userdata('id');

		// 	if($this->db->insert($tableName, $data)){
		// 		echo json_encode(array('success' => true, 'message' => $data, 'debug' => $tableName));
		// 	}else{
		// 		echo json_encode(array('success' => false, 'message' => $this->db->error()));
		// 	}
		// }else{
		// 	$id = $data['id'];
		// 	$data['last_edited_at'] = date('Y-m-d H:i:s');
		// 	$data['last_edited_by'] = $this->session->userdata('id');
		// 	unset($data['id']);

		// 	if($this->db->where('id',$id)->update($tableName, $data)){
		// 		echo json_encode(array('success' => true, 'message' => $data, 'debug' => $tableName));
		// 	}else{
		// 		echo json_encode(array('success' => false, 'message' => $this->db->error()));
		// 	}
		// }

		// echo json_encode($data);

		$this->db->trans_begin();

			$userid = $this->session->userdata('id');
		
			$insert = array();
			$update = array();
			$delete = array();
			foreach ($data as $item) {

				if ($item['id']=='') {
					$item['created_by'] = $userid;
					$insertarray = $item;
					array_push($insert, $insertarray);
				} else {

					if (isset($item['is_deleted'])) {
						
						$item['last_edited_by'] = $userid;
						$item['last_edited_at'] =  date("Y-m-d H:i:s");
						$deletearray = $item;
						array_push($delete, $deletearray);

					} else {

						$item['last_edited_by'] = $userid;
						$item['last_edited_at'] =  date("Y-m-d H:i:s");
						$updatearray = $item;
						array_push($update, $updatearray);
					}
				}
			}

			if (count($insert)) {
				$this->db->insert_batch($tableName, $insert);
			}
			if (count($update)) {
				$this->db->update_batch($tableName, $update, 'id');
			}
			if (count($delete)) {
				$this->db->update_batch($tableName, $delete, 'id');
			}

		if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
			echo json_encode(array('success' => false, 'message' => $this->db->error()['message']));
        }
        else
        {
            $this->db->trans_commit();
			echo json_encode(array('success' => true, 'message' => $data));
        }
	}

	function _edit($tableName, $data, $query = NULL){
		$rs = $query == NULL ? $this->db->where('id',$data['id'])->get($tableName)->row() : $this->db->query($query)->row();

		if(!is_null($rs)){
			echo json_encode(array('success' => true, 'data' => $rs, 'last_query' => $this->db->last_query()));
		}else{
			echo json_encode(array('success' => false, 'message' => $this->db->error()));
		}
	}

	function _delete($tableName, $data){
		$updateData['is_deleted'] = 1;
		$updateData['last_edited_at'] = date('Y-m-d H:i:s');
		$updateData['last_edited_by'] = $this->session->userdata('id');

		if($this->db->where('id',$data['id'])->update($tableName, $updateData)){
			echo json_encode(array('success' => true, 'last_query' => $this->db->last_query()));
		}else{
			echo json_encode(array('success' => false, 'message' => $this->db->error()));
		}
	}

	function _deletearray($tableName, $data , $where){
		$updateData['is_deleted'] = 1;
		$updateData['last_edited_at'] = date('Y-m-d H:i:s');
		$updateData['last_edited_by'] = $this->session->userdata('id');

		if($this->db->where($where, $data->nobukti)->update($tableName, $updateData)){
			echo json_encode(array('success' => true, 'tgl' => $data->tgltrx, 'last_query' => $this->db->last_query()));
		}else{
			echo json_encode(array('success' => false, 'message' => $this->db->error()));
		}
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
