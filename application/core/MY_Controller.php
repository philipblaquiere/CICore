<?php
/**
 * @file
 * Extends the base controller to load the PHP session upon construction. Also
 * provides a convienient way to render a view and check if the user is logged
 * in.
 */
class MY_Controller extends CI_Controller  {

  const TIMEZONE_DEFAULT = "UM5";
  const WEBSITE_NAME = "WEBSITE NAME";

  public function __construct()
  {
    parent::__construct();
    session_start();
    //$this->load->library('database_layer');
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->helper('date');
    $this->load->model('system_message_model');
  }

  /**
   * Load the specified View, automatically wrapping it between the site's
   * header and footer.
   */
  public function view_wrapper($template, $data = array(), $display_messages = TRUE)
  {
    $data['website_name'] = self::WEBSITE_NAME;
    $data['system_messages'] = array();
    if ($display_messages)
    {
      $data['system_messages'] = $this->system_message_model->get_messages();
    }
    $data['is_logged_in'] = $this->is_logged_in();
    $this->load->view('include/header', $data);

    $this->load->view('include/navigation', $data);
    $this->load->view('include/system_messages', $data);
    $this->load->view($template, $data);
    $this->load->view('include/footer');
  }
  public function view($template, $data)
  {
    $this->load->view($template, $data);
  }

  /**
   * Convinience function to determine if the user is logged in.
   * @returns
   *   TRUE if the user is currently authenticated, FALSE otherwise.
   */
  protected function is_logged_in()
  {
    return isset($_SESSION['user']);
  }

  /**
   * Convinience function to get the ID of the currently logged in user.
   */
  protected function get_userid() 
  {
    return isset($_SESSION['user']) ? $_SESSION['user']['id'] : 0;
  }

  protected function set_user($user)
  {
    $_SESSION['user'] = $user;
  }

  protected function destroy_session() 
  {
    unset($_SESSION['user']);
  }

  /**
   * Verifies the current user's session and redirects to the login form if the
   * user has not authenticated.
   */
  protected function require_login()
  {
    if(!$this->is_logged_in())
    {
      $this->system_message_model->set_message('You need to be logged in to do that', MESSAGE_WARNING);
      redirect('home','location');
    }
  }

  /**
  * Verifies that client is not logged in, and if so, it logs them out but allows them to view
  * the originally requested page (ie Registration)
  */
  protected function require_not_login()
  {
    if ($this->is_logged_in())
    {
      $this->system_message_model->set_message('You have been automatically logged out in order to view this page.', MESSAGE_WARNING);
      $this->destroy_session();
    }
  }
  /*
  *Converts the UTC/GMT UNIX standard epoch time to the user specific time zone formatted date. 
  */
  protected function get_local_date($epoch, $format = 'F j, Y')
  {
    $date = new DateTime("@$epoch", new DateTimeZone(self::TIMEZONE_DEFAULT));
    if($_SESSION['user']) {
      $date->setTimezone(new DateTimeZone($_SESSION['user']['time_zone']));
    }
    return $date->format($format);
  }

  /*
  *Converts the UTC/GMT UNIX standard epoch time to the user specific time zone formatted date and time.
  */
  protected function get_local_datetime($epoch, $format='F j, Y H:i:s')
  {
    $date = new DateTime("@$epoch", new DateTimeZone(self::TIMEZONE_DEFAULT));
    if($_SESSION['user']) {
      $date->setTimezone(new DateTimeZone($_SESSION['user']['time_zone']));
    }
    return $date->format($format);
  }

  protected function get_default_epoch($date)
  {
    date_default_timezone_set($_SESSION['user']['time_zone']);
    $epoch = strtotime($date);
    $defdate = new DateTime("@$epoch",new DateTimeZone($_SESSION['user']['time_zone']));
    $defdate->setTimezone(new DateTimeZone(self::TIMEZONE_DEFAULT));
    date_default_timezone_set(self::TIMEZONE_DEFAULT);
    return $defdate->getTimestamp();
  }

  /**
   * Hash a password using the user's unique salt.
   */
  public function password_hash($password)
  {
    // append the salt to thwart rainbow tables
    return sha1($password . $this->get_salt());
  }

  protected function get_salt()
  {
    return "LF98af2kF4K2kjL!dB";
  }

  /*
  *Converts the UTC/GMT UNIX standard epoch time to the user specific time zone formatted date and time.
  */
  protected function local_to_gmt($local_time, $to_human = TRUE)
  {
    $time = local_to_gmt($local_time);
    return $to_human ? unix_to_human($time) : $time;
  }

  protected function gmt_to_local($gmt_time, $to_human = TRUE)
  {
    $time_zone = isset($_SESSION['user']) ? $_SESSION['user']['time_zone'] : self::TIMEZONE_DEFAULT;
    $time = gmt_to_local($gmt_time, $time_zone, date("I",$gmt_time));
    return $to_human ? unix_to_human($time) : $time;
  }

}
