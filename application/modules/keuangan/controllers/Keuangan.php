<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends MY_Controller {
	var $modul = "keuangan";

	function __construct(){

		parent::__construct();
	}

	public function index(){
		parent::_view();
	}

	function manakun(){
		parent::_view();
	}

    function manbarang(){
        parent::_view();
    }

    function manstok(){
        parent::_view();
    }

	function pembayaran(){
		parent::_view();
	}

	function penerimaan(){
        parent::_view();
    }

    function jurnalumum(){
        parent::_view();
    }

    function inventaris(){
        parent::_view();
    }

    function importexcel(){
        parent::_view();
    }

    function cashopname(){
        parent::_view();
    }

    function prosestutuphari(){
        parent::_view();
    }

    function prosestutuptahun(){
        parent::_view();
    }

    function rkap(){
        parent::_view();
    }

}
