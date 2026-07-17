<?php
/**
 * Category archive template.
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_category = get_queried_object();
$parcinq_empty_states = array(
	'videos' => array(
		'kicker' => __( 'Rolling Soon', 'parcinq-theme' ),
		'title'  => __( 'Our Newest Vertical Is Almost Live', 'parcinq-theme' ),
		'copy'   => __( 'PARCINQ Videos is our newest vertical, and it is almost here. Think behind-the-scenes films, candid interviews, and the moments that never make the page, the kind of thing you will want to watch on repeat. We are keeping the details under wraps a little longer. Premieres are coming, be a CINQtizen to get first dibs on every drop.', 'parcinq-theme' ),
	),
	'sports' => array(
		'kicker' => __( 'Warming Up', 'parcinq-theme' ),
		'title'  => __( 'Game On, Very Soon', 'parcinq-theme' ),
		'copy'   => __( 'PARCINQ Sports is where athletes meet pop culture: the crossover moments, the tunnel fits, and the personalities blurring the line between the court and the culture. Expect profiles, style, and the stories the highlight reels leave out. Kickoff is close, be a CINQtizen to catch the very first play.', 'parcinq-theme' ),
	),
	'gaming' => array(
		'kicker' => __( 'Now Loading', 'parcinq-theme' ),
		'title'  => __( 'Press Start, Soon', 'parcinq-theme' ),
		'copy'   => __( 'PARCINQ Gaming is where idols, esports, and fandom collide. The titles everyone is playing, the creators shaping the scene, cosplay as editorial, and the whole culture around the controller. New drops are incoming, join the CINQtizens to be first in the lobby.', 'parcinq-theme' ),
	),
	'food'   => array(
		'kicker' => __( 'Still Cooking', 'parcinq-theme' ),
		'title'  => __( 'We Are Still Setting the Table', 'parcinq-theme' ),
		'copy'   => __( 'PARCINQ Food is where taste meets culture: where the scene eats after hours, the tables worth the wait, the chefs to know, and the dishes everyone will be talking about. Part guide, part obsession. We are still setting the table, so save room. First servings drop soon, become a CINQtizen to get first dibs.', 'parcinq-theme' ),
	),
	'film'   => array(
		'kicker' => __( 'Coming Soon', 'parcinq-theme' ),
		'title'  => __( 'The Reel Is Loading', 'parcinq-theme' ),
		'copy'   => __( "PARCINQ Film is where coming-of-age stories, the directors to watch, festival buzz, and the movies defining a generation of Filipino and Asian cinema all live. Reviews, interviews, and the watchlist you didn't know you needed are on the way. First features premiere soon, become a CINQtizen to get first dibs.", 'parcinq-theme' ),
	),
);
$parcinq_empty_state = array(
	'kicker' => __( 'Coming Soon', 'parcinq-theme' ),
	'title'  => __( 'Stories Are Loading', 'parcinq-theme' ),
	'copy'   => __( 'This PARCINQ section is almost ready. Check back soon for new stories, profiles, and culture dispatches.', 'parcinq-theme' ),
);

if ( $parcinq_category instanceof WP_Term && isset( $parcinq_empty_states[ $parcinq_category->slug ] ) ) {
	$parcinq_empty_state = $parcinq_empty_states[ $parcinq_category->slug ];
}
?>

<main id="primary" class="site-main">
	<section class="ed-hero">
		<div class="bgword"><?php echo esc_html( single_cat_title( '', false ) ); ?></div>
		<div class="wrap">
			<div class="inner reveal">
				<div class="ed-meta"><?php echo esc_html__( 'Category', 'parcinq-theme' ); ?></div>
				<h1><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
				<?php if ( category_description() ) : ?>
					<div class="stand"><?php echo wp_kses_post( category_description() ); ?></div>
				<?php endif; ?>
			</div>
			<div class="ruleheavy category-divider" aria-hidden="true"></div>
		</div>
	</section>

	<div class="wrap category-archive-wrap">
	<?php if ( have_posts() ) : ?>
			<?php
			$parcinq_posts = array();
			while ( have_posts() ) :
				the_post();
				$parcinq_posts[] = get_post();
			endwhile;

			$parcinq_featured_posts = array_slice( $parcinq_posts, 0, 4 );
			$parcinq_remaining_posts = array_slice( $parcinq_posts, 4 );
			$parcinq_mosaic_classes = array( 'm-lead', 'm-tall', 'm-std', 'm-std' );
			$parcinq_placeholder_classes = array( 'g3', 'g8', 'g7', 'g2' );
			?>

			<div class="mosaic reveal">
				<?php foreach ( $parcinq_featured_posts as $parcinq_index => $post ) : ?>
					<?php
					setup_postdata( $post );
					$parcinq_card_categories = get_the_category();
					$parcinq_card_category = ! empty( $parcinq_card_categories ) ? $parcinq_card_categories[0]->name : __( 'Article', 'parcinq-theme' );
					$parcinq_card_class = isset( $parcinq_mosaic_classes[ $parcinq_index ] ) ? $parcinq_mosaic_classes[ $parcinq_index ] : 'm-std';
					$parcinq_placeholder_class = isset( $parcinq_placeholder_classes[ $parcinq_index ] ) ? $parcinq_placeholder_classes[ $parcinq_index ] : 'g3';
					$parcinq_excerpt_limit = 0 === $parcinq_index ? 22 : ( 1 === $parcinq_index ? 16 : 0 );
					?>
					<a class="cover-card <?php echo esc_attr( $parcinq_card_class ); ?>" href="<?php echo esc_url( get_permalink() ); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php
							the_post_thumbnail(
								'large',
								array(
									'class' => 'archive-card-image',
									'alt'   => the_title_attribute( array( 'echo' => false ) ),
								)
							);
							?>
						<?php else : ?>
							<div class="ph <?php echo esc_attr( $parcinq_placeholder_class ); ?>"></div>
						<?php endif; ?>
						<div class="scrim"></div>
						<div class="meta">
							<span class="tag"><?php echo esc_html( $parcinq_card_category ); ?></span>
							<h3><?php echo esc_html( get_the_title() ); ?></h3>
							<?php if ( 0 < $parcinq_excerpt_limit ) : ?>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), $parcinq_excerpt_limit, '...' ) ); ?></p>
							<?php endif; ?>
							<span class="date"><?php echo esc_html( get_the_date() ); ?></span>
						</div>
					</a>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
			</div>

			<?php if ( ! empty( $parcinq_remaining_posts ) ) : ?>
				<section class="category-index reveal" aria-labelledby="category-index-title">
					<div class="index-head">
						<div>
							<div class="k"><?php echo esc_html__( 'Archive', 'parcinq-theme' ); ?></div>
							<h2 id="category-index-title"><?php echo esc_html__( 'The Index', 'parcinq-theme' ); ?></h2>
						</div>
					</div>

					<div class="article-index">
						<?php foreach ( $parcinq_remaining_posts as $parcinq_index => $post ) : ?>
							<?php
							setup_postdata( $post );
							$parcinq_row_categories = get_the_category();
							$parcinq_row_category = ! empty( $parcinq_row_categories ) ? $parcinq_row_categories[0]->name : __( 'Article', 'parcinq-theme' );
							?>
							<a class="index-row" href="<?php echo esc_url( get_permalink() ); ?>">
								<span class="num"><?php echo esc_html( sprintf( '%02d', $parcinq_index + 1 ) ); ?></span>
								<span class="index-title"><?php echo esc_html( get_the_title() ); ?></span>
								<span class="cat"><?php echo esc_html( $parcinq_row_category ); ?></span>
							</a>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</section>
			<?php endif; ?>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => esc_html__( 'Previous', 'parcinq-theme' ),
					'next_text' => esc_html__( 'Next', 'parcinq-theme' ),
				)
			);
			?>
		<?php else : ?>
			<section class="empty-state category-empty-state reveal" aria-labelledby="category-empty-title">
				<span class="kicker"><?php echo esc_html( $parcinq_empty_state['kicker'] ); ?></span>
				<h2 id="category-empty-title"><?php echo esc_html( $parcinq_empty_state['title'] ); ?></h2>
				<p><?php echo esc_html( $parcinq_empty_state['copy'] ); ?></p>
				<button class="btn cinq-modal-trigger" type="button" data-cinq-modal-open><?php echo esc_html__( 'Become a CINQtizen', 'parcinq-theme' ); ?></button>
			</section>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();