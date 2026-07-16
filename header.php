<?php
/**
 * Theme header.
 *
 * @package Parcinq_Theme
 */

$parcinq_header_logo_path = get_template_directory() . '/assets/images/parcinq-logo-black.png';
$parcinq_header_logo_url  = get_template_directory_uri() . '/assets/images/parcinq-logo-black.png';

if ( file_exists( $parcinq_header_logo_path ) ) {
	$parcinq_header_logo_url = add_query_arg( 'ver', filemtime( $parcinq_header_logo_path ), $parcinq_header_logo_url );
}

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/parcinq-site-icon.png' ); ?>" type="image/png">
	<link rel="apple-touch-icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/parcinq-site-icon.png' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="drawer-back" id="drawerBack"></div>
<aside class="drawer" id="drawer">
	<button class="x" id="drawerClose" aria-label="<?php echo esc_attr__( 'Close menu', 'parcinq-theme' ); ?>">&times;</button>
	<div class="drawer-logo">PARCIN<span class="five">Q</span></div>
	<nav aria-label="<?php echo esc_attr__( 'Drawer menu', 'parcinq-theme' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'header_left',
				'container'      => false,
				'fallback_cb'    => false,
				'items_wrap'     => '%3$s',
			)
		);
		wp_nav_menu(
			array(
				'theme_location' => 'header_right',
				'container'      => false,
				'fallback_cb'    => false,
				'items_wrap'     => '%3$s',
			)
		);
		?>
	</nav>
	<div class="rule"></div>
	<div class="sec">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'container'      => false,
				'fallback_cb'    => false,
				'items_wrap'     => '%3$s',
			)
		);
		?>
	</div>
	<div class="dmeta"><?php echo esc_html__( 'PARCINQ', 'parcinq-theme' ); ?></div>
</aside>

<header>
	<div class="wrap nav">
		<div class="util l">
			<button class="burger" id="burger" type="button" aria-label="<?php echo esc_attr__( 'Open menu', 'parcinq-theme' ); ?>" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
		</div>

		<nav aria-label="<?php echo esc_attr__( 'Header left menu', 'parcinq-theme' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'header_left',
					'container'      => false,
					'fallback_cb'    => false,
					'menu_class'     => 'menu left',
				)
			);
			?>
		</nav>

		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<img class="parcinq-logo-image" src="<?php echo esc_url( $parcinq_header_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		</a>

		<nav aria-label="<?php echo esc_attr__( 'Header right menu', 'parcinq-theme' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'header_right',
					'container'      => false,
					'fallback_cb'    => false,
					'menu_class'     => 'menu right',
				)
			);
			?>
		</nav>

		<div class="util r">
			<svg class="icon-btn" viewBox="0 0 24 24" fill="none" stroke-width="2" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
			<svg class="icon-btn" viewBox="0 0 24 24" fill="none" stroke-width="2" aria-hidden="true" focusable="false"><path d="M6 7h15l-1.5 9h-12z"/><path d="M6 7L5 3H2"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
		</div>
	</div>
</header>
