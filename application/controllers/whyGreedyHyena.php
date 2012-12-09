<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WhyGreedyHyena extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
    $data['mainContent'] = '<img style="position:relative; top:-10px; left:-10px; clear:both;margin:0px auto;" src="'.base_url().'images/greedyHyenaBanner.png" />';
    $data['mainContent'] .= '<h3>Is Every Customer The Same? Of Course Not.</h3> <p>So why would you stop your conversion analysis at a simple "yes" or "no", "true" or "false"?  Stop counting and start quantifying!</p><p>With Greedy Hyena, you can assign specific values to each conversion dynamically -selecting champions based on actual revenue.  Just counting the number of clicks a design iteration drives isn\'t exposing the true ROI - don\'t you really want to know which design elements make you the most money?</p>';
    
    $data['pageTitle'] = "Revenue based Split Testing | Greedy Hyena";
		$this->load->view('basic', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/theory.php */