<?php
/**
 * Author archive template.
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_author = get_queried_object();

if ( ! $parcinq_author instanceof WP_User ) {
	$parcinq_author = get_userdata( (int) get_query_var( 'author' ) );
}

$parcinq_author_id      = $parcinq_author ? absint( $parcinq_author->ID ) : 0;
$parcinq_author_profile = ( $parcinq_author_id && function_exists( 'parcinq_get_contributor_profile' ) ) ? parcinq_get_contributor_profile( $parcinq_author_id ) : array();
$parcinq_author_name    = ! empty( $parcinq_author_profile['display_name'] ) ? $parcinq_author_profile['display_name'] : get_the_author_meta( 'display_name', $parcinq_author_id );
$parcinq_author_role    = ! empty( $parcinq_author_profile['role'] ) ? $parcinq_author_profile['role'] : __( 'PARCINQ Contributor', 'parcinq-theme' );
$parcinq_author_bio     = ! empty( $parcinq_author_profile['bio'] ) ? $parcinq_author_profile['bio'] : get_the_author_meta( 'description', $parcinq_author_id );
$parcinq_author_photo   = ! empty( $parcinq_author_profile['photo_id'] ) ? absint( $parcinq_author_profile['photo_id'] ) : 0;
$parcinq_social_links   = ! empty( $parcinq_author_profile['social_links'] ) ? $parcinq_author_profile['social_links'] : array();
$parcinq_story_count    = 0;
$parcinq_post_types     = function_exists( 'parcinq_get_author_post_types' ) ? parcinq_get_author_post_types() : array( 'post' );

foreach ( $parcinq_post_types as $parcinq_post_type ) {
	$parcinq_story_count += (int) count_user_posts( $parcinq_author_id, $parcinq_post_type, true );
}

$parcinq_avatar_alt = sprintf(
	/* translators: %s: Author display name. */
	__( 'Profile photo of %s', 'parcinq-theme' ),
	$parcinq_author_name
);
?>

<main id="primary" class="site-main author-page">
	<section class="author-hero">
		<div class="author-wrap">
			<div class="author-photo">
				<?php
				if ( $parcinq_author_photo ) {
					echo wp_get_attachment_image(
						$parcinq_author_photo,
						'large',
						false,
						array(
							'class' => 'author-photo-image',
							'alt'   => $parcinq_avatar_alt,
						)
					);
				} else {
					echo get_avatar(
						$parcinq_author_id,
						220,
						'',
						$parcinq_avatar_alt,
						array( 'class' => 'author-photo-image' )
					);
				}
				?>
			</div>
			<div class="author-info">
				<span class="kicker"><?php echo esc_html__( 'Contributor', 'parcinq-theme' ); ?></span>
				<h1><?php echo esc_html( $parcinq_author_name ); ?></h1>
				<p class="author-role"><?php echo esc_html( $parcinq_author_role ); ?></p>
				<?php if ( '' !== trim( (string) $parcinq_author_bio ) ) : ?>
					<div class="author-bio"><?php echo wp_kses_post( wpautop( $parcinq_author_bio ) ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $parcinq_social_links ) ) : ?>
					<nav class="author-social" aria-label="<?php echo esc_attr__( 'Author social links', 'parcinq-theme' ); ?>">
						<?php foreach ( $parcinq_social_links as $parcinq_social_link ) : ?>
							<a href="<?php echo esc_url( $parcinq_social_link['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( sprintf( __( '%1$s on %2$s', 'parcinq-theme' ), $parcinq_author_name, $parcinq_social_link['label'] ) ); ?>"><?php echo esc_html( $parcinq_social_link['label'] ); ?></a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>
				<p class="author-count">
					<?php
					printf(
						/* translators: %d: Number of published stories. */
						esc_html( _n( '%d published story', '%d published stories', $parcinq_story_count, 'parcinq-theme' ) ),
						absint( $parcinq_story_count )
					);
					?>
				</p>
			</div>
		</div>
	</section>

	<section class="author-stories" aria-labelledby="author-stories-title">
		<div class="wrap">
			<div class="sec-head reveal">
				<div>
					<span class="kicker"><?php echo esc_html__( 'Archive', 'parcinq-theme' ); ?></span>
					<h2 id="author-stories-title"><?php echo esc_html__( 'Stories by this author', 'parcinq-theme' ); ?></h2>
				</div>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="author-story-grid reveal">
					<?php while ( have_posts() ) : ?>
						<?php
						the_post();
						$parcinq_categories = get_the_category();
						$parcinq_category   = ! empty( $parcinq_categories ) ? $parcinq_categories[0] : null;
						$parcinq_thumb_id   = get_post_thumbnail_id();
						$parcinq_thumb_alt  = $parcinq_thumb_id ? trim( (string) get_post_meta( $parcinq_thumb_id, '_wp_attachment_image_alt', true ) ) : '';
						$parcinq_thumb_alt  = '' !== $parcinq_thumb_alt ? $parcinq_thumb_alt : get_the_title();
						?>
						<article <?php post_class( 'author-story-card' ); ?>>
							<a href="<?php echo esc_url( get_permalink() ); ?>">
								<div class="author-story-media">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php
										echo wp_get_attachment_image(
											$parcinq_thumb_id,
											'large',
											false,
											array(
												'class' => 'author-story-image',
												'alt'   => $parcinq_thumb_alt,
											)
										);
										?>
									<?php else : ?>
										<div class="ph g4" data-label="<?php echo esc_attr__( 'Article Image', 'parcinq-theme' ); ?>"></div>
									<?php endif; ?>
								</div>
								<div class="author-story-body">
									<?php if ( $parcinq_category ) : ?>
										<span class="ck"><?php echo esc_html( $parcinq_category->name ); ?></span>
									<?php endif; ?>
									<h3><?php echo esc_html( get_the_title() ); ?></h3>
									<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?></p>
									<span class="date"><?php echo esc_html( get_the_date() ); ?></span>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				</div>

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
				<div class="author-empty reveal">
					<h2><?php echo esc_html__( 'No published stories yet.', 'parcinq-theme' ); ?></h2>
					<p><?php echo esc_html__( 'Published articles assigned to this contributor will appear here automatically.', 'parcinq-theme' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();