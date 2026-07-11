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
<header class="site-header">
	<div class="site-branding">
		<?php
		if ( has_custom_logo() ) {
			the_custom_logo();
		}
		?>
		<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
		</a>
	</div>

	<nav class="site-navigation" aria-label="<?php echo esc_attr__( 'Primary menu', 'parcinq-theme' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'fallback_cb'    => false,
			)
		);
		?>
	</nav>
</header>
