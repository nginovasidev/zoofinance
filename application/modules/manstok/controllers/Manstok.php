<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manstok extends MY_Controller
{
    var $modul = 'manstok';

    function __construct(){
        parent::__construct();
    }

    public function index(){
        parent::_view();
    }

    function manbarang(){
        parent::_view();
    }

    function stokin(){
        parent::_view();
    }

    function stokout(){
        parent::_view();
    }
}