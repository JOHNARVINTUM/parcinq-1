<?php
/**
 * Newsletter signup storage and form handling.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register private newsletter signup records for admin review.
 */
function parcinq_register_newsletter_signup_post_type() {
	register_post_type(
		'newsletter_signup',
		array(
			'labels'              => array(
				'name'               => __( 'Newsletter Signups', 'parcinq-theme' ),
				'singular_name'      => __( 'Newsletter Signup', 'parcinq-theme' ),
				'menu_name'          => __( 'Newsletter Signups', 'parcinq-theme' ),
				'add_new_item'       => __( 'Add Newsletter Signup', 'parcinq-theme' ),
				'edit_item'          => __( 'View Newsletter Signup', 'parcinq-theme' ),
				'not_found'          => __( 'No newsletter signups found.', 'parcinq-theme' ),
				'not_found_in_trash' => __( 'No newsletter signups found in Trash.', 'parcinq-theme' ),
			),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-email-alt2',
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		)
	);
}
add_action( 'init', 'parcinq_register_newsletter_signup_post_type' );

/**
 * Normalize a newsletter email consistently.
 *
 * @param string $parcinq_email Raw email.
 * @return string
 */
function parcinq_normalize_newsletter_email( $parcinq_email ) {
	$parcinq_email = sanitize_email( strtolower( trim( (string) $parcinq_email ) ) );

	return $parcinq_email;
}

/**
 * Find an existing newsletter signup by email.
 *
 * @param string $parcinq_email Subscriber email.
 * @return int Existing signup ID, or 0.
 */
function parcinq_find_newsletter_signup_by_email( $parcinq_email ) {
	$parcinq_email = parcinq_normalize_newsletter_email( $parcinq_email );

	if ( '' === $parcinq_email ) {
		return 0;
	}

	$parcinq_signups = get_posts(
		array(
			'post_type'      => 'newsletter_signup',
			'post_status'    => array( 'private', 'publish', 'draft' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'   => '_parcinq_newsletter_email',
					'value' => $parcinq_email,
				),
			),
		)
	);

	return empty( $parcinq_signups ) ? 0 : (int) $parcinq_signups[0];
}

/**
 * Create a newsletter signup after validation.
 *
 * @param string $parcinq_email  Raw email.
 * @param string $parcinq_source Signup source URL.
 * @return array
 */
function parcinq_save_newsletter_signup( $parcinq_email, $parcinq_source = '' ) {
	$parcinq_email = parcinq_normalize_newsletter_email( $parcinq_email );

	if ( '' === $parcinq_email || ! is_email( $parcinq_email ) ) {
		return array(
			'status'  => 'error',
			'code'    => 'invalid_email',
			'message' => __( 'Please enter a valid email address.', 'parcinq-theme' ),
			'email'   => $parcinq_email,
		);
	}

	if ( parcinq_find_newsletter_signup_by_email( $parcinq_email ) ) {
		return array(
			'status'  => 'success',
			'code'    => 'duplicate',
			'message' => __( 'You’re already on the list.', 'parcinq-theme' ),
			'email'   => '',
		);
	}

	$parcinq_signup_id = wp_insert_post(
		array(
			'post_type'   => 'newsletter_signup',
			'post_status' => 'private',
			'post_title'  => $parcinq_email,
		),
		true
	);

	if ( is_wp_error( $parcinq_signup_id ) ) {
		return array(
			'status'  => 'error',
			'code'    => 'insert_failed',
			'message' => __( 'Something went wrong. Please try again.', 'parcinq-theme' ),
			'email'   => $parcinq_email,
		);
	}

	// Final duplicate check immediately before storing the normalized email meta.
	$parcinq_existing_id = parcinq_find_newsletter_signup_by_email( $parcinq_email );
	if ( $parcinq_existing_id && (int) $parcinq_existing_id !== (int) $parcinq_signup_id ) {
		wp_delete_post( $parcinq_signup_id, true );

		return array(
			'status'  => 'success',
			'code'    => 'duplicate',
			'message' => __( 'You’re already on the list.', 'parcinq-theme' ),
			'email'   => '',
		);
	}

	update_post_meta( $parcinq_signup_id, '_parcinq_newsletter_email', $parcinq_email );
	update_post_meta( $parcinq_signup_id, '_parcinq_newsletter_source', esc_url_raw( $parcinq_source ? $parcinq_source : home_url( '/' ) ) );

	return array(
		'status'  => 'success',
		'code'    => 'created',
		'message' => __( 'You’re in. Welcome, CINQtizen.', 'parcinq-theme' ),
		'email'   => '',
	);
}

/**
 * Process the newsletter signup form.
 *
 * @return array Result data for the template.
 */
function parcinq_handle_newsletter_signup() {
	$parcinq_result = array(
		'status'  => '',
		'message' => '',
		'email'   => '',
	);

	if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['parcinq_newsletter_submit'] ) ) {
		return $parcinq_result;
	}

	$parcinq_email = isset( $_POST['parcinq_newsletter_email'] ) ? parcinq_normalize_newsletter_email( wp_unslash( $_POST['parcinq_newsletter_email'] ) ) : '';
	$parcinq_trap  = isset( $_POST['parcinq_newsletter_company'] ) ? sanitize_text_field( wp_unslash( $_POST['parcinq_newsletter_company'] ) ) : '';

	$parcinq_result['email'] = $parcinq_email;

	if ( ! isset( $_POST['parcinq_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['parcinq_newsletter_nonce'] ) ), 'parcinq_newsletter_signup' ) ) {
		$parcinq_result['status']  = 'error';
		$parcinq_result['message'] = __( 'The signup form expired. Please refresh and try again.', 'parcinq-theme' );
		return $parcinq_result;
	}

	if ( '' !== $parcinq_trap ) {
		$parcinq_result['status']  = 'error';
		$parcinq_result['message'] = __( 'Something went wrong. Please try again.', 'parcinq-theme' );
		return $parcinq_result;
	}

	$parcinq_result = parcinq_save_newsletter_signup( $parcinq_email, wp_get_referer() ? wp_get_referer() : home_url( '/' ) );

	if ( 'duplicate' === $parcinq_result['code'] ) {
		$parcinq_result['message'] = __( 'You are already on the list.', 'parcinq-theme' );
	} elseif ( 'created' === $parcinq_result['code'] ) {
		$parcinq_result['message'] = __( "You're on the list.", 'parcinq-theme' );
	}

	return $parcinq_result;
}

/**
 * AJAX newsletter signup handler.
 */
function parcinq_ajax_newsletter_signup() {
	$parcinq_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $parcinq_nonce, 'parcinq_newsletter_signup' ) ) {
		wp_send_json_error(
			array(
				'code'    => 'expired',
				'message' => __( 'The signup form expired. Please refresh and try again.', 'parcinq-theme' ),
			),
			403
		);
	}

	$parcinq_trap = isset( $_POST['parcinq_newsletter_company'] ) ? sanitize_text_field( wp_unslash( $_POST['parcinq_newsletter_company'] ) ) : '';
	if ( '' !== $parcinq_trap ) {
		wp_send_json_error(
			array(
				'code'    => 'bot',
				'message' => __( 'Something went wrong. Please try again.', 'parcinq-theme' ),
			),
			400
		);
	}

	$parcinq_email  = isset( $_POST['parcinq_newsletter_email'] ) ? wp_unslash( $_POST['parcinq_newsletter_email'] ) : '';
	$parcinq_result = parcinq_save_newsletter_signup( $parcinq_email, wp_get_referer() ? wp_get_referer() : home_url( '/' ) );

	if ( 'error' === $parcinq_result['status'] ) {
		wp_send_json_error( $parcinq_result, 'invalid_email' === $parcinq_result['code'] ? 400 : 500 );
	}

	wp_send_json_success( $parcinq_result );
}
add_action( 'wp_ajax_parcinq_newsletter_signup', 'parcinq_ajax_newsletter_signup' );
add_action( 'wp_ajax_nopriv_parcinq_newsletter_signup', 'parcinq_ajax_newsletter_signup' );

/**
 * Add useful admin columns for newsletter signups.
 *
 * @param array $parcinq_columns Admin columns.
 * @return array
 */
function parcinq_newsletter_signup_columns( $parcinq_columns ) {
	return array(
		'cb'     => isset( $parcinq_columns['cb'] ) ? $parcinq_columns['cb'] : '',
		'title'  => __( 'Email', 'parcinq-theme' ),
		'source' => __( 'Source', 'parcinq-theme' ),
		'date'   => __( 'Date', 'parcinq-theme' ),
	);
}
add_filter( 'manage_newsletter_signup_posts_columns', 'parcinq_newsletter_signup_columns' );

/**
 * Render custom admin column values.
 *
 * @param string $parcinq_column  Column name.
 * @param int    $parcinq_post_id Post ID.
 */
function parcinq_newsletter_signup_column_value( $parcinq_column, $parcinq_post_id ) {
	if ( 'source' === $parcinq_column ) {
		$parcinq_source = get_post_meta( $parcinq_post_id, '_parcinq_newsletter_source', true );
		echo $parcinq_source ? esc_html( $parcinq_source ) : '&mdash;';
	}
}
add_action( 'manage_newsletter_signup_posts_custom_column', 'parcinq_newsletter_signup_column_value', 10, 2 );