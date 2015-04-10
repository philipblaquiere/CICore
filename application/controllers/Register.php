<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends MY_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
  {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('system_message_model');
  }
  
  public function index()
  {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_unique_email');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[64]|matches[passconf]');
    $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|min_length[6]|max_length[64]');

    if($this->form_validation->run() == FALSE)
    {
      $data['page'] = "register";
      $this->view_wrapper('register', $data);
    } 
    else
    {
      $ip = $this->input->ip_address();
      $user = $this->input->post();
      $user['password'] = $this->password_hash($user['password']);
      //Save user object and get key to send to user email.
      $code = $this->_generate_random_string(42);
      $user['code'] = $code;
      $this->user_model->create($user);

      //Email verification HERE
      $this->system_message_model->set_message($user['email'] . ', check your inbox, we emailed you a link to validate your account!', MESSAGE_INFO);
      
      $data['page'] = "home";
      redirect('home', $data);
    }  
  }
  
  public function unique_email_ajax($email)
  {
    $email = trim(strtolower($email));
    $user = $this->user_model->get($email);
    if($user)
    {
      //$this->form_validation->set_message('unique_email', 'That email is already registered with our website, choose another one.');
      echo FALSE;
    }
    else
    {
      echo TRUE;
    }
  }
 public function unique_email($email)
  {
    $email = trim(strtolower($email));
    $user = $this->user_model->get($email);
    if($user)
    {
      $this->form_validation->set_message('unique_email', '\'' . $email . '\' is already registered.');
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  private function _generate_random_string($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $randomString;
  }
}