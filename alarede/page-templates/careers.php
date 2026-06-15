<?php
/**
 * Template Name: Careers
 * Template Post Type: page
 *
 * Careers page with a values grid and an open-positions list. Edit the page
 * intro in the editor. To list real openings, replace the $jobs array below
 * or add them to the page content.
 *
 * @package Alarede
 */

get_header();

$values = alarede_get_list( 'alarede_careers_value', 'values' );
$jobs   = alarede_get_list( 'alarede_careers_job', 'jobs' );

while ( have_posts() ) :
	the_post();
	?>

	<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Join Us', 'alarede' ); ?></span>
			<h1><?php echo esc_html( get_the_title() ? get_the_title() : __( 'Careers', 'alarede' ) ); ?></h1>
		</div>
	</div>

	<section class="ae-section ae-section--tight">
		<div class="ae-container" style="text-align:center;max-width:820px;">
			<div class="entry-content">
				<?php
				if ( trim( get_the_content() ) ) {
					the_content();
				} else {
					echo '<p class="ae-lead" style="margin-inline:auto;">' . esc_html__( 'We are always looking for warm, detail-loving people who care about creating beautiful experiences.', 'alarede' ) . '</p>';
				}
				?>
			</div>
		</div>
	</section>

	<section class="ae-section ae-section--cream">
		<div class="ae-container">
			<?php
			alarede_section_head(
				get_theme_mod( 'alarede_careers_values_kicker', __( 'Life Here', 'alarede' ) ),
				get_theme_mod( 'alarede_careers_values_title', __( 'What We Value', 'alarede' ) )
			);
			?>
			<div class="ae-features">
				<?php foreach ( $values as $i => $v ) : ?>
					<div class="ae-feature ae-reveal">
						<span class="ae-feature__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3><?php echo esc_html( $v[0] ); ?></h3>
						<p><?php echo esc_html( $v[1] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="ae-section">
		<div class="ae-container">
			<?php
			alarede_section_head(
				get_theme_mod( 'alarede_careers_jobs_kicker', __( 'Open Roles', 'alarede' ) ),
				get_theme_mod( 'alarede_careers_jobs_title', __( 'Current Openings', 'alarede' ) )
			);
			?>
			<?php
			$job_query = new WP_Query(
				array(
					'post_type'      => 'ae_job',
					'posts_per_page' => 20,
					'orderby'        => 'menu_order date',
					'order'          => 'ASC',
				)
			);
			?>
			<div class="ae-jobs">
				<?php if ( $job_query->have_posts() ) : ?>
					<?php
					while ( $job_query->have_posts() ) :
						$job_query->the_post();
						$job_meta  = get_post_meta( get_the_ID(), '_ae_job_meta', true );
						$apply_url = alarede_job_apply_url( get_the_ID() );
						?>
						<div class="ae-job ae-reveal">
							<div class="ae-job__head">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php if ( $job_meta ) : ?><span class="ae-job__meta"><?php echo esc_html( $job_meta ); ?></span><?php endif; ?>
								<?php if ( get_the_excerpt() ) : ?><p class="ae-job__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p><?php endif; ?>
							</div>
							<div class="ae-job__actions">
								<a class="ae-btn" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Full Details', 'alarede' ); ?></a>
								<a class="ae-btn ae-btn--solid" href="<?php echo esc_url( $apply_url ); ?>"><?php esc_html_e( 'Apply', 'alarede' ); ?></a>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				<?php else : ?>
					<?php foreach ( $jobs as $job ) : ?>
						<div class="ae-job ae-reveal">
							<div class="ae-job__head">
								<h3><?php echo esc_html( $job[0] ); ?></h3>
								<span class="ae-job__meta"><?php echo esc_html( $job[1] ); ?></span>
							</div>
							<div class="ae-job__actions">
								<a class="ae-btn ae-btn--solid" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Apply', 'alarede' ); ?></a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<p style="text-align:center;color:var(--ae-color-muted);margin-top:2rem;">
				<?php esc_html_e( 'Don’t see your role? Send us your portfolio anyway — we’d love to hear from you.', 'alarede' ); ?>
			</p>
		</div>
	</section>

	<?php
endwhile;

get_footer();
