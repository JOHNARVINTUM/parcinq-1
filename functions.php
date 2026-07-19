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
require_once get_template_directory() . '/inc/search.php';


/**
 * Adds a Customizer section for the footer description.
 *
 * @param WP_Customize_Manager $parcinq_wp_customize Customizer instance.
 */
function parcinq_customize_footer_description( $parcinq_wp_customize ) {
	$parcinq_default_footer_description = __( 'An Asian pop culture publication. Music, fashion, beauty, culture and the personalities behind it all.', 'parcinq-theme' );

	$parcinq_wp_customize->add_section(
		'parcinq_footer',
		array(
			'title'    => __( 'Parcinq Footer', 'parcinq-theme' ),
			'priority' => 160,
		)
	);

	$parcinq_wp_customize->add_setting(
		'footer_description',
		array(
			'default'           => $parcinq_default_footer_description,
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);

	$parcinq_wp_customize->add_control(
		'footer_description',
		array(
			'label'   => __( 'Footer Description', 'parcinq-theme' ),
			'section' => 'parcinq_footer',
			'type'    => 'textarea',
		)
	);
}
add_action( 'customize_register', 'parcinq_customize_footer_description' );