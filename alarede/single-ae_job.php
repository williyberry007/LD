<?php
/**
 * Single job template — full job details with an apply button.
 *
 * @package Alarede
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="ae-page-header"<?php alarede_page_header_style(); ?>>
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Careers', 'alarede' ); ?></span>
			<h1><?php the_title(); ?></h1>
			<?php
			$job_meta = get_post_meta( get_the_ID(), '_ae_job_meta', true );
			if ( $job_meta ) {
				echo '<p style="color:rgba(255,255,255,.85);margin-top:.5rem;">' . esc_html( $job_meta ) . '</p>';
			}
			?>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container ae-content-area no-sidebar">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>

			<div class="ae-job-apply">
				<a class="ae-btn ae-btn--solid" href="<?php echo esc_url( alarede_job_apply_url( get_the_ID() ) ); ?>"><?php esc_html_e( 'Apply for This Role', 'alarede' ); ?></a>
				<a class="ae-btn" href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( '← All Openings', 'alarede' ); ?></a>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php
get_footer();
