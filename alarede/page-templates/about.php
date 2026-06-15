<?php
/**
 * Template Name: About / Founder
 * Template Post Type: page
 *
 * A founder/about layout: portrait, biography, philosophy and stats. All
 * content is editable in Appearance → Customize → Page Templates → About Page;
 * the bio falls back to the page editor content when the Customizer bio is
 * left at its default and the page has its own content.
 *
 * @package Alarede
 */

get_header();

$portrait = get_theme_mod( 'alarede_about_portrait', '' );
if ( ! $portrait ) {
	$portrait = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : 'https://placehold.co/600x760/efe9dd/b7965a?text=Portrait';
}
$kicker = get_theme_mod( 'alarede_about_kicker', __( 'Meet the Founder', 'alarede' ) );
$bio    = get_theme_mod( 'alarede_about_bio', '' );

while ( have_posts() ) :
	the_post();
	?>

	<div class="ae-page-header"<?php alarede_page_header_style( false ); ?>>
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
					<?php if ( $kicker ) : ?><span class="ae-kicker"><?php echo esc_html( $kicker ); ?></span><?php endif; ?>
					<div class="entry-content">
						<?php
						// Prefer the page editor content; otherwise use the Customizer bio.
						if ( trim( get_the_content() ) ) {
							the_content();
						} elseif ( $bio ) {
							echo wp_kses_post( wpautop( $bio ) );
						}
						?>
					</div>
					<div class="ae-stats">
						<?php for ( $n = 1; $n <= 3; $n++ ) : ?>
							<?php
							$num   = get_theme_mod( "alarede_about_stat_{$n}_num", '' );
							$label = get_theme_mod( "alarede_about_stat_{$n}_label", '' );
							if ( ! $num && ! $label ) {
								continue;
							}
							?>
							<div>
								<div class="ae-stats__num"><?php echo esc_html( $num ); ?></div>
								<div class="ae-stats__label"><?php echo esc_html( $label ); ?></div>
							</div>
						<?php endfor; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php $about_values = alarede_get_list( 'alarede_about_value', 'about_values' ); ?>
	<?php if ( $about_values ) : ?>
		<section class="ae-section ae-section--cream">
			<div class="ae-container">
				<?php
				alarede_section_head(
					get_theme_mod( 'alarede_about_phil_kicker', __( 'Our Philosophy', 'alarede' ) ),
					get_theme_mod( 'alarede_about_phil_title', __( 'How We Work', 'alarede' ) ),
					get_theme_mod( 'alarede_about_phil_intro', __( 'Three principles guide every celebration we create.', 'alarede' ) )
				);
				?>
				<div class="ae-features">
					<?php foreach ( $about_values as $i => $v ) : ?>
						<div class="ae-feature ae-reveal">
							<span class="ae-feature__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
							<h3><?php echo esc_html( $v[0] ); ?></h3>
							<p><?php echo esc_html( $v[1] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
				<div style="text-align:center;margin-top:3rem;">
					<a class="ae-btn ae-btn--solid" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Work With Us', 'alarede' ); ?></a>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
endwhile;

get_footer();
