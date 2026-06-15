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
