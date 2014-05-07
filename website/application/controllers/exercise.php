<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exercise extends CI_Controller {

    public function get($id="")
    {
        $data['title'] = 'Ex';
        $data['content'] = 'exercise';
        $this->load->model('Exercise_Model');             
        $res = $this->Exercise_Model->get($id);
        if ($res == null)
        {
            // No such task
            $data['page_data'] = 'Failure!';
        }
        else
        {
            $data['page_data'] = $res->text;
        }
        
        $this->load->view('exercise', $data);
    }    
}
