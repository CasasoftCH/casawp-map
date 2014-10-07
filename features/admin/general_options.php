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
        add_action( 'admin_menu', array( $this, 'set_standard_terms' ) );
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

                <button class="button button-default" type="submit" name="generate_defaults" value="true">Regenerate Default Terms</button>
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
            'load_google_maps_api', 
             __( 'Google Maps API', 'casasyncmap' ), 
            array( $this, 'load_google_maps_api_callback' ), 
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

        if( isset( $input['load_google_maps_api'] ) ) {
            $new_input['load_google_maps_api'] = sanitize_text_field( $input['load_google_maps_api'] );
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
        if( isset($this->options['load_google_maps_api'] ) && $this->options['load_google_maps_api'] == 1) {
            $is_checked = true;
        }
        echo '<input type="hidden" name="casasync_map[load_google_maps_api]" value="0" />';
        echo '<input type="checkbox" id="load_google_maps_api" name="casasync_map[load_google_maps_api]" value="1" ' . ($is_checked === true ? 'checked="checked"' : '') . ' />';
    }

    public function set_standard_terms(){
        if (isset($_GET['generate_defaults']) || isset($_POST['generate_defaults'])) {
            wp_insert_term( 'Suchmaschinen', 'inquiry_reason', $args = array() );
            wp_insert_term( 'Immobilienplattform', 'inquiry_reason', $args = array() );
            wp_insert_term( 'Events / Anzeigen', 'inquiry_reason', $args = array() );
            wp_insert_term( 'Persönlich vorgeschlagen', 'inquiry_reason', $args = array() );
        }
    }
}

add_action( 'casasyncmap_init', array( 'casasoft\casasyncmap\general_options', 'init' ) );
