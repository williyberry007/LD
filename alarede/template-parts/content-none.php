<?php
/**
 * Template part when no content is found.
 *
 * @package Alarede
 */
?>
<section class="no-results not-found">
	<h2><?php esc_html_e( 'Nothing Found', 'alarede' ); ?></h2>
	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'Sorry, nothing matched your search. Please try again with different keywords.', 'alarede' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'It seems we can’t find what you’re looking for.', 'alarede' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</section>
