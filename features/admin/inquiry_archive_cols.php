<?php
namespace casasoft\casasyncmap;


class inquiry_archive_cols extends Feature {

	public function __construct() {
		add_filter( 'manage_edit-complex_inquiry_columns', array( $this, 'editColumns' )) ;
		add_action( 'manage_complex_inquiry_posts_custom_column', array( $this, 'manageColumns' ));
	}

	public function editColumns($columns){
		$columns = array(
			'cb' 		=> '<input type="checkbox" />',
			'title' 	=> __( 'From', 'casasyncmap' ),
			'address' 	=> __( 'Address', 'casasyncmap' ),
			'email' 	=> __( 'E-Mail', 'casasyncmap' ),
			'phone' 	=> __( 'Telefon', 'casasyncmap' ),
			'date' => __( 'Date')
		);

		return $columns;
	}

	public function manageColumns( $column ) {
		global $post;


		switch( $column ) {
			case 'address' :
				echo get_cxm($post->ID, 'address_html');
				break;
			case 'email' :
				echo get_cxm($post->ID, 'email');
				break;
			case 'phone' :
				echo get_cxm($post->ID, 'phone');
				break;
			default :
				break;
		}
	}
}
add_action( 'casasyncmap_init', array( 'casasoft\casasyncmap\inquiry_archive_cols', 'init' ));