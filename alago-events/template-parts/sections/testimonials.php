<?php
/**
 * Testimonials section — a simple auto-rotating slider built from the
 * Testimonial CPT, with sensible fallbacks.
 *
 * @package Alago_Events
 */

$bg     = get_theme_mod( 'alago_testimonials_bg', '' );
$kicker = get_theme_mod( 'alago_testimonials_kicker', __( 'Kind Words', 'alago-events' ) );
$title  = get_theme_mod( 'alago_testimonials_title', __( 'Love Notes From Our Couples', 'alago-events' ) );

$style = $bg
	? sprintf( 'background-image:linear-gradient(rgba(20,20,18,.78),rgba(20,20,18,.78)),url(%s);background-size:cover;background-position:center;', esc_url( $bg ) )
	: '';

$quotes = new WP_Query(
	array(
		'post_type'      => 'ae_testimonial',
		'posts_per_page' => 8,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

$fallback = array(
	array( __( 'They turned our wildest dreams into a flawless reality. Every detail was perfect, and we were able to simply enjoy our day.', 'alago-events' ), __( 'Sofia & James', 'alago-events' ), __( 'Coastal Villa', 'alago-events' ) ),
	array( __( 'Calm, creative and utterly professional. We could not have imagined a more beautiful celebration.', 'alago-events' ), __( 'Amara & Daniel', 'alago-events' ), __( 'Mountain Estate', 'alago-events' ) ),
	array( __( 'From the first call to the last dance, we felt completely cared for. Truly the best decision we made.', 'alago-events' ), __( 'Elena & Marco', 'alago-events' ), __( 'Seaside Garden', 'alago-events' ) ),
);
?>
<section class="ae-section ae-section--dark ae-testimonials" id="testimonials" style="<?php echo esc_attr( $style ); ?>">
	<div class="ae-container">
		<?php alago_events_section_head( $kicker, $title ); ?>

		<div class="ae-testimonials__track" data-autoplay="6000">
			<?php
			$index = 0;
			if ( $quotes->have_posts() ) :
				while ( $quotes->have_posts() ) :
					$quotes->the_post();
					$role     = get_post_meta( get_the_ID(), '_ae_testimonial_role', true );
					$location = get_post_meta( get_the_ID(), '_ae_testimonial_location', true );
					?>
					<div class="ae-testimonial<?php echo 0 === $index ? ' is-active' : ''; ?>">
						<blockquote><?php echo wp_kses_post( wpautop( get_the_content() ) ); ?></blockquote>
						<cite><?php echo esc_html( $role ? $role : get_the_title() ); ?><?php echo $location ? ' &middot; ' . esc_html( $location ) : ''; ?></cite>
					</div>
					<?php
					$index++;
				endwhile;
				wp_reset_postdata();
			else :
				foreach ( $fallback as $t ) :
					?>
					<div class="ae-testimonial<?php echo 0 === $index ? ' is-active' : ''; ?>">
						<blockquote>&ldquo;<?php echo esc_html( $t[0] ); ?>&rdquo;</blockquote>
						<cite><?php echo esc_html( $t[1] ); ?> &middot; <?php echo esc_html( $t[2] ); ?></cite>
					</div>
					<?php
					$index++;
				endforeach;
			endif;
			?>
			<?php if ( $index > 1 ) : ?>
				<div class="ae-testimonials__dots">
					<?php for ( $d = 0; $d < $index; $d++ ) : ?>
						<button class="<?php echo 0 === $d ? 'is-active' : ''; ?>" data-slide="<?php echo (int) $d; ?>" aria-label="<?php printf( esc_attr__( 'Testimonial %d', 'alago-events' ), $d + 1 ); ?>"></button>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
