<?php
/**
 * Renders the primary menu with the exact markup/classes the original
 * design's CSS and JS expect (navList / menu-item / dropdown / sub-menu),
 * fed from a real WP nav menu so it's editable at Appearance > Menus.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bootg_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="sub-menu">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$has_children = in_array( 'menu-item-has-children', $item->classes, true );

		$classes   = array( 'menu-item' );
		$classes[] = $has_children ? 'dropdown' : '';
		if ( in_array( 'current-menu-item', $item->classes, true ) || in_array( 'current-menu-ancestor', $item->classes, true ) ) {
			$classes[] = 'active';
		}
		$classes = array_filter( $classes );

		$output .= '<li class="' . esc_attr( implode( ' ', $classes ) ) . '">';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : ' title="' . esc_attr( $item->title ) . '"';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ' href="' . esc_url( $item->url ) . '"';

		$output .= '<a' . $attributes . '>' . esc_html( $item->title ) . '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}
