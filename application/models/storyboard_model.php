<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Storyboard_model extends Abstract_model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'storyboard';
        $this->link_table = 'storyboard_slide';
        $this->link_table2 = 'wordcloud_content';
    }

//////////////////////////////// Get Tags for Story Bord ///////////////////////////////////
    function getcardtype($id)
    {
        $post_data = $this->input->post();
        $cid = '';
        if (!empty($post_data['data'])) {
            $cid = $post_data['data'];
        } else {
            $cid = $id;
        }
        $query = $this->db->query("SELECT type FROM card WHERE id = '" . $cid . "'");


        $result = $query->result();
        return $result;
    }


    function gettags($cid, $type)
    {
        if ($type == 'combo_new') 
		{
            $query = $this->db->query("SELECT * FROM `card_company` cc, `company` c WHERE c.entity_id=cc.`company` AND cc.`card` = '" . $cid . "'");
        } 
		else if ($type == 'column' || $type == 'area' || $type == 'line' || $type == 'tree' || $type == 'explore' || $type == 'map') 
		{

            $query = $this->db->query("SELECT * FROM `card_kpi` ckpi, `kpi` c WHERE ckpi.kpi=c.`term_id` AND ckpi.`card` = '" . $cid . "' ORDER BY ckpi.order ASC");


        }
        $result = $query->result();
        return $result;
    }

    function getclass($sid)
    {


        $query = $this->db->query("SELECT style FROM storyboard WHERE id = '" . $sid . "'");
        $result = $query->result();
        return $result;
    }

////////////////////////////////////////////////////////////////////////////////////////////
    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'title') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'description') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'user') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'start_image') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'start_end_template') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'public') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'end_avatar') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'end_text') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'end_link') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'end_link_name') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'creation_time') == 0) {
                if (strcmp($value, 'now') == 0)
                    $this->db->set($index, 'NOW()', false);
                else
                    $this->db->set($index, $value);
            } 
			elseif (strcmp($index, 'modification_time') == 0) {
                if (strcmp($value, 'now') == 0)
                    $this->db->set($index, 'NOW()', false);
                else
                    $this->db->set($index, $value);
            } 
			elseif (strcmp($index, 'style') == 0) {
                $this->db->set($index, $value);
            }
            /*
            elseif (strcmp($index, 'parent_id') == 0)
                $this->db->set($index, $value);
             */
        }
    }

    protected function set_slide_object($data)
    {
        $this->db->set('sb_id', $data['sb_id']);

        $this->db->set('type', $data['type']);
        $this->db->set('content', $data['content']);
        $this->db->set('title', $data['title']);
        $this->db->set('description', $data['description']);
        $this->db->set('template', $data['template']);
        $this->db->set('order', $data['order']);
        $this->db->set('wc_words', $data['wc_words']);
        $this->db->set('wc_type', $data['wc_type']);
    }

    public function update_wc_content_for_slide($slide_id, $json_data)
    {
        $this->db->set('json_data', $json_data);
        $this->db->where('slide_id', (int)$slide_id);
        $this->db->update($this->link_table2);
    }

    public function insert_wc_content_for_slide($wc_content) {
        $this->db->set('slide_id', $wc_content['sb_slide_id']);
        $this->db->set('json_data', $wc_content['json_data']);

        $this->db->insert($this->link_table2);
        return $this->db->insert_id();
    }

    public function save_slide_to_sb($slide) {

        $this->set_slide_object($slide);

        $this->db->insert($this->link_table);

        return $this->db->insert_id();
    }

    public function remove_sb_slides($sb_id) {
        // first delete the wordcloud records, if any
        $this->delete_wordcloud_content_from_storyboard($sb_id);

        return $this->db->where(array('sb_id' => (int)$sb_id))
            ->delete($this->link_table);
    }

    public function get_sb_slides($sb_id) {
        $slide_data = $this->db->select('ss.*, wc.json_data as wc_content')
            ->from($this->link_table . ' as ss')
            ->join($this->link_table2 . ' as wc', 'wc.slide_id = ss.id', 'left')
            ->where(array('sb_id' => $sb_id))
            ->order_by('order', 'asc')
            ->get()
            ->result();

        foreach ($slide_data as $sd) {
            if ($sd->wc_content) {
                $sd->wc_content = base64_encode($sd->wc_content);
            }
        }
        return $slide_data;
    }


  public function get_wc_commands_for_slide($slide_id) {
      if (is_array($slide_id)) {
          $slide_data = $this->db->select('*')
              ->from($this->link_table2)
              ->where_in('slide_id',$slide_id)
              ->get()
              ->result();
      }
      else {
          $slide_data = $this->db->select('json_data')
              ->from($this->link_table2)
              ->where(array('slide_id' => $slide_id))
              ->get()
              ->result();
      }
      return $slide_data;
  }

    public function delete_wordcloud_content_from_slide($slide_id) {
        $sql =  "delete wordcloud_content
					from wordcloud_content
					where slide_id = $slide_id";

        $query = $this->db->query($sql);
    }

    public function delete_wordcloud_content_from_storyboard($storyboard_id) {
      $sql =  "delete wordcloud_content
				from wordcloud_content
				inner join storyboard_slide on storyboard_slide.id = wordcloud_content.slide_id
				where storyboard_slide.sb_id = $storyboard_id";

      $query = $this->db->query($sql);
  }

  public function delete($sb_id){
    $sql = "delete from view_storyboard where storyboard = $sb_id";
    $query = $this->db->query($sql);
    $sql = "delete from storyboard_circle where storyboard = $sb_id";
    $query = $this->db->query($sql);

      $this->delete_wordcloud_content_from_storyboard($sb_id);

      $sql = "delete from storyboard_slide where sb_id = $sb_id";
    $query = $this->db->query($sql);
    $sql = "delete from storyboard where id = $sb_id";
    $query = $this->db->query($sql);
    return true;
  }

  public function add_sb($data) {
    $this->set_object($data);
    $this->db->insert($this->table);
    return $this->db->insert_id();
  }

  public function edit_sb($sb_id, $data) {
    $this->set_object($data);
    $this->db->where('id', (int)$sb_id);
    return $this->db->update($this->table);
  }

    public function update_slide($slide_id, $data) {
        $this->set_slide_object($data);
        $this->db->where('id', (int)$slide_id);

        return $this->db->update($this->link_table);
    }

    public function share($sb,$circles){
    $sql = "delete from storyboard_circle where storyboard = $sb->id";
    $query = $this->db->query($sql);
    foreach ($circles as $c) {
      $sql = "INSERT INTO storyboard_circle(storyboard, circle) VALUES ($sb->id, $c)";
      $query = $this->db->query($sql);
      } 
    return true;
  }

  public function sb_shared($sb_id,$circle_id){
    $sql = "select * from storyboard_circle where storyboard = $sb_id and  circle = $circle_id";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    if(!empty($result) && count($result)>0)
      return true;
    else
      return  false;
    }

    public function storyboards_shared_with_user($user_id = "", $circle_id = "", $limit = false) {
		if($this->input->post()){
			
			$limit = $this->input->post('limit');
			$start = $this->input->post('start');
			$title = $this->input->post('title');
			$order_by = $this->input->post('sort_by');
			$sort_order = $this->input->post('sort_order');
			
		}else{
			$title = '';
			$start = 0;
			$order_by = 'creation_time';
			$sort_order = 'DESC';
		}
		
        $where_circle = "";
		$where_title = "";
        if (!empty($circle_id))
            $where_circle = " and c.id = $circle_id";
		if(!empty($title))
			$where_title = " and sb.title LIKE '%".$title."%'";
        if(!empty($limit))
          $limit = "LIMIT ".$start.", ".$limit;
		  
		if(!empty($order_by)){
			if($order_by == 'viewed'){
				$order = ' ORDER BY viewed '.$sort_order;
			}elseif($order_by == 'creation_time'){
				$order = ' ORDER BY sb.creation_time '.$sort_order;
			}elseif($order_by == 'author'){
				$order = ' ORDER BY author '.$sort_order;
			}elseif($order_by == 'name'){
				$order = ' ORDER BY sb.title '.$sort_order;
			}elseif($order_by == 'autor'){
				$order = ' ORDER BY author '.$sort_order;
			}elseif($order_by == 'description'){
				$order = ' ORDER BY sb.title '.$sort_order;
			}
		}
		  
        $sql = "select 
                    distinct sb.id
                    ,sb.title
                    ,sb.description
                    ,sb.creation_time
                    ,sb.modification_time
                    ,sb.public
                    ,sb.start_end_template
                    ,sb.start_image
                    ,sb.end_text
                    ,sb.end_avatar
                    ,sb.user
                    ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
                    ,(SELECT count(*) from view_storyboard vs2 where  vs2.storyboard = sb.id) as viewed
                  from storyboard sb
                    LEFT OUTER join storyboard_circle sc on sc.storyboard = sb.id
                        LEFT OUTER join user_circle uc on sc.circle = uc.circle
                        LEFT OUTER join circle c on c.id = uc.circle
                        LEFT OUTER join user u on u.id = sb.user
                    where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or sb.public = '1'  or sb.user = $user_id  ) $where_circle $where_title ".$order." ".$limit;
		
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function all_accessible_storyboards($user_id = "",$limit= false) {
		if($this->input->post()){
			$limit = $this->input->post('limit');
			$start = $this->input->post('start');
			$order_by = $this->input->post('sort_by');
			$sort_order = $this->input->post('sort_order');
			$title = $this->input->post('title');
		}else{
			$title = '';
			$start = 0;
			$order_by = 'creation_time';
			$sort_order = 'DESC';
		}
		
		if(!empty($limit))
          $limit = "LIMIT ".$start.", ".$limit;
		if(!empty($title))
			$where_title = " and sb.title LIKE '%".$title."%'"; 
		if(!empty($order_by)){
			if($order_by == 'viewed'){
				$order = ' ORDER BY viewed '.$sort_order;
			}elseif($order_by == 'creation_time'){
				$order = ' ORDER BY sb.creation_time '.$sort_order;
			}elseif($order_by == 'author'){
				$order = ' ORDER BY author '.$sort_order;
			}elseif($order_by == 'name'){
				$order = ' ORDER BY sb.title '.$sort_order;
			}elseif($order_by == 'autor'){
				$order = ' ORDER BY author '.$sort_order;
			}elseif($order_by == 'description'){
				$order = ' ORDER BY sb.title '.$sort_order;
			}
		} 
        $sql = "select 
                    distinct sb.id
                    ,sb.title
                    ,sb.description
                    ,sb.creation_time
                    ,sb.modification_time
                    ,sb.public
                    ,sb.start_end_template
                    ,sb.start_image
                    ,sb.end_text
                    ,sb.end_avatar
                    ,sb.user
                    ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
                    ,(SELECT count(*) from view_storyboard vs2 where  vs2.storyboard = sb.id) as viewed
                  from storyboard sb
                    LEFT OUTER join storyboard_circle sc on sc.storyboard = sb.id
                        LEFT OUTER join user_circle uc on sc.circle = uc.circle
                        LEFT OUTER join circle c on c.id = uc.circle
                        LEFT OUTER join user u on u.id = sb.user
                    where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or sb.public = '1'  or sb.user = $user_id  )  $where_title ".$order." ".$limit;
	    
        $query = $this->db->query($sql);
        return $query->result_array();
    }
	public function is_accessible_storyboards($user_id = "", $storyboard_id = "") {
        $sql = "select 
                    distinct sb.id
                    ,sb.title
                    ,sb.description
                    ,sb.creation_time
                    ,sb.modification_time
                    ,sb.public
                    ,sb.start_end_template
                    ,sb.start_image
                    ,sb.end_text
                    ,sb.end_avatar
                    ,sb.user
                    ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
                    ,(SELECT count(*) from view_storyboard vs2 where  vs2.storyboard = sb.id) as viewed
                  from storyboard sb
                    LEFT OUTER join storyboard_circle sc on sc.storyboard = sb.id
                        LEFT OUTER join user_circle uc on sc.circle = uc.circle
                        LEFT OUTER join circle c on c.id = uc.circle
                        LEFT OUTER join user u on u.id = sb.user
                    where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or sb.public = '1'  or sb.user = $user_id  )  AND sb.id = ".$storyboard_id ;
	    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

	public function storyboards_shared_with_user_count($user_id = "", $circle_id = "") {
		if($this->input->post()){
			$title = $this->input->post('title');
		}else{
			$title = '';
		}
		
		$where_title = "";
        $where_circle = "";
        if (!empty($circle_id))
            $where_circle = " and c.id = $circle_id";
		if(!empty($title))
			$where_title = " and sb.title LIKE '%".$title."%'";
		  
        $sql = "select 
                    distinct sb.id
                    ,sb.title
                    ,sb.description
                    ,sb.creation_time
                    ,sb.modification_time
                    ,sb.public
                    ,sb.start_end_template
                    ,sb.start_image
                    ,sb.end_text
                    ,sb.end_avatar
                    ,sb.user
                    ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
                    ,(SELECT count(*) from view_storyboard vs2 where  vs2.storyboard = sb.id) as viewed
                  from storyboard sb
                    LEFT OUTER join storyboard_circle sc on sc.storyboard = sb.id
                        LEFT OUTER join user_circle uc on sc.circle = uc.circle
                        LEFT OUTER join circle c on c.id = uc.circle
                        LEFT OUTER join user u on u.id = sb.user
                    where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or sb.public = '1'  or sb.user = $user_id  ) $where_circle $where_title";
		
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function all_accessible_storyboards_count($user_id = "") {
		if($this->input->post()){
			$title = $this->input->post('title');
		}else{
			$title = '';
		}
		$where_title = "";
		
		if(!empty($title))
			$where_title = " and sb.title LIKE '%".$title."%'";
        $sql = "select 
                    distinct sb.id
                    ,sb.title
                    ,sb.description
                    ,sb.creation_time
                    ,sb.modification_time
                    ,sb.public
                    ,sb.start_end_template
                    ,sb.start_image
                    ,sb.end_text
                    ,sb.end_avatar
                    ,sb.user
                    ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
                    ,(SELECT count(*) from view_storyboard vs2 where  vs2.storyboard = sb.id) as viewed
                  from storyboard sb
                    LEFT OUTER join storyboard_circle sc on sc.storyboard = sb.id
                        LEFT OUTER join user_circle uc on sc.circle = uc.circle
                        LEFT OUTER join circle c on c.id = uc.circle
                        LEFT OUTER join user u on u.id = sb.user
                    where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or sb.public = '1'  or sb.user = $user_id  )  $where_title";
	    
        $query = $this->db->query($sql);
        return $query->result_array();
    }
  public function most_viewed_storyboards_of_circle($circle_id = "", $limit = '') {
        $sql = "select 
            distinct sb.id
            ,sb.title
                    ,sb.description
                    ,sb.creation_time
                    ,sb.modification_time
                    ,sb.public
                    ,sb.start_end_template
                    ,sb.start_image
                    ,sb.end_text
                    ,sb.end_avatar
                    ,sb.user
                    ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
                    ,(SELECT count(*) from view_storyboard vs2 where  vs2.storyboard = sb.id) as viewed
                    from storyboard sb
                    inner join storyboard_circle sc on sb.id = sc.storyboard
                    LEFT OUTER join user u on u.id = sb.user
                    where sc.circle = $circle_id ORDER BY viewed DESC";
        if(!empty($limit))
          $sql .= " LIMIT ".$limit;
        $query = $this->db->query($sql);
        return $query->result_array();
  }

  public function most_viewed_storyboards_of_user($user_id = "", $limit = '') {
        $sql = "select 
            distinct sb.id
            ,sb.title
                    ,sb.description
                    ,sb.creation_time
                    ,sb.modification_time
                    ,sb.public
                    ,sb.start_end_template
                    ,sb.start_image
                    ,sb.end_text
                    ,sb.end_avatar
                    ,sb.user
                    ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
                    ,(SELECT count(*) from view_storyboard vs2 where  vs2.storyboard = sb.id) as viewed
                    from storyboard sb
                    LEFT OUTER join user u on u.id = sb.user
                    where sb.user = $user_id ORDER BY viewed DESC";
        if(!empty($limit))
          $sql .= " LIMIT ".$limit;
        $query = $this->db->query($sql);
        return $query->result_array();
  }

  public function get_sbs_number_per_10days() {
    $sql = "SELECT days.day, count(storyboard.id) as n FROM
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
              left join storyboard
               on days.day = date(storyboard.creation_time)
            group by
              days.day";
    $query = $this->db->query($sql);
    return  $query->result_array();
  }

  public function count_public_shared_cards() {
    $sql = "SELECT 
              (select count(distinct id)  FROM storyboard c
                inner join storyboard_circle cc on cc.storyboard = c.id
                where c.public = 0)
              + 
              (select count(distinct id) from storyboard where storyboard.public = 1)
            AS n";
    $query = $this->db->query($sql);
    $n = $query->result_array();
    return  $n[0]['n'];
  }

  public function recently_published_storyboards($user_id, $number = 5) {
    $limit = '';
    if(!empty($number))
      $limit = "LIMIT 0, ".$number;
      $sql = "select 
                    distinct sb.id
                    ,sb.title
                    ,sb.description
                    ,sb.creation_time
                    ,sb.modification_time
                    ,sb.public
                    ,sb.start_end_template
                    ,sb.start_image
                    ,sb.end_text
                    ,sb.end_avatar
                    ,sb.user
                    ,CONCAT(u.first_name, ' ',u.last_name) as 'author'
                    ,(SELECT count(*) from view_storyboard vs2 where  vs2.storyboard = sb.id) as viewed
                  from storyboard sb
                    LEFT OUTER join storyboard_circle sc on sc.storyboard = sb.id
                        LEFT OUTER join user_circle uc on sc.circle = uc.circle
                        LEFT OUTER join circle c on c.id = uc.circle
                        LEFT OUTER join user u on u.id = sb.user
                    where ( ( ( uc.user = $user_id  and uc.status = '2') or c.admin = $user_id ) or sb.public = '1'  or sb.user = $user_id  )
                    ORDER BY sb.creation_time DESC ".$limit;
    $query = $this->db->query($sql);
    return  $query->result_array();
  }

  public function card_used_in_storyboards($card_id) {
    $sql = "select count(sb_id) from storyboard_slide where `type` = 'card'and content = $card_id";
    $query = $this->db->query($sql);
    $n =  $query->result_array();
    $n = $n[0]['count(sb_id)'];
    return (int) $n;
  }

}
