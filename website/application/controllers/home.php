<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$data['title'] = 'Welcome';
		$data['content'] = 'home';
		$data['page_data'] = array();
		$this->load->view('index', $data);
	}
}
