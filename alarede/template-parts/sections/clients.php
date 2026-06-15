<?php
/**
 * Clients carousel — an auto-scrolling marquee of client logos. Pulls from the
 * Clients post type (logo = featured image), with placeholders as a fallback.
 *
 * @package Alarede
 */

$kicker = get_theme_mod( 'alarede_clients_kicker', __( 'Trusted By', 'alarede' ) );
$title  = get_theme_mod( 'alarede_clients_title', __( 'Our Clients & Partners', 'alarede' ) );

$clients = new WP_Query(
	array(
		'post_type'      => 'ae_client',
		'posts_per_page' => 20,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	)
);

// Build the list of [ name, logo_url ] once, then render it twice for a seamless loop.
$items = array();
if ( $clients->have_posts() ) {
	while ( $clients->have_posts() ) {
		$clients->the_post();
		$items[] = array(
			'name' => get_the_title(),
			'logo' => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'medium' ) : '',
		);
	}
	wp_reset_postdata();
} else {
	for ( $i = 1; $i <= 6; $i++ ) {
		$items[] = array(
			/* translators: %d: placeholder client number. */
			'name' => sprintf( __( 'Client %d', 'alarede' ), $i ),
			'logo' => '',
		);
	}
}

if ( empty( $items ) ) {
	return;
}
?>
<section class="ae-section ae-section--cream ae-clients" id="clients">
	<div class="ae-container">
		<?php alarede_section_head( $kicker, $title ); ?>
	</div>
	<div class="ae-clients__viewport">
		<div class="ae-clients__track">
			<?php
			// Render the set twice so the marquee can loop without a visible gap.
			for ( $pass = 0; $pass < 2; $pass++ ) :
				foreach ( $items as $item ) :
					?>
					<div class="ae-clients__item" <?php echo 1 === $pass ? 'aria-hidden="true"' : ''; ?>>
						<?php if ( $item['logo'] ) : ?>
							<img src="<?php echo esc_url( $item['logo'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" loading="lazy">
						<?php else : ?>
							<span class="ae-clients__name"><?php echo esc_html( $item['name'] ); ?></span>
						<?php endif; ?>
					</div>
					<?php
				endforeach;
			endfor;
			?>
		</div>
	</div>
</section>
