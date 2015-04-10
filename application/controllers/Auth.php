<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller
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
    $this->login();
  }

  public function login()
  {
    if ($this->is_logged_in())
    {
      redirect('home', 'location');
    }
    $data['page'] = "login";
    $this->view_wrapper('sign_in', $data);
  }

  public function sign_in()
  {
    $this->require_not_login();

    $this->load->library('form_validation');

    $this->form_validation->set_rules('email', 'Email', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');

    if($this->form_validation->run() == FALSE)
    {
      $this->system_message_model->set_message('First Catch', MESSAGE_INFO);
      $data['page'] = "login";
      $this->view_wrapper('sign_in',$data);
    }
    else
    {
      //get sign in form data
      $email = $this->input->post('email');
      $password = $this->input->post('password');

      $user = $this->user_model->get($email);
      if(!$user)
      {
        //no user found
        $this->system_message_model->set_message('There is an error in your email or password', MESSAGE_INFO);
        redirect('home', 'location');
      }
      else if($user['validated'] == 0)
      {
        //user exists but hasn't validated the account.
        $this->system_message_model->set_message('You haven\'t validated your account yet. Check your emails for an activation link', MESSAGE_INFO);
        redirect('home', 'location');
      }
      else if($this->_validate_password($user,$password)) 
      {
        //login success
        $this->user_model->log_login($user['id']);
        $this->set_user($user);
        //Do something
        redirect('home', 'location');
      }
      else
      {
        //login fail email/pass error
        $this->system_message_model->set_message('There is an error in your email or password', MESSAGE_INFO);
        redirect('home', 'location');
      }
    }
  }
  
  public function sign_out()
  {
    $this->require_login();
    $this->destroy_session();
    redirect('home', 'location');
  }

  public function activate($id, $code)
  {
    $user['id'] = $id;
    $user['code'] = $code;

    if($this->user_model->activate($user))
    {
      $this->system_message_model->set_message('Account has been validated, sign in below.', MESSAGE_INFO);
      redirect('auth/login');
    }
    else
    {
      $this->system_message_model->set_message('Activation code is no longer valid.', MESSAGE_INFO);
      redirect('home');
    }
  }
  public function reset($id, $code)
  {
    $this->require_not_login();

    if($id == NULL || $code == NULL)
    {
      $this->system_message_model->set_message('Error reading the id and code :' . $id. "-" .$code, MESSAGE_INFO);
      redirect('home');
    }

    $this->load->library('form_validation');

    $this->form_validation->set_rules('password2', 'Confirm New Password', 'trim|required|xss_clean|min_length[6]');
    $this->form_validation->set_rules('password', 'New Password', 'trim|required|xss_clean|min_length[6]|matches[password2]');

    $user = $this->user_model->is_reset_valid($id, $code);

    if ($this->form_validation->run() == FALSE)
    {
      if(!empty($user))
      {
        $data['user'] = $user;
        $data['page'] = "reset";
        $this->view_wrapper('reset_password', $data);
        return;
      }

      $this->system_message_model->set_message('Code is no longer valid.', MESSAGE_INFO);
      redirect('home');
    }
    else if(!empty($user) && array_key_exists('id', $user))
    {
      $passwords = $this->input->post();
      $password = $this->password_hash($passwords['password']);
      $this->user_model->reset_password($user['id'], $password);
      $this->system_message_model->set_message('Password has been reset successfully', MESSAGE_INFO);
      redirect('home');
    }
    else
    {
       redirect('home');
    }
  }

  public function send_reset_email()
  {
    $content = $_POST;

    if(!array_key_exists('email', $content))
    { 
      $data['status'] = "success";
      $data['message'] = "An error has occured retrieving your email. Please refresh and try again.";
      echo json_encode($data);
      return;
    }

    $user = $this->user_model->get(strtolower(trim($content['email'])));
    if(empty($user))
    {
      $data['status'] = "success";
      $data['message'] = "An email has been sent to " . $content['email'] . ". Please wait a couple moments before receiving the email.";
      echo json_encode($data);
      return;
    }

    $this->load->library('email');
    $config['mailpath'] = '';
    $config['smtp_user'] = '';
    $config['smtp_pass'] = '';
    $this->email->initialize($config);
    $this->email->from('', '');
    $this->email->to($user['email']);

    $this->email->subject('Reset your password');
    $code = $this->_generate_random_string(42);
    $message = "Click on the link below to reset your password\n\n www..com/auth/reset/".$user['id'] ."/". $code;
    $this->email->message($message);  
    
    if($this->email->send())
    {
      $this->user_model->create_password_reset($user['id'], $code);
      $data['status'] = "success";
      $data['message'] = "An email has been sent to " . $user['email'] . ". Please wait a couple moments before receiving the email. ";
      echo json_encode($data);
      return;
    }
    else
    {
      $data['status'] = "fail";
      $data['message'] = "Error occured while sending an email to " . $user['email'] . ". Please try again";
      echo json_encode($data);
      return;
    }
  }

  public function forgot()
  {
    $data['page'] = "forgot";
    $this->view_wrapper('forgot_password', $data);
  }

  private function _validate_password($user,$password)
  {
    if(!$password || !$user['email'])
      return false;
    return $user['password'] === $this->password_hash($password);
  }

  private function _generate_random_string($length = 10)
  {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = '';
      for ($i = 0; $i < $length; $i++)
      {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $randomString;
  }
}