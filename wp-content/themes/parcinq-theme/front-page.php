<?php
/**
 * Temporary coming-soon front page.
 *
 * @package Parcinq_Theme
 */

if ( ! function_exists( 'parcinq_coming_soon_body_class' ) ) {
	/**
	 * Add a body class for the temporary coming-soon landing page.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	function parcinq_coming_soon_body_class( $classes ) {
		$classes[] = 'parcinq-coming-soon-body';

		return $classes;
	}
}

add_filter( 'body_class', 'parcinq_coming_soon_body_class' );
get_header();
remove_filter( 'body_class', 'parcinq_coming_soon_body_class' );

$parcinq_logo_url = get_theme_file_uri( 'assets/images/parcinq-coming-soon-logo.png' );
?>

<main id="primary" class="coming-soon-page" aria-labelledby="coming-soon-title">
	<div class="coming-soon-grain" aria-hidden="true"></div>
	<section class="coming-soon-stage">
		<div class="coming-soon-inner">
			<img class="coming-soon-logo" src="<?php echo esc_url( $parcinq_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<div class="coming-soon-kicker"><?php echo esc_html__( 'Coming Soon', 'parcinq-theme' ); ?></div>
			<h1 id="coming-soon-title" class="coming-soon-title">
				<span><?php echo esc_html__( 'Something New', 'parcinq-theme' ); ?></span>
				<span class="em"><?php echo esc_html__( 'Is On the Way', 'parcinq-theme' ); ?></span>
			</h1>
			<p class="coming-soon-sub"><?php echo esc_html__( 'We are refreshing PARCINQ from the ground up. A new home for Music, Style and Culture is almost here.', 'parcinq-theme' ); ?></p>

			<div class="coming-soon-countdown" id="countdown" aria-label="<?php echo esc_attr__( 'Countdown to launch', 'parcinq-theme' ); ?>">
				<div class="coming-soon-cd-block"><span class="coming-soon-cd-num" id="cd-days">00</span><span class="coming-soon-cd-label"><?php echo esc_html__( 'Days', 'parcinq-theme' ); ?></span></div>
				<div class="coming-soon-cd-block"><span class="coming-soon-cd-num" id="cd-hours">00</span><span class="coming-soon-cd-label"><?php echo esc_html__( 'Hours', 'parcinq-theme' ); ?></span></div>
				<div class="coming-soon-cd-block"><span class="coming-soon-cd-num" id="cd-mins">00</span><span class="coming-soon-cd-label"><?php echo esc_html__( 'Minutes', 'parcinq-theme' ); ?></span></div>
				<div class="coming-soon-cd-block"><span class="coming-soon-cd-num" id="cd-secs">00</span><span class="coming-soon-cd-label"><?php echo esc_html__( 'Seconds', 'parcinq-theme' ); ?></span></div>
			</div>

			<form class="coming-soon-form" id="notify" action="#" method="post">
				<label class="screen-reader-text" for="coming-soon-email"><?php echo esc_html__( 'Email address', 'parcinq-theme' ); ?></label>
				<input id="coming-soon-email" name="email" type="email" required placeholder="<?php echo esc_attr__( 'Enter your email', 'parcinq-theme' ); ?>">
				<button type="submit"><?php echo esc_html__( 'Notify Me', 'parcinq-theme' ); ?></button>
			</form>
			<div class="coming-soon-thanks" id="thanks" hidden><?php echo esc_html__( 'You are on the list, CINQtizen. See you soon.', 'parcinq-theme' ); ?></div>

			<nav class="coming-soon-socials" aria-label="<?php echo esc_attr__( 'Social links', 'parcinq-theme' ); ?>">
				<a href="https://instagram.com/parcinqmagazine" target="_blank" rel="noopener" aria-label="<?php echo esc_attr__( 'Instagram', 'parcinq-theme' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.3 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.3 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1 .4-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.8-.3-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.3-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1-.4 2.2-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.1-1.1.1-1.7.2-2.1.4-.5.2-.9.4-1.3.8-.4.4-.6.8-.8 1.3-.2.4-.3 1-.4 2.1C2.6 9.9 2.6 10.3 2.6 12s0 2.1.1 3.3c.1 1.1.2 1.7.4 2.1.2.5.4.9.8 1.3.4.4.8.6 1.3.8.4.2 1 .3 2.1.4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1.1-.1 1.7-.2 2.1-.4.5-.2.9-.4 1.3-.8.4-.4.6-.8.8-1.3.2-.4.3-1 .4-2.1.1-1.2.1-1.6.1-3.3s0-2.1-.1-3.3c-.1-1.1-.2-1.7-.4-2.1-.2-.5-.4-.9-.8-1.3-.4-.4-.8-.6-1.3-.8-.4-.2-1-.3-2.1-.4-1.2-.1-1.6-.1-4.7-.1zm0 3.1a4.9 4.9 0 1 1 0 9.8 4.9 4.9 0 0 1 0-9.8zm0 8.1a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4zm6.2-8.3a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0z"/></svg></a>
				<a href="https://facebook.com/parcinqmagazine" target="_blank" rel="noopener" aria-label="<?php echo esc_attr__( 'Facebook', 'parcinq-theme' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg></a>
				<a href="https://x.com/parcinqmagazine" target="_blank" rel="noopener" aria-label="<?php echo esc_attr__( 'X', 'parcinq-theme' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.9 3h3.3l-7.2 8.3L23.5 21h-6.6l-5.2-6.8L5.7 21H2.4l7.7-8.8L1.8 3h6.8l4.7 6.2L18.9 3zm-1.2 16h1.8L7.1 4.8H5.2L17.7 19z"/></svg></a>
				<a href="https://www.youtube.com/@ParcinqMagazine" target="_blank" rel="noopener" aria-label="<?php echo esc_attr__( 'YouTube', 'parcinq-theme' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M23 7.5c-.1-1-.6-1.7-1.6-1.9C19.6 5.2 12 5.2 12 5.2s-7.6 0-9.4.4C1.6 5.8 1.1 6.5 1 7.5.8 9.3.8 12 .8 12s0 2.7.2 4.5c.1 1 .6 1.7 1.6 1.9 1.8.4 9.4.4 9.4.4s7.6 0 9.4-.4c1-.2 1.5-.9 1.6-1.9.2-1.8.2-4.5.2-4.5s0-2.7-.2-4.5zM9.7 15.3V8.7l6.3 3.3-6.3 3.3z"/></svg></a>
			</nav>
		</div>
		<footer class="coming-soon-footer">
			<span><?php echo esc_html__( '© 2026. Big Picture Asia Inc.', 'parcinq-theme' ); ?></span>
			<span class="coming-soon-dot" aria-hidden="true">·</span><a href="#"><?php echo esc_html__( 'Privacy', 'parcinq-theme' ); ?></a><span class="coming-soon-dot" aria-hidden="true">·</span><a href="#"><?php echo esc_html__( 'Terms', 'parcinq-theme' ); ?></a>
		</footer>
	</section>
</main>

<?php
get_footer();