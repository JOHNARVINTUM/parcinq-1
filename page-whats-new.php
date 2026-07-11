<?php
/**
 * What’s New page template.
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_paged = max(
	1,
	(int) get_query_var( 'paged' ),
	(int) get_query_var( 'page' )
);

$parcinq_posts_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 12,
		'paged'               => $parcinq_paged,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
	)
);

$parcinq_posts           = $parcinq_posts_query->posts;
$parcinq_featured_posts  = array_slice( $parcinq_posts, 0, 7 );
$parcinq_remaining_posts = array_slice( $parcinq_posts, 7 );
$parcinq_mosaic_classes  = array( 'm-lead', 'm-tall', 'm-std', 'm-std', 'm-std', 'm-half', 'm-half' );
$parcinq_placeholders    = array( 'g4', 'g6', 'g3', 'g7', 'g8', 'g5', 'g2' );

$parcinq_get_post_category = static function ( $parcinq_post_id ) {
	$parcinq_categories = get_the_category( $parcinq_post_id );

	if ( empty( $parcinq_categories ) ) {
		return null;
	}

	foreach ( $parcinq_categories as $parcinq_category ) {
		if ( 0 !== (int) $parcinq_category->parent ) {
			return $parcinq_category;
		}
	}

	return $parcinq_categories[0];
};

$parcinq_render_media = static function ( $parcinq_post_id, $parcinq_class, $parcinq_label ) {
	if ( has_post_thumbnail( $parcinq_post_id ) ) {
		echo get_the_post_thumbnail(
			$parcinq_post_id,
			'large',
			array(
				'class' => 'archive-card-image',
				'alt'   => the_title_attribute(
					array(
						'post' => $parcinq_post_id,
						'echo' => false,
					)
				),
			)
		);
		return;
	}

	echo '<div class="ph ' . esc_attr( $parcinq_class ) . '" data-label="' . esc_attr( $parcinq_label ) . '"></div>';
};
?>

<main id="primary" class="site-main">
	<section class="ed-hero whats-new-hero">
		<div class="wrap">
			<span class="bgword"><?php echo esc_html__( 'NEW', 'parcinq-theme' ); ?></span>
			<div class="inner reveal">
				<div class="ed-meta">
					<span><?php echo esc_html__( 'What’s New', 'parcinq-theme' ); ?></span>
					<span class="dot" aria-hidden="true"></span>
					<span class="muted"><?php echo esc_html__( 'Updated daily', 'parcinq-theme' ); ?></span>
				</div>
				<h1><?php echo esc_html__( 'What’s New', 'parcinq-theme' ); ?></h1>
				<p class="stand"><?php echo esc_html__( 'Latest stories, announcements and the things we are paying attention to right now.', 'parcinq-theme' ); ?></p>
			</div>
		</div>
	</section>

	<div class="ruleheavy"></div>

	<section class="whats-new-feed">
		<div class="wrap whats-new-wrap">
			<?php if ( $parcinq_posts_query->have_posts() ) : ?>
				<?php if ( ! empty( $parcinq_featured_posts ) ) : ?>
					<div class="mosaic reveal">
						<?php foreach ( $parcinq_featured_posts as $parcinq_index => $parcinq_post ) : ?>
							<?php
							$parcinq_category = $parcinq_get_post_category( $parcinq_post->ID );
							$parcinq_category_name = $parcinq_category ? $parcinq_category->name : __( 'Article', 'parcinq-theme' );
							$parcinq_card_class = isset( $parcinq_mosaic_classes[ $parcinq_index ] ) ? $parcinq_mosaic_classes[ $parcinq_index ] : 'm-std';
							$parcinq_placeholder = isset( $parcinq_placeholders[ $parcinq_index ] ) ? $parcinq_placeholders[ $parcinq_index ] : 'g4';
							?>
							<a class="cover-card <?php echo esc_attr( $parcinq_card_class ); ?>" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>">
								<?php if ( 0 === $parcinq_index ) : ?>
									<span class="m-num"><?php echo esc_html( sprintf( '%02d', $parcinq_index + 1 ) ); ?></span>
								<?php endif; ?>
								<?php $parcinq_render_media( $parcinq_post->ID, $parcinq_placeholder, $parcinq_category_name ); ?>
								<div class="scrim"></div>
								<div class="meta">
									<span class="tag"><?php echo esc_html( $parcinq_category_name ); ?></span>
									<h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3>
									<p><?php echo esc_html( get_the_excerpt( $parcinq_post ) ); ?></p>
									<span class="date"><?php echo esc_html( get_the_date( '', $parcinq_post ) ); ?></span>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $parcinq_remaining_posts ) ) : ?>
					<section class="whats-new-index reveal" aria-labelledby="whats-new-index-title">
						<div class="index-head">
							<h2 id="whats-new-index-title"><?php echo esc_html__( 'The Index', 'parcinq-theme' ); ?></h2>
							<span class="k"><?php echo esc_html__( 'More from What’s New', 'parcinq-theme' ); ?></span>
						</div>
						<div class="article-index">
							<?php foreach ( $parcinq_remaining_posts as $parcinq_index => $parcinq_post ) : ?>
								<?php
								$parcinq_category = $parcinq_get_post_category( $parcinq_post->ID );
								$parcinq_category_name = $parcinq_category ? $parcinq_category->name : __( 'Article', 'parcinq-theme' );
								?>
								<a class="index-row" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>">
									<span class="num"><?php echo esc_html( sprintf( '%02d', $parcinq_index + 1 ) ); ?></span>
									<span class="index-copy">
										<h4><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h4>
										<span class="excerpt"><?php echo esc_html( get_the_excerpt( $parcinq_post ) ); ?></span>
										<span class="date"><?php echo esc_html( get_the_date( '', $parcinq_post ) ); ?></span>
									</span>
									<span class="cat"><?php echo esc_html( $parcinq_category_name ); ?></span>
								</a>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>

				<?php
				$parcinq_pagination = paginate_links(
					array(
						'total'     => (int) $parcinq_posts_query->max_num_pages,
						'current'   => $parcinq_paged,
						'mid_size'  => 2,
						'prev_text' => esc_html__( 'Previous', 'parcinq-theme' ),
						'next_text' => esc_html__( 'Next', 'parcinq-theme' ),
					)
				);
				?>
				<?php if ( $parcinq_pagination ) : ?>
					<nav class="navigation pagination" aria-label="<?php echo esc_attr__( 'Posts pagination', 'parcinq-theme' ); ?>">
						<div class="nav-links"><?php echo wp_kses_post( $parcinq_pagination ); ?></div>
					</nav>
				<?php endif; ?>
			<?php else : ?>
				<p class="archive-empty whats-new-empty"><?php echo esc_html__( 'No articles have been published yet.', 'parcinq-theme' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
wp_reset_postdata();
get_footer();