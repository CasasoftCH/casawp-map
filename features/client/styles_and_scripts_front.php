<?php
namespace casasoft\casasyncmap;


class styles_and_scripts_front extends Feature {


	public function __construct() {
		wp_enqueue_style( 'casasync-map-front', PLUGIN_URL . 'assets/css/casasync-map-front.css', array(), '1', 'screen' );
		wp_enqueue_script( 'casasync-map-front', PLUGIN_URL . 'assets/js/min/casasync-map-front-min.js', array('jquery'));

	}

}

add_action( 'wp_enqueue_scripts', array( 'casasoft\casasyncmap\styles_and_scripts_front', 'init' )  );

