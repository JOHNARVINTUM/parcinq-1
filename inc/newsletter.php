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
 * Find an existing newsletter signup by email.
 *
 * @param string $parcinq_email Subscriber email.
 * @return int Existing signup ID, or 0.
 */
function parcinq_find_newsletter_signup_by_email( $parcinq_email ) {
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

	$parcinq_email = isset( $_POST['parcinq_newsletter_email'] ) ? sanitize_email( wp_unslash( $_POST['parcinq_newsletter_email'] ) ) : '';
	$parcinq_trap  = isset( $_POST['parcinq_newsletter_company'] ) ? sanitize_text_field( wp_unslash( $_POST['parcinq_newsletter_company'] ) ) : '';

	$parcinq_result['email'] = $parcinq_email;

	if ( ! isset( $_POST['parcinq_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['parcinq_newsletter_nonce'] ) ), 'parcinq_newsletter_signup' ) ) {
		$parcinq_result['status']  = 'error';
		$parcinq_result['message'] = __( 'The signup form expired. Please refresh and try again.', 'parcinq-theme' );
		return $parcinq_result;
	}

	if ( '' !== $parcinq_trap ) {
		$parcinq_result['status']  = 'error';
		$parcinq_result['message'] = __( 'The signup could not be saved.', 'parcinq-theme' );
		return $parcinq_result;
	}

	if ( '' === $parcinq_email || ! is_email( $parcinq_email ) ) {
		$parcinq_result['status']  = 'error';
		$parcinq_result['message'] = __( 'Please enter a valid email address.', 'parcinq-theme' );
		return $parcinq_result;
	}

	if ( parcinq_find_newsletter_signup_by_email( $parcinq_email ) ) {
		$parcinq_result['status']  = 'success';
		$parcinq_result['message'] = __( 'You are already on the list.', 'parcinq-theme' );
		$parcinq_result['email']   = '';
		return $parcinq_result;
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
		$parcinq_result['status']  = 'error';
		$parcinq_result['message'] = __( 'The signup could not be saved. Please try again.', 'parcinq-theme' );
		return $parcinq_result;
	}

	update_post_meta( $parcinq_signup_id, '_parcinq_newsletter_email', $parcinq_email );
	update_post_meta( $parcinq_signup_id, '_parcinq_newsletter_source', esc_url_raw( wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );

	$parcinq_result['status']  = 'success';
	$parcinq_result['message'] = __( "You're on the list.", 'parcinq-theme' );
	$parcinq_result['email']   = '';

	return $parcinq_result;
}

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
