<?php
/**
 * Pricing section. Three Customizer-editable packages.
 *
 * @package Alarede
 */

$kicker = get_theme_mod( 'alarede_pricing_kicker', __( 'Investment', 'alarede' ) );
$title  = get_theme_mod( 'alarede_pricing_title', __( 'Planning Packages', 'alarede' ) );
$intro  = get_theme_mod( 'alarede_pricing_intro', '' );
?>
<section class="ae-section ae-section--cream ae-pricing" id="pricing">
	<div class="ae-container">
		<?php alarede_section_head( $kicker, $title, $intro ); ?>

		<div class="ae-pricing__grid">
			<?php
			$price_defaults = alarede_template_defaults( 'prices' );
			foreach ( $price_defaults as $i => $price_d ) :
				$n          = $i + 1;
				$p_title    = get_theme_mod( "alarede_price_{$n}_title", $price_d[0] );
				$p_amount   = get_theme_mod( "alarede_price_{$n}_amount", $price_d[1] );
				$p_unit     = get_theme_mod( "alarede_price_{$n}_unit", $price_d[2] );
				$p_features = get_theme_mod( "alarede_price_{$n}_features", $price_d[3] );
				$p_badge    = get_theme_mod( "alarede_price_{$n}_badge", $price_d[4] );
				if ( ! $p_title ) {
					continue;
				}
				$features = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $p_features ) ) );
				$classes  = 'ae-price ae-reveal' . ( $p_badge ? ' ae-price--featured' : '' );
				?>
				<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $p_badge ? ' data-badge="' . esc_attr( $p_badge ) . '"' : ''; ?>>
					<h3><?php echo esc_html( $p_title ); ?></h3>
					<div class="ae-price__amount"><?php echo esc_html( $p_amount ); ?> <small><?php echo esc_html( $p_unit ); ?></small></div>
					<?php if ( $features ) : ?>
						<ul>
							<?php foreach ( $features as $feature ) : ?>
								<li><?php echo esc_html( $feature ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<a class="ae-btn <?php echo $p_badge ? 'ae-btn--solid' : ''; ?>" href="#contact"><?php esc_html_e( 'Enquire', 'alarede' ); ?></a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
