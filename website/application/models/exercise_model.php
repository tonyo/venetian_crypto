<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exercise_Model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get($id)
    {
        $id = intval($id);
        $this->load->database();    
        $query = $this->db->get_where('exercise', array('id' => $id));
        if ($query->num_rows() == 0)
        {
            return null;
        }
        else
        {
            return $query->result()[0];
        }
        $this->db->close();
    }

}
