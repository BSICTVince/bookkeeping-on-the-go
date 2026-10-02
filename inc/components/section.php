<?php
/**
 * Weavit Page Designer — Section component. A full-width (or contained)
 * layout wrapper with an optional background color and anchor id —
 * holds any inner blocks, typically one or more Hero components.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	weavit_register_component( 'weavit/section', array(
		'api_version'     => 3,
		'title'           => 'Section',
		'description'     => 'A full-width layout section — background color, optional anchor, holds other components.',
		'attributes'      => array(
			'backgroundColor' => array( 'type' => 'string', 'default' => '' ),
			'fullWidth'       => array( 'type' => 'boolean', 'default' => true ),
			'anchorId'        => array( 'type' => 'string', 'default' => '' ),
		),
		'render_callback' => 'bootg_render_weavit_section',
	) );
} );

function bootg_render_weavit_section( $attributes, $content ) {
	$bg         = $attributes['backgroundColor'] ?? '';
	$full_width = ! isset( $attributes['fullWidth'] ) || $attributes['fullWidth'];
	$anchor     = $attributes['anchorId'] ?? '';

	$inner_class = $full_width ? 'w-full px-4 sm:px-6 lg:px-8' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8';

	return sprintf(
		'<section class="relative overflow-hidden"%s%s><div class="%s">%s</div></section>',
		$anchor ? ' id="' . esc_attr( $anchor ) . '"' : '',
		$bg ? ' style="background-color:' . esc_attr( $bg ) . ';"' : '',
		esc_attr( $inner_class ),
		$content
	);
}
