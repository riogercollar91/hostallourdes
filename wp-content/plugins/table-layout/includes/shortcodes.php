<?php if ( ! defined( 'ABSPATH' ) ) exit; //exits when accessed directly

class MMTL_Shortcodes
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
		add_shortcode( 'mmtl-row', array( $this, 'row_shortcode' ) );
		add_shortcode( 'mmtl-col', array( $this, 'col_shortcode' ) );

		add_filter( 'the_content', array( $this, 'the_content' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function the_content( $the_content )
 	{
 		if ( has_shortcode( $the_content, 'mmtl-row' ) )
 		{
 			// removes wpautop (paragraphs already included in content)
 			remove_filter( 'the_content', 'wpautop' );

 			// removes paragraphs around brackets
 			$the_content = preg_replace( '/<p>\s*(\[)/s' , '$1', $the_content );   // <p>[
 			$the_content = preg_replace( '/(\])\s*<\/p>/s' , '$1', $the_content ); // ]</p>
 		}

 		return $the_content;
 	}

	public function row_shortcode( $atts, $content = '' )
	{
		extract( shortcode_atts( array
		(
			'id'           => '',
			'class'        => '',
			'bg_image'     => '',
			'bg_position'  => '',
			'bg_repeat'    => '',
			'bg_size'      => ''
		), $atts ) );

		// classes

		$classes = MMTL_Common::html_class_to_array( $class );
		
		if ( $bg_image )
		{
			array_unshift( $classes , 'mmtl-has-overlay' );
		}

		array_unshift( $classes , 'mmtl-row' );

		$class = implode( ' ', $classes );

		// styles

		$style = '';

		if ( $bg_image )
		{
			$style .= sprintf( 'background-image: url("%s");', $bg_image );
		}

		if ( $bg_position )
		{
			$style .= sprintf( 'background-position: %s;', $bg_position );
		}

		if ( $bg_repeat )
		{
			$style .= sprintf( 'background-repeat: %s;', $bg_repeat );
		}

		if ( $bg_size )
		{
			$style .= sprintf( 'background-size: %s;', $bg_size );
		}

		//

		$str = sprintf( '<div%s>', MMTL_Common::parse_html_attributes( array
		(
			'id'    => $id,
			'class' => $class,
			'style' => $style
		)));

		$str .= sprintf( '<div class="mmtl-content">%s</div>', do_shortcode( $content ) );

		if ( $bg_image )
		{
			$str .= '<div class="mmtl-overlay"></div>';
		}

		$str .= '</div>';

		return $str;
	}

	public function col_shortcode( $atts, $content = '' )
	{
		extract( shortcode_atts( array
		(
			'id'        => '',
			'class'     => '',
			'offset_xs' => '',
			'offset'    => '', // sm
			'offset_md' => '',
			'offset_lg' => '',
			'width_xs'  => '',
			'width'     => '', // sm
			'width_md'  => '',
			'width_lg'  => '',
			'hide_xs'   => '',
			'hide'      => '', // sm
			'hide_md'   => '',
			'hide_lg'   => '',
			'push_xs'   => '',
			'push'      => '', // sm
			'push_md'   => '',
			'push_lg'   => '',
			'pull_xs'   => '',
			'pull'      => '', // sm
			'pull_md'   => '',
			'pull_lg'   => ''
		), $atts ) );

		// classes
		
		$classes = MMTL_Common::html_class_to_array( $class );

		$my_classes = array( 'mmtl-col' );

		if ( $offset_xs ) $my_classes[] = 'mmtl-col-xs-offset-' . MMTL_Common::get_column_width_codes( $offset_xs, 'array=0' );
		if ( $offset )    $my_classes[] = 'mmtl-col-sm-offset-' . MMTL_Common::get_column_width_codes( $offset, 'array=0' );
		if ( $offset_md ) $my_classes[] = 'mmtl-col-md-offset-' . MMTL_Common::get_column_width_codes( $offset_md, 'array=0' );
		if ( $offset_lg ) $my_classes[] = 'mmtl-col-lg-offset-' . MMTL_Common::get_column_width_codes( $offset_lg, 'array=0' );

		if ( $width_xs ) $my_classes[] = 'mmtl-col-xs-' . MMTL_Common::get_column_width_codes( $width_xs, 'array=0' );
		if ( $width )    $my_classes[] = 'mmtl-col-sm-' . MMTL_Common::get_column_width_codes( $width, 'array=0' );
		if ( $width_md ) $my_classes[] = 'mmtl-col-md-' . MMTL_Common::get_column_width_codes( $width_md, 'array=0' );
		if ( $width_lg ) $my_classes[] = 'mmtl-col-lg-' . MMTL_Common::get_column_width_codes( $width_lg, 'array=0' );

		if ( $hide_xs ) $my_classes[] = 'mmtl-hidden-xs';
		if ( $hide )    $my_classes[] = 'mmtl-hidden-sm';
		if ( $hide_md ) $my_classes[] = 'mmtl-hidden-md';
		if ( $hide_lg ) $my_classes[] = 'mmtl-hidden-lg';

		if ( $push_xs ) $my_classes[] = 'mmtl-col-xs-push-' . MMTL_Common::get_column_width_codes( $push_xs, 'array=0' );
		if ( $push )    $my_classes[] = 'mmtl-col-sm-push-' . MMTL_Common::get_column_width_codes( $push, 'array=0' );
		if ( $push_md ) $my_classes[] = 'mmtl-col-md-push-' . MMTL_Common::get_column_width_codes( $push_md, 'array=0' );
		if ( $push_lg ) $my_classes[] = 'mmtl-col-lg-push-' . MMTL_Common::get_column_width_codes( $push_lg, 'array=0' );

		if ( $pull_xs ) $my_classes[] = 'mmtl-col-xs-pull-' . MMTL_Common::get_column_width_codes( $pull_xs, 'array=0' );
		if ( $pull )    $my_classes[] = 'mmtl-col-sm-pull-' . MMTL_Common::get_column_width_codes( $pull, 'array=0' );
		if ( $pull_md ) $my_classes[] = 'mmtl-col-md-pull-' . MMTL_Common::get_column_width_codes( $pull_md, 'array=0' );
		if ( $pull_lg ) $my_classes[] = 'mmtl-col-lg-pull-' . MMTL_Common::get_column_width_codes( $pull_lg, 'array=0' );

		$classes = array_merge( $my_classes, $classes );
		$classes = array_unique( $classes );

		$class = implode( ' ', $classes );

		//

		$str = '';

		$str = sprintf( '<div%s><div class="mmtl-content">%s</div></div>', MMTL_Common::parse_html_attributes( array
		(
			'id'    => $id,
			'class' => $class

		)), do_shortcode( $content ) );

		return $str;
	}

	public function enqueue_scripts()
	{
		if ( ! MMTL_Common::is_shortcode_used( 'mmtl-row' ) )
		{
			return;
		}
		
		wp_enqueue_style( 'table-layout' );
	}
}

MMTL_Shortcodes::get_instance()->init();

?>