<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'abstract_model.php';

class View_storyboards_model extends Abstract_model{

	public function __construct(){
		parent::__construct();
		$this->table = 'view_storyboard';
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'storyboard') == 0)
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
