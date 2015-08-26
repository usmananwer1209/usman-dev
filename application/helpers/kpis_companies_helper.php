<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('get_kpi_name')){
  function get_kpi_name($id){
		$CI = get_instance();
		$CI->load->model('kpis_model');
		$obj = $CI->kpis_model->get_by_id($id);
		$name = $obj->name;
		return $name;
  }
  function get_kpi($id){
    $CI = get_instance();
    $CI->load->model('kpis_model');
    $obj = $CI->kpis_model->get_by_id($id);
    return $obj;
  }
}


