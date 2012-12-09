<?php
// User will be redirected to this page after logout
define('LOGOUT_URL', 'http://www.epsilongreedy.com/');
define('LOGGED_IN_URL', 'http://www.epsilongreedy.com/dashboard');
// time out after NN minutes of inactivity. Set to 0 to not timeout
define('TIMEOUT_MINUTES', 10);

define('ERROR_MESSAGE', "");

class Login extends CI_Controller {
	// --------------------------------------------------------------------

	public function index()
	{
	  $data['error_msg'] = ERROR_MESSAGE;
	  
		if(!$this->verifyLogin())
		{
		  $data['error_msg'] = ERROR_MESSAGE;
		  $this->load->view('login', $data);
		}
		else
		   {
          header('Location: ' . LOGGED_IN_URL);
		   }
	}
  function signUpUser()
  {
    if($_POST['access_password'] != $_POST['access_password_verification'])
	{
        echo "Passwords do not match, please try again.";
		return;
	} 
    $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
      $pass = md5(trim($_POST['access_password']));
      
      $loginResults = $this->user_model->GetAllBy(array("user_id"=>$login, "password"=>$pass));
                  
      if(sizeof($loginResults) <= 0)
      {
          $thisUser = new User();
          $thisUser->user_id = $login;
          $thisUser->password = $pass;
          $thisUser->id = $this->user_model->GetNextId();
          $thisUser->api_key = uniqid();
		  $thisUser->confirm_key = uniqid();
		  
          //a regular user
          $thisUser->user_role = 2;
          
          if(isset($_POST["username"]))
            $thisUser->display_name = trim($_POST["username"]);
          else
            $thisUser->display_name = $login;

          $this->user_model->Insert($thisUser);
          
          $email_body = "Welcome to Greedy Hyena ".$thisUser->display_name.",<br/>
          <p>We're super excited to get started testing - click <a href='http://www.epsilongreedy.com/login/confirmUserSignUp?ckey=".$thisUser->confirm_key."'>here</a> if you're ready to go!</p>
          <p>If not, save this email and come back anytime you're ready to get started.</p>
          <p>Thanks,</p>
          <p>Greedy Hyena</p>
          <p>info@epsilongreedy.com</p>";
          
          $this->load->library('email');
          
		  $emailConfig["mailtype"] = "HTML";
		  $config['charset'] = 'iso-8859-1';
		  
		  $this->email->initialize($config);
		  
          $this->email->to($login);
          $this->email->from("newuser@epsilongreedy.com", "Greedy Hyena");
          $this->email->subject("Welcome to Greedy Hyena ".$thisUser->display_name);
          $this->email->message($email_body);
          $this->email->send();
      }
      
      echo "Thanks for signing up, we'll be following up with an email shortly.";
  }
  function confirmUserSignUp()
  {
	$data;
	$confirmed = false;
	$thisUser = new User();

	if(isset($_GET["ckey"]))
		{
			$confirmKey = $_GET["ckey"];
			
			$results = $this->user_model->GetAllBy(array("confirm_key"=>$confirmKey));
	
			if(sizeof($results) > 0)
			{
				$thisUser = $results[0];
				$confirmed = true;
			}
		}
	if(!$confirmed)
	{
		$data["mainContent"] = "<h3>Invalid Confirmation Key</h3>
		<p>Please click <a href='http://www.epsilongreedy.com/'>here</a> to sign-up!</p>";
		$data["pageTitle"] = "Oops!, Sorry - Please Sign Up At EpsilonGreedy.com";
	}
	else
		{
			$data["mainContent"] = "<h3>Thanks for Signing Up ".$thisUser->display_name."</h3>
			<h4>Your unique api key is<br/>
			".$thisUser->api_key."</h4>
			<p>Please copy this down for your own records - you'll need it later but you can always retrieve it from your account settings menus.</p><p>Click <a href='http://www.epsilongreedy.com/login'>here</a> to get started!</p>";
			$data["pageTitle"] = "Oops!, Sorry - Please Sign Up At EpsilonGreedy.com";
		}
		
	$this->load->view("basic", $data);
  }
  function verifyLogin()
  {
   // timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

    if(!isset($_COOKIE['verify']) || $_COOKIE['verify'] == '')
    {
      
        if (isset($_POST['access_password'])) {

      $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
      $pass = md5(trim($_POST['access_password']));

      $loginResults = $this->user_model->GetAllBy(array("display_name"=>$login, "password"=>$pass));
                  
      if(sizeof($loginResults) <= 0)
      {
          setcookie("verify", '', $timeout, '/'); // clear password;
          $this->load->view("login", array("formMessage"=>"Invalid Password or Username, Please Try Again."));
      }
      else
      {
          $thisUser = new User();
          $thisUser = $loginResults[0];
  
          // set cookie if password was validated
          setcookie("verify", md5($login.'%'.$pass), $timeout, '/');
          setcookie("user", $thisUser->id, $timeout, '/');

          // Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
          // So need to clear password protector variables
          unset($_POST['access_login']);
          unset($_POST['access_password']);
          unset($_POST['Submit']);
          
		  $currentTests = $this->test_model->GetAllBy(array("user_id"=>$thisUser->id));
		  
		  if(sizeof($currentTests) > 0)
			$data["currentTests"] = $currentTests;
		  
		  $data['currentUser'] =$thisUser;
		  
		  $this->load->view("dashboard", $data);
      }
    }

    else {

      // check if password cookie is set
      if (!isset($_COOKIE['verify'])) {
        return false;
      }

      // check if cookie is good
      $found = false;
      foreach($LOGIN_INFORMATION as $key=>$val) {
        $lp = (USE_USERNAME ? $key : '') .'%'.$val;
        if ($_COOKIE['verify'] == md5($lp)) {
          $found = true;
          // prolong timeout
          if (TIMEOUT_CHECK_ACTIVITY) {
            setcookie("verify", md5($lp), $timeout, '/');
          }
          break;
        }
      }
      if (!$found) {
         return false;
      }

     }
     }
     
     return true;
  }

	// --------------------------------------------------------------------

	function login_fail()
	{
			$data['page_title'] = $this->lang->line('login_login');
			$this->load->view('login/login_fail',$data);
	}

	// --------------------------------------------------------------------

	function forgot_password()
	{
		$this->load->model('clientcontacts_model');
		$this->load->library('validation');

		if ($this->site_sentry->is_logged_in())
		{
			redirect ('logout');
		}

		$data['page_title'] = $this->lang->line('login_forgot_password');

		$rules['email'] = "required|valid_email";

		$this->validation->set_rules($rules);

		$this->validation->set_error_delimiters('<p class="error">', '</p>');

		$fields['email'] = $this->lang->line('clients_email');

		$this->validation->set_fields($fields);

		if ($this->validation->run() == FALSE)
		{
			$this->load->view('login/login_forgotpassword', $data);
		}
		else
		{
			$email = $this->input->post('email');
			$random_passkey = random_string('alnum', 12);

			$customer_id = $this->clientcontacts_model->password_reset($email, $random_passkey);

			// we won't actually send this if its just the online demo, or there is no customer id returned
			if ($customer_id AND $this->settings_model->get_setting('demo_flag') != 'y')
			{
				$email_body = '<p>' . $this->lang->line('login_password_reset_email1') . '.</p>';
				$email_body .= '<p>' . $this->lang->line('login_password_reset_email2') . ' ' . anchor("login/confirm_password/{$customer_id}/{$random_passkey}", site_url("login/confirm_password/{$customer_id}/{$random_passkey}")) . ".</p>";
				$email_body .= '<p>' . $this->lang->line('login_password_reset_email3') . '</p>';
				$email_body .= '<p>-----------------------<br />' . $this->input->ip_address() . '</p>';

				$config['mailtype'] = 'html';
				$this->email->initialize($config);

				$senderInfo = $this->settings_model->getCompanyInfo()->row();
				$this->email->to($email);
				$this->email->from($this->settings_model->get_setting('primary_contact_email'), $this->settings_model->get_setting('primary_contact'));
				$this->email->subject($this->lang->line('login_password_reset_title'));
				$this->email->message($email_body);
				$this->email->send();
			}

			$data['msg'] = $this->lang->line('login_password_sent') . ' ' . $email;

			$this->load->view('login/login_password_message', $data);
		}
	}

	// --------------------------------------------------------------------

	function confirm_password()
	{
		$this->load->model('clientcontacts_model', '', TRUE);
		$customer_id = (int) $this->uri->segment(3);
		$passkey = $this->uri->segment(4);

		$email = $this->clientcontacts_model->password_confirm($customer_id, $passkey)->row()->email;

		$data['page_title'] = $this->lang->line('login_forgot_password');

		if ($email != FALSE)
		{
			$new_password = random_string('alnum', 12);
			$password_crypted = $this->encrypt->encode($new_password);

			// if this is the demo, disable password resetting
			if ($this->settings_model->get_setting('demo_flag') == 'y')
			{
				$data['msg'] = $this->lang->line('login_password_reset_disabled');
			}
			else
			{
				if ($this->clientcontacts_model->password_change($customer_id, $password_crypted))
				{
					$email_body = '<p>' . $this->lang->line('login_password_email1') . " <em>$new_password</em> " . $this->lang->line('login_password_email2') . ' ' . anchor('login', $this->lang->line('login_login')) . '.</p>';

					$config['mailtype'] = 'html';
					$this->email->initialize($config);
					$this->email->to($email);
					$this->email->from($this->settings_model->get_setting('primary_contact_email'), $this->settings_model->get_setting('primary_contact'));
					$this->email->subject($this->lang->line('login_password_reset_title'));
					$this->email->message($email_body);
					$this->email->send();

					$data['msg'] = $this->lang->line('login_password_success');
				}
				else
				{
					$data['msg'] = $this->lang->line('login_password_fail');
				}
			}
		}
		else
		{
			$data['msg'] = $this->lang->line('login_password_reset_unable');
		}

		$this->load->view('login/login_password_message', $data);
	}

}
 
?>