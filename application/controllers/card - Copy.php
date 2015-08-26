<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once "abstract_controller.php";

class Card extends abstract_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cards_model', 'cards');
        $this->load->model('reporting_periods_model', 'rperiods');
        $this->periods = $this->rperiods->list_records();
        //var_dump($this->periods);
    }

    public function index($message = "", $is_error = false) {
        $user = $this->session->userdata('user');
        if (!empty($user)) {
            $this->my_cards($message, $is_error);
        } else
            redirect('login');
    }

    public function edit($id="", $message = "") {

        $this->load->model('explore_model');
        $current = '/card/edit/' . $id;
        $data = $this->security($current);
        $this_card = $this->cards->get_by_id($id);
        if ($data && !empty($data) &!empty($this_card) && $this_card->user == $this->user_id() ) {
			
            $data['title'] = 'Edit Card';
            if ($message != "")
                $data['message'] = $message;
            if ($message == "succes")
                $data['message'] = "Your Changes have been Successfully Submitted";
            
            $data['type_chart'] = $this_card->type;
            $data['obj'] = $this_card;
            $data['op'] = "edit_";

            //TODO add list_companies to Card
            $list_companies = $this->cards->get_companies($id);
            $data['list_companies'] = $list_companies;
            $list_kpis = $this->cards->get_kpis($id);
            $data['list_kpis'] = $list_kpis;

            $companies = array();
            foreach ($list_companies as $c) {
                $companies[] = sprintf("%06d", $c->entity_id);
            }
            $kpis = array();
            foreach ($list_kpis as $c) {
                $kpis[] = sprintf("%06d", $c->term_id);
            }
            if(!empty($kpis[0]))
                $sort = (!empty($this_card->kpi)) ? $this_card->kpi : $kpis[0];
            $order = strtoupper((!empty($this_card->order)) ? $this_card->order : "DESC");
            $sort_ascending = ($order == "ASC") ? true : false;
            $period = (!empty($this_card->period)) ? array($this_card->period) : array("2013Q1");
            if(empty($sort))
                $sort = '';

            $data['kpis'] = $kpis;
            $data['sort'] = $sort;
            $data['sort_ascending'] = $sort_ascending;
            $data['period'] = $period;
            $data['periods'] = $this->periods;

            $resut = $this->explore_model->get_companies($companies, $kpis, $period, $sort, $order);
            if($resut == "API_ERROR"){
                $data['API_ERROR'] = "API_ERROR";
            } else {
              $data['get_companies'] = $resut['result'];
              $data['get_companies_error'] = $resut['error'];
              $resut2 = $this->explore_model->get_markers($companies, $kpis, $period, $sort);
              $data['get_markers'] = $resut2['result'];
              $data['get_markers_error'] = $resut2['error'];
            }

              $this->load->view('general/header', $data);
              $this->load->view('card/card', $data);
              $this->load->view('general/footer');
        }
        else
            redirect('authorization');
    }

    public function add($message = "", $is_error = false) {
        $this->load->model('explore_model');
        $current = '/card/add/';
        $data = $this->security($current);
        $data['periods'] = $this->periods;
        if ($data && !empty($data)) {
            $data['title'] = 'Add New Card';
            if ($message != "")
                $data['message'] = $message;
            $data['op'] = "add_";

            $this->load->view('general/header', $data);
            $this->load->view('card/card', $data);
            $this->load->view('general/footer');
        }
    }

    public function view($id="", $message = "") {
        $this->load->model('explore_model');
        $current = '/card/view/' . $id;
		$data = $this->security($current);
        $this_card = $this->cards->get_by_id($id);
        $data['periods'] = $this->periods;
		if (!empty($data) && !empty($this_card) && count($this->cards->cards_shared_with_user($this->user_id()))>0) {
            if ($message != "")
                $data['message'] = $message;
            $data['title'] = $this_card->name;
            $data['obj'] = $this_card;
            $data['op'] = "view_";

            $list_companies = $this->cards->get_companies($id);
            $data['list_companies'] = $list_companies;
            $list_kpis = $this->cards->get_kpis($id);
            $data['list_kpis'] = $list_kpis;

            $companies = array();
            foreach ($list_companies as $c) {
                $companies[] = sprintf("%06d", $c->entity_id);
            }
            $kpis = array();
            foreach ($list_kpis as $c) {
                $kpis[] = sprintf("%06d", $c->term_id);
            }
            if(empty($kpis[0]))
                $kpis[0] = '';
            $sort = (!empty($this_card->kpi)) ? $this_card->kpi : $kpis[0];
            $order = strtoupper((!empty($this_card->order)) ? $this_card->order : "DESC");
            $sort_ascending = ($order == "ASC") ? true : false;
            $period = (!empty($this_card->period)) ? array($this_card->period) : array("2013");

            $data['kpis'] = $kpis;
            $data['sort'] = $sort;
            $data['sort_ascending'] = $sort_ascending;
            $data['period'] = $period;

            $resut = $this->explore_model->get_companies($companies, $kpis, $period, $sort, $order);
            if($resut == "API_ERROR"){
                $data['API_ERROR'] = "API_ERROR";
            } else {
                $data['get_companies'] = $resut['result'];
                $data['get_companies_error'] = $resut['error'];
                $resut2 = $this->explore_model->get_markers($companies, $kpis, $period, $sort);
                $data['get_markers'] = $resut2['result'];
                $data['get_markers_error'] = $resut2['error'];
                $card_author = $this->users->get_by_id($data['obj']->user);
                $data['obj']->author = $card_author->first_name." ".$card_author->last_name;
                if ($this_card->user != $this->user_id()) {
                    $this->load->model('view_cards_model','view_cards');
                    $viewed = array("card"=>$id, "user"=>$this->user_id());
                    $this->view_cards->save($viewed);
                }
            }
            $data['view'] = true;
            $this->load->view('general/header', $data);
            $this->load->view('card/view', $data);
            $this->load->view('general/footer');
        }else{
			 redirect('authorization');
		}
    }

    public function embed($guid="") {

        $pass = false;
        if(strlen($guid) > 64)
        {
            $pass = substr($guid, -32);
            $guid2 = $guid;
            $guid = str_replace($pass, '', $guid);
            if(strcmp($pass, md5('idaciti')) == 0)
                $pass = true;
            else
                $pass = false;
        }

        $this->load->model('explore_model');
        $id = guid_to_id($guid2);

        $current = '/card/view/' . $id;
        $data['is_embed'] = true;
        if($id)
            $this_card = $this->cards->get_by_id($id);
        $data['periods'] = $this->periods;
        if (!empty($this_card) && ($this_card->public == 1 || $pass)) {
            $data['title'] = 'Idaciti - '.$this_card->name;
            $data['obj'] = $this_card;
            $data['op'] = "view_";
            $data['current'] = $current;
            $data['is_internal'] = $pass;

            $list_companies = $this->cards->get_companies($id);
            $data['list_companies'] = $list_companies;
            $list_kpis = $this->cards->get_kpis($id);
            $data['list_kpis'] = $list_kpis;

            $companies = array();
            foreach ($list_companies as $c) {
                $companies[] = sprintf("%06d", $c->entity_id);
            }
            $kpis = array();
            foreach ($list_kpis as $c) {
                $kpis[] = sprintf("%06d", $c->term_id);
            }
            if(empty($kpis[0]))
                $kpis[0] = '';
            $sort = (!empty($this_card->kpi)) ? $this_card->kpi : $kpis[0];
            $order = strtoupper((!empty($this_card->order)) ? $this_card->order : "DESC");
            $sort_ascending = ($order == "ASC") ? true : false;
            $period = (!empty($this_card->period)) ? array($this_card->period) : array("2013");

            $data['kpis'] = $kpis;
            $data['sort'] = $sort;
            $data['sort_ascending'] = $sort_ascending;
            $data['period'] = $period;

            $resut = $this->explore_model->get_companies($companies, $kpis, $period, $sort, $order);
            if($resut == "API_ERROR"){
                $data['API_ERROR'] = "API_ERROR";
            } else {
                $data['get_companies'] = $resut['result'];
                $data['get_companies_error'] = $resut['error'];
                $resut2 = $this->explore_model->get_markers($companies, $kpis, $period, $sort);
                $data['get_markers'] = $resut2['result'];
                $data['get_markers_error'] = $resut2['error'];
                $card_author = $this->users->get_by_id($data['obj']->user);
                $data['obj']->author = $card_author->first_name." ".$card_author->last_name;
                if ($this_card->user != $this->user_id()) {
                    $this->load->model('view_cards_model','view_cards');
                    $viewed = array("card"=>$id, "user"=>$this->user_id());
                    
                    
                    $this->view_cards->save($viewed);
                }
            }
            $data['view'] = true;
            $this->load->view('general/header', $data);
            $this->load->view('card/embed', $data);
            $this->load->view('general/footer');
        }
    }

    public function update_data() {
        $this->load->model('explore_model');
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this_card = $this->cards->get_by_id($id);
            $data['title'] = $this_card->name;
            $data['obj'] = $this_card;
            $data['op'] = "view_";
            $data['periods'] = $this->periods;

            $list_companies = $this->cards->get_companies($id);
            $data['list_companies'] = $list_companies;
            $list_kpis = $this->cards->get_kpis($id);
            $data['list_kpis'] = $list_kpis;

            $companies = array();
            foreach ($list_companies as $c) {
                $companies[] = sprintf("%06d", $c->entity_id);
            }
            $kpis = array();
            foreach ($list_kpis as $c) {
                $kpis[] = sprintf("%06d", $c->term_id);
            }
            $sort = $this->input->post('kpi');
            $order = strtoupper($this->input->post('order'));
            $sort_ascending = ($order == "ASC") ? true : false;
            $period = array($this->input->post('period'));

            $data['kpis'] = $kpis;
            $data['sort'] = $sort;
            $data['sort_ascending'] = $sort_ascending;
            $data['period'] = $period;
            $data['type_chart'] = $this->input->post('type_chart');


            $resut = $this->explore_model->get_companies($companies, $kpis, $period, $sort, $order);
            
            if($resut == "API_ERROR"){
                $data['API_ERROR'] = "API_ERROR";
            } else {
                $data['get_companies'] = $resut['result'];
                $data['get_companies_error'] = $resut['error'];
                //$resut2 = $this->explore_model->get_markers($companies, $kpis, $period, $sort);
                //$data['get_markers'] = $resut2['result'];
                //$data['get_markers_error'] = $resut2['error'];
            }

            $data['period'] = $period[0];
            $data['view'] = true;

            $this->load->view('explore/container.php', $data);
        }
    }

    public function data_points() {
        $this->load->model('explore_model');
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this_card = $this->cards->get_by_id($id);
            $data['title'] = $this_card->name;
            $data['obj'] = $this_card;
            $data['op'] = "view_";
            $data['periods'] = $this->periods;
            $data['type'] = $this_card->type;

            if($this_card->type == 'rank' || $this_card->type == 'explore' || $this_card->type == 'map' || $this_card->type == 'tree') {
              $list_companies = $this->cards->get_companies($id);
              $data['list_companies'] = $list_companies;
              $list_kpis = $this->cards->get_kpis($id);
              $data['list_kpis'] = $list_kpis;

              $companies = array();
              foreach ($list_companies as $c) {
                  $companies[] = sprintf("%06d", $c->entity_id);
              }
              $kpis = array();
              foreach ($list_kpis as $c) {
                  $kpis[] = sprintf("%06d", $c->term_id);
              }
              $sort = $this->input->post('kpi');
              $order = strtoupper($this->input->post('order'));
              $sort_ascending = ($order == "ASC") ? true : false;
              $period = array($this->input->post('period'));

              //$period = array('2011');

              $data['kpis'] = $kpis;
              $data['sort'] = $sort;
              $data['sort_ascending'] = $sort_ascending;
              $data['period'] = $period;
              $data['type_chart'] = $this->input->post('type_chart');
              $_companies = $this->explore_model->get_companies_api($companies, $kpis, $period, $sort, $order);

              $data['_companies'] = $_companies;
            }
			
            if($this_card->type == 'line' || $this_card->type == 'column' || $this_card->type == 'area') {

            }

            $this->load->view('card/data_points.php', $data);
        }
    }

    public function get_data_json_by_post() {

        $period = array($this->input->get('period'));
        $s_companies = $this->input->get('companies');
        $s_kpis = $this->input->get('kpis');
        $id = $this->input->get('id');

        if(empty($s_companies) && !empty($id))
            $this->get_data_json_by_id($id);
        else{
            $r_companies = explode(",", $s_companies);
            $companies = array();
            foreach ($r_companies as $c) {
                if(!empty($c)){
                  $companies[] = sprintf("%06d", $c);
                }
            }
          
            $r_kpis = explode(",", $s_kpis);
            $kpis = array();
            foreach ($r_kpis as $c) {
                if(!empty($c))
                $kpis[] = sprintf("%06d", $c);
            }

            $this->get_data_json($companies, $kpis, $period);
        }
    }

    public function get_symbols_json_by_post() {
        $result = array();
        $s_companies = $this->input->get('companies');
        $id = $this->input->get('id');

        if(empty($s_companies) && !empty($id)) {
            $list_companies = $this->cards->get_companies($id);

            foreach ($list_companies as $c) {
                $comp = array();
                $comp[0] = str_replace(',', ' ', $c->company_name);
                $comp[0] = str_replace('  ', ' ', $comp[0]);
                $comp[1] = strtoupper($c->stock_symbol);
                $result[] = $comp;
            }
        }
        else{
            $this->load->model('companies_model','companies');
            $r_companies = explode(",", $s_companies);
            $ids = array();
            foreach ($r_companies as $c) {
                if(!empty($c)){
                  $ids[] = $this->companies->get_by_id($c);
                }
            }
            //$companies = $this->companies->get_companies_by_ids($ids);
            foreach ($ids as $c) {
                $comp = array();
                $comp[0] = str_replace(',', ' ', $c->company_name);
                $comp[0] = str_replace('  ', ' ', $comp[0]);
                $comp[1] = strtoupper($c->stock_symbol);
                $result[] = $comp;   
            }
        }
        //var_dump($result);
        echo json_encode($result);
    }

    public function get_data_json_all_periods($segment = 'year'){
        $s_companies = $this->input->get('companies');
        $s_kpis = $this->input->get('kpis');
        $id = $this->input->get('id');

        if(empty($s_companies) && !empty($id))
            $this->get_data_json_by_id_all_periods($id, $segment);
        else{
            $r_companies = explode(",", $s_companies);
            $companies = array();
            foreach ($r_companies as $c) {
                if(!empty($c)){
                  $companies[] = sprintf("%06d", $c);
                }
            }
          
            $r_kpis = explode(",", $s_kpis);
            $kpis = array();
            foreach ($r_kpis as $c) {
                if(!empty($c))
                $kpis[] = sprintf("%06d", $c);
            }

            $this->get_data_json($companies, $kpis, '', $segment);
		
        }
	
    }

    public function get_data_json_all_periods_combo($segment = 'year'){
        $s_companies = $this->input->get('companies');
        $s_kpis = $this->input->get('kpis');
        $id = $this->input->get('id');
        $active_company = $this->input->get('active_company');
		$chat_type = $this->input->get('type_chart');

        //we prepare the list of companies and the list of data for active_company


        if(empty($s_companies) && !empty($id)){
            $this_card = $this->cards->get_by_id($id);

            $list_companies = $this->cards->get_companies($id);
            $list_kpis = $this->cards->get_kpis($id);

            $companies = array();
            foreach ($list_companies as $c) {
                $companies[] = sprintf("%06d", $c->entity_id);
            }
        }

        else{
            $r_companies = explode(",", $s_companies);
            $companies = array();
            foreach ($r_companies as $c) {
                if(!empty($c)){
                  $companies[] = sprintf("%06d", $c);
        	}
    	}
        }
        $this->load->model('companies_model', 'companies');
        $list_comps = array();
        foreach ($companies as $k => $v) {
            $list_comps[] = $this->companies->get_by_id($v);
        }
        $list_comps = $this->companies->get_companies_by_ids($companies);

        $result['companies_list'] 		= $list_comps;
        $result['active_company'] 		= $active_company;
        $result['details'] 				= $this->cards->get_carddetails($id,$chat_type,'');
		if($_SESSION['pagename']=='show')
		{
		$result['preveiw_title'] = '';
		$result['preview_description'] = '';
		}
		else
		{
		$result['preveiw_title'] = $this->session->userdata('preveiw_title');
		$result['preview_description'] = $this->session->userdata('preview_description');

		}
        $r_kpis = explode(",", $s_kpis);
        $kpis = array();
        foreach ($r_kpis as $c) {
            if(!empty($c))
            $kpis[] = sprintf("%06d", $c);
        }

        $this->load->model('api_model', 'api');
        $result['data'] = $this->api->get_company_kpis_values($active_company, $kpis, $segment);
        echo json_encode($result);

        //$this->get_data_json($companies, $kpis, '', $segment);
    }

    public function get_all_periods(){
      $periods = array();
      foreach ($this->periods as $k => $p) {
        $periods[] = $p->reporting_period;
      }
      echo json_encode($periods);
    }

    public function get_data_json_by_id($id) {

        $this_card = $this->cards->get_by_id($id);

        $list_companies = $this->cards->get_companies($id);
        $list_kpis = $this->cards->get_kpis($id);

        $companies = array();
        foreach ($list_companies as $c) {
            $companies[] = sprintf("%06d", $c->entity_id);
        }
        $kpis = array();
        foreach ($list_kpis as $c) {
            $kpis[] = sprintf("%06d", $c->term_id);
        }
        $sort = (!empty($this_card->kpi)) ? $this_card->kpi : $kpis[0];
        $order = strtoupper((!empty($this_card->order)) ? $this_card->order : "DESC");
        $sort_ascending = ($order == "ASC") ? true : false;
        $period = array($this->input->get('period'));  
        if(empty($period))
            $period = (!empty($this_card->period)) ? array($this_card->period) : array("2013");

        $this->get_data_json($companies, $kpis, $period);
    }

    public function get_data_json_by_id_all_periods($id, $segment = 'year') {
        $this_card = $this->cards->get_by_id($id);

        $list_companies = $this->cards->get_companies($id);
        $list_kpis = $this->cards->get_kpis($id);

        $companies = array();
        foreach ($list_companies as $c) {
            $companies[] = sprintf("%06d", $c->entity_id);
        }
        $kpis = array();
        foreach ($list_kpis as $c) {
            $kpis[] = sprintf("%06d", $c->term_id);
        }
        $sort = (!empty($this_card->kpi)) ? $this_card->kpi : $kpis[0];
        $order = strtoupper((!empty($this_card->order)) ? $this_card->order : "DESC");
        $sort_ascending = ($order == "ASC") ? true : false;

        $this->get_data_json($companies, $kpis, '', $segment);
    }

    public function get_data_json($companies, $kpis, $period = '', $segment = 'year') {
        if(!empty($companies) && !empty($kpis))
        {
            $this->load->model('api_model', 'api');
            if(!empty($period))
                $result = $this->api->get_companies_kpis_values($companies, $kpis, $period);
            else
                $result = $this->api->get_companies_kpis_values_all_periods($companies, $kpis, $segment);

            /*echo '<pre>';
            echo print_r(json_encode($result), true);
            echo '</pre>';*/
            echo json_encode($result);
        }
        else
        {
            echo '[]';
        }
    }
	 public function get_data_simple($companies, $kpis, $period = '', $segment = 'year') {
        if(!empty($companies) && !empty($kpis))
        {
            $this->load->model('api_model', 'api');
            if(!empty($period))
                $result = $this->api->get_companies_kpis_values($companies, $kpis, $period);
            else
                $result = $this->api->get_companies_kpis_values_all_periods($companies, $kpis, $segment);

            /*echo '<pre>';
            echo print_r(json_encode($result), true);
            echo '</pre>';*/
			return $result;
        }
        else
        {
            echo '[]';
        }
    }

    public function delete($card_id){
        try {
            //is card used in a storyboard?
            $this->load->model('storyboard_model', 'storyboard');
            $n =  $this->storyboard->card_used_in_storyboards($card_id);
            if($n == 0)
                $this->cards->delete($card_id);
            echo "ok";
            echo $n;
        } catch (Exception $e) {
            echo "ko";
        }
    }

    public function publish($card_id, $public) {
        $card = $this->cards->get_by_id($card_id);
        $card->public = $public;
        try {
            @$this->cards->simple_save($card);
            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }

    public function share($card_id) {
        $card = $this->cards->get_by_id($card_id);
        $s_circles = $this->input->post('circles');
        $circles = explode(",", $s_circles);

        try {
            @$this->cards->share($card, $circles);
            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }

    public function group_by_decision_category() {
        $this->load->model('kpis_model', 'kpis');
        $decision_cats = $this->kpis->get_decision_cats();
        foreach ($decision_cats as $k => $d_c) {
            $decision_cats[$k]->kpis = $this->kpis->get_kpis_by_decision_cat($d_c->decision_category);
        }
        $data['decision_cats'] = $decision_cats;
        $this->load->view('card/decision_category_tree', $data);
    }

    public function group_by_financial_category() {
        $this->load->model('kpis_model', 'kpis');
        $financial_cats = $this->kpis->get_financial_cats();
        foreach ($financial_cats as $k => $d_c) {
            $financial_cats[$k]->kpis = $this->kpis->get_kpis_by_financial_cat($d_c->financial_category);
        }
        $data['financial_cats'] = $financial_cats;
        $this->load->view('card/financial_category_tree', $data);
    }

    public function my_cards($message = "", $is_error = false) {
        $this->load->model('users_model', 'users');
        $this->load->model('view_cards_model', 'view_cards');
        $current = '/card/my_cards/';
        $data = $this->security($current);
        if ($data && !empty($data)) {
            $data['title'] = 'My Cards';
            $data['active_search'] = true;
            if ($message != "")
                $data['message'] = $message;

            $where = array(
                "user" => $this->user_id()
            );
			$like = array();
			$order_by = array('creation_time','DESC');
            $objs = $this->cards->list_records($where,$like,24,0,$order_by);
            $data['objs'] = $objs;
			
            // adding to author of the card's name based on the id
            foreach ($objs as $obj) {
                $card_author = $this->users->get_by_id($obj->user);
                $obj->author = $card_author->first_name." ".$card_author->last_name;

                $obj->viewed = count($this->view_cards->list_records(array("card" => $obj->id)));
				
            }

            $this->load->view('general/header', $data);
            $this->load->view('card/my_cards', $data);
            $this->load->view('general/footer');
        }
    }
	public function load_more_my_cards() {
			$this->load->model('users_model', 'users');
        	$this->load->model('view_cards_model', 'view_cards');
			$current = '/card/my_cards/';
       		$data = $this->security($current);
			$start = $this->input->post('start');
			$limit = $this->input->post('limit');
			$data['type'] = $this->input->post('type');
			$sort_by = $this->input->post('sort_by');
			$sort_order = $this->input->post('sort_order');
			$where = array(
                "user" => $this->user_id()
            );
			$like = array();
			$order_by = array($sort_by, $sort_order);
            $objs = $this->cards->list_records($where,$like,$limit,$start,$order_by);
            $data['objs'] = $objs;
            // adding to author of the card's name based on the id
			if(count($objs)>0){
            foreach ($objs as $obj) {
                $card_author = $this->users->get_by_id($obj->user);
                $obj->author = $card_author->first_name." ".$card_author->last_name;
                $obj->viewed = count($this->view_cards->list_records(array("card" => $obj->id)));
            }
			$this->load->view('card/load_more_my_cards', $data);
			}else{
				echo 'ko';
			}
    }
    public function all($circle_id = "") {
        $current = '/card/all/';
        $data = $this->security($current);
        if ($data && !empty($data)) {
            $this->load->model('circles_model', 'circles');
            $this->load->model('users_model', 'users');
            $this->load->model('view_cards_model', 'view_cards');

            $data['active_search'] = true;

            if(!empty($circle_id))
                $cards = $this->cards->cards_shared_with_user($this->user_id(),$circle_id,24);
            else
                $cards = $this->cards->cards_shared_with_user($this->user_id(),false,24);
            $cards = (array) $cards;
            foreach ($cards as &$card) {
                $card = (array)$card;
                $circles = $this->circles->circles_of_cards($card['id']);
                if(!empty($circles) && !empty($card)){
                    $card['circles'] = $circles;
                }

                $card['viewed'] = count($this->view_cards->list_records(array("card" => $card['id'])));
            }
            $data['cards'] = $cards;
            if($circle_id!="")
                $data['circle'] = $this->circles->get_by_id($circle_id);

            $this->load->view('general/header', $data);
            $this->load->view('card/all', $data);
            $this->load->view('general/footer');
        }
    }
	 public function load_more_circle_cards($circle_id = "") {
        $current = '/card/all/';
        $data = $this->security($current);
            $this->load->model('circles_model', 'circles');
            $this->load->model('users_model', 'users');
            $this->load->model('view_cards_model', 'view_cards');

            $data['active_search'] = true;

            if(!empty($circle_id))
                $cards = $this->cards->cards_shared_with_user($this->user_id(),$circle_id );
            else
                $cards = $this->cards->cards_shared_with_user($this->user_id());
			if(count($cards)>0){
            $cards = (array) $cards;
            foreach ($cards as &$card) {
                $card = (array)$card;
                $circles = $this->circles->circles_of_cards($card['id']);
                if(!empty($circles) && !empty($card)){
                    $card['circles'] = $circles;
                }

                $card['viewed'] = count($this->view_cards->list_records(array("card" => $card['id'])));
            }
            $data['cards'] = $cards;
            if($circle_id!="")
                $data['circle'] = $this->circles->get_by_id($circle_id);
				
            $this->load->view('card/load_more_circle_cards', $data);
			}else{
				echo 'ko';
			}
    }
    public function submit() {
        $current = '/card/save/';
        $data = $this->security($current);
        if (!empty($data)) {
            //$this->validation();
            $obj = $this->prepare();
            //$this->cards->save($obj);
            echo $this->cards->save($obj);
        }else{
           echo "ko";
        }
    }

    public function company_tree() {
        $this->load->model('companies_model', 'companies');
        $result = $this->companies->get_sector();
		
        $json = '{"name": "Companies","children": [';
        $v1_count = count($result);
        $i = 1;
        
        foreach ($result as $row) {
            $json .= '{';
            $json .= '"name": "' . string_to_json($row->sector) . '",';
            $json .= '"children": [';
            $result_industry = $this->companies->get_industry_and_count($row->sector);
            $v2_count = count($result_industry);
            $j = 1;
            foreach ($result_industry as $row2) {
                $json .= '{';
                $name = $row2->industry . '\n # Companies: ' . $row2->count;
                $json .= '"name": "' . $name . '",';
                $json .= '"id": "' . $row2->industry . '",';
                $json .= '"size": "' . $row2->count . '"';
                $json .= '}';
                if ($j != $v2_count)
                    $json .= ',';
                $j++;
            }
            $json .= ']';
            $json .= '}';
            if ($i != $v1_count)
                $json .= ',';
            $i++;
        }
        $json .= ']';
        $json .= '}';
        $data['json'] = $json;
        
        $this->load->view('commun/json_view', $data);
    }

    public function company_industry($industry) {
        $industry = urldecode($industry);
        $this->load->model('companies_model', 'companies');
        $sics = $this->companies->get_sics_and_count($industry);
        foreach ($sics as $i => $sic) {
            $sics[$i]->companies = $this->companies->get_companies_by_sic_code($sic->sic_code);
        }
        $data['sics'] = $sics;
        $this->load->view('card/companies_tree_ul', $data);
    }

    public function get_list_companies() {
        $this->load->model('list_companies_model', 'list_companies');
        $objs = $this->list_companies->list_records("`user` = ".$this->user_id()." OR `public` = 1 ", array(), 0, 0, array('name', 'DESC'));
        //var_dump($objs);
        print json_encode($objs);
    }

    public function get_list_kpis() {
        $this->load->model('list_kpis_model', 'list_kpis');
        $objs = $this->list_kpis->list_records("`user` = ".$this->user_id()." OR `public` = 1 ", array(), 0, 0, array('name', 'DESC'));
        //var_dump($objs);

        print json_encode($objs);
    }

    public function save_list_companies($id="") {
        try {
            $this->load->model('list_companies_model', 'list_companies');
            $obj = array();
            $name = $this->input->post('name');
            $objs = $this->input->post('objs');
            $public = $this->input->post('public');
            $obj['user'] = $this->user_id();
            if ($id != "")
                $obj['id'] = $id;
            if (!empty($name))
                $obj['name'] = $name;
            if (!empty($objs))
                $obj['companies'] = $objs;
            if (!empty($public))
                $obj['public'] = $public;
            if(!empty($name) && !empty($obj['companies'])) {
              $obj = $this->list_companies->save($obj);
              echo $obj->id;
            }
            else
              echo "ko";
        } catch (Exception $e) {
            echo "ko";
        }
    }

    public function save_list_kpis($id="") {
        try {
            $this->load->model('list_kpis_model', 'list_kpis');
            $obj = array();
            $name = $this->input->post('name');
            $objs = $this->input->post('objs');
            $public = $this->input->post('public');
            $obj['user'] = $this->user_id();
            if ($id != "")
                $obj['id'] = $id;
            if (!empty($name))
                $obj['name'] = $name;
            if (!empty($objs) && strlen($objs) > 2)
                $obj['kpis'] = $objs;
            if (!empty($public))
                $obj['public'] = $public;
            if(!empty($name) && !empty($obj['kpis'])) {
              $obj = $this->list_kpis->save($obj);
              echo $obj->id;
            }
            else
              echo "ko";
        } catch (Exception $e) {
            echo "ko";
        }
    }

    public function delete_list_companies($id="") {
        try {
            $this->load->model('list_companies_model', 'list_companies');
            $this->list_companies->delete(array("id"=>$id));
            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }

    public function delete_list_kpis($id="") {
        try {
            $this->load->model('list_kpis_model', 'list_kpis');
            $this->list_kpis->delete(array("id"=>$id));
            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }
 
    public function get_companies_list($list_id) {
        $this->load->model('list_companies_model', 'list_companies');
        $this->load->model('companies_model', 'companies');
        $obj = (object) $this->list_companies->get_by_id($list_id);
        $obj->companies = str_replace("\"", "", $obj->companies);
        $id_companies = explode(",", $obj->companies);

        $id_companies = array_reverse($id_companies);

        $companies = array();
        foreach ($id_companies as $id) {
            if ($id != '') {
                $c = (object) $this->companies->get_by_id($id);
                if (!empty($c)) {
                    $companies[] = $c;
                }
            }
        }
        print json_encode($companies);
    }

    public function get_kpis_list($list_id) {
        $this->load->model('list_kpis_model', 'list_kpis');
        $this->load->model('kpis_model', 'kpis');
        $obj = (object) $this->list_kpis->get_by_id($list_id);

        $obj->kpis = str_replace("\"", "", $obj->kpis);
        $id_kpis = explode(",", $obj->kpis);

        $id_kpis = array_reverse($id_kpis);

        $kpis = array();
        foreach ($id_kpis as $id) {
            if ($id != '') {
            $c = (object) $this->kpis->get_by_id($id);
            if (!empty($c)) {
                $kpis[] = $c;
            }
        }
        }
        
        print json_encode($kpis);
    }

    public function security($current) {
        $data = parent::security($current);
        if ($data && !empty($data)) {
            $this->load->model('companies_model', 'companies');
            $this->load->model('kpis_model', 'kpis');
            $sectors = $this->companies->get_sector();
            $decision_cats = $this->kpis->get_decision_cats();
            foreach ($decision_cats as $k => $d_c) {
                $decision_cats[$k]->kpis = $this->kpis->get_kpis_by_decision_cat($d_c->decision_category);
            }
            $data['desicion_cats'] = $decision_cats;
        }
        return $data;
    }

    private function validation() {
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
    }

    private function prepare() {
        $obj = array();

        $v0 = $this->input->post('id');
        if (!empty($v0)) {
            $obj['id'] = $this->input->post('id');
            $obj['modification_time'] = 'now';
        }
        else {
            $obj['creation_time'] = 'now';
        }

        $v01 = $this->input->post('name');
        if (!empty($v01))
            $obj['name'] = $this->input->post('name');

        $v02 = $this->input->post('sources');
        if (!empty($v02))
            $obj['sources'] = $this->input->post('sources');

        $v03 = $this->input->post('data_points');
        if (!empty($v03))
            $obj['data_points'] = $this->input->post('data_points');

        $v04 = $this->input->post('author');
        if (!empty($v04))
            $obj['author'] = $this->input->post('author');

        $v1 = $this->input->post('description');
        if (!empty($v1))
            $obj['description'] = $this->input->post('description');

        $v2 = $this->input->post('type');
        if (!empty($v2))
            $obj['type'] = $this->input->post('type');

        $tog = $this->input->post('quarterly_toggle');
        if (!empty($tog))
            $obj['quarterly_toggle'] = $this->input->post('quarterly_toggle');
        $ac = $this->input->post('active_company');
        if (!empty($ac))
            $obj['active_company'] = $this->input->post('active_company');

        $obj['line_kpis'] = $this->input->post('line_kpis');
        $obj['column_kpis'] = $this->input->post('column_kpis');

        $v3 = $this->input->post('companies');
        if (!empty($v3)) {
            $s_companies = $this->input->post('companies');
            $r_companies = explode(",", $s_companies);
            $companies = array();
            foreach ($r_companies as $c) {
                if (!empty($c)) {
                    $companies[] = sprintf("%06d", $c);
                }
            }
            $obj['companies'] = $companies;
        }


        $v4 = $this->input->post('kpis');
        if (!empty($v4)) {
            $s_kpis = $this->input->post('kpis');
            $r_kpis = explode(",", $s_kpis);
            $kpis = array();
            foreach ($r_kpis as $c) {
                if (!empty($c))
                    $kpis[] = sprintf("%06d", $c);
            }
            $obj['kpis'] = $kpis;
        }
        else
            $obj['kpis'] = array();

        $user = $this->session->userdata('user');
        $obj['user'] = $user->id;

        $v5 = $this->input->post('period');
        if (!empty($v5))
            $obj['period'] = $this->input->post('period');

        $v6 = $this->input->post('kpi');
        if (!empty($v6))
            $obj['kpi'] = $this->input->post('kpi');

        $v7 = $this->input->post('order');
        if (!empty($v7))
            $obj['order'] = $this->input->post('order');

        return (object) $obj;
    }

    function getcarddetails() {

        $result[] = array();
        if($_SESSION['pagename']=='show')
            {
            $result['preveiw_title'] = '';
            $result['preview_description'] = '';
            }
            else
            {
            $result['preveiw_title'] = $this->session->userdata('preveiw_title');
            $result['preview_description'] = $this->session->userdata('preview_description');

            }
        $chat_type = $this->input->get('chat_type');
        $id = $this->input->get('id');
        $sid = $this->input->get('sid');
        $result['details'] = $this->cards->get_carddetails($id,$chat_type,$sid);
         echo json_encode($result);

    }

    public function get_dimension_drilldown() {

        $post_data = $this->input->post();

        $this->load->model('dimensions_model');

        echo json_encode($this->dimensions_model->get_drilldown($post_data['entityId'], $post_data['termId'], $post_data['year'], 0, $post_data['fiscal_type']));
    }
}
