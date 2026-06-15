<?php
/**
 * Portfolio / gallery section. Pulls from the Portfolio CPT.
 *
 * @package Alarede
 */

$kicker = get_theme_mod( 'alarede_portfolio_kicker', __( 'Real Celebrations', 'alarede' ) );
$title  = get_theme_mod( 'alarede_portfolio_title', __( 'Selected Work', 'alarede' ) );
$intro  = get_theme_mod( 'alarede_portfolio_intro', '' );
$count  = (int) get_theme_mod( 'alarede_portfolio_count', 6 );

$items = new WP_Query(
	array(
		'post_type'      => 'ae_portfolio',
		'posts_per_page' => $count,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);
?>
<section class="ae-section ae-portfolio" id="portfolio">
	<div class="ae-container">
		<?php alarede_section_head( $kicker, $title, $intro ); ?>

		<div class="ae-portfolio__grid">
			<?php if ( $items->have_posts() ) : ?>
				<?php
				while ( $items->have_posts() ) :
					$items->the_post();
					$terms     = get_the_terms( get_the_ID(), 'ae_portfolio_category' );
					$term_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
					?>
					<a class="ae-portfolio__item ae-reveal" href="<?php the_permalink(); ?>">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'alarede-portrait', array( 'loading' => 'lazy' ) );
						} else {
							echo '<img src="https://placehold.co/600x800/efe9dd/b7965a?text=Portfolio" alt="" loading="lazy">';
						}
						?>
						<div class="ae-portfolio__caption">
							<?php if ( $term_name ) : ?><span><?php echo esc_html( $term_name ); ?></span><?php endif; ?>
							<h3><?php the_title(); ?></h3>
						</div>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			<?php else : ?>
				<?php for ( $i = 1; $i <= max( 3, $count ); $i++ ) : ?>
					<a class="ae-portfolio__item ae-reveal" href="#">
						<img src="https://placehold.co/600x800/<?php echo ( $i % 2 ) ? 'efe9dd/b7965a' : 'e6ded0/9a7c45'; ?>?text=Wedding+<?php echo (int) $i; ?>" alt="" loading="lazy">
						<div class="ae-portfolio__caption">
							<span><?php esc_html_e( 'Destination Wedding', 'alarede' ); ?></span>
							<h3><?php printf( esc_html__( 'Celebration %d', 'alarede' ), (int) $i ); ?></h3>
						</div>
					</a>
				<?php endfor; ?>
			<?php endif; ?>
		</div>

		<?php if ( $items->have_posts() || post_type_exists( 'ae_portfolio' ) ) : ?>
			<div style="text-align:center;margin-top:3rem;">
				<a class="ae-btn" href="<?php echo esc_url( get_post_type_archive_link( 'ae_portfolio' ) ?: '#' ); ?>"><?php esc_html_e( 'View Full Portfolio', 'alarede' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
