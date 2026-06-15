<?php
/**
 * Custom template tags for this theme.
 *
 * @package Alarede
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'alarede_posted_on' ) ) {
	/**
	 * Print the post date / author meta.
	 */
	function alarede_posted_on() {
		printf(
			'<span class="ae-post-meta">%1$s &middot; %2$s</span>',
			esc_html( get_the_date() ),
			esc_html( get_the_author() )
		);
	}
}

if ( ! function_exists( 'alarede_entry_footer' ) ) {
	/**
	 * Print categories and tags.
	 */
	function alarede_entry_footer() {
		if ( 'post' !== get_post_type() ) {
			return;
		}

		$categories = get_the_category_list( ', ' );
		if ( $categories ) {
			printf( '<p class="ae-entry-cats">%1$s %2$s</p>', esc_html__( 'Filed under:', 'alarede' ), wp_kses_post( $categories ) );
		}
	}
}

if ( ! function_exists( 'alarede_pagination' ) ) {
	/**
	 * Themed pagination wrapper.
	 */
	function alarede_pagination() {
		the_posts_pagination(
			array(
				'mid_size'           => 1,
				'prev_text'          => __( '&larr; Previous', 'alarede' ),
				'next_text'          => __( 'Next &rarr;', 'alarede' ),
				'screen_reader_text' => __( 'Posts navigation', 'alarede' ),
				'class'              => 'ae-pagination',
			)
		);
	}
}

if ( ! function_exists( 'alarede_post_thumbnail' ) ) {
	/**
	 * Output a post thumbnail wrapped for cards.
	 *
	 * @param string $size Image size.
	 */
	function alarede_post_thumbnail( $size = 'alarede-card' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}
		echo '<div class="ae-post-card__thumb">';
		the_post_thumbnail( $size, array( 'loading' => 'lazy' ) );
		echo '</div>';
	}
}

/**
 * Helper: fetch a Customizer value with a default.
 *
 * @param string $key     Setting key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function alarede_mod( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/**
 * Default content for the editable page-template lists.
 *
 * Shared by the Customizer (to seed control defaults) and the page templates
 * (to render when a field is left at its default). Keyed by group.
 *
 * @param string $group Optional group key to return just that list.
 * @return array
 */
function alarede_template_defaults( $group = '' ) {
	$defaults = array(
		'services' => array(
			array( '✦', __( 'Full Planning & Design', 'alarede' ), __( 'End-to-end planning, styling and on-the-day coordination tailored entirely to your vision.', 'alarede' ) ),
			array( '❀', __( 'Venue Sourcing', 'alarede' ), __( 'We listen to your vision, present tailored venue options and guide you to the perfect setting.', 'alarede' ) ),
			array( '♛', __( 'Event Coordination', 'alarede' ), __( 'Seamless coordination so you can be fully present and enjoy every precious moment.', 'alarede' ) ),
		),
		'prices'   => array(
			array( __( 'Coordination', 'alarede' ), '2,000€', __( 'Flat fee', 'alarede' ), "Pre-event planning support\nTimeline & supplier liaison\nFull on-the-day coordination", '' ),
			array( __( 'Full Planning', 'alarede' ), '15%', __( 'Of sourced services', 'alarede' ), "Everything in Coordination\nVenue sourcing & negotiation\nBespoke design & styling\nUnlimited consultations", __( 'Most Popular', 'alarede' ) ),
			array( __( 'Bespoke', 'alarede' ), 'POA', __( 'Tailored', 'alarede' ), "Multi-day celebrations\nDestination logistics\nDedicated lead planner", '' ),
		),
		'events'  => array(
			array( __( 'Introductions', 'alarede' ), __( 'A graceful first gathering of two families, planned and hosted with warmth and tradition.', 'alarede' ) ),
			array( __( 'Engagements', 'alarede' ), __( 'Memorable engagement celebrations and proposals, styled and coordinated end to end.', 'alarede' ) ),
			array( __( 'Wedding Reception', 'alarede' ), __( 'Seamless receptions — design, flow, catering liaison and on-the-day management.', 'alarede' ) ),
			array( __( 'MC', 'alarede' ), __( 'Professional master-of-ceremonies services to keep your celebration flowing beautifully.', 'alarede' ) ),
			array( __( 'Weddings', 'alarede' ), __( 'Full wedding planning and design, from concept to the final farewell.', 'alarede' ) ),
			array( __( 'Marriage Counselling', 'alarede' ), __( 'Caring, professional guidance to help couples build a strong foundation for marriage.', 'alarede' ) ),
		),
		'academy' => array(
			array( __( 'Event Mastery', 'alarede' ), __( 'Everything we do in events — planning, design, coordination and hosting — taught hands-on.', 'alarede' ) ),
			array( __( 'Gele', 'alarede' ), __( 'The art of tying elegant gele headwraps for brides, guests and special occasions.', 'alarede' ) ),
			array( __( 'Makeup', 'alarede' ), __( 'Professional bridal and occasion makeup techniques, from flawless base to finishing touches.', 'alarede' ) ),
			array( __( 'Gift Wrapping', 'alarede' ), __( 'Creative, luxurious gift presentation and packaging for every celebration.', 'alarede' ) ),
			array( __( 'Eru-Iyawo Wrapping', 'alarede' ), __( 'Traditional bridal trousseau arrangement and presentation, taught with cultural authenticity.', 'alarede' ) ),
			array( __( 'Letters (Proposal & Acceptance)', 'alarede' ), __( 'Composing the traditional proposal and acceptance letters with the proper etiquette and tone.', 'alarede' ) ),
		),
		'values'  => array(
			array( __( 'Craft', 'alarede' ), __( 'We sweat the details so every celebration feels effortless.', 'alarede' ) ),
			array( __( 'Care', 'alarede' ), __( 'We look after our clients and each other with genuine warmth.', 'alarede' ) ),
			array( __( 'Growth', 'alarede' ), __( 'We invest in learning, mentorship and creative freedom.', 'alarede' ) ),
		),
		'jobs'    => array(
			array( __( 'Event Planner', 'alarede' ), __( 'Full-time · Hybrid', 'alarede' ) ),
			array( __( 'Junior Coordinator', 'alarede' ), __( 'Full-time · On-site', 'alarede' ) ),
			array( __( 'Academy Instructor', 'alarede' ), __( 'Part-time · Flexible', 'alarede' ) ),
			array( '', '' ),
			array( '', '' ),
		),
		'faqs'    => array(
			array( __( 'How far in advance should we book?', 'alarede' ), __( 'We recommend reaching out as early as possible — popular dates and venues are reserved well ahead. We accept a limited number of events each year to give every couple our full attention.', 'alarede' ) ),
			array( __( 'How do your fees work?', 'alarede' ), __( 'Coordination is offered for a flat fee, and full planning typically includes a percentage of the services we source and manage on your behalf. We provide a clear, tailored proposal after our first conversation.', 'alarede' ) ),
			array( __( 'Do you travel for destination events?', 'alarede' ), __( 'Yes. We plan and coordinate destination celebrations and handle the logistics so you can simply enjoy the experience.', 'alarede' ) ),
			array( __( 'Can you work with our existing suppliers?', 'alarede' ), __( 'Absolutely. We are happy to collaborate with vendors you love, and can recommend trusted partners where needed.', 'alarede' ) ),
			array( __( 'Do you offer training through the Academy?', 'alarede' ), __( 'Yes — our Academy teaches event skills, gele, makeup, gift wrapping, eru-iyawo wrapping and traditional letters. Contact us for the next intake.', 'alarede' ) ),
			array( '', '' ),
			array( '', '' ),
			array( '', '' ),
		),
	);

	if ( $group ) {
		return isset( $defaults[ $group ] ) ? $defaults[ $group ] : array();
	}
	return $defaults;
}

/**
 * Read an editable two-field list ("title" + "text") from the Customizer.
 *
 * @param string $prefix Setting prefix, e.g. 'alarede_events_item'.
 * @param string $group  Defaults group key (see alarede_template_defaults()).
 * @return array         List of [ title, text ] pairs with non-empty titles.
 */
function alarede_get_list( $prefix, $group ) {
	$defaults = alarede_template_defaults( $group );
	$items    = array();
	foreach ( $defaults as $i => $row ) {
		$n     = $i + 1;
		$title = get_theme_mod( "{$prefix}_{$n}_title", $row[0] );
		$text  = get_theme_mod( "{$prefix}_{$n}_text", isset( $row[1] ) ? $row[1] : '' );
		if ( '' !== trim( (string) $title ) ) {
			$items[] = array( $title, $text );
		}
	}
	return $items;
}

/**
 * Output a section heading block (kicker + title + intro).
 *
 * @param string $kicker Small overline label.
 * @param string $title  Section title.
 * @param string $intro  Optional intro paragraph.
 */
function alarede_section_head( $kicker, $title, $intro = '' ) {
	echo '<div class="ae-section-head ae-reveal">';
	if ( $kicker ) {
		echo '<span class="ae-kicker">' . esc_html( $kicker ) . '</span>';
	}
	if ( $title ) {
		echo '<h2>' . esc_html( $title ) . '</h2>';
	}
	if ( $intro ) {
		echo '<p>' . wp_kses_post( $intro ) . '</p>';
	}
	echo '</div>';
}
