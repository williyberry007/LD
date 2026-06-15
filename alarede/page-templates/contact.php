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

	<div class="ae-page-header">
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Contact', 'alarede' ); ?></span>
			<h1><?php echo esc_html( get_the_title() ? get_the_title() : __( 'Get In Touch', 'alarede' ) ); ?></h1>
		</div>
	</div>

	<section class="ae-section">
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
				</div>

				<div class="ae-contact__form ae-reveal">
					<?php
					if ( $shortcode ) {
						echo do_shortcode( $shortcode );
					} else {
						?>
						<form action="mailto:<?php echo esc_attr( $email ); ?>" method="post" enctype="text/plain">
							<input type="text" name="name" placeholder="<?php esc_attr_e( 'Your name', 'alarede' ); ?>" required>
							<input type="email" name="email" placeholder="<?php esc_attr_e( 'Email address', 'alarede' ); ?>" required>
							<input type="text" name="subject" placeholder="<?php esc_attr_e( 'Subject', 'alarede' ); ?>">
							<textarea name="message" placeholder="<?php esc_attr_e( 'How can we help?', 'alarede' ); ?>" required></textarea>
							<button type="submit" class="ae-btn ae-btn--solid"><?php esc_html_e( 'Send Message', 'alarede' ); ?></button>
						</form>
						<?php
					}
					?>
				</div>
			</div>

			<?php if ( $map ) : ?>
				<div class="ae-contact__map" style="margin-top:3rem;"><?php echo alarede_sanitize_embed( $map ); ?></div>
			<?php endif; ?>
		</div>
	</section>

	<?php
endwhile;

get_footer();
