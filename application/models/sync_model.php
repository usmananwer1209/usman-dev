<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Sync_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
        $this->table = 'sync';
        $this->ids_name = array('type');
    }

    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'type') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'last_sync') == 0)
                $this->db->set($index, $value);
        }
    }

}
