<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajaxfile extends MY_Controller
{
    var $modul = 'manstok';

    function __construct()
    {
        parent::__construct();
    }

    function get_faktur()
    {
        $search = "";
        $cari = $this->input->post('search');
        // $page = $this->input->post('per_page');
        if ($cari !== "") $search = " AND ( a.no_faktur LIKE '%$cari%')";
        $query = $this->db->query(
            "SELECT a.id, a.no_faktur as 'text'
            FROM t_barang_masuk a
            WHERE a.is_deleted ='0' $search ORDER BY a.id DESC"
        )->result();
        echo json_encode($query);
    }

    function get_faktur_barang()
    {
        $data = $this->input->post();
        $query = $this->db->query(
            "SELECT a.id, a.faktur_id, a.id_barang, b.nama_barang, a.qty_barang, a.hrg_peroleh 
            FROM t_barang_masuk_detail a
            JOIN m_fifo_nama_barang b ON b.id = a.id_barang 
            WHERE a.is_deleted ='0' AND a.faktur_id ='" . $data['id'] . "'"
        );
        if ($query->num_rows() > 0) {
            echo json_encode(array('success' => TRUE, 'data' => $query->result()));
        } else {
            echo json_encode(array('success' => FALSE, 'data' => 'Data tidak ditemukan'));
        }
    }

    function caribarang() {
        $search = "";
        $cari = $this->input->post('search');
        // $page = $this->input->post('per_page');
        if($cari!=="") $search = "AND ( a.nama_barang LIKE '%$cari%')";
        $query = $this->db->query(
            "SELECT a.id, a.nama_barang AS 'text', b.ttl_qty_barang 
            FROM m_fifo_nama_barang a
            LEFT JOIN t_stok_summary b ON b.barang_id = a.id 
            WHERE a.is_deleted = '0' $search ORDER BY a.id DESC")->result();
        echo json_encode($query);
    }
}
