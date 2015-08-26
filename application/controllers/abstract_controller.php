<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class abstract_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('users_model', 'users');
		$this->load->model('circles_model', 'circles');
		$this->load->model('user_circles_model', 'user_circles');
		$this->load->model('cards_model', 'cards');
		$this->load->model('storyboard_model', 'storyboards');

	}
	public function user(){
		$user = $this->session->userdata('user');
		return $user;
	}
	public function user_id(){
		$user = $this->session->userdata('user');

		if (!empty($user))
			return $user->id;
	}
	public function security($current) {
		$user = $this->session->userdata('user');
		if (!empty($user)) {
			if (has_acces($user, $current)) {
				$page_data = array();
				$page_data['current'] = $current;
				$page_data['description'] = '';
				$page_data['user'] = $user;
				$page_data['avatar'] = $this->users->get_avatar($user);
				$page_data['my_circles'] =$this->circles->list_my_cirles($user->id, "2");
				$page_data['all_accessible_cards_number'] = count($this->cards->cards_shared_with_user($user -> id,'',false));
				$page_data['all_accessible_storyboards_number'] = count($this->storyboards->all_accessible_storyboards($user->id,'',false));
				$page_data['all_circles'] =$this->circles->list_all_cirles();
				$page_data['my_recent_5_cards'] = $this->users->list_cards($user->id);
				$page_data['my_recent_5_storyboards'] = $this->users->list_storyboards($user->id);
                                
				//var_dump($page_data['cards']);
				$page_data['notifications'] = $this->user_circles->notifications($user->id);
				return $page_data;
			} else
				redirect('authorization');
		} else
			redirect('login');
		return false;
	}


}
