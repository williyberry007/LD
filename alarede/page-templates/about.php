<?php
/**
 * Template Name: About / Founder
 * Template Post Type: page
 *
 * A founder/about layout: portrait, biography, philosophy and stats.
 * Edit the page title/content in the editor; the surrounding design is here.
 * Replace placeholder copy and images with your own.
 *
 * @package Alarede
 */

get_header();

while ( have_posts() ) :
	the_post();
	$portrait = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : 'https://placehold.co/600x760/efe9dd/b7965a?text=Portrait';
	?>

	<div class="ae-page-header">
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'About', 'alarede' ); ?></span>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container">
			<div class="ae-founder__grid">
				<div class="ae-founder__image ae-reveal">
					<img src="<?php echo esc_url( $portrait ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
				</div>
				<div class="ae-founder__body ae-reveal">
					<span class="ae-kicker"><?php esc_html_e( 'Meet the Founder', 'alarede' ); ?></span>
					<div class="entry-content">
						<?php
						if ( trim( get_the_content() ) ) {
							the_content();
						} else {
							echo '<p class="ae-lead">' . esc_html__( 'A storyteller at heart, our founder has spent two decades turning couples’ visions into flawless celebrations.', 'alarede' ) . '</p>';
							echo '<p>' . esc_html__( 'What began as a passion for bringing people together has grown into a boutique studio trusted to plan some of the most memorable weddings and events. Every celebration is approached with the same care: listen deeply, design thoughtfully, and execute with precision.', 'alarede' ) . '</p>';
							echo '<p>' . esc_html__( 'Edit this page in the WordPress editor to tell your own story, and set a Featured Image for the portrait.', 'alarede' ) . '</p>';
						}
						?>
					</div>
					<div class="ae-stats">
						<div><div class="ae-stats__num">20+</div><div class="ae-stats__label"><?php esc_html_e( 'Years Experience', 'alarede' ); ?></div></div>
						<div><div class="ae-stats__num">500+</div><div class="ae-stats__label"><?php esc_html_e( 'Events Created', 'alarede' ); ?></div></div>
						<div><div class="ae-stats__num">100%</div><div class="ae-stats__label"><?php esc_html_e( 'Bespoke', 'alarede' ); ?></div></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ae-section ae-section--cream">
		<div class="ae-container">
			<?php alarede_section_head( __( 'Our Philosophy', 'alarede' ), __( 'How We Work', 'alarede' ), __( 'Three principles guide every celebration we create.', 'alarede' ) ); ?>
			<div class="ae-features">
				<?php
				$values = array(
					array( __( 'Listen', 'alarede' ), __( 'We begin with your story, your people and your vision — never a template.', 'alarede' ) ),
					array( __( 'Design', 'alarede' ), __( 'We translate that vision into a cohesive, beautiful and personal design.', 'alarede' ) ),
					array( __( 'Deliver', 'alarede' ), __( 'On the day, we manage every detail so you can simply be present.', 'alarede' ) ),
				);
				foreach ( $values as $i => $v ) :
					?>
					<div class="ae-feature ae-reveal">
						<span class="ae-feature__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3><?php echo esc_html( $v[0] ); ?></h3>
						<p><?php echo esc_html( $v[1] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
			<div style="text-align:center;margin-top:3rem;">
				<a class="ae-btn ae-btn--solid" href="#contact"><?php esc_html_e( 'Work With Us', 'alarede' ); ?></a>
			</div>
		</div>
	</section>

	<?php
endwhile;

get_footer();
