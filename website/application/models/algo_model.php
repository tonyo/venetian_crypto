<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Algo_Model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }    
    
    function getTaskIds($algo)
    {
        $algo = strval($algo);
        $this->load->database();
        $query = $this->db->get_where('exercise', array('algo' => $algo));
        $this->db->close();
        
        $res = '';
        foreach ($query->result() as $row)
        {
            $res = $res . $row->id . ', ';
        }
        return $res;
    }
}
