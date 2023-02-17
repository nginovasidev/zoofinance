<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menu_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
    }

    public function get_menu($idjenisuser){
        
        $query = $this->db->query("SELECT a.idmodule, a.module,a.urlmodule
          FROM default_mast_module a
          JOIN `default_mast_menu` b ON a.idmodule=b.idmodule
          LEFT JOIN (SELECT * FROM default_mast_module_user WHERE idjenuser='".$idjenisuser."') c ON b.id=c.idmenu
          LEFT JOIN (SELECT * FROM default_mast_jenisuser where id='".$idjenisuser."') d ON c.idjenuser=d.id
          WHERE c.d='1' group by a.module");

        return $query->result();
    }

    public function get_submenu($idjenisuser,$idmodule){
        
        $query = $this->db->query("SELECT a.urlmodule,b.menu,b.urlmenu
          FROM default_mast_module a
          JOIN `default_mast_menu` b ON a.idmodule=b.idmodule
          LEFT JOIN (SELECT * FROM default_mast_module_user WHERE idjenuser='".$idjenisuser."') c ON b.id=c.idmenu
          LEFT JOIN (SELECT * FROM default_mast_jenisuser where id='".$idjenisuser."') d ON c.idjenuser=d.id
          WHERE c.d='1' and a.idmodule='".$idmodule."'");

        return $query->result();
    }
 
}
