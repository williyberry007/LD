<?php
/**
 * The footer for our theme.
 *
 * @package Alago_Events
 */

$ae_socials = array(
	'instagram' => __( 'Instagram', 'alago-events' ),
	'facebook'  => __( 'Facebook', 'alago-events' ),
	'pinterest' => __( 'Pinterest', 'alago-events' ),
	'youtube'   => __( 'YouTube', 'alago-events' ),
);
?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="ae-container">
			<div class="ae-footer__widgets">
				<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
					<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
						<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
							<div class="ae-footer__col"><?php dynamic_sidebar( 'footer-' . $i ); ?></div>
						<?php endif; ?>
					<?php endfor; ?>
				<?php else : ?>
					<div class="ae-footer__brand">
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></p>
						<p><?php bloginfo( 'description' ); ?></p>
						<div class="ae-footer__social">
							<?php
							foreach ( $ae_socials as $key => $label ) {
								$url = get_theme_mod( 'alago_social_' . $key, '' );
								if ( $url ) {
									printf(
										'<a href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a>',
										esc_url( $url ),
										esc_attr( $label ),
										esc_html( substr( $label, 0, 1 ) )
									);
								}
							}
							?>
						</div>
					</div>
					<div class="ae-footer__col">
						<h3 class="widget-title"><?php esc_html_e( 'Explore', 'alago-events' ); ?></h3>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</div>
					<div class="ae-footer__col">
						<h3 class="widget-title"><?php esc_html_e( 'Get In Touch', 'alago-events' ); ?></h3>
						<p><?php echo esc_html( get_theme_mod( 'alago_contact_email', 'hello@example.com' ) ); ?><br>
						<?php echo esc_html( get_theme_mod( 'alago_contact_phone', '' ) ); ?></p>
						<p><?php echo wp_kses_post( nl2br( get_theme_mod( 'alago_contact_address', '' ) ) ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="ae-footer__bottom">
			<div class="ae-container">
				<?php
				$footer_text = get_theme_mod( 'alago_footer_text', '' );
				if ( $footer_text ) {
					echo esc_html( $footer_text );
				} else {
					printf(
						/* translators: 1: year, 2: site name. */
						esc_html__( '© %1$s %2$s. All rights reserved.', 'alago-events' ),
						esc_html( gmdate( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
				}
				?>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<button class="ae-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'alago-events' ); ?>">&uarr;</button>

<?php wp_footer(); ?>
</body>
</html>
