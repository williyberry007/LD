<?php
/**
 * Single portfolio item template.
 *
 * @package Alago_Events
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="ae-page-header" <?php echo has_post_thumbnail() ? 'style="background-image:linear-gradient(rgba(20,20,18,.55),rgba(20,20,18,.55)),url(' . esc_url( get_the_post_thumbnail_url( null, 'full' ) ) . ');background-size:cover;background-position:center;"' : ''; ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Portfolio', 'alago-events' ); ?></span>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container ae-content-area no-sidebar">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>
			<div style="text-align:center;margin-top:3rem;">
				<a class="ae-btn" href="<?php echo esc_url( get_post_type_archive_link( 'ae_portfolio' ) ); ?>"><?php esc_html_e( '← Back to Portfolio', 'alago-events' ); ?></a>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php
get_footer();
