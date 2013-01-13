<?php
// User will be redirected to this page after logout
define('LOGOUT_URL', 'http://www.epsilongreedy.com/');
define('TIMEOUT_MINUTES', 30);
/* 
 * written by: Michael Angstadt, extended from SimpleLogin
 * date: 12/12/11
 * Logout is a frontend website controller
 * which when redirected to will effective log out a user
 */
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