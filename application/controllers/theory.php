<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * written by: Michael Angstadt
 * date: 12/12/11
 * Theory is a frontend website page controller
 */
class Theory extends CI_Controller {
	public function index()
	{
		$this->load->view('theory');
	}
}