<?php
/**
 * The template for displaying archive pages (categories, CPT archives, etc.).
 *
 * @package Alago_Events
 */

get_header();

$is_portfolio = is_post_type_archive( 'ae_portfolio' ) || is_tax( 'ae_portfolio_category' );
?>

<div class="ae-page-header">
	<div class="ae-container">
		<span class="ae-breadcrumb"><?php bloginfo( 'name' ); ?></span>
		<?php the_archive_title( '<h1>', '</h1>' ); ?>
		<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
	</div>
</div>

<section class="ae-section">
	<div class="ae-container">
		<?php if ( have_posts() ) : ?>
			<?php if ( $is_portfolio ) : ?>
				<div class="ae-portfolio__grid">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<a class="ae-portfolio__item ae-reveal" href="<?php the_permalink(); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'alago-portrait', array( 'loading' => 'lazy' ) );
							} else {
								echo '<img src="https://placehold.co/600x800/efe9dd/b7965a?text=Portfolio" alt="" loading="lazy">';
							}
							?>
							<div class="ae-portfolio__caption"><h3><?php the_title(); ?></h3></div>
						</a>
						<?php
					endwhile;
					?>
				</div>
			<?php else : ?>
				<div class="ae-content-area <?php echo alago_events_has_sidebar() ? '' : 'no-sidebar'; ?>">
					<main id="primary" class="site-main">
						<div class="ae-post-grid">
							<?php
							while ( have_posts() ) :
								the_post();
								get_template_part( 'template-parts/content', 'card' );
							endwhile;
							?>
						</div>
						<?php alago_events_pagination(); ?>
					</main>
					<?php if ( alago_events_has_sidebar() ) : ?>
						<aside class="widget-area"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $is_portfolio ) { alago_events_pagination(); } ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
