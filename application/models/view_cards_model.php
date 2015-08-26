<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'abstract_model.php';

class View_cards_model extends Abstract_model{

	public function __construct(){
		parent::__construct();
		$this->table = 'view_card';
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'card') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'user') == 0)
				$this->db->set($index,  $value);
		  	elseif (strcmp($index, 'creation_time') == 0) {
          	if (strcmp($value, 'now') == 0)
              	$this->db->set($index, 'NOW()', false);
          	else
              	$this->db->set($index, $value);
	      }
		}
	}	
}
