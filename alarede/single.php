<?php
/**
 * The template for displaying all single posts.
 *
 * @package Alarede
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php echo esc_html( get_post_type() === 'post' ? __( 'Journal', 'alarede' ) : get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
			<h1><?php the_title(); ?></h1>
			<?php if ( 'post' === get_post_type() ) : ?>
				<p style="color:rgba(255,255,255,.8);"><?php echo esc_html( get_the_date() ); ?> &middot; <?php echo esc_html( get_the_author() ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container">
			<div class="ae-content-area <?php echo alarede_has_sidebar() ? '' : 'no-sidebar'; ?>">
				<main id="primary" class="site-main">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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
						<footer class="entry-footer"><?php alarede_entry_footer(); ?></footer>
					</article>

					<?php
					the_post_navigation(
						array(
							'prev_text' => '<span class="ae-post-meta">' . esc_html__( 'Previous', 'alarede' ) . '</span> %title',
							'next_text' => '<span class="ae-post-meta">' . esc_html__( 'Next', 'alarede' ) . '</span> %title',
						)
					);

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
