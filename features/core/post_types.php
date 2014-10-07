<?php
namespace casasoft\casasyncmap;

class post_types extends Feature {

	public function __construct() {
		$this->add_action( 'init', 'set_posttypes' );
		if (is_admin()) {
			$this->add_filter( 'dashboard_glance_items', 'glance_items', 10, 1 );
		}
	}

	public function set_posttypes() {
		$labels = array(
			'name'               => _x( 'Apartment Units', 'post type general name', 'casasyncmap' ),
			'singular_name'      => _x( 'Unit', 'post type singular name', 'casasyncmap' ),
			'menu_name'          => _x( 'Apartment Units', 'admin menu', 'casasyncmap' ),
			'name_admin_bar'     => _x( 'Unit', 'add new on admin bar', 'casasyncmap' ),
			'add_new'            => _x( 'Add New', 'unit', 'casasyncmap' ),
			'add_new_item'       => __( 'Add New Unit', 'casasyncmap' ),
			'new_item'           => __( 'New Unit', 'casasyncmap' ),
			'edit_item'          => __( 'Edit Unit', 'casasyncmap' ),
			'view_item'          => __( 'View Unit', 'casasyncmap' ),
			'all_items'          => __( 'All Units', 'casasyncmap' ),
			'search_items'       => __( 'Search Units', 'casasyncmap' ),
			'parent_item_colon'  => __( 'Parent Unit:', 'casasyncmap' ),
			'not_found'          => __( 'No units found.', 'casasyncmap' ),
			'not_found_in_trash' => __( 'No units found in Trash.', 'casasyncmap' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'unit' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => null,
			'supports'           => array( 'title', 'thumbnail','page-attributes', 'editor' ) // 'editor', 'author', 
		);

		register_post_type( 'complex_unit', $args );

		if (is_admin() && function_exists('pti_set_post_type_icon')) {
			pti_set_post_type_icon( 'complex_unit', 'home' );
		}

		$labels = array(
			'name'              => _x( 'Buildings', 'taxonomy general name', 'casasyncmap'  ),
			'singular_name'     => _x( 'Building', 'taxonomy singular name', 'casasyncmap'  ),
			'search_items'      => __( 'Search Buildings', 'casasyncmap'  ),
			'all_items'         => __( 'All Buildings', 'casasyncmap'  ),
			'parent_item'       => __( 'Parent Building', 'casasyncmap'  ),
			'parent_item_colon' => __( 'Parent Building:', 'casasyncmap'  ),
			'edit_item'         => __( 'Edit Building', 'casasyncmap'  ),
			'update_item'       => __( 'Update Building', 'casasyncmap'  ),
			'add_new_item'      => __( 'Add New Building', 'casasyncmap'  ),
			'new_item_name'     => __( 'New Building Name', 'casasyncmap'  ),
			'menu_name'         => __( 'Building', 'casasyncmap'  ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'cm-building' ),
		);
		register_taxonomy( 'building', array( 'complex_unit' ), $args );

		$labels = array(
			'name'              => _x( 'Types', 'taxonomy general name', 'casasyncmap'  ),
			'singular_name'     => _x( 'Type', 'taxonomy singular name', 'casasyncmap'  ),
			'search_items'      => __( 'Search Types', 'casasyncmap'  ),
			'all_items'         => __( 'All Types', 'casasyncmap'  ),
			'parent_item'       => __( 'Parent Type', 'casasyncmap'  ),
			'parent_item_colon' => __( 'Parent Type:', 'casasyncmap'  ),
			'edit_item'         => __( 'Edit Type', 'casasyncmap'  ),
			'update_item'       => __( 'Update Type', 'casasyncmap'  ),
			'add_new_item'      => __( 'Add New Type', 'casasyncmap'  ),
			'new_item_name'     => __( 'New Type Name', 'casasyncmap'  ),
			'menu_name'         => __( 'Types', 'casasyncmap'  ),
		);
		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'cm-unit-type' ),
		);
		register_taxonomy( 'unit_type', array( 'complex_unit' ), $args );

		$labels = array(
			'name'               => _x( 'Inquiries', 'post type general name', 'casasyncmap' ),
			'singular_name'      => _x( 'Inquiry', 'post type singular name', 'casasyncmap' ),
			'menu_name'          => _x( 'Inquiries', 'admin menu', 'casasyncmap' ),
			'name_admin_bar'     => _x( 'Inquiry', 'add new on admin bar', 'casasyncmap' ),
			'add_new'            => _x( 'Add New', 'inquiry', 'casasyncmap' ),
			'add_new_item'       => __( 'Add New Inquiry', 'casasyncmap' ),
			'new_item'           => __( 'New Inquiry', 'casasyncmap' ),
			'edit_item'          => __( 'Edit Inquiry', 'casasyncmap' ),
			'view_item'          => __( 'View Inquiry', 'casasyncmap' ),
			'all_items'          => __( 'All Inquiries', 'casasyncmap' ),
			'search_items'       => __( 'Search Inquiries', 'casasyncmap' ),
			'parent_item_colon'  => __( 'Parent Inquiries:', 'casasyncmap' ),
			'not_found'          => __( 'No inquiries found.', 'casasyncmap' ),
			'not_found_in_trash' => __( 'No inquiries found in Trash.', 'casasyncmap' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'cm-inquiry' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title')
		);

		register_post_type( 'complex_inquiry', $args );

		$labels = array(
			'name'              => _x( 'Reasons', 'taxonomy general name', 'casasyncmap'  ),
			'singular_name'     => _x( 'Reason', 'taxonomy singular name', 'casasyncmap'  ),
			'search_items'      => __( 'Search Reasons', 'casasyncmap'  ),
			'all_items'         => __( 'All Reasons', 'casasyncmap'  ),
			'parent_item'       => __( 'Parent Reason', 'casasyncmap'  ),
			'parent_item_colon' => __( 'Parent Reason:', 'casasyncmap'  ),
			'edit_item'         => __( 'Edit Reason', 'casasyncmap'  ),
			'update_item'       => __( 'Update Reason', 'casasyncmap'  ),
			'add_new_item'      => __( 'Add New Reason', 'casasyncmap'  ),
			'new_item_name'     => __( 'New Reason Name', 'casasyncmap'  ),
			'menu_name'         => __( 'Reasons', 'casasyncmap'  ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'cm-reason' ),
		);
		register_taxonomy( 'inquiry_reason', array( 'complex_inquiry' ), $args );

		if (is_admin() && function_exists('pti_set_post_type_icon')) {
			pti_set_post_type_icon( 'complex_inquiry', 'inbox' );
		}

	}

	public function glance_items( $items = array() ) {
	    $post_types = array( 'complex_unit', 'complex_inquiry' );
	    foreach( $post_types as $type ) {
	        if( ! post_type_exists( $type ) ) continue;
	        $num_posts = wp_count_posts( $type );
	        if( $num_posts ) {
	            $published = intval( $num_posts->publish );
	            $post_type = get_post_type_object( $type );
	            $text = _n( '%s ' . $post_type->labels->name, '%s ' . $post_type->labels->name, $published, 'casasyncmap' );
	            $text = sprintf( $text, number_format_i18n( $published ) );
	            if ( current_user_can( $post_type->cap->edit_posts ) ) {
	            	$output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
	                	echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
	            } else {
	           	 $output = '<span>' . $text . '</span>';
	                echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
	            }
	        }

	        
	    }
	    echo '<style type="text/css">
		    #dashboard_right_now li.complex_unit-count a::before, #dashboard_right_now li.complex_unit-count span::before{
		    	font-family: FontAwesome;
		    	content: \'\f015\' !important; 
		    }
		    #dashboard_right_now li.complex_inquiry-count a::before, #dashboard_right_now li.complex_complex_inquiry-count span::before{
		    	font-family: FontAwesome;
		    	content: \'\f01c\' !important; 
		    }
	    </style>';
	    return $items;
	}

} // End Class


// Subscribe to the drop-in to the initialization event
add_action( 'casasyncmap_init', array( 'casasoft\casasyncmap\post_types', 'init' ) );