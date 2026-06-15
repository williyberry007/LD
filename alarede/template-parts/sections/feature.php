<?php
/**
 * Feature band — large image with an overlapping white card (heading, accent
 * divider, body and a button). All fields editable in the Customizer.
 *
 * @package Alarede
 */

$image    = get_theme_mod( 'alarede_feature_image', '' );
$kicker   = get_theme_mod( 'alarede_feature_kicker', '' );
$title    = get_theme_mod( 'alarede_feature_title', __( 'Luxury Wedding Planner in Mallorca', 'alarede' ) );
$text     = get_theme_mod( 'alarede_feature_text', __( "We don't just plan weddings; we curate unparalleled experiences. Imagine your dream day unfolding against breathtaking backdrops, meticulously designed and flawlessly executed.\n\nFrom exclusive venues to bespoke culinary journeys, we transform your vision into an unforgettable celebration — so you can simply savour every precious moment.", 'alarede' ) );
$btn_text = get_theme_mod( 'alarede_feature_btn_text', __( 'Book a Consultation', 'alarede' ) );
$btn_url  = get_theme_mod( 'alarede_feature_btn_url', '#contact' );

$src = $image ? $image : 'https://placehold.co/900x900/efe9dd/b7965a?text=Your+Image';
?>
<section class="ae-section ae-feature-band" id="feature">
	<div class="ae-container">
		<div class="ae-feature-band__grid">
			<div class="ae-feature-band__image ae-reveal">
				<img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
			</div>
			<div class="ae-feature-band__card ae-reveal">
				<?php if ( $kicker ) : ?><span class="ae-kicker"><?php echo esc_html( $kicker ); ?></span><?php endif; ?>
				<h2><?php echo esc_html( $title ); ?></h2>
				<span class="ae-feature-band__divider" aria-hidden="true"></span>
				<div class="ae-feature-band__text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
				<?php if ( $btn_text ) : ?>
					<a class="ae-btn ae-btn--solid" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $btn_text ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
