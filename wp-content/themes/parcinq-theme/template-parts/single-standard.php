<?php
/**
 * Standard news-style single post template part.
 *
 * @package Parcinq_Theme
 */

$parcinq_get_display_category = get_query_var( 'parcinq_get_display_category' );
if ( ! is_callable( $parcinq_get_display_category ) ) {
	$parcinq_get_display_category = static function ( $parcinq_post_id ) {
		$parcinq_categories = get_the_category( $parcinq_post_id );
		return empty( $parcinq_categories ) ? null : $parcinq_categories[0];
	};
}

$parcinq_post_id          = get_the_ID();
$parcinq_display_category = $parcinq_get_display_category( $parcinq_post_id );
$parcinq_thumbnail_id     = get_post_thumbnail_id( $parcinq_post_id );
$parcinq_thumbnail_alt    = $parcinq_thumbnail_id ? trim( (string) get_post_meta( $parcinq_thumbnail_id, '_wp_attachment_image_alt', true ) ) : '';
$parcinq_thumbnail_alt    = '' !== $parcinq_thumbnail_alt ? $parcinq_thumbnail_alt : get_the_title();
$parcinq_caption          = '';
$parcinq_permalink        = get_permalink();
$parcinq_author_id          = (int) get_the_author_meta( 'ID' );
$parcinq_author_name        = get_the_author_meta( 'display_name', $parcinq_author_id );
$parcinq_author_description = get_the_author_meta( 'description', $parcinq_author_id );
$parcinq_author_url         = get_author_posts_url( $parcinq_author_id );
$parcinq_author_first_name  = strtok( $parcinq_author_name, ' ' ) ?: $parcinq_author_name;

if ( $parcinq_thumbnail_id ) {
	$parcinq_attachment = get_post( $parcinq_thumbnail_id );
	$parcinq_caption    = $parcinq_attachment ? trim( (string) $parcinq_attachment->post_excerpt ) : '';
}

$parcinq_breadcrumb_terms = array();
if ( $parcinq_display_category ) {
	$parcinq_ancestor_ids = array_reverse( get_ancestors( $parcinq_display_category->term_id, 'category' ) );
	foreach ( $parcinq_ancestor_ids as $parcinq_ancestor_id ) {
		$parcinq_ancestor = get_category( $parcinq_ancestor_id );
		if ( $parcinq_ancestor && ! is_wp_error( $parcinq_ancestor ) ) {
			$parcinq_breadcrumb_terms[] = $parcinq_ancestor;
		}
	}
	$parcinq_breadcrumb_terms[] = $parcinq_display_category;
}

$parcinq_tags = get_the_tags();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'news-article' ); ?>>
	<div class="na-wrap">
		<nav class="breadcrumb" aria-label="<?php echo esc_attr__( 'Article breadcrumb', 'parcinq-theme' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'Home', 'parcinq-theme' ); ?></a>
			<?php foreach ( $parcinq_breadcrumb_terms as $parcinq_term ) : ?>
				<span aria-hidden="true">&rsaquo;</span>
				<a href="<?php echo esc_url( get_category_link( $parcinq_term ) ); ?>"><?php echo esc_html( $parcinq_term->name ); ?></a>
			<?php endforeach; ?>
		</nav>

		<h1 class="na-title"><?php echo esc_html( get_the_title() ); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="na-deck"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>

		<div class="na-meta">
			<span class="na-by">
				<?php
				printf(
					/* translators: %s: Post author name. */
					esc_html__( 'By %s', 'parcinq-theme' ),
					esc_html( get_the_author() )
				);
				?>
			</span>
			<span class="na-date"><?php echo esc_html( get_the_date() ); ?></span>
		</div>

		<div class="na-share" aria-label="<?php echo esc_attr__( 'Share this article', 'parcinq-theme' ); ?>">
			<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $parcinq_permalink ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr__( 'Share on Facebook', 'parcinq-theme' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7A10 10 0 0022 12z"/></svg>
			</a>
			<a href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . rawurlencode( $parcinq_permalink ) . '&text=' . rawurlencode( get_the_title() ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr__( 'Share on X', 'parcinq-theme' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.9 3h3.3l-7.2 8.3L23.5 21h-6.6l-5.2-6.8L5.7 21H2.4l7.7-8.8L1.8 3h6.8l4.7 6.2L18.9 3zm-1.2 16h1.8L7.1 4.8H5.2L17.7 19z"/></svg>
			</a>
			<a href="<?php echo esc_url( $parcinq_permalink ); ?>" aria-label="<?php echo esc_attr__( 'Copy link', 'parcinq-theme' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M10 13a5 5 0 007.5.5l3-3A5 5 0 0013.5 3.5l-1.7 1.7M14 11a5 5 0 00-7.5-.5l-3 3A5 5 0 0010.5 20.5l1.7-1.7" fill="none" stroke="currentColor" stroke-width="2"/></svg>
			</a>
		</div>

		<figure class="na-hero">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="ph">
					<?php
					the_post_thumbnail(
						'large',
						array(
							'class' => 'na-hero-image',
							'alt'   => $parcinq_thumbnail_alt,
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="ph g5" data-label="<?php echo esc_attr__( 'Lead Image', 'parcinq-theme' ); ?>"></div>
			<?php endif; ?>
			<?php if ( '' !== $parcinq_caption ) : ?>
				<figcaption><?php echo esc_html( $parcinq_caption ); ?></figcaption>
			<?php endif; ?>
		</figure>

		<div class="na-body">
			<?php the_content(); ?>
		</div>

		<?php if ( ! empty( $parcinq_tags ) ) : ?>
			<div class="na-tags" aria-label="<?php echo esc_attr__( 'Article tags', 'parcinq-theme' ); ?>">
				<?php foreach ( $parcinq_tags as $parcinq_tag ) : ?>
					<a class="na-tag" href="<?php echo esc_url( get_tag_link( $parcinq_tag ) ); ?>"><?php echo esc_html( $parcinq_tag->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<footer class="na-author parcinq-author-profile">
			<a class="parcinq-author-profile__avatar" href="<?php echo esc_url( $parcinq_author_url ); ?>">
				<?php
				echo get_avatar(
					$parcinq_author_id,
					64,
					'',
					sprintf(
						/* translators: %s: Author display name. */
						__( 'Profile photo of %s', 'parcinq-theme' ),
						$parcinq_author_name
					),
					array( 'class' => 'parcinq-author-profile__image' )
				);
				?>
			</a>
			<div class="parcinq-author-profile__body">
				<a class="parcinq-author-profile__name" href="<?php echo esc_url( $parcinq_author_url ); ?>"><?php echo esc_html( $parcinq_author_name ); ?></a>
				<span class="parcinq-author-profile__role"><?php echo esc_html__( 'Parcinq Contributor', 'parcinq-theme' ); ?></span>
				<?php if ( '' !== trim( (string) $parcinq_author_description ) ) : ?>
					<p><?php echo esc_html( $parcinq_author_description ); ?></p>
				<?php endif; ?>
				<a href="<?php echo esc_url( $parcinq_author_url ); ?>" class="parcinq-author-profile__more">
					<?php
					printf(
						/* translators: %s: Post author first name or display name. */
						esc_html__( 'More stories by %s', 'parcinq-theme' ),
						esc_html( $parcinq_author_first_name )
					);
					?> &rsaquo;
				</a>
			</div>
		</footer>
	</div>
</article>
