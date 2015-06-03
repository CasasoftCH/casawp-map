<?php

namespace casasoft\casasyncmap;

class general_options extends Feature
{
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_external_scripts' ) );

		
	}

	public function load_external_scripts(){   
		 wp_register_script('casasync-map-options', PLUGIN_URL . 'assets/js/min/casasync-map-options-min.js',  array('jquery') );
 
		wp_enqueue_media();
		//wp_enqueue_script('media-upload');
		wp_enqueue_script('casasync-map-options');
 
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_submenu_page(
			'casasync',
			'Casasync Map',
			'Casasync Map',
			'manage_options',
			'casasync-map',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{

		// Set class property
		$this->options = get_option( 'casasync_map' );
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Casasync Map</h2>           
			<form method="post" action="options.php">
				<?php
					// This prints out all hidden setting fields
					settings_fields( 'csm_general_options' );   
					do_settings_sections( 'my-setting-admin' );
					submit_button(); 
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init()
	{        
		register_setting(
			'csm_general_options', // Option group
			'casasync_map', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'My Custom Settings', // Title
			array( $this, 'print_section_info' ), // Callback
			'my-setting-admin' // Page
		);

		add_settings_field(
			'csm_load_google_maps_api', 
			 __( 'Load Google Maps API', 'casasyncmap' ), 
			array( $this, 'load_google_maps_api_callback' ), 
			'my-setting-admin', 
			'setting_section_id'
		);

		add_settings_field(
			'csm_marker_image', 
			 __( 'Marker Image', 'casasyncmap' ), 
			array( $this, 'marker_image_callback' ), 
			'my-setting-admin', 
			'setting_section_id'
		);

		add_settings_field(
			'csm_filter_config', 
			 __( 'Filter-Einstellungen', 'casasyncmap' ), 
			array( $this, 'filter_config_callback' ), 
			'my-setting-admin', 
			'setting_section_id'
		);

		add_settings_field(
			'csm_infobox_template', 
			 __( 'Objekt-Details Tempalte', 'casasyncmap' ), 
			array( $this, 'infobox_template_callback' ), 
			'my-setting-admin', 
			'setting_section_id'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		$new_input = array();

		if( isset( $input['csm_load_google_maps_api'] ) ) {
			$new_input['csm_load_google_maps_api'] = sanitize_text_field( $input['csm_load_google_maps_api'] );
		}
		if( isset( $input['csm_filter_config'] ) ) {
			$new_input['csm_filter_config'] = sanitize_text_field( $input['csm_filter_config'] );
		}
		if( isset( $input['csm_infobox_template'] ) ) {
			$new_input['csm_infobox_template'] = $input['csm_infobox_template'];
		}
		if( isset( $input['csm_marker_image'] ) ) {
			$new_input['csm_marker_image'] = sanitize_text_field( $input['csm_marker_image'] );
		}
		if( isset( $input['csm_filter_typ'] ) ) {
			$new_input['csm_filter_typ'] = sanitize_text_field( $input['csm_filter_typ'] );
		}

		return $new_input;
	}

	/** 
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Enter your settings below:';
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function load_google_maps_api_callback()
	{
		$is_checked = false;
		if( isset($this->options['csm_load_google_maps_api'] ) && $this->options['csm_load_google_maps_api'] == 1) {
			$is_checked = true;
		}
		echo '<input type="hidden" name="casasync_map[csm_load_google_maps_api]" value="0" />';
		echo '<input type="checkbox" id="csm_load_google_maps_api" name="casasync_map[csm_load_google_maps_api]" value="1" ' . ($is_checked === true ? 'checked="checked"' : '') . ' />';
	}

	public function filter_config_callback()
	{
		$value = '';
		if( isset($this->options['csm_filter_typ'] ) ) {
			$value = $this->options['csm_filter_typ'];
		}

		echo 'Filter Typ: ';
		echo '<select name="casasync_map[csm_filter_typ]">';
		echo '<option value="basic"    ' . ( ($value == 'basic')    ? ('selected="selected"') : ('') ) . ' >Einfach</option>';
		echo '<option value="advanced" ' . ( ($value == 'advanced') ? ('selected="selected"') : ('') ) . ' disabled>Erweitert</option>';
		echo '</select>';


		echo '<hr>';
		echo '<div data-filter-type="basic">';

		//basic filter
		echo '<label>Vermarktungsart</label>';
		echo '<input type="text" value=""/>';


		echo '</div>';
		echo '<div data-filter-type="advanced">';

		//advanced filter


		echo '</div>';

		echo '<hr>';
		$value = '';
		if( isset($this->options['csm_filter_config']) ) {
			$value = json_decode($this->options['csm_filter_config']);
			$value = json_encode($value, JSON_PRETTY_PRINT);
		}
		echo '<textarea id="csm_filter_config" class="large-text code" cols="30" rows="15" name="casasync_map[csm_filter_config]">'.$value.'</textarea>';
	}

	public function infobox_template_callback()
	{
		$value = '';
		if( isset($this->options['csm_infobox_template']) ) {
			$value = $this->options['csm_infobox_template'];
			#$value = json_decode($this->options['csm_infobox_template']);
			#$value = json_encode($value, JSON_PRETTY_PRINT);
		}
		echo '<textarea id="csm_infobox_template" class="large-text code" cols="30" rows="15" name="casasync_map[csm_infobox_template]">'.$value.'</textarea>';
	}

	public function marker_image_callback() {
		$image_src = false;
		$set = false;
		$value = (isset( $this->options['csm_marker_image'] ) ? esc_attr( $this->options['csm_marker_image']) : '');
		if ($value) {
			$image_attributes = wp_get_attachment_image_src( $value, 'full' ); // returns an array
			if ($image_attributes) {
				$set = true;
				$image_src = $image_attributes[0];
			}
		}

		if (!$set) {
			echo '<p>' . __('No Image', 'casasyncmap') . '</p>';
		} else {
			echo '<img src="'.$image_src.'" id="complex_upload_project_image_src" />';
		}
		echo '<br>';
		echo '<input type="hidden" id="casasync_map_upload_marker_image" name="casasync_map[marker_image]" value="'.$value.'" />';
		echo '<input id="casasync_map_upload_marker_image_button" type="button" class="casasync_map_upload button button-primary" value="' . __( 'Upload Image', 'casasyncmap' ) . '" />';
		echo ' <input type="button" class="button delete_media" value="' . __( 'Delete Image', 'casasyncmap' ) . '" />';
	}
}

add_action( 'casasyncmap_init', array( 'casasoft\casasyncmap\general_options', 'init' ) );
