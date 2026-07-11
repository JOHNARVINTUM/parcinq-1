<?php
/**
 * Theme footer.
 *
 * @package Parcinq_Theme
 */

?>
<footer>
	<div class="wrap">
		<div class="foot-top">
			<div>
				<div class="foot-logo">PARCIN<span class="five">Q</span></div>
				<p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
			</div>
			<div class="foot-col">
				<h4><?php echo esc_html__( 'Menu', 'parcinq-theme' ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'fallback_cb'    => false,
						'items_wrap'     => '%3$s',
					)
				);
				?>
			</div>
			<div class="foot-col">
				<h4><?php echo esc_html__( 'Social', 'parcinq-theme' ); ?></h4>
				<div class="socials"></div>
			</div>
		</div>
		<div class="foot-bot">
			<span>
				<?php
				printf(
					/* translators: 1: Current year. 2: Site name. */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'parcinq-theme' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</span>
			<div class="socials"></div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>