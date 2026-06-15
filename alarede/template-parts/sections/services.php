<?php
/**
 * Services section — image cards that link to each service page.
 * Pulls from the Service CPT (featured image + excerpt), falling back to
 * placeholder cards built from the Customizer service defaults.
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
<section class="ae-section ae-services" id="services">
	<div class="ae-container">
		<?php alarede_section_head( $kicker, $title, $intro ); ?>

		<div class="ae-services__grid">
			<?php if ( $services->have_posts() ) : ?>
				<?php
				while ( $services->have_posts() ) :
					$services->the_post();
					$img = has_post_thumbnail()
						? get_the_post_thumbnail_url( null, 'large' )
						: 'https://placehold.co/700x560/2b2a26/b7965a?text=' . rawurlencode( wp_strip_all_tags( get_the_title() ) );
					?>
					<a class="ae-service-card ae-reveal" href="<?php the_permalink(); ?>">
						<img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
						<span class="ae-service-card__overlay">
							<span class="ae-service-card__title"><?php the_title(); ?></span>
							<?php if ( has_excerpt() || get_the_excerpt() ) : ?>
								<span class="ae-service-card__desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 12 ) ); ?></span>
							<?php endif; ?>
						</span>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			<?php else : ?>
				<?php
				$svc_defaults = alarede_template_defaults( 'services' );
				foreach ( $svc_defaults as $i => $svc_d ) :
					$n       = $i + 1;
					$s_title = get_theme_mod( "alarede_service_{$n}_title", $svc_d[1] );
					$s_text  = get_theme_mod( "alarede_service_{$n}_text", $svc_d[2] );
					if ( ! $s_title ) {
						continue;
					}
					$ph = 'https://placehold.co/700x560/' . ( ( $n % 2 ) ? '2b2a26/b7965a' : '3a352b/efe9dd' ) . '?text=' . rawurlencode( $s_title );
					?>
					<a class="ae-service-card ae-reveal" href="<?php echo esc_url( get_post_type_archive_link( 'ae_service' ) ?: '#services' ); ?>">
						<img src="<?php echo esc_url( $ph ); ?>" alt="<?php echo esc_attr( $s_title ); ?>" loading="lazy">
						<span class="ae-service-card__overlay">
							<span class="ae-service-card__title"><?php echo esc_html( $s_title ); ?></span>
							<span class="ae-service-card__desc"><?php echo esc_html( $s_text ); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<?php if ( post_type_exists( 'ae_service' ) && get_post_type_archive_link( 'ae_service' ) ) : ?>
			<div style="text-align:center;margin-top:3rem;">
				<a class="ae-btn" href="<?php echo esc_url( get_post_type_archive_link( 'ae_service' ) ); ?>"><?php esc_html_e( 'View All Services', 'alarede' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
