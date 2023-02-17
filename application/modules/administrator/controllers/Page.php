<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends MY_Controller
{
    var $modul = "administrator";

    function __construct()
    {
        parent::__construct();
        parent::_privileges();
    }

    function manmodul_load()
    {
        $query = "select a.* from master_module a where a.is_deleted = 0";
        $where = ["a.module_url", "a.module_name"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function manmodul_save()
    {
        parent::_insert('master_module', $_POST);
    }

    function manmodul_edit()
    {
        parent::_edit('master_module', $_POST);
    }

    function manmodul_delete()
    {
        parent::_delete('master_module', $_POST);
    }

    function manmenu_load()
    {
        $query = "select a.*, b.module_name, c.menu_name as menu_parent from master_menu a 
        left join master_module b on a.module_id = b.id
        left join master_menu c on a.menu_id = c.id
        where a.is_deleted = 0";
        $where = ["a.menu_url", "a.menu_name", "b.module_name", "c.menu_name"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function manmenu_save()
    {
        $_POST['menu_id'] = $_POST['menu_id'] == "" ? null : $_POST['menu_id'];
        parent::_insert('master_menu', $_POST);
    }

    function manmenu_edit()
    {
        parent::_edit('master_menu', $_POST);
    }

    function manmenu_delete()
    {
        parent::_delete('master_menu', $_POST);
    }

    function manjenisuser_load()
    {
        $query = "select a.* from master_user_type a where a.is_deleted = 0";
        $where = ["a.user_type_code", "a.user_type_name"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function manjenisuser_save()
    {
        parent::_insert('master_user_type', $_POST);
    }

    function manjenisuser_edit()
    {
        parent::_edit('master_user_type', $_POST);
    }

    function manjenisuser_delete()
    {
        parent::_delete('master_user_type', $_POST);
    }

    function manuser_load()
    {
        $query = "select a.*, b.user_type_name from master_user a 
        left join master_user_type b on a.user_type_code = b.user_type_code
        where a.is_deleted = 0";
        $where = ["a.user_name", "a.user_username", "a.user_email", "b.user_type_name"];

        parent::_loadDatatable($query, $where, $_POST);
    }


    function manuser_save()
    {
        $data = $_POST;
        $data["user_password"] = md5($data["user_password"]);

        parent::_insert('master_user', $data);
    }

    function manuser_edit()
    {
        $data = $this->input->post();
        $query = "SELECT a.* , b.user_type_name from master_user a left join master_user_type b on a.user_type_code=b.user_type_code
        where a.id = '".$data['id']."'";
        parent::_edit('master_user', $_POST, $query);
    }

    function manuser_delete()
    {
        parent::_delete('master_user', $_POST);
    }

    function saveHakAkses($previleges, $deleted, $user_type)
    {
        $this->db->trans_start();

        foreach ($deleted as $deleted_id) {
            //delete by module and usertype
            $this->db->query('delete a.* from master_user_privileges a 
    		inner join master_menu b on a.menu_id = b.id
    		inner join master_module c on b.module_id = c.id
    		where a.user_type_code = "' . $user_type . '" and c.id = "' . $deleted_id . '" and a.is_deleted = 1');

            //delete menu by module and user type
            $this->db->query('update master_user_privileges a 
    		inner join master_menu b on a.menu_id = b.id
    		inner join master_module c on b.module_id = c.id
    		set a.is_deleted = 1, a.last_edited_at = "' . date("Y-m-d H:i:s") . '", a.last_edited_by = "' . $this->session->userdata('id') . '"
    		where a.user_type_code = "' . $user_type . '" and c.id = "' . $deleted_id . '"');
        }

        foreach ($previleges as $previlege) {

            $previlagesUpdate = [
                "v = '" . $previlege["v"] . "'",
                "i = '" . $previlege["i"] . "'",
                "d = '" . $previlege["d"] . "'",
                "e = '" . $previlege["e"] . "'",
                "o = '" . $previlege["o"] . "'",
                "last_edited_by = '" . $this->session->userdata('id') . "'",
                "last_edited_at = '" . date("Y-m-d H:i:s") . "'"
            ];

            $query = $this->db->insert_string('master_user_privileges', $previlege) . ' ON DUPLICATE KEY UPDATE ' . implode(", ", $previlagesUpdate);
            $this->db->query($query);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->db->trans_complete();
            return false;
        } else {
            $this->db->trans_commit();
            $this->db->trans_complete();
            return true;
        }
    }

    function manhakakses_save()
    {
        $number_menu = count($this->input->post('idmenu'));
        $deleted = explode(",", $this->input->post('delete'));

        $previlagesData = [];
        for ($i = 0; $i < $number_menu; $i++) {
            $previlagesData[] = [
                "id" => $this->input->post('id')[$i],
                "menu_id" => $this->input->post('idmenu')[$i],
                "v" => $this->input->post('v')[$i] ?? "0",
                "i" => $this->input->post('i')[$i] ?? "0",
                "d" => $this->input->post('d')[$i] ?? "0",
                "e" => $this->input->post('e')[$i] ?? "0",
                "o" => $this->input->post('o')[$i] ?? "0",
                "user_type_code" => $this->input->post('iduser'),
                "created_by" => $this->session->userdata('id'),
                "created_at" => date("Y-m-d H:i:s")
            ];
        }

        if ($this->saveHakAkses($previlagesData, $deleted, $this->input->post('iduser'))) {
            echo json_encode(array('success' => true, 'message' => $previlagesData));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->db->error()));
        }
    }

    function manttd_load()
    {
        $query = "select a.* from master_ttd a 
        where a.is_deleted = 0";
        $where = ["a.nama_ttd", "a.jataban_ttd"];

        parent::_loadDatatable($query, $where, $_POST);
    }

    function manttd_save()
    {
        parent::_insert('master_ttd', $_POST);
    }

    function manttd_edit()
    {
        parent::_edit('master_ttd', $_POST);
    }

    function manttd_delete()
    {
        parent::_delete('master_ttd', $_POST);
    }
}
