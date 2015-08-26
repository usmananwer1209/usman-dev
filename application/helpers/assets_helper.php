<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('css_url')){
	function css_url($nom){
		return base_url() . 'assets/css/' . $nom . '.css';
		}
	}
if ( ! function_exists('htc_url')){
	function htc_url($nom){
		return base_url() . 'assets/css/' . $nom . '.htc';
		}
	}	
if ( ! function_exists('js_url')){
	function js_url($nom){
		return base_url() . 'assets/js/' . $nom . '.js';
		}
	}
if ( ! function_exists('css_lib')){
	function css_lib($nom,$lib=""){
		return base_url().'assets/plugins/'.(($lib!="")?($lib.'/'):"").$nom.'.css';
		}
	}
if ( ! function_exists('js_lib')){
	function js_lib($nom,$lib=""){
		return base_url().'assets/plugins/'.(($lib!="")?($lib.'/'):"").$nom.'.js';
		}
	}

if ( ! function_exists('img_url')){
	function img_url($nom){
		return base_url() . 'assets/img/' . $nom;
		}
	}
if ( ! function_exists('img')){
	function img($nom, $alt = '', $height='', $width=''){
		$h = ''; $w = '';
		if(!empty($height))
			$h = '  height="' . $height . '"';
		if(!empty($width))
			$w = '  width="' . $width . '"';
		if(!empty($alt))
			$a = ' alt="'.$alt.'" ';
		return '<img src="'.img_url($nom).'" '.$a.' '.$h.' '.$w.' />';
		}
	}

if ( ! function_exists('uploads_url')){
	function uploads_url($user = null){
		if(!empty($user)){
			return base_url() . 'data/upload/'. $user->id.'/';	
			}
		else
			return base_url() . 'data/upload/';	
		}
	}

if ( ! function_exists('avatar_default_url')){
	function avatar_default_url(){
		return base_url() . 'data/upload/avatar.jpg';	
		}
	}
if ( ! function_exists('avatar_url')){
	function avatar_url($user = null){
		
		if(!empty($user)){
			$filename = 'data/upload/'. $user->id.'/avatar.';	
			$url ="";
			if (file_exists($filename. 'jpg'))
			    $url = base_url() . $filename . 'jpg';
			else if (file_exists($filename. 'jpeg'))
			    $url = base_url() . $filename . 'jpeg';
			else if (file_exists($filename. 'png'))
			    $url = base_url() . $filename . 'png';
			else 
			    $url = avatar_default_url();

			return $url;	
			}
		else
			return avatar_default_url();	
		}
	}
if ( ! function_exists('get_user_dir')){
	function get_user_dir($user){
		if(!empty($user)){
			$dir_name = $this->config->item('upload_path') . $user;	
			if (!file_exists($dir_name))
				mkdir($dir_name, 0777);
			return $dir_name;	
			}
	}
}

if ( ! function_exists('is_img')){
	function is_img($url){
		if(!empty($url)){
			$extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
			if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif')
		    return true;
		  else
		    return false;
		}
		else
			return false;
	}
}

if ( ! function_exists('get_youtube_id_from_url')){
	function get_youtube_id_from_url($url){
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
		if(!empty($matches[0]))
			return $matches[0];
		else
			return false;
	}
}	


if ( ! function_exists('get_vimeo_id_from_url')){
	function get_vimeo_id_from_url($url){
		$result = preg_match('/(\d+)/', $url, $matches);
		if (!empty($matches[0])) {
		    return $matches[0];
		}
		else
			return false;

	}
}	

if ( ! function_exists('getVimeoThumb')){
	function getVimeoThumb($videoid,$size = '') {
		$xml = simplexml_load_file("http://vimeo.com/api/v2/video/".$videoid.".xml");
		$xml = $xml->video;
		$xml_pic = $xml->thumbnail_medium;
		return $xml_pic;
	}
}	