<?php
/**
 * Template Name: Media Gallery
 * Template Post Type: page
 *
 * A filterable media gallery. Pulls images from Portfolio items (and their
 * categories). If none exist, it shows placeholders. You can also add a
 * native [gallery] in the page content, which renders above the grid.
 *
 * @package Alarede
 */

get_header();

$portfolio = new WP_Query(
	array(
		'post_type'      => 'ae_portfolio',
		'posts_per_page' => 30,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

$cats = get_terms( array( 'taxonomy' => 'ae_portfolio_category', 'hide_empty' => true ) );

while ( have_posts() ) :
	the_post();
	?>

	<div class="ae-page-header">
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Gallery', 'alarede' ); ?></span>
			<h1><?php echo esc_html( get_the_title() ? get_the_title() : __( 'Media Gallery', 'alarede' ) ); ?></h1>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container">
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="entry-content" style="text-align:center;max-width:760px;margin:0 auto 3rem;"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
				<div class="ae-gallery__filters">
					<button class="ae-gallery__filter is-active" data-filter="*"><?php esc_html_e( 'All', 'alarede' ); ?></button>
					<?php foreach ( $cats as $cat ) : ?>
						<button class="ae-gallery__filter" data-filter="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="ae-gallery__grid">
				<?php if ( $portfolio->have_posts() ) : ?>
					<?php
					while ( $portfolio->have_posts() ) :
						$portfolio->the_post();
						$terms = get_the_terms( get_the_ID(), 'ae_portfolio_category' );
						$slug  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
						$img   = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : 'https://placehold.co/800x600/efe9dd/b7965a?text=Gallery';
						?>
						<a class="ae-gallery__item ae-reveal" data-cat="<?php echo esc_attr( $slug ); ?>" href="<?php echo esc_url( $img ); ?>">
							<img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
						</a>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				<?php else : ?>
					<?php for ( $i = 1; $i <= 9; $i++ ) : ?>
						<div class="ae-gallery__item ae-reveal" data-cat="*">
							<img src="https://placehold.co/800x600/<?php echo ( $i % 2 ) ? 'efe9dd/b7965a' : 'e6ded0/9a7c45'; ?>?text=Photo+<?php echo (int) $i; ?>" alt="" loading="lazy">
						</div>
					<?php endfor; ?>
				<?php endif; ?>
			</div>

			<?php if ( ! $portfolio->have_posts() ) : ?>
				<p style="text-align:center;color:var(--ae-color-muted);margin-top:2rem;">
					<?php esc_html_e( 'Add items under “Portfolio” (with categories) to populate this gallery.', 'alarede' ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<?php
endwhile;

get_footer();
