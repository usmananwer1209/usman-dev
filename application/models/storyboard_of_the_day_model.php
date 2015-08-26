<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Storyboard_of_the_day_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
        $this->table = 'storyboard_of_the_day';
    }

    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'storyboard') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'time') == 0)
                if(strcmp($value, 'now') == 0)
                    $this->db->set($index, 'NOW()', false);
                else
                    $this->db->set($index, $value);
        }
    }

     public function get_last_record() {
        $sql = 'select * from storyboard_of_the_day order by time desc limit 0,1';
        $query = $this->db->query($sql);
        return  $query->result();
    }

}
