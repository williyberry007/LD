<?php
/**
 * Contact section.
 *
 * @package Alago_Events
 */

$kicker    = get_theme_mod( 'alago_contact_kicker', __( 'Get In Touch', 'alago-events' ) );
$title     = get_theme_mod( 'alago_contact_title', __( 'Let’s Plan Something Beautiful', 'alago-events' ) );
$text      = get_theme_mod( 'alago_contact_text', '' );
$email     = get_theme_mod( 'alago_contact_email', 'hello@example.com' );
$phone     = get_theme_mod( 'alago_contact_phone', '' );
$address   = get_theme_mod( 'alago_contact_address', '' );
$shortcode = get_theme_mod( 'alago_contact_shortcode', '' );
?>
<section class="ae-section ae-contact" id="contact">
	<div class="ae-container">
		<?php alago_events_section_head( $kicker, $title ); ?>

		<div class="ae-contact__grid">
			<div class="ae-contact__info ae-reveal">
				<?php if ( $text ) : ?><p><?php echo wp_kses_post( $text ); ?></p><?php endif; ?>
				<?php if ( $email ) : ?>
					<p><strong><?php esc_html_e( 'Email', 'alago-events' ); ?></strong><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
					<p><strong><?php esc_html_e( 'Phone', 'alago-events' ); ?></strong><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
				<?php endif; ?>
				<?php if ( $address ) : ?>
					<p><strong><?php esc_html_e( 'Studio', 'alago-events' ); ?></strong><?php echo wp_kses_post( nl2br( $address ) ); ?></p>
				<?php endif; ?>
			</div>

			<div class="ae-contact__form ae-reveal">
				<?php
				if ( $shortcode ) {
					echo do_shortcode( $shortcode );
				} else {
					$subject = rawurlencode( __( 'Event enquiry', 'alago-events' ) );
					?>
					<form action="mailto:<?php echo esc_attr( $email ); ?>" method="post" enctype="text/plain">
						<input type="text" name="name" placeholder="<?php esc_attr_e( 'Your name', 'alago-events' ); ?>" required>
						<input type="email" name="email" placeholder="<?php esc_attr_e( 'Email address', 'alago-events' ); ?>" required>
						<input type="text" name="date" placeholder="<?php esc_attr_e( 'Approximate event date', 'alago-events' ); ?>">
						<textarea name="message" placeholder="<?php esc_attr_e( 'Tell us about your celebration…', 'alago-events' ); ?>" required></textarea>
						<button type="submit" class="ae-btn ae-btn--solid"><?php esc_html_e( 'Send Enquiry', 'alago-events' ); ?></button>
					</form>
					<p style="font-size:.8rem;color:var(--ae-color-muted);margin-top:1rem;">
						<?php esc_html_e( 'Tip: install a form plugin (e.g. Contact Form 7) and paste its shortcode in the Customizer for a fully featured form.', 'alago-events' ); ?>
					</p>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
