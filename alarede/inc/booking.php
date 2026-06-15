<?php
/**
 * Appointment booking system.
 *
 * - "Bookings" post type so admins can see every submission.
 * - "Appointment Types" taxonomy so admins can add/edit the types offered.
 * - Front-end form (type → date → time → details) with honeypot + reCAPTCHA.
 * - Email notification to a configurable address on each booking.
 *
 * @package Alarede
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Bookings post type and Appointment Types taxonomy.
 */
function alarede_register_booking() {
	register_post_type(
		'ae_booking',
		array(
			'labels'              => array(
				'name'          => __( 'Bookings', 'alarede' ),
				'singular_name' => __( 'Booking', 'alarede' ),
				'menu_name'     => __( 'Bookings', 'alarede' ),
				'all_items'     => __( 'All Bookings', 'alarede' ),
				'edit_item'     => __( 'View Booking', 'alarede' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-calendar-alt',
			'menu_position'       => 26,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title' ),
			'exclude_from_search' => true,
			// Bookings are created from the front-end form, not by hand.
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
		)
	);

	register_taxonomy(
		'ae_appt_type',
		'ae_booking',
		array(
			'labels'            => array(
				'name'          => __( 'Appointment Types', 'alarede' ),
				'singular_name' => __( 'Appointment Type', 'alarede' ),
				'add_new_item'  => __( 'Add New Appointment Type', 'alarede' ),
				'menu_name'     => __( 'Appointment Types', 'alarede' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
		)
	);
}
add_action( 'init', 'alarede_register_booking' );

/**
 * Seed a few default appointment types on activation.
 */
function alarede_seed_appointment_types() {
	if ( ! taxonomy_exists( 'ae_appt_type' ) ) {
		alarede_register_booking();
	}
	$defaults = array(
		__( 'Initial Consultation', 'alarede' ),
		__( 'Wedding Planning', 'alarede' ),
		__( 'Event Planning', 'alarede' ),
		__( 'Venue Visit', 'alarede' ),
		__( 'Academy Enrolment', 'alarede' ),
	);
	foreach ( $defaults as $name ) {
		if ( ! term_exists( $name, 'ae_appt_type' ) ) {
			wp_insert_term( $name, 'ae_appt_type' );
		}
	}
}
add_action( 'after_switch_theme', 'alarede_seed_appointment_types' );

/**
 * Seed appointment types once even on a theme update (no re-activation),
 * guarded by an option so it only runs a single time.
 */
function alarede_maybe_seed_types() {
	if ( get_option( 'alarede_types_seeded' ) ) {
		return;
	}
	alarede_seed_appointment_types();
	update_option( 'alarede_types_seeded', 1 );
}
add_action( 'init', 'alarede_maybe_seed_types', 20 );

/**
 * Create appointment-type terms from the Customizer textarea when settings are
 * saved. One type per line; existing terms are left untouched.
 */
function alarede_create_types_from_customizer() {
	$raw = get_theme_mod( 'alarede_booking_types', '' );
	if ( ! $raw ) {
		return;
	}
	$names = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) );
	foreach ( $names as $name ) {
		if ( ! term_exists( $name, 'ae_appt_type' ) ) {
			wp_insert_term( $name, 'ae_appt_type' );
		}
	}
}
add_action( 'customize_save_after', 'alarede_create_types_from_customizer' );

/**
 * Get the configured time slots as an array (one per line in the Customizer).
 *
 * @return array Empty when none configured (form then uses a free time input).
 */
function alarede_booking_slots() {
	$raw = get_theme_mod( 'alarede_booking_slots', "10:00\n11:00\n12:00\n14:00\n15:00\n16:00" );
	$slots = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) );
	return array_values( $slots );
}

/**
 * Count active (non-cancelled) bookings for a given date and slot.
 *
 * @param string $date Date (Y-m-d).
 * @param string $slot Time slot.
 * @return int
 */
function alarede_slot_booked_count( $date, $slot ) {
	if ( ! $date ) {
		return 0;
	}
	$meta = array(
		'relation' => 'AND',
		array( 'key' => '_ae_booking_date', 'value' => $date ),
		array( 'key' => '_ae_booking_status', 'value' => 'cancelled', 'compare' => '!=' ),
	);
	if ( '' !== $slot ) {
		$meta[] = array( 'key' => '_ae_booking_time', 'value' => $slot );
	}
	$query = new WP_Query(
		array(
			'post_type'              => 'ae_booking',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_query'             => $meta, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		)
	);
	return (int) $query->post_count;
}

/**
 * Availability for each slot on a given date.
 *
 * @param string $date Date (Y-m-d).
 * @return array List of [ 'time' => slot, 'full' => bool ].
 */
function alarede_available_slots( $date ) {
	$capacity = (int) get_theme_mod( 'alarede_booking_capacity', 1 );
	$out      = array();
	foreach ( alarede_booking_slots() as $slot ) {
		$full  = ( $capacity > 0 ) && ( alarede_slot_booked_count( $date, $slot ) >= $capacity );
		$out[] = array( 'time' => $slot, 'full' => $full );
	}
	return $out;
}

/**
 * AJAX: return slot availability for a chosen date (public, read-only).
 */
function alarede_ajax_slots() {
	$date = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		wp_send_json_error();
	}
	wp_send_json_success( alarede_available_slots( $date ) );
}
add_action( 'wp_ajax_alarede_slots', 'alarede_ajax_slots' );
add_action( 'wp_ajax_nopriv_alarede_slots', 'alarede_ajax_slots' );

/**
 * Pass the AJAX URL and labels to the front-end script.
 */
function alarede_booking_localize() {
	wp_localize_script(
		'alarede-main',
		'alaredeBooking',
		array(
			'ajax' => admin_url( 'admin-ajax.php' ),
			'full' => __( 'fully booked', 'alarede' ),
			'pick' => __( 'Select a time…', 'alarede' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'alarede_booking_localize', 20 );

/**
 * Admin columns for the Bookings list table.
 *
 * @param array $cols Columns.
 * @return array
 */
function alarede_booking_columns( $cols ) {
	$new = array(
		'cb'             => isset( $cols['cb'] ) ? $cols['cb'] : '',
		'title'          => __( 'Name', 'alarede' ),
		'ae_type'        => __( 'Type', 'alarede' ),
		'ae_date'        => __( 'Date', 'alarede' ),
		'ae_time'        => __( 'Time', 'alarede' ),
		'ae_email'       => __( 'Email', 'alarede' ),
		'ae_phone'       => __( 'Phone', 'alarede' ),
		'ae_status'      => __( 'Status', 'alarede' ),
		'date'           => __( 'Submitted', 'alarede' ),
	);
	return $new;
}
add_filter( 'manage_ae_booking_posts_columns', 'alarede_booking_columns' );

/**
 * Render custom column content.
 *
 * @param string $col     Column key.
 * @param int    $post_id Post ID.
 */
function alarede_booking_column_content( $col, $post_id ) {
	switch ( $col ) {
		case 'ae_type':
			$terms = get_the_terms( $post_id, 'ae_appt_type' );
			echo ( $terms && ! is_wp_error( $terms ) ) ? esc_html( $terms[0]->name ) : '—';
			break;
		case 'ae_date':
			echo esc_html( get_post_meta( $post_id, '_ae_booking_date', true ) ?: '—' );
			break;
		case 'ae_time':
			echo esc_html( get_post_meta( $post_id, '_ae_booking_time', true ) ?: '—' );
			break;
		case 'ae_email':
			$email = get_post_meta( $post_id, '_ae_booking_email', true );
			echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '—';
			break;
		case 'ae_phone':
			echo esc_html( get_post_meta( $post_id, '_ae_booking_phone', true ) ?: '—' );
			break;
		case 'ae_status':
			$status = get_post_meta( $post_id, '_ae_booking_status', true ) ?: 'new';
			printf( '<span class="ae-booking-status ae-booking-status--%1$s">%2$s</span>', esc_attr( $status ), esc_html( ucfirst( $status ) ) );
			break;
	}
}
add_action( 'manage_ae_booking_posts_custom_column', 'alarede_booking_column_content', 10, 2 );

/**
 * Meta box: show the full booking and let the admin set a status.
 */
function alarede_booking_meta_box() {
	add_meta_box( 'ae_booking_details', __( 'Booking Details', 'alarede' ), 'alarede_booking_meta_box_cb', 'ae_booking', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'alarede_booking_meta_box' );

/**
 * Render the booking meta box.
 *
 * @param WP_Post $post Post.
 */
function alarede_booking_meta_box_cb( $post ) {
	wp_nonce_field( 'ae_booking_admin', 'ae_booking_admin_nonce' );
	$fields = array(
		__( 'Name', 'alarede' )    => get_post_meta( $post->ID, '_ae_booking_name', true ),
		__( 'Email', 'alarede' )   => get_post_meta( $post->ID, '_ae_booking_email', true ),
		__( 'Phone', 'alarede' )   => get_post_meta( $post->ID, '_ae_booking_phone', true ),
		__( 'Date', 'alarede' )    => get_post_meta( $post->ID, '_ae_booking_date', true ),
		__( 'Time', 'alarede' )    => get_post_meta( $post->ID, '_ae_booking_time', true ),
	);
	$message = get_post_meta( $post->ID, '_ae_booking_message', true );
	$status  = get_post_meta( $post->ID, '_ae_booking_status', true ) ?: 'new';
	echo '<table class="form-table">';
	foreach ( $fields as $label => $value ) {
		echo '<tr><th style="width:140px;">' . esc_html( $label ) . '</th><td>' . ( $value ? esc_html( $value ) : '—' ) . '</td></tr>';
	}
	echo '<tr><th>' . esc_html__( 'Message', 'alarede' ) . '</th><td>' . ( $message ? nl2br( esc_html( $message ) ) : '—' ) . '</td></tr>';
	echo '<tr><th><label for="ae_booking_status">' . esc_html__( 'Status', 'alarede' ) . '</label></th><td><select name="ae_booking_status" id="ae_booking_status">';
	foreach ( array( 'new', 'confirmed', 'completed', 'cancelled' ) as $opt ) {
		printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $opt ), selected( $status, $opt, false ), esc_html( ucfirst( $opt ) ) );
	}
	echo '</select></td></tr>';
	echo '</table>';
}

/**
 * Save the admin status.
 *
 * @param int $post_id Post ID.
 */
function alarede_save_booking_admin( $post_id ) {
	if ( ! isset( $_POST['ae_booking_admin_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ae_booking_admin_nonce'] ) ), 'ae_booking_admin' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['ae_booking_status'] ) ) {
		update_post_meta( $post_id, '_ae_booking_status', sanitize_key( wp_unslash( $_POST['ae_booking_status'] ) ) );
	}
}
add_action( 'save_post_ae_booking', 'alarede_save_booking_admin' );

/**
 * Small admin styles for the booking status pills.
 */
function alarede_booking_admin_css() {
	$screen = get_current_screen();
	if ( ! $screen || 'ae_booking' !== $screen->post_type ) {
		return;
	}
	echo '<style>
		.ae-booking-status{display:inline-block;padding:.15em .7em;border-radius:2px;font-size:.85em;}
		.ae-booking-status--new{background:#e8eef6;color:#2b577e;}
		.ae-booking-status--confirmed{background:#e7f3e8;color:#2e6b34;}
		.ae-booking-status--completed{background:#efe8f6;color:#5a3e7e;}
		.ae-booking-status--cancelled{background:#f6e8e8;color:#7e2e2e;}
	</style>';
}
add_action( 'admin_head', 'alarede_booking_admin_css' );

/**
 * Verify a Google reCAPTCHA response, if keys are configured.
 *
 * @return bool True when valid or when reCAPTCHA is not configured.
 */
function alarede_verify_recaptcha() {
	$secret = get_theme_mod( 'alarede_recaptcha_secret', '' );
	if ( ! $secret ) {
		return true; // Not configured — rely on nonce + honeypot.
	}
	$response = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
	if ( ! $response ) {
		return false;
	}
	$request = wp_remote_post(
		'https://www.google.com/recaptcha/api/siteverify',
		array(
			'timeout' => 10,
			'body'    => array(
				'secret'   => $secret,
				'response' => $response,
				'remoteip' => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
			),
		)
	);
	if ( is_wp_error( $request ) ) {
		return false;
	}
	$body = json_decode( wp_remote_retrieve_body( $request ), true );
	return ! empty( $body['success'] );
}

/**
 * Handle a front-end booking submission (admin-post.php).
 */
function alarede_handle_booking() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	$fail = function ( $reason ) use ( $redirect ) {
		wp_safe_redirect( add_query_arg( 'ae_booking', $reason, $redirect ) . '#contact' );
		exit;
	};

	// Nonce.
	if ( ! isset( $_POST['ae_booking_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ae_booking_nonce'] ) ), 'ae_booking_submit' ) ) {
		$fail( 'error' );
	}

	// Honeypot: must be empty (bots fill it).
	if ( ! empty( $_POST['ae_website'] ) ) {
		$fail( 'spam' );
	}

	// reCAPTCHA.
	if ( ! alarede_verify_recaptcha() ) {
		$fail( 'captcha' );
	}

	// Collect + sanitize.
	$name    = isset( $_POST['ae_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ae_name'] ) ) : '';
	$email   = isset( $_POST['ae_email'] ) ? sanitize_email( wp_unslash( $_POST['ae_email'] ) ) : '';
	$phone   = isset( $_POST['ae_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['ae_phone'] ) ) : '';
	$date    = isset( $_POST['ae_date'] ) ? sanitize_text_field( wp_unslash( $_POST['ae_date'] ) ) : '';
	$time    = isset( $_POST['ae_time'] ) ? sanitize_text_field( wp_unslash( $_POST['ae_time'] ) ) : '';
	$message = isset( $_POST['ae_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ae_message'] ) ) : '';
	$type_id = isset( $_POST['ae_type'] ) ? absint( $_POST['ae_type'] ) : 0;

	if ( ! $name || ! is_email( $email ) || ! $date ) {
		$fail( 'error' );
	}

	// Time-slot validation + capacity check (when slots are configured).
	$slots = alarede_booking_slots();
	if ( $slots ) {
		if ( ! $time || ! in_array( $time, $slots, true ) ) {
			$fail( 'error' );
		}
		$capacity = (int) get_theme_mod( 'alarede_booking_capacity', 1 );
		if ( $capacity > 0 && alarede_slot_booked_count( $date, $time ) >= $capacity ) {
			$fail( 'full' );
		}
	}

	$type_term = $type_id ? get_term( $type_id, 'ae_appt_type' ) : null;
	$type_name = ( $type_term && ! is_wp_error( $type_term ) ) ? $type_term->name : __( 'Appointment', 'alarede' );

	// Store the booking.
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'ae_booking',
			'post_status' => 'publish',
			/* translators: 1: customer name, 2: appointment type. */
			'post_title'  => sprintf( __( '%1$s — %2$s', 'alarede' ), $name, $type_name ),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		$fail( 'error' );
	}

	update_post_meta( $post_id, '_ae_booking_name', $name );
	update_post_meta( $post_id, '_ae_booking_email', $email );
	update_post_meta( $post_id, '_ae_booking_phone', $phone );
	update_post_meta( $post_id, '_ae_booking_date', $date );
	update_post_meta( $post_id, '_ae_booking_time', $time );
	update_post_meta( $post_id, '_ae_booking_message', $message );
	update_post_meta( $post_id, '_ae_booking_status', 'new' );
	if ( $type_term && ! is_wp_error( $type_term ) ) {
		wp_set_object_terms( $post_id, array( $type_id ), 'ae_appt_type' );
	}

	// Notify the admin.
	$to = get_theme_mod( 'alarede_booking_email', '' );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}
	$subject = sprintf(
		/* translators: %s: appointment type. */
		__( 'New appointment request: %s', 'alarede' ),
		$type_name
	);
	$lines = array(
		__( 'A new appointment request has been submitted.', 'alarede' ),
		'',
		sprintf( '%s: %s', __( 'Type', 'alarede' ), $type_name ),
		sprintf( '%s: %s', __( 'Name', 'alarede' ), $name ),
		sprintf( '%s: %s', __( 'Email', 'alarede' ), $email ),
		sprintf( '%s: %s', __( 'Phone', 'alarede' ), $phone ),
		sprintf( '%s: %s', __( 'Date', 'alarede' ), $date ),
		sprintf( '%s: %s', __( 'Time', 'alarede' ), $time ),
		'',
		__( 'Message:', 'alarede' ),
		$message,
		'',
		sprintf( '%s: %s', __( 'Manage', 'alarede' ), admin_url( 'post.php?action=edit&post=' . $post_id ) ),
	);
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', $name, $email ),
	);
	wp_mail( $to, $subject, implode( "\n", $lines ), $headers );

	// Confirmation auto-reply to the customer.
	if ( get_theme_mod( 'alarede_booking_confirm_enable', true ) ) {
		$site         = get_bloginfo( 'name' );
		$conf_subject = get_theme_mod( 'alarede_booking_confirm_subject', '' );
		if ( ! $conf_subject ) {
			/* translators: %s: site name. */
			$conf_subject = sprintf( __( 'Your appointment request — %s', 'alarede' ), $site );
		}
		$conf_intro = get_theme_mod( 'alarede_booking_confirm_message', __( 'Thank you for your request. We have received the following details and will confirm your appointment by email shortly.', 'alarede' ) );
		$conf_lines = array(
			sprintf( __( 'Dear %s,', 'alarede' ), $name ),
			'',
			$conf_intro,
			'',
			sprintf( '%s: %s', __( 'Type', 'alarede' ), $type_name ),
			sprintf( '%s: %s', __( 'Date', 'alarede' ), $date ),
			sprintf( '%s: %s', __( 'Time', 'alarede' ), $time ),
			'',
			sprintf( __( 'Warm regards,', 'alarede' ) ),
			$site,
		);
		$from         = is_email( $to ) ? $to : get_option( 'admin_email' );
		$conf_headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			sprintf( 'Reply-To: %s <%s>', $site, $from ),
		);
		wp_mail( $email, $conf_subject, implode( "\n", $conf_lines ), $conf_headers );
	}

	wp_safe_redirect( add_query_arg( 'ae_booking', 'success', $redirect ) . '#contact' );
	exit;
}
add_action( 'admin_post_nopriv_alarede_booking', 'alarede_handle_booking' );
add_action( 'admin_post_alarede_booking', 'alarede_handle_booking' );

/**
 * Enqueue the reCAPTCHA API script when a site key is configured.
 */
function alarede_recaptcha_script() {
	if ( get_theme_mod( 'alarede_recaptcha_site', '' ) ) {
		wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
}
add_action( 'wp_enqueue_scripts', 'alarede_recaptcha_script' );

/**
 * Render the appointment booking form.
 *
 * @return string Form HTML.
 */
function alarede_booking_form() {
	$types = get_terms(
		array(
			'taxonomy'   => 'ae_appt_type',
			'hide_empty' => false,
			'orderby'    => 'name',
		)
	);
	$site_key = get_theme_mod( 'alarede_recaptcha_site', '' );

	// Status message from the redirect.
	$notice    = '';
	$ae_status = isset( $_GET['ae_booking'] ) ? sanitize_key( wp_unslash( $_GET['ae_booking'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'success' === $ae_status ) {
		$msg    = get_theme_mod( 'alarede_booking_success', __( 'Thank you! Your appointment request has been received — we will confirm by email shortly.', 'alarede' ) );
		$notice = '<div class="ae-form-notice ae-form-notice--ok">' . esc_html( $msg ) . '</div>';
	} elseif ( 'captcha' === $ae_status ) {
		$notice = '<div class="ae-form-notice ae-form-notice--err">' . esc_html__( 'Please complete the reCAPTCHA and try again.', 'alarede' ) . '</div>';
	} elseif ( 'full' === $ae_status ) {
		$notice = '<div class="ae-form-notice ae-form-notice--err">' . esc_html__( 'Sorry, that time slot is fully booked. Please choose another date or time.', 'alarede' ) . '</div>';
	} elseif ( $ae_status && 'spam' !== $ae_status ) {
		$notice = '<div class="ae-form-notice ae-form-notice--err">' . esc_html__( 'Sorry, something went wrong. Please check your details and try again.', 'alarede' ) . '</div>';
	}

	ob_start();
	echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above.
	?>
	<form class="ae-booking-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="action" value="alarede_booking">
		<?php wp_nonce_field( 'ae_booking_submit', 'ae_booking_nonce' ); ?>
		<!-- Honeypot (hidden from humans). -->
		<div class="ae-hp" aria-hidden="true">
			<label>Website<input type="text" name="ae_website" tabindex="-1" autocomplete="off"></label>
		</div>

		<label class="ae-field">
			<span><?php esc_html_e( 'Appointment Type', 'alarede' ); ?> *</span>
			<select name="ae_type" required>
				<option value="" disabled selected><?php esc_html_e( 'Select a service…', 'alarede' ); ?></option>
				<?php
				if ( $types && ! is_wp_error( $types ) ) {
					foreach ( $types as $type ) {
						printf( '<option value="%1$d">%2$s</option>', (int) $type->term_id, esc_html( $type->name ) );
					}
				}
				?>
			</select>
		</label>

		<div class="ae-field-row">
			<label class="ae-field">
				<span><?php esc_html_e( 'Preferred Date', 'alarede' ); ?> *</span>
				<input type="date" name="ae_date" min="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>" required>
			</label>
			<label class="ae-field">
				<span><?php esc_html_e( 'Preferred Time', 'alarede' ); ?></span>
				<?php $slots = alarede_booking_slots(); ?>
				<?php if ( $slots ) : ?>
					<select name="ae_time" data-slots="1">
						<option value="" disabled selected><?php esc_html_e( 'Choose a date first…', 'alarede' ); ?></option>
						<?php foreach ( $slots as $slot ) : ?>
							<option value="<?php echo esc_attr( $slot ); ?>" data-slot="1"><?php echo esc_html( $slot ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<input type="time" name="ae_time">
				<?php endif; ?>
			</label>
		</div>

		<div class="ae-field-row">
			<label class="ae-field">
				<span><?php esc_html_e( 'Full Name', 'alarede' ); ?> *</span>
				<input type="text" name="ae_name" required>
			</label>
			<label class="ae-field">
				<span><?php esc_html_e( 'Phone', 'alarede' ); ?></span>
				<input type="tel" name="ae_phone">
			</label>
		</div>

		<label class="ae-field">
			<span><?php esc_html_e( 'Email', 'alarede' ); ?> *</span>
			<input type="email" name="ae_email" required>
		</label>

		<label class="ae-field">
			<span><?php esc_html_e( 'Notes', 'alarede' ); ?></span>
			<textarea name="ae_message" placeholder="<?php esc_attr_e( 'Tell us a little about your celebration…', 'alarede' ); ?>"></textarea>
		</label>

		<?php if ( $site_key ) : ?>
			<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $site_key ); ?>"></div>
		<?php endif; ?>

		<button type="submit" class="ae-btn ae-btn--solid"><?php esc_html_e( 'Request Appointment', 'alarede' ); ?></button>
	</form>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode wrapper: [alarede_booking_form].
 *
 * @return string
 */
function alarede_booking_form_shortcode() {
	return alarede_booking_form();
}
add_shortcode( 'alarede_booking_form', 'alarede_booking_form_shortcode' );
