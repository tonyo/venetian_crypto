<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Palgorithms extends CI_Controller {

	/*
		@param-pam $which: alberti/trithemius/belaso/vigenere
	*/
	public function index($which='alberti')
	{
		$data['title'] = 'Polyalphabetic Ciphers';
		$data['content'] = 'palgorithms';
		$data['page_data'] = array();
		$data['which'] = $which;

        $this->load->model('Algo_Model');
        $res = $this->Algo_Model->getTaskIds('palgo');
        $data['task_ids'] = $res;
		
		$this->load->view('index', $data);
	}
}
