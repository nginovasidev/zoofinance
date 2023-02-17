<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {
	var $modul = "keuangan";

	function __construct(){

		parent::__construct();
	}

	public function index(){
		parent::_view();
	}

	function laporanneraca(){
		parent::_view();
	}

	function laporanlabarugi(){
		parent::_view();
	}

	function laporaninventaris(){
        parent::_view();
    }

    function laporanledgerdetail(){
        parent::_view();
    }

    function laporanharian(){
        parent::_view();
    }

    function ceklabarugiberjalan(){
        parent::_view();
    }

    function realisasirkap(){
    	parent::_view();
    }
}
