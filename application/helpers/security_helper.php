<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('has_acces')){
	/****************************    Acces     *********************************/
	/***************************************************************************/
	function has_acces($user,$fct,$obj = null){
		if($user->is_root)
			return true;
		elseif(strpos($fct,'my_') !== false)
		    return true;
		if($fct=='/profile/edit/')
			return true;
		/*************    Profile    *******************/
		if($fct=='/home/')
			return true;
		elseif($fct== ('/profile/edit/'.$user->id))
			return true;
		elseif($fct=='/profile/all')
			return true;
		elseif(startsWith($fct,'/profile/view'))
			return true;
		/*************    Circle    *******************/
		elseif(startsWith($fct,'/circle/view'))
			return true;
		elseif($fct=='/circle/all/')
			return true;
		elseif($fct=='/circle/all/'.$user->id)
			return true;
		/*************    Card    *******************/
		elseif($fct=='/card/')
			return true;
		elseif(startsWith($fct,'/card/all'))
			return true;
		elseif(startsWith($fct,'/card/add'))
			return true;
		elseif(startsWith($fct,'/card/edit'))
			return true;
		elseif(startsWith($fct,'/card/submit'))
			return true;
		elseif(startsWith($fct,'/card/view'))
			return true;
		elseif(startsWith($fct,'/card/save'))
			return true;
		elseif(startsWith($fct,'/explore/explore_rank'))
			return true;

		/*************    Card    *******************/
		elseif($fct=='/storyboard/')
			return true;
		elseif(startsWith($fct,'/storyboard/all'))
			return true;
		elseif(startsWith($fct,'/storyboard/add'))
			return true;
		elseif(startsWith($fct,'/storyboard/edit'))
			return true;
		elseif(startsWith($fct,'/storyboard/submit'))
			return true;
		elseif(startsWith($fct,'/storyboard/view'))
			return true;
		elseif(startsWith($fct,'/storyboard/save'))
			return true;
		
		return false;
		}
	/***************************************************************************/
	}
	
if ( ! function_exists('is_active')){
	function is_active($current,$page){
		if($current==$page)
			return "active";
		return "";
		}
	}
if ( ! function_exists('module_active')){
	function module_active($current,$module){
		if( $module=="Dashboard" ){
			if(strpos($current, "/profile") === 0 )
				return true;
			elseif(strpos($current, "/circle") === 0 )
				return true;
			else 
				return false;
			
		}
		if( $module=="Browse Cards" ){
			if(strpos($current, "/card/all") === 0 )
				return true;
		}
		if( $module=="My Cards" ){
			if(strpos($current, "/card") === 0 && strpos($current, "/card/all") !== 0)
				return true;
		}
		if( $module=="Browse Circles" ){
			if(strpos($current, "/circle") === 0 )
				return true;
			else 
				return false;
		}
		if( $module=="Browse Storyboards" ){
			if(strpos($current, "/storyboard/all") === 0 )
				return true;
			else 
				return false;
		}
		if( $module=="My Storyboards" ){
			if(strpos($current, "/storyboard") === 0 && strpos($current, "/storyboard/all") !== 0  )
				return true;
			else 
				return false;
		}
		if(strpos($current, $module) === 0 )
			return "start active open";
		return false;
		}
	}
		
if ( ! function_exists('user_full_name')){
	function user_full_name($user_id){
		$CI =& get_instance();            
		$CI->load->model('users_model', 'users');
		$user = $CI->users->get_by_id($user_id);
		if(!empty($user))
			return $user->first_name.' '.$user->last_name;
		return "";
		}
	}
if ( ! function_exists('card_shared')){
	function card_shared($card_id,$circle_id){
		$CI =& get_instance();
		$CI->load->model('cards_model', 'cards');
		return $CI->cards->card_shared($card_id,$circle_id);
		}
	}
if ( ! function_exists('sb_shared')){
	function sb_shared($sb_id,$circle_id){
		$CI =& get_instance();
		$CI->load->model('storyboard_model', 'storyboards');
		return $CI->storyboards->sb_shared($sb_id,$circle_id);
		}
	}
if ( ! function_exists('card_shared_circle_list')){
	function card_shared_circle_list($card_id){
		$CI =& get_instance();
		$CI->load->model('cards_model', 'cards');
		return $CI->cards->card_shared_circle_list($card_id);
		}
	}