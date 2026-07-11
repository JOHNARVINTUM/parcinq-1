<?php
/**
 * Theme footer.
 *
 * @package Parcinq_Theme
 */

?>
<footer class="site-footer">
	<nav class="footer-navigation" aria-label="<?php echo esc_attr__( 'Footer menu', 'parcinq-theme' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'container'      => false,
				'fallback_cb'    => false,
			)
		);
		?>
	</nav>
</footer>
<?php wp_footer(); ?>
</body>
</html>
