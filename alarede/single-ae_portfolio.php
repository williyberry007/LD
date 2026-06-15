<?php
/**
 * Single portfolio item template.
 *
 * @package Alarede
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Portfolio', 'alarede' ); ?></span>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container ae-content-area no-sidebar">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>
			<div style="text-align:center;margin-top:3rem;">
				<a class="ae-btn" href="<?php echo esc_url( get_post_type_archive_link( 'ae_portfolio' ) ); ?>"><?php esc_html_e( '← Back to Portfolio', 'alarede' ); ?></a>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php
get_footer();
