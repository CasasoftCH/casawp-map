<?php
namespace casasoft\casasyncmap;


class unit_metabox extends Feature {

	public $prefix = 'casasyncmap_unit_';

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_action( 'admin_enqueue_scripts', array($this, 'js_enqueue' ));
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
        $post_types = array('complex_unit'); //limit meta box to certain post types
        if ( in_array( $post_type, $post_types )) {
			add_meta_box(
				'casasyncmap_unit_box'
				,__( 'Unit Settings', 'casasyncmap' )
				,array( $this, 'render_meta_box_content' )
				,$post_type
				,'normal'
				,'core'
			);
        }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['casasyncmap_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['casasyncmap_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'casasyncmap_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		/* OK, its safe for us to save the data now. */

		if ('complex_unit' == $_POST['post_type']) {
			$texts = array(
				$this->prefix.'number_of_rooms',
				$this->prefix.'story',
				$this->prefix.'status',
				$this->prefix.'purchase_price',
				$this->prefix.'purchase_price_propertysegment',
				$this->prefix.'rent_net',
				$this->prefix.'rent_timesegment',
				$this->prefix.'rent_propertysegment',
				$this->prefix.'currency',
				$this->prefix.'document',
				$this->prefix.'graphic_hover_color',
				$this->prefix.'graphic_poly',
				$this->prefix.'living_space',
				$this->prefix.'usable_space',
				$this->prefix.'terrace_space',
				$this->prefix.'balcony_space',
				$this->prefix.'idx_ref_house',
				$this->prefix.'idx_ref_object',
				$this->prefix.'extra_costs',
			);

			foreach ($texts as $key) {
				if (isset($_POST[$key] )) {
					$mydata = sanitize_text_field( $_POST[$key] );
					update_post_meta( $post_id, '_'.$key, $mydata );
				}
			}

		}
	}

	public function js_enqueue() {
	    global $typenow;
	    if( $typenow == 'complex_unit' ) {
	        wp_enqueue_media();
	 		
	 		wp_register_script( 'jquery-canvasareadraw', PLUGIN_URL . 'assets/js/jquery.canvasAreaDraw.min.js', array('jquery'));

	        // Registers and enqueues the required javascript.
	        wp_register_script( 'casasyncmap-meta-box', PLUGIN_URL.'/assets/js/casasyncmap-meta-box.js' , array( 'jquery', 'wp-color-picker', 'jquery-canvasareadraw' ) );
	        wp_localize_script( 'casasyncmap-meta-box', 'i18n',
	            array(
	                'title' => __( 'Choose or Upload a Document', 'casasyncmap' ),
	                'button' => __( 'Use this document', 'casasyncmap' ),
	            )
	        );
	        wp_enqueue_script( 'jquery-canvasareadraw' );
	        wp_enqueue_script( 'casasyncmap-meta-box' );

	        wp_enqueue_style( 'wp-color-picker' ); 

	    }
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'casasyncmap_inner_custom_box', 'casasyncmap_inner_custom_box_nonce' );
		

        $value = get_post_meta( $post->ID, '_casasyncmap_unit_graphic_hover_color', true );
		echo '<p><label for="casasyncmap_unit_graphic_hover_color">';
		_e( 'Hover color', 'casasyncmap' );
		echo '</label><br>';
		echo '<input type="text" id="casasyncmap_unit_graphic_hover_color" name="casasyncmap_unit_graphic_hover_color"';
                echo ' value="' . esc_attr( $value ) . '" size="25" />';
        echo '</p>';

       	$value = get_post_meta( $post->ID, '_casasyncmap_unit_graphic_poly', true );
        $image_src = PLUGIN_URL.'assets/img/example-project-bg.png';
        $project_image_id = $this->get_option("project_image");
        if ($project_image_id) {
            $image_attributes = wp_get_attachment_image_src( $project_image_id, 'full' ); // returns an array
            if ($image_attributes) {
                $set = true;
                $image_src = $image_attributes[0];
            }
        }
        
        echo '<div class="comlexmanager-polyhelper">
        		<textarea id="casasyncmap_unit_graphic_poly" name="casasyncmap_unit_graphic_poly" data-image-url="'.$image_src.'">
        		'.$value.'
        		</textarea>
        	</div>';

        echo "<hr>";

		echo '<div class="casasyncmap-meta-row">';
			echo '<div class="casasyncmap-meta-col">';
				echo "<h3>". __('General', 'casasyncmap'). "</h3>";

				$value = get_post_meta( $post->ID, '_casasyncmap_unit_status', true );
		        echo '<p><label for="casasyncmap_unit_status">';
				_e( 'Status', 'casasyncmap' );
				echo '</label><br>';
				echo '<select id="casasyncmap_unit_status" name="casasyncmap_unit_status">';
					echo '<option value="available" ' . ($value == 'available' ? 'selected' : '') . '>' . __('Available', 'casasyncmap') . '</option>';
					echo '<option value="reserved" '  . ($value == 'reserved' ? 'selected' : '') . '>'  . __('Reserved', 'casasyncmap') . '</option>';
					echo '<option value="sold" '      . ($value == 'sold' ? 'selected' : '') . '>'      . __('Sold', 'casasyncmap') . '</option>';
		        echo '</select>';
		        echo '</p>';

				$value = get_post_meta( $post->ID, '_casasyncmap_unit_number_of_rooms', true );
				echo '<p><label for="casasyncmap_unit_number_of_rooms">';
				_e( 'Number of Rooms', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="0.5" min="0" id="casasyncmap_unit_number_of_rooms" name="casasyncmap_unit_number_of_rooms"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />';
		        echo '</p>';


		        $value = get_post_meta( $post->ID, '_casasyncmap_unit_story', true );
		        echo '<p><label for="casasyncmap_unit_story">';
				_e( 'Apartment Story', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="text" id="casasyncmap_unit_story" name="casasyncmap_unit_story" placeholder="1. OG"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />';
		        //echo '<br> (' . __('0 = EG, +1 = 1. OG, -1 = 1. UG', 'casasyncmap' ) . ')';
		        echo '</p>';


		        $key = $this->prefix.'idx_ref_house';
				$value = get_post_meta( $post->ID, '_'.$key, true );
				echo '<p><label for="'.$key.'">';
				_e( 'IDX / REMCat House Ref.', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="1" min="0"  id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />';
		        echo '</p>';

		        $key = $this->prefix.'idx_ref_object';
				$value = get_post_meta( $post->ID, '_'.$key, true );
				echo '<p><label for="'.$key.'">';
				_e( 'IDX / REMCat Object Ref.', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="1" min="0"  id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />';
		        echo '</p>';


		        

		    echo "</div>";
		    echo '<div class="casasyncmap-meta-col">';
		    	echo "<h3>". __('Spaces', 'casasyncmap'). " m<sup>2</sup></h3>";
		       
		        $key = $this->prefix.'living_space';
				$value = get_post_meta( $post->ID, '_'.$key, true );
				echo '<p><label for="'.$key.'">';
				_e( 'Living Space', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="0.1" min="0"  id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />&nbsp;m<sup>2</sup>';
		        echo '</p>';

		        $key = $this->prefix.'usable_space';
				$value = get_post_meta( $post->ID, '_'.$key, true );
				echo '<p><label for="'.$key.'">';
				_e( 'Usable Space', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="0.1" min="0" id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />&nbsp;m<sup>2</sup>';
		        echo '</p>';


		        $key = $this->prefix.'terrace_space';
				$value = get_post_meta( $post->ID, '_'.$key, true );
				echo '<p><label for="'.$key.'">';
				_e( 'Terrace Space', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="0.1" min="0" id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />&nbsp;m<sup>2</sup>';
		        echo '</p>';

		        $key = $this->prefix.'balcony_space';
				$value = get_post_meta( $post->ID, '_'.$key, true );
				echo '<p><label for="'.$key.'">';
				_e( 'Balcony Space', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="0.1" min="0" id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />&nbsp;m<sup>2</sup>';
		        echo '</p>';

		    echo "</div>";
		    echo '<div style="clear:both"></div>';
        echo "</div>"; 	

        echo "<hr>";

		echo '<div class="casasyncmap-meta-row">';
			echo '<div class="casasyncmap-meta-col">';
				echo "<h3>". __('Buy', 'casasyncmap'). "</h3>";

				$value = get_post_meta( $post->ID, '_casasyncmap_unit_currency', true );
		        echo '<p><label for="casasyncmap_unit_currency">';
				_e( 'Currency', 'casasyncmap' );
				echo '</label><br>';
				echo '<select id="casasyncmap_unit_currency" name="casasyncmap_unit_currency">';
					echo '<option value="CHF" ' . ($value == 'CHF' ? 'selected' : '') . '>CHF</option>';
					echo '<option value="EUR" ' . ($value == 'EUR' ? 'selected' : '') . '>€</option>';
					echo '<option value="USD" ' . ($value == 'USD' ? 'selected' : '') . '>$</option>';
					echo '<option value="GBP" ' . ($value == 'GBP' ? 'selected' : '') . '>£</option>';
		        echo '</select>';
		        echo '</p>';

        		$key = $this->prefix.'purchase_price';
		        $value = get_post_meta( $post->ID, '_'.$key, true );
		        echo '<p><label for="'.$key.'">';
				_e( 'Purchase Price', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="1" id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />';
		        echo '<br> (' . __('"" = not for sale, 0 = upon request', 'casasyncmap') . ')';
		        echo '</p>';

		        $key = $this->prefix.'purchase_price_propertysegment';
		        $value = get_post_meta( $post->ID, '_'.$key, true );
		        echo '<p><label for="'.$key.'">';
				_e( 'Purchase scope', 'casasyncmap' );
				echo '</label><br>';
				echo '<select id="'.$key.'" name="'.$key.'">';
					echo '<option value="full" ' . ($value == 'full' ? 'selected' : '') . '>Full price</option>';
					echo '<option value="M2" ' . ($value == 'M2' ? 'selected' : '') . '>per M2</option>';
		        echo '</select>';
		        echo '</p>';

		    echo "</div>";
		   	echo '<div class="casasyncmap-meta-col">';
		   		echo "<h3>". __('Rent', 'casasyncmap'). "</h3>";

		   		$key = $this->prefix.'rent_net';
		        $value = get_post_meta( $post->ID, '_'.$key, true );
		        echo '<p><label for="'.$key.'">';
				_e( 'Rent Net Price', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="1" id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />';
		        echo '<br> (' . __('"" = not for rent, 0 = upon request', 'casasyncmap') . ')';
		        echo '</p>';

		        $key = $this->prefix.'rent_timesegment';
		        $value = get_post_meta( $post->ID, '_'.$key, true );
		        echo '<p><label for="'.$key.'">';
				_e( 'Rent Time segment', 'casasyncmap' );
				echo '</label><br>';
				echo '<select id="'.$key.'" name="'.$key.'">';
					echo '<option value="M" ' . ($value == 'M' ? 'selected' : '') . '>Month</option>';
					echo '<option value="W" ' . ($value == 'W' ? 'selected' : '') . '>Week</option>';
		        echo '</select>';
		        echo '</p>';

		        $key = $this->prefix.'rent_propertysegment';
		        $value = get_post_meta( $post->ID, '_'.$key, true );
		        echo '<p><label for="'.$key.'">';
				_e( 'Rental scope', 'casasyncmap' );
				echo '</label><br>';
				echo '<select id="'.$key.'" name="'.$key.'">';
					echo '<option value="full" ' . ($value == 'full' ? 'selected' : '') . '>Full price</option>';
					echo '<option value="M2" ' . ($value == 'M2' ? 'selected' : '') . '>per M2</option>';
		        echo '</select>';
		        echo '</p>';

		        $key = $this->prefix.'extra_costs';
		        $value = get_post_meta( $post->ID, '_'.$key, true );
		        echo '<p><label for="'.$key.'">';
				_e( 'Extra Costs', 'casasyncmap' );
				echo '</label><br>';
				echo '<input type="number" step="1" min="0" id="'.$key.'" name="'.$key.'"';
		                echo ' value="' . esc_attr( $value ) . '" size="25" />';
		        echo '</p>';

		 	echo "</div>";
		    echo '<div style="clear:both"></div>';
        echo "</div>"; 	


        /* echo "<hr>";


        $value = get_post_meta( $post->ID, '_casasyncmap_unit_document', true );
        echo '<p>
		    <label for="casasyncmap_unit_document">'.__( 'No file chosen', 'casasyncmap' ).'</label>
		    <input type="button" id="casasyncmap_unit_document-button" class="button" value="' . __( 'Add file', 'casasyncmap' ).'" />
		</p>';
		echo '
			<div class="cxm-file-uploader clearfix  active" data-library="all">
				<input type="text" name="casasyncmap_unit_document" id="casasyncmap_unit_document-file" value="' . $value . '" />
				<div class="has-file">
					<ul class="hl clearfix">
						<li>
							<img class="cxm-file-icon" src="/wp-includes/images/media/default.png" alt="">
							<div class="hover">
								<ul class="bl">
									<li><a href="#" class="cxm-button-delete"><i></i></a></li>
									<li><a href="#" class="cxm-button-edit"><i></i></a></li>
								</ul>
							</div>
						</li>
						<li>
							<p>
								<strong class="cxm-file-title">Bristle Grass</strong>
							</p>
							<p>
								<strong>Name:</strong>
								<a class="cxm-file-name" href="http://wordpress.local/wp-content/uploads/2014/09/Bristle-Grass.jpg" target="_blank">Bristle-Grass.jpg</a>
							</p>
							<p>
								<strong>Size:</strong>
								<span class="cxm-file-size">4 MB</span>
							</p>
							
						</li>
					</ul>
				</div>
			</div>
		';*/


		echo '<div style="clear:both"></div>';




	

		

		//casasyncmap-unit-document-upload.js


	}
}

add_action( 'load-post.php', array( 'casasoft\casasyncmap\unit_metabox', 'init' )  );
add_action( 'load-post-new.php', array( 'casasoft\casasyncmap\unit_metabox', 'init' ) );
