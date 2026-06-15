<?php
/**
 * Contact section.
 *
 * @package Alarede
 */

$kicker    = get_theme_mod( 'alarede_contact_kicker', __( 'Get In Touch', 'alarede' ) );
$title     = get_theme_mod( 'alarede_contact_title', __( 'Let’s Plan Something Beautiful', 'alarede' ) );
$text      = get_theme_mod( 'alarede_contact_text', '' );
$email     = get_theme_mod( 'alarede_contact_email', 'hello@example.com' );
$phone     = get_theme_mod( 'alarede_contact_phone', '' );
$address   = get_theme_mod( 'alarede_contact_address', '' );
$shortcode = get_theme_mod( 'alarede_contact_shortcode', '' );
?>
<section class="ae-section ae-contact" id="contact">
	<div class="ae-container">
		<?php alarede_section_head( $kicker, $title ); ?>

		<div class="ae-contact__grid">
			<div class="ae-contact__info ae-reveal">
				<?php if ( $text ) : ?><p><?php echo wp_kses_post( $text ); ?></p><?php endif; ?>
				<?php if ( $email ) : ?>
					<p><strong><?php esc_html_e( 'Email', 'alarede' ); ?></strong><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
					<p><strong><?php esc_html_e( 'Phone', 'alarede' ); ?></strong><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
				<?php endif; ?>
				<?php if ( $address ) : ?>
					<p><strong><?php esc_html_e( 'Studio', 'alarede' ); ?></strong><?php echo wp_kses_post( nl2br( $address ) ); ?></p>
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
