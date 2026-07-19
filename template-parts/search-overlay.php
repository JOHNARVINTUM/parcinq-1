<?php
/**
 * Full-screen search overlay.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$parcinq_search_query = get_search_query();
?>
<div class="search-back" id="parcinqSearchOverlay" role="dialog" aria-modal="true" aria-labelledby="parcinq-search-title" hidden>
	<div class="search-top">
		<div class="search-inner">
			<a class="search-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php echo esc_html__( 'PARCINQ', 'parcinq-theme' ); ?>
			</a>
			<button class="search-close" type="button" data-search-close aria-label="<?php echo esc_attr__( 'Close search', 'parcinq-theme' ); ?>">&times;</button>
		</div>
	</div>

	<div class="search-body">
		<div class="search-inner">
			<h2 class="screen-reader-text" id="parcinq-search-title"><?php echo esc_html__( 'Search PARCINQ', 'parcinq-theme' ); ?></h2>
			<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="parcinq-search-input"><?php echo esc_html__( 'Search PARCINQ', 'parcinq-theme' ); ?></label>
				<input class="search-field" id="parcinq-search-input" type="search" name="s" value="<?php echo esc_attr( $parcinq_search_query ); ?>" placeholder="<?php echo esc_attr__( 'Search PARCINQ...', 'parcinq-theme' ); ?>" autocomplete="off" data-search-input>
				<button class="search-submit" type="submit"><?php echo esc_html__( 'Search', 'parcinq-theme' ); ?></button>
			</form>
			<div class="search-results" aria-live="polite" data-search-results>
				<p class="search-hint"><?php echo esc_html__( 'Start typing to search stories and sections', 'parcinq-theme' ); ?></p>
			</div>
		</div>
	</div>
</div>