<?php
/**
 * Template Name: Editorial Preview
 *
 * Dynamic editorial homepage preview for Parcinq.
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_displayed_post_ids = array();
$parcinq_placeholder_index  = 0;
$parcinq_placeholder_classes = array( 'g1', 'g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8' );

$parcinq_track_posts = static function ( $parcinq_posts ) use ( &$parcinq_displayed_post_ids ) {
	foreach ( $parcinq_posts as $parcinq_post ) {
		$parcinq_displayed_post_ids[] = (int) $parcinq_post->ID;
	}

	$parcinq_displayed_post_ids = array_values( array_unique( $parcinq_displayed_post_ids ) );
};

$parcinq_get_category = static function ( $parcinq_slug ) {
	$parcinq_category = get_category_by_slug( $parcinq_slug );

	return $parcinq_category instanceof WP_Term ? $parcinq_category : null;
};

$parcinq_category_link = static function ( $parcinq_category ) {
	if ( ! $parcinq_category instanceof WP_Term ) {
		return '#';
	}

	$parcinq_link = get_category_link( $parcinq_category );

	return is_wp_error( $parcinq_link ) ? '#' : $parcinq_link;
};

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

$parcinq_get_posts = static function ( $parcinq_args ) use ( &$parcinq_displayed_post_ids ) {
	$parcinq_defaults = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'post__not_in'        => $parcinq_displayed_post_ids,
	);

	$parcinq_query = new WP_Query( array_merge( $parcinq_defaults, $parcinq_args ) );
	$parcinq_posts = $parcinq_query->posts;
	wp_reset_postdata();

	return $parcinq_posts;
};

$parcinq_get_category_posts = static function ( $parcinq_slug, $parcinq_count ) use ( $parcinq_get_category, $parcinq_get_posts ) {
	$parcinq_category = $parcinq_get_category( $parcinq_slug );

	if ( ! $parcinq_category ) {
		return array(
			'category' => null,
			'posts'    => array(),
		);
	}

	return array(
		'category' => $parcinq_category,
		'posts'    => $parcinq_get_posts(
			array(
				'posts_per_page' => $parcinq_count,
				'cat'            => (int) $parcinq_category->term_id,
			)
		),
	);
};

$parcinq_render_post_media = static function ( $parcinq_post_id, $parcinq_size = 'large', $parcinq_context = 'Article', $parcinq_extra_class = '' ) use ( &$parcinq_placeholder_index, $parcinq_placeholder_classes ) {
	if ( has_post_thumbnail( $parcinq_post_id ) ) {
		echo '<div class="ph ' . esc_attr( $parcinq_extra_class ) . '">';
		echo get_the_post_thumbnail(
			$parcinq_post_id,
			$parcinq_size,
			array(
				'alt' => the_title_attribute(
					array(
						'post' => $parcinq_post_id,
						'echo' => false,
					)
				),
			)
		);
		echo '</div>';
		return;
	}

	$parcinq_placeholder_class = $parcinq_placeholder_classes[ $parcinq_placeholder_index % count( $parcinq_placeholder_classes ) ];
	$parcinq_placeholder_index++;
	echo '<div class="ph ' . esc_attr( trim( $parcinq_placeholder_class . ' ' . $parcinq_extra_class ) ) . '" data-label="' . esc_attr( $parcinq_context ) . '"></div>';
};
$parcinq_trim_excerpt = static function ( $parcinq_post, $parcinq_word_limit ) {
	return wp_trim_words( get_the_excerpt( $parcinq_post ), $parcinq_word_limit, '...' );
};
$parcinq_sticky_posts = get_option( 'sticky_posts' );
$parcinq_hero_posts   = array();

if ( ! empty( $parcinq_sticky_posts ) ) {
	$parcinq_hero_posts = $parcinq_get_posts(
		array(
			'posts_per_page'      => 1,
			'post__in'            => $parcinq_sticky_posts,
			'post__not_in'        => array(),
			'ignore_sticky_posts' => false,
			'orderby'             => 'date',
			'order'               => 'DESC',
		)
	);
}

if ( empty( $parcinq_hero_posts ) ) {
	$parcinq_hero_posts = $parcinq_get_posts( array( 'posts_per_page' => 1 ) );
}

$parcinq_hero_post = ! empty( $parcinq_hero_posts ) ? $parcinq_hero_posts[0] : null;

$parcinq_hero_category       = null;
$parcinq_hero_kicker         = '';
$parcinq_hero_title_before   = '';
$parcinq_hero_title_emphasis = '';
$parcinq_hero_title_after    = '';
$parcinq_hero_has_title      = false;
$parcinq_hero_credits        = array();

if ( $parcinq_hero_post ) {
	$parcinq_track_posts( array( $parcinq_hero_post ) );

	$parcinq_hero_category = $parcinq_get_post_category( $parcinq_hero_post->ID );


	if ( function_exists( 'get_field' ) ) {
		$parcinq_hero_kicker         = trim( (string) get_field( 'hero_kicker', $parcinq_hero_post->ID ) );
		$parcinq_hero_title_before   = trim( (string) get_field( 'hero_title_before', $parcinq_hero_post->ID ) );
		$parcinq_hero_title_emphasis = trim( (string) get_field( 'hero_title_emphasis', $parcinq_hero_post->ID ) );
		$parcinq_hero_title_after    = trim( (string) get_field( 'hero_title_after', $parcinq_hero_post->ID ) );

		$parcinq_hero_credit_fields = array(
			'photographer'       => __( 'Photography', 'parcinq-theme' ),
			'art_director'       => __( 'Art Direction', 'parcinq-theme' ),
			'stylist'            => __( 'Styling', 'parcinq-theme' ),
			'additional_credits' => __( 'Additional Credits', 'parcinq-theme' ),
		);

		foreach ( $parcinq_hero_credit_fields as $parcinq_field_name => $parcinq_label ) {
			$parcinq_value = trim( (string) get_field( $parcinq_field_name, $parcinq_hero_post->ID ) );
			if ( '' !== $parcinq_value ) {
				$parcinq_hero_credits[] = $parcinq_label . ': ' . $parcinq_value;
			}
		}
	}

	$parcinq_hero_has_title = '' !== $parcinq_hero_title_before || '' !== $parcinq_hero_title_emphasis || '' !== $parcinq_hero_title_after;

	if ( '' === $parcinq_hero_kicker ) {
		$parcinq_hero_kicker = $parcinq_hero_category ? $parcinq_hero_category->name : __( 'Article', 'parcinq-theme' );
	}
}

$parcinq_cover_data   = $parcinq_get_category_posts( 'cover-stories', 3 );
$parcinq_cover_posts  = $parcinq_cover_data['posts'];
$parcinq_track_posts( $parcinq_cover_posts );

$parcinq_cityboy_data  = $parcinq_get_category_posts( 'city-boy', 1 );
$parcinq_cityboy_posts = $parcinq_cityboy_data['posts'];
$parcinq_track_posts( $parcinq_cityboy_posts );

$parcinq_whats_new_posts = $parcinq_get_posts( array( 'posts_per_page' => 4 ) );
$parcinq_track_posts( $parcinq_whats_new_posts );

$parcinq_music_data  = $parcinq_get_category_posts( 'music', 3 );
$parcinq_music_posts = $parcinq_music_data['posts'];
$parcinq_track_posts( $parcinq_music_posts );

$parcinq_style_data  = $parcinq_get_category_posts( 'style', 2 );
$parcinq_style_posts = $parcinq_style_data['posts'];
$parcinq_track_posts( $parcinq_style_posts );

$parcinq_culture_data  = $parcinq_get_category_posts( 'culture', 3 );
$parcinq_culture_posts = $parcinq_culture_data['posts'];
$parcinq_track_posts( $parcinq_culture_posts );

$parcinq_videos_data  = $parcinq_get_category_posts( 'videos', 4 );
$parcinq_videos_posts = $parcinq_videos_data['posts'];
$parcinq_track_posts( $parcinq_videos_posts );

$parcinq_cityboy_url = $parcinq_cityboy_data['category'] ? $parcinq_category_link( $parcinq_cityboy_data['category'] ) : '#';

$parcinq_shop_page = get_page_by_path( 'shop' );
$parcinq_shop_url  = $parcinq_shop_page instanceof WP_Post && 'publish' === $parcinq_shop_page->post_status ? get_permalink( $parcinq_shop_page ) : '#';
?>
<main id="primary" class="site-main">
	<section class="hero" id="top">
		<?php if ( $parcinq_hero_post ) : ?>
			<a class="hero-media" href="<?php echo esc_url( get_permalink( $parcinq_hero_post ) ); ?>">
				<?php if ( has_post_thumbnail( $parcinq_hero_post->ID ) ) : ?>
					<?php
					echo get_the_post_thumbnail(
						$parcinq_hero_post->ID,
						'full',
						array(
							'class' => 'hero-image',
							'alt'   => the_title_attribute(
								array(
									'post' => $parcinq_hero_post->ID,
									'echo' => false,
								)
							),
						)
					);
					?>
				<?php endif; ?>
				<div class="hero-monogram">P5</div>
				<div class="hero-grad"></div>
				<div class="wrap hero-content reveal">
					<span class="kicker"><?php echo esc_html( $parcinq_hero_kicker ); ?></span>
					<h1>
						<?php if ( $parcinq_hero_has_title ) : ?>
							<?php if ( '' !== $parcinq_hero_title_before ) : ?><?php echo esc_html( $parcinq_hero_title_before ); ?><?php endif; ?><?php if ( '' !== $parcinq_hero_title_emphasis ) : ?><?php echo '' !== $parcinq_hero_title_before ? ' ' : ''; ?><em><?php echo esc_html( $parcinq_hero_title_emphasis ); ?></em><?php endif; ?><?php if ( '' !== $parcinq_hero_title_after ) : ?><?php echo ( '' !== $parcinq_hero_title_before || '' !== $parcinq_hero_title_emphasis ) ? ' ' : ''; ?><?php echo esc_html( $parcinq_hero_title_after ); ?><?php endif; ?>
						<?php else : ?>
							<?php echo esc_html( get_the_title( $parcinq_hero_post ) ); ?>
						<?php endif; ?>
					</h1>
					<p><?php echo esc_html( $parcinq_trim_excerpt( $parcinq_hero_post, 22 ) ); ?></p>
					<div class="byline"><?php echo esc_html( get_the_date( '', $parcinq_hero_post ) ); ?><?php if ( ! empty( $parcinq_hero_credits ) ) : ?> <?php echo esc_html( 'Â·' ); ?> <?php echo esc_html( implode( ' Â· ', $parcinq_hero_credits ) ); ?><?php endif; ?></div>
					<span class="btn"><?php echo esc_html__( 'Enter the Story', 'parcinq-theme' ); ?>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
					</span>
				</div>
			</a>
		<?php else : ?>
			<div class="hero-media">
				<div class="hero-monogram">P5</div>
				<div class="hero-grad"></div>
				<div class="wrap hero-content reveal">
					<span class="kicker"><?php echo esc_html__( 'Parcinq', 'parcinq-theme' ); ?></span>
					<h1><?php echo esc_html__( 'Stories coming soon', 'parcinq-theme' ); ?></h1>
					<p><?php echo esc_html__( 'Publish posts to populate the homepage.', 'parcinq-theme' ); ?></p>
				</div>
			</div>
		<?php endif; ?>
	</section>

	<section id="covers">
		<div class="wrap">
			<div class="sec-head reveal">
				<div><span class="kicker"><?php echo esc_html__( 'The Vault', 'parcinq-theme' ); ?></span><h2><?php echo esc_html__( 'Cover Stories', 'parcinq-theme' ); ?></h2></div>
				<?php if ( $parcinq_cover_data['category'] ) : ?><a href="<?php echo esc_url( $parcinq_category_link( $parcinq_cover_data['category'] ) ); ?>" class="seeall"><?php echo esc_html__( 'All Covers', 'parcinq-theme' ); ?></a><?php endif; ?>
			</div>
			<?php if ( ! empty( $parcinq_cover_posts ) ) : ?>
				<div class="cover-grid reveal">
					<?php foreach ( $parcinq_cover_posts as $parcinq_index => $parcinq_post ) : ?>
						<?php $parcinq_card_category = $parcinq_get_post_category( $parcinq_post->ID ); ?>
						<?php if ( 0 === $parcinq_index ) : ?>
							<a class="cover-card feature" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>">
								<?php $parcinq_render_post_media( $parcinq_post->ID, 'large', __( 'Cover Editorial', 'parcinq-theme' ) ); ?>
								<div class="scrim"></div>
								<div class="meta"><span class="tag"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Cover Story', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3></div>
							</a>
							<?php if ( count( $parcinq_cover_posts ) > 1 ) : ?><div class="cover-col"><?php endif; ?>
						<?php else : ?>
							<a class="cover-card" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>">
								<?php $parcinq_render_post_media( $parcinq_post->ID, 'large', __( 'Cover Editorial', 'parcinq-theme' ) ); ?>
								<div class="scrim"></div>
								<div class="meta"><span class="tag"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Cover Story', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3></div>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php if ( count( $parcinq_cover_posts ) > 1 ) : ?></div><?php endif; ?>
				</div>
			<?php else : ?>
				<div class="home-empty reveal"><?php echo esc_html__( 'Cover stories will appear here once posts are assigned to the Cover Stories category.', 'parcinq-theme' ); ?></div>
			<?php endif; ?>
		</div>
	</section>

	<section class="franchise" id="cityboy">
		<div class="fr-inner">
			<div class="fr-media">
				<?php if ( ! empty( $parcinq_cityboy_posts ) ) : ?>
					<?php $parcinq_render_post_media( $parcinq_cityboy_posts[0]->ID, 'large', __( 'City Boy', 'parcinq-theme' ), 'fr-placeholder' ); ?>
				<?php else : ?>
					<div class="ph g5 fr-placeholder" data-label="<?php echo esc_attr__( 'City Boy', 'parcinq-theme' ); ?>"></div>
				<?php endif; ?>
				<div class="scrim"></div>
			</div>
			<div class="fr-text reveal">
				<span class="kicker"><?php echo esc_html__( 'A PARCINQ Franchise', 'parcinq-theme' ); ?></span>
				<div class="label"><?php echo esc_html__( 'City Boy', 'parcinq-theme' ); ?></div>
				<?php if ( ! empty( $parcinq_cityboy_posts ) ) : ?>
					<?php $parcinq_cityboy_chips = get_the_category( $parcinq_cityboy_posts[0]->ID ); ?>
					<p><?php echo esc_html( $parcinq_trim_excerpt( $parcinq_cityboy_posts[0], 16 ) ); ?></p>
					<?php if ( ! empty( $parcinq_cityboy_chips ) ) : ?>
						<div class="fr-chips">
							<?php foreach ( $parcinq_cityboy_chips as $parcinq_cityboy_chip ) : ?>
								<span class="chip"><?php echo esc_html( $parcinq_cityboy_chip->name ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<a href="<?php echo esc_url( get_permalink( $parcinq_cityboy_posts[0] ) ); ?>" class="btn ghost"><?php echo esc_html__( 'Explore City Boy', 'parcinq-theme' ); ?></a>
				<?php else : ?>
					<?php $parcinq_cityboy_chips = $parcinq_cityboy_data['category'] ? get_categories( array( 'parent' => $parcinq_cityboy_data['category']->term_id, 'hide_empty' => false ) ) : array(); ?>
					<p><?php echo esc_html__( 'City Boy stories will appear here once posts are assigned to the City Boy category.', 'parcinq-theme' ); ?></p>
					<?php if ( ! empty( $parcinq_cityboy_chips ) ) : ?>
						<div class="fr-chips">
							<?php foreach ( $parcinq_cityboy_chips as $parcinq_cityboy_chip ) : ?>
								<span class="chip"><?php echo esc_html( $parcinq_cityboy_chip->name ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<a href="<?php echo esc_url( $parcinq_cityboy_url ); ?>" class="btn ghost"><?php echo esc_html__( 'Explore City Boy', 'parcinq-theme' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section id="new">
		<div class="wrap">
			<div class="sec-head reveal">
				<div><span class="kicker"><?php echo esc_html__( 'Fresh Off the Feed', 'parcinq-theme' ); ?></span><h2><?php echo esc_html__( "What's New", 'parcinq-theme' ); ?></h2></div>
			</div>
			<div class="new-grid reveal">
				<?php foreach ( $parcinq_whats_new_posts as $parcinq_post ) : ?>
					<?php $parcinq_card_category = $parcinq_get_post_category( $parcinq_post->ID ); ?>
					<a class="new-card" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>"><?php $parcinq_render_post_media( $parcinq_post->ID, 'medium_large', __( 'Article', 'parcinq-theme' ) ); ?><span class="ck"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Article', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3><span class="by"><?php echo esc_html( get_the_date( '', $parcinq_post ) ); ?></span></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="alt" id="music">
		<div class="wrap">
			<div class="sec-head reveal">
				<div><span class="kicker"><?php echo esc_html__( 'On Repeat', 'parcinq-theme' ); ?></span><h2><?php echo esc_html__( 'Music', 'parcinq-theme' ); ?></h2></div>
				<div class="sub">
					<?php if ( $parcinq_music_data['category'] ) : ?>
						<?php foreach ( get_categories( array( 'parent' => $parcinq_music_data['category']->term_id, 'hide_empty' => false ) ) as $parcinq_child_category ) : ?><a class="sublink" href="<?php echo esc_url( $parcinq_category_link( $parcinq_child_category ) ); ?>"><?php echo esc_html( $parcinq_child_category->name ); ?></a><?php endforeach; ?>
						<a href="<?php echo esc_url( $parcinq_category_link( $parcinq_music_data['category'] ) ); ?>" class="seeall"><?php echo esc_html__( 'All Music', 'parcinq-theme' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<div class="three-grid reveal">
				<?php foreach ( $parcinq_music_posts as $parcinq_post ) : ?>
					<?php $parcinq_card_category = $parcinq_get_post_category( $parcinq_post->ID ); ?>
					<a class="mcard" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>"><?php $parcinq_render_post_media( $parcinq_post->ID, 'large', __( 'Music', 'parcinq-theme' ) ); ?><div class="scrim"></div><div class="meta"><span class="tag"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Music', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3></div></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section id="style">
		<div class="wrap">
			<div class="sec-head reveal">
				<div><span class="kicker"><?php echo esc_html__( 'The Edit', 'parcinq-theme' ); ?></span><h2><?php echo esc_html__( 'Style', 'parcinq-theme' ); ?></h2></div>
				<div class="sub">
					<?php if ( $parcinq_style_data['category'] ) : ?>
						<?php foreach ( get_categories( array( 'parent' => $parcinq_style_data['category']->term_id, 'hide_empty' => false ) ) as $parcinq_child_category ) : ?><a class="sublink" href="<?php echo esc_url( $parcinq_category_link( $parcinq_child_category ) ); ?>"><?php echo esc_html( $parcinq_child_category->name ); ?></a><?php endforeach; ?>
						<a href="<?php echo esc_url( $parcinq_category_link( $parcinq_style_data['category'] ) ); ?>" class="seeall"><?php echo esc_html__( 'All Style', 'parcinq-theme' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<div class="style-grid reveal">
				<?php foreach ( $parcinq_style_posts as $parcinq_post ) : ?>
					<?php $parcinq_card_category = $parcinq_get_post_category( $parcinq_post->ID ); ?>
					<a class="style-card" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>"><?php $parcinq_render_post_media( $parcinq_post->ID, 'large', __( 'Style', 'parcinq-theme' ) ); ?><div class="scrim"></div><div class="meta"><span class="tag"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Style', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3><p><?php echo esc_html( $parcinq_trim_excerpt( $parcinq_post, 16 ) ); ?></p></div></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="alt" id="culture">
		<div class="wrap">
			<div class="sec-head reveal">
				<div><span class="kicker"><?php echo esc_html__( 'Everything Else We Love', 'parcinq-theme' ); ?></span><h2><?php echo esc_html__( 'Culture', 'parcinq-theme' ); ?></h2></div>
				<?php if ( $parcinq_culture_data['category'] ) : ?><a href="<?php echo esc_url( $parcinq_category_link( $parcinq_culture_data['category'] ) ); ?>" class="seeall"><?php echo esc_html__( 'All Culture', 'parcinq-theme' ); ?></a><?php endif; ?>
			</div>
			<div class="culture-tabs reveal">
				<span class="ctab active"><?php echo esc_html__( 'All', 'parcinq-theme' ); ?></span><?php if ( $parcinq_culture_data['category'] ) : ?><?php foreach ( get_categories( array( 'parent' => $parcinq_culture_data['category']->term_id, 'hide_empty' => false ) ) as $parcinq_child_category ) : ?><a class="ctab" href="<?php echo esc_url( $parcinq_category_link( $parcinq_child_category ) ); ?>"><?php echo esc_html( $parcinq_child_category->name ); ?></a><?php endforeach; ?><?php endif; ?>
			</div>
			<div class="culture-grid reveal">
				<?php foreach ( $parcinq_culture_posts as $parcinq_post ) : ?>
					<?php $parcinq_card_category = $parcinq_get_post_category( $parcinq_post->ID ); ?>
					<a class="new-card" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>"><?php $parcinq_render_post_media( $parcinq_post->ID, 'medium_large', __( 'Culture', 'parcinq-theme' ) ); ?><span class="ck"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Culture', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3><span class="by"><?php echo esc_html( get_the_date( '', $parcinq_post ) ); ?></span></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="videos" id="videos">
		<div class="wrap">
			<div class="sec-head reveal">
				<div><span class="kicker light"><?php echo esc_html__( 'Press Play', 'parcinq-theme' ); ?></span><h2><?php echo esc_html__( 'Videos', 'parcinq-theme' ); ?></h2></div>
				<?php if ( $parcinq_videos_data['category'] ) : ?><a href="<?php echo esc_url( $parcinq_category_link( $parcinq_videos_data['category'] ) ); ?>" class="seeall"><?php echo esc_html__( 'All Videos', 'parcinq-theme' ); ?></a><?php endif; ?>
			</div>
			<div class="vid-grid reveal">
				<?php foreach ( $parcinq_videos_posts as $parcinq_index => $parcinq_post ) : ?>
					<?php $parcinq_card_category = $parcinq_get_post_category( $parcinq_post->ID ); ?>
					<?php if ( 0 === $parcinq_index ) : ?>
						<a class="vcard lead" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>"><?php $parcinq_render_post_media( $parcinq_post->ID, 'large', __( 'Video', 'parcinq-theme' ), 'video-fill' ); ?><div class="scrim"></div><div class="play"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg></div><div class="meta"><span class="vseries"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Video', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3></div></a>
						<?php if ( count( $parcinq_videos_posts ) > 1 ) : ?><div class="vid-col"><?php endif; ?>
					<?php else : ?>
						<a class="vcard small" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>"><?php $parcinq_render_post_media( $parcinq_post->ID, 'medium_large', __( 'Video', 'parcinq-theme' ) ); ?><div><span class="ck"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Video', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3></div></a>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if ( count( $parcinq_videos_posts ) > 1 ) : ?></div><?php endif; ?>
			</div>
		</div>
	</section>

	<section id="shop">
		<div class="wrap">
			<div class="shop-head reveal">
				<div><span class="kicker"><?php echo esc_html__( 'PARCINQ Marketplace', 'parcinq-theme' ); ?></span><h2><?php echo esc_html__( 'Shop', 'parcinq-theme' ); ?></h2></div>
				<a href="<?php echo esc_url( $parcinq_shop_url ); ?>" class="seeall"><?php echo esc_html__( 'Visit the Store', 'parcinq-theme' ); ?></a>
			</div>
			<p class="shop-note reveal"><?php echo esc_html__( 'Collectible print objects, photocards and editorial merch. Product integration will be connected in a later phase.', 'parcinq-theme' ); ?></p>
			<div class="shop-grid reveal">
				<div class="product"><div class="ph" data-label="<?php echo esc_attr__( 'Product', 'parcinq-theme' ); ?>"></div><h3><?php echo esc_html__( 'Product preview', 'parcinq-theme' ); ?></h3><div class="price"><?php echo esc_html__( 'Coming soon', 'parcinq-theme' ); ?></div><div class="add"><?php echo esc_html__( '+ Add to Cart', 'parcinq-theme' ); ?></div></div>
				<div class="product"><div class="ph" data-label="<?php echo esc_attr__( 'Product', 'parcinq-theme' ); ?>"></div><h3><?php echo esc_html__( 'Product preview', 'parcinq-theme' ); ?></h3><div class="price"><?php echo esc_html__( 'Coming soon', 'parcinq-theme' ); ?></div><div class="add"><?php echo esc_html__( '+ Add to Cart', 'parcinq-theme' ); ?></div></div>
				<div class="product"><div class="ph" data-label="<?php echo esc_attr__( 'Product', 'parcinq-theme' ); ?>"></div><h3><?php echo esc_html__( 'Product preview', 'parcinq-theme' ); ?></h3><div class="price"><?php echo esc_html__( 'Coming soon', 'parcinq-theme' ); ?></div><div class="add"><?php echo esc_html__( '+ Add to Cart', 'parcinq-theme' ); ?></div></div>
				<div class="product"><div class="ph" data-label="<?php echo esc_attr__( 'Product', 'parcinq-theme' ); ?>"></div><h3><?php echo esc_html__( 'Product preview', 'parcinq-theme' ); ?></h3><div class="price"><?php echo esc_html__( 'Coming soon', 'parcinq-theme' ); ?></div><div class="add"><?php echo esc_html__( '+ Add to Cart', 'parcinq-theme' ); ?></div></div>
			</div>
		</div>
	</section>

	<section class="news">
		<div class="wrap reveal">
			<span class="kicker"><?php echo esc_html__( "Don't Miss a Cover", 'parcinq-theme' ); ?></span>
			<h2><?php echo esc_html__( 'Join the PARCINQ List', 'parcinq-theme' ); ?></h2>
			<p><?php echo esc_html__( 'Covers, culture and the occasional secret drop, straight to your inbox. No noise.', 'parcinq-theme' ); ?></p>
			<div class="news-form">
				<input type="email" placeholder="<?php echo esc_attr__( 'your@email.com', 'parcinq-theme' ); ?>" aria-label="<?php echo esc_attr__( 'Email address', 'parcinq-theme' ); ?>">
				<button><?php echo esc_html__( 'Subscribe', 'parcinq-theme' ); ?></button>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
