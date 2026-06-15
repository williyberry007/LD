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

$faqs = array(
	array(
		__( 'How far in advance should we book?', 'alarede' ),
		__( 'We recommend reaching out as early as possible — popular dates and venues are reserved well ahead. We accept a limited number of events each year to give every couple our full attention.', 'alarede' ),
	),
	array(
		__( 'How do your fees work?', 'alarede' ),
		__( 'Coordination is offered for a flat fee, and full planning typically includes a percentage of the services we source and manage on your behalf. We provide a clear, tailored proposal after our first conversation.', 'alarede' ),
	),
	array(
		__( 'Do you travel for destination events?', 'alarede' ),
		__( 'Yes. We plan and coordinate destination celebrations and handle the logistics so you can simply enjoy the experience.', 'alarede' ),
	),
	array(
		__( 'Can you work with our existing suppliers?', 'alarede' ),
		__( 'Absolutely. We are happy to collaborate with vendors you love, and can recommend trusted partners where needed.', 'alarede' ),
	),
	array(
		__( 'Do you offer training through the Academy?', 'alarede' ),
		__( 'Yes — our Academy teaches event skills, gele, makeup, gift wrapping, eru-iyawo wrapping and traditional letters. Contact us for the next intake.', 'alarede' ),
	),
);

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
