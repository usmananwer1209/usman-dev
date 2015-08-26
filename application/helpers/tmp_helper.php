<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('sortArrayofObjectByProperty')){
    function sortArrayofObjectByProperty($array, $property, $order = "DESC") {

        $array = (array)$array;
        $cur = 1;
        $stack[1]['l'] = 0;
        $stack[1]['r'] = count($array) - 1;
        do {
            $l = $stack[$cur]['l'];
            $r = $stack[$cur]['r'];
            $cur--;
            do {
                $i = $l;
                $j = $r;
                $tmp = $array[(int) ( ($l + $r) / 2 )];

                // split the array in to parts
                // first: objects with "smaller" property $property
                // second: objects with "bigger" property $property
                do {

                    while (!empty($array[$i][$property]) && !empty($tmp[$property]) && $array[$i][$property] < $tmp[$property] )
                        $i++;
                    while (!empty($array[$i][$property]) && !empty($tmp[$property]) && $tmp[$property] < $array[$j][$property])
                        $j--;

                    // Swap elements of two parts if necesary
                    if ($i <= $j) {
                        $w = $array[$i];
                        $array[$i] = $array[$j];
                        $array[$j] = $w;

                        $i++;
                        $j--;
                    }
                    
                } while ($i <= $j);

                if ($i < $r) {
                    $cur++;
                    $stack[$cur]['l'] = $i;
                    $stack[$cur]['r'] = $r;
                }
                $r = $j;
            } while ($l < $r);
        } while ($cur != 0);
        /*
        for($index=0; $index<count($array); $index++){
            if($array[$index][$property]==''){
                $arr[]=$index;
            }
        }
        for($index=0; $index<count($arr); $index++){
            array_unshift($array, $array[$arr[$index]]);
            unset($array[$arr[$index]+1]);
            $array = array_values($array);
        }
         //*/

        // Added ordering.
        if ($order == "DESC") {
            $array = array_reverse($array);
        }
        return $array;
    }

}
if ( ! function_exists('get_min_max_value')){
    function get_min_max_value($companies, $property, $op) {
        $companies = (array)($companies );
        $init_first = false;
        $v = 0;
        foreach ($companies as $company) {
        	if (is_array($company) && array_key_exists('error', $company) && !empty($company['error']) )
				continue;
            $company = (array)($company );
            if(!empty($company[$property]))
            {
                $value = $company[$property];
                if (!$init_first) {
                    $v = abs(floatval($value));
                    $init_first = true;
                }
                if ($op == 'max') {
                    $v = (abs(floatval($value)) > $v) ? abs(floatval($value)) : $v;
                } else if ($op == 'min') {
                    $v = (abs(floatval($value)) < $v) ? abs(floatval($value)) : $v;
                }
            }
        }
        return $v;
    }	
}   


if ( ! function_exists('csv_to_array')){
    function csv_to_array($filename = '', $delimiter = ',') {
        if (!file_exists($filename) || !is_readable($filename))
            return FALSE;
        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}
    
if ( ! function_exists('string_to_float')){
    function string_to_float($v){
        $v = str_replace("%","",$v);
        $v = str_replace(" ","",$v);
        $v = str_replace("(","",$v);
        $v = str_replace(",","",$v);
        $v = str_replace(")","",$v);
        $v = floatval($v);
        $v = round($v, 2);
        return $v;
        }
    }

if ( ! function_exists('state_city_coordinates')){
    function state_city_coordinates($state,$company_name){
        $v = "";
        if($state ==  "AL") $v = "[32.366805 , -86.299969]";
        else if($state ==  "AK") $v = "[58.301944 , -134.419722]";
        else if($state ==  "AZ") $v = "[33.448377 , -112.074037]";
        else if($state ==  "AR") $v = "[34.746481 , -92.289595]";
        else if($state ==  "CA"){
            if( empty($_SESSION["CA_".$company_name])){
                $x = 38.581572;
                $x = rand($x - 1, $x + 1);
                $y =  -121.4944;
                $y = rand($y - 2, $y + 2);
                $_SESSION["CA_".$company_name] = "[" . $x . " , " . $y . "]";
            }
            $v = $_SESSION["CA_".$company_name];
        }
        
        else if($state ==  "CO") $v = "[39.737567 , -104.984718]";
        else if($state ==  "CT") $v = "[41.763711 , -72.685093]";
        else if($state ==  "DE") $v = "[39.158168 , -75.524368]";
        else if($state ==  "FL") $v = "[30.438256 , -84.280733]";
        else if($state ==  "GA") $v = "[33.748995 , -84.387982]";
        else if($state ==  "HI") $v = "[21.306944 , -157.858333]";
        else if($state ==  "ID") $v = "[43.618710 , -116.214607]";
        else if($state ==  "IL") $v = "[39.781721 , -89.650148]";
        else if($state ==  "IN") $v = "[39.768403 , -86.158068]";
        else if($state ==  "IA") $v = "[41.600545 , -93.609106]";
        else if($state ==  "KS") $v = "[39.055824 , -95.689018]";
        else if($state ==  "KY") $v = "[38.200905 , -84.873284]";
        else if($state ==  "LA") $v = "[30.458283 , -91.14032]";
        else if($state ==  "ME") $v = "[44.310624 , -69.77949]";
        else if($state ==  "MD") $v = "[38.978445 , -76.492183]";
        else if($state ==  "MA") $v = "[42.358431 , -71.059773]";
        else if($state ==  "MI") $v = "[42.732535 , -84.555535]";
        else if($state ==  "MN") $v = "[44.953703 , -93.089958]";
        else if($state ==  "MS") $v = "[32.298757 , -90.18481]";
        else if($state ==  "MO") $v = "[38.576702 , -92.173516]";
        else if($state ==  "MT") $v = "[46.595806 , -112.027031]";
        else if($state ==  "NE") $v = "[40.806862 , -96.681679]";
        else if($state ==  "NV") $v = "[39.163798 , -119.767403]";
        else if($state ==  "NH") $v = "[43.208137 , -71.537572]";
        else if($state ==  "NJ") $v = "[40.217053 , -74.742938]";
        else if($state ==  "NM") $v = "[35.686975 , -105.937799]";
        else if($state ==  "NY") $v = "[42.652579 , -73.756232]";
        else if($state ==  "NC") $v = "[35.779590 , -78.638179]";
        else if($state ==  "ND") $v = "[46.808327 , -100.783739]";
        else if($state ==  "OH") $v = "[39.961176 , -82.998794]";
        else if($state ==  "OK") $v = "[35.467560 , -97.516428]";
        else if($state ==  "OR") $v = "[44.942898 , -123.035096]";
        else if($state ==  "PA") $v = "[40.273700 , -76.884418]";
        else if($state ==  "RI") $v = "[41.823989 , -71.412834]";
        else if($state ==  "SC") $v = "[34.000710 , -81.034814]";
        else if($state ==  "SD") $v = "[44.368316 , -100.350967]";
        else if($state ==  "TN") $v = "[36.166667 , -86.783333]";
        else if($state ==  "TX") $v = "[30.267153 , -97.743061]";
        else if($state ==  "UT") $v = "[40.760779 , -111.891047]";
        else if($state ==  "VT") $v = "[44.260059 , -72.575387]";
        else if($state ==  "VA") $v = "[37.540725 , -77.436048]";
        else if($state ==  "WA") $v = "[47.037874 , -122.900695]";
        else if($state ==  "WV") $v = "[38.349820 , -81.632623]";
        else if($state ==  "WI") $v = "[43.073052 , -89.40123]";
        else if($state ==  "WY") $v = "[41.139981 , -104.820246]";
        return $v;
    }
}

if ( ! function_exists('is_id_in_elmts_array'))
{
    function is_id_in_elmts_array($id, $objects_array)
    {
       foreach ($objects_array as $key => $value) 
       {
            $value = (array) $value;
            if($value['id'] == $id)
                return true;
       }
       return false;
    }
}


