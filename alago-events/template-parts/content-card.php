<?php
/**
 * Blog post card.
 *
 * @package Alago_Events
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'ae-post-card ae-reveal' ); ?>>
	<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
		<?php alago_events_post_thumbnail(); ?>
	</a>
	<div class="ae-post-card__body">
		<?php alago_events_posted_on(); ?>
		<h2 class="ae-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
		<a class="ae-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More →', 'alago-events' ); ?></a>
	</div>
</article>
