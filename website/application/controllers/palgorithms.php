<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Palgorithms extends CI_Controller {

	public function index()
	{
		$data['title'] = 'Polyalphabetic Ciphers';
		$data['content'] = 'palgorithms';
		$data['page_data'] = array();
		$this->load->view('index', $data);
	}
}
