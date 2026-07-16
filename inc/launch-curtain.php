<?php
/**
 * Site-wide launch curtain controls.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Launch target shown to the browser countdown.
 *
 * @return string
 */
function parcinq_launch_curtain_target_iso() {
	return '2026-07-17T12:00:00+08:00';
}

/**
 * Launch target as a UTC timestamp for server-side gating.
 *
 * @return int
 */
function parcinq_launch_curtain_timestamp() {
	return strtotime( '2026-07-17 04:00:00 UTC' );
}

/**
 * Determine whether the public launch curtain should be shown.
 *
 * @return bool
 */
function parcinq_should_show_launch_curtain() {
	global $pagenow;

	$parcinq_request_method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';
	$parcinq_request_uri    = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$parcinq_request_path   = wp_parse_url( $parcinq_request_uri, PHP_URL_PATH );

	if ( 'GET' !== $parcinq_request_method && 'HEAD' !== $parcinq_request_method ) {
		return false;
	}

	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
		return false;
	}

	if ( 'wp-login.php' === $pagenow || 'wp-cron.php' === $pagenow ) {
		return false;
	}

	if ( $parcinq_request_path && preg_match( '#/(wp-json|wp-admin|wp-login\.php|xmlrpc\.php|wp-cron\.php)(/|$)#', $parcinq_request_path ) ) {
		return false;
	}

	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		return false;
	}

	return time() < parcinq_launch_curtain_timestamp();
}

/**
 * Render the launch curtain before normal public templates load.
 *
 * @return void
 */
function parcinq_maybe_render_launch_curtain() {
	if ( ! parcinq_should_show_launch_curtain() ) {
		return;
	}

	status_header( 200 );
	nocache_headers();

	$template = get_template_directory() . '/template-parts/launch-curtain.php';

	if ( file_exists( $template ) ) {
		require $template;
		exit;
	}
}
add_action( 'template_redirect', 'parcinq_maybe_render_launch_curtain', 0 );