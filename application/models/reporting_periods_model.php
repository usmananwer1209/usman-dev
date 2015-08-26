<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Reporting_periods_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
        $this->table = 'reporting_periods';
    }

    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'reporting_period') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'time') == 0)
                if(strcmp($value, 'now') == 0)
                    $this->db->set($index, 'NOW()', false);
                else
                    $this->db->set($index, $value);
        }
    }

}
