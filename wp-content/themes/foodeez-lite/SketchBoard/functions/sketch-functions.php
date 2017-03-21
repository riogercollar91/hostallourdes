<?php

/***************** EXCERPT LENGTH ************/
function foodeez_lite_excerpt_length($length) {
	return 50;
}
add_filter('excerpt_length', 'foodeez_lite_excerpt_length');

/***************** READ MORE ****************/
function foodeez_lite_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'foodeez_lite_excerpt_more');

/************* CUSTOM PAGE TITLE ***********/
add_filter( 'wp_title', 'foodeez_lite_title' );
function foodeez_lite_title($title)
{
	$foodeez_lite_title = $title;
	if ( is_home() && !is_front_page() ) {
		$foodeez_lite_title .= get_bloginfo('name');
	}
	if ( is_front_page() ){
		$foodeez_lite_title .=  get_bloginfo('name');
		$foodeez_lite_title .= ' | '; 
		$foodeez_lite_title .= get_bloginfo('description');
	}
	if ( is_search() ) {
		$foodeez_lite_title .=  get_bloginfo('name');
	}
	if ( is_author() ) { 
		global $wp_query;
		$curauth = $wp_query->get_queried_object();	
		$foodeez_lite_title .= __('Author: ','foodeez-lite');
		$foodeez_lite_title .= $curauth->display_name;
		$foodeez_lite_title .= ' | ';
		$foodeez_lite_title .= get_bloginfo('name');
	}
	if ( is_single() ) {
		$foodeez_lite_title .= get_bloginfo('name');
	}
	if ( is_page() && !is_front_page() ) {
		$foodeez_lite_title .= get_bloginfo('name');
	}
	if ( is_category() ) {
		$foodeez_lite_title .= get_bloginfo('name');
	}
	if ( is_year() ) { 
		$foodeez_lite_title .= get_bloginfo('name');
	}
	if ( is_month() ) {
		$foodeez_lite_title .= get_bloginfo('name');
	}
	if ( is_day() ) {
		$foodeez_lite_title .= get_bloginfo('name');
	}
	if (function_exists('is_tag')) { 
		if ( is_tag() ) {
			$foodeez_lite_title .= get_bloginfo('name');
		}
		if ( is_404() ) {
			$foodeez_lite_title .= get_bloginfo('name');
		}					
	}
	return $foodeez_lite_title;
}


/*********************************************
*   LIMIT WORDS
*********************************************/

function foodeez_lite_slider_limit_words($string, $word_limit) {
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
}