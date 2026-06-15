<?php
/**
 * The template for displaying comments.
 *
 * @package Alarede
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comment_count = get_comments_number();
			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s Comment', '%s Comments', $comment_count, 'alarede' ) ),
				esc_html( number_format_i18n( $comment_count ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 56,
				)
			);
			?>
		</ol>

		<?php the_comments_pagination(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Leave a Comment', 'alarede' ),
			'class_submit'       => 'ae-btn ae-btn--solid',
		)
	);
	?>
</div>
