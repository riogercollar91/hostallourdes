<?php
/**
 * The Header for our theme.
 * @package WordPress
 * @SketchThemes
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
	<div id="wrapper" class="skepage">
		
<div class="slider-top clearfix">
		<div class="header-topbar clearfix" >
			<div class="container">      
					<div class="row-fluid">   
						<!-- #logo -->
						<div id="logo" class="span3">
							<?php if( get_theme_mod('_logo_img', '') != '' ){ ?>
								<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>" ><img class="logo" src="<?php echo esc_url( get_theme_mod('_logo_img') ); ?>" alt="<?php bloginfo('name'); ?>" /></a>
							<?php } elseif ( display_header_text() ) { ?>
							<!-- #description -->
							<div id="site-title" class="logo_desp">
								<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name') ?>" ><?php bloginfo('name'); ?></a> 
								<div id="site-description"><?php bloginfo( 'description' ); ?></div>
							</div>
							<!-- #description -->
							<?php } ?>
						</div>
						<!-- #logo -->
						<div class="span4">
							<!-- Top Contact Info -->
							<div class="topbar_info">
								<?php if( get_theme_mod('_topbar_contact', '+123-456-789') != '' ) { ?><i class="fa fa-phone"></i><span class="head-phone-txt"><?php _e('Call Us&nbsp;:','foodeez-lite'); ?></span>&nbsp;<span class="head-phone"><a href="tel:<?php echo esc_attr( get_theme_mod('_topbar_contact', '+123-456-789') ); ?>"><?php echo esc_attr( get_theme_mod('_topbar_contact', '+123-456-789') ); ?></a></span><?php } ?>
							</div>
							<!-- Top Contact Info -->
						</div>
						<div class="span5">


							<!-- Social Links Section -->
							<div class="social_icon">
								<ul class="clearfix">
									<?php if( get_theme_mod('_fbook_link', '#') != '' ){ ?><li class="fb-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod('_fbook_link', '#') ); ?>"><span class="fa fa-facebook" title="Facebook"></span></a></li><?php } ?>
									<?php if( get_theme_mod('_twitter_link', '#') != '' ){ ?><li class="tw-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod('_twitter_link', '#') ); ?>"><span class="fa fa-twitter" title="Twitter"></span></a></li><?php } ?>
									<?php if( get_theme_mod('_gplus_link', '#') != '' ){ ?><li class="gplus-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod('_gplus_link', '#') ); ?>"><span class="fa fa-google-plus" title="Google Plus"></span></a></li><?php } ?>
									<?php if( get_theme_mod('_pinterest_link', '#') != '' ){ ?><li class="pinterest-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod('_pinterest_link', '#') ); ?>"><span class="fa fa-pinterest" title="Pinterest"></span></a></li><?php } ?>
									<?php if( get_theme_mod('_linkedin_link', '#') != '' ){ ?><li class="linkedin-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod('_linkedin_link', '#') ); ?>"><span class="fa fa-linkedin" title="Linkedin"></span></a></li><?php } ?>
									<?php if( get_theme_mod('_foursquare_link', '#') != '' ){ ?><li class="foursquare-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod('_foursquare_link', '#') ); ?>"><span class="fa fa-foursquare" title="Foursquare"></span></a></li><?php } ?>
									<?php if( get_theme_mod('_flickr_link', '#') != '' ){ ?><li class="flickr-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod('_flickr_link', '#') ); ?>"><span class="fa fa-flickr" title="Flickr"></span></a></li><?php } ?>
									<?php if( get_theme_mod('_youtube_link', '#') != '' ){ ?><li class="youtube-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod('_youtube_link', '#') ); ?>"><span class="fa fa-youtube-play" title="Youtube"></span></a></li><?php } ?>
									<li><a href="javascript:void(0);" class="strip-icon search-strip" title="search"><i class="fa fa-search"></i></a></li>
								</ul>

								<div class="idioma">
								<?php 
								function qtrans_generateLanguageSelectCode($style='', $id=''){ return qtranxf_generateLanguageSelectCode($style,$id); }
							     echo qtrans_generateLanguageSelectCode('image'); 
							     ?>
								</div>	
							</div>

							
							<!-- Social Links Section -->
						</div>
					</div>
			</div>					
		</div><!-- header-topbar -->
		<!-- search-strip -->
		<div class="hsearch" >
			<div class="container">
					<div class="row-fluid">
						<div class="skt-s-form">
							<form method="get" id="header-searchform" action="<?php echo esc_url(home_url('/')); ?>">
								<fieldset>
									<input type="text" value="" placeholder="Search Here ..." id="s" name="s">
									<input type="submit" value="Search" id="header-searchsubmit">
								</fieldset>
							</form>
							<div class="hsearch-close"><i class="fa fa-times"></i></div>
						</div>
					</div>
			</div>
		</div>
		<div id="header" class="skehead-headernav clearfix">
				<div id="skehead">
					<div class="container">      
						<div class="row-fluid"> 
							<!-- navigation-->
							<div class="top-nav-menu span10">
							<?php 
								if( function_exists( 'has_nav_menu' ) && has_nav_menu( 'Header' ) ) {
									wp_nav_menu(array( 'container_class' => 'ske-menu', 'container_id' => 'skenav', 'menu_id' => 'menu-main','menu' => 'Primary Menu','theme_location' => 'Header' )); 
								} else {
								?>
								<div class="ske-menu" id="skenav">
									<ul id="menu-main" class="menu">
										<?php wp_list_pages('title_li=&depth=0'); ?>
									</ul>
								</div>
								<?php } ?>
							</div>
							<!-- #navigation --> 
							<?php if( get_theme_mod('_rbtn_link', '#') != '' ){ ?>
								<div class="span2">
									<a href="<?php echo esc_url( get_theme_mod('_rbtn_link', '#') ); ?>" class="res-button"><?php _e('Reserve Now','foodeez-lite'); ?></a>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<!-- #skehead -->
		</div>
<!-- #header -->
		<div class="header-clone"></div>
</div>		
<!-- header image section -->
  <?php $classes = get_body_class(); ?>
  <?php if(in_array('front-page',$classes)) {  include("includes/front-header-image-section.php");} ?>



<div id="main" class="clearfix">