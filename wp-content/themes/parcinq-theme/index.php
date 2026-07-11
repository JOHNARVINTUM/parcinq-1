<?php
/**
 * Main template file.
 *
 * @package Parcinq_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
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
