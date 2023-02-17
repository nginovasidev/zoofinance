<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajaxfile extends MY_Controller
{
    var $modul = "polda";

    function __construct()
    {
        parent::__construct();
    }

    function cariparent(){
        $search = "";
        $cari = $this->input->post('search');
        // $page = $this->input->post('per_page');
        if($cari!=="") $search = " AND ( namaakun LIKE '%$cari%')";
        $query = $this->db->query("SELECT a.kdakun as id , namaakun as 'text',  posisi , normalbalance from m_akun a where is_deleted='0' $search ")->result();
        echo json_encode($query);
    }

    function cariuser(){
        $search = "";
        $cari = $this->input->post('search');
        // $page = $this->input->post('per_page');
        if($cari!=="") $search = " AND ( a.user_username LIKE '%$cari%')";
        $query = $this->db->query("SELECT a.id, a.user_username as 'text' FROM `master_user` a WHERE a.is_deleted='0' AND a.user_type_code!='sad' $search ")->result();
        echo json_encode($query);
    }

    function carikotabyprov(){
        $search = "";
        $cari = $this->input->post('search');
        $id_prov = $this->input->post('id_prov');
        // $page = $this->input->post('per_page');
        if($cari!=="") $search = " AND ( nama LIKE '%$cari%')";
        $query = $this->db->query("SELECT a.id , nama as 'text' from m_lokasi_indonesia a where length(a.id)='4' and id like '".$id_prov."%' $search")->result();
        echo json_encode($query);
    }


    function getSuggestAkun(){

        $data = $this->input->post();

        $rs = $this->db->query("SELECT IFNULL(MAX(kdakun)+1,concat('".$data['idparent']."-', CONCAT_WS('',LPAD(IFNULL(1,1),3,'0')))) AS kdakun FROM m_akun
        WHERE idparent='".$data['idparent']."' ");

        echo $rs->row()->kdakun;
        
    }


    function pdf(){
        $mpdf = new \Mpdf\Mpdf();
          $html = $this->load->view('html_to_pdf',[],true);
          $mpdf->WriteHTML($html);
          $mpdf->Output();
    }
}
