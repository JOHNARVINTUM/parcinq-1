<?php
/**
 * Parcinq Theme functions.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/article-fields.php';
require_once get_template_directory() . '/inc/contributors.php';
require_once get_template_directory() . '/inc/launch-curtain.php';
require_once get_template_directory() . '/inc/newsletter.php';


/**
 * Adds a small Customizer fallback for the footer description.
 *
 * @param WP_Customize_Manager $parcinq_wp_customize Customizer instance.
 */
function parcinq_customize_footer_description( $parcinq_wp_customize ) {
	$parcinq_wp_customize->add_setting(
		'footer_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$parcinq_wp_customize->add_control(
		'footer_description',
		array(
			'label'       => __( 'Footer Description', 'parcinq-theme' ),
			'description' => __( 'Optional footer description. Leave blank to use the default PARCINQ sentence.', 'parcinq-theme' ),
			'section'     => 'title_tagline',
			'type'        => 'textarea',
		)
	);
}
add_action( 'customize_register', 'parcinq_customize_footer_description' );