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
			'My custom settings', // Title
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
			 __( 'Marker image', 'casasyncmap' ), 
			array( $this, 'marker_image_callback' ), 
			'my-setting-admin', 
			'setting_section_id'
		);

		add_settings_field(
			'csm_filter_type', 
			 __( 'Filter type', 'casasyncmap' ), 
			array( $this, 'filter_type_callback' ), 
			'my-setting-admin', 
			'setting_section_id'
		);
		add_settings_field(
			'csm_map_viewport', 
			 __( 'Map viewport', 'casasyncmap' ), 
			array( $this, 'map_viewport_callback' ), 
			'my-setting-admin', 
			'setting_section_id'
		);
		add_settings_field(
			'csm_filter_basic', 
			 __( 'Filter-settings', 'casasyncmap' ), 
			array( $this, 'filter_basic_callback' ), 
			'my-setting-admin', 
			'setting_section_id'
		);

		add_settings_field(
			'csm_filter_advanced', 
			 __( 'Advanced filter-settings', 'casasyncmap' ), 
			array( $this, 'filter_advanced_callback' ), 
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
		if( isset( $input['csm_filter_basic'] ) ) {
			$new_input['csm_filter_basic'] = sanitize_text_field( $input['csm_filter_basic'] );
		}
		if( isset( $input['csm_filter_advanced'] ) ) {
			$new_input['csm_filter_advanced'] = sanitize_text_field( $input['csm_filter_advanced'] );
		}
		if( isset( $input['csm_filter_type'] ) ) {
			$new_input['csm_filter_type'] = sanitize_text_field( $input['csm_filter_type'] );
		}
		if( isset( $input['csm_map_viewport'] ) ) {
			$new_input['csm_map_viewport'] = sanitize_text_field( $input['csm_map_viewport'] );
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

	public function filter_type_callback(){
		$value = '';
		if( isset($this->options['csm_filter_typ'] ) ) {
			$value = $this->options['csm_filter_typ'];
		}


		echo '<select name="casasync_map[csm_filter_typ]">';
		echo '<option value="basic"    ' . ( ($value == 'basic')    ? ('selected="selected"') : ('') ) . ' >Einfach</option>';
		echo '<option value="advanced" ' . ( ($value == 'advanced') ? ('selected="selected"') : ('') ) . ' >Erweitert</option>';
		echo '</select>';
	}

	public function map_viewport_callback(){
		$value = '';
		if( isset($this->options['csm_map_viewport'] ) ) {
			$value = $this->options['csm_map_viewport'];
		}


		echo '<select name="casasync_map[csm_map_viewport]">';
		echo '<option value="fitbounds"    ' . ( ($value == 'fitbounds')    ? ('selected="selected"') : ('') ) . ' >' . __( 'Show all markers', 'casasyncmap' ) . '</option>';
		echo '<option value="switzerland" ' . ( ($value == 'switzerland') ? ('selected="selected"') : ('') ) . ' >' . __( 'Show Switzerland', 'casasyncmap' ) . '</option>';
		echo '</select>';
	}

	public function filter_advanced_callback(){

	}

	public function filter_basic_callback()
	{

		$segments = array(
			"casasync_salestype" => array(
				"title" => "Salestype",
				"tax" => "casasync_salestype"
				),
			"casasync_availability" => array(
				"title" => "Availability",
				"tax" => "casasync_availability"
				),
			"casasync_category" => array(
				"title" => "Category",
				"tax" => "casasync_category"
				),
			"casasync_location" => array(
				"title" => "Location",
				"tax" => "casasync_location"
				),
		);

		$value = '';
		$value = json_decode($this->options['csm_filter_basic']);

		
		
		

		echo '<hr>';
		echo '<div data-filter-type="basic">';
		
		//basic filter
		echo "<table id='CsmFilterBasicTable'>";
			echo "<thead><tr>";
				echo "<th>";
					echo 'Kategorie';
				echo "</th>";
				echo "<th>";
					echo 'Anzeigen';
				echo "</th>";
				echo "<th>";
					echo 'Beschriftung';
				echo "</th>";
					
				echo "<th>";
					echo 'Include / Exclude';
				echo "</th>";

				echo "<th>";
					echo 'Terms';
				echo "</th>";
			echo "</tr></thead><tbody>";
		$i = 0;
		foreach ($segments as $segment) {

			if (taxonomy_exists($segment['tax'])) {

				$tax = get_taxonomy( $segment['tax'] );

				echo "<tr class='data-row'>";
					echo "<td>";
					echo "<input type='hidden' name='filters[$i][taxonomy]' value='".$segment['tax']."'>";
						echo '<label>' .  __($tax->labels->singular_name , 'casasyncmap') . '</label>';
					echo "</td>";
					echo "<td>";
						echo "<input type='checkbox' name='filters[$i][visible]' value='true' " . ( ($value[$i]->visible == 1) ? ('checked') : ('') ) . ">";
					echo "</td>";
					echo "<td>";
						echo "<input type='text' name='filters[$i][label]' value='" . ( ($value[$i]->label) ? ($value[$i]->label) : (__($tax->labels->singular_name , "casasyncmap")) ) . "'>";
					echo "</td>";
					echo "<td>";
						echo "<select name='filters[$i][inclusive]'>";
						echo "<option value='1' " . ( ($value[$i]->inclusive) ? ("selected") : ("")) . ">Include</option>";
						echo "<option value='0' " . ( (!$value[$i]->inclusive) ? ("selected") : ("")) . ">Exclude</option>";
						echo "</select>";
					echo "</td>";
					echo "<td>";
						echo "<input type='text' name='filters[$i][filter_terms]' value='" . ( ($value[$i]->filter_terms) ? ($value[$i]->filter_terms) : ("")) . "'>";
					echo "</td>";
				echo "</tr>";
			}

			$i++;
		}
		echo "</tbody></table>";
		


		echo '</div>';
		echo '<div data-filter-type="advanced">';

		//advanced filter


		echo '</div>';

		echo '<hr>';
		

		$value = '';
		if( isset($this->options['csm_filter_basic']) ) {
			$value = json_decode($this->options['csm_filter_basic']);
			$value = json_encode($value, JSON_PRETTY_PRINT);
		} 
		echo '<textarea id="csm_filter_basic" class="large-text code" cols="30" rows="15" name="casasync_map[csm_filter_basic]">'.$value.'</textarea>';
			
		
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
