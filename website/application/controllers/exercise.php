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
            show_error('Failure: incorrect task');
        }
        else
        {
            $data['page_data'] = $res->text;
        }
        
        $this->load->view('exercise', $data);
    }    
    
    function check($id="")
    {
        $this->load->model('Exercise_Model');
        $res = $this->Exercise_Model->get($id);
        if ($res == null)
        {
            // No such task
            $data['page_data'] = 'Failure! Incorrect task.';
            return;
        }
        else
        {
            $correct_answer = $res->answer;
            $given_answer = $this->input->post('answer');
            $data['page_data'] = $correct_answer == $given_answer ? 'Correct!' : 'Incorrect answer!';
        }     
        $this->load->view('exercise', $data);        
    }    
}
