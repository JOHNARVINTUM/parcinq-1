<?php
/**
 * Contributor profile fields and helpers.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers editable contributor profile fields on WordPress users.
 */
function parcinq_register_contributor_profile_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_parcinq_contributor_profile',
			'title'                 => 'Contributor Profile',
			'fields'                => array(
				array(
					'key'           => 'field_parcinq_author_photo',
					'label'         => 'Author Photo',
					'name'          => 'parcinq_author_photo',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
				),
				array(
					'key'   => 'field_parcinq_contributor_role',
					'label' => 'Contributor Role',
					'name'  => 'parcinq_contributor_role',
					'type'  => 'text',
				),
				array(
					'key'       => 'field_parcinq_author_bio',
					'label'     => 'Short Biography',
					'name'      => 'parcinq_author_bio',
					'type'      => 'textarea',
					'new_lines' => 'br',
				),
				array(
					'key'   => 'field_parcinq_instagram_url',
					'label' => 'Instagram URL',
					'name'  => 'parcinq_instagram_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_parcinq_facebook_url',
					'label' => 'Facebook URL',
					'name'  => 'parcinq_facebook_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_parcinq_x_url',
					'label' => 'X/Twitter URL',
					'name'  => 'parcinq_x_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_parcinq_tiktok_url',
					'label' => 'TikTok URL',
					'name'  => 'parcinq_tiktok_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_parcinq_youtube_url',
					'label' => 'YouTube URL',
					'name'  => 'parcinq_youtube_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_parcinq_website_url',
					'label' => 'Personal Website URL',
					'name'  => 'parcinq_website_url',
					'type'  => 'url',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'user_form',
						'operator' => '==',
						'value'    => 'all',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);
}
add_action( 'acf/init', 'parcinq_register_contributor_profile_fields' );

/**
 * Returns public editorial post types used on author archives.
 *
 * @return string[]
 */
function parcinq_get_author_post_types() {
	$parcinq_post_types = array( 'post' );

	foreach ( array( 'cover_story', 'publicity' ) as $parcinq_post_type ) {
		if ( post_type_exists( $parcinq_post_type ) ) {
			$parcinq_post_types[] = $parcinq_post_type;
		}
	}

	return $parcinq_post_types;
}

/**
 * Safely reads an ACF user field.
 *
 * @param string $parcinq_field_name ACF field name.
 * @param int    $parcinq_user_id    User ID.
 * @return mixed
 */
function parcinq_get_user_profile_field( $parcinq_field_name, $parcinq_user_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}

	return get_field( $parcinq_field_name, 'user_' . absint( $parcinq_user_id ) );
}

/**
 * Validates a profile URL for output.
 *
 * @param mixed $parcinq_url URL value.
 * @return string
 */
function parcinq_get_valid_profile_url( $parcinq_url ) {
	$parcinq_url = trim( (string) $parcinq_url );

	if ( '' === $parcinq_url || ! wp_http_validate_url( $parcinq_url ) ) {
		return '';
	}

	return $parcinq_url;
}

/**
 * Builds normalized contributor profile data.
 *
 * @param int $parcinq_user_id User ID.
 * @return array
 */
function parcinq_get_contributor_profile( $parcinq_user_id ) {
	$parcinq_user_id = absint( $parcinq_user_id );
	$parcinq_user    = get_userdata( $parcinq_user_id );

	if ( ! $parcinq_user ) {
		return array();
	}

	$parcinq_photo_id = absint( parcinq_get_user_profile_field( 'parcinq_author_photo', $parcinq_user_id ) );
	$parcinq_role     = trim( (string) parcinq_get_user_profile_field( 'parcinq_contributor_role', $parcinq_user_id ) );
	$parcinq_bio      = trim( (string) parcinq_get_user_profile_field( 'parcinq_author_bio', $parcinq_user_id ) );

	if ( '' === $parcinq_role ) {
		$parcinq_role = __( 'PARCINQ Contributor', 'parcinq-theme' );
	}

	if ( '' === $parcinq_bio ) {
		$parcinq_bio = get_the_author_meta( 'description', $parcinq_user_id );
	}

	$parcinq_social_fields = array(
		'instagram' => array( 'label' => __( 'Instagram', 'parcinq-theme' ), 'field' => 'parcinq_instagram_url' ),
		'facebook'  => array( 'label' => __( 'Facebook', 'parcinq-theme' ), 'field' => 'parcinq_facebook_url' ),
		'x'         => array( 'label' => __( 'X', 'parcinq-theme' ), 'field' => 'parcinq_x_url' ),
		'tiktok'    => array( 'label' => __( 'TikTok', 'parcinq-theme' ), 'field' => 'parcinq_tiktok_url' ),
		'youtube'   => array( 'label' => __( 'YouTube', 'parcinq-theme' ), 'field' => 'parcinq_youtube_url' ),
		'website'   => array( 'label' => __( 'Website', 'parcinq-theme' ), 'field' => 'parcinq_website_url' ),
	);
	$parcinq_social_links  = array();

	foreach ( $parcinq_social_fields as $parcinq_key => $parcinq_social_field ) {
		$parcinq_url = parcinq_get_valid_profile_url( parcinq_get_user_profile_field( $parcinq_social_field['field'], $parcinq_user_id ) );
		if ( '' !== $parcinq_url ) {
			$parcinq_social_links[ $parcinq_key ] = array(
				'label' => $parcinq_social_field['label'],
				'url'   => $parcinq_url,
			);
		}
	}

	$parcinq_display_name = $parcinq_user->display_name ? $parcinq_user->display_name : $parcinq_user->user_login;

	return array(
		'id'           => $parcinq_user_id,
		'display_name' => $parcinq_display_name,
		'first_name'   => strtok( $parcinq_display_name, ' ' ) ?: $parcinq_display_name,
		'archive_url'  => get_author_posts_url( $parcinq_user_id ),
		'photo_id'     => $parcinq_photo_id,
		'role'         => $parcinq_role,
		'bio'          => $parcinq_bio,
		'social_links' => $parcinq_social_links,
	);
}

/**
 * Ensures editorial CPTs support WordPress authors when registered.
 *
 * @param array  $parcinq_args      Post type args.
 * @param string $parcinq_post_type Post type key.
 * @return array
 */
function parcinq_editorial_cpt_author_support( $parcinq_args, $parcinq_post_type ) {
	if ( ! in_array( $parcinq_post_type, array( 'cover_story', 'publicity' ), true ) ) {
		return $parcinq_args;
	}

	$parcinq_supports = isset( $parcinq_args['supports'] ) && is_array( $parcinq_args['supports'] ) ? $parcinq_args['supports'] : array();

	if ( ! in_array( 'author', $parcinq_supports, true ) ) {
		$parcinq_supports[] = 'author';
	}

	$parcinq_args['supports'] = $parcinq_supports;

	return $parcinq_args;
}
add_filter( 'register_post_type_args', 'parcinq_editorial_cpt_author_support', 10, 2 );

/**
 * Scopes author archives to editorial post types.
 *
 * @param WP_Query $parcinq_query Query object.
 */
function parcinq_author_archive_post_types( $parcinq_query ) {
	if ( is_admin() || ! $parcinq_query->is_main_query() || ! $parcinq_query->is_author() ) {
		return;
	}

	$parcinq_query->set( 'post_type', parcinq_get_author_post_types() );
	$parcinq_query->set( 'post_status', 'publish' );
	$parcinq_query->set( 'posts_per_page', 12 );
	$parcinq_query->set( 'ignore_sticky_posts', true );
}
add_action( 'pre_get_posts', 'parcinq_author_archive_post_types' );