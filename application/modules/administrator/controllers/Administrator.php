<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends MY_Controller {
	var $modul = "administrator";

	function __construct(){

		parent::__construct();
	}

	public function index(){
		parent::_view();
	}

	function manmodul(){
		parent::_view();
	}

	function manmenu(){
		parent::_view();
	}

	function manjenisuser(){
		parent::_view();
	}

	function manuser(){
		parent::_view();
	}
    
    function manhakakses(){
		parent::_view();
	}

	function manttd(){
		parent::_view();
	}
}
