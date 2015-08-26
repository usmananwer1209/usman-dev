<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_model.php';

class Circles_model extends Abstract_model{
	public function __construct(){
		parent::__construct();
		$this->table = 'circle';
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'name') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'description') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'admin') == 0)
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

	public function list_my_cirles($user_id = "", $stauts = ""){
	 	if($stauts == "")
			$sql = "select DISTINCT c.*,  ( CASE  WHEN c.admin = $user_id THEN 'true' ELSE 'false' END)  AS is_admin 
						from circle c 
						inner join user_circle uc on uc.circle = c.id 
						where ( uc.user = $user_id ) or  c.admin = $user_id ORDER BY c.name";
		else
			$sql = "select DISTINCT c.*,  ( CASE  WHEN c.admin = 2 THEN 'true' ELSE 'false' END)  AS is_admin 
 ,(SELECT count(*) from card_circle cc2 where  cc2.circle = c.id) as cards
 ,(SELECT count(*) from storyboard_circle cs2 where  cs2.circle = c.id) as storyboards
from circle c 
inner join user_circle uc on uc.circle = c.id 

where ( uc.user = $user_id  and uc.status = 2 ) or  c.admin = $user_id  ORDER BY c.name";
		$query = $this->db->query($sql);
		return  $query->result_array();
		}

	public function circles_of_user($user_id = ""){
		$sql = "  select * from circle where admin = $user_id ";
		$query = $this->db->query($sql);
		return  $query->result_array();
		}

	public function circles_of_cards($card_id = ""){
		$sql = "  select distinct ci.* from circle ci
					inner join card_circle cc on ci.id = cc.circle
					where cc.card = $card_id";
		$query = $this->db->query($sql);
		return  $query->result_array();
		}		

    public function circles_of_storyboards($storyboard_id = "") {
        $sql = "  select distinct ci.* from circle ci
					inner join storyboard_circle sc on ci.id = sc.circle
					where sc.storyboard = $storyboard_id";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
	public function addmemberuser($obj){
		$this->db->set('user', $obj->admin);
		$this->db->set('circle', $obj->id);
		$this->db->set('status', 1);
		$this->db->insert('user_circle');
	}
	public function list_all_cirles($user_id = ""){
		if($user_id == "")
			$sql = "select  DISTINCT  c.* from circle c ORDER BY c.name";

		$query = $this->db->query($sql);
		return  $query->result_array();
		}
	
	}

