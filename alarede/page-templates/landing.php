<?php
/**
 * Template Name: Landing (Homepage Sections)
 * Template Post Type: page
 *
 * Renders the same configurable sections as the front page, so you can build
 * additional landing pages without setting them as the site front page.
 *
 * @package Alarede
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	get_template_part( 'template-parts/sections/hero' );

	if ( get_theme_mod( 'alarede_intro_enable', true ) ) {
		get_template_part( 'template-parts/sections/intro' );
	}
	if ( get_theme_mod( 'alarede_feature_enable', true ) ) {
		get_template_part( 'template-parts/sections/feature' );
	}
	if ( get_theme_mod( 'alarede_services_enable', true ) ) {
		get_template_part( 'template-parts/sections/services' );
	}
	if ( get_theme_mod( 'alarede_portfolio_enable', true ) ) {
		get_template_part( 'template-parts/sections/portfolio' );
	}
	if ( get_theme_mod( 'alarede_pricing_enable', false ) ) {
		get_template_part( 'template-parts/sections/pricing' );
	}
	if ( get_theme_mod( 'alarede_testimonials_enable', true ) ) {
		get_template_part( 'template-parts/sections/testimonials' );
	}
	if ( get_theme_mod( 'alarede_contact_enable', true ) ) {
		get_template_part( 'template-parts/sections/contact' );
	}
	if ( get_theme_mod( 'alarede_clients_enable', true ) ) {
		get_template_part( 'template-parts/sections/clients' );
	}

	while ( have_posts() ) :
		the_post();
		if ( trim( get_the_content() ) ) {
			echo '<section class="ae-section"><div class="ae-container ae-content-area no-sidebar"><div class="entry-content">';
			the_content();
			echo '</div></div></section>';
		}
	endwhile;
	?>
</main>

<?php
get_footer();
