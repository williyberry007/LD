<?php
/**
 * The main template file (blog index / fallback).
 *
 * @package Alarede
 */

get_header();
?>

<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
	<div class="ae-container">
		<?php if ( is_home() && ! is_front_page() ) : ?>
			<span class="ae-breadcrumb"><?php esc_html_e( 'Journal', 'alarede' ); ?></span>
			<h1><?php echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ?: __( 'Latest Stories', 'alarede' ) ); ?></h1>
		<?php else : ?>
			<span class="ae-breadcrumb"><?php esc_html_e( 'Journal', 'alarede' ); ?></span>
			<h1><?php esc_html_e( 'Our Journal', 'alarede' ); ?></h1>
		<?php endif; ?>
	</div>
</div>

<section class="ae-section">
	<div class="ae-container">
		<div class="ae-content-area <?php echo alarede_has_sidebar() ? '' : 'no-sidebar'; ?>">
			<main id="primary" class="site-main">
				<?php if ( have_posts() ) : ?>
					<div class="ae-post-grid">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'card' );
						endwhile;
						?>
					</div>
					<?php alarede_pagination(); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</main>

			<?php if ( alarede_has_sidebar() ) : ?>
				<aside class="widget-area"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
