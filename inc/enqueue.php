<?php
/**
 * Theme assets.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns a file modification version when available.
 *
 * @param string $relative_path Theme-relative asset path.
 * @return string
 */
function parcinq_asset_version( $relative_path ) {
	$file_path = get_template_directory() . '/' . ltrim( $relative_path, '/' );

	if ( file_exists( $file_path ) ) {
		return (string) filemtime( $file_path );
	}

	return wp_get_theme()->get( 'Version' );
}

/**
 * Enqueues theme styles and scripts.
 */
function parcinq_enqueue_assets() {
	wp_enqueue_style(
		'parcinq-theme-style',
		get_stylesheet_uri(),
		array(),
		parcinq_asset_version( 'style.css' )
	);

	wp_enqueue_style(
		'parcinq-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'parcinq-theme-style' ),
		parcinq_asset_version( 'assets/css/main.css' )
	);

	wp_enqueue_script(
		'parcinq-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		parcinq_asset_version( 'assets/js/main.js' ),
		true
	);

	wp_localize_script(
		'parcinq-main',
		'parcinqNewsletter',
		array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'parcinq_newsletter_signup' ),
			'messages' => array(
				'invalid' => __( 'Please enter a valid email address.', 'parcinq-theme' ),
				'server'  => __( 'Something went wrong. Please try again.', 'parcinq-theme' ),
			),
		)
	);
	wp_localize_script(
		'parcinq-main',
		'parcinqSearch',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'parcinq_live_search' ),
			'minLength' => 1,
			'debounce'  => 300,
			'messages'  => array(
				'initial'   => __( 'Start typing to search stories and sections', 'parcinq-theme' ),
				'loading'   => __( 'Searching...', 'parcinq-theme' ),
				'noResults' => __( 'No results for', 'parcinq-theme' ),
				'error'     => __( 'Search is temporarily unavailable.', 'parcinq-theme' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'parcinq_enqueue_assets' );
