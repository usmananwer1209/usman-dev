<?php

abstract class user_circle_status{
    const not_fount = 0;
    const request_wait = 1;
    const request_accept = 2;
    const request_reject = 3;
	}

if ( ! function_exists('enum_user_circle_status')){
	function enum_user_circle_status($str){
		if((int)$str == "0")
			return user_circle_status::not_fount;
		elseif((int)$str == "1")
			return user_circle_status::request_wait;
		elseif((int)$str == "2")
			return user_circle_status::request_accept;
		elseif((int)$str == "3")
			return user_circle_status::request_reject;
		}
	}	



abstract class cards_type{
    const explore = 0;
    const rank = 1;
    const map = 2;
    const tree = 3;
	}

if ( ! function_exists('enum_user_circle_status')){
	function enum_user_circle_status($str){
		if((int)$str == "0")
			return cards_type::explore;
		elseif((int)$str == "1")
			return cards_type::rank;
		elseif((int)$str == "2")
			return cards_type::map;
		elseif((int)$str == "3")
			return cards_type::tree;
		}
	}	
