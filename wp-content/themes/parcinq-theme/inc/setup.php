<?php
/**
 * Theme setup.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets up theme defaults and registers support for WordPress features.
 */
function parcinq_theme_setup() {
	load_theme_textdomain( 'parcinq-theme', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	register_nav_menus(
		array(
			'header_left'  => esc_html__( 'Header Left Menu', 'parcinq-theme' ),
			'header_right' => esc_html__( 'Header Right Menu', 'parcinq-theme' ),
			'footer'       => esc_html__( 'Footer Menu', 'parcinq-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'parcinq_theme_setup' );
