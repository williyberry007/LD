<?php
/**
 * The header for our theme.
 *
 * @package Alago_Events
 */

$ae_transparent = ( is_front_page() && ! is_paged() ) ? 'is-transparent' : 'is-solid';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'alago-events' ); ?></a>

	<header id="masthead" class="site-header <?php echo esc_attr( $ae_transparent ); ?>" data-transparent="<?php echo esc_attr( $ae_transparent ); ?>">
		<div class="ae-container site-header__inner">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<div>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
						$ae_desc = get_bloginfo( 'description', 'display' );
						if ( $ae_desc ) {
							echo '<p class="site-description">' . esc_html( $ae_desc ) . '</p>';
						}
						?>
					</div>
					<?php
				}
				?>
			</div>

			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span></span><span></span><span></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'alago-events' ); ?></span>
			</button>

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary', 'alago-events' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => 'alago_events_default_menu',
					)
				);
				?>
			</nav>
		</div>
	</header>

	<div id="content" class="site-content">
<?php
/**
 * Fallback menu shown until the user assigns a Primary menu.
 */
function alago_events_default_menu() {
	echo '<ul id="primary-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'alago-events' ) . '</a></li>';
	wp_list_pages( array( 'title_li' => '', 'depth' => 1, 'number' => 5 ) );
	echo '</ul>';
}
