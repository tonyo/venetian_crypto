<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alberti extends CI_Controller {

    public function genTaskIds()
    {
        $query = $this->db->query("SELECT id FROM exercise WHERE algo='alberti'");
        $res = '';
        foreach ($query->result() as $row)
        {
            $res = $res . ', ' . $row;
        }
        return $res;
    }

    public function index()
    {
        $data['title'] = 'Alberti Cipher Disk';
        $data['content'] = 'alberti';
        $data['page_data'] = '';
        
        $this->load->model('Algo_Model');             
        $res = $this->Algo_Model->getTaskIds('alberti');        
        $data['task_ids'] = $res;
        
        $this->load->view('index', $data);
    }
}
