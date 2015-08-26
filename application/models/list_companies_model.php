<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_model.php';

class List_companies_model extends Abstract_model{
	public function __construct(){
		parent::__construct();
		$this->table = 'list_companies';
		}
	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'id') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'name') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'companies') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'user') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'public') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'creation_time') == 0){
				if(strcmp($value, 'now') == 0)
					$this->db->set($index, 'NOW()', false);
				else
					$this->db->set($index, $value);
				}
			elseif(strcmp($index, 'modification_time') == 0){
				if(strcmp($value, 'now') == 0)
					$this->db->set($index, 'NOW()', false);
				else
					$this->db->set($index, $value);
				}
			}
		}	
	}
