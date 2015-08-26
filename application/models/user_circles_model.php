<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class User_circles_model extends Abstract_model{

	public function __construct(){
		parent::__construct();
		$this->table = 'user_circle';
        $this->ids_name = array('user', 'circle');
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'user') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'circle') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'status') == 0)
				$this->db->set($index,  $value);
			}
		}	

	public function user_in_circle_status($circle,$user){
		$where = array("user"=>$user->id,"circle"=>$circle->id);
		$this->db->select('*')->from($this->table)->where($where);
		$results = $this->db->get()->result();
		if(count($results)>1)
			throw new Exception("Error id of user & circle is duplicated in user_circle", 1);
		elseif(count($results)==0)
			return user_circle_status::not_fount;
		elseif(count($results)==1){
			return enum_user_circle_status($results[0]->status);
			}
		}

    public function notifications($user_id) {
        $sql = "select u.id 'user_id', CONCAT(u.first_name,' ' ,u.last_name) 'user_name', u.avatar, c.name 'circle_name', uc.* from user_circle uc, user u, circle c where uc.user = u.id and uc.circle = c.id and status = ".user_circle_status::request_wait." and u.id <> $user_id and circle in(select id from circle where admin =$user_id)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

	}
