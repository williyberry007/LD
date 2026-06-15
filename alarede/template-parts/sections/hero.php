<?php
/**
 * Hero section — a slider built from Hero Slides (ae_slide). Each slide can use
 * an uploaded image or a video (self-hosted MP4 background, or YouTube/Vimeo).
 * Falls back to a single Customizer-driven hero when no slides exist.
 *
 * @package Alarede
 */

/**
 * Build a muted, looping, controls-free background embed URL for YouTube/Vimeo.
 *
 * @param string $url Source URL.
 * @return string Embed iframe HTML, or '' if the provider is not recognised.
 */
function alarede_hero_embed( $url ) {
	// YouTube (watch, youtu.be, embed, shorts).
	if ( preg_match( '~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $m ) ) {
		$id  = $m[1];
		$src = add_query_arg(
			array(
				'autoplay'       => 1,
				'mute'           => 1,
				'loop'           => 1,
				'playlist'       => $id, // Required for looping a single video.
				'controls'       => 0,
				'showinfo'       => 0,
				'modestbranding' => 1,
				'playsinline'    => 1,
				'rel'            => 0,
			),
			'https://www.youtube.com/embed/' . $id
		);
		return sprintf( '<iframe src="%s" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen title="%s"></iframe>', esc_url( $src ), esc_attr__( 'Hero video', 'alarede' ) );
	}

	// Vimeo.
	if ( preg_match( '~vimeo\.com/(?:video/)?(\d+)~', $url, $m ) ) {
		$src = add_query_arg(
			array(
				'autoplay'   => 1,
				'muted'      => 1,
				'loop'       => 1,
				'background' => 1, // Clean autoplay loop, no controls.
			),
			'https://player.vimeo.com/video/' . $m[1]
		);
		return sprintf( '<iframe src="%s" frameborder="0" allow="autoplay; fullscreen" allowfullscreen title="%s"></iframe>', esc_url( $src ), esc_attr__( 'Hero video', 'alarede' ) );
	}

	return '';
}

/**
 * Render one slide's media layer.
 *
 * @param string $type      'image' or 'video'.
 * @param string $image_url Background image URL.
 * @param string $video_url Video URL.
 * @param string $alt       Image alt text.
 */
function alarede_hero_media( $type, $image_url, $video_url, $alt = '' ) {
	if ( 'video' === $type && $video_url ) {
		$ext = strtolower( pathinfo( (string) wp_parse_url( $video_url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
		if ( in_array( $ext, array( 'mp4', 'webm', 'ogg' ), true ) ) {
			$mime = ( 'ogg' === $ext ) ? 'ogg' : $ext;
			printf(
				'<video class="ae-hero__video" autoplay muted loop playsinline preload="auto"%1$s><source src="%2$s" type="video/%3$s"></video>',
				$image_url ? ' poster="' . esc_url( $image_url ) . '"' : '',
				esc_url( $video_url ),
				esc_attr( $mime )
			);
			return;
		}

		// YouTube / Vimeo background embed.
		$embed = alarede_hero_embed( $video_url );
		if ( $embed ) {
			echo '<div class="ae-hero__embed">' . $embed . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_url() above.
			return;
		}

		// Last resort: let WordPress try to embed it.
		$oembed = wp_oembed_get( $video_url );
		if ( $oembed ) {
			echo '<div class="ae-hero__embed">' . $oembed . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- oEmbed HTML.
			return;
		}
	}

	$style = $image_url
		? 'background-image:url(' . esc_url( $image_url ) . ');'
		: 'background:linear-gradient(135deg,#2b2a26,#4a463d);';
	printf( '<div class="ae-hero__bg" style="%s" role="img" aria-label="%s"></div>', esc_attr( $style ), esc_attr( $alt ) );
}

$slides = new WP_Query(
	array(
		'post_type'      => 'ae_slide',
		'posts_per_page' => 10,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

$autoplay = (int) get_theme_mod( 'alarede_hero_autoplay', 6000 );
?>
<section class="ae-hero ae-hero--slider" id="home" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<div class="ae-hero__slides">
	<?php if ( $slides->have_posts() ) : ?>
		<?php
		$i = 0;
		while ( $slides->have_posts() ) :
			$slides->the_post();
			$type      = get_post_meta( get_the_ID(), '_ae_slide_media_type', true ) ?: 'image';
			$video_url = get_post_meta( get_the_ID(), '_ae_slide_video_url', true );
			$kicker    = get_post_meta( get_the_ID(), '_ae_slide_kicker', true );
			$b1t       = get_post_meta( get_the_ID(), '_ae_slide_btn1_text', true );
			$b1u       = get_post_meta( get_the_ID(), '_ae_slide_btn1_url', true );
			$b2t       = get_post_meta( get_the_ID(), '_ae_slide_btn2_text', true );
			$b2u       = get_post_meta( get_the_ID(), '_ae_slide_btn2_url', true );
			$image_url = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'full' ) : '';
			$subtitle  = get_the_content();
			?>
			<div class="ae-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>">
				<?php alarede_hero_media( $type, $image_url, $video_url, get_the_title() ); ?>
				<div class="ae-hero__overlay"></div>
				<div class="ae-hero__content">
					<?php if ( $kicker ) : ?><span class="ae-hero__kicker"><?php echo esc_html( $kicker ); ?></span><?php endif; ?>
					<h1 class="ae-hero__title"><?php the_title(); ?></h1>
					<?php if ( $subtitle ) : ?><p class="ae-hero__subtitle"><?php echo esc_html( wp_strip_all_tags( $subtitle ) ); ?></p><?php endif; ?>
					<?php if ( $b1t || $b2t ) : ?>
						<div class="ae-hero__actions">
							<?php if ( $b1t ) : ?><a class="ae-btn ae-btn--solid" href="<?php echo esc_url( $b1u ?: '#contact' ); ?>"><?php echo esc_html( $b1t ); ?></a><?php endif; ?>
							<?php if ( $b2t ) : ?><a class="ae-btn ae-btn--light" href="<?php echo esc_url( $b2u ?: '#portfolio' ); ?>"><?php echo esc_html( $b2t ); ?></a><?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php
			$i++;
		endwhile;
		wp_reset_postdata();
		?>
	<?php else : ?>
		<?php
		// Fallback: single Customizer hero (also supports image or video).
		$hero_image    = get_theme_mod( 'alarede_hero_image', '' );
		$hero_video    = get_theme_mod( 'alarede_hero_video', '' );
		$hero_type     = get_theme_mod( 'alarede_hero_media_type', 'image' );
		$hero_kicker   = get_theme_mod( 'alarede_hero_kicker', __( 'Luxury Wedding & Event Planning', 'alarede' ) );
		$hero_title    = get_theme_mod( 'alarede_hero_title', __( 'Timeless Celebrations, Effortlessly Designed', 'alarede' ) );
		$hero_subtitle = get_theme_mod( 'alarede_hero_subtitle', __( 'Bespoke destination weddings crafted with artistry, precision and heart.', 'alarede' ) );
		$btn1_text     = get_theme_mod( 'alarede_hero_btn1_text', __( 'Start Planning', 'alarede' ) );
		$btn1_url      = get_theme_mod( 'alarede_hero_btn1_url', '#contact' );
		$btn2_text     = get_theme_mod( 'alarede_hero_btn2_text', __( 'View Portfolio', 'alarede' ) );
		$btn2_url      = get_theme_mod( 'alarede_hero_btn2_url', '#portfolio' );
		?>
		<div class="ae-hero__slide is-active">
			<?php alarede_hero_media( $hero_type, $hero_image, $hero_video, $hero_title ); ?>
			<div class="ae-hero__overlay"></div>
			<div class="ae-hero__content">
				<?php if ( $hero_kicker ) : ?><span class="ae-hero__kicker"><?php echo esc_html( $hero_kicker ); ?></span><?php endif; ?>
				<h1 class="ae-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
				<?php if ( $hero_subtitle ) : ?><p class="ae-hero__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p><?php endif; ?>
				<div class="ae-hero__actions">
					<?php if ( $btn1_text ) : ?><a class="ae-btn ae-btn--solid" href="<?php echo esc_url( $btn1_url ); ?>"><?php echo esc_html( $btn1_text ); ?></a><?php endif; ?>
					<?php if ( $btn2_text ) : ?><a class="ae-btn ae-btn--light" href="<?php echo esc_url( $btn2_url ); ?>"><?php echo esc_html( $btn2_text ); ?></a><?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	</div>

	<?php if ( $slides->post_count > 1 ) : ?>
		<button class="ae-hero__nav ae-hero__nav--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'alarede' ); ?>">&#8249;</button>
		<button class="ae-hero__nav ae-hero__nav--next" aria-label="<?php esc_attr_e( 'Next slide', 'alarede' ); ?>">&#8250;</button>
		<div class="ae-hero__dots">
			<?php for ( $d = 0; $d < $slides->post_count; $d++ ) : ?>
				<button class="<?php echo 0 === $d ? 'is-active' : ''; ?>" data-slide="<?php echo (int) $d; ?>" aria-label="<?php printf( esc_attr__( 'Go to slide %d', 'alarede' ), $d + 1 ); ?>"></button>
			<?php endfor; ?>
		</div>
	<?php endif; ?>

	<a class="ae-hero__scroll" href="#intro"><?php esc_html_e( 'Scroll', 'alarede' ); ?></a>
</section>
