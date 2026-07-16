<?php
/**
 * Template Name: Editorial Preview
 *
 * Dynamic editorial homepage preview for Parcinq.
 *
 * @package Parcinq_Theme
 */

$parcinq_newsletter_result = function_exists( 'parcinq_handle_newsletter_signup' ) ? parcinq_handle_newsletter_signup() : array( 'status' => '', 'message' => '', 'email' => '' );

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
			'posts_per_page'      => 3,
			'post__in'            => $parcinq_sticky_posts,
			'post__not_in'        => array(),
			'ignore_sticky_posts' => false,
			'orderby'             => 'date',
			'order'               => 'DESC',
		)
	);
}

if ( count( $parcinq_hero_posts ) < 3 ) {
	$parcinq_hero_ids = wp_list_pluck( $parcinq_hero_posts, 'ID' );
	$parcinq_hero_posts = array_merge(
		$parcinq_hero_posts,
		$parcinq_get_posts(
			array(
				'posts_per_page' => 3 - count( $parcinq_hero_posts ),
				'post__not_in'   => array_map( 'absint', $parcinq_hero_ids ),
			)
		)
	);
}

$parcinq_hero_posts = array_slice( $parcinq_hero_posts, 0, 3 );
$parcinq_track_posts( $parcinq_hero_posts );

$parcinq_cover_data   = $parcinq_get_category_posts( 'cover-stories', 3 );
$parcinq_cover_posts  = $parcinq_cover_data['posts'];
$parcinq_track_posts( $parcinq_cover_posts );


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

$parcinq_whats_new_page = get_page_by_path( 'whats-new' );
$parcinq_whats_new_url  = $parcinq_whats_new_page instanceof WP_Post && 'publish' === $parcinq_whats_new_page->post_status ? get_permalink( $parcinq_whats_new_page ) : home_url( '/whats-new/' );
?>
<main id="primary" class="site-main">
	<section class="hero" id="top">
		<?php if ( ! empty( $parcinq_hero_posts ) ) : ?>
			<div class="hero-carousel">
				<?php foreach ( $parcinq_hero_posts as $parcinq_index => $parcinq_hero_post ) : ?>
					<?php
					$parcinq_hero_category       = $parcinq_get_post_category( $parcinq_hero_post->ID );
					$parcinq_hero_kicker         = '';
					$parcinq_hero_title_before   = '';
					$parcinq_hero_title_emphasis = '';
					$parcinq_hero_title_after    = '';

					if ( function_exists( 'get_field' ) ) {
						$parcinq_hero_kicker         = trim( (string) get_field( 'hero_kicker', $parcinq_hero_post->ID ) );
						$parcinq_hero_title_before   = trim( (string) get_field( 'hero_title_before', $parcinq_hero_post->ID ) );
						$parcinq_hero_title_emphasis = trim( (string) get_field( 'hero_title_emphasis', $parcinq_hero_post->ID ) );
						$parcinq_hero_title_after    = trim( (string) get_field( 'hero_title_after', $parcinq_hero_post->ID ) );
					}

					$parcinq_hero_has_title = '' !== $parcinq_hero_title_before || '' !== $parcinq_hero_title_emphasis || '' !== $parcinq_hero_title_after;

					if ( '' === $parcinq_hero_kicker ) {
						$parcinq_hero_kicker = $parcinq_hero_category ? $parcinq_hero_category->name : __( 'Article', 'parcinq-theme' );
					}
					?>
					<div class="hero-slide<?php echo 0 === $parcinq_index ? ' active' : ''; ?>">
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
								<div class="byline">
									<?php
									printf(
										/* translators: %s: Post author name. */
										esc_html__( 'by %s', 'parcinq-theme' ),
										esc_html( get_the_author_meta( 'display_name', $parcinq_hero_post->post_author ) )
									);
									?>
								</div>
								<span class="btn"><?php echo esc_html( 0 === $parcinq_index ? __( 'Enter the Story', 'parcinq-theme' ) : __( 'Read the Story', 'parcinq-theme' ) ); ?>
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
								</span>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
				<?php if ( count( $parcinq_hero_posts ) > 1 ) : ?>
					<div class="hero-dots">
						<?php foreach ( $parcinq_hero_posts as $parcinq_index => $parcinq_hero_post ) : ?>
							<button class="hero-dot<?php echo 0 === $parcinq_index ? ' active' : ''; ?>" data-slide="<?php echo esc_attr( (string) $parcinq_index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Story %d', 'parcinq-theme' ), $parcinq_index + 1 ) ); ?>"></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
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

	<section id="new">
		<div class="wrap">
			<div class="sec-head reveal">
				<div><span class="kicker"><?php echo esc_html__( 'Fresh Off the Feed', 'parcinq-theme' ); ?></span><h2><?php echo esc_html__( "What's New", 'parcinq-theme' ); ?></h2></div>
				<a href="<?php echo esc_url( $parcinq_whats_new_url ); ?>" class="seeall"><?php echo esc_html__( 'Latest', 'parcinq-theme' ); ?></a>
			</div>
			<div class="new-grid reveal">
				<?php foreach ( $parcinq_whats_new_posts as $parcinq_post ) : ?>
					<?php $parcinq_card_category = $parcinq_get_post_category( $parcinq_post->ID ); ?>
					<a class="new-card" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>"><?php $parcinq_render_post_media( $parcinq_post->ID, 'medium_large', __( 'Article', 'parcinq-theme' ) ); ?><span class="ck"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Article', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3><span class="by"><?php echo esc_html( get_the_author_meta( 'display_name', $parcinq_post->post_author ) ); ?></span></a>
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
					<a class="new-card" href="<?php echo esc_url( get_permalink( $parcinq_post ) ); ?>"><?php $parcinq_render_post_media( $parcinq_post->ID, 'medium_large', __( 'Culture', 'parcinq-theme' ) ); ?><span class="ck"><?php echo esc_html( $parcinq_card_category ? $parcinq_card_category->name : __( 'Culture', 'parcinq-theme' ) ); ?></span><h3><?php echo esc_html( get_the_title( $parcinq_post ) ); ?></h3><span class="by"><?php echo esc_html( get_the_author_meta( 'display_name', $parcinq_post->post_author ) ); ?></span></a>
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

	<section class="news" id="newsletter">
		<div class="wrap reveal">
			<span class="kicker"><?php echo esc_html__( 'Become a CINQtizen', 'parcinq-theme' ); ?></span>
			<h2><?php echo esc_html__( 'Join the CINQtizens', 'parcinq-theme' ); ?></h2>
			<p><?php echo esc_html__( 'Covers, culture and the occasional secret drop, straight to your inbox. No noise.', 'parcinq-theme' ); ?></p>
			<form class="news-form" method="post" action="<?php echo esc_url( home_url( '/#newsletter' ) ); ?>">
				<?php wp_nonce_field( 'parcinq_newsletter_signup', 'parcinq_newsletter_nonce' ); ?>
				<label class="screen-reader-text" for="parcinq-newsletter-email"><?php echo esc_html__( 'Email address', 'parcinq-theme' ); ?></label>
				<input id="parcinq-newsletter-email" type="email" name="parcinq_newsletter_email" placeholder="<?php echo esc_attr__( 'your@email.com', 'parcinq-theme' ); ?>" value="<?php echo esc_attr( $parcinq_newsletter_result['email'] ); ?>" autocomplete="email" required>
				<label class="news-hp" for="parcinq-newsletter-company"><?php echo esc_html__( 'Company', 'parcinq-theme' ); ?></label>
				<input class="news-hp" id="parcinq-newsletter-company" type="text" name="parcinq_newsletter_company" tabindex="-1" autocomplete="off">
				<button type="submit" name="parcinq_newsletter_submit" value="1"><?php echo esc_html__( 'Subscribe', 'parcinq-theme' ); ?></button>
			</form>
			<?php if ( ! empty( $parcinq_newsletter_result['message'] ) ) : ?>
				<div class="news-message news-message-<?php echo esc_attr( $parcinq_newsletter_result['status'] ); ?>" role="status">
					<?php echo esc_html( $parcinq_newsletter_result['message'] ); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();



