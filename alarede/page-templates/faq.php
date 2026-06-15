<?php
/**
 * Template Name: FAQ
 * Template Post Type: page
 *
 * Frequently asked questions as an accordion. Edit the $faqs array below to
 * manage questions, or add your own content above them in the page editor.
 *
 * @package Alarede
 */

get_header();

$faqs = alarede_get_list( 'alarede_faq_item', 'faqs' );

while ( have_posts() ) :
	the_post();
	?>

	<div class="ae-page-header">
		<div class="ae-container">
			<span class="ae-breadcrumb"><?php esc_html_e( 'Help', 'alarede' ); ?></span>
			<h1><?php echo esc_html( get_the_title() ? get_the_title() : __( 'Frequently Asked Questions', 'alarede' ) ); ?></h1>
		</div>
	</div>

	<section class="ae-section">
		<div class="ae-container">
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="entry-content" style="text-align:center;max-width:760px;margin:0 auto 3rem;"><?php the_content(); ?></div>
			<?php endif; ?>

			<div class="ae-faq">
				<?php foreach ( $faqs as $faq ) : ?>
					<div class="ae-faq__item">
						<button class="ae-faq__q"><?php echo esc_html( $faq[0] ); ?></button>
						<div class="ae-faq__a"><div class="ae-faq__a-inner"><?php echo wp_kses_post( wpautop( $faq[1] ) ); ?></div></div>
					</div>
				<?php endforeach; ?>
			</div>

			<div style="text-align:center;margin-top:3rem;">
				<p><?php esc_html_e( 'Still have a question?', 'alarede' ); ?></p>
				<a class="ae-btn ae-btn--solid" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'alarede' ); ?></a>
			</div>
		</div>
	</section>

	<?php
endwhile;

get_footer();
