<?php
	$bg_color = esc_attr( get_theme_mod('_colorpicker', '#7fbf00') );
	$fullparallax_image = esc_url( get_theme_mod('_fullparallax_image', get_template_directory_uri().'/images/Foodies-Restaurant-WordPress-Theme-Lite-parallax-image-2.png">' ) );
	$innerheader_image = esc_url( get_theme_mod('_innerheader_stype', get_template_directory_uri().'/images/Foodies-Restaurant-WordPress-Theme-Lite-blog-title-img-2.jpg') );

?>
<style type="text/css">


	/***************** THEME *****************/
	
  	 a.skt-featured-icons,.service-icon{ background: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;}
	 a.skt-featured-icons:after,.service-icon:after {border-top-color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>; }
	 a.skt-featured-icons:before,.service-icon:before {border-bottom-color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>; }

	
	.sticky-post {color : <?php if(isset($bg_color)){ echo $bg_color; } ?>;border-color:<?php if(isset($bdrrgbcolor)){ echo $bdrrgbcolor; } else { echo '#7fbf00'; } ?>}
	#footer{ border-color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>; }
	.social li a:hover{background: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;}
	.social li a:hover:before{color:#fff; }
	a#backtop,#respond input[type="submit"],.skt-ctabox div.skt-ctabox-button a:hover,.widget_tag_cloud a:hover,.continue a,blockquote,.skt-quote,#foodeez-paginate .foodeez-current,#foodeez-paginate a:hover,.postformat-gallerydirection-nav li a:hover,#wp-calendar,.comments-template .reply a,#commentsbox .reply a,#content .contact-left form input[type="submit"]:hover,.skt-parallax-button:hover,.sktmenu-toggle,#footer .tagcloud a:hover,form.wpcf7-form input[type="submit"]  {background-color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>; }
	.skt-ctabox div.skt-ctabox-button a,#portfolio-division-box .readmore,.teammember,.slider-link a,.ske_tab_v ul.ske_tabs li.active,.ske_tab_h ul.ske_tabs li.active,#content .contact-left form input[type="submit"],.filter a,.skt-parallax-button,#foodeez-paginate a:hover,#foodeez-paginate .foodeez-current,form.wpcf7-form input[type="text"]:focus,form.wpcf7-form input[type="email"]:focus,
	form.wpcf7-form input[type="url"]:focus,form.wpcf7-form input[type="tel"]:focus,
	form.wpcf7-form input[type="number"]:focus,form.wpcf7-form input[type="range"]:focus,
	form.wpcf7-form input[type="date"]:focus,form.wpcf7-form input[type="file"]:focus,form.wpcf7-form textarea:focus{border-color:<?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;}
	.clients-items li a:hover{border-bottom-color:<?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;}
	a,.ske-footer-container ul li:hover:before,.ske-footer-container ul li:hover > a,.ske_widget ul ul li:hover:before,.ske_widget ul ul li:hover,.ske_widget ul ul li:hover a,.title a ,.skepost-meta a:hover,.post-tags a:hover,.entry-title a:hover ,.readmore a:hover,#Site-map .sitemap-rows ul li a:hover ,.childpages li a,#Site-map .sitemap-rows .title,.ske_widget a,.ske_widget a:hover,#Site-map .sitemap-rows ul li:hover,#footer .third_wrapper a:hover,.ske-title,#content .contact-left form input[type="submit"],.filter a,span.team_name,.reply a, a.comment-edit-link,.skt_price_table .price_in_table .value, .teammember strong .team_name,#content .skt-service-page .one_third:hover .service-box-text h3,.ad-service:hover .service-box-text h3,.mid-box-mid .mid-box:hover .iconbox-content h4,.error-txt,.skt-ctabox .skt-ctabox-content h2,.reply a:hover, a.comment-edit-link:hover,.skepost-meta i,.topbar_info i, .topbar_info .head-phone-txt {color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;text-decoration: none;}
	.single #content .title,#content .post-heading,.childpages li ,.fullwidth-heading,.comment-meta a:hover,#respond .required, #wp-calendar tbody a{color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;} 

	*::-moz-selection{background: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;color:#fff;}
	::selection {background: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;color:#fff;}
	#skenav ul li.current_page_item > a,
	#skenav ul li.current-menu-ancestor > a,
	#skenav ul li.current-menu-item > a,
	#skenav ul li.current-menu-parent > a,#skenav ul li.current_page_ancestor > a {background-color:<?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;color:#fff;}
	#skenav ul ul li a:hover{background-color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;color:#fff;}
	.sticky-post { border-color: <?php if(isset($bg_color)){ echo $bg_color; } ?>;  }
	#searchform input[type="submit"]{ background: none repeat scroll 0 0 <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;  }

	.col-one .box .title, .col-two .box .title, .col-three .box .title, .col-four .box .title {color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?> !important;  }
	.full-bg-breadimage-fixed {}
	#full-division-box { background-image: url("<?php if(isset($fullparallax_image)){ echo $fullparallax_image; } else { echo get_template_directory_uri().'/images/french-fries-226773_1280.jpg'; } ?>"); }
	.footer-top-border {border: 2px solid <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;}
	.front-page #wrapper{background: none repeat scroll 0 0 rgba(0, 0, 0, 0); }
	#wrapper{background:url("<?php if(isset($innerheader_image)){ echo $innerheader_image; } else { echo get_template_directory_uri().'/images/Foodies-Restaurant-WordPress-Theme-Lite-blog-title-img-2.jpg'; } ?>") no-repeat scroll 0 0 transparent;-webkit-background-size: contain;-moz-background-size: contain ;-o-background-size: contain ; background-size: contain ; }
	/***************** Navigation *****************/

	#skenav li a:hover,#skenav .sfHover { background-color:#333333;color: #FFFFFF;}
	#skenav .sfHover a { color: #FFFFFF;}
	#skenav ul ul li { background: none repeat scroll 0 0 #333333; color: #FFFFFF; }
	#skenav ul ul li { background: none repeat scroll 0 0 #333333; color: #FFFFFF; }
	#skenav .ske-menu #menu-secondary-menu li a:hover, #skenav .ske-menu #menu-secondary-menu .current-menu-item a{color: #71C1F2;  }
	.footer-seperator{background-color: rgba(0,0,0,.2);}
	#skenav .ske-menu #menu-secondary-menu li .sub-menu li {	margin: 0;  }

	.bread-title-holder h1.title,.cont_nav_inner span,.bread-title-holder .cont_nav_inner p{
		color: <?php if(isset($bg_color)){ echo $bg_color; } else { echo '#7fbf00'; } ?>;
	}
	.skehead-headernav .logo {
		height: 40px;
		width: 156px;
	}
	@media only screen and (max-width : 1025px) {
		#menu-main {
			display:none;
		}

		.skehead-headernav .logo {
		    margin-bottom: 3px;
		    margin-top: 12px;
		    position: relative;
		}

		.skehead-headernav.skehead-headernav-shrink .logo {
            margin-top: 1px;
            top: 6px;
		}

	}
</style>