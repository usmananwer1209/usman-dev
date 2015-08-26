<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once "abstract_controller.php";

class Profile extends abstract_controller {

	public function __construct(){
    	parent::__construct();
    	$this->load->model('users_model', 'users');
		  $this->load->model('cards_model', 'cards');
        $this->load->model('circles_model', 'circles');
		}

	public function index($message = "", $is_error = false){
		$user = $this->session->userdata('user');
		if(!empty($user)){
			$this->all();
        } else
			redirect('login');
		}

	public function view($id="",$num_cards = "",$message = "",$op_ = ""){
		$current = '/profile/view/'.$id;
		$data = $this->security($current);
    $obj = $this->users->get_by_id($id);
		if($data && !empty($data) && !empty($obj) && ($id == $this->user_id() || $obj->public_profile == 1)){
			$data['title'] = user_full_name($this->user_id())+ ' Profile';
			if($message!="") 
				$data['message'] = $message;
			$data['obj'] = $obj;
			$data['op'] = "view_";
			
      $cards = $this->cards->most_viewed_cards_of_user($id);
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
      $storyboards = $this->storyboards->most_viewed_storyboards_of_user($id);
      $storyboards = (array) $storyboards;
      foreach ($storyboards as &$sb) {
          $sb = (array)$sb;
          $circles = $this->circles->circles_of_storyboards($sb['id']);
          if(!empty($circles) && !empty($sb)){
              $sb['circles'] = $circles;
          }
      }
      $data['storyboards'] = $storyboards;



			$data['cards'] = $cards;

			$circles = $this->circles->circles_of_user($id);
			$data['circles'] = $circles;
			$data['num_cards'] = $num_cards;

			$this->load->view('general/header', $data);
			$this->load->view('profile/view',$data);
			$this->load->view('general/footer');
        } else
        redirect('authorization');
		}

	public function edit($id="",$message = "",$op_ = ""){
		$current = '/profile/edit/'.$id;
		$data = $this->security($current);
		if($data && !empty($data)){
			$data['title'] = 'Edit Profile';
			if($message!="")
				$data['message'] = $message;
			$user = $this->session->userdata('user');
			$this_user = ( $id=="")?true:false;
			if($this_user){
				$data['obj'] = $user;
				$data['op'] = "edit_my_";
            } else {
				$data['obj'] =  $this->users->get_by_id($id);
				if($op_ != "")
					$data['op'] = $op_;	
				else	
				$data['op'] = "edit_";
				}

			$this->load->view('general/header', $data);
			$this->load->view('profile/profile',$data);
			$this->load->view('general/footer');
			}
		}

	public function add(){
		$current = '/profile/add';
		$data = $this->security($current);
		if($data && !empty($data)){
			$data['title'] = 'Add new user';
			$data['op'] = "add_";
			$this->load->view('general/header', $data);
			$this->load->view('profile/profile',$data);
			$this->load->view('general/footer');
			}
		}

    public function delete($user_id) {
        try {
            $list_cards = $this->cards->cards_of_user($user_id);

            foreach ($list_cards as $card) {
                $this->cards->delete($card['id']);
            }

            $this->user_circles->delete_list(array("user" => $user_id));
            $this->circles->delete_list(array("admin" => $user_id));
            $this->users->delete_list(array("id" => $user_id));
            //*/
            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }

	public function all(){
		$current = '/profile/all';
		$data = $this->security($current);
		if($data && !empty($data)){
				$page_data['title'] = 'List Users';
				$objs = $this->users->list_records();
				$objs2 = array();
				foreach ($objs as $obj) {
					$obj = (array)$obj;
					
					$where = array(
							"status"=>user_circle_status::request_accept,
							"user"=>$obj['id']);
					$obj['circle_accept'] = count($this->user_circles_model->list_records($where));

					$where = array(
							"status"=>user_circle_status::request_wait,
							"user"=>$obj['id']);
					$obj['circle_wait'] = count($this->user_circles_model->list_records($where));

					$where = array(
							"status"=>user_circle_status::request_reject,
							"user"=>$obj['id']);
					$obj['circle_reject'] = count($this->user_circles_model->list_records($where));


					$where = array("user"=>$obj['id']);
					$obj['cards'] = count($this->cards->list_records($where));


					
					$obj = (object)$obj;
					$objs2[] = $obj;
				}
				$data['objs'] = $objs2;
                                $data['active_search'] = true;
				$this->load->view('general/header', $data);
				$this->load->view('profile/all',$data);
				$this->load->view('general/footer');
			}
		}

	public function submit(){
		$current = '/profile/'.$this->input->post('op');
		$data = $this->security($current);
		if($data && !empty($data)){
			$this->validation();
			$obj = $this->prepare($data);
			if($this->form_validation->run()){
                //if ($this->input->post('op') == "add_" ) {
                    //$obj->password = uniqid();
                    //$obj->modification_time = date("Y-m-d H:i:s");
                //}
                $obj = $this->users->save($obj);
				$user_dir = $this->config->item('upload_path') . $obj->id;
				if (!file_exists($user_dir))
					mkdir($user_dir, 0777);
                $config['upload_path'] = $user_dir . '/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['overwrite'] = 'TRUE';
                $config['file_name'] = $this->config->item('default_upload_file');
                $config['max_size'] = '2000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('avatar')) {
                    	echo $this->upload->display_errors();
				}
            	if ($this->input->post('op') == "add_" ) {
					$this->send_mail($obj->password, $this->input->post('email'));
            	}
				if($this->input->post('op')=="edit_my_"){
					$obj = $this->users->get_by_id($obj->id);
					$this->session->set_userdata('user',$obj);
				}
				$this->edit($obj->id,success_changes(),$this->input->post('op'));
            } else {
				if($this->input->post('op') == "add_"){
					$this->add();
				}else{ 
					$this->edit();
				}
			}
        } else
			redirect('login');
		}

	private function validation(){
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[40]|encode_php_tags|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[40]|encode_php_tags|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[2]|max_length[50]|encode_php_tags|xss_clean|callback__check_unique_email');
		$this->form_validation->set_rules('country', 'Country', 'trim|required|min_length[1]|max_length[20]|encode_php_tags|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
		}

	private function prepare($data){

		$obj = array();
		$v1 = $this->input->post('id');
        if (!empty($v1))
            $obj['id'] = $this->input->post('id');
		$v2 = $this->input->post('first_name');
        if (!empty($v2))
            $obj['first_name'] = $this->input->post('first_name');
		$v3 = $this->input->post('last_name');
        if (!empty($v3))
            $obj['last_name'] = $this->input->post('last_name');
		$v4 = $this->input->post('organization');
        if (!empty($v4))
            $obj['organization'] = $this->input->post('organization');
		$v5 = $this->input->post('country');
        if (!empty($v5))
            $obj['country'] = $this->input->post('country');
		$v6 = $this->input->post('email');
        if (!empty($v6))
            $obj['email'] = $this->input->post('email');
		$v10 = $this->input->post('password');
        if (!empty($v10))
            $obj['password'] = $this->input->post('password');

		$v132 = $this->input->post('about');
        if (!empty($v132))
            $obj['about'] = $this->input->post('about');
        $v_twitter = $this->input->post('twitter_profile');
        if (!empty($v_twitter))
            $obj['twitter_profile'] = $this->input->post('twitter_profile');
		$v231 = $this->input->post('facebook_profile');
        if (!empty($v231))
            $obj['facebook_profile'] = $this->input->post('facebook_profile');
		$v432 = $this->input->post('google_profile');
        if (!empty($v432))
            $obj['google_profile'] = $this->input->post('google_profile');
		$v152 = $this->input->post('linkedin_profile');
        if (!empty($v152))
            $obj['linkedin_profile'] = $this->input->post('linkedin_profile');

		$v7 = $this->input->post('public_profile');
		if(!empty($v7)){
			if( $this->input->post('public_profile')=="on" || $this->input->post('public_profile')=="1" || $this->input->post('public_profile')=="true") 
				$obj['public_profile'] = 1;
			else
				$obj['public_profile'] = 0;
        } else
			$obj['public_profile'] = 0;
		$v8 = $this->input->post('is_active');
		if(!empty($v8)){
			if( $this->input->post('is_active')=="on" || $this->input->post('is_active')=="1" || $this->input->post('is_active')=="true") 
				$obj['is_active'] = 1;
        } else
			$obj['is_active'] = 0;

		if($this->input->post('op') == "edit_my_"){
			$obj['is_root'] = $data["user"]->is_root;
        } else {
			$v9 = $this->input->post('is_root');
			if(!empty($v2)){
				if( $this->input->post('is_root')=="on" || $this->input->post('is_root')=="1" || $this->input->post('is_root')=="true") 
					$obj['is_root'] = 1;
				else
					$obj['is_root'] = 0;
            } else
				$obj['is_root'] = $data["user"]->is_root;
		}
		return (object)$obj;
		}

    public function _check_unique_email($str) {
        if ($str == $this->input->post('h_email')) {
            return true;
        } else {

            $count = count($this->users->get_by_email($str));

            if ($count == 0)
                return true;
            else {
                $this->form_validation->set_message('_check_unique_email', 'the email you entered already exists');
                return false;
            }
        }
		return false;
    }

    public function send_mail($password, $to) {
        $config['mailtype'] = 'html';
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $this->email->from($this->config->item('from'), $this->config->item('from_name'));
        $this->email->to($to);

        $this->email->subject($this->config->item('subject'));

        $email_message = "An account has been created for you in <a href=\"" . $this->config->item('base_url') . "\">idaciti.com</a><br/>";
        $email_message .= "You can log in using your email and this generated password : $password<br/>";
        $email_message .= "Once you are connected, don't forget to change your password<br/>";
        $email_message .= 'Welcome to <a href="' . $this->config->item('base_url') . '">Idaciti.com</a><br/>';

        $this->email->message($email_message);

        if (!$this->email->send()) {
            log_message('error', 'Email Failed. Debug: ' . $this->email->print_debugger() . " \r\n");
            return false;
        }

        return true;
    }

  public function upload_img(){
    $user = $this->user_id();
    $allowed = array('png', 'jpg', 'gif','jpeg');
    if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
      $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
      if(!in_array(strtolower($extension), $allowed)){
        echo '{"status":"error"}';
        exit;
      }
      $file_path = 'data/upload/'.$user.$_FILES['upl']['name'];
      if(move_uploaded_file($_FILES['upl']['tmp_name'],  $file_path  )) {
        echo '{"status":"success","url": "'.site_url().'/'.$file_path.'"}';
        exit;
      }
    }

    echo '{"status":"error"}';
    exit;
  }
}
