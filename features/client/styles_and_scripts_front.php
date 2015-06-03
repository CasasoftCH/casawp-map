<?php
namespace casasoft\casasyncmap;


class styles_and_scripts_front extends Feature {

	public function __construct() {
		wp_enqueue_style( 'casasync-map-front', PLUGIN_URL . 'assets/css/casasync-map-front.css', array(), '1', 'screen' );
		
		$this->options = get_option( 'casasync_map' );
		if ($this->options['csm_load_google_maps_api'] == 1) {
			wp_enqueue_script(
				'casasync-google-maps',
				'https://maps.googleapis.com/maps/api/js?v=3.exp',
				array('jquery'),
				false,
				true);
		}

		wp_enqueue_script(
			'casasync-map-front',
			PLUGIN_URL . 'assets/js/min/casasync-map-front-min.js',
			array('jquery'),
			false,
			true
		);

		$image_src = '';
		$value = (isset( $this->options['marker_image'] ) ? esc_attr( $this->options['marker_image']) : '');
		if ($value) {
			$image_attributes = wp_get_attachment_image_src( $value, 'full' );
			if ($image_attributes) {
				$image_src = $image_attributes[0];
			}
		}
		$args = array(
			'plugin_url'   => PLUGIN_URL,
			'marker_image' => $image_src,
			'infobox_template' => $this->options['csm_infobox_template'],
			'i18n' => $this->getTranslations()
		);
		wp_localize_script( 'casasync-map-front', 'casasyncMapOptions', $args );
	}

	private function getTranslations() {
		return array(
			'living_space' 		=> __('Living space', 'casasync'),
			'net_living_space' 	=> __('Net living space', 'casasync'),
			'location'			=> __('Location', 'casasync'),
			'read_more'			=>__('To property / contact', 'casasyncmap')
		);
	}

}

add_action( 'wp_enqueue_scripts', array( 'casasoft\casasyncmap\styles_and_scripts_front', 'init' )  );

