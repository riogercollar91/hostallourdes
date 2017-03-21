<?php
/**
* The template for displaying the footer.
*
* Contains footer content and the closing of the
* #main and #page div elements.
*
*/
?>

<div class="footer-top-border"></div>
	<div class="clearfix"></div>
</div>
<!-- #main --> 

<!-- #footer -->
<div id="footer">
	<div class="container">
		<div class="row-fluid">
			<div class="second_wrapper">
				<?php dynamic_sidebar( 'footer-sidebar' ); ?>
				<div class="clearfix"></div>
			</div><!-- second_wrapper -->
		</div>
	</div>

	<div class="third_wrapper">
		<div class="container">
			<div class="row-fluid">
				<div class="copyright span6 alpha omega"> <?php echo wp_kses_post( get_theme_mod('_copyright', __('Copyright Text', 'foodeez-lite') ) ); ?> </div>
				<!-- <div class="owner span6 alpha omega"><?php _e('Foodeez Theme by','foodeez-lite'); ?> <a href="<?php echo esc_url('https://sketchthemes.com/'); ?>" ><?php _e('SketchThemes','foodeez-lite'); ?></a></div> -->
				<div class="clearfix"></div>
			</div>
		</div>
	</div><!-- third_wrapper --> 
</div>
<!-- #footer -->

</div>
<!-- #wrapper -->
	<a href="JavaScript:void(0);" title="<?php _e('Back To Top', 'foodeez-lite'); ?>" id="backtop"></a>
	<?php wp_footer(); ?>
</body>
</html>