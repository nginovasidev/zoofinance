<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends MY_Controller
{
    var $modul = "polda";

    function __construct()
    {
        parent::__construct();
        parent::_privileges();
    }

    // begin laporanneraca
    function laporanneraca_load()
    {
        // $query = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , c.saldoawal , d.debet , d.kredit ,
        //                         if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0)) as saldoakhir
        //                         from m_akun a
                                
        //                         left join t_keuangan b on a.`kdakun`=b.kdakun
                                
        //                         left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".$_GET['tgl']."' group by a.kdakun) c on a.kdakun=c.kdakun

        //                         left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$_GET['tgl']."' and '".$_GET['tgl']."' group by a.kdakun) d on a.kdakun=d.kdakun

        //                             where a.kdakun in (1,2,3)
        //                         group by a.kdakun")->result();

        $this->load->view('data/laporanneraca_data');
    }

    function laporanneraca_save()
    {
        parent::_insert('m_inventaris', $_POST);
    }

    function laporanneraca_edit()
    {
        $data = $this->input->post();
        $query = "select * from m_inventaris a
        where a.id = '".$data['id']."'";
        parent::_edit('m_inventaris', $_POST , $query);
    }

    function laporanneraca_delete()
    {
        parent::_delete('m_inventaris', $_POST);
    }

    // end laporanneraca

     // begin laporanlabarugi
    function laporanlabarugi_load()
    {
        $this->load->view('data/laporanlabarugi_data');
    }

    function laporanlabarugi_save()
    {
        parent::_insert('m_inventaris', $_POST);
    }

    function laporanlabarugi_edit()
    {
        $data = $this->input->post();
        $query = "select * from m_inventaris a
        where a.id = '".$data['id']."'";
        parent::_edit('m_inventaris', $_POST , $query);
    }

    function laporanlabarugi_delete()
    {
        parent::_delete('m_inventaris', $_POST);
    }

    // end laporanlabarugi


    function laporanledgerdetail_load()
    {
        $this->load->view('data/laporanledgerdetail_data');
    }

    function bukubesarsistemlama_load()
    {
        $this->load->view('data/bukubesarsistemlama_data');
    }



    function ceklabarugiberjalan_load()
    {
        $this->load->view('data/ceklabarugiberjalan_data');
    }



}
