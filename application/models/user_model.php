<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model
{
  protected $table = 'users';
  protected $pkey = 'id';

  public function __construct()
  {
    parent::__construct();
    $this->db1 = $this->load->database('default', TRUE);
  }
  
  public function get($email)
  {
    $sql = "SELECT * FROM {$this->table} WHERE email = '$email' LIMIT 1";
    $result = $this->db1->query($sql);
    return $result->row_array();
  }

  public function get_byid($id)
  {
    $sql = "SELECT * FROM {$this->table} WHERE id = '$id' LIMIT 1";
    $result = $this->db1->query($sql);
    return $result->row_array();
  }

  /*
  * Returns key to be appended to url in email for user account validation
  */
  public function create($user)
  {
    $sql = "INSERT INTO users (email, password)
            VALUES ('" . strtolower($user['email']) . "', '" . $user['password'] . "')";
    
    $this->db1->query($sql);
    $user['id'] = $insert_id;
    $this->pend_user($user);
  }

  public function activate($user)
  {
    $id = $user['id'];
    $code = $user['code'];
    $sql = "SELECT u.* FROM users u, users_pending rp
            WHERE u.id = '$id' AND
               u.id = rp.id AND
               rp.code = '$code'
              LIMIT 1";

    $result = $this->db1->query($sql);
    $user = $result->row_array();
    if(!empty($user))
    {
      $sql = "UPDATE {$this->table} 
            SET validated = 1
            WHERE id = '$id'";
      $this->db1->query($sql);

      $sql = "DELETE FROM users_pending
              WHERE userid = '$id'";
      $this->db1->query($sql);
      return TRUE;
    }
    return FALSE;

    
  }

  public function pend_user($user)
  {
    $code = $user['code'];
    $sql = "INSERT INTO users_pending (id, code)
            VALUES ('" . $user['id'] . "', '" . $code . "')
            ON DUPLICATE KEY UPDATE
            code = '$code', 
            created = current_timestamp";
    
    $this->db1->query($sql);
  }
  
  public function generate_rune_page_key()
  {
    $max_key_length = 8;
    $domain = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz123456789';
    $index_limit = strlen($domain) - 1;

    $key = '';
    for ($i = 0; $i < $max_key_length; $i++) {
      $key .= $domain[rand(0, $index_limit)];
    }
    return $key;
  }

  public function log_login($uid)
  {
    $sql = "UPDATE {$this->table} 
            SET last_login = current_timestamp 
            WHERE id = '$uid'";
    $this->db1->query($sql);
  }

  public function create_password_reset($id, $code)
  {
    $sql = "INSERT INTO reset_password (id, code)
            VALUES ('" . $id . "', '" . $code . "')
            ON DUPLICATE KEY UPDATE
            code = '$code', 
            created = current_timestamp";
    
    $this->db1->query($sql);
  }

  public function reset_password($id, $password)
  {
    $sql = "UPDATE {$this->table} 
            SET password = '$password'
            WHERE id = '$id'";
    $this->db1->query($sql);

    $sql = "DELETE FROM reset_password
            WHERE userid = '$id'";
    $this->db1->query($sql);
  }

  public function is_reset_valid($id, $code)
  {
    $sql = "SELECT u.* FROM users u, reset_password rp
            WHERE u.id = '$id'
              AND u.id = rp.userid
              AND rp.code = '$code'
              LIMIT 1";
    $result = $this->db1->query($sql);
    $result = $result->row_array();
    return $result;
  }
}
