<?php
/**
 * Template Name: Full Width (No Sidebar)
 * Template Post Type: page
 *
 * @package Alago_Events
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
		<div class="ae-container ae-content-area no-sidebar">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
	</section>

<?php endwhile; ?>

<?php
get_footer();
