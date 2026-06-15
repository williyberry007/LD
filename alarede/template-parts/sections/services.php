<?php
/**
 * Services section. Pulls from the Service CPT, falling back to three
 * Customizer-editable cards when no Service posts exist.
 *
 * @package Alarede
 */

$kicker = get_theme_mod( 'alarede_services_kicker', __( 'What We Do', 'alarede' ) );
$title  = get_theme_mod( 'alarede_services_title', __( 'Our Services', 'alarede' ) );
$intro  = get_theme_mod( 'alarede_services_intro', '' );

$services = new WP_Query(
	array(
		'post_type'      => 'ae_service',
		'posts_per_page' => 6,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);
?>
<section class="ae-section ae-section--cream ae-services" id="services">
	<div class="ae-container">
		<?php alarede_section_head( $kicker, $title, $intro ); ?>

		<div class="ae-services__grid">
			<?php if ( $services->have_posts() ) : ?>
				<?php
				while ( $services->have_posts() ) :
					$services->the_post();
					?>
					<article class="ae-service ae-reveal">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="ae-service__icon"><?php the_post_thumbnail( 'thumbnail' ); ?></div>
						<?php else : ?>
							<div class="ae-service__icon">&#10022;</div>
						<?php endif; ?>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			<?php else : ?>
				<?php
				$svc_defaults = alarede_template_defaults( 'services' );
				foreach ( $svc_defaults as $i => $svc_d ) :
					$n       = $i + 1;
					$s_icon  = get_theme_mod( "alarede_service_{$n}_icon", $svc_d[0] );
					$s_title = get_theme_mod( "alarede_service_{$n}_title", $svc_d[1] );
					$s_text  = get_theme_mod( "alarede_service_{$n}_text", $svc_d[2] );
					if ( ! $s_title ) {
						continue;
					}
					?>
					<article class="ae-service ae-reveal">
						<div class="ae-service__icon"><?php echo esc_html( $s_icon ); ?></div>
						<h3><?php echo esc_html( $s_title ); ?></h3>
						<p><?php echo esc_html( $s_text ); ?></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
