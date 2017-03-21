<?php

function foodeez_lite_customize_register( $wp_customize ) {

	// define image directory path
	$imagepath =  get_template_directory_uri() . '/images/';

	// Do stuff with $wp_customize, the WP_Customize_Manager object.
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->remove_control('header_textcolor');

	// ====================================
	// = Foodeez Lite Theme Pannel
	// ====================================
	$wp_customize->add_panel( 'home_settings_panel', array(
		'title' => __( 'Home Page Settings', 'foodeez-lite'),
		'priority' => 10,
		'active_callback' => 'is_front_page'
	) );

	$wp_customize->add_panel( 'header_settings_panel', array(
		'title' => __( 'Header Settings', 'foodeez-lite'),
		'priority' => 11,
	) );

	// ====================================
	// = Foodeez Lite Theme Sections
	// ====================================
	// Home Page
	$wp_customize->add_section( 'home_featured_section' , array(
		'title' 		=> __('Featured Box Settings','foodeez-lite'),
		'panel'	 => 'home_settings_panel'
	) );
	$wp_customize->add_section( 'home_parallax_section' , array(
		'title' 		=> __('Parallax Settings','foodeez-lite'),
		'panel'	 => 'home_settings_panel'
	) );

	// Header
	$wp_customize->add_section( 'topbar_contact_settings' , array(
		'title' => __('Top Bar Contact','foodeez-lite'),
		'panel' => 'header_settings_panel',
	) );
	$wp_customize->add_section( 'reservation_settings' , array(
		'title' => __('Reservation Link','foodeez-lite'),
		'panel' => 'header_settings_panel',
	) );
	$wp_customize->add_section( 'social_links' , array(
		'title' => __('Social Links','foodeez-lite'),
		'panel' => 'header_settings_panel',
	) );

	// Blog Page
	$wp_customize->add_section( 'blog_page_settings' , array(
		'title' => __('Blog Page Settings','foodeez-lite'),
	) );

	// Footer
	$wp_customize->add_section( 'footer_settings' , array(
		'title' => __('Footer Settings','foodeez-lite'),
	) );

	// ====================================
	// = Theme Color - Colors
	// ====================================
	$wp_customize->add_setting( '_colorpicker', array(
		'default'           => '#7fbf00' ,
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, '_colorpicker', array(
		'priority' => 1,
		'label'       => __( 'Set color scheme', 'foodeez-lite' ),
		'section'     => 'colors',
	) ) );

	// ====================================
	// = Theme Logo - Site Identity
	// ====================================
	$wp_customize->add_setting( '_logo_img', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, '_logo_img', array(
		'priority' => 1,
		'label' => __( 'Logo Image', 'foodeez-lite' ),
		'section' => 'title_tagline',
		'mime_type' => 'image',
	) ) );
	
	$wp_customize->add_setting( '_innerheader_stype', array(
		'default'           => $imagepath.'Foodies-Restaurant-WordPress-Theme-Lite-blog-title-img-2.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, '_innerheader_stype', array(
		'label' => __( 'Inner Page Header Background', 'foodeez-lite' ),
		'section' => 'header_settings',
		'mime_type' => 'image',
	) ) );

	// ====================================
	// = Top Bar Settings Sections
	// ====================================
	// Contact Number
	$wp_customize->add_setting( '_topbar_contact', array(
		'default'        => '+123-456-789',
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_topbar_contact', array(
		'section'  		=> 'topbar_contact_settings',
		'label' => __('Top Bar Contact no.','foodeez-lite'),
	));

	// Reservation Link
	$wp_customize->add_setting( '_rbtn_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_rbtn_link', array(
		'type'     		=> 'url',
		'section'  		=> 'reservation_settings',
		'label'    		=> __( 'Reservation Button Link', 'foodeez-lite' ),
	) );

	// Facebook
	$wp_customize->add_setting( '_fbook_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_fbook_link', array(
		'type'     		=> 'url',
		'section'  		=> 'social_links',
		'label'    		=> __( 'Facebook URL', 'foodeez-lite' ),
	) );
	// Twitter
	$wp_customize->add_setting( '_twitter_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_twitter_link', array(
		'type'     		=> 'url',
		'section'  		=> 'social_links',
		'label'    		=> __( 'Twitter URL', 'foodeez-lite' ),
	) );
	// Goggle+
	$wp_customize->add_setting( '_gplus_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_gplus_link', array(
		'type'     		=> 'url',
		'section'  		=> 'social_links',
		'label'    		=> __( 'Google+ URL', 'foodeez-lite' ),
	) );
	// LinkedIn
	$wp_customize->add_setting( '_linkedin_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_linkedin_link', array(
		'type'     		=> 'url',
		'section'  		=> 'social_links',
		'label'    		=> __( 'LinkedIn URL', 'foodeez-lite' ),
	) );
	// Pinterest
	$wp_customize->add_setting( '_pinterest_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_pinterest_link', array(
		'type'     		=> 'url',
		'section'  		=> 'social_links',
		'label'    		=> __( 'Pinterest URL', 'foodeez-lite' ),
	) );
	// Flickr
	$wp_customize->add_setting( '_flickr_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_flickr_link', array(
		'type'     		=> 'url',
		'section'  		=> 'social_links',
		'label'    		=> __( 'Flickr URL', 'foodeez-lite' ),
	) );
	// Foursquare
	$wp_customize->add_setting( '_foursquare_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_foursquare_link', array(
		'type'     		=> 'url',
		'section'  		=> 'social_links',
		'label'    		=> __( 'Foursquare URL', 'foodeez-lite' ),
	) );
	// YouTube
	$wp_customize->add_setting( '_youtube_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( '_youtube_link', array(
		'type'     		=> 'url',
		'section'  		=> 'social_links',
		'label'    		=> __( 'YouTube URL', 'foodeez-lite' ),
	) );
	

	// ====================================
	// = Home Featured Settings Sections
	// ====================================
	// Fist Feature Section
	$wp_customize->add_setting( '_fb1_first_part_heading', array(
		'default'        => __('Five Course Meal', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_fb1_first_part_heading', array(
		'section' => 'home_featured_section',
		'label' => __('First Featured Box Heading','foodeez-lite'),
	));
	$wp_customize->add_setting( '_fb1_first_part_image', array(
		'default'           => get_template_directory_uri().'/images/celebration-315079_1280.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, '_fb1_first_part_image', array(
		'label' => __( 'First Featured Box Image', 'foodeez-lite' ),
		'description' => __('Recomended size : 270x150 px', 'foodeez-lite'),
		'section' => 'home_featured_section',
		'mime_type' => 'image',
	) ) );
	$wp_customize->add_setting( '_fb1_first_part_content', array(
		'default'        => __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_fb1_first_part_content', array(
		'type'	=> 'textarea',
		'section' => 'home_featured_section',
		'label' => __('First Featured Box Content','foodeez-lite'),
	));
	$wp_customize->add_setting( '_fb1_first_part_link', array(
		'default'        => '#',
		'sanitize_callback' => 'esc_url_raw',
	));
	$wp_customize->add_control('_fb1_first_part_link', array(
		'type' => 'url',
		'section' => 'home_featured_section',
		'label' => __('First Featured Box Link','foodeez-lite'),
	));
	// Second Feature Section
	$wp_customize->add_setting( '_fb2_second_part_heading', array(
		'default'        =>  __('Candle Light Dinners', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_fb2_second_part_heading', array(
		'section' => 'home_featured_section',
		'label' => __('Second Featured Box Heading','foodeez-lite'),
	));
	$wp_customize->add_setting( '_fb2_second_part_image', array(
		'default'           => get_template_directory_uri().'/images/pan-seared-salmon-belly-250866_1920.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, '_fb2_second_part_image', array(
		'label' => __( 'Second Featured Box Image', 'foodeez-lite' ),
		'description' => __('Recomended size : 270x150 px', 'foodeez-lite'),
		'section' => 'home_featured_section',
		'mime_type' => 'image',
	) ) );
	$wp_customize->add_setting( '_fb2_second_part_content', array(
		'default'        => __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_fb2_second_part_content', array(
		'type'	=> 'textarea',
		'section' => 'home_featured_section',
		'label' => __('Second Featured Box Content','foodeez-lite'),
	));
	$wp_customize->add_setting( '_fb2_second_part_link', array(
		'default'        => '#',
		'sanitize_callback' => 'esc_url_raw',
	));
	$wp_customize->add_control('_fb2_second_part_link', array(
		'type' => 'url',
		'section' => 'home_featured_section',
		'label' => __('Second Featured Box Link','foodeez-lite'),
	));
	// Third Feature Section
	$wp_customize->add_setting( '_fb3_third_part_heading', array(
		'default'        => __('Delightful Desserts', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_fb3_third_part_heading', array(
		'section' => 'home_featured_section',
		'label' => __('Third Featured Box Heading','foodeez-lite'),
	));
	$wp_customize->add_setting( '_fb3_third_part_image', array(
		'default'           => get_template_directory_uri().'/images/pasta-250872_1920.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, '_fb3_third_part_image', array(
		'label' => __( 'Third Featured Box Image', 'foodeez-lite' ),
		'description' => __('Recomended size : 270x150 px', 'foodeez-lite'),
		'section' => 'home_featured_section',
		'mime_type' => 'image',
	) ) );
	$wp_customize->add_setting( '_fb3_third_part_content', array(
		'default'        => __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_fb3_third_part_content', array(
		'type'	=> 'textarea',
		'section' => 'home_featured_section',
		'label' => __('Third Featured Box Content','foodeez-lite'),
	));
	$wp_customize->add_setting( '_fb3_third_part_link', array(
		'default'        => '#',
		'sanitize_callback' => 'esc_url_raw',
	));
	$wp_customize->add_control('_fb3_third_part_link', array(
		'type' => 'url',
		'section' => 'home_featured_section',
		'label' => __('Third Featured Box Link','foodeez-lite'),
	));
	// Fourth Feature Section
	$wp_customize->add_setting( '_fb4_fourth_part_heading', array(
		'default'        => __('Finest Wine Collection', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_fb4_fourth_part_heading', array(
		'section' => 'home_featured_section',
		'label' => __('Fourth Featured Box Heading','foodeez-lite'),
	));
	$wp_customize->add_setting( '_fb4_fourth_part_image', array(
		'default'           => get_template_directory_uri().'/images/spaghetti-237907_1920.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, '_fb4_fourth_part_image', array(
		'label' => __( 'Fourth Featured Box Image', 'foodeez-lite' ),
		'description' => __('Recomended size : 270x150 px', 'foodeez-lite'),
		'section' => 'home_featured_section',
		'mime_type' => 'image',
	) ) );
	$wp_customize->add_setting( '_fb4_fourth_part_content', array(
		'default'        => __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_fb4_fourth_part_content', array(
		'type'	=> 'textarea',
		'section' => 'home_featured_section',
		'label' => __('Fourth Featured Box Content','foodeez-lite'),
	));
	$wp_customize->add_setting( '_fb4_fourth_part_link', array(
		'default'        => '#',
		'sanitize_callback' => 'esc_url_raw',
	));
	$wp_customize->add_control('_fb4_fourth_part_link', array(
		'type' => 'url',
		'section' => 'home_featured_section',
		'label' => __('Fourth Featured Box Link','foodeez-lite'),
	));
	
	// ====================================
	// = Home Parallax Settings Sections
	// ====================================
	// Parallax Section
	$wp_customize->add_setting( '_fullparallax_image', array(
		'default'           => $imagepath.'Foodies-Restaurant-WordPress-Theme-Lite-parallax-image-2.png',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, '_fullparallax_image', array(
		'section' 		=> 'home_parallax_section',
		'label' 		=> __( 'Parallax Image', 'foodeez-lite' ),
		'description' 	=> __('Recomended size min. 1600x750 px.', 'foodeez-lite' ),
	) ) );
	$wp_customize->add_setting( '_para_content_left', array(
		'default'        => '<h2 class="skt-parallax-heading heading center">'. __('DISH OF THE DAY','foodeez-lite').'</h2><div class="skt-parallax-imgwrap"><a href="'.get_template_directory_uri().'/images/Foodies-Restaurant-WordPress-Theme-Lite-parallax-image-2.png"><img src="'.get_template_directory_uri().'/images/Foodies-Restaurant-WordPress-Theme-Lite-parallax-image-2.png" alt="Foodies-Restaurant-WordPress-Theme-Lite" width="1000" height="425" class="alignnone size-full wp-image-1714" /></a></div><div class="skt-parallax-contentwrap">'. __('Phosfluorescently matrix adaptive interfaces rather than out-of-the-box intellectual capital. Interactively generate timely e-commerce rather than multimedia based vortals. Objectively restore cooperative scenarios and interactive alignments. Uniquely maximize fully researched technology rather than seamless relationships. Conveniently empower extensive customer service and ethical supply chains.Phosfluorescently matrix adaptive interfaces rather than out-of-the-box intellectual capital. Interactively generate timely e-commerce rather than multimedia based vortals. Objectively restore cooperative scenarios and interactive alignments. Uniquely maximize fully researched technology rather than seamless relationships. Conveniently empower extensive customer service and ethical supply chains.','foodeez-lite').'</div>',
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
	));
	$wp_customize->add_control('_para_content_left', array(
		'type'	=> 'textarea',
		'section' => 'home_parallax_section',
		'label' => __('Parallax Section Content','foodeez-lite'),
	));

	// ====================================
	// = Blog Page Settings Sections
	// ====================================
	$wp_customize->add_setting( '_blogpage_heading', array(
		'default'        => __('Blog', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
		'transport' => 'postMessage',
	));
	$wp_customize->add_control('_blogpage_heading', array(
		'label' => __('Blog Page Title','foodeez-lite'),
		'section' => 'blog_page_settings',
	));

	// ====================================
	// = Footer Settings Sections
	// ====================================
	$wp_customize->add_setting( '_copyright', array(
		'default'        => __('Proudly Powered by WordPress', 'foodeez-lite'),
		'sanitize_callback' => 'foodeez_lite_sanitize_textarea',
		'transport' => 'postMessage',
	));
	$wp_customize->add_control('_copyright', array(
		'label' => __('Copyright Text','foodeez-lite'),
		'section' => 'footer_settings',
	));
}
add_action( 'customize_register', 'foodeez_lite_customize_register' );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Twenty Fifteen 1.0
 */
function foodeez_lite_customize_preview_js() {
	wp_enqueue_script( 'foodeez-lite-customizer-js', get_template_directory_uri() . '/js/foodeez-lite-customizer.js', array( 'customize-preview' ), '20141216', true );
}
add_action( 'customize_preview_init', 'foodeez_lite_customize_preview_js' );


// sanitize textarea
function foodeez_lite_sanitize_textarea( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function foodeez_lite_sanitize_on_off( $input ) {
	$valid = array(
		'on' =>'ON',
		'off'=> 'OFF'
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

?>