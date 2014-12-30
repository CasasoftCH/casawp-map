<?php
namespace casasoft\casasyncmap;

class render extends Feature {

	public function __construct() {
		$this->add_action( 'init', 'set_shortcodes' );
	}

	public function set_shortcodes() {
		add_shortcode( 'CSM-map', array($this, 'shortcode_map'));
		add_shortcode( 'CSM-filter', array($this, 'shortcode_filter'));
	}

	function shortcode_filter( $atts ) {
		$a = shortcode_atts( array(
			'title' => ''
		), $atts );

		$template = $this->get_template();
		$template->set('filter_config', $this->get_option("csm_filter_config"));
		$template->set('title', $a['title']);
		$filter = $template->apply( 'map-filter.php' );

		return $filter;
	}
	
	function shortcode_map( $atts ) {
		$a = shortcode_atts( array(
		), $atts );
		return "<div id='casasync-map_map'></div>";
	}
}

// Subscribe to the drop-in to the initialization event
add_action( 'casasyncmap_init', array( 'casasoft\casasyncmap\render', 'init' ) );