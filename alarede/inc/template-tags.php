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
		'about_values' => array(
			array( __( 'Listen', 'alarede' ), __( 'We begin with your story, your people and your vision — never a template.', 'alarede' ) ),
			array( __( 'Design', 'alarede' ), __( 'We translate that vision into a cohesive, beautiful and personal design.', 'alarede' ) ),
			array( __( 'Deliver', 'alarede' ), __( 'On the day, we manage every detail so you can simply be present.', 'alarede' ) ),
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
 * Build a muted, looping background embed for a YouTube/Vimeo URL.
 *
 * @param string $url Source URL.
 * @return string Iframe HTML, or '' when the provider is not recognised.
 */
function alarede_video_embed( $url ) {
	if ( preg_match( '~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $m ) ) {
		$id  = $m[1];
		$src = add_query_arg(
			array(
				'autoplay'       => 1,
				'mute'           => 1,
				'loop'           => 1,
				'playlist'       => $id,
				'controls'       => 0,
				'modestbranding' => 1,
				'playsinline'    => 1,
				'rel'            => 0,
			),
			'https://www.youtube.com/embed/' . $id
		);
		return sprintf( '<iframe src="%s" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen title="%s"></iframe>', esc_url( $src ), esc_attr__( 'Video', 'alarede' ) );
	}
	if ( preg_match( '~vimeo\.com/(?:video/)?(\d+)~', $url, $m ) ) {
		$src = add_query_arg(
			array( 'autoplay' => 1, 'muted' => 1, 'loop' => 1, 'background' => 1 ),
			'https://player.vimeo.com/video/' . $m[1]
		);
		return sprintf( '<iframe src="%s" frameborder="0" allow="autoplay; fullscreen" allowfullscreen title="%s"></iframe>', esc_url( $src ), esc_attr__( 'Video', 'alarede' ) );
	}
	return '';
}

/**
 * Return inline video markup for a URL: a self-hosted file plays as a muted
 * looping clip; a YouTube/Vimeo link is embedded.
 *
 * @param string $url    Video URL.
 * @param string $poster Optional poster image URL (self-hosted only).
 * @return string Markup, or '' when nothing renders.
 */
function alarede_inline_video( $url, $poster = '' ) {
	if ( ! $url ) {
		return '';
	}
	$ext = strtolower( pathinfo( (string) wp_parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
	if ( in_array( $ext, array( 'mp4', 'webm', 'ogg' ), true ) ) {
		return sprintf(
			'<video class="ae-inline-video" autoplay muted loop playsinline preload="auto"%1$s><source src="%2$s" type="video/%3$s"></video>',
			$poster ? ' poster="' . esc_url( $poster ) . '"' : '',
			esc_url( $url ),
			esc_attr( $ext )
		);
	}
	$embed = alarede_video_embed( $url );
	return $embed ? '<div class="ae-inline-embed">' . $embed . '</div>' : '';
}

/**
 * Return an inline SVG icon for a social network.
 *
 * @param string $network instagram|facebook|pinterest|youtube.
 * @return string SVG markup (empty if unknown).
 */
function alarede_social_icon( $network ) {
	$open  = '<svg class="ae-icon" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">';
	$paths = array(
		'instagram' => '<path d="M12 2.16c3.2 0 3.58 0 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s0 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s0-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 1.62c-3.15 0-3.5 0-4.74.07-.9.04-1.38.19-1.7.31-.43.17-.74.37-1.06.69-.32.32-.52.63-.69 1.06-.12.32-.27.8-.31 1.7-.06 1.23-.07 1.6-.07 4.73s0 3.5.07 4.74c.04.9.19 1.38.31 1.7.17.43.37.74.69 1.06.32.32.63.52 1.06.69.32.12.8.27 1.7.31 1.24.06 1.6.07 4.74.07s3.5 0 4.74-.07c.9-.04 1.38-.19 1.7-.31.43-.17.74-.37 1.06-.69.32-.32.52-.63.69-1.06.12-.32.27-.8.31-1.7.06-1.24.07-1.6.07-4.74s0-3.5-.07-4.73c-.04-.9-.19-1.39-.31-1.7a2.85 2.85 0 0 0-.69-1.07 2.85 2.85 0 0 0-1.06-.69c-.32-.12-.8-.27-1.7-.31-1.24-.06-1.6-.07-4.74-.07zm0 2.76a5.46 5.46 0 1 1 0 10.92 5.46 5.46 0 0 1 0-10.92zm0 9a3.54 3.54 0 1 0 0-7.08 3.54 3.54 0 0 0 0 7.08zm6.95-9.2a1.28 1.28 0 1 1-2.55 0 1.28 1.28 0 0 1 2.55 0z"/>',
		'facebook'  => '<path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.51 1.49-3.9 3.78-3.9 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.44 2.9h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94z"/>',
		'pinterest' => '<path d="M12 2C6.48 2 2 6.48 2 12c0 4.24 2.64 7.86 6.36 9.32-.09-.79-.17-2 .03-2.86.18-.78 1.18-4.97 1.18-4.97s-.3-.6-.3-1.49c0-1.4.81-2.44 1.82-2.44.86 0 1.27.64 1.27 1.42 0 .86-.55 2.15-.83 3.35-.24 1 .5 1.82 1.49 1.82 1.79 0 3.16-1.89 3.16-4.61 0-2.41-1.73-4.1-4.21-4.1-2.87 0-4.55 2.15-4.55 4.37 0 .86.33 1.79.75 2.29.08.1.09.19.07.29l-.28 1.13c-.04.18-.15.22-.34.13-1.25-.58-2.03-2.4-2.03-3.87 0-3.15 2.29-6.04 6.6-6.04 3.46 0 6.16 2.47 6.16 5.77 0 3.44-2.17 6.21-5.18 6.21-1.01 0-1.97-.53-2.29-1.15l-.62 2.37c-.23.86-.83 1.95-1.24 2.61.93.29 1.92.44 2.95.44 5.52 0 10-4.48 10-10S17.52 2 12 2z"/>',
		'youtube'   => '<path d="M23 12s0-3.2-.41-4.74a2.5 2.5 0 0 0-1.76-1.76C19.29 5.1 12 5.1 12 5.1s-7.29 0-8.83.4a2.5 2.5 0 0 0-1.76 1.76C1 8.8 1 12 1 12s0 3.2.41 4.74a2.5 2.5 0 0 0 1.76 1.76c1.54.4 8.83.4 8.83.4s7.29 0 8.83-.4a2.5 2.5 0 0 0 1.76-1.76C23 15.2 23 12 23 12zm-13.4 3.27V8.73L15.27 12 9.6 15.27z"/>',
	);
	if ( empty( $paths[ $network ] ) ) {
		return '';
	}
	return $open . $paths[ $network ] . '</svg>';
}

/**
 * Echo the inline style attribute for an .ae-page-header banner.
 *
 * Priority: the post's featured image (on singular views, when allowed) →
 * the Customizer "Default Page Header Image" → no inline style, so the CSS
 * gradient default applies. An image always gets a dark overlay so the white
 * heading stays readable.
 *
 * @param bool $use_featured Whether to use the current post's featured image.
 */
function alarede_page_header_style( $use_featured = true ) {
	$image = '';
	if ( $use_featured && is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'full' );
	}
	if ( ! $image ) {
		$image = get_theme_mod( 'alarede_page_header_image', '' );
	}
	if ( ! $image ) {
		return; // Fall back to the CSS gradient default.
	}
	$style = sprintf(
		'background-image:linear-gradient(rgba(20,20,18,.55),rgba(20,20,18,.7)),url(%s);background-size:cover;background-position:center;',
		esc_url( $image )
	);
	echo ' style="' . esc_attr( $style ) . '"';
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
