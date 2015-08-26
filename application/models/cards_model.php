<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Cards_model extends Abstract_model{

	public function __construct(){
		parent::__construct();
		$this->table = 'card';
		}

	public function save($data){

		if(!empty($data)){
			if($this->has_id($data))
				$obj = $this->get_by_object($data);	
			if(!empty($obj))
				$obj = $this->edit($data);
			else
				$obj = $this->add($data);
			}
			$this->update_companies($obj,$data->companies);
			$this->update_kpis($obj,$data->kpis);
                        return $obj->id;
		}

	public function delete($card_id){
		$sql = "delete from view_card where card = $card_id";
		$query = $this->db->query($sql);
		$sql = "delete from card_circle where card = $card_id";
		$query = $this->db->query($sql);
		$sql = "delete from card_company where card = $card_id";
		$query = $this->db->query($sql);
		$sql = "delete from card_kpi where card = $card_id";
		$query = $this->db->query($sql);
		$sql = "delete from card where id = $card_id";
		$query = $this->db->query($sql);
		return true;
	}

	public function simple_save($data){
		if(!empty($data)){
			if($this->has_id($data))
				$obj = $this->get_by_object($data);	
			if(!empty($obj))
				$obj = $this->edit($data);
			else
				$obj = $this->add($data);
		}
	}

	public function card_shared($card_id,$circle_id){
		$sql = "select * from card_circle where card = $card_id and  circle = $circle_id";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		if(!empty($result) && count($result)>0)
			return true;
		else
			return  false;
    }

	public function card_shared_circle_list($card_id){
		$sql = "select circle as c from card_circle where card = $card_id";
		$circles ="";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		if(!empty($result) && count($result) !=0){
			foreach ($result as $r) {
				$circles  .= $r.',';
			}
		}
		return $circles;
	}

	public function share($card,$circles){
		$sql = "delete from card_circle where card = $card->id";
		$query = $this->db->query($sql);
		foreach ($circles as $c) {
			$sql = "INSERT INTO card_circle(card, circle) VALUES ($card->id, $c)";
			$query = $this->db->query($sql);
			}	
		return true;
	}

	public function cards_of_circle($circle_id = ""){
		$sql = " select crd.* from card crd
				inner join card_circle cc on crd.id = cc.card
				where cc.circle = $circle_id  and crd.public = '1' ";

		$query = $this->db->query($sql);
		return  $query->result_array();
		}

    public function most_viewed_cards_of_circle($circle_id = "", $limit = '') {
        $sql = "select 
			      distinct cr.id
			      ,cr.name
			      ,cr.description
			      ,cr.type
			      ,cr.period
			      ,cr.kpi
			      ,cr.public
			      ,cr.order
			      ,cr.user
			      ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
			      ,cr.creation_time
			      ,(SELECT count(*) from view_card vc2 where  vc2.card = cr.id) as viewed
                    from card cr
                    inner join card_circle cc on cr.id = cc.card
										LEFT OUTER join user u on u.id = cr.user
                    where cc.circle = $circle_id ORDER BY viewed DESC";
        if(!empty($limit))
        	$sql .= " LIMIT ".$limit;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function most_viewed_cards_of_user($user_id = "", $limit = '') {
        $sql = "select 
			      distinct cr.id
			      ,cr.name
			      ,cr.description
			      ,cr.type
			      ,cr.period
			      ,cr.kpi
			      ,cr.public
			      ,cr.order
			      ,cr.user
			      ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
			      ,cr.creation_time
			      ,(SELECT count(*) from view_card vc2 where  vc2.card = cr.id) as viewed
                    from card cr
										LEFT OUTER join user u on u.id = cr.user
                    where cr.user = $user_id ORDER BY viewed DESC";
        if(!empty($limit))
        	$sql .= " LIMIT ".$limit;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

	public function cards_of_user($user_id = ""){
		$sql = " select * from card where user = $user_id ";
		$query = $this->db->query($sql);
		return  $query->result_array();
		}

	public function cards_most_viewed($user_id = ""){
		//$sql = " select * from card where user = $user_id ";
		$sql = "select c.* from card c inner join view_card vc on vc.card = c.id where vc.user = $user_id ";
		$query = $this->db->query($sql);
		return  $query->result_array();
		}

	public function cards_shared_with_user($user_id = "" , $circle_id = "",$limit = false){

        //LEFT OUTER join view_card vc   on vc.card = cr.id
		if($this->input->post()){
			$limit = $this->input->post('limit');
			$title = $this->input->post('title');
			$start = $this->input->post('start');
			$order_by = $this->input->post('sort_by');
			$sort_order = $this->input->post('sort_order');
			
		}else{
			$title = '';
			$start = 0;
			$order_by = 'creation_time';
			$sort_order = 'DESC';
		}
		$where_circle = "";
		if(!empty($circle_id))
			$where_circle = " and c.id = $circle_id";
		if(!empty($title))
			$where_title = " and cr.name LIKE '%".$title."%'";
		if(!empty($limit))
			$limit = "LIMIT ".$start.", ".$limit;
		if(!empty($order_by)){
			if($order_by == 'viewed'){
				$order = ' ORDER BY viewed '.$sort_order;
			}elseif($order_by == 'creation_time'){
				$order = ' ORDER BY cr.creation_time '.$sort_order;
			}elseif($order_by == 'autor'){
				$order = ' ORDER BY autor '.$sort_order;
			}elseif($order_by == 'description'){
				$order = ' ORDER BY cr.name '.$sort_order;
			}elseif($order_by == 'name'){
				$order = ' ORDER BY cr.name '.$sort_order;
			}elseif($order_by == 'author'){
				$order = ' ORDER BY autor '.$sort_order;
			}
		}
			
		$sql = "select 
			      distinct cr.id
			      ,cr.name
			      ,cr.description
			      ,cr.type
			      ,cr.period
			      ,cr.kpi
			      ,cr.public
			      ,cr.order
			      ,cr.user
			      ,CONCAT(u.first_name, ' ',u.last_name) as 'autor'
			      ,cr.creation_time
			      ,cr.disqus_post_count
			      ,(SELECT count(*) from view_card vc2 where  vc2.card = cr.id) as 'viewed'
			from card cr
				LEFT OUTER join card_circle cc on cc.card = cr.id
				LEFT OUTER join user_circle uc on cc.circle = uc.circle
				LEFT OUTER join circle c on c.id = uc.circle
				LEFT OUTER join user u on u.id = cr.user
			  where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or cr.public = '1'  or cr.user = $user_id  ) $where_circle $where_title ".$order." ".$limit;
		
		$query = $this->db->query($sql);
		return  $query->result_array();
	}	
	public function cards_shared_with_user_count($user_id = "" , $circle_id = ""){

        //LEFT OUTER join view_card vc   on vc.card = cr.id
		if($this->input->post()){
			$title = $this->input->post('title');
		}else{
			$title = '';
		}
		$where_circle = "";
		if(!empty($circle_id))
			$where_circle = " and c.id = $circle_id";
		if(!empty($title))
			$where_title = " and cr.name LIKE '%".$title."%'";	
			
		$sql = "select 
			      distinct cr.id
			      ,cr.name
			      ,cr.description
			      ,cr.type
			      ,cr.period
			      ,cr.kpi
			      ,cr.public
			      ,cr.order
			      ,cr.user
			      ,CONCAT(u.first_name, ' ',u.last_name) as 'autor'
			      ,cr.creation_time
			      ,cr.disqus_post_count
			      ,(SELECT count(*) from view_card vc2 where  vc2.card = cr.id) as 'viewed'
			from card cr
				LEFT OUTER join card_circle cc on cc.card = cr.id
				LEFT OUTER join user_circle uc on cc.circle = uc.circle
				LEFT OUTER join circle c on c.id = uc.circle
				LEFT OUTER join user u on u.id = cr.user
			  where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or cr.public = '1'  or cr.user = $user_id  ) $where_circle $where_title ";
		
		$query = $this->db->query($sql);
		return  $query->result_array();
	}	
	public function is_card_shared_with_user($user_id = "" , $card_id = ""){
	
		$sql = "select 
			      distinct cr.id
			      ,cr.name
			      ,cr.description
			      ,cr.type
			      ,cr.period
			      ,cr.kpi
			      ,cr.public
			      ,cr.order
			      ,cr.user
			      ,CONCAT(u.first_name, ' ',u.last_name) as 'autor'
			      ,cr.creation_time
			      ,cr.disqus_post_count
			      ,(SELECT count(*) from view_card vc2 where  vc2.card = cr.id) as 'viewed'
			from card cr
				LEFT OUTER join card_circle cc on cc.card = cr.id
				LEFT OUTER join user_circle uc on cc.circle = uc.circle
				LEFT OUTER join circle c on c.id = uc.circle
				LEFT OUTER join user u on u.id = cr.user
			  where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or cr.public = '1'  or cr.user = $user_id  ) AND cr.id = ".$card_id ;
		
		$query = $this->db->query($sql);
		return  $query->result_array();
	}	
	private function update_companies($card, $companies){
		$card = (object)$card;
		$CI = get_instance();
 		$CI->load->model('card_companies_model','card_companies');
		$where = array( "card" => $card->id );
 		$CI->card_companies->delete_list($where);
        $i = 0;
 		foreach ($companies as $company ) {
			$obj = array();
			$obj['card'] = (int)$card->id;
			$obj['company'] = (int)$company;
            $obj['order'] = $i;
            $i++;
			$CI->card_companies->save($obj);
 			}
		}

	private function update_kpis($card, $kpis){
		$card = (object)$card;
		$CI = get_instance();
 		$CI->load->model('card_kpis_model','card_kpis');
		$where = array( "card" => $card->id );
 		$CI->card_kpis->delete_list($where);
        $i = 1;
 		foreach ($kpis as $kpi ) {
			$obj = array();
			$obj['card'] = (int)$card->id;
			$obj['kpi'] = (int)$kpi;
            $obj['order'] = $i;
            $i++;
			$CI->card_kpis->save($obj);
 			}
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'name') == 0)
				$this->db->set($index,  $value);
            elseif (strcmp($index, 'sources') == 0)
                $this->db->set($index, $value);
			elseif(strcmp($index, 'description') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'type') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'user') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'period') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'kpi') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'order') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'public') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'active_company') == 0)

				$this->db->set($index,  $value);
			elseif(strcmp($index, 'column_kpis') == 0)

				$this->db->set($index,  $value);
			elseif(strcmp($index, 'line_kpis') == 0)

				$this->db->set($index,  $value);
			elseif(strcmp($index, 'active_company') == 0)

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
	
	

	public function get_companies($id){
		$CI = get_instance();
 		$CI->load->model('card_companies_model','card_companies');
 		$CI->load->model('companies_model','companies');

 		$companies = array();
 		$where = array( "card" => $id );
    $companies_id = $CI->card_companies->list_records($where, array(), 0, 0, array('order', 1));
 		foreach ($companies_id as $id) {
			$companies[] = $CI->companies->get_by_id($id->company);
	 	}
 		return $companies;
	}

	public function get_carddetails($cid,$chat_type,$sid){

 		

		$query = $this->db->query("SELECT * FROM `storyboard` s,`storyboard_slide` ss WHERE s.id=ss.sb_id AND ss.content = '".$cid."' AND ss.sb_id='".$sid."'");

		

		$result = $query->result();

 		return $result;

	}



	public function get_kpis($id){
		$CI = get_instance();
 		$CI->load->model('card_kpis_model','card_kpis');
 		$CI->load->model('kpis_model','kpis');

 		$kpis = array();
 		$where = array( "card" => $id );
    $kpis_id = $CI->card_kpis->list_records($where, array(), 0, 0, array('order', 1));
 		foreach ($kpis_id as $id) {
 			$kpi = $CI->kpis->get_by_id($id->kpi);
			$kpis[] = $kpi;
	 	}
		return $kpis;
	}

	public function get_sectors($id){
		$CI = get_instance();
 		$CI->load->model('card_companies_model','card_companies');
 		$CI->load->model('companies_model','companies');

 		$sectors = array();
 		$where = array( "card" => $id );
 		$companies_id = $CI->card_companies->list_records($where);
 		foreach ($companies_id as $id) {
 			$sector = $CI->companies->get_by_id($id->company)->sector;
 			if(!in_array($sector, $sectors))
	        	$sectors[] = $sector;
	 		}
 		return $sectors;
		}

	public function cards_shared_with_user_not_his($user_id){
		$sql = "select distinct u.first_name, u.last_name, cr.* from card cr
						inner join card_circle cc on cc.card = cr.id
						inner join user_circle uc on uc.circle = cc.circle
						inner join user u on u.id = cr.user
						where ((uc.user = ".$user_id." and uc.status = 2) or cc.circle in (select id from circle where circle.admin = ".$user_id.") ) and cr.user <> ".$user_id." and cr.public = 0";
		$query = $this->db->query($sql);
		return  $query->result();
	}

	public function public_cards_not_users($user_id){
		$sql = "select u2.first_name, u2.last_name, cr2.* from card cr2
						inner join user u2 on u2.id = cr2.user
						where public = 1 and user <> ".$user_id." 
						and cr2.id not in(
						  select cr.id from card cr
						  inner join card_circle cc on cc.card = cr.id
						  inner join user_circle uc on uc.circle = cc.circle
						  where (uc.user = ".$user_id." and uc.status = 2) )";
		$query = $this->db->query($sql);
		return  $query->result();
	}

	public function get_cards_number_per_10days() {
		$sql = "SELECT days.day, count(card.id) as n FROM
						  (select curdate() as day
						   union select curdate() - interval 1 day
						   union select curdate() - interval 2 day
						   union select curdate() - interval 3 day
						   union select curdate() - interval 4 day
						   union select curdate() - interval 5 day
						   union select curdate() - interval 6 day
						   union select curdate() - interval 7 day
						   union select curdate() - interval 8 day
						   union select curdate() - interval 9 day) days
						  left join card
						   on days.day = date(card.creation_time)
						group by
						  days.day";
		$query = $this->db->query($sql);
		return  $query->result_array();
	}

	public function count_public_shared_cards() {
		$sql = "SELECT 
						  (select count(distinct id)  FROM card c
								inner join card_circle cc on cc.card = c.id
								where c.public = 0)
						  + 
						  (select count(distinct id) from card where card.public = 1)
						AS n";
		$query = $this->db->query($sql);
		$n = $query->result_array();
		return  $n[0]['n'];
	}

	public function recently_published_cards($user_id, $number = 5) {
		$limit = '';
		if(!empty($number))
			$limit = "LIMIT 0, ".$number;
		$sql = "select 
			      distinct cr.id
			      ,cr.name
			      ,cr.description
			      ,cr.type
			      ,cr.period
			      ,cr.kpi
			      ,cr.public
			      ,cr.order
			      ,cr.user
			      ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
			      ,cr.creation_time
			      ,(SELECT count(*) from view_card vc2 where  vc2.card = cr.id) as 'viewed'
			from card cr
				LEFT OUTER join card_circle cc on cc.card = cr.id
				LEFT OUTER join user_circle uc on cc.circle = uc.circle
				LEFT OUTER join circle c on c.id = uc.circle
				LEFT OUTER join user u on u.id = cr.user
			  where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or cr.public = '1'  or cr.user = $user_id  )
			  ORDER BY cr.creation_time DESC ".$limit;
		$query = $this->db->query($sql);
		return  $query->result_array();
	}

}

