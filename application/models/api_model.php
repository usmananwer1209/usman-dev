<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$folder = dirname(dirname(__FILE__));
require_once $folder . "/helpers/curl.php";

require_once 'abstract_mongo_model.php';


class Api_model extends Abstract_mongo_Model {

    public function __construct(){
        parent::__construct();
		
        $this->mongo_db->set_database('database');

        $this->load->model('companies_model');
        $this->load->model('reporting_periods_model', 'rperiods');
    }

    public function set_object($data = array()) {
        return $data;
    }

    public function load_companies_api($companies_id = array(), $kpis = array(), $period = array()) {

        $this->benchmark->mark('Api_model-load_companies_api_start');

        $entityID = array_to_string($companies_id);
        $termID   = array_to_string($kpis);
        $FYFQ     = array_to_string($period);

        $result = $this->get_data($entityID, $termID, $FYFQ);

        $all_companies = array();

        if(empty($result) || !is_array($result))
            return $all_companies;

        $c1 = array();
        foreach ($companies_id as $_company) {

            $c1["entityID"] = $_company;
            $obj = $this->companies_model->get_by_id($_company);
            $c1['company_name'] = $obj->company_name;
            $c1['drilldown'] = array();

            foreach ($kpis as $kpi) {
                $found = false;
                foreach ($result as $r) {
                    if(empty($r['entityID']) && !empty($r['entityId']))
                        $r['entityID'] = $r['entityId'];
                    if(empty($r['termID']) && !empty($r['termId']))
                        $r['termID'] = $r['termId'];
                    if(empty($r['value']) && !empty($r['amount']))
                        $r['value'] = $r['amount'];
                    if (!empty($r['entityID']) && !empty($r['termID']) && $_company == $r['entityID'] && $kpi == $r['termID']) {
                        if(!empty($r['error']))
                            $c1['error'] = $r['error'];

                        if(!empty($r['value']))
                            $c1[(string)$r['termID']] = $r['value'];
                        else
                            $c1[(string)$r['termID']] = '';

                        //echo 'company: '.$_company.'/'.$r['entityID'].' - kpi: '.$kpi.'/'.$r['termID'].' - value: '.$c1[$r['termID']].' <br/>';

                        $info = array();

                        if ((isset($r['dimensionalFacts']) && count($r['dimensionalFacts']) > 0) ||
                            (isset($r['dimData']) && $r['dimData'] == 'true')) {    // dimData is only provided by curl - php provides the dimensionalFacts array
                            $c1['drilldown'][] = (string)$r['termID'];
                        }

                        $info['entity_id'] = $obj->entity_id;
                        $info['cik'] = $obj->cik;
                        $info['company_name'] = $obj->company_name;
                        $info['industry'] = $obj->industry;
                        $info['sector'] = $obj->sector;
                        $info['sic'] = $obj->sic;
                        $info['sic_code'] = $obj->sic_code;
                        $info['state'] = $obj->state;
                        $info['stock_symbol'] = $obj->stock_symbol;
                        $c1['info'] = $info;
                        $found = true;

                        break;
                    }
                }
                if (!$found) {
                    $info = array();
                    $info['entity_id'] = $obj->entity_id;
                    $info['cik'] = $obj->cik;
                    $info['company_name'] = $obj->company_name;
                    $info['industry'] = $obj->industry;
                    $info['sector'] = $obj->sector;
                    $info['sic'] = $obj->sic;
                    $info['sic_code'] = $obj->sic_code;
                    $info['state'] = $obj->state;
                    $info['stock_symbol'] = $obj->stock_symbol;
                    $c1['info'] = $info;
                    $c1[$kpi] = '';
                }
            }
            $all_companies[] = $c1;
        }

        $this->benchmark->mark('Api_model-load_companies_api_end');

        log_message('debug', 'Api_model-load_companies_api: ' . $this->benchmark->elapsed_time('Api_model-load_companies_api_start', 'Api_model-load_companies_api_end'));

        //$dataSources = $this->datasources($result);

        //$all_companies['datasources'] = $dataSources;

        //var_dump($all_companies[1]);
        return $all_companies;
    }



    public function get_companies_kpis_values($companies_id = array(), $kpis = array(), $period = array()) {

        $this->benchmark->mark('Api_model-get_companies_kpis_values_start');

        $entityID = array_to_string($companies_id);
        $termID   = array_to_string($kpis);
        $FYFQ     = array_to_string($period);

        $result = $this->get_data($entityID, $termID, $FYFQ);

        $all_companies = array();
        $c1 = array();
        foreach ($companies_id as $_company) {
            $c1["entityID"] = $_company;
            $obj = $this->companies_model->get_by_id($_company);
            $c1['company_name'] = $obj->company_name;

            foreach ($kpis as $kpi) {
                $found = false;
                foreach ($result as $r) {
                    if(empty($r['entityID']) && !empty($r['entityId']))
                        $r['entityID'] = $r['entityId'];
                    if(empty($r['termID']) && !empty($r['termId']))
                        $r['termID'] = $r['termId'];
                    if(empty($r['value']) && !empty($r['amount']))
                        $r['value'] = $r['amount'];
                    if (!empty($r['entityID']) && !empty($r['termID']) && $_company == $r['entityID'] && $kpi == $r['termID']) {
                        if(!empty($r['error']))
                            $c1['error'] = $r['error'];

                        if(!empty($r['value']))
                            $c1[(string)$r['termID']] = $r['value'];
                        else
                            $c1[(string)$r['termID']] = '';

                        $info = array();
                        $info['entity_id'] = $obj->entity_id;
                        $info['cik'] = $obj->cik;
                        $info['company_name'] = $obj->company_name;
                        $info['industry'] = $obj->industry;
                        $info['sector'] = $obj->sector;
                        $info['sic'] = $obj->sic;
                        $info['sic_code'] = $obj->sic_code;
                        $info['state'] = $obj->state;
                        $info['stock_symbol'] = $obj->stock_symbol;
                        $c1['info'] = $info;
                        $found = true;

                        break;
                    }
                }
                if (!$found) {
                    $info = array();
                    $info['entity_id'] = $obj->entity_id;
                    $info['cik'] = $obj->cik;
                    $info['company_name'] = $obj->company_name;
                    $info['industry'] = $obj->industry;
                    $info['sector'] = $obj->sector;
                    $info['sic'] = $obj->sic;
                    $info['sic_code'] = $obj->sic_code;
                    $info['state'] = $obj->state;
                    $info['stock_symbol'] = $obj->stock_symbol;
                    $c1['info'] = $info;
                    $c1[$kpi] = '';
                }
            }
            $all_companies[] = $c1;
        }

        $this->benchmark->mark('Api_model-get_companies_kpis_values_end');

        log_message('debug', 'Api_model-get_companies_kpis_values: ' . $this->benchmark->elapsed_time('Api_model-get_companies_kpis_values_start', 'Api_model-get_companies_kpis_values_end'));

        return $all_companies;
    }

    protected function get_cache_key($entityID, $termID, $FYFQ, $specialFormat = FALSE) {

        // get_companies_kpis_values             : $data = '{"entityID" : ['.$entityID.'],"termID" : ['.$termID.'],"FYFQ" : ['.$FYFQ.']}';
        // get_companies_kpis_values_all_periods : $data = '{"entityID" : ['.$entityID.'],"termID" : ['.$termID.'],"FYFQ" : ['.$FYFQ.']}';
        // get_company_kpis_values               : $data = '{"entityID" : ["'.$entityID.'"],"termID" : ['.$termID.'],"FYFQ" : ['.$FYFQ.']}';

        if ($this->config->item('use_php_service') === TRUE || $specialFormat === FALSE) {
            $ck = '{"entityID" : [' . $entityID . '],"termID" : [' . $termID . '],"FYFQ" : [' . $FYFQ . ']}';
        }
        else {
            $ck = '{"entityID" : ["' . $entityID . '"],"termID" : [' . $termID . '],"FYFQ" : [' . $FYFQ . ']}';
        }

        log_message('debug', 'Api_model-cacheKey: ' . $ck);

        return $ck;
    }

    protected function get_data($entityID, $termID, $FYFQ, $specialFormat = FALSE) {

        if ($this->config->item('use_php_service') === TRUE) {
            return $this->get_data_php($entityID, $termID, $FYFQ);
        }

        // the default
        return $this->get_data_curl($entityID, $termID, $FYFQ, $specialFormat);
    }

    protected function get_data_curl($entityID, $termID, $FYFQ, $specialFormat) {

        //first we attempt to get the data from the session
        //if no data is stored in the session, we get data from API
        $cacheKey = $this->get_cache_key($entityID, $termID, $FYFQ, $specialFormat);

        $use_cache = FALSE;
        if ($this->config->item('use_cache') === TRUE) {
            $use_cache = TRUE;

            $from_session = true;

            $result = $this->session->userdata($cacheKey);
        }

        if (empty($result)) {

            $url = API_URL . API_GET_DATA_SERVICE;
            $user = API_AUTH__USER;
            $pass = API_AUTH_PASSE;

            //var_dump($data);

            $this->benchmark->mark('Api_model-curl_start');

            $_mycurl = new  mycurl($url, $user, $pass);
            $_mycurl->setPost($cacheKey);
            $_mycurl->createCurl();
            $result = (string)$_mycurl;

            $result = json_decode($result, true);

            $this->benchmark->mark('Api_model-curl_end');

            log_message('debug', 'Api_model-curl: ' . $this->benchmark->elapsed_time('Api_model-curl_start', 'Api_model-curl_end'));

            //log_message('debug', 'Api_model-curl: json_decode: ' . print_r($result, true));
            $from_session = false;
        }

        /* don't cache */
        if($use_cache === TRUE && !$from_session && !empty($result))
            $this->session->set_userdata($cacheKey, $result);
        /* */

        //var_dump($result);

        return $result;
    }

    protected function create_array($dataIn) {

        $ds = str_replace('"', '', $dataIn);
        $ds = str_replace(' ', '', $ds);

        return explode(',', $ds);
    }

    protected function create_year_fq_arrays($fyfq, &$yearArray, &$fqArray) {

        $fyfqArray = $this->create_array($fyfq);

        // data comes in either as 2010, 2011, 2012 (which implies fq = FY
        // or 2010Q1, 2010Q2,

        $fqDoneArray = array(
            'Q1' => false,
            'Q2' => false,
            'Q3' => false,
            'Q4' => false,
            'FY' => false,
            'ALL' => false
        );

        foreach ($fyfqArray as $fItem) {

            $fq = "FY";
            if ($fqDoneArray['ALL'] === FALSE) {
                if (strlen($fItem) == 6) {
                    $fq = substr($fItem, 4, 6);
                }
            }

            $y = intval(substr($fItem, 0, 4));
            if (array_search($y, $yearArray) === FALSE) {

                array_push($yearArray, $y);
            }

            if ($fqDoneArray['ALL'] === FALSE && $fqDoneArray[$fq] === FALSE) {

                array_push($fqArray, $fq);
                $fqDoneArray[$fq] = TRUE;

                if ($fqDoneArray['Q1'] === TRUE &&
                $fqDoneArray['Q2'] === TRUE &&
                $fqDoneArray['Q3'] === TRUE &&
                $fqDoneArray['Q4'] === TRUE &&
                $fqDoneArray['FY'] === TRUE)
                {
                    $fqDoneArray['ALL'] = TRUE;
                }
            }
        }
    }

    protected function get_data_php($entityID, $termID, $FYFQ) {

        // for caching
        $fyfq = trim($FYFQ, '"');

        $use_cache = FALSE;

        if ($this->config->item('use_cache') === TRUE) {
            $use_cache = TRUE;
            $cacheKey = $this->get_cache_key($entityID, $termID, $fyfq);

            // comment out to disable cache
            $result = $this->session->userdata($cacheKey);
            if (!empty($result)) {
                return $result;
            }
        }

        $this->benchmark->mark('Api_model-php_start');

        $termArray = $this->create_array($termID);
        $terms = $this->sort_terms($termArray);

        $entityArray = $this->create_array($entityID);

        $yearArray = array();
        $fqArray = array();
        $this->create_year_fq_arrays($fyfq, $yearArray, $fqArray);

        $result = array();

        if (count($terms->TermResults) > 0) {
            $result = $this->get_data_from_mongo($terms->TermResults, $entityArray, $yearArray, $fqArray, 'TermResults_Col');
        }

        if (count($terms->InsiderTrading) > 0) {
            $yearsAsStrings = $this->convert_array_to_strings($yearArray);
            $result = array_merge($result, $this->get_data_from_mongo($terms->InsiderTrading, $entityArray, $yearsAsStrings, $fqArray, 'insiderTradingFacts_Col' ));
        }

        if (count($terms->GovContract) > 0) {
            $yearsAsStrings = $this->convert_array_to_strings($yearArray);
            $result = array_merge($result, $this->get_data_from_mongo($terms->GovContract, $entityArray, $yearsAsStrings, $fqArray, 'contractFacts_Col' ));
        }

        //$result = json_decode($result, true);

        $this->benchmark->mark('Api_model-php_end');

        log_message('debug', 'Api_model-php: ' . $this->benchmark->elapsed_time('Api_model-php_start', 'Api_model-php_end'));
        //log_message('debug', 'Api_model-php: json_decode: ' . print_r($result, true));

        /* don't cache */
        if($use_cache === TRUE && !empty($result)) {
            $this->session->set_userdata($cacheKey, $result);
        }
        /* */

        return $result;
    }

    protected function convert_array_to_strings($theArray) {

        $strArray = array();
        foreach ($theArray as $val) {
            array_push($strArray, strval($val));
        }

        return $strArray;
    }

    protected function sort_terms($terms) {

        $result = new stdClass();
        $result->GovContract = array();
        $result->InsiderTrading = array();
        $result->TermResults = array();

        $insiderTrading = $this->config->item('InsiderTradingTermIds');
        $gov_contracts = $this->config->item('GovContractTermIds');

        foreach ($terms as $t) {

            $t_trimmed = trim($t, '"');

            if (array_search($t_trimmed, $insiderTrading) !== false) {

                array_push($result->InsiderTrading, $t);
            }
            else if (array_search($t_trimmed, $gov_contracts) !== false) {

                array_push($result->GovContract, $t);
            }
            else {
                array_push($result->TermResults, $t);
            }
        }

        return $result;
    }

    protected function get_data_from_mongo($terms, $entities, $years, $fqs, $collectionName) {

        $this->collection = $this->mongo_db->get_collection($collectionName);

        $query = array();

        if (count($entities) > 1) {

            $query['entityId']['$in'] = $entities;
        }
        else {

            $query['entityId'] = $entities[0];
        }

        if (count($terms) > 1) {

            $query['termId']['$in'] = $terms;
        }
        else {

            $query['termId'] = $terms[0];
        }

        if (count($years) > 1) {
            $query['FY']['$in'] = $years;
        }
        else {
            $query['FY'] = $years[0];
        }

        if (count($fqs) > 1) {

            $query['FQ']['$in'] = $fqs;
        }
        else {
            $query['FQ'] = $fqs[0];
        }

        log_message('debug', print_r($query, true));

        try {
            $res = $this->find($query);

            if (is_array($res) === false) {
                log_message('debug', 'mongo->find returns: ' . $res);
            }
            else {
                log_message('debug', 'mongo->find returns array ');
            }
        }
        catch (Exception $e) {

            log_message('error', 'Exception caught: ' . $e->getMessage());
            $res = null;
        }

        //log_message('debug', 'Returning from mongo find ');
        //log_message('debug', print_r($res, true));

        return $res;
    }


    public function get_companies_kpis_values_all_periods($companies_id = array(), $kpis = array(), $segments = 'year') {

        $this->benchmark->mark('Api_model-get_companies_kpis_values_all_periods_start');

        $all_periods = $this->rperiods->list_records();
        $quarters = array();
        $years = array();
        foreach ($all_periods as $k => $p) {
          if(strpos($p->reporting_period, 'Q') === false)
            $years[] = $p->reporting_period;
          else
            $quarters[] = $p->reporting_period;
        }

        if($segments == 'quarter') {
            $periods = $quarters;
        }
        else
            $periods = $years;


        $entityID = array_to_string($companies_id);
        $termID   = array_to_string($kpis);
        $FYFQ     = array_to_string($periods);

        $result = $this->get_data($entityID, $termID, $FYFQ);

        $all_companies = array();
        $c1 = array();
        foreach ($companies_id as $_company) {
            $c1["entityID"] = $_company;
            $obj = $this->companies_model->get_by_id($_company);
            $c1['company_name'] = $obj->company_name;
            foreach ($periods as $period)
            {
                foreach ($kpis as $kpi) {
                    $found = false;
                    $key = '';
                    $c2 = array();
                    foreach ($result as $k => $r) {
                        if(empty($r['entityID']) && !empty($r['entityId']))
                            $r['entityID'] = $r['entityId'];
                        if(empty($r['termID']) && !empty($r['termId']))
                            $r['termID'] = $r['termId'];
                        if(empty($r['value']) && !empty($r['amount']))
                            $r['value'] = $r['amount'];

                        if (empty($r['FYFQ'])) {
                            if ($r['FQ'] !== 'FY') {
                                $r['FYFQ'] = $r['FY'] . $r['FQ'];
                            }
                            else {
                                $r['FYFQ'] = $r['FY'];
                            }
                        }
                        if (!empty($r['entityID']) && !empty($r['termID']) && $_company == $r['entityID'] && $kpi == $r['termID'] && ($period == $r['FYFQ'] || $period == $r['FY'])) {
                            if(!empty($r['error']))
                                $c2['error'] = $r['error'];

                            if(!empty($r['value']))
                                $c2[(string)$r['termID']] = floatval($r['value']);
                            else
                                $c2[(string)$r['termID']] = '';

                            $found = true;
                            $key = $k;

                            break;
                        }
                    }
                    if (!$found) {
                        $c2[$kpi] = '';
                    }
                    foreach ($c2 as $k => $v) {
                        $c1[$period][$k] = $v;
                    }
                    if(!empty($key))
                    {
                        unset($result[$key]);
                        $result = array_values($result);
                    }
                }
            }
            $all_companies[] = $c1;
        }

        $this->benchmark->mark('Api_model-get_companies_kpis_values_all_periods_end');

        log_message('debug', 'get_companies_kpis_values_all_periods: ' . $this->benchmark->elapsed_time('Api_model-get_companies_kpis_values_all_periods_start', 'Api_model-get_companies_kpis_values_all_periods_end'));

        return $all_companies;
    }

    public function get_company_kpis_values($company, $kpis = array(), $segments = 'year') {

        $this->benchmark->mark('Api_model-get_company_kpis_values_start');

        $all_periods = $this->rperiods->list_records();
        $quarters = array();
        $years = array();
        foreach ($all_periods as $k => $p) {
          if(strpos($p->reporting_period, 'Q') === false)
            $years[] = $p->reporting_period;
          else
            $quarters[] = $p->reporting_period;
        }

        if($segments == 'quarter') {
            $periods = $quarters;
        }
        else
            $periods = $years;


        $entityID = sprintf("%06d", $company);
        $termID   = array_to_string($kpis);
        $FYFQ     = array_to_string($periods);

        $result = $this->get_data($entityID, $termID, $FYFQ, TRUE);

        $return_result = array();

        $return_result["entityID"] = $company;
        $obj = $this->companies_model->get_by_id($company);
        $return_result['company_name'] = $obj->company_name;
        $data = array();

        foreach ($kpis as $kpi) {
            $k = array();
            $k['kpi'] = $kpi;
            $k['vals'] = array();
            foreach ($periods as $period) {
                $found = false;
                $key = '';
                $c2 = '';
                foreach ($result as $e => $r) {
                    if(empty($r['entityID']) && !empty($r['entityId']))
                        $r['entityID'] = $r['entityId'];
                    if(empty($r['termID']) && !empty($r['termId']))
                        $r['termID'] = $r['termId'];
                    if(empty($r['value']) && !empty($r['amount']))
                        $r['value'] = $r['amount'];

                    if (empty($r['FYFQ'])) {
                        if ($r['FQ'] !== 'FY') {
                            $r['FYFQ'] = $r['FY'] . $r['FQ'];
                        }
                        else {
                            $r['FYFQ'] = $r['FY'];
                        }
                    }

                    if (!empty($r['entityID']) && !empty($r['termID']) && $company == $r['entityID'] && $kpi == $r['termID'] && ($period == $r['FYFQ'] || $period == $r['FY'])) {
                        if(!empty($r['value']))
                            $c2 = floatval($r['value']);
                        else
                            $c2 = '';

                        $found = true;
                        $key = $e;

                        break;
                    }
                }
                if (!$found) {
                    $c2 = '';
                }
                if(!empty($key)) {
                    unset($result[$key]);
                    $result = array_values($result);
                }
                $k['vals'][] = $c2;
            }
            $data[] = $k;
        }
        $return_result['data'] = $data;

        $this->benchmark->mark('Api_model-get_company_kpis_values_end');

        log_message('debug', 'get_company_kpis_values: ' . $this->benchmark->elapsed_time('Api_model-get_company_kpis_values_start', 'Api_model-get_company_kpis_values_end'));

        return $return_result;
    }

    /*
    private function datasources($result) {
        $datasources = array();
        $string = '';
        foreach ($result as $arr) {
            if (empty($arr['error']) || $arr['error'] == NULL) {
                foreach ($arr['dataSources'] as $arr2) {
                    //var_dump($arr2);
                    array_push($datasources, $arr2['dataSource']);
                }
            }
        }
        $datasources = array_values(array_unique($datasources));
        //var_dump($datasources);

        $n = count($datasources);
        for ($i = 0; $i < $n; $i++) {
            //if(!empty($datasources[$i]))
            $string .= ( $i < $n - 1) ? ($datasources[$i] . ', ') : ($datasources[$i]);
        }

        return $string;
    }
    */
}

