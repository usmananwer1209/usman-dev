<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once "abstract_controller.php";

class Circle extends abstract_controller {

	public function __construct(){
    	parent::__construct();
        $this->load->model('card_circle_model', 'card_circle');
        }

	public function index($message = "", $is_error = false){
		$user = $this->session->userdata('user');
		if(!empty($user)){
			$this->all();
			}
		else
			redirect('login');
		}

	public function edit($id="",$message = ""){
		$current = '/circle/edit/'.$id;
		$data = $this->security($current);
		if($data && !empty($data)){
			$data['title'] = 'Edit Circle';
			if($message!="")
				$data['message'] = $message;
			$data['obj'] = $this->circles->get_by_id($id);//TODO if is null
			$data['users'] = $this->users->list_records();
			$data['op'] = "edit_";

			$objs = $this->users->list_records($id);
			$data['objs'] = $objs;
			
			$this->load->view('general/header', $data);
			$this->load->view('circle/circle',$data);
			$this->load->view('general/footer');
			}
		}

    public function delete($circle_id) {
        try {
            $this->user_circles->delete_list(array("circle" => $circle_id));
            $this->card_circle->delete_list(array("circle" => $circle_id));
            $this->circles->delete_list(array("id" => $circle_id));

            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }

	public function view($id="",$message = ""){
		$current = '/circle/view/'.$id;
		$data = $this->security($current);
		if($data && !empty($data)){
			$obj = $this->circles->get_by_id($id);
			$data['title'] = $obj->name;
			if($message!="")
				$data['message'] = $message;
			
			$data['circle'] = $obj;
			$data['op'] = "view_";
			
			$data['admin'] = (object )$this->users->get_by_id($obj->admin);

      $objs = $this->users->list_records_and_admin($id);
			$data['members'] = $objs;


			$user = $this->user();
			$status = $this->user_circles->user_in_circle_status($obj,$user);
			$data['user_circle'] = $status;
			
			$this->load->model('cards_model', 'cards');
      //$cards = $this->cards->cards_of_circle($id);
      $cards = $this->cards->most_viewed_cards_of_circle($id);
      $cards = (array) $cards;
      foreach ($cards as &$card) {
          $card = (array)$card;
          $circles = $this->circles->circles_of_cards($card['id']);
          if(!empty($circles) && !empty($card)){
              $card['circles'] = $circles;
          }
      }
			$data['cards'] = $cards;

      $this->load->model('storyboards_model', 'storyboards');
      $storyboards = $this->storyboards->most_viewed_storyboards_of_circle($id);
      $storyboards = (array) $storyboards;
      foreach ($storyboards as &$sb) {
          $sb = (array)$sb;
          $circles = $this->circles->circles_of_storyboards($sb['id']);
          if(!empty($circles) && !empty($sb)){
              $sb['circles'] = $circles;
          }
      }
      $data['storyboards'] = $storyboards;

			$current = '/circle/'.$this->input->post('op');
			
			/*echo '<pre>';
      var_dump($data['circle']);
			var_dump($user);
			echo '</pre>';*/
			
			$this->load->view('general/header', $data);
			$this->load->view('circle/view',$data);
			$this->load->view('general/footer');
			}
		}

	public function add(){
		$current = '/circle/add';
		$data = $this->security($current);
		if($data && !empty($data)){
			$data['title'] = 'Add new circle';
			$data['users'] = $this->users->list_records();
			$data['op'] = "add_";
			$this->load->view('general/header', $data);
			$this->load->view('circle/circle',$data);
			$this->load->view('general/footer');
			}
		}

	public function all($user_id="", $stauts = ""){
		$user = (object)$this->session->userdata('user');
		$current = '/circle/all/'.$user_id;
		$data = $this->security($current);
		if($data && !empty($data)){
				$data['title'] = 'List Circle';
				if($user_id==""){
					$objs = $this->circles->list_records();
            } else {
					$objs = $this->circles->list_records($user_id, $stauts);
					}
				$objs2 = array();
				foreach ($objs as $obj) {
					$obj = (array)$obj;
					$obj['user_circle'] = $this->user_circles->user_in_circle_status((object)$obj,(object)$user);

					$where = array(
							"status"=>user_circle_status::request_accept,
							"circle"=>$obj['id']);
					$obj['user_count'] = count($this->user_circles->list_records($where));
					//$obj['user_count'] = 0;
					$obj = (object)$obj;
					$objs2[] = $obj;
				}
				$data['objs'] = $objs2;
                                $data['active_search'] = true;
				$this->load->view('general/header', $data);
				$this->load->view('circle/all',$data);
				$this->load->view('general/footer');
			}
		}

	public function submit(){
		$current = '/circle/'.$this->input->post('op');
		$data = $this->security($current);
		if($data && !empty($data)){
            $obj = $this->prepare();
			
			$this->validation();

			if($this->form_validation->run()){
				$obj = $this->circles->save($obj);
				
				$this->circles->addmemberuser($obj);
				$this->edit($obj->id,success_changes());
            } else {
				if($this->input->post('op') == "add_")
					$this->add();
				else 
					$this->edit();
				}
			}
		else
			redirect('login');
		}

	public function join($circle, $user, $status = '1'){
		$obj['user'] = $user;
		$obj['circle'] = $circle;
		$obj['status'] = enum_user_circle_status($status);
		try {
			$this->user_circles->save($obj);	
			echo "ok";	
		} catch (Exception $e) {
			echo "ko";	
		}
    }
	
	private function validation(){
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[40]|encode_php_tags|xss_clean|callback__check_unique_name');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[2]|max_length[1000]|encode_php_tags|xss_clean');
		//$admin = $obj->admin;
		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
		}

	private function prepare(){
		$obj = array();
		$v1 = $this->input->post('id');
        if (!empty($v1))
            $obj['id'] = $this->input->post('id');
		$v2 = $this->input->post('name');
        if (!empty($v2))
            $obj['name'] = $this->input->post('name');
		$v3 = $this->input->post('description');
        if (!empty($v3))
            $obj['description'] = $this->input->post('description');
		$v4 = $this->input->post('admin');
        if (!empty($v4))
            $obj['admin'] = $this->input->post('admin');
		return (object)$obj;
		}

    public function _check_unique_name($str) {

        if ($str == $this->input->post('h_name')) {
            return true;
        } else {
            $where = array("name" => $str);

            $count = $this->circles->count($where);

            if ($count == 0)
                return true;
            else {
                $this->form_validation->set_message('_check_unique_name', 'the name you entered already exists');
                return false;
            }
        }
    }

	}

