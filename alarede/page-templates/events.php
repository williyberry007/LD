<?php
/**
 * Template Name: Events
 * Template Post Type: page
 *
 * Luxury events landing page with a fixed set of event services. Edit the
 * page title/intro in the editor; the service grid below is defined here and
 * can be adjusted via the $services array.
 *
 * @package Alarede
 */

get_header();

$services = alarede_get_list( 'alarede_events_item', 'events' );

while ( have_posts() ) :
	the_post();
	?>

	<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Services', 'alarede' ); ?></span>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<?php if ( trim( get_the_content() ) ) : ?>
		<section class="ae-section ae-section--tight">
			<div class="ae-container" style="text-align:center;max-width:820px;">
				<div class="entry-content"><?php the_content(); ?></div>
			</div>
		</section>
	<?php endif; ?>

	<section class="ae-section <?php echo trim( get_the_content() ) ? 'ae-section--cream' : ''; ?>">
		<div class="ae-container">
			<?php
			alarede_section_head(
				get_theme_mod( 'alarede_events_kicker', __( 'What We Offer', 'alarede' ) ),
				get_theme_mod( 'alarede_events_title', __( 'Event Services', 'alarede' ) ),
				get_theme_mod( 'alarede_events_intro', __( 'Bespoke planning and coordination for every milestone celebration.', 'alarede' ) )
			);
			?>
			<div class="ae-features">
				<?php foreach ( $services as $i => $s ) : ?>
					<div class="ae-feature ae-reveal">
						<span class="ae-feature__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3><?php echo esc_html( $s[0] ); ?></h3>
						<p><?php echo esc_html( $s[1] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="ae-section ae-section--dark ae-cta-band">
		<div class="ae-container">
			<h2><?php esc_html_e( 'Planning a celebration?', 'alarede' ); ?></h2>
			<p><?php esc_html_e( 'Tell us your date and vision — we will craft a tailored proposal.', 'alarede' ); ?></p>
			<a class="ae-btn ae-btn--light" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Enquire Now', 'alarede' ); ?></a>
		</div>
	</section>

	<?php
endwhile;

get_footer();
