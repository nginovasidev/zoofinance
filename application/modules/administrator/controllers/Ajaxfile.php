<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajaxfile extends MY_Controller
{
    var $modul = "ajaxfile";

    function __construct()
    {
        parent::__construct();
    }

    function menu($module_id)
    {
        $menu = $this->db->where(array('module_id' => $module_id, 'menu_id' => NULL))->get('master_menu')->result();

        $option = array_map(function ($data) {
            return "<option value='" . $data->id . "'>" . $data->menu_name . "</option>";
        }, $menu);

        echo "<option value=''>Jadikan Menu Utama</option>" . implode("", $option);
    }

    function cariJenisUser()
    {
        $search = "";
        $cari = $this->input->post('search');
        $page = $this->input->post('per_page');
        if ($cari !== "") $search = " where ( user_type_name LIKE '%$cari%')";
        $query = $this->db->query("SELECT user_type_code as id , user_type_name as 'text' from master_user_type $search limit $page")->result();
        echo json_encode($query);
    }

    function getMenu($module_id)
    {
        $menus = $this->db->query('select * from master_menu where module_id = "' . $module_id . '" and menu_id is null and is_deleted = 0')->result();
        $array = array_map(function ($menu) {
            $x = json_decode(json_encode($menu), true);
            // $x['submenu'] = $this->administratorModel->getSubMenu($x['id']);
            $x['submenu'] = $this->db->query('select * from master_menu where menu_id = "' . $x['id'] . '" and is_deleted = 0')->result();

            return $x;
        }, $menus);

        echo json_encode($array);
    }

    public function getModuleSelect()
    {
        $module = $this->db->query('select a.* from master_module a where a.is_deleted = 0')->result();

        $option = array_map(function ($data) {
            return "<option value='" . $data->id . "'>" . $data->module_name . "</option>";
        }, $module);

        echo "<select class='idmodule' name='idmodule[]' required>
                <option value=''>Pilih Modul</option>" .
            implode("", $option) . "<select>";
    }



    public function getModuleUser()
    {
        function _group_by($array, $key)
        {
            $return = array();
            foreach ($array as $val) {
                $return[$val[$key]][] = $val;
            }
            return $return;
        }

        $id = $this->input->post('id');
        // $module_user = groupby($this->administratorModel->getModuleUser($_POST['id']), 'module_id');
        $module_user = $this->db->query('select a.*, b.menu_name, b.menu_url, c.id as module_id, c.module_name, c.module_name 
        from master_user_privileges a 
        inner join master_menu b on a.menu_id = b.id and b.is_deleted = 0
        inner join master_module c on b.module_id = c.id and c.is_deleted = 0
        where a.user_type_code = "' . $id . '" and a.is_deleted = 0
        ')->result_array();


        $grouped = _group_by($module_user, 'module_id');


        // $this->db->group_by('module_id')->result();

        echo json_encode($grouped);
    }
}
