<?php
/**
 * Single post template router.
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_get_display_category = static function ( $parcinq_post_id ) {
	$parcinq_categories = get_the_category( $parcinq_post_id );

	if ( empty( $parcinq_categories ) ) {
		return null;
	}

	usort(
		$parcinq_categories,
		static function ( $parcinq_first, $parcinq_second ) {
			$parcinq_first_depth  = count( get_ancestors( $parcinq_first->term_id, 'category' ) );
			$parcinq_second_depth = count( get_ancestors( $parcinq_second->term_id, 'category' ) );

			if ( $parcinq_first_depth === $parcinq_second_depth ) {
				return $parcinq_first->term_id <=> $parcinq_second->term_id;
			}

			return $parcinq_second_depth <=> $parcinq_first_depth;
		}
	);

	return $parcinq_categories[0];
};

set_query_var( 'parcinq_get_display_category', $parcinq_get_display_category );
?>

<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();

		$parcinq_article_layout = function_exists( 'get_field' ) ? (string) get_field( 'article_layout' ) : 'cover';
		$parcinq_article_layout = 'standard' === $parcinq_article_layout ? 'standard' : 'cover';

		get_template_part( 'template-parts/single', $parcinq_article_layout );
	endwhile;
	?>
</main>

<?php
get_footer();
