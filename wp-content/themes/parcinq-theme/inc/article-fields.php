<?php
/**
 * Article hero ACF fields.
 *
 * @package Parcinq_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers article hero settings for native posts.
 */
function parcinq_register_article_hero_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_parcinq_article_hero_settings',
			'title'                 => 'Article Hero Settings',
			'fields'                => array(
				array(
					'key'           => 'field_parcinq_hero_kicker',
					'label'         => 'Hero Kicker',
					'name'          => 'hero_kicker',
					'type'          => 'text',
					'instructions'  => 'Plain text only. Do not enter HTML.',
					'required'      => 0,
					'wrapper'       => array( 'width' => '', 'class' => '', 'id' => '' ),
					'default_value' => '',
				),
				array(
					'key'           => 'field_parcinq_hero_title_before',
					'label'         => 'Hero Title Before',
					'name'          => 'hero_title_before',
					'type'          => 'text',
					'instructions'  => 'Plain text only. Do not enter HTML.',
					'required'      => 0,
					'wrapper'       => array( 'width' => '', 'class' => '', 'id' => '' ),
					'default_value' => '',
				),
				array(
					'key'           => 'field_parcinq_hero_title_emphasis',
					'label'         => 'Hero Title Emphasis',
					'name'          => 'hero_title_emphasis',
					'type'          => 'text',
					'instructions'  => 'Plain text only. This value is displayed in italic styling.',
					'required'      => 0,
					'wrapper'       => array( 'width' => '', 'class' => '', 'id' => '' ),
					'default_value' => '',
				),
				array(
					'key'           => 'field_parcinq_hero_title_after',
					'label'         => 'Hero Title After',
					'name'          => 'hero_title_after',
					'type'          => 'text',
					'instructions'  => 'Plain text only. Do not enter HTML.',
					'required'      => 0,
					'wrapper'       => array( 'width' => '', 'class' => '', 'id' => '' ),
					'default_value' => '',
				),
				array(
					'key'           => 'field_parcinq_photographer',
					'label'         => 'Photographer',
					'name'          => 'photographer',
					'type'          => 'text',
					'instructions'  => 'Plain text only. Do not enter HTML.',
					'required'      => 0,
					'wrapper'       => array( 'width' => '', 'class' => '', 'id' => '' ),
					'default_value' => '',
				),
				array(
					'key'           => 'field_parcinq_art_director',
					'label'         => 'Art Director',
					'name'          => 'art_director',
					'type'          => 'text',
					'instructions'  => 'Plain text only. Do not enter HTML.',
					'required'      => 0,
					'wrapper'       => array( 'width' => '', 'class' => '', 'id' => '' ),
					'default_value' => '',
				),
				array(
					'key'           => 'field_parcinq_stylist',
					'label'         => 'Stylist',
					'name'          => 'stylist',
					'type'          => 'text',
					'instructions'  => 'Plain text only. Do not enter HTML.',
					'required'      => 0,
					'wrapper'       => array( 'width' => '', 'class' => '', 'id' => '' ),
					'default_value' => '',
				),
				array(
					'key'           => 'field_parcinq_additional_credits',
					'label'         => 'Additional Credits',
					'name'          => 'additional_credits',
					'type'          => 'textarea',
					'instructions'  => 'Plain text only. Do not enter HTML.',
					'required'      => 0,
					'wrapper'       => array( 'width' => '', 'class' => '', 'id' => '' ),
					'default_value' => '',
					'new_lines'     => '',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'post',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'side',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);
}
add_action( 'acf/init', 'parcinq_register_article_hero_fields' );