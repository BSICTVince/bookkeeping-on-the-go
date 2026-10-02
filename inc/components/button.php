<?php
/**
 * Weavit Page Designer — Button component. Standalone block, usable on
 * its own or nested inside Hero (see hero.php). Renders the theme's
 * existing .btn classes so it matches every hand-coded button already
 * on the site.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	weavit_register_component( 'weavit/button', array(
		'api_version'     => 3,
		'title'           => 'Button',
		'description'     => 'A call-to-action button, styled to match the rest of the site.',
		'attributes'       => array(
			'text'       => array( 'type' => 'string', 'default' => 'Learn More' ),
			'url'        => array( 'type' => 'string', 'default' => '' ),
			'newTab'     => array( 'type' => 'boolean', 'default' => false ),
			'style'      => array( 'type' => 'string', 'default' => 'primary' ), // primary | outline | dark
		),
		'render_callback' => 'bootg_render_weavit_button',
	) );
} );

function bootg_render_weavit_button( $attributes ) {
	$text  = $attributes['text'] ?? 'Learn More';
	$url   = $attributes['url'] ?? '';
	$style = in_array( $attributes['style'] ?? 'primary', array( 'primary', 'outline', 'dark' ), true ) ? $attributes['style'] : 'primary';
	$blank = ! empty( $attributes['newTab'] );

	if ( '' === trim( $text ) ) {
		return '';
	}

	return sprintf(
		'<a href="%s" class="btn btn-%s px-7 py-3.5 text-base"%s>%s</a>',
		esc_url( $url ?: '#' ),
		esc_attr( $style ),
		$blank ? ' target="_blank" rel="noopener"' : '',
		esc_html( $text )
	);
}
