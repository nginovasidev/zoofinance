<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
    }

    public function cek_user($data)
    {
      $this->db->select('*');
      $this->db->from('default_mast_user');
      $this->db->where('username', $data['user_username']);
      $this->db->where('userpass', $data['user_password']);
      $query = $this->db->get();
      return $query->result();
    }

    public function get_role($idjenisuser){
        
        $query = $this->db->query("SELECT a.idmodule, a.module,a.urlmodule,b.menu,b.urlmenu,b.idparent,c.idjenuser,c.d,d.jenisuser
          FROM default_mast_module a
          JOIN `default_mast_menu` b ON a.idmodule=b.idmodule
          LEFT JOIN (SELECT * FROM default_mast_module_user WHERE idjenuser='".$idjenisuser."') c ON b.id=c.idmenu
          LEFT JOIN (SELECT * FROM default_mast_jenisuser where id='".$idjenisuser."') d ON c.idjenuser=d.id
          WHERE c.d='1'");

        return $query->result();
    }
 
}
