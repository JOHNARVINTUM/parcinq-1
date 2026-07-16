<?php
/**
 * Navigation helpers.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve menu category URLs through WordPress instead of stale custom paths.
 *
 * @param array   $atts      Link attributes.
 * @param WP_Post $menu_item Menu item object.
 * @return array
 */
function parcinq_normalize_menu_category_links( $atts, $menu_item ) {
	if ( ! $menu_item instanceof WP_Post ) {
		return $atts;
	}

	if ( 'taxonomy' === $menu_item->type && 'category' === $menu_item->object && ! empty( $menu_item->object_id ) ) {
		$category_link = get_category_link( (int) $menu_item->object_id );

		if ( ! is_wp_error( $category_link ) ) {
			$atts['href'] = $category_link;
		}

		return $atts;
	}

	if ( empty( $atts['href'] ) || '#' === $atts['href'] ) {
		return $atts;
	}

	$home_host = wp_parse_url( home_url(), PHP_URL_HOST );
	$link_host = wp_parse_url( $atts['href'], PHP_URL_HOST );

	if ( $link_host && $home_host && strtolower( $link_host ) !== strtolower( $home_host ) ) {
		return $atts;
	}

	$link_path = wp_parse_url( $atts['href'], PHP_URL_PATH );

	if ( ! $link_path ) {
		return $atts;
	}

	$path_parts = array_values( array_filter( explode( '/', trim( $link_path, '/' ) ) ) );
	$slug       = end( $path_parts );

	if ( ! $slug || in_array( $slug, array( 'page', 'feed' ), true ) ) {
		return $atts;
	}

	$category = get_category_by_slug( sanitize_title( $slug ) );

	if ( ! $category ) {
		return $atts;
	}

	$category_link = get_category_link( $category );

	if ( ! is_wp_error( $category_link ) ) {
		$atts['href'] = $category_link;
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'parcinq_normalize_menu_category_links', 10, 2 );