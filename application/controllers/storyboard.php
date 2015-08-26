<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once "abstract_controller.php";

class Storyboard extends abstract_controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('storyboard_model', 'storyboards');
	
    $this->load->model('cards_model', 'cards');
	
	$this->load->model('explore_model', 'explore');
	
  }

  public function index($message = "", $is_error = false) {
	 
    $user = $this->session->userdata('user');
    if (!empty($user)) {
      $this->my_storyboards($message, $is_error);
    } else
      redirect('login');
  }

  public function edit($id="", $message = "") {
	//  print_r($this->storyboards->get_companies_api());
      $current = '/storyboard/edit/';
    $data = $this->security($current);
    $this_sb = $this->storyboards->get_by_id($id);
    if(!empty($data) && !empty($this_sb) && $this_sb->user == $this->user_id()) {

      $data['title'] = 'Edit Storyboard';

      if ($message != "")
          $data['message'] = $message;
      if ($message == "succes")
          $data['message'] = "Your Changes have been Successfully Submitted";
      
      $data['op'] = "edit_";

      $where = array( "user" => $this->user_id() );
      $my_cards = $this->cards->list_records($where);
      foreach ($my_cards as $k => $v) {
        $my_cards[$k]->first_name = $data['user']->first_name;
        $my_cards[$k]->last_name = $data['user']->last_name;
        $my_cards[$k]->open_type = 'my_cards';
      }
      $data['my_cards_number'] = count($my_cards);
      $shared_cards = $this->cards->cards_shared_with_user_not_his($this->user_id());
      $data['shared_cards_number'] = count($shared_cards);
      $public_cards = $this->cards->public_cards_not_users($this->user_id());
      $data['public_cards_number'] = count($public_cards);
      $data['type'] = 'my_cards';
      foreach ($shared_cards as $k => $v) {
        $shared_cards[$k]->open_type = 'shared_cards';
      }
      foreach ($public_cards as $k => $v) {
        $public_cards[$k]->open_type = 'public_cards';
      }


      $data['all_cards'] = array_merge($my_cards, $shared_cards, $public_cards);
      $data['cards'] = $data['all_cards'];

      $this_sb->slides = $this->storyboards->get_sb_slides($this_sb->id);

      $data['obj'] = $this_sb;

      $this->load->model('indicators_model', 'indicators');
      $indicators = $this->indicators->list_records(array(), array(), 0,  0, array("name", "asc"));
      //$indicators = $this->_sortArrayofObjectByProperty($indicators, "type", "ASC");
      $data['indicators'] = $indicators;

      $this->load->view('general/header', $data);
      $this->load->view('storyboard/storyboard', $data);
      $this->load->view('general/footer');
        } else
      redirect('authorization');
  }

  public function add($message = "", $is_error = false) {
	 
    $current = '/storyboard/add/';
    $data = $this->security($current);
    if (!empty($data)) {
        $data['title'] = 'Add New Storyboard';
        if ($message != "")
            $data['message'] = $message;
        $data['op'] = "add_";

        $where = array( "user" => $this->user_id() );
        $my_cards = $this->cards->list_records($where);
        foreach ($my_cards as $k => $v) {
          $my_cards[$k]->first_name = $data['user']->first_name;
          $my_cards[$k]->last_name = $data['user']->last_name;
          $my_cards[$k]->open_type = 'my_cards';
        }
        $data['my_cards_number'] = count($my_cards);
        $shared_cards = $this->cards->cards_shared_with_user_not_his($this->user_id());
        $data['shared_cards_number'] = count($shared_cards);
        $public_cards = $this->cards->public_cards_not_users($this->user_id());
        $data['public_cards_number'] = count($public_cards);
        $data['all_cards'] = array_merge($my_cards, $shared_cards, $public_cards);
        /*foreach ($shared_cards as $k => $v) {
          $shared_cards[$k]->open_type = 'shared_cards';
        }
        foreach ($public_cards as $k => $v) {
          $public_cards[$k]->open_type = 'public_cards';
        }*/
        //$data['cards'] = $data['all_cards'];
		$data['cards'] = $my_cards;
        $data['type'] = 'my_cards';

        $this->load->model('indicators_model', 'indicators');
        $indicators = $this->indicators->list_records(array(), array(), 0,  0, array("name", "asc") );

        $data['indicators'] = $indicators;

        $this->load->view('general/header', $data);
        $this->load->view('storyboard/storyboard', $data);
        $this->load->view('general/footer');
        } else
      redirect('authorization');
  }

  public function submit_add() {
	
    $current = '/storyboard/add/';
    $data = $this->security($current);
    if (!empty($data)) {
		
      $post_data = $this->input->post();

      //collecting storyboard data
      $sb_data['title'] = $post_data['title'];
      $sb_data['description'] = $post_data['description'];
      $sb_data['start_image'] = $post_data['start_image'];
      $sb_data['start_end_template'] = $post_data['start_end_template'];
      $sb_data['end_text'] = $post_data['end_text'];
      $sb_data['end_link'] = $post_data['end_link'];
      $sb_data['end_link_name'] = $post_data['end_link_name'];
      $sb_data['end_avatar'] = ($post_data['end_avatar'] == 'true')?1:0;
      $sb_data['public'] = 0;
      $sb_data['creation_time'] = 'now';
      $sb_data['modification_time'] = 'now';
	  $sb_data['style'] = $post_data['userstyle'];
      /*$sb_data['parent_id'] = -1;*/
      $sb_data['user'] = $this->user_id();

      //saving storyboard and getting sb_id
      $sb_id = $this->storyboards->add_sb($sb_data);

      //saving storyboard slides
      if(!empty($post_data['slides'])){
          $this->save_slide_data($sb_id, $post_data['slides']);
      }

      //saving complete, we return ok
      echo $sb_id;
    }
    else
      echo "ko";
  }

    protected function save_slide_data($sb_id, $slide_content) {
        foreach ($slide_content as $k => $slide) {
            $this->save_slide($sb_id, $slide, $k);
        }
    }

    protected function save_slide($sb_id, $slide, $order) {
        $slide_data = array();
        $slide_data['type'] = $slide['type'];
        $slide_data['template'] = $slide['template'];
        $slide_data['content'] = $slide['content'];
        $slide_data['title'] = $slide['title'];
        $slide_data['description'] = $slide['description'];
        $slide_data['order'] = $order;
        $slide_data['sb_id'] = $sb_id;

        if (!empty($slide['wc_words'])) {
            $slide_data['wc_words'] = $slide['wc_words'];
            $slide_data['wc_type'] = $slide['wc_type'];
        }
        else {
            $slide_data['wc_words'] = '';
            $slide_data['wc_type'] = '';
        }

        $slide_id = $this->storyboards->save_slide_to_sb($slide_data);

        if ($slide_data['type'] == 'wordcloud') {
            $wc_detail_data = array();

            $wc_detail_data['sb_slide_id'] = $slide_id;
            $wc_detail_data['json_data'] = json_encode($slide['wc_content']);

            $this->storyboards->insert_wc_content_for_slide($wc_detail_data);
        }

    }

    protected function update_slide_data($sb_id, $slide_content) {
        foreach ($slide_content as $k => $slide) {

            $slide_id = guid_to_id($slide['guid']);

            if (!$slide_id) {
                // it's an insert (new slide here
                $this->save_slide($sb_id, $slide, $k);
                continue;
            }

            $slide_data = array();
            $slide_data['type'] = $slide['type'];
            $slide_data['id'] = $slide_id;
            $slide_data['template'] = $slide['template'];
            $slide_data['content'] = $slide['content'];
            $slide_data['title'] = $slide['title'];
            $slide_data['description'] = $slide['description'];
            $slide_data['order'] = $k;
            $slide_data['sb_id'] = $sb_id;

            $slide_data['wc_words'] = $slide['wc_words'];
            $slide_data['wc_type'] = $slide['wc_type'];

            $this->storyboards->update_slide($slide_id, $slide_data);

            if ($slide['wc_content']) {
                $this->storyboards->update_wc_content_for_slide($slide_id, json_encode($slide['wc_content']));
            }
        }
    }

    public function submit_edit()
    {
        $current = '/storyboard/edit/';
        $data = $this->security($current);
        if (!empty($data)) {
            $post_data = $this->input->post();

            //collecting storyboard data
            $sb_id = $post_data['sb_id'];
            $sb_data['title'] = $post_data['title'];
            $sb_data['description'] = $post_data['description'];
            $sb_data['start_image'] = $post_data['start_image'];
            $sb_data['start_end_template'] = $post_data['start_end_template'];
            $sb_data['end_text'] = $post_data['end_text'];
            $sb_data['end_link'] = $post_data['end_link'];
            $sb_data['end_link_name'] = $post_data['end_link_name'];
            $sb_data['end_avatar'] = ($post_data['end_avatar'] == 'true') ? 1 : 0;
            $sb_data['modification_time'] = 'now';
            $sb_data['style'] = $post_data['userstyle'];
            /*$sb_data['parent_id'] = -1;*/

            //saving storyboard and getting sb_id
            $this->storyboards->edit_sb($sb_id, $sb_data);

            //removing all previous sb slides
            $this->storyboards->remove_sb_slides($sb_id);

            // and re-add them
            $this->save_slide_data($sb_id, $post_data['slides']);

            //saving complete, we return ok
            echo $sb_id;
        } else
            echo "ko";
    }

  /* preview_storyboard_code Begin 
  
  public function submit_preview_add() {
    $current = '/storyboard/add/';
    $data = $this->security($current);
    if (!empty($data)) {
      $post_data = $this->input->post();

      //collecting storyboard data
      $sb_data['title'] = $post_data['title'];
      $sb_data['description'] = $post_data['description'];
      $sb_data['start_image'] = $post_data['start_image'];
      $sb_data['start_end_template'] = $post_data['start_end_template'];
      $sb_data['end_text'] = $post_data['end_text'];
      $sb_data['end_avatar'] = ($post_data['end_avatar'] == 'true')?1:0;
      $sb_data['public'] = 0;
      $sb_data['creation_time'] = 'now';
      $sb_data['modification_time'] = 'now';
      $sb_data['parent_id'] = $post_data['parent_id'];
      $sb_data['user'] = $this->user_id();

      //saving storyboard and getting sb_id
      $sb_id = $this->storyboards->add_sb($sb_data);

      //saving storyboard slides
      foreach ($post_data['slides'] as $k => $slide) {
        $slide_data = array();
        $slide_data['type'] = $slide['type'];
        $slide_data['template'] = $slide['template'];
        $slide_data['content'] = $slide['content'];
        $slide_data['title'] = $slide['title'];
        $slide_data['description'] = $slide['description'];
        $slide_data['order'] = $k;
        $slide_data['sb_id'] = $sb_id;

        $this->storyboards->save_slide_to_sb($slide_data);
      }

      //saving complete, we return ok
      echo $sb_id;
        } else
      echo "ko";
  }

  public function submit_preview_edit() {
    $current = '/storyboard/edit/';
    $data = $this->security($current);
    if (!empty($data)) {
      $post_data = $this->input->post();

      //collecting storyboard data
      $sb_id = $post_data['sb_id'];
      $sb_data['title'] = $post_data['title'];
      $sb_data['description'] = $post_data['description'];
      $sb_data['start_image'] = $post_data['start_image'];
      $sb_data['start_end_template'] = $post_data['start_end_template'];
      $sb_data['end_text'] = $post_data['end_text'];
      $sb_data['end_avatar'] = ($post_data['end_avatar'] == 'true')?1:0;
      $sb_data['modification_time'] = 'now';
      $sb_data['parent_id'] = $post_data['parent_id'];

      //saving storyboard and getting sb_id
      $this->storyboards->edit_sb($sb_id, $sb_data);

      //removing all previous sb slides
      $this->storyboards->remove_sb_slides($sb_id);

      //saving storyboard slides
      foreach ($post_data['slides'] as $k => $slide) {
        $slide_data = array();
        $slide_data['type'] = $slide['type'];
        $slide_data['template'] = $slide['template'];
        $slide_data['content'] = $slide['content'];
        $slide_data['title'] = $slide['title'];
        $slide_data['description'] = $slide['description'];
        $slide_data['order'] = $k;
        $slide_data['sb_id'] = $sb_id;

        $this->storyboards->save_slide_to_sb($slide_data);
      }

      //saving complete, we return ok
      echo $sb_id;
        } else
      echo "ko";
  }

    preview_storyboard_code End */
  
  	public function view($id="", $message = "") {
    $current = '/storyboard/view/' . $id;
    $data = $this->security($current);
	$this_sb = $this->storyboards->get_by_id($id);
        if (!empty($data) && !empty($this_sb) && count($this->storyboards->is_accessible_storyboards($this->user_id(),$id))>0) {
            $data = $this->load_slides_for_view_and_embed($id, $this_sb, $data, $this_sb->title, $message);

            $this->load->view('general/header', $data);
            $this->load->view('storyboard/view', $data);
            $this->load->view('general/footer');
        }else
      redirect('authorization');
    }

    protected function load_slides_for_view_and_embed($id, $this_sb, $data, $title, $message)
    {

        if ($message != "")
            $data['message'] = $message;

        $data['title'] = $title;
        $data['obj'] = $this_sb;
        $data['op'] = "view_";
        $this_sb->slides = $this->storyboards->get_sb_slides($this_sb->id);

        $data['obj'] = $this_sb;

        $where = array("user" => $this->user_id());
        $my_cards = $this->cards->list_records($where);
        $data['cards'] = $my_cards;
        $cards_ids = array();

        foreach ($this_sb->slides as $k => $slide) {
            if ($slide->type == 'card') {
                $cards_ids[] = $slide->content;
            } else if ($slide->type == 'wordcloud') {

                $wc_data = $this->get_wc_json($slide->id);

                if ($wc_data) {
                    $slide->word_content = json_decode($wc_data[0]->json_data);
                }
            }
        }

        $data['all_cards'] = $this->cards->get_by_ids($cards_ids);
        $this->load->model('indicators_model', 'indicators');
        $indicators = $this->indicators->list_records();
        $data['indicators'] = $indicators;

        if ($this_sb->user != $this->user_id()) {
            $this->load->model('view_storyboards_model', 'view_storyboards');
            $viewed = array("storyboard" => $id, "user" => $this->user_id());
            $this->view_storyboards->save($viewed);
        }

        $data['sb_user'] = $this->users->get_by_id($this_sb->user);

        return $data;
    }

    public function embed($guid="")
    {
        $id = guid_to_id($guid);
        $current = '/storyboard/view/' . $id;

        if ($id)
            $this_sb = $this->storyboards->get_by_id($id);

        if (!empty($this_sb) && $this_sb->public == 1) {
            $title = 'Idaciti - ' . $this_sb->title;

            $data = array();
            $data = $this->load_slides_for_view_and_embed($id, $this_sb, $data, $title, '');

            $data['is_embed'] = true;

            $user = $this->session->userdata('user');
            $data['current'] = $current;
            $data['user'] = $user;
            $data['avatar'] = $this->users->get_avatar($user);

            $this->load->view('general/header', $data);
            $this->load->view('storyboard/embed', $data);
            $this->load->view('general/footer');
        }
    }

  public function all($circle_id = "") {
    $current = '/storyboard/all/';
    $data = $this->security($current);
    if ($data && !empty($data)) {
        $this->load->model('circles_model', 'circles');
        $this->load->model('users_model', 'users');
        $this->load->model('view_storyboards_model', 'view_storyboards');

        $data['active_search'] = true;
        if(!empty($circle_id)){
		  $data['total_result_count'] = count($this->storyboards->storyboards_shared_with_user($this->user_id(), $circle_id));
          $storyboards = $this->storyboards->storyboards_shared_with_user($this->user_id(), $circle_id,24);
		}else{
		  $data['total_result_count'] = count($this->storyboards->all_accessible_storyboards($this->user_id()));
          $storyboards = $this->storyboards->all_accessible_storyboards($this->user_id(),24);
		}
        //$storyboards = (array) $storyboards;
        foreach ($storyboards as &$storyboard) {
            //$storyboard = (array) $storyboard;
            $circles = $this->circles->circles_of_storyboards($storyboard['id']);
            
            if (!empty($circles) && !empty($storyboard)) {
                $storyboard['circles'] = $circles;
            }
            $storyboard['viewed'] = count($this->view_storyboards->list_records(array("storyboard" => $storyboard['id'])));
        }
        
        $data['storyboards'] = $storyboards;
        if ($circle_id != "")
            $data['circle'] = $this->circles->get_by_id($circle_id);

        $this->load->view('general/header', $data);
        $this->load->view('storyboard/all', $data);
        $this->load->view('general/footer');
    }
  }
  public function load_more_circle_storyboards($circle_id = "") {
    $current = '/storyboard/all/';
    $data = $this->security($current);
        $this->load->model('circles_model', 'circles');
        $this->load->model('users_model', 'users');
        $this->load->model('view_storyboards_model', 'view_storyboards');
		
        $data['active_search'] = true;
        if(!empty($circle_id)){
		  $data['total_result_count'] = count($this->storyboards->storyboards_shared_with_user_count($this->user_id(), $circle_id));
          $storyboards = $this->storyboards->storyboards_shared_with_user($this->user_id(), $circle_id);
		}else{
		  $data['total_result_count'] = count($this->storyboards->all_accessible_storyboards_count($this->user_id()));
          $storyboards = $this->storyboards->all_accessible_storyboards($this->user_id());
		}
        if(count($storyboards)>0){
        foreach ($storyboards as &$storyboard) {
            //$storyboard = (array) $storyboard;
            $circles = $this->circles->circles_of_storyboards($storyboard['id']);
            
            if (!empty($circles) && !empty($storyboard)) {
                $storyboard['circles'] = $circles;
            }

            $storyboard['viewed'] = count($this->view_storyboards->list_records(array("storyboard" => $storyboard['id'])));
        }
        $data['storyboards'] = $storyboards;
        if ($circle_id != "")
            $data['circle'] = $this->circles->get_by_id($circle_id);
			
		$data['type'] = $this->input->post('type');
        $this->load->view('storyboard/load_more_circle_storyboards', $data);
		}else{
			echo 'ko';
		}
  }
  public function delete($id){
    try {
        $this->storyboards->delete($id);
        echo "ok";
    } catch (Exception $e) {
        echo "ko";
    }
  }

  public function publish($id, $public) {
    $storyboard = $this->storyboards->get_by_id($id);
    $storyboard->public = $public;
    try {
        $this->storyboards->edit($storyboard);
        echo "ok";
    } catch (Exception $e) {
        echo "ko";
    }
  }

  public function share($id) {
    $storyboard = $this->storyboards->get_by_id($id);
    $s_circles = $this->input->post('circles');
    $circles = explode(",", $s_circles);

    try {
        $this->storyboards->share($storyboard, $circles);
        echo "ok";
    } catch (Exception $e) {
        echo "ko";
    }
  }

  public function my_storyboards($message = "", $is_error = false) {
    $this->load->model('users_model', 'users');
    $this->load->model('view_storyboards_model', 'view_storyboards');
    $current = '/storyboard/my_storyboards/';
    $data = $this->security($current);
    if (!empty($data)) {
        $data['title'] = 'My Storyboards';
        $data['active_search'] = true;
        if ($message != "")
            $data['message'] = $message;

        $where = array(
            "user" => $this->user_id()
        );
		$like = array();
		$order_by = array('creation_time','DESC');
		$data['total_result_count'] = count($this->storyboards->list_records($where));
        $objs = $this->storyboards->list_records($where,$like,24,0,$order_by);
        $data['objs'] = $objs;

        // adding to author of the card's name based on the id
        foreach ($objs as $obj) {
            $card_author = $this->users->get_by_id($obj->user);
            $obj->author = $card_author->first_name." ".$card_author->last_name;

            $obj->viewed = count($this->view_storyboards->list_records(array("storyboard" => $obj->id)));
        }
        //var_dump($objs);

        $this->load->view('general/header', $data);
        $this->load->view('storyboard/my_storyboards', $data);
        $this->load->view('general/footer');
    }
  }
  public function load_more_my_storyboards() {
			$this->load->model('users_model', 'users');
    		$this->load->model('view_storyboards_model', 'view_storyboards');
    		$current = '/storyboard/my_storyboards/';
       		$data = $this->security($current);
			$start = $this->input->post('start');
			$limit = $this->input->post('limit');
			$title = $this->input->post('title');
			$data['type'] = $this->input->post('type');
			$sort_by = $this->input->post('sort_by');
			$sort_order = $this->input->post('sort_order');
			if($sort_by == 'name'){
				$sort_by = 'title';
			}
			$where = array(
                "user" => $this->user_id()
            );
			if($title!=''){
				$like = array('title' => $title);
			}else{
				$like = array();
			}
			$order_by = array($sort_by, $sort_order);
            $objs = $this->storyboards->list_records($where,$like,$limit,$start,$order_by);
			$data['total_result_count'] = count($this->storyboards->list_records($where,$like));
            $data['objs'] = $objs;
            // adding to author of the card's name based on the id
			if(count($objs)>0){
				foreach ($objs as $obj) {
					$card_author = $this->users->get_by_id($obj->user);
					$obj->author = $card_author->first_name." ".$card_author->last_name;
					$obj->viewed = count($this->view_storyboards->list_records(array("storyboard" => $obj->id)));
				}
				$this->load->view('storyboard/load_more_my_storyboards', $data);
			}else{
				echo 'ko';
			}
    }
  public function get_cards($type = "my_cards") {
    $current = '/storyboard/add';
    $data = $this->security($current);
    if (!empty($data)) {
      if($type == "my_cards") {
        $where = array( "user" => $this->user_id() );
        $my_cards = $this->cards->list_records($where);
        foreach ($my_cards as $k => $v) {
          $my_cards[$k]->first_name = $data['user']->first_name; 
          $my_cards[$k]->last_name = $data['user']->last_name;
          $my_cards[$k]->open_type = $type;
        }
        $data['type'] = $type;
        $data['cards'] = $my_cards;
        $this->load->view('storyboard/deck_cards', $data);
      }
      if($type == "shared_cards") {
        $cards = $this->cards->cards_shared_with_user_not_his($this->user_id());
        foreach ($cards as $k => $v) {
          $cards[$k]->open_type = $type;
        }
        $data['type'] = $type;
        $data['cards'] = $cards;
        $this->load->view('storyboard/deck_cards', $data);
      }
      if($type == "public_cards") {
        $cards = $this->cards->public_cards_not_users($this->user_id());
        foreach ($cards as $k => $v) {
          $cards[$k]->open_type = $type;
        }
        $data['type'] = $type;
        $data['cards'] = $cards;
        $this->load->view('storyboard/deck_cards', $data);
      }
    }
  }
	public function gettags()
	{
		$post_data = $this->input->post();
		$data = array();
		//print_r($_REQUEST);
		$result = $this->storyboards->getcardtype($post_data['data']);
		foreach($result as $type) { $data['type'] = $type->type;}
		$result = $this->storyboards->gettags($post_data['data'],$data['type']);
		$styles = $this->storyboards->getclass($post_data['sid']);
		foreach($styles as $style) { $data['styleclass'] = $style->style;}
		$data['tags'] = $result;
		echo json_encode($data);
	}

    public function get_wc_json($slide_ids) {
      return $this->storyboards->get_wc_commands_for_slide($slide_ids);
  }

  public function _sortArrayofObjectByProperty($array, $property, $order = "DESC") {
        $array = (array)$array;
        $cur = 1;
        $stack[1]['l'] = 0;
        $stack[1]['r'] = count($array) - 1;
        do {
            $l = $stack[$cur]['l'];
            $r = $stack[$cur]['r'];
            $cur--;
            do {
                $i = $l;
                $j = $r;
                $tmp = $array[(int) ( ($l + $r) / 2 )];

                // split the array in to parts
                // first: objects with "smaller" property $property
                // second: objects with "bigger" property $property
                do {
                    while ($array[$i][$property] < $tmp[$property] )
                        $i++;
                    while ($tmp[$property] < $array[$j][$property])
                        $j--;

                    // Swap elements of two parts if necesary
                    if ($i <= $j) {
                        $w = $array[$i];
                        $array[$i] = $array[$j];
                        $array[$j] = $w;

                        $i++;
                        $j--;
                    }
                } while ($i <= $j);

                if ($i < $r) {
                    $cur++;
                    $stack[$cur]['l'] = $i;
                    $stack[$cur]['r'] = $r;
                }
                $r = $j;
            } while ($l < $r);
        } while ($cur != 0);
        // Added ordering.
        if ($order == "DESC") {
            $array = array_reverse($array);
        }
        return $array;
  }

    function setDescription()
    {
        $this->load->library('session');
        $post_data = $this->input->post();
        $this->session->set_userdata('preveiw_title', $post_data['preview_title']);
        $this->session->set_userdata('preview_description', $post_data['preview_description']);
        return true;
    }
    function getcardtype($id)
    {
        $type = '';
        $result = $this->storyboards->getcardtype($id);
            foreach($result as $type) { $type = $type->type;}
            return $type;
    }
}
