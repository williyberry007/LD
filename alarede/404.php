<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Alarede
 */

get_header();
?>

<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
	<div class="ae-container">
		<span class="ae-breadcrumb"><?php esc_html_e( 'Error 404', 'alarede' ); ?></span>
		<h1><?php esc_html_e( 'This page has wandered off.', 'alarede' ); ?></h1>
	</div>
</div>

<section class="ae-section">
	<div class="ae-container" style="text-align:center;max-width:640px;">
		<p><?php esc_html_e( 'The page you are looking for could not be found. Perhaps a search will help.', 'alarede' ); ?></p>
		<?php get_search_form(); ?>
		<p style="margin-top:2rem;"><a class="ae-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return Home', 'alarede' ); ?></a></p>
	</div>
</section>

<?php
get_footer();
