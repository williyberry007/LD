<?php
/**
 * Alarede theme functions and definitions.
 *
 * @package Alarede
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALAREDE_VERSION', '1.0.0' );

/**
 * Theme setup.
 */
function alarede_setup() {
	load_theme_textdomain( 'alarede', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'html5',
		array( 'navigation-widgets' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'alarede' ),
			'footer'  => __( 'Footer Menu', 'alarede' ),
		)
	);

	// Custom image sizes used by the portfolio and blog cards.
	add_image_size( 'alarede-portrait', 600, 800, true );
	add_image_size( 'alarede-card', 800, 500, true );
}
add_action( 'after_setup_theme', 'alarede_setup' );

/**
 * Set the content width.
 */
function alarede_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'alarede_content_width', 1200 );
}
add_action( 'after_setup_theme', 'alarede_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function alarede_scripts() {
	// Google Fonts.
	wp_enqueue_style(
		'alarede-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Montserrat:wght@300;400;500;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'alarede-style', get_stylesheet_uri(), array( 'alarede-fonts' ), ALAREDE_VERSION );

	// Inline the Customizer-driven CSS variables.
	wp_add_inline_style( 'alarede-style', alarede_inline_css() );

	wp_enqueue_script( 'alarede-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), ALAREDE_VERSION, true );
	wp_enqueue_script( 'alarede-main', get_template_directory_uri() . '/assets/js/main.js', array(), ALAREDE_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'alarede_scripts' );

/**
 * Build the dynamic CSS that maps Customizer colour/font settings to CSS vars.
 *
 * @return string
 */
function alarede_inline_css() {
	$accent  = get_theme_mod( 'alarede_color_accent', '#b7965a' );
	$dark    = get_theme_mod( 'alarede_color_dark', '#1c1c1a' );
	$light   = get_theme_mod( 'alarede_color_light', '#faf7f1' );
	$heading = get_theme_mod( 'alarede_font_heading', '"Cormorant Garamond", Georgia, serif' );
	$body    = get_theme_mod( 'alarede_font_body', '"Montserrat", Arial, sans-serif' );

	$css  = ':root{';
	$css .= '--ae-color-accent:' . sanitize_text_field( $accent ) . ';';
	$css .= '--ae-color-dark:' . sanitize_text_field( $dark ) . ';';
	$css .= '--ae-color-light:' . sanitize_text_field( $light ) . ';';
	$css .= '--ae-font-heading:' . wp_strip_all_tags( $heading ) . ';';
	$css .= '--ae-font-body:' . wp_strip_all_tags( $body ) . ';';
	$css .= '}';

	return $css;
}

/**
 * Register widget areas.
 */
function alarede_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'alarede' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets shown beside blog posts and pages.', 'alarede' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer Column %d', 'alarede' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => __( 'Footer widget area.', 'alarede' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'alarede_widgets_init' );

/**
 * Custom body classes.
 *
 * @param array $classes Body classes.
 * @return array
 */
function alarede_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( is_front_page() ) {
		$classes[] = 'ae-front';
	}
	return $classes;
}
add_filter( 'body_class', 'alarede_body_classes' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/custom-post-types.php';
require get_template_directory() . '/inc/customizer.php';

/**
 * Tell WordPress the front page uses a wide layout (no sidebar).
 */
function alarede_has_sidebar() {
	return is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'page-templates/full-width.php' ) && ! is_front_page() && ! is_page_template( 'page-templates/landing.php' );
}
