<?php
/**
 * Template Name: General Page
 * Template Post Type: page
 *
 * A flexible default page layout: a styled page header, the editor content,
 * and the blog sidebar when it has widgets. Use this for any page that does
 * not need a bespoke template.
 *
 * @package Alarede
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php bloginfo( 'name' ); ?></span>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container">
			<div class="ae-content-area <?php echo alarede_has_sidebar() ? '' : 'no-sidebar'; ?>">
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
				</article>

				<?php if ( alarede_has_sidebar() ) : ?>
					<aside class="widget-area"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php
endwhile;

get_footer();
