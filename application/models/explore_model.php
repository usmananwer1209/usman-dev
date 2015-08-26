<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$folder = dirname(dirname(__FILE__));
require_once $folder . "/helpers/curl.php";

class Explore_model  extends CI_Model {

    public function __construct(){
        parent::__construct();
		
        $this->load->model('api_model', 'api');
		 
    }


    public function get_companies_api($companies_id = array(), $kpis = array(), $period = array(), $sort = '', $order = "DESC"){

        $array_companies = $this->api->load_companies_api($companies_id, $kpis, $period);
        //var_dump($array_companies);
        $datasources = '';
        if (is_array($array_companies) && isset($array_companies['datasources'])) {
            $datasources = $array_companies['datasources'];
            unset($array_companies['datasources']);
        }

		if($array_companies != "API_ERROR"){
        $array_companies = @sortArrayofObjectByProperty($array_companies, $sort, $order);
        $array_companies['datasources'] = $datasources;

        return $array_companies;
        } else {
		    return "API_ERROR";
			}
        }

    public function get_companies($companies, $kpis , $period, $sort = '', $order = "DESC") {

        $_companies = $this->get_companies_api($companies, $kpis, $period, $sort, $order);

		if($_companies =="API_ERROR")
			return "API_ERROR";


        $html = '';
        $error= array();

        // efj 30-Apr-15 getting rid of datasources
        //$html .= '<ul id="source"  class="isotope2 transition" datasources ="' . $_companies["datasources"] . '">';
        $html .= '<ul id="source"  class="isotope2 transition">';
        //$html .= '<ul id="source"  class="isotope2 transition" >';
        $modal= '';
        $i = 1;

        unset($_companies['datasources']);

        foreach ($_companies as &$company) {

            //if(gettype ( $company ) == 'string'){
            //   continue;
            //}


            if (array_key_exists('error', $company) && !empty($company['error']) ){
                $error[$company['company_name']] = $company['error'];
                continue;
            }

            if(!empty($company[(int)$sort]))
                $display = format_number($company[(int)$sort]);
            else
                $display = "Not Provided";


            $css_ = 'positive';

            $num_is = 'positive';
            $html .= '<li class="cell element-item transition"';
            $html .= ' data-index="' . $i.'"';
            $html .= ' data-ticker="' . $company['entityID'].'"';
            $html .= ' data-name="' . $company['company_name'].'"';
            foreach ($kpis as $kpi) {
                if (array_key_exists($kpi, $company)){
                    if (empty($company[$kpi])) {
                        $html .= ' data-'.((int)$kpi).'="0"';
                        $html .= ' data-'.((int)$kpi).'-exist="false"';
                    } else {
                        $html .= ' data-'.((int)$kpi).'="' . $company[$kpi] .'"';
                        $html .= ' data-'.((int)$kpi).'-exist="true"';
                    }
                }

                if (in_array($kpi, $company['drilldown'], true)) {
                    $html .= ' dd-data-'.((int)$kpi).'="true"';
                }
                else {
                    $html .= ' dd-data-'.((int)$kpi).'="false"';
                }
            }

            $html .= '>' ;


            $html .=    '<div class="companyIcon">';
            $html .=    '</div>';
            $html .=    '<div class="grid_view">';
                //$html .=    '<div class="_rank">' . $i . '</div>' ;
                $html .=    '<div class="num"><span class="'.$css_.'">' . $display . '</span></div>';
            $html .= '<div class="name" title="' . (empty($company['info']) ? '' : $company['info']['company_name']) . '">';
            $html .= cut_string((empty($company['info']) ? '' : $company['info']['company_name']), 25);
                $html .=    '</div>';

            $html .= '<div class="dd_cb"><input type="checkbox" name="drilldown_cb" d_index="'.$i.'" value="'.$company['entityID'].'" />Drilldown</div>';

            $html .=    '</div>';   //end grid_view

            $html .=    '<div class="list_view">';
            $html .=    '<div class="ident">';
                //$html .=    '    <div class="num">' . $i . '</div>';
            $html .= '<div class="name" title="' . (empty($company['info']) ? '' : $company['info']['company_name']) . '">';
            $html .= cut_string((empty($company['info']) ? '' : $company['info']['company_name']), 25);
            $html .=    '</div>';
            $html .= '</div>';
            $html .=    '<ul class="scores">';
            if(!empty($kpis)){
                $tmp = 90/count($kpis);
                $width = ((string)$tmp)."%";
                }
            foreach ($kpis as $kpi) {

                if (!empty($company[$kpi])) {
                    $display_comp_kpi = format_number($company[$kpi]);
                    $comp_kpi = $company[$kpi];
                } else {
                    $display_comp_kpi = 'N/A';
                    $comp_kpi = 0;
                }
                
                

                if ((int) $comp_kpi < 0) {
                    $css_ = 'negative';
                } else {
                    $css_ = 'positive';
                }

                $html .= '<li style="width:' . $width . ';min-width: 150px;" class="' . $css_ . '">
                            <div data-' . ((int) $kpi) . '="' . $comp_kpi . '" class="num scores_active ' . $css_ . '">' .
                                $display_comp_kpi .
                            '</div>
                            <div data-' . ((int)$kpi) . '="' . $comp_kpi . '" class="progress" style="opacity:0">
                                <div class="progress-bar progress-bar-success animate-progress-bar ' . $css_ . '">
                               &nbsp;
                                </div>
                            </div>
                        </li>';
            }

            $html .=    '</ul>';
            $html .=    '</div>';

            $html .= '</li>';
            $i++;
        }
        $html .= '</ul>';
        $result = array('result'=>$html ,'error'=>$error);
        return $result;
        }

    public function get_markers($companies, $kpis , $period, $sort = '', $order = "ASC") {
    	
    	

		$order = "DESC";
        $_companies = $this->get_companies_api($companies, $kpis, $period, $sort, $order );

		

        $min_value_raduis = 0;
        $max_value_raduis = get_min_max_value($_companies,$sort , "max" );
        $min_value_opacity = $min_value_raduis;
        $max_value_opacity = $max_value_raduis;
        $v1_count = count($_companies);
        $i =1;
        $error= array();
        $tmp = '[ ';

        foreach ($_companies as $c) {

            if (gettype($c) == 'string') {
                continue;
            }

            if (array_key_exists('error', $c) && !empty($c['error']) ){
                $error[$c['company_name']] = $c['error'];
                continue;
            }

            $exist = true;
            if(!empty($c[$sort]))
                $value  =  $c[$sort];
            else
            {
                $value  =  'Not Provided';
                $exist = false;
            }

            $is_neg = false;
            if($value < 0)
                $is_neg = true;
            $value = abs($value);

            $raduis  = $this->calcul_raduis($value, $max_value_raduis, $min_value_raduis);
            $opacity = $this->calcul_opacity($value, $max_value_opacity, $min_value_opacity);
            $opacity = 1 - $opacity;
            $state = (empty($c['info']) ? '' : $c['info']['state']);
            $tmp_obj = state_city_coordinates($state,$c['company_name']);
            if(!empty($tmp_obj)){
                $tmp .= '{ ';
                $tmp .= 'latLng: '.state_city_coordinates($state,$c['company_name']).',';
                $tmp .= 'name:"'.$c['info']['company_name'].'",';
                $tmp .= 'style: {';
                if(!$exist)
                     $tmp .= 'fill: "rgba(0,0,255,'. $opacity .')",';
                else
                {
                    if($is_neg)
                        $tmp .=     'fill: "rgba(255,0,0,'. $opacity .')",';
                    else
                        $tmp .=     'fill: "rgba(179,211,26,'. $opacity .')",';
                }
                $tmp .=     'r: '.$raduis.',';
                $tmp .=     'data_index: "'.$i.'",';

                foreach ($kpis as $kpi) {
                    if (array_key_exists($kpi, $c)){
                        if(empty($c[$kpi]))
                {
                        $tmp .= ' data_'.((int)$kpi).': "0",';
                            $tmp .= ' data_'.((int)$kpi).'_exist : "false",';
                        }
                        else
                        {
                            $tmp .= ' data_'.((int)$kpi).' : "' . $c[$kpi] .'",';
                            $tmp .= ' data_'.((int)$kpi).'_exist : "true",';
                        }
                    }
                }
                $tmp .=     'data_ticker: "'.$c['info']['company_name'].'",';
                $tmp .=     'data_name: "'.$c['info']['company_name'].'"';
                $tmp .=     '}';
                $tmp .= '} ';
                if($i!=$v1_count)
                    $tmp .= ',';
            }
            $i++;
        }
        $tmp .= ']';

        $result = array('result'=>$tmp ,'error'=>$error);

		$_SESSION['companies'] =($_companies);

		

        return $result;
    }

    private function calcul_raduis($value, $max_value, $min_value) {
        $min_radius =  5;
        $max_radius =  65;
        if($max_value == $min_value)
        {
            if($max_value ==  0)
                return 3;
            else
                return $min_radius;
        }

        $r = ( ($value - $min_value)*(   ($max_radius - $min_radius)/($max_value - $min_value))  ) + $min_radius;
        $r = abs($r);

        if($value == 0)
            $r = 3;
        return $r;
        }

    private function calcul_opacity($value, $max_value, $min_value) {
        $min_opacity =  0.4;
        $max_opacity =  0.7;
        if($max_value == $min_value)
            return ($max_opacity+$min_opacity)/2;
        $o = (($value - $min_value)*($max_opacity - $min_opacity)/($max_value - $min_value)) + $min_opacity;
        $o = abs($o);
        if($value == 0)
            $o = 0;
        return $o;
    }


}

