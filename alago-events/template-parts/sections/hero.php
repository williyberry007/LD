<?php
/**
 * Hero section.
 *
 * @package Alago_Events
 */

$hero_image    = get_theme_mod( 'alago_hero_image', '' );
$hero_kicker   = get_theme_mod( 'alago_hero_kicker', __( 'Luxury Wedding & Event Planning', 'alago-events' ) );
$hero_title    = get_theme_mod( 'alago_hero_title', __( 'Timeless Celebrations, Effortlessly Designed', 'alago-events' ) );
$hero_subtitle = get_theme_mod( 'alago_hero_subtitle', __( 'Bespoke destination weddings crafted with artistry, precision and heart.', 'alago-events' ) );
$btn1_text     = get_theme_mod( 'alago_hero_btn1_text', __( 'Start Planning', 'alago-events' ) );
$btn1_url      = get_theme_mod( 'alago_hero_btn1_url', '#contact' );
$btn2_text     = get_theme_mod( 'alago_hero_btn2_text', __( 'View Portfolio', 'alago-events' ) );
$btn2_url      = get_theme_mod( 'alago_hero_btn2_url', '#portfolio' );

$style = $hero_image
	? sprintf( 'background-image:url(%s);', esc_url( $hero_image ) )
	: 'background:linear-gradient(135deg,#2b2a26,#4a463d);';
?>
<section class="ae-hero" id="home">
	<div class="ae-hero__bg" style="<?php echo esc_attr( $style ); ?>"></div>
	<div class="ae-hero__overlay"></div>
	<div class="ae-hero__content">
		<?php if ( $hero_kicker ) : ?>
			<span class="ae-hero__kicker"><?php echo esc_html( $hero_kicker ); ?></span>
		<?php endif; ?>
		<h1 class="ae-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
		<?php if ( $hero_subtitle ) : ?>
			<p class="ae-hero__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
		<?php endif; ?>
		<div class="ae-hero__actions">
			<?php if ( $btn1_text ) : ?>
				<a class="ae-btn ae-btn--solid" href="<?php echo esc_url( $btn1_url ); ?>"><?php echo esc_html( $btn1_text ); ?></a>
			<?php endif; ?>
			<?php if ( $btn2_text ) : ?>
				<a class="ae-btn ae-btn--light" href="<?php echo esc_url( $btn2_url ); ?>"><?php echo esc_html( $btn2_text ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<a class="ae-hero__scroll" href="#intro"><?php esc_html_e( 'Scroll', 'alago-events' ); ?></a>
</section>
