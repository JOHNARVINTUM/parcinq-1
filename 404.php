<?php
/**
 * 404 template.
 *
 * @package Parcinq_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
	<section class="error-404 not-found">
		<header class="page-header">
			<h1 class="page-title"><?php echo esc_html__( 'Page not found.', 'parcinq-theme' ); ?></h1>
		</header>
		<div class="page-content">
			<p><?php echo esc_html__( 'The requested page could not be found.', 'parcinq-theme' ); ?></p>
		</div>
	</section>
</main>

<?php
get_footer();
