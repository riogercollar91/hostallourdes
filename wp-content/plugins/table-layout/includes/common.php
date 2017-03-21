<?php if ( ! defined( 'ABSPATH' ) ) exit; //exits when accessed directly

class MMTL_Common
{
	static public function get_attachment_sizes( $attachment_id, $abs = false )
	{
		$data = wp_get_attachment_metadata( $attachment_id );

		if ( ! $data )
		{
			return false;
		}
		
		$sizes = $data['sizes'];

		// adds original size

		$sizes['full'] = array
		(
			'file'   => basename( $data['file'] ),
			'width'  => $data['width'],
			'height' => $data['height']
		);

		// sets dir

		$upload_dir = wp_upload_dir();

		$base = trailingslashit( dirname( $data['file'] ) );

		if ( $abs == 'path' )
		{
			$base = trailingslashit( $upload_dir['basedir'] ) . $base;
		}

		else if ( $abs == 'url' )
		{
			$base = trailingslashit( $upload_dir['baseurl'] ) . $base;
		}

		foreach ( $sizes as &$size )
		{
			$size['file'] = $base . ltrim( $size['file'], '/' );
		}

		return $sizes;
	}

	static public function get_attachment_id_by_url( $url )
	{
		// removes size suffix

		$guid = preg_replace( '/-\d+x\d+(\.[a-z0-9]+)$/i', '$1', $url );

		// gets attachment id

		global $wpdb;

		$attachment = $wpdb->get_row( sprintf( 'SELECT ID FROM %sposts WHERE guid="%s"', esc_sql( $wpdb->prefix ), esc_sql( $guid ) ) );

		if ( $attachment )
		{
			return $attachment->ID;
		}

		return false;
	}

	static public function format( $value, $format = '%s' )
	{
		if ( is_array( $value ) )
		{
			foreach ( $value as $k => &$v )
			{
				$v = self::format( $v, $format );
			}

			return $value;
		}

		return sprintf( $format, $value );
	}

	static public function html_class_to_array( $class )
	{
		$class = trim( preg_replace( '/\s+/' , ' ', $class ) );

		if ( $class )
		{
			$classes = explode( ' ', $class );
		}

		else
		{
			$classes = array();
		}

		return $classes;
	}

	static public function parse_html_attributes( $attributes, $extra = '' )
	{
		$extra = trim( $extra );
	 
		$str = '';
	 
		foreach ( $attributes as $key => $value )
		{
			if ( (string) $value === '' )
			{
				continue;
			}
	 
			$str .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}
	 
		if ( $extra )
		{
			$str .= ' ' . $extra;
		}
	 
		return $str;
	}

	static public function parse_custom_html_attributes( $attributes )
	{
		$a = array();

		foreach ( $attributes as $key => $value )
		{
			if ( stripos( $key, 'data-' ) !== 0 )
			{
				$key = 'data-' . $key;
			}

			$a[ $key ] = $value;
		}

		return self::parse_html_attributes( $a );
	}

	static public function is_shortcode_used( $tag )
	{
		global $wp_query;

	    $posts   = $wp_query->posts;
	    $pattern = get_shortcode_regex();
	    
	    foreach ( $posts as $post )
	    {
			if ( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
				&& array_key_exists( 2, $matches )
				&& in_array( $tag, $matches[2] ) )
			{
				return true;
			}    
	    }

	    return false;
	}

	static public function get_column_width_codes( $layout, $args = '' )
	{
		extract( wp_parse_args( $args, array
		(
			'array' => true
		)));

		// 1/4 + 1/2 + 1/4 => [ 3, 6, 3 ]

		$codes = array();
		
		$layout = explode( '+', $layout );
		
		foreach ( $layout as $key => $value )
		{
			$value = trim( $value );

			if ( $value == '' )
			{
				continue;
			}

			$parts = str_replace( ' ', '', $value );
			$parts = explode( '/', $parts );

			if ( count( $parts ) != 2 )
			{
				continue;
			}

			$numerator   = $parts[0];
			$denominator = $parts[1];

			if ( ! is_numeric( $numerator ) || ! is_numeric( $denominator ) )
			{
				continue;
			}

			$code = 12 * $numerator / $denominator;

			if ( $code % 1 !== 0 )
			{
				continue;
			}

			if ( $code < 1 || $code > 12 )
			{
				continue;
			};

			$codes[] = $code;
		}

		if ( ! $array )
		{
			$codes = implode( '-', $codes );
		}

		return $codes;
	}
}

?>