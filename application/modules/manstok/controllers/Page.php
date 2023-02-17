<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends MY_Controller
{
    var $modul = 'manstok';

    function __construct()
    {
        parent::__construct();
        parent::_privileges();
    }

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

        $q = ($key != "" ? $query . " and (" . implode(" or ", array_map(function ($x) use ($key) {
            return $x . " like '%" . $key . "%'";
        }, $where)) . ")" : $query) . " order by a.created_at desc ";

        $allData = $this->db->query($query)->num_rows();
        $filteredData = $this->db->query($q)->num_rows();

        $q .= $length > -1 ? " limit " . $start . "," . $length : "";

        $result = $this->db->query($q)->result();
        echo json_encode(array("data" => $result, "recordsTotal" => $allData, "recordsFiltered" => $filteredData, 'debug' => array('last_query' => $this->db->last_query(), 'start' => $start, 'length' => $length)));
    }

    function manbarang_save()
    {
        parent::_insert('m_fifo_nama_barang', $_POST);
        $cekbarang = $this->db->query("SELECT * FROM m_fifo_nama_barang where nama_barang='" . $_POST['nama_barang'] . "' and is_deleted='0' ")->num_rows();
        if ($cekbarang == 0) {
            $this->db->query("INSERT INTO m_fifo_nama_barang (nama_barang) VALUES ('" . $_POST['nama_barang'] . "') ");
        } else {
            $this->db->query("UPDATE m_fifo_nama_barang set is_deleted='0' where nama_barang='" . $_POST['nama_barang'] . "' ");
        }
    }

    function manbarang_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.* FROM m_fifo_nama_barang a WHERE a.id = '" . $data['id'] . "'";
        parent::_edit('m_fifo_nama_barang', $_POST, $query);
    }

    function manbarang_delete()
    {
        parent::_delete('m_fifo_nama_barang', $_POST);
    }
// end manbarang

// begin stokin
    function stokin_load()
    {
        $query = "SELECT a.id, a.no_faktur, c.nama_barang, a.tgl_peroleh, b.qty_barang, b.hrg_peroleh
        FROM t_barang_masuk a
        JOIN t_barang_masuk_detail b ON b.faktur_id = a.no_faktur 
        JOIN m_fifo_nama_barang c ON c.id = b.id_barang 
        WHERE a.is_deleted ='0'";
        $where = ["a.tgl_peroleh", "a.no_faktur", "c.nama_barang", "b.qty_barang", "b.hrg_peroleh"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function stokin_save()
    {
        $data = $this->input->post();
        $this->db->trans_start();

        $save = [
            'no_faktur' => $data['no_faktur'],
            'tgl_peroleh' => $data['tgl_peroleh'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('id')
        ];
        $no_faktur = $this->db->query("SELECT * FROM t_barang_masuk where no_faktur='" . $data['no_faktur'] . "' ")->num_rows();
        if ($no_faktur == 0) {
            $this->db->insert('t_barang_masuk', $save);

            $save_detail = [];
            foreach ($data['nama_barang'] as $key => $value) {
                $save_detail[$key]['faktur_id'] = $save['no_faktur'];
                $save_detail[$key]['id_barang'] = $value;
                $save_detail[$key]['qty_barang'] = $data['jml_barang'][$key];
                $save_detail[$key]['hrg_peroleh'] = $data['hrg_peroleh'][$key];
                $save_detail[$key]['tgl_peroleh'] = $save['tgl_peroleh'];
                $save_detail[$key]['created_at'] = date('Y-m-d H:i:s');
                $save_detail[$key]['created_by'] = $this->session->userdata('id');
            }
            $this->db->insert_batch('t_barang_masuk_detail', $save_detail);

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit(); // Commit transaction
                $this->db->trans_complete();
                echo json_encode(array('success' => TRUE, 'message' => 'Data berhasil disimpan'));
            } else {
                $this->db->trans_rollback(); // Rollback transaction
                echo json_encode(array('success' => FALSE, 'message' => 'Data gagal disimpan', 'error' => $this->db->error()));
            }
        } else {
            echo json_encode(array('success' => FALSE, 'message' => 'No Faktur sudah ada'));
        }

    }

    function stokin_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.* FROM m_fifo_nama_barang a WHERE a.id = '" . $data['id'] . "'";
        parent::_edit('m_fifo_nama_barang', $_POST, $query);
    }

    function stokin_delete()
    {
        parent::_delete('m_fifo_nama_barang', $_POST);
    }
// end stokin
// ==============================================================================================================================
// ==============================================================================================================================
// begin stokout
    function stokout_load()
    {
        $query = "SELECT a.id, a.no_faktur, c.nama_barang, a.tgl_peroleh, b.qty_barang, b.hrg_peroleh
        FROM t_barang_keluar a
        JOIN t_barang_keluar_detail b ON b.faktur_id = a.no_faktur 
        JOIN m_fifo_nama_barang c ON c.id = b.id_barang 
        WHERE a.is_deleted ='0'";
        $where = ["a.tgl_peroleh", "a.no_faktur", "c.nama_barang", "b.qty_barang", "b.hrg_peroleh"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function stokout_save()
    {
        $data = $this->input->post();
        print_r('<pre>');
        print_r($data);
        print_r('<pre>');
        // $this->db->trans_start();

        // $save = [
        //     'no_faktur' => $data['no_faktur'],
        //     'tgl_peroleh' => $data['tgl_peroleh'],
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'created_by' => $this->session->userdata('id')
        // ];
        // $no_faktur = $this->db->query("SELECT * FROM t_barang_masuk where no_faktur='" . $data['no_faktur'] . "' ")->num_rows();
        // if ($no_faktur == 0) {
        //     $this->db->insert('t_barang_masuk', $save);

        //     $save_detail = [];
        //     foreach ($data['nama_barang'] as $key => $value) {
        //         $save_detail[$key]['faktur_id'] = $save['no_faktur'];
        //         $save_detail[$key]['id_barang'] = $value;
        //         $save_detail[$key]['qty_barang'] = $data['jml_barang'][$key];
        //         $save_detail[$key]['hrg_peroleh'] = $data['hrg_peroleh'][$key];
        //         $save_detail[$key]['tgl_peroleh'] = $save['tgl_peroleh'];
        //         $save_detail[$key]['created_at'] = date('Y-m-d H:i:s');
        //         $save_detail[$key]['created_by'] = $this->session->userdata('id');
        //     }
        //     $this->db->insert_batch('t_barang_masuk_detail', $save_detail);

        //     if ($this->db->trans_status() === TRUE) {
        //         $this->db->trans_commit(); // Commit transaction
        //         $this->db->trans_complete();
        //         echo json_encode(array('success' => TRUE, 'message' => 'Data berhasil disimpan'));
        //     } else {
        //         $this->db->trans_rollback(); // Rollback transaction
        //         echo json_encode(array('success' => FALSE, 'message' => 'Data gagal disimpan', 'error' => $this->db->error()));
        //     }
        // } else {
        //     echo json_encode(array('success' => FALSE, 'message' => 'No Faktur sudah ada'));
        // }

    }

    function stokout_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.* FROM m_fifo_nama_barang a WHERE a.id = '" . $data['id'] . "'";
        parent::_edit('m_fifo_nama_barang', $_POST, $query);
    }

    function stokout_delete()
    {
        parent::_delete('m_fifo_nama_barang', $_POST);
    }
// end stokout
}
