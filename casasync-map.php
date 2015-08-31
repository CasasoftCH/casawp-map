<?php
/*
 *	Plugin Name: 	Casasync Map
 *	Description:    Plugin for managing and presenting real-estate building on a map.
 *	Author:         Casasoft AG
 *	Author URI:     http://casasoft.ch
 *	Version: 		1.0.0
 *	Text Domain: 	casasyncmap
 *	Domain Path: 	languages/
 */

namespace casasoft\casasyncmap;
require_once( 'features/silence.php' );


define( 'casasoft\casasyncmap\VERSION', '1.0.0' );
define( 'casasoft\casasyncmap\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'casasoft\casasyncmap\PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*
 * The following includes add features to the plugin
 */
require_once( 'features/feature.php' );
require_once( 'features/template.php' );
require_once( 'features/options.php' );
require_once( 'features/class-loader.php' );
require_once( 'features/kit.php' );
/**
 * The central plugin class and bootstrap for the application.
 *
 * While this class is primarily boilerplate code and can be used without alteration,
 * there are a few things you need to edit to get the most out of this kit:
 *  * Add any initialization code that must run *during* the plugins_loaded action in the constructor.
 *  * Edit the return value of the defaults function so that the array contains all your default plugin values.
 *  * Add any plugin activation code to the activate_plugin method.
 *  * Add any plugin deactivation code to the deactivate_plugin method.
 *      - If you don't have any activation code, be sure to comment-out register_deactivation_hook
 */
class CasasyncMap extends Kit {

	private static $__instance;

	public static function init() {
		if ( !self::$__instance ) {
			$plugin_dir = basename( dirname( __FILE__ ) );
			load_plugin_textdomain( 'casasyncmap', FALSE, $plugin_dir . '/languages/' );
			self::$__instance = new CasasyncMap();
			parent::initialize();
		}
		return self::$__instance;
	}

	/**
	 * Constructor: Main entry point for your plugin. Runs during the plugins_loaded action.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Provides the default settings for the plugin.
	 *
	 * The defaults method is only ever run on plugin activation and is used to populate the default options
	 * for the plugin. When you update the options for your plugin in this method when adding functionality,
	 * the kit will ensure that the user's options are up to date.
	 *
	 * @static
	 * @return array The default preferences and settings for the plugin.
	 */
	public static function defaults() {
		$filter_config = array(
			array(
				'label' => "Miete",
				'url' => get_site_url() . '/immobilien/?casasync_salestype_s[]=rent&casasync_availability_s[]=active'
			),
			array(
				'label' => "Kauf",
				'url' => get_site_url() . '/immobilien/?casasync_salestype_s[]=buy&casasync_availability_s[]=active'
			),
		);

		return array(
			'csm_load_google_maps_api' => false,
			'csm_filter_config' => json_encode($filter_config, JSON_FORCE_OBJECT),
			'csm_infobox_template' => file_get_contents(__DIR__ . '/assets/templates/infobox.mst'),
			'marker_image' => false,
			'csm_filter_basic' => '[
					    {
					        "taxonomy": "casasync_salestype",
					        "visible": true,
					        "inclusive": true,
					        "label": "Vermarktungsart",
					        "filter_terms": ""
					    },
					    {
					        "taxonomy": "casasync_availability",
					        "visible": true,
					        "inclusive": true,
					        "label": "Verf\u00fcgbarkeit",
					        "filter_terms": ""
					    },
					    {
					        "taxonomy": "casasync_category",
					        "visible": true,
					        "inclusive": true,
					        "label": "Kategorie",
					        "filter_terms": ""
					    },
					    {
					        "taxonomy": "casasync_location",
					        "visible": true,
					        "inclusive": true,
					        "label": "Ortschaft",
					        "filter_terms": ""
					    }
					]'
		);
	}

	/**
	 * Plugin activation hook
	 *
	 * Add any activation code you need to do here, like building tables and such.
	 * You won't need to worry about your options so long as you updated them using the defaults method.
	 *
	 * @static
	 * @hook register_activation_hook
	 */
	public static function activate_plugin() {
	}

	/**
	 * Plugin deactivation hook
	 *
	 * Need to clean up your plugin when it's deactivated?  Do that here.
	 * Remember, this isn't when your plugin is uninstalled, just deactivated
	 * ( so it happens when the plugin is updated too ).
	 *
	 * @static
	 * @hook register_deactivation_hook
	 */
	public static function deactivate_plugin() {

	}

}

add_action( 'plugins_loaded', array( 'casasoft\casasyncmap\CasasyncMap', 'init' ) );
register_activation_hook( __FILE__, array( 'casasoft\casasyncmap\CasasyncMap', '_activate_plugin' ) );
register_deactivation_hook( __FILE__, array( 'casasoft\casasyncmap\CasasyncMap', 'deactivate_plugin' ) );