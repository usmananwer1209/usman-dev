<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Explore extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('cards_model', 'cards');
    $this->load->model('reporting_periods_model', 'rperiods');
    $this->periods = $this->rperiods->list_records();
    }

  public function index($message = "", $is_error = false) {
        
        

      }

  public function explore_rank(){
      $this->load->model('explore_model');

      $period = array($this->input->post('period'));
      $s_companies = $this->input->post('companies');
      $s_kpis = $this->input->post('kpis');
      $type_chart =  $this->input->post('type_chart');
      $kpi = $this->input->post('kpi');
      $order = $this->input->post('order');

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
      $kpi = sprintf("%06d", $kpi);

		
		
      $resut = @$this->explore_model->get_companies($companies, $kpis, $period, $kpi, $order);
     

      if($resut == "API_ERROR"){
        $data['API_ERROR'] = "API_ERROR";
        } else {
        $ihm_companies = $resut['result'];
        $ihm_companies_error = $resut['error'];

        $resut2 = @$this->explore_model->get_markers($companies, $kpis, $period, $kpi, $order);
		
        $js_markers = $resut2['result'];
        $js_markers_error = $resut2['error'];


        $data['type_chart'] = $type_chart;
        $data['kpis'] = $kpis;
        $data['sort'] = $kpi;
        $data['sort_ascending'] = true;
        $data['get_companies'] = $ihm_companies;
        $data['get_companies_error'] = $ihm_companies_error;
        $data['period'] = $period[0];
        $data['periods'] = $this->periods;

  	    $data['get_markers'] = $js_markers; 
        $data['get_markers_error'] = $js_markers_error; 
      }
	  
      $this -> load -> view('explore/container', $data);
    }

  public function markers(){
    $this->load->model('explore_model');
	$this->load->model('card_companies_model');
	$this->load->model('card_kpis_model');

    $period = array($this->input->post('period'));

    $s_companies = $this->input->post('companies' );
    $list_companies = array();
	if(empty($s_companies)){
		$where = array(
                "card" => $this->input->post('id')
            );
        $companies = $this->card_companies_model->list_records($where);
		
		foreach ($companies as $c) {
			$list_companies[] = sprintf("%06d", $c->company);
		}
        } else {
	    $r_companies = explode(",", $s_companies);
    foreach ($r_companies as $c) {
        if(!empty($c)){
            $list_companies[] = sprintf("%06d", $c);
            }
        }
	}
    $s_kpis = $this->input->post('kpis');
    $list_kpis = array();
	if(empty($s_kpis)){
		$where = array(
                "card" => $this->input->post('id')
            );
        $kpis = $this->card_kpis_model->list_records($where);
		foreach ($kpis as $k) {
			$list_kpis[] = sprintf("%06d", $k->kpi);
		}
        } else {
	    $r_kpis = explode(",", $s_kpis);
	    foreach ($r_kpis as $k) {
	        if(!empty($k)){
	            $list_kpis[] = sprintf("%06d", $k);
            }
        }
	}
    $order = $this->input->post('order');
    $kpi = $this->input->post('kpi');
    $kpi = sprintf( "%06d", $kpi );

    $markers = $this->explore_model->get_markers($list_companies, $list_kpis, $period, $kpi, $order);
    echo $markers['result'];
    }

  public function tree(){
    $this->load->model('explore_model');

    $this->load->model('card_companies_model');
    $this->load->model('card_kpis_model');

    $s_companies = $this->input->get('companies');
    $list_companies = array();
    if(empty($s_companies)){
      $where = array(
                  "card" => $this->input->get('id')
              );
      $companies = $this->card_companies_model->list_records($where);
      foreach ($companies as $c) {
        $list_companies[] = sprintf("%06d", $c->company);
      }
        } else {
        $r_companies = explode(",", $s_companies);
    foreach ($r_companies as $c) {
      if(!empty($c)){
        $list_companies[] = sprintf("%06d", $c);
        }
      }
    }

    $s_kpis = $this->input->get('kpis');
    $r_kpis = explode(",", $s_kpis);
    $list_kpis = array();
    foreach ($r_kpis as $c) {
      if(!empty($c)){
        $list_kpis[] = sprintf("%06d", $c);
        }
      }
    $kpi = $this->input->get('kpi');
    if(empty($kpi))
      $kpi = $r_kpis[0];
    $kpi = sprintf( "%06d", $kpi );
    $period = array($this->input->get('period'));
    $_companies = $this->explore_model->get_companies_api($list_companies, $list_kpis, $period, $kpi);
    unset($_companies['datasources']);
    if($_companies == "API_ERROR"){
      $data['API_ERROR'] = "API_ERROR";
        } else {
    $sectors = array();
    $_companies = $this->remove_null_comps($_companies, $kpi);
    foreach ($_companies as $company){
      $sector_value = $company['info']['sector'];
      if(!in_array($sector_value, $sectors))
        $sectors[] = $sector_value;
    }
    $z = 1;
    $json = '{"children": [';
    $v1_count = count($sectors);
    $i =1;
    foreach ($sectors as $sector) {
      $json .= '{';
      $json .= '"name": "'.string_to_json($sector).'",';


      


      

      $v2_count = 0;
      foreach ($_companies as $company)
        if( $company['info']['sector'] == $sector)
          $v2_count++;

      $json .= '"children": [';
      $j =1;
      foreach ($_companies as $company) {
        $sector_value = $company['info']['sector'];
        $company_name = $company['info']['company_name'];
        $kpi_value = $company[$kpi];
        $kpi_value = (empty($kpi_value )?1000000:$kpi_value);

        if($sector_value == $sector){
          $json .= '{';
              $name = string_to_json($company_name);
              $json .= '"name": "'.$name.'",';
              
              

              $json .= '"data-index": "'.$z.'",';
              $z++;
              /*
              foreach ($r_kpis as $c) {
                if(!empty($c)){
                  $json .= '"data-'.$c.'": "'.$company[sprintf( "%06d", $c )].'",';
                  }
                }
              */
              foreach ($r_kpis as $c) {
                    if (array_key_exists($c, $company)){
                                if (empty($company[$c])) {
                            $json .= ' "data-'.$c.'" : "0",';
                            $json .= ' "data-'.$c.'-exist" : "false",';
                                } else {
                            $json .= ' "data-'.$c.'" : "' . $company[sprintf( "%06d", $c )] .'",';
                            $json .= ' "data-'.$c.'-exist" : "true",';
                        }
                    }
                }

              $json .= '"size": "'.$kpi_value.'"';
              $json .= '}';
              if($j!=$v2_count)
                  $json .= ','; 
              $j++;
          }
      }
      $json .= ']';
      $json .= '}';
      if($i!=$v1_count)
        $json .= ',';
      $i++;
    }


      $json .= ']';
      $json .= '}';
    $data['json'] = $json;
      }
    $this->load->view('commun/json_view', $data);
    }

    public function remove_null_comps($_companies, $sort) {
      $new_comp = array();
      foreach ($_companies as $key => $comp) {
        if(!empty($comp[$sort]) && $comp[$sort] > 0)
          $new_comp[] = $comp;
      }
      return $new_comp;
    }

  }

