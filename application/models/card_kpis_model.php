<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Card_kpis_model extends Abstract_model{

	public function __construct(){
		parent::__construct();
		$this->table = 'card_kpi';
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'card') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'kpi') == 0)
				$this->db->set($index,  $value);
            elseif (strcmp($index, 'order') == 0)
                $this->db->set($index, $value);
			}
		}	

	}
