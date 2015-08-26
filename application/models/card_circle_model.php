<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Card_circle_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
        $this->table = 'user_circle';
        $this->ids_name = array('card', 'circle');
    }

    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'card') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'circle') == 0)
                $this->db->set($index, $value);
        }
    }

}
