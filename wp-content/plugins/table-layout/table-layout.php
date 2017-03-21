<?php if ( ! defined( 'ABSPATH' ) ) exit; //exits when accessed directly

/*
------------------------------------------------------------------------------------------------------------------------
 Plugin Name: Responsive Table Layout
 Plugin URI:
 Description: Provides an easy- and user friendly way to make your site's content more responsive.
 Version:     1.4.2
 Author:      Maarten Menten
 Author URI:  https://www.ivalue.be/blog/author/mmenten/
 License:     GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: table-layout
 Domain Path: /languages
------------------------------------------------------------------------------------------------------------------------
*/

define( 'MMTL_FILE', __FILE__ );
define( 'MMTL_NONCE_NAME', '_mmtlnonce' );
define( 'MMTL_VERSION', '1.4.2' );

if ( ! defined( 'MMTL_DEBUG' ) )
{
	define( 'MMTL_DEBUG', false );
}

if ( MMTL_DEBUG ) require_once plugin_dir_path( MMTL_FILE ) . 'includes/debug.php';

require_once plugin_dir_path( MMTL_FILE ) . 'includes/common.php';
require_once plugin_dir_path( MMTL_FILE ) . 'includes/shortcodes.php';

if ( is_admin() )
{
	require_once plugin_dir_path( MMTL_FILE ) . 'includes/ajax.php';
	require_once plugin_dir_path( MMTL_FILE ) . 'includes/editor.php';
}

class MM_Table_Layout
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
		if ( is_admin() )
		{
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );
		}

		else
		{
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );
		}

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		register_activation_hook( MMTL_FILE, array( $this, 'activate' ) );
	}

	public function activate()
	{
		update_option( 'mmtl_version', MMTL_VERSION );
	}

	public function load_textdomain()
	{
		load_plugin_textdomain( 'table-layout', false, plugin_basename( dirname( MMTL_FILE ) ) . '/languages' );
	}

	public function enqueue_scripts()
	{
		wp_register_style( 'table-layout', plugins_url( 'css/table-layout.min.css', MMTL_FILE ) );
	}
}

MM_Table_Layout::get_instance()->init();

?>