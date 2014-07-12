<?php
/*
Plugin Name: LockOn Docs Custom Content Type
Plugin URI: http://www.lockonfasteners.com
Description: Creates the custom content type for Documents
Version: 1.0
Author: Topher
Author URI: http://derosia.com
*/

if ( ! function_exists('lockon_doc_cct') ) {

// Register Custom Post Type
function lockon_doc_cct() {

	$labels = array(
		'name'                => _x( 'Documents', 'Post Type General Name', 'lockon' ),
		'singular_name'       => _x( 'Document', 'Post Type Singular Name', 'lockon' ),
		'menu_name'           => __( 'Documents', 'lockon' ),
		'parent_item_colon'   => __( 'Parent Document:', 'lockon' ),
		'all_items'           => __( 'All Documents', 'lockon' ),
		'view_item'           => __( 'View Document', 'lockon' ),
		'add_new_item'        => __( 'Add New Document', 'lockon' ),
		'add_new'             => __( 'Add New', 'lockon' ),
		'edit_item'           => __( 'Edit Document', 'lockon' ),
		'update_item'         => __( 'Update Document', 'lockon' ),
		'search_items'        => __( 'Search Document', 'lockon' ),
		'not_found'           => __( 'Not found', 'lockon' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'lockon' ),
	);
	$rewrite = array(
		'slug'                => 'document',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'docs', 'lockon' ),
		'description'         => __( 'For rendering documents', 'lockon' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', ),
		'taxonomies'          => array( 'document-type' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'docs', $args );

}

// Hook into the 'init' action
add_action( 'init', 'lockon_doc_cct', 0 );

}

if ( ! function_exists( 'lockon_docs_taxonomy' ) ) {

// Register Custom Taxonomy
function lockon_docs_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Types', 'Taxonomy General Name', 'lockon' ),
		'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'lockon' ),
		'menu_name'                  => __( 'Types', 'lockon' ),
		'all_items'                  => __( 'All Types', 'lockon' ),
		'parent_item'                => __( 'Parent Type', 'lockon' ),
		'parent_item_colon'          => __( 'Parent Type:', 'lockon' ),
		'new_item_name'              => __( 'New Type Name', 'lockon' ),
		'add_new_item'               => __( 'Add New Type', 'lockon' ),
		'edit_item'                  => __( 'Edit Type', 'lockon' ),
		'update_item'                => __( 'Update Type', 'lockon' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'lockon' ),
		'search_items'               => __( 'Search Types', 'lockon' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'lockon' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'lockon' ),
		'not_found'                  => __( 'Not Found', 'lockon' ),
	);
	$rewrite = array(
		'slug'                       => 'document-type',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'lockon_docs_type', array( 'docs' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'lockon_docs_taxonomy', 0 );

}


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function lockon_docs_add_meta_box() {

	$screens = array( 'docs' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'lockon_docs_sectionid',
			__( 'My Post Section Title', 'lockon_docs_textdomain' ),
			'lockon_docs_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'lockon_docs_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function lockon_docs_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'lockon_docs_meta_box', 'lockon_docs_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

	echo '<label for="lockon_docs_new_field">';
	_e( 'Description for this field', 'lockon_docs_textdomain' );
	echo '</label> ';
	echo '<input type="text" id="lockon_docs_new_field" name="lockon_docs_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function lockon_docs_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['lockon_docs_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['lockon_docs_meta_box_nonce'], 'lockon_docs_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['lockon_docs_new_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['lockon_docs_new_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_my_meta_value_key', $my_data );
}
add_action( 'save_post', 'lockon_docs_save_meta_box_data' );
