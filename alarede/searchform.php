<?php
/**
 * Custom search form.
 *
 * @package Alarede
 */
?>
<form role="search" method="get" class="ae-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="ae-search-field"><?php esc_html_e( 'Search for:', 'alarede' ); ?></label>
	<input type="search" id="ae-search-field" class="search-field" placeholder="<?php esc_attr_e( 'Search…', 'alarede' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="ae-btn ae-btn--solid"><?php esc_html_e( 'Search', 'alarede' ); ?></button>
</form>
