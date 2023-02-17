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
        if($cari!=="") $search = " AND ( namaakun LIKE '%$cari%' or kdakun LIKE '%$cari%')";
        $query = $this->db->query("SELECT a.kdakun as id , concat(kdakun, ' | ', namaakun) as 'text',  posisi , normalbalance from m_akun a where is_deleted='0' $search order by a.kdakun")->result();
        echo json_encode($query);
    }

    function cariparent_datalama(){
        $search = "";
        $cari = $this->input->post('search');
        // $page = $this->input->post('per_page');
        if($cari!=="") $search = " AND ( namaakun LIKE '%$cari%' or kdakun LIKE '%$cari%')";
        $query = $this->db->query("SELECT a.kdakun as id , concat(kdakun, ' | ', namaakun as 'text' from mast_akun a where kdakun is not null $search order by a.kdakun")->result();
        echo json_encode($query);
    }

    function cariakuntransaksi(){
        $search = "";
        $cari = $this->input->post('search');
        // $page = $this->input->post('per_page');
        if($cari!=="") $search = " AND ( namaakun LIKE '%$cari%' or kdakun LIKE '%$cari%')";
        $query = $this->db->query("SELECT a.kdakun as id , concat(kdakun, ' | ', namaakun) as 'text' from m_akun a where is_deleted='0' and is_cash='1'  $search order by a.kdakun")->result();
        echo json_encode($query);
    }

    function cariakunall(){
        $search = "";
        $cari = $this->input->post('search');
        // $page = $this->input->post('per_page');
        if($cari!=="") $search = " AND ( namaakun LIKE '%$cari%' or kdakun LIKE '%$cari%')";
        $query = $this->db->query("SELECT a.kdakun as id , concat(kdakun, ' | ', namaakun) as 'text' from m_akun a where is_deleted='0' and is_saldo='1' $search order by a.kdakun")->result();
        echo json_encode($query);
    }


    function getSuggestAkun(){

        $data = $this->input->post();

        $rs = $this->db->query("SELECT IFNULL(
                if(LENGTH('".$data['idparent']."')=1,
                    concat(SUBSTRING_INDEX(MAX(kdakun), '-', 1),'-', CONCAT_WS('',LPAD(SUBSTRING_INDEX(MAX(kdakun), '-', -1)+10,2,'0'))) ,
                    concat(SUBSTRING_INDEX(MAX(kdakun), '-', 1),'-',SUBSTRING_INDEX(MAX(kdakun), '-', -1)+1)
                )   ,
            if(LENGTH('".$data['idparent']."')<2, concat('".$data['idparent']."-', '10') , concat('".$data['idparent']."' ,'01') )

            ) AS kdakun FROM m_akun
        WHERE idparent='".$data['idparent']."' ");

        echo $rs->row()->kdakun;
        
    }

    function ceknomorbukti(){
        $data = $this->input->post();

        $rs = $this->db->query("SELECT * from t_keuangan where nobukti='".$data['nobukti']."' and is_deleted='0' ");

        $nobuktiready = 0;
        if ($rs->row()==null) {
            echo "Nomor bukti Belum Digunakan";
        } else {

            // echo json_encode($rs->result());
            foreach ($rs->result() as $item) {
                if ($item->id==$data['idakunutama']) {
                    $nobuktiready = 1;
                }
            }

            if ($nobuktiready==1) {
                echo "";
            } else {
                echo "<text style='color: red'>Nomor Bukti Sudah Digunakan</text>";
            }
        }
    }

    function updatelababerjalan(){
        $data = $this->input->post();
        $cekdata = $this->db->query("SELECT * from t_keuangan where is_transaksi='2' and is_deleted='0' and tgltrx='".$data['tanggal']."'")->num_rows();
        
        $kredit = $this->db->query("SELECT kdakun, namaakun , is_saldo , sum(saldo_awal) as saldo_awal , sum(_debet) as _debet , sum(_kredit) as _kredit , sum(_kredit)-sum(_debet) as saldoakhir from (
            SELECT a.kdakun , a.namaakun , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', ifnull(d.kredit,0)-ifnull(d.debet,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".$data['tanggal']."' and b.is_deleted='0' group by a.kdakun) c on a.kdakun=c.kdakun left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$data['tanggal']."' and '".$data['tanggal']."' and b.is_deleted='0' group by a.kdakun) d on a.kdakun=d.kdakun where a.kdakun like '4-%' group by kdakun ) a")->row();
        
        $debit = $this->db->query("SELECT kdakun, namaakun , is_saldo , sum(saldo_awal) as saldo_awal , sum(_debet) as _debet , sum(_kredit) as _kredit , sum(_debet)-sum(_kredit) as saldoakhir from (
            SELECT a.kdakun , a.namaakun , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                    from m_akun a
                    left join t_keuangan b on a.`kdakun`=b.kdakun
                    left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".$data['tanggal']."' and b.is_deleted='0' group by a.kdakun) c on a.kdakun=c.kdakun
                    left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$data['tanggal']."' and '".$data['tanggal']."' and b.is_deleted='0' group by a.kdakun) d on a.kdakun=d.kdakun where a.kdakun like '5-%' group by kdakun ) a")->row();



        if ($cekdata=='0') {


            $this->db->query("INSERT INTO t_keuangan ( tgltrx, kdjentrx, nobukti, ket, kdakun , d , k , is_transaksi ) VALUES 
                                         ( '".$data['tanggal']."', '0', 'Laba Tahun Berjalan ".$data['tanggal']."', 'Laba Tahun Berjalan Kredit', '3-2002' , '0' , '".$kredit->saldoakhir."' , '2' )");



            $this->db->query("INSERT INTO t_keuangan ( tgltrx, kdjentrx, nobukti, ket, kdakun , d , k , is_transaksi ) VALUES 
                                         ( '".$data['tanggal']."', '0', 'Laba Tahun Berjalan ".$data['tanggal']."', 'Laba Tahun Berjalan Debet', '3-2002' , '".$debit->saldoakhir."' , '0' , '2' )");

        } else {

            $this->db->query("UPDATE t_keuangan SET k='".$kredit->saldoakhir."' WHERE  tgltrx='".$data['tanggal']."' and ket='Laba Tahun Berjalan Kredit' and is_deleted='0' and is_transaksi='2'");

            $this->db->query("UPDATE t_keuangan SET d='".$debit->saldoakhir."' WHERE  tgltrx='".$data['tanggal']."' and ket='Laba Tahun Berjalan Debet' and is_deleted='0' and is_transaksi='2'");

        }

        echo "kredit ".$kredit->saldoakhir;
        echo "debet ".$debit->saldoakhir;
        echo $cekdata;
        
    }

    function cariakunopname(){
        $search = "";
        $cari = $this->input->post('search');
        // $page = $this->input->post('per_page');
        if($cari!=="") $search = " where ( text LIKE '%$cari%')";
        $query = $this->db->query("SELECT * FROM (SELECT a.kdakun as id , concat(kdakun, ' | ', namaakun) as 'text' from m_akun a where is_deleted='0' and is_opname='1'  order by a.kdakun) a $search ")->result();
        echo json_encode($query);
    }


    function cekstokawal() {
        $data = $this->input->post();
        $query = $this->db->query("SELECT a.* from t_stok_summary a WHERE a.is_deleted='0' and a.id_barang='".$data['id']."'");
        if ($query->num_rows() > 0) {
            echo json_encode(array('success'=>true,'data'=>$query->row()));
        } else {
            echo json_encode(array('success'=>false));
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
