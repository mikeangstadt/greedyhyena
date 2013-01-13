<?php
// User will be redirected to this page after logout
define('LOGOUT_URL', 'http://www.epsilongreedy.com/');
define('LOGGED_IN_URL', 'http://www.epsilongreedy.com/dashboard');

// time out after NN minutes of inactivity. Set to 0 to not timeout
define('TIMEOUT_MINUTES', 30);

define('ERROR_MESSAGE', "");

/* 
 * written by: Michael Angstadt, extended from SimpleLogin
 * date: 12/12/11
 * Login is a frontend website controller
 * which supports authorized user login functionality
 */
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
	
//a postback function for signing up a user
//given a login (email), username, and password
//POST: email has been sent as a follow-up to the user
//who signed up
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
  
  //the 'email follow-up callback' for users
  //confirming their email address by clicking on
  //the link embedded in their follow-up email
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
  
  //a postback function to verify the login
  //if session cookie exists - keep it moving
  //otherwise we'll look for a username and password
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

	//helper function for loading login fail
	//screen
	//** NOT CURRENTLY USED **/
	function login_fail()
	{
			$data['page_title'] = $this->lang->line('login_login');
			$this->load->view('login/login_fail',$data);
	}

	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
}
 
?>