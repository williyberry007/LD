<?php
/**
 * Intro / About section.
 *
 * @package Alarede
 */

$image     = get_theme_mod( 'alarede_intro_image', '' );
$kicker    = get_theme_mod( 'alarede_intro_kicker', __( 'Our Story', 'alarede' ) );
$title     = get_theme_mod( 'alarede_intro_title', __( 'Crafting Unforgettable Moments Since 2005', 'alarede' ) );
$text      = get_theme_mod( 'alarede_intro_text', '' );
$signature = get_theme_mod( 'alarede_intro_signature', '' );
?>
<section class="ae-section ae-intro" id="intro">
	<div class="ae-container">
		<div class="ae-intro__grid">
			<div class="ae-intro__image ae-reveal">
				<?php if ( $image ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
				<?php else : ?>
					<img src="https://placehold.co/600x720/efe9dd/b7965a?text=Your+Image" alt="<?php esc_attr_e( 'Placeholder', 'alarede' ); ?>" loading="lazy">
				<?php endif; ?>
			</div>
			<div class="ae-intro__body ae-reveal">
				<?php if ( $kicker ) : ?><span class="ae-kicker"><?php echo esc_html( $kicker ); ?></span><?php endif; ?>
				<h2><?php echo esc_html( $title ); ?></h2>
				<?php if ( $text ) : ?><p><?php echo wp_kses_post( $text ); ?></p><?php endif; ?>
				<?php if ( $signature ) : ?><p class="ae-intro__signature"><?php echo esc_html( $signature ); ?></p><?php endif; ?>
			</div>
		</div>
	</div>
</section>
