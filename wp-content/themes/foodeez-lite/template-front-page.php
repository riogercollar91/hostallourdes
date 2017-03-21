<?php get_header(); ?>

<!-- FEATURED BOXES SECTION -->
<?php include("includes/front-featured-boxes-section.php"); ?>

<!-- AWESOME PARALLAX SECTION -->
<?php include("includes/front-parallax-section.php"); ?>

<!-- PAGE EDITER CONTENT -->
<?php if(have_posts()) : ?>
	<?php while(have_posts()) : the_post(); ?>

<?php 

echo do_shortcode('[wpgmza id="1"]'); 

?> 

<!-- <img src="/wp-content/uploads/mapa-google.png" alt=""> -->


		<div id="front-content-box" class="skt-section">
			<div class="container">
				 <div class="row-fluid"> 
						<?php the_content(); ?>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>