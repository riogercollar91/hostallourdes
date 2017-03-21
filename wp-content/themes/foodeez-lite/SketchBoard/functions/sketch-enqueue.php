<?php
/***********************************************
*  ENQUQUE CSS AND JAVASCRIPT
************************************************/
//ENQUEUE JQUERY 
function foodeez_lite_script_enqueqe() {
	if(!is_admin()) {
		wp_enqueue_script('foodeez_lite_componentssimple_slide', get_template_directory_uri() .'/js/custom.js',array('jquery'),'1.0',1 );
		wp_enqueue_script('comment-reply');
	}
}
add_action('init', 'foodeez_lite_script_enqueqe');

//ENQUEUE FRONT SCRIPTS
function foodeez_lite_theme_stylesheet()
{
	$theme = wp_get_theme();

	//ENQUEUE STYLE FOR IE BROWSER
	global $is_IE;
	if($is_IE ) {
		wp_enqueue_style( 'foodeez-lite-ie-style', get_template_directory_uri().'/css/ie-style.css', false, $theme->Version );
		wp_enqueue_style( 'foodeez-lite-ie-awesome-stylesheet', get_template_directory_uri().'/css/font-awesome-ie7.css', false, $theme->Version );
	}

	wp_enqueue_script('hoverIntent');
	wp_enqueue_script('foodeez-lite-superfish', get_template_directory_uri().'/js/superfish.js',array('jquery'),true,'1.0');
	wp_enqueue_script('foodeez-lite-AnimatedHeader', get_template_directory_uri().'/js/cbpAnimatedHeader.js',array('jquery'),true,'1.0');
	wp_enqueue_script('foodeez-lite-easing_slide',get_template_directory_uri().'/js/jquery.easing.1.3.js',array('jquery'),'1.0',true);
	wp_enqueue_script('foodeez-lite-waypoints',get_template_directory_uri().'/js/waypoints.min.js',array('jquery'),'1.0',true );
	
	wp_enqueue_style('foodeez-lite-style', get_stylesheet_uri());
	wp_enqueue_style('foodeez-lite-animation-stylesheet', get_template_directory_uri().'/css/skt-animation.css', false, $theme->Version);
	wp_enqueue_style('foodeez-lite-awesome-stylesheet', get_template_directory_uri().'/css/font-awesome.css', false, $theme->Version);

	/*SUPERFISH*/
	wp_enqueue_style( 'foodeez-lite-superfish-stylesheet', get_template_directory_uri().'/css/superfish.css', false, $theme->Version);
	wp_enqueue_style( 'foodeez-lite-bootstrap-stylesheet', get_template_directory_uri().'/css/bootstrap-responsive.css', false, $theme->Version);
	
	/*GOOGLE FONTS*/
	wp_enqueue_style( 'googleFontsDancing','//fonts.googleapis.com/css?family=Dancing+Script', false, $theme->Version);
	wp_enqueue_style( 'googleFontsMuli','//fonts.googleapis.com/css?family=Muli', false, $theme->Version);
}
add_action('wp_enqueue_scripts', 'foodeez_lite_theme_stylesheet');

function foodeez_lite_head() {

	if(!is_admin()) {
		require_once(get_template_directory().'/includes/foodeez-custom-css.php');
	}
 
}
add_action('wp_head', 'foodeez_lite_head');