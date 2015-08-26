<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('string_to_json')){
	function string_to_json($s){
	    $s = str_replace("&", "", $s);
	    $s = str_replace("'", "", $s);
	    $s = str_replace("\"", "", $s);
	    $s = str_replace("\\", "", $s);
	    $s = str_replace(",", "", $s);
	    $s = str_replace(".", "", $s);
	    return $s;
	  }
	}

if ( ! function_exists('array_to_string')){
	function array_to_string($objs , $delimiter = ','){
		$s = "" ;
		$numItems = count($objs);
        $i = 0;
        foreach($objs as $obj) {
    	 	$s .= '"'.(string)$obj.'"';
          	if(!(++$i === $numItems)) {
            	 $s .= $delimiter.' ';
	          	}
	        }   
	    return $s;
	  }
	}

if ( ! function_exists('cut_string')){
    function cut_string($name , $lenght = 13){
    	$name = (strlen($name) > $lenght) ? substr($name,0,($lenght-3)).'...' : $name;
		return $name;
    }
}

if ( ! function_exists('format_number')){
    function format_number($val){

      $is_neg = ($val < 0)?true : false;
      $val = abs($val);
      $r = $val;

    	if($val>=1000000000000) $r = round(($val/1000000000000),2).' T';
    	elseif($val>=1000000000) $r = round(($val/1000000000),2).' B';
        else if($val>=1000000) $r = round(($val/1000000),2).' M';
          else $r = str_replace('.00', '', number_format($val, '2', '.', ','));
        //else if($val>1000) return round(($val/1000),1).' thousand';

      $r =  ($is_neg)?('-'.$r):$r;
        return $r;
    }
}

if ( ! function_exists('ago')){
	function ago($time)
  {
     $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
     $lengths = array("60","60","24","7","4.35","12","10");

     $now = time();

         $difference = $now - $time;
         $tense = "ago";

     for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
         $difference /= $lengths[$j];
     }

     $difference = round($difference);

     if($difference != 1) {
         $periods[$j].= "s";
     }

     return "$difference $periods[$j] ago ";
  }
}

if ( ! function_exists('remove_unicode')){
  function remove_unicode($str)
  {
    $str = str_replace('’', '\'', $str);
    $str = str_replace('“', '"', $str);
    $str = str_replace('”', '"', $str);

    return $str;
  }
}

if ( ! function_exists('id_to_guid')){
    /**
     * @param $id
     * @return string
     */
    function id_to_guid($id)
  {
    $str = hash('md5', $id);

    //insert id at the 10th pos
    $hexStr = dechex($id);
    $secret = strlen($hexStr) . $hexStr;

    $str = substr_replace($str, $secret, 10, 0). hash('md5', 'idaciti');

    return $str;
  }
}

if ( ! function_exists('guid_to_id')){
  function guid_to_id($guid)
  {
    //get the id
    $id_length = subStr($guid, 10, 1);
    $hexedId = substr($guid, 10+1, $id_length);
    $id = hexdec($hexedId);

      // $guid might be longer than $check, so only compare up to $check len
      // which substr_compare gives us
    $check =  id_to_guid($id);

    $temp = substr($guid, 0, strlen($check));

    if (strcmp($check, $temp) == 0) {
        return $id;
    }

    return false;
  }
}

