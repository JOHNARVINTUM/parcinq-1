<?php
/**
 * Parcinq search helpers and AJAX search.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns approved public page slugs for search.
 *
 * @return string[]
 */
function parcinq_search_approved_page_slugs() {
	return array( 'about', 'advertise', 'contact', 'careers', 'shop' );
}

/**
 * Returns public searchable post types for Parcinq search.
 *
 * @param bool $include_pages Whether to include page in the returned post types.
 * @return string[]
 */
function parcinq_search_post_types( $include_pages = true ) {
	$parcinq_post_types = array( 'post' );

	foreach ( array( 'cover_story', 'publicity' ) as $parcinq_post_type ) {
		$parcinq_object = get_post_type_object( $parcinq_post_type );

		if ( $parcinq_object && ! empty( $parcinq_object->public ) && ! $parcinq_object->exclude_from_search ) {
			$parcinq_post_types[] = $parcinq_post_type;
		}
	}

	if ( $include_pages ) {
		$parcinq_post_types[] = 'page';
	}

	return $parcinq_post_types;
}

/**
 * Returns approved page IDs for search.
 *
 * @return int[]
 */
function parcinq_search_approved_page_ids() {
	$parcinq_page_ids = array();

	foreach ( parcinq_search_approved_page_slugs() as $parcinq_slug ) {
		$parcinq_page = get_page_by_path( $parcinq_slug );

		if ( $parcinq_page instanceof WP_Post && 'publish' === $parcinq_page->post_status ) {
			$parcinq_page_ids[] = (int) $parcinq_page->ID;
		}
	}

	return array_values( array_unique( array_filter( $parcinq_page_ids ) ) );
}

/**
 * Returns globally excluded page IDs for search.
 *
 * @return int[]
 */
function parcinq_search_excluded_page_ids() {
	$parcinq_ids = array(
		(int) get_option( 'page_on_front' ),
		(int) get_option( 'page_for_posts' ),
		(int) get_option( 'wp_page_for_privacy_policy' ),
	);

	return array_values( array_unique( array_filter( $parcinq_ids ) ) );
}

/**
 * Restricts a search SQL fragment to post titles only for marked Parcinq queries.
 *
 * @param string   $search Search SQL.
 * @param WP_Query $query Query object.
 * @return string
 */
function parcinq_posts_search_title_only( $search, $query ) {
	global $wpdb;

	if ( ! $query instanceof WP_Query || ! $query->get( 'parcinq_title_only' ) || ! $query->is_search() ) {
		return $search;
	}

	$parcinq_search_terms = (array) $query->get( 'search_terms' );

	if ( empty( $parcinq_search_terms ) ) {
		$parcinq_raw = trim( (string) $query->get( 's' ) );
		$parcinq_search_terms = '' !== $parcinq_raw ? array( $parcinq_raw ) : array();
	}

	if ( empty( $parcinq_search_terms ) ) {
		return $search;
	}

	$parcinq_parts = array();

	foreach ( $parcinq_search_terms as $parcinq_term ) {
		$parcinq_like = '%' . $wpdb->esc_like( $parcinq_term ) . '%';
		$parcinq_parts[] = $wpdb->prepare( "{$wpdb->posts}.post_title LIKE %s", $parcinq_like );
	}

	return ' AND (' . implode( ' AND ', $parcinq_parts ) . ') ';
}
add_filter( 'posts_search', 'parcinq_posts_search_title_only', 10, 2 );

/**
 * Restricts page matches to approved pages for marked Parcinq search queries.
 *
 * @param string   $where SQL where fragment.
 * @param WP_Query $query Query object.
 * @return string
 */
function parcinq_posts_where_approved_pages( $where, $query ) {
	global $wpdb;

	if ( ! $query instanceof WP_Query || ! $query->get( 'parcinq_limit_pages' ) || ! $query->is_search() ) {
		return $where;
	}

	$parcinq_page_ids = array_map( 'absint', (array) $query->get( 'parcinq_approved_page_ids' ) );
	$parcinq_page_ids = array_values( array_filter( $parcinq_page_ids ) );

	if ( empty( $parcinq_page_ids ) ) {
		$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_type <> %s", 'page' );
		return $where;
	}

	$where .= " AND ( {$wpdb->posts}.post_type <> 'page' OR {$wpdb->posts}.ID IN (" . implode( ',', $parcinq_page_ids ) . ') )';

	return $where;
}
add_filter( 'posts_where', 'parcinq_posts_where_approved_pages', 10, 2 );

/**
 * Scopes frontend search queries to Parcinq public content.
 *
 * @param WP_Query $query Query object.
 */
function parcinq_scope_main_search_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	$parcinq_search = trim( (string) $query->get( 's' ) );

	$query->set( 'post_type', parcinq_search_post_types( true ) );
	$query->set( 'post_status', 'publish' );
	$query->set( 'post__not_in', parcinq_search_excluded_page_ids() );
	$query->set( 'parcinq_limit_pages', true );
	$query->set( 'parcinq_approved_page_ids', parcinq_search_approved_page_ids() );

	if ( '' !== $parcinq_search && 3 > mb_strlen( $parcinq_search ) ) {
		$query->set( 'parcinq_title_only', true );
	}
}
add_action( 'pre_get_posts', 'parcinq_scope_main_search_query' );

/**
 * Returns searchable public taxonomies for section results.
 *
 * @return string[]
 */
function parcinq_search_taxonomies() {
	$parcinq_taxonomies = array( 'category' );

	foreach ( get_taxonomies( array( 'public' => true ), 'objects' ) as $parcinq_taxonomy ) {
		if ( ! empty( $parcinq_taxonomy->name ) && in_array( $parcinq_taxonomy->name, array( 'post_tag', 'nav_menu' ), true ) ) {
			continue;
		}

		if ( ! empty( $parcinq_taxonomy->name ) && ! in_array( $parcinq_taxonomy->name, $parcinq_taxonomies, true ) ) {
			$parcinq_taxonomies[] = $parcinq_taxonomy->name;
		}
	}

	return $parcinq_taxonomies;
}

/**
 * Returns the best category for a post.
 *
 * @param int $post_id Post ID.
 * @return WP_Term|null
 */
function parcinq_search_post_primary_category( $post_id ) {
	$parcinq_categories = get_the_category( $post_id );

	if ( empty( $parcinq_categories ) || is_wp_error( $parcinq_categories ) ) {
		return null;
	}

	$parcinq_primary_ids = array(
		(int) get_post_meta( $post_id, '_yoast_wpseo_primary_category', true ),
		(int) get_post_meta( $post_id, 'rank_math_primary_category', true ),
	);

	foreach ( $parcinq_primary_ids as $parcinq_primary_id ) {
		foreach ( $parcinq_categories as $parcinq_category ) {
			if ( (int) $parcinq_category->term_id === $parcinq_primary_id ) {
				return $parcinq_category;
			}
		}
	}

	usort(
		$parcinq_categories,
		static function ( $a, $b ) {
			$depth_a = count( get_ancestors( $a->term_id, 'category' ) );
			$depth_b = count( get_ancestors( $b->term_id, 'category' ) );

			if ( $depth_a === $depth_b ) {
				return strcasecmp( $a->name, $b->name );
			}

			return $depth_b <=> $depth_a;
		}
	);

	return $parcinq_categories[0];
}

/**
 * Builds a taxonomy hierarchy label.
 *
 * @param WP_Term $term Term object.
 * @return string
 */
function parcinq_search_term_label( $term ) {
	$parcinq_names = array( $term->name );

	if ( ! empty( $term->parent ) ) {
		$parcinq_parent = get_term( $term->parent, $term->taxonomy );

		if ( $parcinq_parent && ! is_wp_error( $parcinq_parent ) ) {
			array_unshift( $parcinq_names, $parcinq_parent->name );
		}
	}

	return strtoupper( implode( ' · ', $parcinq_names ) );
}

/**
 * Returns a deterministic result label for a post.
 *
 * @param int|WP_Post $post Post object or ID.
 * @return string
 */
function parcinq_search_post_label( $post ) {
	$parcinq_post = get_post( $post );

	if ( ! $parcinq_post ) {
		return __( 'ARTICLE', 'parcinq-theme' );
	}

	if ( 'post' === $parcinq_post->post_type ) {
		$parcinq_category = parcinq_search_post_primary_category( $parcinq_post->ID );
		return $parcinq_category ? strtoupper( $parcinq_category->name ) : __( 'ARTICLE', 'parcinq-theme' );
	}

	if ( 'page' === $parcinq_post->post_type ) {
		return strtoupper( get_the_title( $parcinq_post ) ? get_the_title( $parcinq_post ) : __( 'PAGE', 'parcinq-theme' ) );
	}

	$parcinq_terms = wp_get_object_terms( $parcinq_post->ID, get_object_taxonomies( $parcinq_post->post_type ), array( 'fields' => 'all' ) );

	if ( ! is_wp_error( $parcinq_terms ) && ! empty( $parcinq_terms ) ) {
		usort(
			$parcinq_terms,
			static function ( $a, $b ) {
				return strcasecmp( $a->name, $b->name );
			}
		);
		return strtoupper( $parcinq_terms[0]->name );
	}

	if ( 'cover_story' === $parcinq_post->post_type ) {
		return __( 'COVER STORY', 'parcinq-theme' );
	}

	if ( 'publicity' === $parcinq_post->post_type ) {
		return __( 'PUBLICITY', 'parcinq-theme' );
	}

	$parcinq_object = get_post_type_object( $parcinq_post->post_type );
	return $parcinq_object && ! empty( $parcinq_object->labels->singular_name ) ? strtoupper( $parcinq_object->labels->singular_name ) : __( 'ARTICLE', 'parcinq-theme' );
}

/**
 * Scores a candidate result.
 *
 * @param string $query Search query.
 * @param string $title Result title.
 * @param string $label Result label.
 * @param string $body Optional body text.
 * @return int
 */
function parcinq_search_score_result( $query, $title, $label, $body = '' ) {
	$needle = mb_strtolower( $query );
	$title_l = mb_strtolower( $title );
	$label_l = mb_strtolower( $label );
	$body_l  = mb_strtolower( $body );

	if ( $title_l === $needle ) {
		return 700;
	}

	if ( $label_l === $needle ) {
		return 650;
	}

	if ( 0 === mb_strpos( $title_l, $needle ) ) {
		return 600;
	}

	if ( 0 === mb_strpos( $label_l, $needle ) ) {
		return 550;
	}

	if ( false !== mb_strpos( $title_l, $needle ) ) {
		return 500;
	}

	if ( false !== mb_strpos( $label_l, $needle ) ) {
		return 450;
	}

	if ( 3 <= mb_strlen( $needle ) && '' !== $body_l && false !== mb_strpos( $body_l, $needle ) ) {
		return 200;
	}

	return 0;
}

/**
 * Builds a normalized result array for a post.
 *
 * @param WP_Post $post Post object.
 * @param string  $query Search query.
 * @return array|null
 */
function parcinq_search_post_result( $post, $query ) {
	$parcinq_label = parcinq_search_post_label( $post );
	$parcinq_body  = 3 <= mb_strlen( $query ) ? wp_strip_all_tags( $post->post_excerpt . ' ' . $post->post_content ) : '';
	$parcinq_score = parcinq_search_score_result( $query, get_the_title( $post ), $parcinq_label, $parcinq_body );

	if ( 1 > $parcinq_score ) {
		return null;
	}

	return array(
		'id'        => 'post:' . $post->ID,
		'type'      => 'content',
		'title'     => html_entity_decode( get_the_title( $post ), ENT_QUOTES, get_bloginfo( 'charset' ) ),
		'label'     => $parcinq_label,
		'permalink' => get_permalink( $post ),
		'score'     => $parcinq_score,
		'date'      => get_post_time( 'U', true, $post ),
	);
}

/**
 * Builds a normalized result array for a term.
 *
 * @param WP_Term $term Term object.
 * @param string  $query Search query.
 * @return array|null
 */
function parcinq_search_term_result( $term, $query ) {
	$parcinq_url = get_term_link( $term );

	if ( is_wp_error( $parcinq_url ) ) {
		return null;
	}

	$parcinq_label = parcinq_search_term_label( $term );
	$parcinq_score = parcinq_search_score_result( $query, $term->name, $parcinq_label );

	if ( 1 > $parcinq_score ) {
		return null;
	}

	return array(
		'id'        => 'term:' . $term->taxonomy . ':' . $term->term_id,
		'type'      => 'section',
		'title'     => html_entity_decode( $term->name, ENT_QUOTES, get_bloginfo( 'charset' ) ),
		'label'     => $parcinq_label,
		'permalink' => $parcinq_url,
		'score'     => $parcinq_score + 25,
		'date'      => 0,
	);
}

/**
 * Runs a constrained content query for search candidates.
 *
 * @param string $query Search query.
 * @param bool   $title_only Whether to search titles only.
 * @param int    $limit Candidate limit.
 * @return WP_Post[]
 */
function parcinq_search_query_posts( $query, $title_only, $limit ) {
	$parcinq_query = new WP_Query(
		array(
			's'                         => $query,
			'post_type'                 => parcinq_search_post_types( true ),
			'post_status'               => 'publish',
			'posts_per_page'            => $limit,
			'no_found_rows'             => true,
			'ignore_sticky_posts'       => true,
			'post__not_in'              => parcinq_search_excluded_page_ids(),
			'parcinq_title_only'        => $title_only,
			'parcinq_limit_pages'       => true,
			'parcinq_approved_page_ids' => parcinq_search_approved_page_ids(),
		)
	);

	return $parcinq_query->posts;
}

/**
 * Returns combined live-search results.
 *
 * @param string $raw_query Raw query.
 * @return array
 */
function parcinq_live_search_results( $raw_query ) {
	$parcinq_query = sanitize_text_field( wp_unslash( $raw_query ) );
	$parcinq_query = trim( preg_replace( '/\s+/', ' ', $parcinq_query ) );

	if ( '' === $parcinq_query ) {
		return array(
			'query'   => '',
			'total'   => 0,
			'results' => array(),
		);
	}

	$parcinq_length = mb_strlen( $parcinq_query );
	$parcinq_posts  = parcinq_search_query_posts( $parcinq_query, true, 60 );

	if ( 3 <= $parcinq_length ) {
		$parcinq_posts = array_merge( $parcinq_posts, parcinq_search_query_posts( $parcinq_query, false, 80 ) );
	}

	$parcinq_results = array();
	$parcinq_seen    = array();

	foreach ( $parcinq_posts as $parcinq_post ) {
		if ( isset( $parcinq_seen[ 'post:' . $parcinq_post->ID ] ) ) {
			continue;
		}

		$parcinq_seen[ 'post:' . $parcinq_post->ID ] = true;
		$parcinq_result = parcinq_search_post_result( $parcinq_post, $parcinq_query );

		if ( $parcinq_result ) {
			$parcinq_results[] = $parcinq_result;
		}
	}

	$parcinq_terms = get_terms(
		array(
			'taxonomy'   => parcinq_search_taxonomies(),
			'hide_empty' => false,
			'name__like' => $parcinq_query,
			'number'     => 60,
		)
	);

	if ( ! is_wp_error( $parcinq_terms ) ) {
		foreach ( $parcinq_terms as $parcinq_term ) {
			$parcinq_result = parcinq_search_term_result( $parcinq_term, $parcinq_query );

			if ( $parcinq_result ) {
				$parcinq_results[] = $parcinq_result;
			}
		}
	}

	$parcinq_deduped = array();

	foreach ( $parcinq_results as $parcinq_result ) {
		$parcinq_url_key = strtolower( untrailingslashit( $parcinq_result['permalink'] ) );

		if ( isset( $parcinq_deduped[ $parcinq_url_key ] ) && $parcinq_deduped[ $parcinq_url_key ]['score'] >= $parcinq_result['score'] ) {
			continue;
		}

		$parcinq_deduped[ $parcinq_url_key ] = $parcinq_result;
	}

	$parcinq_results = array_values( $parcinq_deduped );

	usort(
		$parcinq_results,
		static function ( $a, $b ) {
			if ( $a['score'] === $b['score'] ) {
				if ( $a['date'] === $b['date'] ) {
					return strcasecmp( $a['title'], $b['title'] );
				}

				return $b['date'] <=> $a['date'];
			}

			return $b['score'] <=> $a['score'];
		}
	);

	$parcinq_total = count( $parcinq_results );
	$parcinq_rows  = array_slice( $parcinq_results, 0, 10 );

	return array(
		'query'   => $parcinq_query,
		'total'   => $parcinq_total,
		'results' => array_map(
			static function ( $result ) {
				return array(
					'title'     => $result['title'],
					'label'     => $result['label'],
					'permalink' => $result['permalink'],
					'type'      => $result['type'],
				);
			},
			$parcinq_rows
		),
	);
}

/**
 * Handles live search AJAX requests.
 */
function parcinq_ajax_live_search() {
	check_ajax_referer( 'parcinq_live_search', 'nonce' );

	$parcinq_payload = parcinq_live_search_results( isset( $_POST['query'] ) ? $_POST['query'] : '' );

	wp_send_json_success( $parcinq_payload );
}
add_action( 'wp_ajax_parcinq_live_search', 'parcinq_ajax_live_search' );
add_action( 'wp_ajax_nopriv_parcinq_live_search', 'parcinq_ajax_live_search' );