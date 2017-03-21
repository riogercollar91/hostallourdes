<?php 
/*
Template Name: Page Left Sidebar Template
*/

get_header(); ?>

<div class="main-wrapper-item"> 
	<?php if(have_posts()) : ?>
	<?php while(have_posts()) : the_post(); ?>
	<div class="bread-title-holder">
		<div class="bread-title-bg-image full-bg-breadimage-fixed"></div>
		<div class="container">
			<div class="row-fluid">
				<div class="container_inner clearfix">
					<h1 class="title"><?php the_title(); ?></h1>
					<?php if ((class_exists('foodeez_breadcrumb_class'))) {$foodeez_breadcumb->custom_breadcrumb();} ?>
				</div>
			</div>
		</div>
	</div>		

	<div class="page-content left-sidebar">
		<div class="container post-wrap">
			<div class="row-fluid">
			<div id="content" class="span8">
				<div class="post" id="post-<?php the_ID(); ?>">
					<div class="skepost">
						<?php the_content(); ?>
						<?php wp_link_pages( array('before' => '<p><strong>'.__('Pages :','foodeez-lite').'</strong>','after' => '</p>', __('number','foodeez-lite') ) ); ?>
						<?php edit_post_link( __('Edit','foodeez-lite'), '', ''); ?>
					</div><!-- skepost --> 
				</div>
				<!-- post -->
				<?php endwhile; ?>
				<?php else :  ?>
				<div class="post">
					<h2>
						<?php _e('Page Does Not Exist','foodeez-lite'); ?>
					</h2>
				</div>
				<?php endif; ?>
				<div class="clearfix"></div>
			</div><!-- content -->

			<!-- Sidebar -->
			<div id="sidebar" class="span4">
				<?php get_sidebar('page'); ?>
			</div>
			<!-- Sidebar --> 
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>