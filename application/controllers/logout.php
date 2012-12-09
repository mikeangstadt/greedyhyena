<?php
// User will be redirected to this page after logout
define('LOGOUT_URL', 'http://www.epsilongreedy.com/');
define('TIMEOUT_MINUTES', 0);

class Logout extends Controller {

	function Logout()
	{
		parent::Controller();
	}

	// --------------------------------------------------------------------

	function index()
	{


// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

      setcookie("verify", '', $timeout, '/'); // clear password;

      header( 'Location: '.LOGOUT_URL ) ;
	}

}
 
?>