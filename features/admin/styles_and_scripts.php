<?php
namespace casasoft\casasyncmap;


class styles_and_scripts extends Feature {


	public function __construct() {
		wp_enqueue_style( 'casasync-map-admin', PLUGIN_URL . 'assets/css/casasync-map-admin.css', array(), '1', 'screen' );
	}

}

add_action( 'load-post.php', array( 'casasoft\casasyncmap\styles_and_scripts', 'init' )  );
add_action( 'load-post-new.php', array( 'casasoft\casasyncmap\styles_and_scripts', 'init' )  );

