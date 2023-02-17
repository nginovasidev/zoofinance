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

    function bukubesarsistemlama(){
        parent::_view();
    }

    function excel(){
        $url = $this->uri->segment('2') ;

        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=".$url."-".date('d-m-Y H:i:s').".xls");  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);

        echo $this->load->view('pdf/'.$this->uri->segment('3').'_pdf',[],true);
    }
}
