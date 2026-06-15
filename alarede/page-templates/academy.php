<?php
/**
 * Template Name: Academy
 * Template Post Type: page
 *
 * Training academy page. Edit the page title/intro in the editor; the course
 * grid below is defined here and can be adjusted via the $courses array.
 *
 * @package Alarede
 */

get_header();

$courses = alarede_get_list( 'alarede_academy_item', 'academy' );

while ( have_posts() ) :
	the_post();
	?>

	<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Learn With Us', 'alarede' ); ?></span>
			<h1><?php echo esc_html( get_the_title() ? get_the_title() : __( 'Alago Academy', 'alarede' ) ); ?></h1>
		</div>
	</div>

	<section class="ae-section ae-section--tight">
		<div class="ae-container" style="text-align:center;max-width:820px;">
			<div class="entry-content">
				<?php
				if ( trim( get_the_content() ) ) {
					the_content();
				} else {
					echo '<p class="ae-lead" style="margin-inline:auto;">' . esc_html__( 'Learn the crafts behind unforgettable celebrations from experienced professionals.', 'alarede' ) . '</p>';
				}
				?>
			</div>
		</div>
	</section>

	<section class="ae-section ae-section--cream">
		<div class="ae-container">
			<?php
			alarede_section_head(
				get_theme_mod( 'alarede_academy_kicker', __( 'Courses', 'alarede' ) ),
				get_theme_mod( 'alarede_academy_title', __( 'What You Can Learn', 'alarede' ) ),
				get_theme_mod( 'alarede_academy_intro', __( 'Practical, hands-on training across our full range of specialities.', 'alarede' ) )
			);
			?>
			<div class="ae-features">
				<?php foreach ( $courses as $i => $c ) : ?>
					<div class="ae-feature ae-reveal">
						<span class="ae-feature__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3><?php echo esc_html( $c[0] ); ?></h3>
						<p><?php echo esc_html( $c[1] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="ae-section ae-section--dark ae-cta-band">
		<div class="ae-container">
			<h2><?php esc_html_e( 'Ready to enrol?', 'alarede' ); ?></h2>
			<p><?php esc_html_e( 'Request the course schedule and pricing for the next intake.', 'alarede' ); ?></p>
			<a class="ae-btn ae-btn--light" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Request Details', 'alarede' ); ?></a>
		</div>
	</section>

	<?php
endwhile;

get_footer();
