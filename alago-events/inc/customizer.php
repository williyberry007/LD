<?php
/**
 * Alago Events Customizer settings.
 *
 * Every front-page section, colour, font and contact detail is editable here,
 * with live preview where practical.
 *
 * @package Alago_Events
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer panels, sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function alago_events_customize_register( $wp_customize ) {

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
		'alago_front_page',
		array(
			'title'       => __( 'Front Page Sections', 'alago-events' ),
			'description' => __( 'Edit every section of the homepage. Use a static front page (Settings → Reading) to display these.', 'alago-events' ),
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
				'sanitize_callback' => 'alago_events_sanitize_checkbox',
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
	$wp_customize->add_section( 'alago_hero', array( 'title' => __( 'Hero', 'alago-events' ), 'panel' => 'alago_front_page' ) );
	$add_image( 'alago_hero_image', __( 'Background Image', 'alago-events' ), 'alago_hero' );
	$add_text( 'alago_hero_kicker', __( 'Overline Text', 'alago-events' ), __( 'Luxury Wedding & Event Planning', 'alago-events' ), 'alago_hero' );
	$add_text( 'alago_hero_title', __( 'Title', 'alago-events' ), __( 'Timeless Celebrations, Effortlessly Designed', 'alago-events' ), 'alago_hero' );
	$add_text( 'alago_hero_subtitle', __( 'Subtitle', 'alago-events' ), __( 'Bespoke destination weddings crafted with artistry, precision and heart.', 'alago-events' ), 'alago_hero', 'textarea' );
	$add_text( 'alago_hero_btn1_text', __( 'Primary Button Text', 'alago-events' ), __( 'Start Planning', 'alago-events' ), 'alago_hero' );
	$add_text( 'alago_hero_btn1_url', __( 'Primary Button URL', 'alago-events' ), '#contact', 'alago_hero', 'url' );
	$add_text( 'alago_hero_btn2_text', __( 'Secondary Button Text', 'alago-events' ), __( 'View Portfolio', 'alago-events' ), 'alago_hero' );
	$add_text( 'alago_hero_btn2_url', __( 'Secondary Button URL', 'alago-events' ), '#portfolio', 'alago_hero', 'url' );

	/* ===============================================================
	 * 2. INTRO / ABOUT
	 * =============================================================== */
	$wp_customize->add_section( 'alago_intro', array( 'title' => __( 'Intro / About', 'alago-events' ), 'panel' => 'alago_front_page' ) );
	$add_toggle( 'alago_intro_enable', __( 'Show this section', 'alago-events' ), 'alago_intro' );
	$add_image( 'alago_intro_image', __( 'Image', 'alago-events' ), 'alago_intro' );
	$add_text( 'alago_intro_kicker', __( 'Overline Text', 'alago-events' ), __( 'Our Story', 'alago-events' ), 'alago_intro' );
	$add_text( 'alago_intro_title', __( 'Title', 'alago-events' ), __( 'Crafting Unforgettable Moments Since 2005', 'alago-events' ), 'alago_intro' );
	$add_text( 'alago_intro_text', __( 'Body Text', 'alago-events' ), __( 'We are a boutique team of planners and designers devoted to creating celebrations as unique as the couples we serve. By accepting only a limited number of events each year, we devote exceptional attention to every detail.', 'alago-events' ), 'alago_intro', 'textarea' );
	$add_text( 'alago_intro_signature', __( 'Signature / Name', 'alago-events' ), __( 'The Events Team', 'alago-events' ), 'alago_intro' );

	/* ===============================================================
	 * 3. SERVICES
	 * =============================================================== */
	$wp_customize->add_section( 'alago_services', array( 'title' => __( 'Services', 'alago-events' ), 'panel' => 'alago_front_page' ) );
	$add_toggle( 'alago_services_enable', __( 'Show this section', 'alago-events' ), 'alago_services' );
	$add_text( 'alago_services_kicker', __( 'Overline Text', 'alago-events' ), __( 'What We Do', 'alago-events' ), 'alago_services' );
	$add_text( 'alago_services_title', __( 'Title', 'alago-events' ), __( 'Our Services', 'alago-events' ), 'alago_services' );
	$add_text( 'alago_services_intro', __( 'Intro Text', 'alago-events' ), __( 'From the first conversation to the final dance, we are with you every step of the way.', 'alago-events' ), 'alago_services', 'textarea' );

	// Three editable service cards (fallback when no Service posts exist).
	$service_defaults = array(
		array( '✦', __( 'Full Planning & Design', 'alago-events' ), __( 'End-to-end planning, styling and on-the-day coordination tailored entirely to your vision.', 'alago-events' ) ),
		array( '❀', __( 'Venue Sourcing', 'alago-events' ), __( 'We listen to your vision, present tailored venue options and guide you to the perfect setting.', 'alago-events' ) ),
		array( '♛', __( 'Event Coordination', 'alago-events' ), __( 'Seamless coordination so you can be fully present and enjoy every precious moment.', 'alago-events' ) ),
	);
	foreach ( $service_defaults as $i => $svc ) {
		$n = $i + 1;
		$add_text( "alago_service_{$n}_icon", sprintf( __( 'Card %d — Icon', 'alago-events' ), $n ), $svc[0], 'alago_services' );
		$add_text( "alago_service_{$n}_title", sprintf( __( 'Card %d — Title', 'alago-events' ), $n ), $svc[1], 'alago_services' );
		$add_text( "alago_service_{$n}_text", sprintf( __( 'Card %d — Text', 'alago-events' ), $n ), $svc[2], 'alago_services', 'textarea' );
	}

	/* ===============================================================
	 * 4. PORTFOLIO
	 * =============================================================== */
	$wp_customize->add_section( 'alago_portfolio', array( 'title' => __( 'Portfolio', 'alago-events' ), 'panel' => 'alago_front_page' ) );
	$add_toggle( 'alago_portfolio_enable', __( 'Show this section', 'alago-events' ), 'alago_portfolio' );
	$add_text( 'alago_portfolio_kicker', __( 'Overline Text', 'alago-events' ), __( 'Real Celebrations', 'alago-events' ), 'alago_portfolio' );
	$add_text( 'alago_portfolio_title', __( 'Title', 'alago-events' ), __( 'Selected Work', 'alago-events' ), 'alago_portfolio' );
	$add_text( 'alago_portfolio_intro', __( 'Intro Text', 'alago-events' ), __( 'A glimpse into some of the celebrations we have had the honour of creating.', 'alago-events' ), 'alago_portfolio', 'textarea' );
	$wp_customize->add_setting( 'alago_portfolio_count', array( 'default' => 6, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'alago_portfolio_count', array( 'label' => __( 'Number of items', 'alago-events' ), 'section' => 'alago_portfolio', 'type' => 'number', 'input_attrs' => array( 'min' => 3, 'max' => 12 ) ) );

	/* ===============================================================
	 * 5. PRICING
	 * =============================================================== */
	$wp_customize->add_section( 'alago_pricing', array( 'title' => __( 'Pricing', 'alago-events' ), 'panel' => 'alago_front_page' ) );
	$add_toggle( 'alago_pricing_enable', __( 'Show this section', 'alago-events' ), 'alago_pricing', false );
	$add_text( 'alago_pricing_kicker', __( 'Overline Text', 'alago-events' ), __( 'Investment', 'alago-events' ), 'alago_pricing' );
	$add_text( 'alago_pricing_title', __( 'Title', 'alago-events' ), __( 'Planning Packages', 'alago-events' ), 'alago_pricing' );
	$add_text( 'alago_pricing_intro', __( 'Intro Text', 'alago-events' ), __( 'Transparent, flexible packages designed around your celebration.', 'alago-events' ), 'alago_pricing', 'textarea' );

	$price_defaults = array(
		array( __( 'Coordination', 'alago-events' ), '2,000€', __( 'Flat fee', 'alago-events' ), "Pre-event planning support\nTimeline & supplier liaison\nFull on-the-day coordination", '' ),
		array( __( 'Full Planning', 'alago-events' ), '15%', __( 'Of sourced services', 'alago-events' ), "Everything in Coordination\nVenue sourcing & negotiation\nBespoke design & styling\nUnlimited consultations", __( 'Most Popular', 'alago-events' ) ),
		array( __( 'Bespoke', 'alago-events' ), 'POA', __( 'Tailored', 'alago-events' ), "Multi-day celebrations\nDestination logistics\nDedicated lead planner", '' ),
	);
	foreach ( $price_defaults as $i => $price ) {
		$n = $i + 1;
		$add_text( "alago_price_{$n}_title", sprintf( __( 'Plan %d — Name', 'alago-events' ), $n ), $price[0], 'alago_pricing' );
		$add_text( "alago_price_{$n}_amount", sprintf( __( 'Plan %d — Price', 'alago-events' ), $n ), $price[1], 'alago_pricing' );
		$add_text( "alago_price_{$n}_unit", sprintf( __( 'Plan %d — Unit', 'alago-events' ), $n ), $price[2], 'alago_pricing' );
		$add_text( "alago_price_{$n}_features", sprintf( __( 'Plan %d — Features (one per line)', 'alago-events' ), $n ), $price[3], 'alago_pricing', 'textarea' );
		$add_text( "alago_price_{$n}_badge", sprintf( __( 'Plan %d — Badge (optional)', 'alago-events' ), $n ), $price[4], 'alago_pricing' );
	}

	/* ===============================================================
	 * 6. TESTIMONIALS
	 * =============================================================== */
	$wp_customize->add_section( 'alago_testimonials', array( 'title' => __( 'Testimonials', 'alago-events' ), 'panel' => 'alago_front_page' ) );
	$add_toggle( 'alago_testimonials_enable', __( 'Show this section', 'alago-events' ), 'alago_testimonials' );
	$add_image( 'alago_testimonials_bg', __( 'Background Image', 'alago-events' ), 'alago_testimonials' );
	$add_text( 'alago_testimonials_kicker', __( 'Overline Text', 'alago-events' ), __( 'Kind Words', 'alago-events' ), 'alago_testimonials' );
	$add_text( 'alago_testimonials_title', __( 'Title', 'alago-events' ), __( 'Love Notes From Our Couples', 'alago-events' ), 'alago_testimonials' );

	/* ===============================================================
	 * 7. CONTACT / CTA
	 * =============================================================== */
	$wp_customize->add_section( 'alago_contact', array( 'title' => __( 'Contact', 'alago-events' ), 'panel' => 'alago_front_page' ) );
	$add_toggle( 'alago_contact_enable', __( 'Show this section', 'alago-events' ), 'alago_contact' );
	$add_text( 'alago_contact_kicker', __( 'Overline Text', 'alago-events' ), __( 'Get In Touch', 'alago-events' ), 'alago_contact' );
	$add_text( 'alago_contact_title', __( 'Title', 'alago-events' ), __( 'Let’s Plan Something Beautiful', 'alago-events' ), 'alago_contact' );
	$add_text( 'alago_contact_text', __( 'Intro Text', 'alago-events' ), __( 'Tell us about your celebration and we will be in touch within 48 hours.', 'alago-events' ), 'alago_contact', 'textarea' );
	$add_text( 'alago_contact_email', __( 'Email', 'alago-events' ), 'hello@example.com', 'alago_contact' );
	$add_text( 'alago_contact_phone', __( 'Phone', 'alago-events' ), '+34 000 000 000', 'alago_contact' );
	$add_text( 'alago_contact_address', __( 'Address', 'alago-events' ), __( 'Palma de Mallorca, Spain', 'alago-events' ), 'alago_contact', 'textarea' );
	$wp_customize->add_setting( 'alago_contact_shortcode', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'alago_contact_shortcode', array( 'label' => __( 'Contact Form Shortcode (optional)', 'alago-events' ), 'description' => __( 'Paste a form shortcode (e.g. Contact Form 7). Leave blank to use the built-in mailto form.', 'alago-events' ), 'section' => 'alago_contact', 'type' => 'textarea' ) );

	/* ===============================================================
	 * COLOURS
	 * =============================================================== */
	$wp_customize->add_section( 'alago_colors', array( 'title' => __( 'Theme Colours', 'alago-events' ), 'priority' => 30 ) );
	$colors = array(
		'alago_color_accent' => array( __( 'Accent (gold)', 'alago-events' ), '#b7965a' ),
		'alago_color_dark'   => array( __( 'Dark', 'alago-events' ), '#1c1c1a' ),
		'alago_color_light'  => array( __( 'Light / Cream', 'alago-events' ), '#faf7f1' ),
	);
	foreach ( $colors as $id => $data ) {
		$wp_customize->add_setting( $id, array( 'default' => $data[1], 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array( 'label' => $data[0], 'section' => 'alago_colors' ) ) );
	}

	/* ===============================================================
	 * TYPOGRAPHY
	 * =============================================================== */
	$wp_customize->add_section( 'alago_typography', array( 'title' => __( 'Typography', 'alago-events' ), 'priority' => 31 ) );
	$add_text( 'alago_font_heading', __( 'Heading Font Stack', 'alago-events' ), '"Cormorant Garamond", Georgia, serif', 'alago_typography' );
	$add_text( 'alago_font_body', __( 'Body Font Stack', 'alago-events' ), '"Montserrat", Arial, sans-serif', 'alago_typography' );

	/* ===============================================================
	 * SOCIAL & FOOTER
	 * =============================================================== */
	$wp_customize->add_section( 'alago_social', array( 'title' => __( 'Social & Footer', 'alago-events' ), 'priority' => 32 ) );
	$add_text( 'alago_social_instagram', __( 'Instagram URL', 'alago-events' ), '', 'alago_social', 'url' );
	$add_text( 'alago_social_facebook', __( 'Facebook URL', 'alago-events' ), '', 'alago_social', 'url' );
	$add_text( 'alago_social_pinterest', __( 'Pinterest URL', 'alago-events' ), '', 'alago_social', 'url' );
	$add_text( 'alago_social_youtube', __( 'YouTube URL', 'alago-events' ), '', 'alago_social', 'url' );
	$add_text( 'alago_footer_text', __( 'Footer Copyright Text', 'alago-events' ), '', 'alago_social' );
}
add_action( 'customize_register', 'alago_events_customize_register' );

/**
 * Sanitize a checkbox.
 *
 * @param mixed $checked Value.
 * @return bool
 */
function alago_events_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked );
}

/**
 * Live preview JS for the Customizer.
 */
function alago_events_customize_preview_js() {
	wp_enqueue_script( 'alago-events-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), ALAGO_EVENTS_VERSION, true );
}
add_action( 'customize_preview_init', 'alago_events_customize_preview_js' );
