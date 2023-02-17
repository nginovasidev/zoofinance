<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends MY_Controller
{
    var $modul = "keuangan";

    function __construct()
    {
        parent::__construct();
        parent::_privileges();
        $this->load->library(array('excel','session'));
    }

// begin manakun
    function manakun_load()
    {
        $query = "select *, FORMAT(saldoawal_d, '#,#') as ssaldoawal_d, FORMAT(saldoawal_k, '#,#') as ssaldoawal_k from m_akun a where a.is_deleted= '0' ";
        $where = ["a.kdakun","a.namaakun", "a.normalbalance", "a.posisi", "a.saldoawal_d", "a.saldoawal_k"];


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
             }, $where)) . ")" : $query) . " order by a.kdakun ";

        $allData = $this->db->query($query)->num_rows();
        $filteredData = $this->db->query($q)->num_rows();

        $q .= $length > -1 ? " limit ".$start.",".$length : "";

        $result = $this->db->query($q)->result();

        echo json_encode(array("data" => $result, "recordsTotal" => $allData, "recordsFiltered" => $filteredData, 'debug' => array('last_query' => $this->db->last_query(), 'start' => $start, 'length' => $length)));

    }

    function manakun_save()
    {
        $_POST['saldoawal_d'] = str_replace(",","",$_POST['saldoawal_d']);
        $_POST['saldoawal_k'] = str_replace(",","",$_POST['saldoawal_k']);
        parent::_insert('m_akun', $_POST);

        $cekketsaldo = $this->db->query("SELECT * FROM m_akun where kdakun='".$_POST['idparent']."' and is_saldo='1' ")->num_rows();
        if ($cekketsaldo>0) {
            if ($this->db->query("UPDATE m_akun set is_saldo='0' where kdakun='".$_POST['idparent']."' ")) {

                if ($this->db->query("SELECT * FROM m_akun WHERE idparent='".$_POST['idparent']."' ")->num_rows()==0) {
                
                        $this->db->query("UPDATE m_akun set is_saldo='1' where kdakun='".$_POST['idparent']."' ");
               
                }
            };
        }
    }

    function manakun_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.* ,FORMAT(a.saldoawal_d, '#,#') as ssaldoawal_d, FORMAT(a.saldoawal_k, '#,#') as ssaldoawal_k, b.namaakun as namaparent FROM m_akun a LEFT JOIN m_akun b ON a.idparent=b.kdakun

        where a.id = '".$data['id']."'";
        parent::_edit('m_akun', $_POST , $query);
    }

    function manakun_delete()
    {
        parent::_delete('m_akun', $_POST);
    }

// end manakun

// begin manbarang
    function manbarang_load()
    {
        $query = "SELECT * FROM m_fifo_nama_barang a WHERE a.is_deleted='0' ";
        $where = ["a.nama_barang"];

        $start = $_POST["start"];
        $length = $_POST["length"];
        $search = $_POST["search"];
        $order = $_POST["order"][0];
        $columns = $_POST["columns"];
        $key = $search["value"];
        $orderColumn = $columns[$order["column"]]["data"];
        $orderDirection = $order["dir"];

        $q = ($key != "" ? $query . " and (" .implode(" or ", array_map(function($x) use ($key) {return $x . " like '%". $key ."%'";}, $where)) . ")" : $query) . " order by a.created_at desc ";

        $allData = $this->db->query($query)->num_rows();
        $filteredData = $this->db->query($q)->num_rows();

        $q .= $length > -1 ? " limit ".$start.",".$length : "";

        $result = $this->db->query($q)->result();
        echo json_encode(array("data" => $result, "recordsTotal" => $allData, "recordsFiltered" => $filteredData, 'debug' => array('last_query' => $this->db->last_query(), 'start' => $start, 'length' => $length)));
    }

    function manbarang_save()
    {
        parent::_insert('m_fifo_nama_barang', $_POST);
        $cekbarang = $this->db->query("SELECT * FROM m_fifo_nama_barang where nama_barang='".$_POST['nama_barang']."' and is_deleted='0' ")->num_rows();
        if ($cekbarang==0) {
            $this->db->query("INSERT INTO m_fifo_nama_barang (nama_barang) VALUES ('".$_POST['nama_barang']."') ");
        } else {
            $this->db->query("UPDATE m_fifo_nama_barang set is_deleted='0' where nama_barang='".$_POST['nama_barang']."' ");
        }
    }

    function manbarang_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.* FROM m_fifo_nama_barang a WHERE a.id = '".$data['id']."'";
        parent::_edit('m_fifo_nama_barang', $_POST , $query);
    }

    function manbarang_delete()
    {
        parent::_delete('m_fifo_nama_barang', $_POST);
    }

// end manbarang

// begin manstok
    function manstok_load()
    {
        $query = "SELECT a.id, b.nama_barang, a.ttl_qty_barang, a.ttl_hrg_barang
                    FROM t_stok_summary a
                    JOIN m_fifo_nama_barang b ON b.id = a.barang_id
                    WHERE a.is_deleted='0'";
        $where = ["b.nama_barang", "a.ttl_qty_barang", "a.ttl_hrg_barang"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function manstok_save()
    {
        $userID = $this->session->userdata('id');
        $kategori = $_POST['kategori'];
        $id_barang = $_POST['nama_barang'];
        $after_qty = $_POST['after_qty'];
        $before_qty = $_POST['before_qty'];
        $tgl_peroleh = $_POST['tgl_peroleh'];
        $hrg_peroleh = $_POST['hrg_peroleh'];

        // print_r($_POST);
        // die();
        if ($kategori=='0') {
            // pembelian

            $data_t_fifo = [
                'id_barang' => $id_barang,
                'kategori' => $kategori,
                'before_qty' => $before_qty,
                'after_qty' => $after_qty,
                'created_by' => $this->session->userdata('id')
            ];

            $this->db->insert('t_fifo', $data_t_fifo);

            // $query = "INSERT INTO t_fifo (id_barang, kategori, before_qty, after_qty, created_by) VALUES ('".$id_barang."', '".$kategori."', '".$before_qty."', '".$qty_barang."', '".$userID."') ";
            // if ($this->db->query($query)) {
            //     $query2 = "INSERT INTO t_stok_barang (id_barang, qty_barang, tgl_peroleh, hrg_peroleh, created_by) VALUES ('".$id_barang."', '".$qty_barang."', '".$tgl_peroleh."', '".$hrg_peroleh."', '".$userID."') ";
            //     $this->db->query($query2);
            //     echo json_encode(array('success' => true, 'message' => 'Data berhasil disimpan'));
            // } else {
            //     echo json_encode(array('success' => false, 'message' => 'Data gagal disimpan'));
            // }
        } else if ($kategori=='1') {
            // pemakaian
            $query = "INSERT INTO t_fifo (id_barang, kategori, before_qty, after_qty, created_by) VALUES ('".$id_barang."', '".$kategori."', '".$before_qty."', '".$qty_barang."', '".$userID."') ";
            if ($this->db->query($query)) {
                $query2 = "INSERT INTO t_stok_barang (id_barang, qty_barang, tgl_peroleh, hrg_peroleh, created_by) VALUES ('".$id_barang."', '".$qty_barang."', '".$tgl_peroleh."', '".$hrg_peroleh."', '".$userID."') ";
                $this->db->query($query2);
                echo json_encode(array('success' => true, 'message' => 'Data berhasil disimpan'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Data gagal disimpan'));
            }
        }
    }

    function manstok_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.* FROM m_fifo_nama_barang a WHERE a.id = '".$data['id']."'";
        parent::_edit('m_fifo_nama_barang', $_POST , $query);
    }

    function manstok_delete()
    {
        parent::_delete('m_fifo_nama_barang', $_POST);
    }

// end manstok

// begin inventaris
    function inventaris_load()
    {
        $query = "select *, FORMAT(jml_barang, 0) as jmlperoleh ,FORMAT(hrg_peroleh, 0) as hrgperoleh from m_inventaris a where a.is_deleted= '0' ";
        $where = ["a.nama_barang","a.jml_barang", "a.tgl_peroleh", "a.hrg_peroleh", "a.kategori"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function inventaris_save()
    {
        parent::_insert('m_inventaris', $_POST);
    }

    function inventaris_edit()
    {
        $data = $this->input->post();
        $query = "select * from m_inventaris a
        where a.id = '".$data['id']."'";
        parent::_edit('m_inventaris', $_POST , $query);
    }

    function inventaris_delete()
    {
        parent::_delete('m_inventaris', $_POST);
    }

// end inventaris


    // begin Pembayaran
    function pembayaran_load()
    {

        if ($_SESSION['menu']['0']->user_type_code=='FTI' or $_SESSION['menu']['0']->user_type_code=='FNT') {

            $filteruser = "AND a.created_by='".$_SESSION['id']."'";

        } else {

            $filteruser = "";
        }
        // $query = "SELECT a.*, FORMAT(sum(a.d), 0) as saldo_d, FORMAT(if(a.k=0, a.d , a.k), 0) as saldo_k, b.namaakun from t_keuangan a left join m_akun b on a.kdakun=b.kdakun where a.is_deleted= '0' and a.is_transaksi='1' and a.is_parent='0'
        //  AND a.tgltrx='".$_POST["tgl_trx"]."' $filteruser ";

         $query = "SELECT a.* , format(a.k, 0) as kredit , format(a.d, 0) as debet , b.namaakun , concat(a.tgltrx,a.nobukti) as edit_delete
                from t_keuangan a 
                left join m_akun b on a.kdakun=b.kdakun where a.is_deleted= '0' and a.is_transaksi='1' $filteruser ";

         // $query = "SELECT a.* , format(a.k, 0) as kredit , format(a.d, 0) as debet , b.namaakun , concat(a.tgltrx,a.nobukti) as edit_delete
         //        from t_keuangan a 
         //        left join m_akun b on a.kdakun=b.kdakun where a.is_deleted= '0' and a.is_transaksi='1' 
         // AND a.tgltrx='".$_POST["tgl_trx"]."' $filteruser ";
          // $filteruser ";
        // $where = ["a.ket"];
        $where = ["a.tgltrx","a.nobukti", "a.ket", "a.d" , "a.k"];
        

        // parent::_loadDatatable($query, $where, $_POST);


        $start = $_POST["start"];
        $length = $_POST["length"];
        $search = $_POST["search"];
        $order = $_POST["order"][0];
        $columns = $_POST["columns"];
        $key = $search["value"];
        // $orderColumn = $columns[$order["column"]]["data"];
        // $orderColumn = "a.tgltrx";
        $orderColumn = "a.tgltrx , a.nobukti";
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

    function pembayaran_save()
    {
        $cekyear = $this->db->query("SELECT * from m_tutup_tahun where tahun='".date("Y")."' and `is_block`='0'");
        if ($cekyear->num_rows()==0) {
            echo json_encode(array('success' => false, 'message' => 'Maaf sudah tutup tahun'));
        } else {
            $cekconfigtrx = $this->db->query("SELECT * FROM m_config_trx a where a.tgl='".$_POST['tgltrx']."' ");

            if ($cekconfigtrx->num_rows()==0) {
                echo json_encode(array('success' => false, 'message' => 'Maaf tanggal transaksi belum tergenerate'));
            } else {

                if ($cekconfigtrx->row()->is_block==0) {
                    // echo json_encode(array('success' => false, 'message' => 'Lancar'));

                    if ($_POST['kdjentrx']=="") {
                        echo json_encode(array('success' => false, 'message' => 'Pilih Jenis Transaksi Terlebih Dahulu'));
                    } else if ($_POST['tgltrx']=="") {
                        echo json_encode(array('success' => false, 'message' => 'Tentukan Tanggal Transaksi'));
                    } else if ($_POST['nobukti']=="") {
                        echo json_encode(array('success' => false, 'message' => 'Mohon nomor bukti diisi terlebih dahulu'));
                    } else if ($_POST['ket']=="") {
                        echo json_encode(array('success' => false, 'message' => 'Mohon keterangan diisi terlebih dahulu'));
                    } else if (isset($_POST['kdakunutama'])==0) {
                        echo json_encode(array('success' => false, 'message' => 'Mohon memilih akun '));
                    } else {

                        if (isset($_POST['kdakun'])!='') {


                            $data = array();
                            $datautama = array(
                                                "id" => $_POST['idakunutama'],
                                                "kdjentrx" => $_POST['kdjentrx'],
                                                "tgltrx" => $_POST['tgltrx'],
                                                "nobukti" => $_POST['nobukti'],
                                                "ket" => $_POST['ket'],
                                                "kdakun" => $_POST['kdakunutama'],
                                                "d" => str_replace(",","",$_POST['dutama']),
                                                "k" => str_replace(",","",$_POST['kutama']),
                                                "is_parent" => 0,
                                                "is_transaksi" => 1
                                                );

                            array_push($data, $datautama);
                            for ($i=0; $i < count($_POST['kdakun']); $i++) { 
                                $dataarray = array(
                                                "id" => $_POST['id'][$i],
                                                    "kdjentrx" => $_POST['kdjentrx'],
                                                    "tgltrx" => $_POST['tgltrx'],
                                                    "nobukti" => $_POST['nobukti'],
                                                    "ket" => $_POST['ket'],
                                                    "kdakun" => $_POST['kdakun'][$i],
                                                    "d" => isset($_POST['d'][$i])==1? str_replace(",","",$_POST['d'][$i]) : 0,
                                                    "k" => isset($_POST['k'][$i])==1? str_replace(",","",$_POST['k'][$i]) : 0,
                                                    "is_parent" => 1,
                                                    "is_transaksi" => 1
                                                );
                                array_push($data, $dataarray);
                            }
                            if (isset($_POST['is_deleted'])) {
                                for ($i=0; $i < count($_POST['is_deleted']); $i++) { 
                                    $dataarray = array(
                                                    'id' => $_POST['is_deleted'][$i],
                                                    // 'tgltrx' => NULL,
                                                    'is_deleted' => 1
                                                );
                                    array_push($data, $dataarray);
                                } 
                            }
                            // echo json_encode($data);
                            parent::_insertbatch('t_keuangan', $data );
                        } else {
                            echo json_encode(array('success' => false, 'message' => 'Pilih Akun pada tabel perkiraan'));
                        }

                    }

                } elseif ($cekconfigtrx->row()->is_block==1) {
                    echo json_encode(array('success' => false, 'message' => 'Maaf tanggal transaksi sudah di tutup'));
                }
            }
        }
        
    }

    function pembayaran_edit()
    {

        $data = $this->input->post();
        $cekid = $this->db->query("SELECT nobukti FROM t_keuangan WHERE id='".$data['id']."'")->row();

        $query = "SELECT a.kdjentrx ,a.tgltrx, a.nobukti, a.ket, JSON_ARRAYAGG(JSON_OBJECT('idtrx', a.id, 'kd_akun', a.kdakun, 'nama_akun' , b.namaakun, 'debet' , a.d, 'kredit', a.k , 'is_parent' , a.is_parent, 'kdjentrx' , a.kdjentrx, 'is_deleted', a.is_deleted) ) AS arr_akun FROM t_keuangan a LEFT JOIN m_akun b ON a.kdakun=b.kdakun WHERE a.nobukti='".$cekid->nobukti."' and a.is_deleted='0' and a.is_transaksi='1' GROUP BY a.nobukti ";
        parent::_edit('t_keuangan', $_POST , $query);
    }

    function pembayaran_delete()
    {
        $data = $this->input->post();
        $cekid = $this->db->query("SELECT tgltrx,nobukti FROM t_keuangan WHERE id='".$data['id']."'")->row();
        // echo json_encode($cekid);
        parent::_deletearray('t_keuangan', $cekid , 'nobukti');
    }
    // end Pembayaran




    // begin jurnal umum

    function jurnalumum_load()
    {

        if ($_SESSION['menu']['0']->user_type_code=='FTI' or $_SESSION['menu']['0']->user_type_code=='FNT') {

            $filteruser = "AND a.created_by='".$_SESSION['id']."'";

        } else {

            $filteruser = "";
        }
        $query = "SELECT a.*, FORMAT(sum(a.d), 0) as saldo_d, FORMAT(if(a.k=0, a.d , a.k), 0) as saldo_k, b.namaakun from t_keuangan a left join m_akun b on a.kdakun=b.kdakun where a.is_deleted= '0' and a.is_transaksi='0' and a.is_parent='0' $filteruser ";
        $where = ["a.tgltrx","a.nobukti", "a.ket", "a.d" , "a.k"];

        // parent::_loadDatatable($query, $where, $_POST);


        $start = $_POST["start"];
        $length = $_POST["length"];
        $search = $_POST["search"];
        $order = $_POST["order"][0];
        $columns = $_POST["columns"];
        $key = $search["value"];
        // $orderColumn = $columns[$order["column"]]["data"];
        $orderColumn = "a.tgltrx , a.nobukti";
        $orderDirection = $order["dir"];


        $q = ($key != "" ? $query . " and (" .implode(" or ", array_map(function($x) use ($key) {
                    return $x . " like '%". $key ."%'";
             }, $where)) . ")" : $query) . " group by a.nobukti order by ".$orderColumn." ".$orderDirection;

        $allData = $this->db->query($query)->num_rows();
        $filteredData = $this->db->query($q)->num_rows();

        $q .= $length > -1 ? " limit ".$start.",".$length : "";

        $result = $this->db->query($q)->result();

        echo json_encode(array("data" => $result, "recordsTotal" => $allData, "recordsFiltered" => $filteredData, 'debug' => array('last_query' => $this->db->last_query(), 'start' => $start, 'length' => $length)));
    }

    function jurnalumum_save()
    {

        $cekyear = $this->db->query("SELECT * from m_tutup_tahun where tahun='".date("Y")."' and `is_block`='0'");
        if ($cekyear->num_rows()==0) {
            echo json_encode(array('success' => false, 'message' => 'Maaf sudah tutup tahun'));
        } else {

            $cekconfigtrx = $this->db->query("SELECT * FROM m_config_trx a where a.tgl='".$_POST['tgltrx']."' ");

            if ($cekconfigtrx->num_rows()==0) {
                echo json_encode(array('success' => false, 'message' => 'Maaf tanggal transaksi sudah di tutup'));
            } else {

                if ($cekconfigtrx->row()->is_block==0) {
                    // echo json_encode(array('success' => false, 'message' => 'Lancar'));

                    if ($_POST['tgltrx']=="") {
                        echo json_encode(array('success' => false, 'message' => 'Tentukan Tanggal Transaksi'));
                    } else if ($_POST['nobukti']=="") {
                        echo json_encode(array('success' => false, 'message' => 'Mohon nomor bukti diisi terlebih dahulu'));
                    } else if ($_POST['ket']=="") {
                        echo json_encode(array('success' => false, 'message' => 'Mohon keterangan diisi terlebih dahulu'));
                    }  else {

                        if (isset($_POST['kdakun'])!='') {

                            $data = array();
                            for ($i=0; $i < count($_POST['kdakun']); $i++) { 
                                $dataarray = array(
                                                "id" => $_POST['id'][$i],
                                                "tgltrx" => $_POST['tgltrx'],
                                                "nobukti" => $_POST['nobukti'],
                                                "ket" => $_POST['ket'],
                                                "kdakun" => $_POST['kdakun'][$i],
                                                "d" => isset($_POST['d'][$i])==1? str_replace(",","",$_POST['d'][$i]) : 0,
                                                "k" => isset($_POST['k'][$i])==1? str_replace(",","",$_POST['k'][$i]) : 0

                                                );
                                array_push($data, $dataarray);
                            }
                            if (isset($_POST['is_deleted'])) {
                                for ($i=0; $i < count($_POST['is_deleted']); $i++) { 
                                    $dataarray = array(
                                                    'id' => $_POST['is_deleted'][$i],
                                                    'tgltrx' => NULL,
                                                    'is_deleted' => 1
                                                );
                                    array_push($data, $dataarray);
                                } 
                            }
                            parent::_insertbatch('t_keuangan', $data );
                        } else {
                            echo json_encode(array('success' => false, 'message' => 'Pilih Akun pada tabel perkiraan'));
                        }

                    }

                } elseif ($cekconfigtrx->row()->is_block==1) {
                    echo json_encode(array('success' => false, 'message' => 'Maaf tanggal transaksi sudah di tutup'));
                }
            }
        }


    }

    function jurnalumum_edit()
    {
        $data = $this->input->post();
        $cekid = $this->db->query("SELECT nobukti FROM t_keuangan WHERE id='".$data['id']."'")->row();

        $query = "SELECT a.tgltrx, a.nobukti, a.ket, JSON_ARRAYAGG(JSON_OBJECT('idtrx', a.id, 'kd_akun', a.kdakun, 'nama_akun' , b.namaakun, 'debet' , a.d, 'kredit', a.k) ) AS arr_akun FROM t_keuangan a LEFT JOIN m_akun b ON a.kdakun=b.kdakun WHERE a.nobukti='".$cekid->nobukti."' and a.is_deleted='0' and a.is_transaksi='0' GROUP BY a.nobukti ";
        parent::_edit('t_keuangan', $_POST , $query);
    }

    function jurnalumum_delete()
    {

        $data = $this->input->post();
        $cekid = $this->db->query("SELECT tgltrx,nobukti FROM t_keuangan WHERE id='".$data['id']."'")->row();
        // echo json_encode($cekid);
        parent::_deletearray('t_keuangan', $cekid , 'nobukti');
    }


    function importexcel_load(){
        // echo json_decode($_FILES["fileExcel"]["name"]);
        if (isset($_FILES["fileExcel"]["name"])) {
            $path = $_FILES["fileExcel"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);


            $cekakun = $this->db->query("SELECT * FROM m_akun where kdakun='".$_POST['akun']."' ")->row();
            $akuntampungan = $this->db->query("SELECT kdakun , namaakun from m_akun where is_tampungan='1' ")->row();

	        $dataarrayimport = array();
            ?>
            <form id="insertimport">
                <input type="hidden" class="form-control" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>

	            <table border="1" width="100%" class="table table-bordered">
	                <tr>
	                    <th>NO</th>
	                    <th>PERKIRAAN</th>
	                    <th>TANGGAL</th>
	                    <th>KETERANGAN</th>
	                    <th>DEBET</th>
	                    <th>KREDIT</th>
	                </tr>

            <?php
            // PHPExcel_Style_NumberFormat
            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();  
                $no = 0 ;  
                $nobukti = 0 ;  

                $tglarrayy = array();
                for($row=6; $row<=$highestRow; $row++)
                {
                    
                    $tgltrx = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCellByColumnAndRow(1, $row)->getValue())) ;
                    $ket = $worksheet->getCellByColumnAndRow(3, $row)->getValue();


                    $d = substr($worksheet->getCellByColumnAndRow(4, $row)->getValue(), 0 , 1)!='-'? $worksheet->getCellByColumnAndRow(4, $row)->getValue() : 0;
                    $k = substr($worksheet->getCellByColumnAndRow(4, $row)->getValue(), 0 , 1)=='-'? substr($worksheet->getCellByColumnAndRow(4, $row)->getValue(), 1) : 0;

                    $tglarray = $tgltrx;

                    array_push($tglarrayy, $tglarray);
                      $nobukti++;
                    for ($i=0; $i < 2; $i++) { 
                      $no++;
                      
                      if ($i==0) {
	                      	$dataarray = array(
	                            "tgltrx" => $tgltrx,
	                            // "nobukti" => $_POST['nobukti'],
	                            "ket" => $ket,
	                            "kdakun" => $cekakun->kdakun,
	                            "d" => isset($d)==1? str_replace(",","",$d) : 0,
	                            "k" => isset($k)==1? str_replace(",","",$k) : 0

	                            );
				            array_push($dataarrayimport, $dataarray);
                      	?>

                    <tr>
                        <td><?=$no?></td>
                        <td><input type="hidden" name="kdakun[]" value="<?=$cekakun->kdakun?>"><?=$cekakun->kdakun . ' | '. $cekakun->namaakun?></td>
                        <td><input type="hidden" name="tgltrx[]" value="<?=$tgltrx?>"><input type="hidden" name="nobukti[]" value="<?=date("dmyhis").''.str_pad($nobukti, 3, '0', STR_PAD_LEFT)?>"><?=$tgltrx?></td>
                        <td><input type="hidden" name="ket[]" value="<?=$ket?>"><?=$ket?></td>
                        <td align="right"><input type="hidden" name="d[]" value="<?=$d==''? '0' : $d?>"><?=number_format($d)?></td>
                        <td align="right"><input type="hidden" name="k[]" value="<?=$k==''? '0' : $k?>"><?=number_format($k)?></td>
                    </tr>

                  	  <?php
                      } else if ($i==1) {

                      	$dataarray = array(
	                            "tgltrx" => $tgltrx,
	                            // "nobukti" => $_POST['nobukti'],
	                            "ket" => $ket,
	                            "kdakun" => $akuntampungan->kdakun,
	                            "d" => isset($k)==1? str_replace(",","",$k) : 0,
	                            "k" => isset($d)==1? str_replace(",","",$d) : 0

	                            );
				            array_push($dataarrayimport, $dataarray);
                      	 ?>

	                    <tr>
	                        <td><?=$no?></td>
	                        <td><input type="hidden" name="kdakun[]" value="<?=$akuntampungan->kdakun?>"><?=$akuntampungan->kdakun?> | <?=$akuntampungan->namaakun?></td>
	                        <td><input type="hidden" name="tgltrx[]" value="<?=$tgltrx?>"><input type="hidden" name="nobukti[]" value="<?=date("dmyhis").''.str_pad($nobukti, 3, '0', STR_PAD_LEFT)?>"><?=$tgltrx?></td>
	                        <td><input type="hidden" name="ket[]" value="<?=$ket?>"><?=$ket?></td>
	                        <td align="right"><input type="hidden" name="d[]" value="<?=$k==''? '0' : $k?>"><?=number_format($k)?></td>
	                        <td align="right"><input type="hidden" name="k[]" value="<?=$d==''? '0' : $d?>"><?=number_format($d)?></td>
	                    </tr>
                      <?php }


                    }  
                }
            }
            ?>
		            <tr>
		            	<th colspan="4" style="text-align: center;">TOTAL</th>
		            	<th style="text-align: right;"><?=number_format(array_sum(array_column($dataarrayimport, 'd')))?></th>
		            	<th style="text-align: right;"><?=number_format(array_sum(array_column($dataarrayimport, 'k')))?></th>
		            </tr>
	            </table>
                <?php 
                    if (count(array_unique($tglarrayy))==1) {
                        ?>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        <?php 
                    } else {
                        ?>
                            <button class="btn btn-primary" disabled="">Simpan</button>
                            <small style="color: red">Data terdeteksi Lebih dari 1 hari</small>
                        <?php 
                    }
                ?>
            </form>
            <?php 
        }else{
            echo "Tidak ada file yang masuk";
        }
    }

    function importexcel_save(){

        $cekconfigtrx = $this->db->query("SELECT * FROM m_config_trx a where a.tgl='".$_POST['tgltrx'][0]."' ");

        if ($cekconfigtrx->num_rows()==0) {
            echo json_encode(array('success' => false, 'message' => 'Maaf tanggal transaksi sudah di tutup'));
        } else {

            if ($cekconfigtrx->row()->is_block==0) {

                $this->db->trans_begin();
        
                $data = array();
                for ($i=0; $i < count($_POST['kdakun']); $i++) { 
                    $dataarray = array(
                                        "id" => '',
                                        "tgltrx" => $_POST['tgltrx'][$i],
                                        "nobukti" => $_POST['nobukti'][$i],
                                        "ket" => $_POST['ket'][$i],
                                        "kdakun" => $_POST['kdakun'][$i],
                                        "d" => isset($_POST['d'][$i])==1? str_replace(",","",$_POST['d'][$i]) : 0,
                                        "k" => isset($_POST['k'][$i])==1? str_replace(",","",$_POST['k'][$i]) : 0,
                                        "is_parent" => 0,
                                        "is_transaksi" => 0
                                    );
                    array_push($data, $dataarray);
                }

                    $this->db->insert_batch('t_keuangan', $data);

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

            } elseif ($cekconfigtrx->row()->is_block==1) {
                echo json_encode(array('success' => false, 'message' => 'Maaf tanggal transaksi sudah di tutup'));
            }
        }

    }


    function cashopname_load()
    {

        if ($_SESSION['menu']['0']->user_type_code=='FTI' or $_SESSION['menu']['0']->user_type_code=='FNT') {

            $filteruser = "AND a.created_by='".$_SESSION['id']."'";

        } else {

            $filteruser = "";
        }
        $query = "SELECT a.*, FORMAT(sum(a.d), 0) as saldo_d, FORMAT(if(a.k=0, a.d , a.k), 0) as saldo_k, b.namaakun from t_keuangan a left join m_akun b on a.kdakun=b.kdakun where a.is_deleted= '0' and a.is_transaksi='0' and a.is_parent='0' $filteruser ";
        $where = ["a.nama_barang","a.jml_barang", "a.tgl_peroleh", "a.hrg_peroleh", "a.kategori"];

        // parent::_loadDatatable($query, $where, $_POST);


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
             }, $where)) . ")" : $query) . " group by a.nobukti order by ".$orderColumn." ".$orderDirection;

        $allData = $this->db->query($query)->num_rows();
        $filteredData = $this->db->query($q)->num_rows();

        $q .= $length > -1 ? " limit ".$start.",".$length : "";

        $result = $this->db->query($q)->result();

        echo json_encode(array("data" => $result, "recordsTotal" => $allData, "recordsFiltered" => $filteredData, 'debug' => array('last_query' => $this->db->last_query(), 'start' => $start, 'length' => $length)));
    }

    function cashopname_save()
    {
        $_POST['saldo_kas'] = str_replace(",","",$_POST['saldo_kas']);
        parent::_insert('t_opname', $_POST);

    }

    function cashopname_edit()
    {
        $data = $this->input->post();

        $query = " SELECT  a.kdakun as kd_akun , b.*, format(c.saldo , 0) as saldo ,  '".$data['tgl']."' as tgltrx from m_akun a left join (SELECT * FROM t_opname where tgl='".$data['tgl']."') b on a.kdakun=b.kdakun left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldo from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<='".$data['tgl']."' where a.is_cash='1' and a.is_deleted='0' group by a.kdakun) c on a.kdakun=c.kdakun where a.is_cash='1' and a.is_deleted='0' and a.kdakun='".$data['akunopname']."' ";
        parent::_edit('t_opname', $_POST , $query);
    }

    function cashopname_delete()
    {

        $data = $this->input->post();
        $cekid = $this->db->query("SELECT tgltrx,nobukti FROM t_keuangan WHERE id='".$data['id']."'")->row();
        // echo json_encode($cekid);
        parent::_deletearray('t_keuangan', $cekid , 'nobukti');
    }



    // begin prosestutuphari
    function prosestutuphari_load()
    {
        $query = "select *, FORMAT(jml_barang, 0) as jmlperoleh ,FORMAT(hrg_peroleh, 0) as hrgperoleh from m_config_trx a where a.is_deleted= '0' ";
        $where = ["a.nama_barang","a.jml_barang", "a.tgl_peroleh", "a.hrg_peroleh", "a.kategori"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function prosestutuphari_save()
    {
        // parent::_insert('m_config_trx', $_POST);

        $_POST['last_edited_at'] = date('Y-m-d H:i:s');
        $_POST['last_edited_by'] = $this->session->userdata('id');
        $_POST['block_at'] = date('Y-m-d H:i:s');
        $_POST['block_by'] = $this->session->userdata('id');

        if($this->db->where('tgl',$_POST['tgl'])->update('m_config_trx', $_POST)){
            echo json_encode(array('success' => true, 'message' => $_POST, 'debug' => 'm_config_trx'));
        }else{
            echo json_encode(array('success' => false, 'message' => $this->db->error()));
        }
    }

    function prosestutuphari_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.tgl , if(a.is_block=1, 'dikunci' , 'dibuka' ) as is_block, b.user_name , a.block_at FROM `m_config_trx` a left join master_user b on a.block_by=b.id WHERE a.tgl= '".$data['tgl']."'";
        parent::_edit('m_config_trx', $_POST , $query);
    }

    function prosestutuphari_delete()
    {
        parent::_delete('m_config_trx', $_POST);
    }

    // end prosestutuphari


    // begin tutup tahun
    function prosestutuptahun_load()
    {
        $query = "select *, FORMAT(jml_barang, 0) as jmlperoleh ,FORMAT(hrg_peroleh, 0) as hrgperoleh from m_tutup_tahun a where a.is_deleted= '0' ";
        $where = ["a.nama_barang","a.jml_barang", "a.tgl_peroleh", "a.hrg_peroleh", "a.kategori"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function prosestutuptahun_save()
    {
        // parent::_insert('m_config_trx', $_POST);

        $_POST['last_edited_at'] = date('Y-m-d H:i:s');
        $_POST['last_edited_by'] = $this->session->userdata('id');
        $_POST['block_at'] = date('Y-m-d H:i:s');
        $_POST['block_by'] = $this->session->userdata('id');

        if($this->db->where('tahun',$_POST['tahun'])->update('m_tutup_tahun', $_POST)){
            echo json_encode(array('success' => true, 'message' => $_POST, 'debug' => 'm_tutup_tahun'));
        }else{
            echo json_encode(array('success' => false, 'message' => $this->db->error()));
        }
    }

    function prosestutuptahun_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.tgl , if(a.is_block=1, 'dikunci' , 'dibuka' ) as is_block, b.user_name , a.block_at FROM `m_tutup_tahun` a left join master_user b on a.block_by=b.id WHERE a.tgl= '".$data['tgl']."'";
        parent::_edit('m_tutup_tahun', $_POST , $query);
    }

    function prosestutuptahun_delete()
    {
        parent::_delete('m_tutup_tahun', $_POST);
    }

    // end tutup tahun


        // begin rkap 
    function rkap_load()
    {
        $data = $this->input->post();
        $query = "SELECT * FROM (SELECT b.id, a.kdakun, a.namaakun, b.tahun ,ifnull(b.nominal, 0) as nominal , a.is_deleted from m_akun a left join (select * from t_rkap where tahun='".$_POST['tahun']."') b on a.id=b.id_akun and b.is_deleted='0' where a.is_deleted='0' and a.is_saldo='1' order by a.kdakun) a where a.is_deleted='0' ";
        parent::_loadAjax('t_rkap', $_POST , $query);
    }

    function rkap_save()
    {
        // parent::_insert('m_config_trx', $_POST);

        $_POST['last_edited_at'] = date('Y-m-d H:i:s');
        $_POST['last_edited_by'] = $this->session->userdata('id');
        $_POST['block_at'] = date('Y-m-d H:i:s');
        $_POST['block_by'] = $this->session->userdata('id');

        if($this->db->where('tahun',$_POST['tahun'])->update('t_rkap', $_POST)){
            echo json_encode(array('success' => true, 'message' => $_POST, 'debug' => 'm_tutup_tahun'));
        }else{
            echo json_encode(array('success' => false, 'message' => $this->db->error()));
        }
    }

    function rkaphot_save()
    {
        if ($_POST['id']==null || $_POST['id']=="" ) {
            $cekidakun = $this->db->query("SELECT id FROM  m_akun where kdakun='".$_POST['kdakun']."' ")->row();
            $data['id'] = "";
            $data['id_akun'] = $cekidakun->id;
            $data['nominal'] = $_POST['nominal'];
            $data['tahun'] = $_POST['tahun'];
            parent::_insert('t_rkap', $data);
        } else {
            unset($_POST['kdakun']);
            parent::_insert('t_rkap', $_POST);
        }

    }

    function rkap_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.tgl , if(a.is_block=1, 'dikunci' , 'dibuka' ) as is_block, b.user_name , a.block_at FROM `t_rkap` a left join master_user b on a.block_by=b.id WHERE a.tgl= '".$data['tgl']."'";
        parent::_edit('t_rkap', $_POST , $query);
    }

    function rkap_delete()
    {
        parent::_delete('t_rkap', $_POST);
    }

    // end rkap


}
