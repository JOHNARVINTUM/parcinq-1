<?php
/**
 * Shared CINQtizen newsletter modal.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cinq-modal-backdrop" id="cinqNewsletterModal" data-cinq-modal hidden>
	<div class="cinq-modal-panel" role="dialog" aria-modal="true" aria-labelledby="cinq-modal-title" aria-describedby="cinq-modal-copy">
		<button class="cinq-modal-close" type="button" data-cinq-modal-close aria-label="<?php echo esc_attr__( 'Close newsletter signup', 'parcinq-theme' ); ?>">&times;</button>
		<div class="cinq-modal-content" data-cinq-modal-content>
			<span class="cinq-modal-kicker"><?php echo esc_html__( 'Become a CINQtizen', 'parcinq-theme' ); ?></span>
			<h2 id="cinq-modal-title"><?php echo esc_html__( 'Join the CINQtizens', 'parcinq-theme' ); ?></h2>
			<p id="cinq-modal-copy"><?php echo esc_html__( 'Covers, culture and the occasional secret drop, straight to your inbox. No noise.', 'parcinq-theme' ); ?></p>
			<form class="cinq-modal-form" data-cinq-modal-form method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="cinq-modal-email"><?php echo esc_html__( 'Email address', 'parcinq-theme' ); ?></label>
				<input id="cinq-modal-email" type="email" name="parcinq_newsletter_email" placeholder="<?php echo esc_attr__( 'your@email.com', 'parcinq-theme' ); ?>" autocomplete="email" required>
				<label class="news-hp" for="cinq-modal-company"><?php echo esc_html__( 'Company', 'parcinq-theme' ); ?></label>
				<input class="news-hp" id="cinq-modal-company" type="text" name="parcinq_newsletter_company" tabindex="-1" autocomplete="off">
				<input type="hidden" name="parcinq_newsletter_submit" value="1">
				<button type="submit"><?php echo esc_html__( 'Subscribe', 'parcinq-theme' ); ?></button>
			</form>
			<p class="cinq-modal-fine"><?php echo esc_html__( 'We will only use your email for PARCINQ updates.', 'parcinq-theme' ); ?></p>
			<p class="cinq-modal-status" data-cinq-modal-status role="status" aria-live="polite"></p>
		</div>
		<div class="cinq-modal-success" data-cinq-modal-success hidden>
			<span class="cinq-modal-kicker"><?php echo esc_html__( 'You are In', 'parcinq-theme' ); ?></span>
			<h2><?php echo esc_html__( 'Welcome, CINQtizen.', 'parcinq-theme' ); ?></h2>
			<p><?php echo esc_html__( 'See you when we drop.', 'parcinq-theme' ); ?></p>
		</div>
	</div>
</div>