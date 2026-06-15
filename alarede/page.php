<?php
/**
 * The template for displaying all single pages.
 *
 * @package Alarede
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="ae-page-header">
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php bloginfo( 'name' ); ?></span>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container">
			<div class="ae-content-area <?php echo alarede_has_sidebar() ? '' : 'no-sidebar'; ?>">
				<main id="primary" class="site-main">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="ae-page-thumb"><?php the_post_thumbnail( 'large' ); ?></div>
						<?php endif; ?>
						<div class="entry-content">
							<?php
							the_content();
							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'alarede' ),
									'after'  => '</div>',
								)
							);
							?>
						</div>
					</article>

					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</main>

				<?php if ( alarede_has_sidebar() ) : ?>
					<aside class="widget-area"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside>
				<?php endif; ?>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php
get_footer();
