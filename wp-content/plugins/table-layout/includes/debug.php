<?php if ( ! defined( 'ABSPATH' ) ) exit; //exits when accessed directly

class MMTL_Debug
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
		add_filter( 'plugins_url', array( $this, 'change_assets_url' ), 10, 3 );
	}

	public function change_assets_url( $url, $path, $plugin )
	{
		if ( $plugin === MMTL_FILE )
		{
			if ( strpos( $path, '.min.' ) !== false )
			{
				$dev_path = preg_replace( '/\.min(\.(js|css))$/i' , '$1', $path );

				if ( file_exists( plugin_dir_path( MMTL_FILE ) . $dev_path ) )
				{
					$url = str_replace( $path, $dev_path, $url );
				}
			}
		}

		return $url;
	}
}

MMTL_Debug::get_instance()->init();

?>