<?php
/**
 * Theme footer.
 *
 * @package Parcinq_Theme
 */

$parcinq_get_page_url = static function ( $parcinq_slug ) {
	$parcinq_page = get_page_by_path( $parcinq_slug );

	if ( $parcinq_page instanceof WP_Post && 'publish' === $parcinq_page->post_status ) {
		return get_permalink( $parcinq_page );
	}

	return home_url( '/' . trim( $parcinq_slug, '/' ) . '/' );
};

$parcinq_get_category_url = static function ( $parcinq_slug, $parcinq_fallback = '' ) {
	$parcinq_category = get_category_by_slug( $parcinq_slug );

	if ( $parcinq_category ) {
		return get_category_link( $parcinq_category );
	}

	return home_url( '/' . trim( $parcinq_fallback ? $parcinq_fallback : $parcinq_slug, '/' ) . '/' );
};

$parcinq_footer_description_fallback = __( 'An Asian pop culture publication. Music, fashion, beauty, culture and the personalities behind it all.', 'parcinq-theme' );
$parcinq_footer_description          = trim( (string) get_theme_mod( 'footer_description', $parcinq_footer_description_fallback ) );

if ( '' === $parcinq_footer_description ) {
	$parcinq_footer_description = $parcinq_footer_description_fallback;
}
$parcinq_read_links = array(
	array(
		'label' => __( "What's New", 'parcinq-theme' ),
		'url'   => $parcinq_get_page_url( 'whats-new' ),
	),
	array(
		'label' => __( 'Cover Stories', 'parcinq-theme' ),
		'url'   => $parcinq_get_category_url( 'cover-stories' ),
	),
	array(
		'label' => __( 'Music', 'parcinq-theme' ),
		'url'   => $parcinq_get_category_url( 'music' ),
	),
	array(
		'label' => __( 'Style', 'parcinq-theme' ),
		'url'   => $parcinq_get_category_url( 'style' ),
	),
	array(
		'label' => __( 'Culture', 'parcinq-theme' ),
		'url'   => $parcinq_get_category_url( 'culture' ),
	),
);

$parcinq_footer_logo_path = get_template_directory() . '/assets/images/parcinq-logo-white.png';
$parcinq_footer_logo_url  = get_template_directory_uri() . '/assets/images/parcinq-logo-white.png';

if ( file_exists( $parcinq_footer_logo_path ) ) {
	$parcinq_footer_logo_url = add_query_arg( 'ver', filemtime( $parcinq_footer_logo_path ), $parcinq_footer_logo_url );
}

$parcinq_social_links = array(
	array(
		'label' => __( 'Facebook', 'parcinq-theme' ),
		'url'   => 'https://facebook.com/parcinqmagazine',
	),
	array(
		'label' => __( 'Instagram', 'parcinq-theme' ),
		'url'   => 'https://instagram.com/parcinqmagazine',
	),
	array(
		'label' => __( 'X', 'parcinq-theme' ),
		'url'   => 'https://x.com/parcinqmagazine',
	),
	array(
		'label' => __( 'TikTok', 'parcinq-theme' ),
		'url'   => 'https://www.tiktok.com/@parcinqmagazine',
	),
	array(
		'label' => __( 'YouTube', 'parcinq-theme' ),
		'url'   => 'https://www.youtube.com/@ParcinqMagazine',
	),
);
?>
<footer class="site-footer">
	<div class="wrap foot-wrap">
		<div class="foot-top">
			<div class="foot-brand">
				<a class="foot-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<img class="foot-logo-image" src="<?php echo esc_url( $parcinq_footer_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</a>
				<p><?php echo esc_html( $parcinq_footer_description ); ?></p>
			</div>

			<nav class="foot-col" aria-label="<?php echo esc_attr__( 'Read', 'parcinq-theme' ); ?>">
				<h4><?php echo esc_html__( 'Read', 'parcinq-theme' ); ?></h4>
				<ul class="foot-menu">
					<?php foreach ( $parcinq_read_links as $parcinq_link ) : ?>
						<li><a href="<?php echo esc_url( $parcinq_link['url'] ); ?>"><?php echo esc_html( $parcinq_link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<nav class="foot-col" aria-label="<?php echo esc_attr__( 'Parcinq', 'parcinq-theme' ); ?>">
				<h4><?php echo esc_html__( 'PARCINQ', 'parcinq-theme' ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'fallback_cb'    => false,
						'menu_class'     => 'foot-menu',
						'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
					)
				);
				?>
			</nav>
		</div>

		<div class="foot-bot">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html__( 'PARCINQ. All rights reserved. Big Picture Asia Inc.', 'parcinq-theme' ); ?></span>
			<nav class="socials" aria-label="<?php echo esc_attr__( 'Social links', 'parcinq-theme' ); ?>">
				<?php foreach ( $parcinq_social_links as $parcinq_link ) : ?>
					<a href="<?php echo esc_url( $parcinq_link['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $parcinq_link['label'] ); ?></a>
				<?php endforeach; ?>
			</nav>
		</div>
	</div>
</footer>
<?php get_template_part( 'template-parts/newsletter-modal' ); ?>
<?php wp_footer(); ?>
</body>
</html>