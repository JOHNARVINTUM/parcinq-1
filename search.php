<?php
/**
 * Search results template.
 *
 * @package Parcinq_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
	<header class="page-header">
		<h1 class="page-title">
			<?php
			printf(
				/* translators: %s: Search query. */
				esc_html__( 'Search results for: %s', 'parcinq-theme' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h2 class="entry-title">
						<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
					</h2>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>

		<?php the_posts_navigation(); ?>
	<?php else : ?>
		<p><?php echo esc_html__( 'No posts found.', 'parcinq-theme' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
