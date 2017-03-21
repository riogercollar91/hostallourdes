<?php if ( ! defined( 'ABSPATH' ) ) exit; //exits when accessed directly

define( 'MMTL_POST_EDITOR_ID', 'content' );

class MMTL_Editor
{
	private static $instance = null;

	static public function get_instance()
	{
		if ( ! self::$instance )
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{

	}

	public function init()
	{
		add_action( 'edit_form_after_title', array( $this, 'print_activation_buttons' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer', array( $this, 'print_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce_before_init' ), 99, 2 );
	}

	public function get_post_id()
	{
		if ( ! is_admin() )
		{
			return false;
		}

		if ( empty( $GLOBALS['post'] ) )
		{
			return false;
		}

		global $post;

		return $post->ID;
	}

	public function set_active_state( $active, $post_id )
	{
		if ( $active )
		{
			update_post_meta( $post_id, 'mmtl_active', true );
		}

		else
		{
			delete_post_meta( $post_id, 'mmtl_active' );
		}
	}

	public function is_editor_active( $post_id )
	{
		return get_post_meta( $post_id, 'mmtl_active', true ) ? true : false;
	}

	public function is_editor_screen()
	{
		if ( ! is_admin() )
		{
			return false;
		}

		// checks if current page is single post edit screen

		$pages = apply_filters( 'mmtl_editor_pages', array( 'post.php', 'post-new.php' ) );

		if ( ! is_array( $pages ) )
		{
			return false;
		}

		if ( empty( $GLOBALS['pagenow'] ) || ! in_array( $GLOBALS['pagenow'], $pages ) )
		{
			return false;
		}

		// gets post type

		if ( empty( $GLOBALS['typenow'] ) )
		{
			return false;
		}

		$post_type = $GLOBALS['typenow'];
			
		// checks if post type supports editor

		return post_type_supports( $post_type, 'editor' );
	}

	public function admin_body_class( $classes )
	{
		if ( $this->is_editor_screen() )
		{
			$classes .= 'mmtl';

			if ( ! empty( $_GET['post'] ) )
			{
				$post_id = $_GET['post'];
			}

			else
			{
				$post_id = 0;
			}

			if ( $this->is_editor_active( $post_id ) )
			{
				$classes .= ' mmtl-active';
			}

			else
			{
				$classes .= ' mmtl-inactive';
			}

			if ( MMTL_DEBUG )
			{
				$classes .= ' mmtl-debug';
			}
		}

		return $classes;
	}

	public function tiny_mce_before_init( $settings, $editor_id )
	{
		// makes sure auto paragraphs are disabled

		if ( $this->is_editor_screen() && $editor_id == MMTL_POST_EDITOR_ID )
		{
			$settings[ 'wpautop' ] = false;
		}

		return $settings;
	}

	public function print_activation_buttons()
	{
		if ( ! $this->is_editor_screen() )
		{
			return;
		}

		?>

		<p>
			<a href="#" class="button mmtl-activate"><?php esc_html_e( 'Table Layout Editor', 'table-layout' ); ?></a>
			<a href="#" class="button mmtl-deactivate"><?php esc_html_e( 'Default Editor', 'table-layout' ); ?></a>
		</p>

		<?php
	}

	public function print_scripts()
	{
		if ( ! $this->is_editor_screen() )
		{
			return;
		}

		?>

		<script type="text/html" id="tmpl-mmtl-main">
			<div class="mmtl-header">
				<h2 class="mmtl-title"><?php _e( 'Table Layout', 'table-layout' ); ?></h2>
				<span class="mmtl-loader mmtl-spin glyphicons glyphicons-refresh"></span>
				<# if ( data.controls ) { #><div class="mmtl-controls mmtl-header-controls">{{{ data.controls }}}</div><# } #>
			</div>
			<div class="mmtl-content"></div>
			<div class="mmtl-footer">
				<a class="mmtl-add-component-button" title="<?php esc_attr_e( 'Add Row', 'table-layout' ); ?>" data-type="mmtl-row" href="#"><span class="glyphicons glyphicons-plus"></span><?php esc_html_e( 'Add Row', 'table-layout' ); ?></a>
			</div>
		</script>

		<script type="text/html" id="tmpl-mmtl-component">
			<div id="mmtl-component-{{ data.id }}" class="mmtl-component" data-type="{{ data.type }}" data-id="{{ data.id }}"{{{ data.attrs }}}>
				<div class="mmtl-component-inner">
					<div class="mmtl-component-header">
						<# if ( data.controls ) { #><div class="mmtl-controls mmtl-component-controls">{{{ data.controls }}}</div><# } #>
						<# if ( data.meta ) { #><div class="mmtl-component-meta">{{{ data.meta }}}</div><# } #>
					</div>
					<div class="mmtl-component-content">{{{ data.content }}}</div>
				</div>
			</div>
		</script>

		<script type="text/html" id="tmpl-mmtl-control">
			<a href="#" class="mmtl-control" title="{{ data.title }}" data-type="{{ data.id }}"><span class="mmtl-control-icon glyphicons glyphicons-{{ data.icon }}"></span><span class="mmtl-control-text screen-reader-text">{{{ data.text }}}</span></a> 
		</script>

		<script type="text/html" id="tmpl-mmtl-meta">
			<span class="mmtl-meta" title="{{ data.title }}" data-type="{{ data.type }}">{{{ data.text }}}</span> 
		</script>

		<script type="text/html" id="tmpl-mmtl-screen">

			<div id="mmtl-screen-{{ data.id }}" class="mmtl-screen">

				<form class="mmtl-screen-form" method="post">

					<div class="mmtl-screen-content">
						{{{ data.content }}}
					</div>

					<p class="mmtl-screen-footer">
						<?php submit_button( __( 'Update', 'table-layout' ), 'primary', 'submit', false ); ?>
					</p>

				</form>

			</div>

		</script>

		<script type="text/html" id="tmpl-mmtl-row-settings">

			<h2><?php esc_html_e( 'Layout', 'table-layout' ); ?></h2>

			<ul class="mmtl-layout">
				<li class="mmtl-row"><a href="#" title="1/1"><span class="mmtl-col-xs-12"></span></a></li>
				<li class="mmtl-row"><a href="#" title="1/2 + 1/2"><span class="mmtl-col-xs-6"></span><span class="mmtl-col-xs-6"></span></a></li>
				<li class="mmtl-row"><a href="#" title="1/3 + 1/3 + 1/3"><span class="mmtl-col-xs-4"></span><span class="mmtl-col-xs-4"></span><span class="mmtl-col-xs-4"></span></span></a></li>
				<li class="mmtl-row"><a href="#" title="1/3 + 2/3"><span class="mmtl-col-xs-4"></span><span class="mmtl-col-xs-8"></span></a></li>
				<li class="mmtl-row"><a href="#" title="2/3 + 1/3"><span class="mmtl-col-xs-8"></span><span class="mmtl-col-xs-4"></span></a></li>
				<li class="mmtl-row"><a href="#" title="1/4 + 3/4"><span class="mmtl-col-xs-3"></span><span class="mmtl-col-xs-9"></span></a></li>
				<li class="mmtl-row"><a href="#" title="3/4 + 1/4"><span class="mmtl-col-xs-9"></span><span class="mmtl-col-xs-3"></span></a></li>
				<li class="mmtl-row"><a href="#" title="1/4 + 1/4 + 1/4 + 1/4"><span class="mmtl-col-xs-3"></span><span class="mmtl-col-xs-3"></span><span class="mmtl-col-xs-3"></span></span><span class="mmtl-col-xs-3"></span></a></li>
			</ul>

			<p>
				<label for="mmtl-layout"><?php esc_html_e( 'Custom', 'table-layout' ); ?></label><br>
				<input type="text" id="mmtl-layout" name="layout" value="{{ data.layout }}"><br>
				<span class="description"><?php esc_html_e( 'e.g. `1/3 + 2/3` creates 2 columns with one third and two third width.', 'table-layout' ); ?></span>
			</p>

			<h2><?php esc_html_e( 'Background', 'table-layout' ); ?></h2>

			<p>
				<label for="mmtl-bg_image"><?php esc_html_e( 'Image', 'table-layout' ); ?></label><br>
				
				<span class="mmtl-media">
					<input type="text" id="mmtl-bg_image" class="mmtl-media-field mmtl-hide" name="bg_image" value="{{ data.bg_image }}">
					<a href="#" class="mmtl-media-add" title="<?php esc_attr_e( 'Add', 'table-layout' ); ?>"><span class="glyphicons glyphicons-plus"></span></a>
					<a href="#" class="mmtl-media-remove" title="<?php esc_attr_e( 'Remove', 'table-layout' ); ?>"><span class="glyphicons glyphicons-minus"></span></a>
					<img class="mmtl-media-image">
				</span>
			</p>

			<p>
				<label for="mmtl-bg_position"><?php esc_html_e( 'Position', 'table-layout' ); ?></label><br>
				<select id="mmtl-bg_position" name="bg_position">
					<option value=""<# 				if ( data.bg_position == '' ) 				{ #> selected<# } #>><?php esc_html_e( '- choose -', 'table-layout' ); ?></option>
					<option value="left top"<# 		if ( data.bg_position == 'left top' ) 		{ #> selected<# } #>><?php esc_html_e( 'left top', 'table-layout' ); ?></option>
					<option value="left center"<# 	if ( data.bg_position == 'left center' ) 	{ #> selected<# } #>><?php esc_html_e( 'left center', 'table-layout' ); ?></option>
					<option value="left bottom"<# 	if ( data.bg_position == 'left bottom' ) 	{ #> selected<# } #>><?php esc_html_e( 'left bottom', 'table-layout' ); ?></option>
					<option value="right top"<# 	if ( data.bg_position == 'right top' ) 		{ #> selected<# } #>><?php esc_html_e( 'right top', 'table-layout' ); ?></option>
					<option value="right center"<# 	if ( data.bg_position == 'right center' ) 	{ #> selected<# } #>><?php esc_html_e( 'right center', 'table-layout' ); ?></option>
					<option value="right bottom"<# 	if ( data.bg_position == 'right bottom' ) 	{ #> selected<# } #>><?php esc_html_e( 'right bottom', 'table-layout' ); ?></option>
					<option value="center top"<# 	if ( data.bg_position == 'center top' ) 	{ #> selected<# } #>><?php esc_html_e( 'center top', 'table-layout' ); ?></option>
					<option value="center center"<# if ( data.bg_position == 'center center' ) 	{ #> selected<# } #>><?php esc_html_e( 'center center', 'table-layout' ); ?></option>
					<option value="center bottom"<# if ( data.bg_position == 'center bottom' ) 	{ #> selected<# } #>><?php esc_html_e( 'center bottom', 'table-layout' ); ?></option>
				</select>
			</p>

			<p>
				<label for="mmtl-bg_repeat"><?php esc_html_e( 'Repeat', 'table-layout' ); ?></label><br>
				<select id="mmtl-bg_repeat" name="bg_repeat">
					<option value=""<# 			if ( data.bg_repeat == '' ) 		 { #> selected<# } #>><?php esc_html_e( '- choose -', 'table-layout' ); ?></option>
					<option value="repeat"<# 	if ( data.bg_repeat == 'repeat' ) 	 { #> selected<# } #>><?php esc_html_e( 'repeat', 'table-layout' ); ?></option>
					<option value="repeat-x"<# 	if ( data.bg_repeat == 'repeat-x' )  { #> selected<# } #>><?php esc_html_e( 'repeat x', 'table-layout' ); ?></option>
					<option value="repeat-y"<# 	if ( data.bg_repeat == 'repeat-y' )  { #> selected<# } #>><?php esc_html_e( 'repeat y', 'table-layout' ); ?></option>
					<option value="no-repeat"<# if ( data.bg_repeat == 'no-repeat' ) { #> selected<# } #>><?php esc_html_e( 'no repeat', 'table-layout' ); ?></option>
				</select>
			</p>

			<p>
				<label for="mmtl-bg_size"><?php esc_html_e( 'Size', 'table-layout' ); ?></label><br>
				<select id="mmtl-bg_size" name="bg_size">
					<option value=""<# 		  if ( data.bg_size == '' ) 	   { #> selected<# } #>><?php esc_html_e( '- choose -', 'table-layout' ); ?></option>
					<option value="cover"<#   if ( data.bg_size == 'cover' )   { #> selected<# } #>><?php esc_html_e( 'cover', 'table-layout' ); ?></option>
					<option value="contain"<# if ( data.bg_size == 'contain' ) { #> selected<# } #>><?php esc_html_e( 'contain', 'table-layout' ); ?></option>
				</select>
			</p>

			<h2><?php esc_html_e( 'General', 'table-layout' ); ?></h2>

			<p>
				<label for="mmtl-id"><?php esc_html_e( 'ID', 'table-layout' ); ?></label><br>
				<input type="text" id="mmtl-id" name="id" value="{{ data.id }}">
			</p>

			<p>
				<label for="mmtl-class"><?php esc_html_e( 'Class', 'table-layout' ); ?></label><br>
				<input type="text" id="mmtl-class" name="class" value="{{ data.class }}">
			</p>

		</script>

		<script type="text/html" id="tmpl-mmtl-col-settings">

			<h2><?php esc_html_e( 'Content', 'table-layout' ); ?></h2>

			<?php wp_editor( '{{ data.content }}', 'mmtl_content', array( 'textarea_name' => 'content', 'wpautop' => false ) ); ?>

			<h2><?php esc_html_e( 'General', 'table-layout' ); ?></h2>

			<p>
				<label for="mmtl-id"><?php esc_html_e( 'ID', 'table-layout' ); ?></label><br>
				<input type="text" id="mmtl-id" name="id" value="{{ data.id }}">
			</p>

			<p>
				<label for="mmtl-class"><?php esc_html_e( 'Class', 'table-layout' ); ?></label><br>
				<input type="text" id="mmtl-class" name="class" value="{{ data.class }}">
			</p>

			<h2><?php esc_html_e( 'Responsiveness', 'table-layout' ); ?></h2>

			<p>
				<label for="mmtl-width"><?php esc_html_e( 'Width', 'table-layout' ); ?></label>
				<select id="mmtl-width" name="width_sm">
					<option value="1/12"<# if ( data.width_sm == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
					<option value="1/6"<# if ( data.width_sm == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
					<option value="1/4"<# if ( data.width_sm == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
					<option value="1/3"<# if ( data.width_sm == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
					<option value="5/12"<# if ( data.width_sm == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
					<option value="1/2"<# if ( data.width_sm == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
					<option value="7/12"<# if ( data.width_sm == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
					<option value="2/3"<# if ( data.width_sm == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
					<option value="3/4"<# if ( data.width_sm == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
					<option value="5/6"<# if ( data.width_sm == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
					<option value="11/12"<# if ( data.width_sm == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
					<option value="1/1"<# if ( data.width_sm == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
				</select>
			</p>

			<h3><?php esc_html_e( 'Responsiveness', 'table-layout' ); ?></h3>

			<table class="mmtl-responsive-table">

				<tr>
					<th><?php _e( 'Device', 'table-layout' ); ?></th>
					<th><?php _e( 'Offset', 'table-layout' ); ?></th>
					<th><?php _e( 'Width', 'table-layout' ); ?></th>
					<th><?php _e( 'Push', 'table-layout' ); ?></th>
					<th><?php _e( 'Pull', 'table-layout' ); ?></th>
					<th><?php _e( 'Hide', 'table-layout' ); ?></th>
				</tr>

				<tr>
					<td><i class="fa fa-desktop fa-2x" title="<?php esc_attr_e( 'Large desktop', 'table-layout' ); ?>"></i><span class="screen-reader-text"><?php _e( 'Large desktop', 'table-layout' ); ?></span></td>
					<td>
						<select name="offset_lg">
							<option value=""<# if ( data.offset_lg == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.offset_lg == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.offset_lg == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.offset_lg == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.offset_lg == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.offset_lg == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.offset_lg == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.offset_lg == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.offset_lg == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.offset_lg == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.offset_lg == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.offset_lg == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.offset_lg == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.offset_lg == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="width_lg">
							<option value=""<# if ( data.width_lg == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.width_lg == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.width_lg == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.width_lg == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.width_lg == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.width_lg == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.width_lg == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.width_lg == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.width_lg == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.width_lg == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.width_lg == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.width_lg == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.width_lg == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="push_lg">
							<option value=""<# if ( data.push_lg == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.push_lg == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.push_lg == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.push_lg == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.push_lg == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.push_lg == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.push_lg == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.push_lg == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.push_lg == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.push_lg == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.push_lg == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.push_lg == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.push_lg == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.push_lg == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="pull_lg">
							<option value=""<# if ( data.pull_lg == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.pull_lg == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.pull_lg == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.pull_lg == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.pull_lg == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.pull_lg == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.pull_lg == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.pull_lg == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.pull_lg == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.pull_lg == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.pull_lg == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.pull_lg == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.pull_lg == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.pull_lg == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<input type="checkbox" name="hide_lg" value="1"<# if ( data.hide_lg == '1' ) { #> checked<# } #>>
					</td>
				</tr>

				<tr>
					<td><i class="fa fa-desktop fa-lg" title="<?php esc_attr_e( 'Desktop', 'table-layout' ); ?>"></i><span class="screen-reader-text"><?php _e( 'Desktop', 'table-layout' ); ?></span></td>
					<td>
						<select name="offset_md">
							<option value=""<# if ( data.offset_md == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.offset_md == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.offset_md == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.offset_md == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.offset_md == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.offset_md == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.offset_md == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.offset_md == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.offset_md == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.offset_md == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.offset_md == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.offset_md == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.offset_md == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.offset_md == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="width_md">
							<option value=""<# if ( data.width_md == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.width_md == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.width_md == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.width_md == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.width_md == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.width_md == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.width_md == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.width_md == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.width_md == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.width_md == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.width_md == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.width_md == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.width_md == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="push_md">
							<option value=""<# if ( data.push_md == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.push_md == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.push_md == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.push_md == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.push_md == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.push_md == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.push_md == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.push_md == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.push_md == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.push_md == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.push_md == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.push_md == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.push_md == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.push_md == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="pull_md">
							<option value=""<# if ( data.pull_md == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.pull_md == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.pull_md == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.pull_md == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.pull_md == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.pull_md == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.pull_md == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.pull_md == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.pull_md == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.pull_md == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.pull_md == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.pull_md == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.pull_md == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.pull_md == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<input type="checkbox" name="hide_md" value="1"<# if ( data.hide_md == '1' ) { #> checked<# } #>>
					</td>
				</tr>

				<tr>
					<td><i class="fa fa-tablet fa-lg" title="<?php esc_attr_e( 'Tablet', 'table-layout' ); ?>"></i><span class="screen-reader-text"><?php _e( 'Tablet', 'table-layout' ); ?></span></td>
					<td>
						<select name="offset_sm">
							<option value=""<# if ( data.offset_sm == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.offset_sm == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.offset_sm == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.offset_sm == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.offset_sm == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.offset_sm == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.offset_sm == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.offset_sm == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.offset_sm == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.offset_sm == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.offset_sm == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.offset_sm == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.offset_sm == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.offset_sm == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td><p class="description"><?php _e( 'Value from width attribute.', 'table-layout' ); ?></p></td>
					<td>
						<select name="push_sm">
							<option value=""<# if ( data.push_sm == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.push_sm == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.push_sm == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.push_sm == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.push_sm == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.push_sm == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.push_sm == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.push_sm == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.push_sm == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.push_sm == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.push_sm == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.push_sm == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.push_sm == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.push_sm == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="pull_sm">
							<option value=""<# if ( data.pull_sm == '' ) { #> selected<# } #>><?php esc_html_e( 'Inherit from smaller', 'table-layout' ); ?></option>
							<option value="0"<# if ( data.pull_sm == '0' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.pull_sm == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.pull_sm == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.pull_sm == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.pull_sm == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.pull_sm == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.pull_sm == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.pull_sm == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.pull_sm == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.pull_sm == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.pull_sm == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.pull_sm == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.pull_sm == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<input type="checkbox" name="hide_sm" value="1"<# if ( data.hide_sm == '1' ) { #> checked<# } #>>
					</td>
				</tr>

				<tr>
					<td><i class="fa fa-mobile fa-lg" title="<?php esc_attr_e( 'Phone', 'table-layout' ); ?>"></i><span class="screen-reader-text"><?php _e( 'Phone', 'table-layout' ); ?></span></td>
					<td>
						<select name="offset_xs">
							<option value=""<# if ( data.offset_xs == '' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.offset_xs == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.offset_xs == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.offset_xs == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.offset_xs == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.offset_xs == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.offset_xs == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.offset_xs == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.offset_xs == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.offset_xs == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.offset_xs == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.offset_xs == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.offset_xs == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="width_xs">
							<option value=""<# if ( data.width_xs == '' ) { #> selected<# } #>></option>
							<option value="1/12"<# if ( data.width_xs == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.width_xs == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.width_xs == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.width_xs == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.width_xs == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.width_xs == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.width_xs == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.width_xs == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.width_xs == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.width_xs == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.width_xs == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.width_xs == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="push_xs">
							<option value=""<# if ( data.push_xs == '' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.push_xs == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.push_xs == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.push_xs == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.push_xs == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.push_xs == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.push_xs == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.push_xs == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.push_xs == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.push_xs == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.push_xs == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.push_xs == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.push_xs == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<select name="pull_xs">
							<option value=""<# if ( data.pull_xs == '' ) { #> selected<# } #>><?php esc_html_e( 'none', 'table-layout' ); ?></option>
							<option value="1/12"<# if ( data.pull_xs == '1/12' ) { #> selected<# } #>><?php esc_html_e( '1 column - 1/12', 'table-layout' ); ?></option>
							<option value="1/6"<# if ( data.pull_xs == '1/6' ) { #> selected<# } #>><?php esc_html_e( '2 columns - 1/6', 'table-layout' ); ?></option>
							<option value="1/4"<# if ( data.pull_xs == '1/4' ) { #> selected<# } #>><?php esc_html_e( '3 columns - 1/4', 'table-layout' ); ?></option>
							<option value="1/3"<# if ( data.pull_xs == '1/3' ) { #> selected<# } #>><?php esc_html_e( '4 columns - 1/3', 'table-layout' ); ?></option>
							<option value="5/12"<# if ( data.pull_xs == '5/12' ) { #> selected<# } #>><?php esc_html_e( '5 columns - 5/12', 'table-layout' ); ?></option>
							<option value="1/2"<# if ( data.pull_xs == '1/2' ) { #> selected<# } #>><?php esc_html_e( '6 columns - 1/2', 'table-layout' ); ?></option>
							<option value="7/12"<# if ( data.pull_xs == '7/12' ) { #> selected<# } #>><?php esc_html_e( '7 columns - 7/12', 'table-layout' ); ?></option>
							<option value="2/3"<# if ( data.pull_xs == '2/3' ) { #> selected<# } #>><?php esc_html_e( '8 columns - 2/3', 'table-layout' ); ?></option>
							<option value="3/4"<# if ( data.pull_xs == '3/4' ) { #> selected<# } #>><?php esc_html_e( '9 columns - 3/4', 'table-layout' ); ?></option>
							<option value="5/6"<# if ( data.pull_xs == '5/6' ) { #> selected<# } #>><?php esc_html_e( '10 columns - 5/6', 'table-layout' ); ?></option>
							<option value="11/12"<# if ( data.pull_xs == '11/12' ) { #> selected<# } #>><?php esc_html_e( '11 columns - 11/12', 'table-layout' ); ?></option>
							<option value="1/1"<# if ( data.pull_xs == '1/1' ) { #> selected<# } #>><?php esc_html_e( '12 columns - 1/1', 'table-layout' ); ?></option>
						</select>
					</td>
					<td>
						<input type="checkbox" name="hide_xs" value="1"<# if ( data.hide_xs == '1' ) { #> checked<# } #>>
					</td>
				</tr>

			</table>

		</script>

		<?php
	}

	public function enqueue_scripts()
	{
		if ( ! $this->is_editor_screen() )
		{
			return;
		}

		// Styles

		wp_enqueue_style( 'glyphicons', plugins_url( 'css/glyphicons.css', MMTL_FILE ), null, '1.9.2' );
		wp_enqueue_style( 'font-awesome', plugins_url( 'css/font-awesome.min.css', MMTL_FILE ), null, '4.5.0' );
		wp_enqueue_style( 'jquery-ui-structure', plugins_url( 'css/jquery-ui.structure.min.css', MMTL_FILE ), null, '1.11.4' );

		wp_enqueue_style( 'table-layout' );
		wp_enqueue_style( 'table-layout-editor', plugins_url( 'css/editor.min.css', MMTL_FILE ) );
		wp_enqueue_style( 'table-layout-editor-style', plugins_url( 'css/editor-style.min.css', MMTL_FILE ) );

		// Scripts

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'featherlight', plugins_url( 'js/featherlight.min.js', MMTL_FILE ), array( 'jquery' ), '1.3.4', true );
		wp_enqueue_script( 'jquery-sticky-kit', plugins_url( 'js/jquery.sticky-kit.min.js', MMTL_FILE ), array( 'jquery' ), '1.1.2', true );

		wp_enqueue_script( 'table-layout-editor', plugins_url( 'js/editor.js', MMTL_FILE ), null, false, true );
		wp_enqueue_script( 'table-layout-editor-common', plugins_url( 'js/editor-common.js', MMTL_FILE ), null, false, true );
		wp_enqueue_script( 'table-layout-admin', plugins_url( 'js/admin.js', MMTL_FILE ), null, false, true );
		
		$post_id = $this->get_post_id();

		$options = apply_filters( 'mmtl_options', array
		(
			'post_id'                  => $post_id,
			'post_editor_id'           => MMTL_POST_EDITOR_ID,
			'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
			'noncename'                => MMTL_NONCE_NAME,
			'nonce'                    => wp_create_nonce( 'editor' ),
			'confirm_delete'           => __( 'Are you sure you want to delete this component?', 'table-layout' ),
			'control_label_add'        => __( 'Add', 'table-layout' ),
			'control_label_edit'       => __( 'Edit', 'table-layout' ),
			'control_label_copy'       => __( 'Copy', 'table-layout' ),
			'control_label_delete'     => __( 'Delete', 'table-layout' ),
			'control_label_toggle'     => __( 'Toggle', 'table-layout' ),
			'control_label_fullscreen' => __( 'Toggle full screen', 'table-layout' ),
			'control_label_col_width'  => __( 'Width', 'table-layout' ),
			'control_label_col_increase_width' => __( 'Increase width', 'table-layout' ),
			'control_label_col_decrease_width' => __( 'Decrease width', 'table-layout' ),
			'meta_title_id'            => __( 'ID', 'table-layout' ),
			'meta_title_class'         => __( 'Class', 'table-layout' ),
			'meta_title_bg_image'      => __( 'Background image', 'table-layout' ),
			'meta_title_push'          => __( 'Push', 'table-layout' ),
			'meta_title_pull'          => __( 'Pull', 'table-layout' ),
			'debug'                    => MMTL_DEBUG
		));

		wp_localize_script( 'table-layout-admin', 'MMTL_Options', $options );

		remove_editor_styles();
	}
}

MMTL_Editor::get_instance()->init();

?>