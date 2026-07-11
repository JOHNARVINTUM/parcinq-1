<?php
/**
 * Single post template.
 *
 * @package Parcinq_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$parcinq_get_field = function_exists( 'get_field' ) ? 'get_field' : null;

		$parcinq_categories = get_the_category();
		$parcinq_hero_kicker = $parcinq_get_field ? trim( (string) get_field( 'hero_kicker' ) ) : '';
		$parcinq_kicker = $parcinq_hero_kicker;

		if ( '' === $parcinq_kicker ) {
			$parcinq_kicker = ! empty( $parcinq_categories ) ? $parcinq_categories[0]->name : __( 'Article', 'parcinq-theme' );
		}

		$parcinq_title_before   = $parcinq_get_field ? trim( (string) get_field( 'hero_title_before' ) ) : '';
		$parcinq_title_emphasis = $parcinq_get_field ? trim( (string) get_field( 'hero_title_emphasis' ) ) : '';
		$parcinq_title_after    = $parcinq_get_field ? trim( (string) get_field( 'hero_title_after' ) ) : '';
		$parcinq_has_custom_title = '' !== $parcinq_title_before || '' !== $parcinq_title_emphasis || '' !== $parcinq_title_after;

		$parcinq_credit_fields = array(
			'photographer'       => __( 'Photography', 'parcinq-theme' ),
			'art_director'       => __( 'Art Direction', 'parcinq-theme' ),
			'stylist'            => __( 'Styling', 'parcinq-theme' ),
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
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<section class="art-hero">
				<?php if ( has_post_thumbnail() ) : ?>
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
							echo esc_html( implode( ' · ', $parcinq_credit_output ) );
							?>
						</div>
					<?php endif; ?>
				</div>
			</section>

			<div class="art-body">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>

	<section class="art-related" aria-label="<?php echo esc_attr__( 'Related articles', 'parcinq-theme' ); ?>">
		<div class="wrap">
			<div class="sec-head">
				<div>
					<div class="kicker"><?php echo esc_html__( 'Temporary Placeholder', 'parcinq-theme' ); ?></div>
					<h2><?php echo esc_html__( 'Related Stories', 'parcinq-theme' ); ?></h2>
				</div>
			</div>
			<div class="mosaic">
				<article class="cover-card m-half reveal">
					<div class="ph g2"></div><div class="scrim"></div>
					<div class="meta"><span class="tag"><?php echo esc_html__( 'Placeholder', 'parcinq-theme' ); ?></span><h3><?php echo esc_html__( 'Related story placeholder', 'parcinq-theme' ); ?></h3></div>
				</article>
				<article class="cover-card m-half reveal">
					<div class="ph g3"></div><div class="scrim"></div>
					<div class="meta"><span class="tag"><?php echo esc_html__( 'Placeholder', 'parcinq-theme' ); ?></span><h3><?php echo esc_html__( 'Related story placeholder', 'parcinq-theme' ); ?></h3></div>
				</article>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();