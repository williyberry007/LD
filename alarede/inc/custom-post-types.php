<?php
/**
 * Custom post types and taxonomies: Services, Portfolio, Testimonials.
 *
 * @package Alarede
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all custom content types.
 */
function alarede_register_post_types() {

	// --- Services ---------------------------------------------------------
	register_post_type(
		'ae_service',
		array(
			'labels'       => array(
				'name'               => __( 'Services', 'alarede' ),
				'singular_name'      => __( 'Service', 'alarede' ),
				'add_new_item'       => __( 'Add New Service', 'alarede' ),
				'edit_item'          => __( 'Edit Service', 'alarede' ),
				'menu_name'          => __( 'Services', 'alarede' ),
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
				'name'          => __( 'Portfolio', 'alarede' ),
				'singular_name' => __( 'Portfolio Item', 'alarede' ),
				'add_new_item'  => __( 'Add New Portfolio Item', 'alarede' ),
				'edit_item'     => __( 'Edit Portfolio Item', 'alarede' ),
				'menu_name'     => __( 'Portfolio', 'alarede' ),
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
				'name'          => __( 'Portfolio Categories', 'alarede' ),
				'singular_name' => __( 'Portfolio Category', 'alarede' ),
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
				'name'          => __( 'Testimonials', 'alarede' ),
				'singular_name' => __( 'Testimonial', 'alarede' ),
				'add_new_item'  => __( 'Add New Testimonial', 'alarede' ),
				'edit_item'     => __( 'Edit Testimonial', 'alarede' ),
				'menu_name'     => __( 'Testimonials', 'alarede' ),
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
add_action( 'init', 'alarede_register_post_types' );

/**
 * Add a "Couple / Location" meta box for testimonials.
 */
function alarede_testimonial_meta_box() {
	add_meta_box(
		'ae_testimonial_meta',
		__( 'Testimonial Details', 'alarede' ),
		'alarede_testimonial_meta_box_cb',
		'ae_testimonial',
		'side'
	);
}
add_action( 'add_meta_boxes', 'alarede_testimonial_meta_box' );

/**
 * Render the testimonial meta box.
 *
 * @param WP_Post $post Current post.
 */
function alarede_testimonial_meta_box_cb( $post ) {
	wp_nonce_field( 'ae_testimonial_meta', 'ae_testimonial_nonce' );
	$role     = get_post_meta( $post->ID, '_ae_testimonial_role', true );
	$location = get_post_meta( $post->ID, '_ae_testimonial_location', true );
	?>
	<p>
		<label for="ae_testimonial_role"><strong><?php esc_html_e( 'Names (e.g. Sofia &amp; James)', 'alarede' ); ?></strong></label>
		<input type="text" id="ae_testimonial_role" name="ae_testimonial_role" value="<?php echo esc_attr( $role ); ?>" style="width:100%;" />
	</p>
	<p>
		<label for="ae_testimonial_location"><strong><?php esc_html_e( 'Location / Venue', 'alarede' ); ?></strong></label>
		<input type="text" id="ae_testimonial_location" name="ae_testimonial_location" value="<?php echo esc_attr( $location ); ?>" style="width:100%;" />
	</p>
	<?php
}

/**
 * Save the testimonial meta.
 *
 * @param int $post_id Post ID.
 */
function alarede_save_testimonial_meta( $post_id ) {
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
add_action( 'save_post_ae_testimonial', 'alarede_save_testimonial_meta' );

/**
 * Register the Hero Slides post type used by the homepage hero slider.
 * Each slide supports either an uploaded image or a video (self-hosted MP4
 * or a YouTube/Vimeo URL).
 */
function alarede_register_slides() {
	register_post_type(
		'ae_slide',
		array(
			'labels'       => array(
				'name'          => __( 'Hero Slides', 'alarede' ),
				'singular_name' => __( 'Hero Slide', 'alarede' ),
				'add_new_item'  => __( 'Add New Slide', 'alarede' ),
				'edit_item'     => __( 'Edit Slide', 'alarede' ),
				'menu_name'     => __( 'Hero Slides', 'alarede' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-images-alt2',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'alarede_register_slides' );

/**
 * Register the Clients post type (logo = featured image) for the homepage
 * clients carousel.
 */
function alarede_register_clients() {
	register_post_type(
		'ae_client',
		array(
			'labels'              => array(
				'name'          => __( 'Clients', 'alarede' ),
				'singular_name' => __( 'Client', 'alarede' ),
				'add_new_item'  => __( 'Add New Client', 'alarede' ),
				'edit_item'     => __( 'Edit Client', 'alarede' ),
				'menu_name'     => __( 'Clients', 'alarede' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'alarede_register_clients' );

/**
 * Register the Jobs post type for the Careers page (full details + apply link).
 */
function alarede_register_jobs() {
	register_post_type(
		'ae_job',
		array(
			'labels'       => array(
				'name'          => __( 'Jobs', 'alarede' ),
				'singular_name' => __( 'Job', 'alarede' ),
				'add_new_item'  => __( 'Add New Job', 'alarede' ),
				'edit_item'     => __( 'Edit Job', 'alarede' ),
				'menu_name'     => __( 'Jobs', 'alarede' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-businessperson',
			'rewrite'      => array( 'slug' => 'jobs' ),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'alarede_register_jobs' );

/**
 * Job meta box: employment details + apply link.
 */
function alarede_job_meta_box() {
	add_meta_box( 'ae_job_meta', __( 'Job Details', 'alarede' ), 'alarede_job_meta_box_cb', 'ae_job', 'side' );
}
add_action( 'add_meta_boxes', 'alarede_job_meta_box' );

/**
 * Render the job meta box.
 *
 * @param WP_Post $post Current post.
 */
function alarede_job_meta_box_cb( $post ) {
	wp_nonce_field( 'ae_job_meta', 'ae_job_nonce' );
	$meta  = get_post_meta( $post->ID, '_ae_job_meta', true );
	$apply = get_post_meta( $post->ID, '_ae_job_apply', true );
	?>
	<p>
		<label for="ae_job_meta"><strong><?php esc_html_e( 'Employment Detail', 'alarede' ); ?></strong></label>
		<input type="text" id="ae_job_meta" name="ae_job_meta" value="<?php echo esc_attr( $meta ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( 'Full-time · Hybrid', 'alarede' ); ?>" />
	</p>
	<p>
		<label for="ae_job_apply"><strong><?php esc_html_e( 'Apply Link (URL or email)', 'alarede' ); ?></strong></label>
		<input type="text" id="ae_job_apply" name="ae_job_apply" value="<?php echo esc_attr( $apply ); ?>" style="width:100%;" placeholder="https://… or jobs@example.com" />
		<span class="description"><?php esc_html_e( 'Leave blank to use the Contact page. Use the post content for the full job description.', 'alarede' ); ?></span>
	</p>
	<?php
}

/**
 * Save the job meta.
 *
 * @param int $post_id Post ID.
 */
function alarede_save_job_meta( $post_id ) {
	if ( ! isset( $_POST['ae_job_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ae_job_nonce'] ) ), 'ae_job_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['ae_job_meta'] ) ) {
		update_post_meta( $post_id, '_ae_job_meta', sanitize_text_field( wp_unslash( $_POST['ae_job_meta'] ) ) );
	}
	if ( isset( $_POST['ae_job_apply'] ) ) {
		update_post_meta( $post_id, '_ae_job_apply', sanitize_text_field( wp_unslash( $_POST['ae_job_apply'] ) ) );
	}
}
add_action( 'save_post_ae_job', 'alarede_save_job_meta' );

/**
 * Resolve a job's apply URL (mailto for emails), falling back to the Contact page.
 *
 * @param int $post_id Job ID.
 * @return string
 */
function alarede_job_apply_url( $post_id ) {
	$apply = get_post_meta( $post_id, '_ae_job_apply', true );
	if ( ! $apply ) {
		return home_url( '/contact/' );
	}
	if ( is_email( $apply ) ) {
		return 'mailto:' . $apply;
	}
	return $apply;
}

/**
 * Slide media + content meta box.
 */
function alarede_slide_meta_box() {
	add_meta_box( 'ae_slide_meta', __( 'Slide Settings', 'alarede' ), 'alarede_slide_meta_box_cb', 'ae_slide' );
}
add_action( 'add_meta_boxes', 'alarede_slide_meta_box' );

/**
 * Render the slide meta box.
 *
 * @param WP_Post $post Current post.
 */
function alarede_slide_meta_box_cb( $post ) {
	wp_nonce_field( 'ae_slide_meta', 'ae_slide_nonce' );
	$fields = array(
		'media_type'  => get_post_meta( $post->ID, '_ae_slide_media_type', true ),
		'video_url'   => get_post_meta( $post->ID, '_ae_slide_video_url', true ),
		'kicker'      => get_post_meta( $post->ID, '_ae_slide_kicker', true ),
		'btn1_text'   => get_post_meta( $post->ID, '_ae_slide_btn1_text', true ),
		'btn1_url'    => get_post_meta( $post->ID, '_ae_slide_btn1_url', true ),
		'btn2_text'   => get_post_meta( $post->ID, '_ae_slide_btn2_text', true ),
		'btn2_url'    => get_post_meta( $post->ID, '_ae_slide_btn2_url', true ),
	);
	$media_type = $fields['media_type'] ? $fields['media_type'] : 'image';
	?>
	<style>.ae-slide-fields p{margin:.9em 0}.ae-slide-fields label{display:block;font-weight:600;margin-bottom:.25em}.ae-slide-fields input,.ae-slide-fields select{width:100%;max-width:520px}</style>
	<div class="ae-slide-fields">
		<p>
			<label for="ae_slide_media_type"><?php esc_html_e( 'Background Media', 'alarede' ); ?></label>
			<select id="ae_slide_media_type" name="ae_slide_media_type">
				<option value="image" <?php selected( $media_type, 'image' ); ?>><?php esc_html_e( 'Image (use the Featured Image →)', 'alarede' ); ?></option>
				<option value="video" <?php selected( $media_type, 'video' ); ?>><?php esc_html_e( 'Video (enter a URL below)', 'alarede' ); ?></option>
			</select>
		</p>
		<p>
			<label for="ae_slide_video_url"><?php esc_html_e( 'Video URL (MP4, YouTube or Vimeo)', 'alarede' ); ?></label>
			<input type="url" id="ae_slide_video_url" name="ae_slide_video_url" value="<?php echo esc_attr( $fields['video_url'] ); ?>" placeholder="https://…/video.mp4" />
			<span class="description"><?php esc_html_e( 'A self-hosted .mp4 plays as a muted background loop. A YouTube/Vimeo link is embedded.', 'alarede' ); ?></span>
		</p>
		<p>
			<label for="ae_slide_kicker"><?php esc_html_e( 'Overline / Kicker', 'alarede' ); ?></label>
			<input type="text" id="ae_slide_kicker" name="ae_slide_kicker" value="<?php echo esc_attr( $fields['kicker'] ); ?>" />
			<span class="description"><?php esc_html_e( 'The slide Title and Content (above) are used as the heading and subtitle.', 'alarede' ); ?></span>
		</p>
		<p>
			<label><?php esc_html_e( 'Primary Button', 'alarede' ); ?></label>
			<input type="text" name="ae_slide_btn1_text" value="<?php echo esc_attr( $fields['btn1_text'] ); ?>" placeholder="<?php esc_attr_e( 'Button text', 'alarede' ); ?>" />
			<input type="url" name="ae_slide_btn1_url" value="<?php echo esc_attr( $fields['btn1_url'] ); ?>" placeholder="https:// or #contact" />
		</p>
		<p>
			<label><?php esc_html_e( 'Secondary Button', 'alarede' ); ?></label>
			<input type="text" name="ae_slide_btn2_text" value="<?php echo esc_attr( $fields['btn2_text'] ); ?>" placeholder="<?php esc_attr_e( 'Button text', 'alarede' ); ?>" />
			<input type="url" name="ae_slide_btn2_url" value="<?php echo esc_attr( $fields['btn2_url'] ); ?>" placeholder="https:// or #portfolio" />
		</p>
	</div>
	<?php
}

/**
 * Save slide meta.
 *
 * @param int $post_id Post ID.
 */
function alarede_save_slide_meta( $post_id ) {
	if ( ! isset( $_POST['ae_slide_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ae_slide_nonce'] ) ), 'ae_slide_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$map = array(
		'_ae_slide_media_type' => array( 'ae_slide_media_type', 'sanitize_key' ),
		'_ae_slide_video_url'  => array( 'ae_slide_video_url', 'esc_url_raw' ),
		'_ae_slide_kicker'     => array( 'ae_slide_kicker', 'sanitize_text_field' ),
		'_ae_slide_btn1_text'  => array( 'ae_slide_btn1_text', 'sanitize_text_field' ),
		'_ae_slide_btn1_url'   => array( 'ae_slide_btn1_url', 'sanitize_text_field' ),
		'_ae_slide_btn2_text'  => array( 'ae_slide_btn2_text', 'sanitize_text_field' ),
		'_ae_slide_btn2_url'   => array( 'ae_slide_btn2_url', 'sanitize_text_field' ),
	);
	foreach ( $map as $meta_key => $conf ) {
		if ( isset( $_POST[ $conf[0] ] ) ) {
			update_post_meta( $post_id, $meta_key, call_user_func( $conf[1], wp_unslash( $_POST[ $conf[0] ] ) ) );
		}
	}
}
add_action( 'save_post_ae_slide', 'alarede_save_slide_meta' );

/**
 * Create starter pages (with their templates assigned) and a primary menu the
 * first time the theme is activated, so the site is ready out of the box.
 * Existing pages with the same slug are left untouched.
 */
function alarede_create_starter_pages() {
	$pages = array(
		'home'    => array( __( 'Home', 'alarede' ), 'page-templates/landing.php' ),
		'about'   => array( __( 'About', 'alarede' ), 'page-templates/about.php' ),
		'events'  => array( __( 'Luxury Events', 'alarede' ), 'page-templates/events.php' ),
		'academy' => array( __( 'Alago Academy', 'alarede' ), 'page-templates/academy.php' ),
		'gallery' => array( __( 'Media Gallery', 'alarede' ), 'page-templates/gallery.php' ),
		'careers' => array( __( 'Careers', 'alarede' ), 'page-templates/careers.php' ),
		'contact' => array( __( 'Contact', 'alarede' ), 'page-templates/contact.php' ),
		'faq'     => array( __( 'FAQ', 'alarede' ), 'page-templates/faq.php' ),
	);

	$ids = array();
	foreach ( $pages as $slug => $data ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_title'   => $data[0],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_wp_page_template', $data[1] );
			$ids[ $slug ] = $id;
		}
	}

	// Use the Home page as a static front page if none is configured yet.
	if ( 'page' !== get_option( 'show_on_front' ) && isset( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}

	// Build a primary menu if the location has none.
	if ( ! has_nav_menu( 'primary' ) ) {
		$menu_name = __( 'Primary Menu', 'alarede' );
		$menu_id   = wp_create_nav_menu( $menu_name );
		if ( ! is_wp_error( $menu_id ) ) {
			$order = array( 'home', 'about', 'events', 'academy', 'gallery', 'careers', 'faq', 'contact' );
			foreach ( $order as $slug ) {
				if ( isset( $ids[ $slug ] ) ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-object-id' => $ids[ $slug ],
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
			$locations            = get_theme_mod( 'nav_menu_locations', array() );
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
}

/**
 * Flush rewrite rules on theme activation so CPT permalinks work.
 */
function alarede_rewrite_flush() {
	alarede_register_slides();
	alarede_create_starter_pages();
	alarede_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'alarede_rewrite_flush' );
