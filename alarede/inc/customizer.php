<?php
/**
 * Alarede Customizer settings.
 *
 * Every front-page section, colour, font and contact detail is editable here,
 * with live preview where practical.
 *
 * @package Alarede
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer panels, sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function alarede_customize_register( $wp_customize ) {

	// Live-preview the core blog info.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => function () {
					bloginfo( 'name' );
				},
			)
		);
	}

	/* ---------------------------------------------------------------
	 * Master panel
	 * --------------------------------------------------------------- */
	$wp_customize->add_panel(
		'alarede_front_page',
		array(
			'title'       => __( 'Front Page Sections', 'alarede' ),
			'description' => __( 'Edit every section of the homepage. Use a static front page (Settings → Reading) to display these.', 'alarede' ),
			'priority'    => 20,
		)
	);

	/* ---------------------------------------------------------------
	 * Helper closures for registering controls quickly.
	 * --------------------------------------------------------------- */
	$add_text = function ( $id, $label, $default, $section, $type = 'text' ) use ( $wp_customize ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => ( 'textarea' === $type ) ? 'wp_kses_post' : 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => $section,
				'type'    => $type,
			)
		);
	};

	$add_toggle = function ( $id, $label, $section, $default = true ) use ( $wp_customize ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'alarede_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => $section,
				'type'    => 'checkbox',
			)
		);
	};

	$add_image = function ( $id, $label, $section ) use ( $wp_customize ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $label,
					'section' => $section,
				)
			)
		);
	};

	/* ===============================================================
	 * 1. HERO
	 * =============================================================== */
	$wp_customize->add_section(
		'alarede_hero',
		array(
			'title'       => __( 'Hero', 'alarede' ),
			'panel'       => 'alarede_front_page',
			'description' => __( 'For a multi-slide hero, add posts under “Hero Slides” in the dashboard — each can be an image or a video. The fields below are the single-slide fallback used when no Hero Slides exist.', 'alarede' ),
		)
	);
	// Media type for the fallback hero.
	$wp_customize->add_setting( 'alarede_hero_media_type', array( 'default' => 'image', 'sanitize_callback' => 'sanitize_key' ) );
	$wp_customize->add_control(
		'alarede_hero_media_type',
		array(
			'label'   => __( 'Background Media', 'alarede' ),
			'section' => 'alarede_hero',
			'type'    => 'select',
			'choices' => array(
				'image' => __( 'Image', 'alarede' ),
				'video' => __( 'Video', 'alarede' ),
			),
		)
	);
	$add_image( 'alarede_hero_image', __( 'Background Image (also used as video poster)', 'alarede' ), 'alarede_hero' );
	$add_text( 'alarede_hero_video', __( 'Background Video URL (MP4, YouTube or Vimeo)', 'alarede' ), '', 'alarede_hero', 'url' );
	$wp_customize->add_setting( 'alarede_hero_autoplay', array( 'default' => 6000, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'alarede_hero_autoplay', array( 'label' => __( 'Slider speed (ms, 0 = no autoplay)', 'alarede' ), 'section' => 'alarede_hero', 'type' => 'number', 'input_attrs' => array( 'min' => 0, 'step' => 500 ) ) );
	$add_text( 'alarede_hero_kicker', __( 'Overline Text', 'alarede' ), __( 'Luxury Wedding & Event Planning', 'alarede' ), 'alarede_hero' );
	$add_text( 'alarede_hero_title', __( 'Title', 'alarede' ), __( 'Timeless Celebrations, Effortlessly Designed', 'alarede' ), 'alarede_hero' );
	$add_text( 'alarede_hero_subtitle', __( 'Subtitle', 'alarede' ), __( 'Bespoke destination weddings crafted with artistry, precision and heart.', 'alarede' ), 'alarede_hero', 'textarea' );
	$add_text( 'alarede_hero_btn1_text', __( 'Primary Button Text', 'alarede' ), __( 'Start Planning', 'alarede' ), 'alarede_hero' );
	$add_text( 'alarede_hero_btn1_url', __( 'Primary Button URL', 'alarede' ), '#contact', 'alarede_hero', 'url' );
	$add_text( 'alarede_hero_btn2_text', __( 'Secondary Button Text', 'alarede' ), __( 'View Portfolio', 'alarede' ), 'alarede_hero' );
	$add_text( 'alarede_hero_btn2_url', __( 'Secondary Button URL', 'alarede' ), '#portfolio', 'alarede_hero', 'url' );

	/* ===============================================================
	 * 2. INTRO / ABOUT
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_intro', array( 'title' => __( 'Intro / About', 'alarede' ), 'panel' => 'alarede_front_page' ) );
	$add_toggle( 'alarede_intro_enable', __( 'Show this section', 'alarede' ), 'alarede_intro' );
	$add_image( 'alarede_intro_image', __( 'Image', 'alarede' ), 'alarede_intro' );
	$add_text( 'alarede_intro_kicker', __( 'Overline Text', 'alarede' ), __( 'Our Story', 'alarede' ), 'alarede_intro' );
	$add_text( 'alarede_intro_title', __( 'Title', 'alarede' ), __( 'Crafting Unforgettable Moments Since 2005', 'alarede' ), 'alarede_intro' );
	$add_text( 'alarede_intro_text', __( 'Body Text', 'alarede' ), __( 'We are a boutique team of planners and designers devoted to creating celebrations as unique as the couples we serve. By accepting only a limited number of events each year, we devote exceptional attention to every detail.', 'alarede' ), 'alarede_intro', 'textarea' );
	$add_text( 'alarede_intro_signature', __( 'Signature / Name', 'alarede' ), __( 'The Events Team', 'alarede' ), 'alarede_intro' );

	/* ===============================================================
	 * 3. SERVICES
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_services', array( 'title' => __( 'Services', 'alarede' ), 'panel' => 'alarede_front_page' ) );
	$add_toggle( 'alarede_services_enable', __( 'Show this section', 'alarede' ), 'alarede_services' );
	$add_text( 'alarede_services_kicker', __( 'Overline Text', 'alarede' ), __( 'What We Do', 'alarede' ), 'alarede_services' );
	$add_text( 'alarede_services_title', __( 'Title', 'alarede' ), __( 'Our Services', 'alarede' ), 'alarede_services' );
	$add_text( 'alarede_services_intro', __( 'Intro Text', 'alarede' ), __( 'From the first conversation to the final dance, we are with you every step of the way.', 'alarede' ), 'alarede_services', 'textarea' );

	// Three editable service cards (fallback when no Service posts exist).
	$service_defaults = alarede_template_defaults( 'services' );
	foreach ( $service_defaults as $i => $svc ) {
		$n = $i + 1;
		$add_text( "alarede_service_{$n}_icon", sprintf( __( 'Card %d — Icon', 'alarede' ), $n ), $svc[0], 'alarede_services' );
		$add_text( "alarede_service_{$n}_title", sprintf( __( 'Card %d — Title', 'alarede' ), $n ), $svc[1], 'alarede_services' );
		$add_text( "alarede_service_{$n}_text", sprintf( __( 'Card %d — Text', 'alarede' ), $n ), $svc[2], 'alarede_services', 'textarea' );
	}

	/* ===============================================================
	 * 4. PORTFOLIO
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_portfolio', array( 'title' => __( 'Portfolio', 'alarede' ), 'panel' => 'alarede_front_page' ) );
	$add_toggle( 'alarede_portfolio_enable', __( 'Show this section', 'alarede' ), 'alarede_portfolio' );
	$add_text( 'alarede_portfolio_kicker', __( 'Overline Text', 'alarede' ), __( 'Real Celebrations', 'alarede' ), 'alarede_portfolio' );
	$add_text( 'alarede_portfolio_title', __( 'Title', 'alarede' ), __( 'Selected Work', 'alarede' ), 'alarede_portfolio' );
	$add_text( 'alarede_portfolio_intro', __( 'Intro Text', 'alarede' ), __( 'A glimpse into some of the celebrations we have had the honour of creating.', 'alarede' ), 'alarede_portfolio', 'textarea' );
	$wp_customize->add_setting( 'alarede_portfolio_count', array( 'default' => 6, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'alarede_portfolio_count', array( 'label' => __( 'Number of items', 'alarede' ), 'section' => 'alarede_portfolio', 'type' => 'number', 'input_attrs' => array( 'min' => 3, 'max' => 12 ) ) );

	/* ===============================================================
	 * 5. PRICING
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_pricing', array( 'title' => __( 'Pricing', 'alarede' ), 'panel' => 'alarede_front_page' ) );
	$add_toggle( 'alarede_pricing_enable', __( 'Show this section', 'alarede' ), 'alarede_pricing', false );
	$add_text( 'alarede_pricing_kicker', __( 'Overline Text', 'alarede' ), __( 'Investment', 'alarede' ), 'alarede_pricing' );
	$add_text( 'alarede_pricing_title', __( 'Title', 'alarede' ), __( 'Planning Packages', 'alarede' ), 'alarede_pricing' );
	$add_text( 'alarede_pricing_intro', __( 'Intro Text', 'alarede' ), __( 'Transparent, flexible packages designed around your celebration.', 'alarede' ), 'alarede_pricing', 'textarea' );

	$price_defaults = alarede_template_defaults( 'prices' );
	foreach ( $price_defaults as $i => $price ) {
		$n = $i + 1;
		$add_text( "alarede_price_{$n}_title", sprintf( __( 'Plan %d — Name', 'alarede' ), $n ), $price[0], 'alarede_pricing' );
		$add_text( "alarede_price_{$n}_amount", sprintf( __( 'Plan %d — Price', 'alarede' ), $n ), $price[1], 'alarede_pricing' );
		$add_text( "alarede_price_{$n}_unit", sprintf( __( 'Plan %d — Unit', 'alarede' ), $n ), $price[2], 'alarede_pricing' );
		$add_text( "alarede_price_{$n}_features", sprintf( __( 'Plan %d — Features (one per line)', 'alarede' ), $n ), $price[3], 'alarede_pricing', 'textarea' );
		$add_text( "alarede_price_{$n}_badge", sprintf( __( 'Plan %d — Badge (optional)', 'alarede' ), $n ), $price[4], 'alarede_pricing' );
	}

	/* ===============================================================
	 * 6. TESTIMONIALS
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_testimonials', array( 'title' => __( 'Testimonials', 'alarede' ), 'panel' => 'alarede_front_page' ) );
	$add_toggle( 'alarede_testimonials_enable', __( 'Show this section', 'alarede' ), 'alarede_testimonials' );
	$add_image( 'alarede_testimonials_bg', __( 'Background Image', 'alarede' ), 'alarede_testimonials' );
	$add_text( 'alarede_testimonials_kicker', __( 'Overline Text', 'alarede' ), __( 'Kind Words', 'alarede' ), 'alarede_testimonials' );
	$add_text( 'alarede_testimonials_title', __( 'Title', 'alarede' ), __( 'Love Notes From Our Couples', 'alarede' ), 'alarede_testimonials' );

	/* ===============================================================
	 * 7. CONTACT / CTA
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_contact', array( 'title' => __( 'Contact', 'alarede' ), 'panel' => 'alarede_front_page' ) );
	$add_toggle( 'alarede_contact_enable', __( 'Show this section', 'alarede' ), 'alarede_contact' );
	$add_text( 'alarede_contact_kicker', __( 'Overline Text', 'alarede' ), __( 'Get In Touch', 'alarede' ), 'alarede_contact' );
	$add_text( 'alarede_contact_title', __( 'Title', 'alarede' ), __( 'Let’s Plan Something Beautiful', 'alarede' ), 'alarede_contact' );
	$add_text( 'alarede_contact_text', __( 'Intro Text', 'alarede' ), __( 'Tell us about your celebration and we will be in touch within 48 hours.', 'alarede' ), 'alarede_contact', 'textarea' );
	$add_text( 'alarede_contact_email', __( 'Email', 'alarede' ), 'hello@example.com', 'alarede_contact' );
	$add_text( 'alarede_contact_phone', __( 'Phone', 'alarede' ), '+34 000 000 000', 'alarede_contact' );
	$add_text( 'alarede_contact_address', __( 'Address', 'alarede' ), __( 'Palma de Mallorca, Spain', 'alarede' ), 'alarede_contact', 'textarea' );
	$wp_customize->add_setting( 'alarede_contact_shortcode', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'alarede_contact_shortcode', array( 'label' => __( 'Contact Form Shortcode (optional)', 'alarede' ), 'description' => __( 'Paste a form shortcode to override the built-in appointment booking form. Leave blank to use the booking form.', 'alarede' ), 'section' => 'alarede_contact', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'alarede_contact_map', array( 'default' => '', 'sanitize_callback' => 'alarede_sanitize_embed' ) );
	$wp_customize->add_control( 'alarede_contact_map', array( 'label' => __( 'Map Embed (optional)', 'alarede' ), 'description' => __( 'Paste a Google Maps embed <iframe> to show a map on the Contact page template.', 'alarede' ), 'section' => 'alarede_contact', 'type' => 'textarea' ) );

	/* ===============================================================
	 * BOOKING & reCAPTCHA
	 * =============================================================== */
	$wp_customize->add_section(
		'alarede_booking',
		array(
			'title'       => __( 'Appointment Booking', 'alarede' ),
			'priority'    => 26,
			'description' => __( 'The contact form is an appointment booking form. Add or edit the options under Bookings → Appointment Types in the dashboard, and see all submissions under Bookings.', 'alarede' ),
		)
	);
	$add_text( 'alarede_booking_email', __( 'Send Bookings To (email)', 'alarede' ), 'info@alarede.com', 'alarede_booking' );
	$add_text( 'alarede_booking_success', __( 'Success Message', 'alarede' ), __( 'Thank you! Your appointment request has been received — we will confirm by email shortly.', 'alarede' ), 'alarede_booking', 'textarea' );

	// Time slots + capacity.
	$add_text( 'alarede_booking_slots', __( 'Time Slots (one per line)', 'alarede' ), "10:00\n11:00\n12:00\n14:00\n15:00\n16:00", 'alarede_booking', 'textarea' );
	$wp_customize->add_setting( 'alarede_booking_capacity', array( 'default' => 1, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'alarede_booking_capacity', array( 'label' => __( 'Bookings allowed per slot', 'alarede' ), 'description' => __( 'How many bookings each date + time slot can take before it shows as fully booked. Set 0 for unlimited.', 'alarede' ), 'section' => 'alarede_booking', 'type' => 'number', 'input_attrs' => array( 'min' => 0 ) ) );

	// Customer confirmation auto-reply.
	$add_toggle( 'alarede_booking_confirm_enable', __( 'Send confirmation email to the customer', 'alarede' ), 'alarede_booking' );
	$add_text( 'alarede_booking_confirm_subject', __( 'Confirmation Email Subject', 'alarede' ), '', 'alarede_booking' );
	$add_text( 'alarede_booking_confirm_message', __( 'Confirmation Email Message', 'alarede' ), __( 'Thank you for your request. We have received the following details and will confirm your appointment by email shortly.', 'alarede' ), 'alarede_booking', 'textarea' );

	// Spam protection.
	$add_text( 'alarede_recaptcha_site', __( 'reCAPTCHA v2 Site Key', 'alarede' ), '', 'alarede_booking' );
	$add_text( 'alarede_recaptcha_secret', __( 'reCAPTCHA v2 Secret Key', 'alarede' ), '', 'alarede_booking' );

	/* ===============================================================
	 * PAGE TEMPLATES (editable lists)
	 * =============================================================== */
	$wp_customize->add_panel(
		'alarede_pages',
		array(
			'title'       => __( 'Page Templates', 'alarede' ),
			'description' => __( 'Edit the lists shown on the Events, Academy, Careers and FAQ page templates. Leave an item’s title blank to hide it.', 'alarede' ),
			'priority'    => 25,
		)
	);

	$tpl_defaults = alarede_template_defaults();

	// Helper: register a section heading (kicker/title/intro).
	$add_heading = function ( $section, $kicker, $title, $intro = '' ) use ( $wp_customize, $add_text ) {
		$prefix = $section;
		$add_text( "{$prefix}_kicker", __( 'Overline Text', 'alarede' ), $kicker, $section );
		$add_text( "{$prefix}_title", __( 'Section Title', 'alarede' ), $title, $section );
		if ( false !== $intro ) {
			$add_text( "{$prefix}_intro", __( 'Intro Text', 'alarede' ), $intro, $section, 'textarea' );
		}
	};

	// Helper: register a title+text list seeded from defaults.
	$add_list = function ( $section, $item_prefix, $rows, $title_label, $text_label, $text_type = 'textarea' ) use ( $wp_customize, $add_text ) {
		foreach ( $rows as $i => $row ) {
			$n = $i + 1;
			$add_text( "{$item_prefix}_{$n}_title", sprintf( '%s %d — %s', __( 'Item', 'alarede' ), $n, $title_label ), $row[0], $section );
			$add_text( "{$item_prefix}_{$n}_text", sprintf( '%s %d — %s', __( 'Item', 'alarede' ), $n, $text_label ), isset( $row[1] ) ? $row[1] : '', $section, $text_type );
		}
	};

	// --- Events ----------------------------------------------------------
	$wp_customize->add_section( 'alarede_events', array( 'title' => __( 'Events Page', 'alarede' ), 'panel' => 'alarede_pages' ) );
	$add_heading( 'alarede_events', __( 'What We Offer', 'alarede' ), __( 'Event Services', 'alarede' ), __( 'Bespoke planning and coordination for every milestone celebration.', 'alarede' ) );
	$add_list( 'alarede_events', 'alarede_events_item', $tpl_defaults['events'], __( 'Name', 'alarede' ), __( 'Description', 'alarede' ) );

	// --- Academy ---------------------------------------------------------
	$wp_customize->add_section( 'alarede_academy', array( 'title' => __( 'Academy Page', 'alarede' ), 'panel' => 'alarede_pages' ) );
	$add_heading( 'alarede_academy', __( 'Courses', 'alarede' ), __( 'What You Can Learn', 'alarede' ), __( 'Practical, hands-on training across our full range of specialities.', 'alarede' ) );
	$add_list( 'alarede_academy', 'alarede_academy_item', $tpl_defaults['academy'], __( 'Course', 'alarede' ), __( 'Description', 'alarede' ) );

	// --- Careers ---------------------------------------------------------
	$wp_customize->add_section( 'alarede_careers', array( 'title' => __( 'Careers Page', 'alarede' ), 'panel' => 'alarede_pages' ) );
	$add_heading( 'alarede_careers_values', __( 'Life Here', 'alarede' ), __( 'What We Value', 'alarede' ), false );
	$add_list( 'alarede_careers', 'alarede_careers_value', $tpl_defaults['values'], __( 'Value', 'alarede' ), __( 'Description', 'alarede' ) );
	$add_heading( 'alarede_careers_jobs', __( 'Open Roles', 'alarede' ), __( 'Current Openings', 'alarede' ), false );
	$add_list( 'alarede_careers', 'alarede_careers_job', $tpl_defaults['jobs'], __( 'Role', 'alarede' ), __( 'Detail (e.g. Full-time · Hybrid)', 'alarede' ), 'text' );

	// --- FAQ -------------------------------------------------------------
	$wp_customize->add_section( 'alarede_faq', array( 'title' => __( 'FAQ Page', 'alarede' ), 'panel' => 'alarede_pages' ) );
	$add_list( 'alarede_faq', 'alarede_faq_item', $tpl_defaults['faqs'], __( 'Question', 'alarede' ), __( 'Answer', 'alarede' ) );

	/* ===============================================================
	 * COLOURS
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_colors', array( 'title' => __( 'Theme Colours', 'alarede' ), 'priority' => 30 ) );
	$colors = array(
		'alarede_color_accent' => array( __( 'Accent (gold)', 'alarede' ), '#b7965a' ),
		'alarede_color_dark'   => array( __( 'Dark', 'alarede' ), '#1c1c1a' ),
		'alarede_color_light'  => array( __( 'Light / Cream', 'alarede' ), '#faf7f1' ),
	);
	foreach ( $colors as $id => $data ) {
		$wp_customize->add_setting( $id, array( 'default' => $data[1], 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array( 'label' => $data[0], 'section' => 'alarede_colors' ) ) );
	}

	/* ===============================================================
	 * TYPOGRAPHY
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_typography', array( 'title' => __( 'Typography', 'alarede' ), 'priority' => 31 ) );
	$add_text( 'alarede_font_heading', __( 'Heading Font Stack', 'alarede' ), '"Cormorant Garamond", Georgia, serif', 'alarede_typography' );
	$add_text( 'alarede_font_body', __( 'Body Font Stack', 'alarede' ), '"Montserrat", Arial, sans-serif', 'alarede_typography' );

	/* ===============================================================
	 * PAGE HEADER
	 * =============================================================== */
	$wp_customize->add_section(
		'alarede_page_header',
		array(
			'title'       => __( 'Page Header', 'alarede' ),
			'priority'    => 31,
			'description' => __( 'Default banner image shown behind inner page titles (blog, archives, and pages without a Featured Image). A dark overlay is applied automatically. A page’s own Featured Image takes priority.', 'alarede' ),
		)
	);
	$add_image( 'alarede_page_header_image', __( 'Default Header Image', 'alarede' ), 'alarede_page_header' );

	/* ===============================================================
	 * SOCIAL & FOOTER
	 * =============================================================== */
	$wp_customize->add_section( 'alarede_social', array( 'title' => __( 'Social & Footer', 'alarede' ), 'priority' => 32 ) );
	$add_toggle( 'alarede_footer_tagline_enable', __( 'Show footer tagline band', 'alarede' ), 'alarede_social' );
	$add_text( 'alarede_footer_tagline_kicker', __( 'Footer Tagline — Overline', 'alarede' ), __( 'For Those Who Desire the Finest', 'alarede' ), 'alarede_social' );
	$add_text( 'alarede_footer_tagline_title', __( 'Footer Tagline — Heading', 'alarede' ), __( 'Exclusive Luxury Wedding Experiences', 'alarede' ), 'alarede_social' );
	$add_text( 'alarede_footer_about', __( 'Footer About Text', 'alarede' ), __( 'An elegant studio crafting bespoke luxury weddings and events — blending timeless design, exclusivity and personal care into truly extraordinary celebrations.', 'alarede' ), 'alarede_social', 'textarea' );
	$add_text( 'alarede_social_instagram', __( 'Instagram URL', 'alarede' ), '', 'alarede_social', 'url' );
	$add_text( 'alarede_social_facebook', __( 'Facebook URL', 'alarede' ), '', 'alarede_social', 'url' );
	$add_text( 'alarede_social_pinterest', __( 'Pinterest URL', 'alarede' ), '', 'alarede_social', 'url' );
	$add_text( 'alarede_social_youtube', __( 'YouTube URL', 'alarede' ), '', 'alarede_social', 'url' );
	$add_text( 'alarede_footer_text', __( 'Footer Copyright Text', 'alarede' ), '', 'alarede_social' );
}
add_action( 'customize_register', 'alarede_customize_register' );

/**
 * Sanitize a checkbox.
 *
 * @param mixed $checked Value.
 * @return bool
 */
function alarede_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked );
}

/**
 * Sanitize an embed snippet, allowing a safe <iframe> (e.g. a map embed).
 *
 * @param string $value Raw value.
 * @return string
 */
function alarede_sanitize_embed( $value ) {
	$allowed = array(
		'iframe' => array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'style'           => true,
			'frameborder'     => true,
			'allow'           => true,
			'allowfullscreen' => true,
			'loading'         => true,
			'referrerpolicy'  => true,
			'title'           => true,
		),
	);
	return wp_kses( $value, $allowed );
}

/**
 * Live preview JS for the Customizer.
 */
function alarede_customize_preview_js() {
	wp_enqueue_script( 'alarede-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), ALAREDE_VERSION, true );
}
add_action( 'customize_preview_init', 'alarede_customize_preview_js' );
