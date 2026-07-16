<?php
/**
 * Reusable article author profile card.
 *
 * @package Parcinq_Theme
 */

$parcinq_author_id = isset( $args['author_id'] ) ? absint( $args['author_id'] ) : absint( get_post_field( 'post_author', get_the_ID() ) );

if ( ! $parcinq_author_id || ! function_exists( 'parcinq_get_contributor_profile' ) ) {
	return;
}

$parcinq_author_profile = parcinq_get_contributor_profile( $parcinq_author_id );

if ( empty( $parcinq_author_profile ) ) {
	return;
}

$parcinq_author_name       = $parcinq_author_profile['display_name'];
$parcinq_author_photo_id   = absint( $parcinq_author_profile['photo_id'] );
$parcinq_author_archive    = $parcinq_author_profile['archive_url'];
$parcinq_author_first_name = $parcinq_author_profile['first_name'];
$parcinq_avatar_alt        = sprintf(
	/* translators: %s: Author display name. */
	__( 'Profile photo of %s', 'parcinq-theme' ),
	$parcinq_author_name
);
?>
<footer class="na-author parcinq-author-profile art-author-profile">
	<a class="parcinq-author-profile__avatar" href="<?php echo esc_url( $parcinq_author_archive ); ?>">
		<?php
		if ( $parcinq_author_photo_id ) {
			echo wp_get_attachment_image(
				$parcinq_author_photo_id,
				'thumbnail',
				false,
				array(
					'class' => 'parcinq-author-profile__image',
					'alt'   => $parcinq_avatar_alt,
				)
			);
		} else {
			echo get_avatar(
				$parcinq_author_id,
				64,
				'',
				$parcinq_avatar_alt,
				array( 'class' => 'parcinq-author-profile__image' )
			);
		}
		?>
	</a>
	<div class="parcinq-author-profile__body na-author-text">
		<a class="parcinq-author-profile__name" href="<?php echo esc_url( $parcinq_author_archive ); ?>"><?php echo esc_html( $parcinq_author_name ); ?></a>
		<span class="parcinq-author-profile__role"><?php echo esc_html( $parcinq_author_profile['role'] ); ?></span>
		<?php if ( '' !== trim( (string) $parcinq_author_profile['bio'] ) ) : ?>
			<p><?php echo wp_kses_post( $parcinq_author_profile['bio'] ); ?></p>
		<?php endif; ?>
		<a href="<?php echo esc_url( $parcinq_author_archive ); ?>" class="parcinq-author-profile__more na-more">
			<?php
			printf(
				/* translators: %s: Author first name or display name. */
				esc_html__( 'More stories by %s', 'parcinq-theme' ),
				esc_html( $parcinq_author_first_name )
			);
			?> &rsaquo;
		</a>
	</div>
</footer>
