<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * written by: Michael Angstadt
 * date: 12/12/11
 * Home is a frontend website controller
 * this is the default controller/page for the front-end website
 */
class Home extends CI_Controller {

	public function index()
	{
		$this->load->view('home');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */