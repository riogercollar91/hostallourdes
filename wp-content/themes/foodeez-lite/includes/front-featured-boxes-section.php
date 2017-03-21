<?php global $foodeez_lite_shortname; ?>

<div id="featured-box" class="skt-section">
	<div class="container">
		<div class="mid-box-mid row-fluid"> 
			<!-- Featured Box 1 -->
			<div class="mid-box span3 fade_in_hide element_fade_in">
				<div class="skt-iconbox iconbox-top">		
					<div class="iconbox-icon skt-animated small-to-large skt-viewport">	
							<a class="skt-featured-images" href="<?php echo esc_url( get_theme_mod('_fb1_first_part_link', '#') ); ?>" title="<?php echo esc_attr( get_theme_mod('_fb1_first_part_heading', __('Five Course Meal', 'foodeez-lite') ) ); ?>">
									<img class="skin-bg" src="<?php echo esc_url( get_theme_mod('_fb1_first_part_image', get_template_directory_uri().'/images/celebration-315079_1280.jpg') );  ?>" alt="boximg"/>	  
							</a>
					</div>		
					<div class="iconbox-content">		
						<h4><?php echo esc_attr( get_theme_mod('_fb1_first_part_heading', __('Five Course Meal', 'foodeez-lite') ) ); ?></h4>			
						<p><?php echo do_shortcode( wp_kses_post( get_theme_mod('_fb1_first_part_content', __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'foodeez-lite') ) ) ); ?></p>		
					</div>			
					<div class="clearfix"></div>	
				</div>
			</div>
			<!-- Featured Box 2 -->
			<div class="mid-box span3 fade_in_hide element_fade_in" >
				<div class="skt-iconbox iconbox-top">
					<div class="iconbox-icon skt-animated small-to-large skt-viewport">						  
						<a class="skt-featured-images" href="<?php echo esc_url( get_theme_mod('_fb2_second_part_link', '#') ); ?>" title="<?php echo esc_attr( get_theme_mod('_fb2_second_part_heading', __('Candle Light Dinners', 'foodeez-lite') ) ); ?>">
								<img class="skin-bg" src="<?php echo esc_url( get_theme_mod('_fb2_second_part_image', get_template_directory_uri().'/images/pan-seared-salmon-belly-250866_1920.jpg') );  ?>" alt="boximg"/>
						</a>	
					</div>		
					<div class="iconbox-content">		
						<h4><?php echo esc_attr( get_theme_mod('_fb2_second_part_heading', __('Candle Light Dinners', 'foodeez-lite') ) ); ?></h4>				
						<p><?php echo do_shortcode( wp_kses_post( get_theme_mod('_fb2_second_part_content', __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'foodeez-lite') ) ) ); ?></p>			
					</div>			
					<div class="clearfix"></div>	
				</div>
			</div>
			<!-- Featured Box 3 -->
			<div class="mid-box span3 fade_in_hide element_fade_in" >
				<div class="skt-iconbox iconbox-top">		
					<div class="iconbox-icon skt-animated small-to-large skt-viewport">			
						<a class="skt-featured-images" href="<?php echo esc_url( get_theme_mod('_fb3_third_part_link', '#') ); ?>" title="<?php echo esc_attr( get_theme_mod('_fb3_third_part_heading', __('Delightful Desserts', 'foodeez-lite') ) ); ?>">				
								<img class="skin-bg" src="<?php echo esc_url( get_theme_mod('_fb3_third_part_image', get_template_directory_uri().'/images/pasta-250872_1920.jpg') ); ?>" alt="boximg"/>
						</a>
					</div>			
					<div class="iconbox-content">			
						<h4><?php echo esc_attr( get_theme_mod('_fb3_third_part_heading', __('Delightful Desserts', 'foodeez-lite') ) ); ?></h4>				
						<p><?php echo do_shortcode( wp_kses_post( get_theme_mod('_fb3_third_part_content', __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'foodeez-lite') ) ) ); ?></p>		
					</div>		
					<div class="clearfix"></div>	
				</div>
			</div>
			<!-- Featured Box 4 -->
			<div class="mid-box span3 fade_in_hide element_fade_in">
				<div class="skt-iconbox iconbox-top">		
					<div class="iconbox-icon skt-animated small-to-large skt-viewport">	
							<a class="skt-featured-images" href="<?php echo esc_url( get_theme_mod('_fb4_fourth_part_link', '#') ); ?>" title="<?php echo esc_attr( get_theme_mod('_fb4_fourth_part_heading', __('Finest Wine Collection', 'foodeez-lite') ) ); ?>">
									<img class="skin-bg" src="<?php echo esc_url( get_theme_mod('_fb4_fourth_part_image', get_template_directory_uri().'/images/spaghetti-237907_1920.jpg') ); ?>" alt="boximg"/>	  
							</a>
					</div>		
					<div class="iconbox-content">		
						<h4><?php echo esc_attr( get_theme_mod('_fb4_fourth_part_heading', __('Finest Wine Collection', 'foodeez-lite') ) ); ?></h4>			
						<p><?php echo do_shortcode( wp_kses_post( get_theme_mod('_fb4_fourth_part_content', __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'foodeez-lite') ) ) ); ?></p>		
					</div>			
					<div class="clearfix"></div>	
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>