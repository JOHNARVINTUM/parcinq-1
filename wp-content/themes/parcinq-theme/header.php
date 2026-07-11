<?php
/**
 * Theme header.
 *
 * @package Parcinq_Theme
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="topbar">
	<div class="ticker">
		<span><?php echo esc_html__( 'Now Live - PARCINQ', 'parcinq-theme' ); ?></span>
	</div>
</div>

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
			<button class="burger" id="burger" type="button" aria-label="<?php echo esc_attr__( 'Open menu', 'parcinq-theme' ); ?>">
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

		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				PARCIN<span class="five">Q</span>
				<?php
			}
			?>
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