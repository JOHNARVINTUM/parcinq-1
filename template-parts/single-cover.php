<?php
/**
 * Cover-style single post template part.
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

$parcinq_get_field  = function_exists( 'get_field' ) ? 'get_field' : null;
$parcinq_post_id    = get_the_ID();
$parcinq_categories = get_the_category();
$parcinq_show_featured_image = true;

if ( metadata_exists( 'post', $parcinq_post_id, 'show_featured_image' ) ) {
	$parcinq_show_featured_image = '0' !== (string) get_post_meta( $parcinq_post_id, 'show_featured_image', true );
}
$parcinq_author_id          = (int) get_the_author_meta( 'ID' );
$parcinq_author_name        = get_the_author_meta( 'display_name', $parcinq_author_id );
$parcinq_author_description = get_the_author_meta( 'description', $parcinq_author_id );
$parcinq_author_url         = get_author_posts_url( $parcinq_author_id );
$parcinq_author_first_name  = strtok( $parcinq_author_name, ' ' ) ?: $parcinq_author_name;

$parcinq_hero_kicker = $parcinq_get_field ? trim( (string) get_field( 'hero_kicker' ) ) : '';
$parcinq_kicker      = $parcinq_hero_kicker;

if ( '' === $parcinq_kicker ) {
	$parcinq_display_category = $parcinq_get_display_category( $parcinq_post_id );
	$parcinq_kicker           = $parcinq_display_category ? $parcinq_display_category->name : __( 'Article', 'parcinq-theme' );
}

$parcinq_title_before     = $parcinq_get_field ? trim( (string) get_field( 'hero_title_before' ) ) : '';
$parcinq_title_emphasis   = $parcinq_get_field ? trim( (string) get_field( 'hero_title_emphasis' ) ) : '';
$parcinq_title_after      = $parcinq_get_field ? trim( (string) get_field( 'hero_title_after' ) ) : '';
$parcinq_has_custom_title = '' !== $parcinq_title_before || '' !== $parcinq_title_emphasis || '' !== $parcinq_title_after;

$parcinq_credit_fields = array(
	'photographer'       => __( 'Photography', 'parcinq-theme' ),
	'art_director'       => __( 'Art Direction', 'parcinq-theme' ),
	'stylist'            => __( 'Words', 'parcinq-theme' ),
	'additional_credits' => __( 'Additional Credits', 'parcinq-theme' ),
);
$parcinq_credits = array();

if ( $parcinq_get_field ) {
	foreach ( $parcinq_credit_fields as $parcinq_field_name => $parcinq_label ) {
		$parcinq_value = trim( (string) get_field( $parcinq_field_name ) );
		if ( '' !== $parcinq_value ) {
			$parcinq_credits[] = array(
				'label' => $parcinq_label,
				'value' => $parcinq_value,
			);
		}
	}
}

$parcinq_related_ids  = array();
$parcinq_category_ids = wp_list_pluck( $parcinq_categories, 'term_id' );

if ( ! empty( $parcinq_category_ids ) ) {
	$parcinq_category_query = new WP_Query(
		array(
			'cat'                 => implode( ',', array_map( 'absint', $parcinq_category_ids ) ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'post__not_in'        => array( $parcinq_post_id ),
			'posts_per_page'      => 3,
		)
	);

	if ( $parcinq_category_query->have_posts() ) {
		while ( $parcinq_category_query->have_posts() ) {
			$parcinq_category_query->the_post();
			$parcinq_related_ids[] = get_the_ID();
		}
		wp_reset_postdata();
	}
}

$parcinq_related_ids = array_values( array_unique( array_map( 'absint', $parcinq_related_ids ) ) );

if ( count( $parcinq_related_ids ) < 3 ) {
	$parcinq_fill_query = new WP_Query(
		array(
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'post__not_in'        => array_merge( array( $parcinq_post_id ), $parcinq_related_ids ),
			'posts_per_page'      => 3 - count( $parcinq_related_ids ),
		)
	);

	if ( $parcinq_fill_query->have_posts() ) {
		while ( $parcinq_fill_query->have_posts() ) {
			$parcinq_fill_query->the_post();
			$parcinq_related_ids[] = get_the_ID();
		}
		wp_reset_postdata();
	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-cover-article' ); ?>>
	<section class="art-hero">
		<?php if ( $parcinq_show_featured_image && has_post_thumbnail() ) : ?>
			<div class="ph art-hero-media">
				<?php
				the_post_thumbnail(
					'full',
					array(
						'class' => 'art-hero-image',
						'alt'   => the_title_attribute( array( 'echo' => false ) ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<div class="ph g7"></div>
		<?php endif; ?>
		<div class="scrim"></div>
		<div class="wrap inner">
			<div class="kicker light"><?php echo esc_html( $parcinq_kicker ); ?></div>
			<h1>
				<?php if ( $parcinq_has_custom_title ) : ?>
					<?php if ( '' !== $parcinq_title_before ) : ?><?php echo esc_html( $parcinq_title_before ); ?><?php endif; ?><?php if ( '' !== $parcinq_title_emphasis ) : ?><?php echo '' !== $parcinq_title_before ? ' ' : ''; ?><em><?php echo esc_html( $parcinq_title_emphasis ); ?></em><?php endif; ?><?php if ( '' !== $parcinq_title_after ) : ?><?php echo ( '' !== $parcinq_title_before || '' !== $parcinq_title_emphasis ) ? ' ' : ''; ?><?php echo esc_html( $parcinq_title_after ); ?><?php endif; ?>
				<?php else : ?>
					<?php echo esc_html( get_the_title() ); ?>
				<?php endif; ?>
			</h1>
			<?php if ( ! empty( $parcinq_credits ) ) : ?>
				<div class="art-credits">
					<?php
					$parcinq_credit_output = array();
					foreach ( $parcinq_credits as $parcinq_credit ) {
						$parcinq_credit_output[] = sprintf(
							/* translators: 1: Credit label. 2: Credit value. */
							esc_html__( '%1$s: %2$s', 'parcinq-theme' ),
							esc_html( $parcinq_credit['label'] ),
							esc_html( $parcinq_credit['value'] )
						);
					}
                    echo wp_kses( implode( ' <span aria-hidden="true">&middot;</span> ', $parcinq_credit_output ), array( 'span' => array( 'aria-hidden' => true ) ) );
					?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<div class="art-body">
		<?php the_content(); ?>
	</div>
	<?php get_template_part( 'template-parts/article-author', null, array( 'author_id' => get_post_field( 'post_author', get_the_ID() ) ) ); ?>
</article>

<?php if ( ! empty( $parcinq_related_ids ) ) : ?>
	<section class="art-related" aria-label="<?php echo esc_attr__( 'Related articles', 'parcinq-theme' ); ?>">
		<div class="wrap">
			<div class="sec-head">
				<div>
					<h2><?php echo esc_html__( 'Related Stories', 'parcinq-theme' ); ?></h2>
				</div>
			</div>
			<div class="mosaic art-related-grid">
				<?php foreach ( $parcinq_related_ids as $parcinq_related_id ) : ?>
					<?php
					$parcinq_related_category = $parcinq_get_display_category( $parcinq_related_id );
					$parcinq_thumbnail_id     = get_post_thumbnail_id( $parcinq_related_id );
					$parcinq_thumbnail_alt    = $parcinq_thumbnail_id ? trim( (string) get_post_meta( $parcinq_thumbnail_id, '_wp_attachment_image_alt', true ) ) : '';
					$parcinq_thumbnail_alt    = '' !== $parcinq_thumbnail_alt ? $parcinq_thumbnail_alt : get_the_title( $parcinq_related_id );
					?>
					<a class="cover-card art-related-card reveal" href="<?php echo esc_url( get_permalink( $parcinq_related_id ) ); ?>">
						<?php if ( has_post_thumbnail( $parcinq_related_id ) ) : ?>
							<div class="ph">
								<?php
								echo wp_get_attachment_image(
									$parcinq_thumbnail_id,
									'large',
									false,
									array(
										'class' => 'archive-card-image',
										'alt'   => $parcinq_thumbnail_alt,
									)
								);
								?>
							</div>
						<?php else : ?>
							<div class="ph g4" data-label="<?php echo esc_attr__( 'Article Image', 'parcinq-theme' ); ?>"></div>
						<?php endif; ?>
						<div class="scrim"></div>
						<div class="meta">
							<?php if ( $parcinq_related_category ) : ?>
								<span class="tag"><?php echo esc_html( $parcinq_related_category->name ); ?></span>
							<?php endif; ?>
							<h3><?php echo esc_html( get_the_title( $parcinq_related_id ) ); ?></h3>
							<span class="date"><?php echo esc_html( get_the_date( '', $parcinq_related_id ) ); ?></span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
