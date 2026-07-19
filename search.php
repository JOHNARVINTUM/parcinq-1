<?php
/**
 * Search results template.
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_search_query = get_search_query();
$parcinq_result_count = (int) $GLOBALS['wp_query']->found_posts;
?>

<main id="primary" class="site-main search-page">
	<section class="search-page-hero">
		<div class="wrap">
			<span class="kicker"><?php echo esc_html__( 'Search', 'parcinq-theme' ); ?></span>
			<h1>
				<?php
				printf(
					/* translators: %s: Search query. */
					esc_html__( 'Results for "%s"', 'parcinq-theme' ),
					esc_html( $parcinq_search_query )
				);
				?>
			</h1>
			<p class="search-page-count">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %d: Result count. */
						_n( '%d result', '%d results', $parcinq_result_count, 'parcinq-theme' ),
						$parcinq_result_count
					)
				);
				?>
			</p>
		</div>
	</section>

	<section class="search-page-results">
		<div class="wrap">
			<?php if ( have_posts() ) : ?>
				<div class="search-result-list">
					<?php
					while ( have_posts() ) :
						the_post();
						$parcinq_permalink = get_permalink();
						$parcinq_label     = function_exists( 'parcinq_search_post_label' ) ? parcinq_search_post_label( get_post() ) : __( 'ARTICLE', 'parcinq-theme' );
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-card' ); ?>>
							<a class="search-result-thumb" href="<?php echo esc_url( $parcinq_permalink ); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php
									the_post_thumbnail(
										'medium',
										array(
											'alt' => the_title_attribute( array( 'echo' => false ) ),
										)
									);
									?>
								<?php else : ?>
									<span class="ph g5" data-label="<?php echo esc_attr__( 'Result Image', 'parcinq-theme' ); ?>"></span>
								<?php endif; ?>
							</a>
							<div class="search-result-body">
								<span class="cat"><?php echo esc_html( $parcinq_label ); ?></span>
								<h2><a href="<?php echo esc_url( $parcinq_permalink ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h2>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '...' ) ); ?></p>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<div class="search-pagination">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 2,
							'prev_text' => esc_html__( 'Previous', 'parcinq-theme' ),
							'next_text' => esc_html__( 'Next', 'parcinq-theme' ),
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="search-page-empty">
					<h2><?php echo esc_html__( 'No results found.', 'parcinq-theme' ); ?></h2>
					<p><?php echo esc_html__( 'Try another keyword or browse the latest Parcinq stories.', 'parcinq-theme' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();