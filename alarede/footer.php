<?php
/**
 * The footer for our theme.
 *
 * Layout: a centred tagline band, a divider, four columns (brand + about,
 * Services menu, Get In Touch, Connect With Us) and a bottom bar with the
 * copyright and policy links. All text is editable via the Customizer.
 *
 * @package Alarede
 */

$ae_socials = array(
	'instagram' => __( 'Instagram', 'alarede' ),
	'facebook'  => __( 'Facebook', 'alarede' ),
	'pinterest' => __( 'Pinterest', 'alarede' ),
	'youtube'   => __( 'YouTube', 'alarede' ),
);

$ae_email   = get_theme_mod( 'alarede_contact_email', 'hello@example.com' );
$ae_phone   = get_theme_mod( 'alarede_contact_phone', '' );
$ae_about   = get_theme_mod( 'alarede_footer_about', __( 'An elegant studio crafting bespoke luxury weddings and events — blending timeless design, exclusivity and personal care into truly extraordinary celebrations.', 'alarede' ) );
$ae_widgets = is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' );
?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php if ( get_theme_mod( 'alarede_footer_tagline_enable', true ) ) : ?>
			<div class="ae-container">
				<div class="ae-footer__tagline">
					<?php
					$f_kicker = get_theme_mod( 'alarede_footer_tagline_kicker', __( 'For Those Who Desire the Finest', 'alarede' ) );
					$f_title  = get_theme_mod( 'alarede_footer_tagline_title', __( 'Exclusive Luxury Wedding Experiences', 'alarede' ) );
					if ( $f_kicker ) {
						echo '<span class="ae-footer__tagline-kicker">' . esc_html( $f_kicker ) . '</span>';
					}
					if ( $f_title ) {
						echo '<h2 class="ae-footer__tagline-title">' . esc_html( $f_title ) . '</h2>';
					}
					?>
				</div>
			</div>
		<?php endif; ?>

		<div class="ae-container">
			<div class="ae-footer__widgets">
				<?php if ( $ae_widgets ) : ?>
					<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
						<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
							<div class="ae-footer__col"><?php dynamic_sidebar( 'footer-' . $i ); ?></div>
						<?php endif; ?>
					<?php endfor; ?>
				<?php else : ?>

					<div class="ae-footer__col ae-footer__brand">
						<h3 class="ae-footer__heading"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h3>
						<?php if ( $ae_about ) : ?><p class="ae-footer__about"><?php echo esc_html( $ae_about ); ?></p><?php endif; ?>
					</div>

					<div class="ae-footer__col">
						<h3 class="ae-footer__heading"><?php esc_html_e( 'Services', 'alarede' ); ?></h3>
						<?php
						if ( has_nav_menu( 'footer' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'container'      => false,
									'depth'          => 1,
									'menu_class'     => 'ae-footer__menu',
									'fallback_cb'    => false,
								)
							);
						} else {
							echo '<ul class="ae-footer__menu">';
							echo '<li><a href="' . esc_url( home_url( '/events/' ) ) . '">' . esc_html__( 'Weddings', 'alarede' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/events/' ) ) . '">' . esc_html__( 'Events', 'alarede' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/gallery/' ) ) . '">' . esc_html__( 'Venues', 'alarede' ) . '</a></li>';
							echo '</ul>';
						}
						?>
					</div>

					<div class="ae-footer__col">
						<h3 class="ae-footer__heading"><?php esc_html_e( 'Get In Touch', 'alarede' ); ?></h3>
						<ul class="ae-footer__contact">
							<?php if ( $ae_phone ) : ?>
								<li>
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
									<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $ae_phone ) ); ?>"><?php echo esc_html( $ae_phone ); ?></a>
								</li>
							<?php endif; ?>
							<?php if ( $ae_email ) : ?>
								<li>
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
									<a href="mailto:<?php echo esc_attr( $ae_email ); ?>"><?php echo esc_html( $ae_email ); ?></a>
								</li>
							<?php endif; ?>
						</ul>
					</div>

					<div class="ae-footer__col">
						<h3 class="ae-footer__heading"><?php esc_html_e( 'Connect With Us', 'alarede' ); ?></h3>
						<ul class="ae-footer__social">
							<?php
							foreach ( $ae_socials as $key => $label ) {
								$url = get_theme_mod( 'alarede_social_' . $key, '' );
								if ( ! $url ) {
									continue;
								}
								printf(
									'<li><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s<span>%3$s</span></a></li>',
									esc_url( $url ),
									alarede_social_icon( $key ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted inline SVG.
									esc_html( $label )
								);
							}
							?>
						</ul>
					</div>

				<?php endif; ?>
			</div>
		</div>

		<div class="ae-footer__bottom">
			<div class="ae-container ae-footer__bottom-inner">
				<p class="ae-footer__copy">
					<?php
					$footer_text = get_theme_mod( 'alarede_footer_text', '' );
					if ( $footer_text ) {
						echo esc_html( $footer_text );
					} else {
						printf(
							/* translators: 1: year, 2: site name. */
							esc_html__( 'Copyright %1$s © %2$s. All rights reserved.', 'alarede' ),
							esc_html( gmdate( 'Y' ) ),
							esc_html( get_bloginfo( 'name' ) )
						);
					}
					?>
				</p>
				<?php
				if ( has_nav_menu( 'footer_policy' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_policy',
							'container'      => false,
							'depth'          => 1,
							'menu_class'     => 'ae-footer__policy',
							'fallback_cb'    => false,
						)
					);
				} else {
					echo '<ul class="ae-footer__policy">';
					echo '<li><a href="' . esc_url( home_url( '/legal/' ) ) . '">' . esc_html__( 'Legal', 'alarede' ) . '</a></li>';
					echo '<li><a href="' . esc_url( home_url( '/cookies-policy/' ) ) . '">' . esc_html__( 'Cookies Policy', 'alarede' ) . '</a></li>';
					echo '<li><a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . esc_html__( 'Privacy Policy', 'alarede' ) . '</a></li>';
					echo '</ul>';
				}
				?>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<button class="ae-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'alarede' ); ?>">&uarr;</button>

<?php wp_footer(); ?>
</body>
</html>
