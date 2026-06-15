<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 *
 * Contact page reusing the contact details from the Customizer plus an
 * optional map embed. Add a form-plugin shortcode in the page content (or
 * set one in Customize → Front Page Sections → Contact) for a real form.
 *
 * @package Alarede
 */

get_header();

$email     = get_theme_mod( 'alarede_contact_email', 'hello@example.com' );
$phone     = get_theme_mod( 'alarede_contact_phone', '' );
$address   = get_theme_mod( 'alarede_contact_address', '' );
$shortcode = get_theme_mod( 'alarede_contact_shortcode', '' );
$map       = get_theme_mod( 'alarede_contact_map', '' );

while ( have_posts() ) :
	the_post();
	?>

	<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Contact', 'alarede' ); ?></span>
			<h1><?php echo esc_html( get_the_title() ? get_the_title() : __( 'Get In Touch', 'alarede' ) ); ?></h1>
		</div>
	</div>

	<section class="ae-section" id="contact">
		<div class="ae-container">
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="entry-content" style="text-align:center;max-width:760px;margin:0 auto 3rem;"><?php the_content(); ?></div>
			<?php endif; ?>

			<div class="ae-contact__grid">
				<div class="ae-contact__info ae-reveal">
					<h3><?php esc_html_e( 'Studio', 'alarede' ); ?></h3>
					<?php if ( $email ) : ?>
						<p><strong><?php esc_html_e( 'Email', 'alarede' ); ?></strong><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<p><strong><?php esc_html_e( 'Phone', 'alarede' ); ?></strong><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
					<?php endif; ?>
					<?php if ( $address ) : ?>
						<p><strong><?php esc_html_e( 'Address', 'alarede' ); ?></strong><?php echo wp_kses_post( nl2br( $address ) ); ?></p>
					<?php endif; ?>
					<?php if ( $map ) : ?>
						<div class="ae-contact__map" style="margin-top:1.5rem;"><?php echo alarede_sanitize_embed( $map ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised iframe. ?></div>
					<?php endif; ?>
				</div>

				<div class="ae-contact__form ae-reveal">
					<?php
					if ( $shortcode ) {
						echo do_shortcode( $shortcode );
					} else {
						echo alarede_booking_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped internally.
					}
					?>
				</div>
			</div>
		</div>
	</section>

	<?php
endwhile;

get_footer();
