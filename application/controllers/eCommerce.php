<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ECommerce extends CI_Controller {

	public function index()
	{
    $data['mainContent'] = '<img style="position:relative; top:-10px; left:-10px; clear:both;margin:0px auto;" src="'.base_url().'images/eCommerceSplitTesting.jpg" />';
    $data['mainContent'] .= '<h3>eCommerce is About Revenue - Not Volume</h3> <p>Most eCommerce sales generate fixed costs of shipping, handling, inventory management, and billing just to name a few.  By conducting split testing based on a basic conversion count (ie, Did a user click or purchase?) you\'re selecting iterations which drive the most transactions or behavior - not the most revenue.</p><p>By tracking users through the shopping experience and capturing the actual revenue generated from each conversion - <b>Greedy Hyena</b> incorporates pricing strategy with split testing to deliver measurably better results.</p>';
    
    $data['pageTitle'] = "eCommerce Split Testing | Epsilong Greedy : Greedy Hyena";
		$this->load->view('basic', $data);
	}
}

/* End of file ecommerce.php */
/* Location: ./application/controllers/eCommerce.php */