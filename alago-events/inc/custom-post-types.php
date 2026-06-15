<?php
/**
 * Custom post types and taxonomies: Services, Portfolio, Testimonials.
 *
 * @package Alago_Events
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all custom content types.
 */
function alago_events_register_post_types() {

	// --- Services ---------------------------------------------------------
	register_post_type(
		'ae_service',
		array(
			'labels'       => array(
				'name'               => __( 'Services', 'alago-events' ),
				'singular_name'      => __( 'Service', 'alago-events' ),
				'add_new_item'       => __( 'Add New Service', 'alago-events' ),
				'edit_item'          => __( 'Edit Service', 'alago-events' ),
				'menu_name'          => __( 'Services', 'alago-events' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-heart',
			'rewrite'      => array( 'slug' => 'services' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);

	// --- Portfolio / Real Weddings ---------------------------------------
	register_post_type(
		'ae_portfolio',
		array(
			'labels'       => array(
				'name'          => __( 'Portfolio', 'alago-events' ),
				'singular_name' => __( 'Portfolio Item', 'alago-events' ),
				'add_new_item'  => __( 'Add New Portfolio Item', 'alago-events' ),
				'edit_item'     => __( 'Edit Portfolio Item', 'alago-events' ),
				'menu_name'     => __( 'Portfolio', 'alago-events' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-format-gallery',
			'rewrite'      => array( 'slug' => 'portfolio' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'ae_portfolio_category',
		'ae_portfolio',
		array(
			'labels'            => array(
				'name'          => __( 'Portfolio Categories', 'alago-events' ),
				'singular_name' => __( 'Portfolio Category', 'alago-events' ),
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'portfolio-category' ),
		)
	);

	// --- Testimonials -----------------------------------------------------
	register_post_type(
		'ae_testimonial',
		array(
			'labels'       => array(
				'name'          => __( 'Testimonials', 'alago-events' ),
				'singular_name' => __( 'Testimonial', 'alago-events' ),
				'add_new_item'  => __( 'Add New Testimonial', 'alago-events' ),
				'edit_item'     => __( 'Edit Testimonial', 'alago-events' ),
				'menu_name'     => __( 'Testimonials', 'alago-events' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'exclude_from_search' => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'alago_events_register_post_types' );

/**
 * Add a "Couple / Location" meta box for testimonials.
 */
function alago_events_testimonial_meta_box() {
	add_meta_box(
		'ae_testimonial_meta',
		__( 'Testimonial Details', 'alago-events' ),
		'alago_events_testimonial_meta_box_cb',
		'ae_testimonial',
		'side'
	);
}
add_action( 'add_meta_boxes', 'alago_events_testimonial_meta_box' );

/**
 * Render the testimonial meta box.
 *
 * @param WP_Post $post Current post.
 */
function alago_events_testimonial_meta_box_cb( $post ) {
	wp_nonce_field( 'ae_testimonial_meta', 'ae_testimonial_nonce' );
	$role     = get_post_meta( $post->ID, '_ae_testimonial_role', true );
	$location = get_post_meta( $post->ID, '_ae_testimonial_location', true );
	?>
	<p>
		<label for="ae_testimonial_role"><strong><?php esc_html_e( 'Names (e.g. Sofia &amp; James)', 'alago-events' ); ?></strong></label>
		<input type="text" id="ae_testimonial_role" name="ae_testimonial_role" value="<?php echo esc_attr( $role ); ?>" style="width:100%;" />
	</p>
	<p>
		<label for="ae_testimonial_location"><strong><?php esc_html_e( 'Location / Venue', 'alago-events' ); ?></strong></label>
		<input type="text" id="ae_testimonial_location" name="ae_testimonial_location" value="<?php echo esc_attr( $location ); ?>" style="width:100%;" />
	</p>
	<?php
}

/**
 * Save the testimonial meta.
 *
 * @param int $post_id Post ID.
 */
function alago_events_save_testimonial_meta( $post_id ) {
	if ( ! isset( $_POST['ae_testimonial_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ae_testimonial_nonce'] ) ), 'ae_testimonial_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['ae_testimonial_role'] ) ) {
		update_post_meta( $post_id, '_ae_testimonial_role', sanitize_text_field( wp_unslash( $_POST['ae_testimonial_role'] ) ) );
	}
	if ( isset( $_POST['ae_testimonial_location'] ) ) {
		update_post_meta( $post_id, '_ae_testimonial_location', sanitize_text_field( wp_unslash( $_POST['ae_testimonial_location'] ) ) );
	}
}
add_action( 'save_post_ae_testimonial', 'alago_events_save_testimonial_meta' );

/**
 * Flush rewrite rules on theme activation so CPT permalinks work.
 */
function alago_events_rewrite_flush() {
	alago_events_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'alago_events_rewrite_flush' );
