<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Autocomplete extends CI_Controller {

	public function __construct(){
    	parent::__construct();
		$this->load->model('users_model', 'users');
		$this->load->model('circles_model', 'circles');
		$this->load->model('user_circles_model', 'user_circles');
		$this->header_data = array();
		$this->page_data = array();
        }

	public function companies(){
		$data = $this->input->post('data');
	  	@$this->load->model('companies_model','companies');
		$like = array(
					"LOWER(company_name)"=>strtolower($data),
                                        "LOWER(stock_symbol)"=>strtolower($data),
					//"CAP_FIRST(company_name)"=>strtolower($data),
				);
		$objs = @$this->companies->list_records(null,$like,50);
		print json_encode($objs);
	}

	public function kpis(){
		$data = $this->input->post('data');
	  	@$this->load->model('kpis_model','kpis');
		$like = array(
					"LOWER(name)"=>strtolower($data),
            //"CAP_FIRST(name)" => strtolower($data),
				);
		$objs = @$this->kpis->list_records(null,$like,50);
		print json_encode($objs);
	}

	}

