<?php
/**
 * Footer customizer — "Footer" panel (layout/background/padding) plus 4
 * native widget-area columns (footer-column-1..4), so content comes from
 * WordPress's own Widgets Customizer screen (Appearance > Customize >
 * Widgets) instead of more hardcoded PHP. The footer used to be 100%
 * hand-coded; this is the first pass at making it editable without a
 * developer, same spirit as Site Options for the rest of the site.
 *
 * v1 scope: layout/background/padding controls use the default `refresh`
 * transport (the preview iframe reloads on change) rather than a custom
 * postMessage live-preview script — correct and simple, just not
 * instant. Can be upgraded to instant preview later if it's worth it.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOOTG_FOOTER_COLUMN_IDS', array( 'footer-column-1', 'footer-column-2', 'footer-column-3', 'footer-column-4' ) );

add_action( 'widgets_init', function () {
	$labels = array( 'Footer Column 1', 'Footer Column 2', 'Footer Column 3', 'Footer Column 4' );
	foreach ( BOOTG_FOOTER_COLUMN_IDS as $i => $id ) {
		register_sidebar( array(
			'name'          => $labels[ $i ],
			'id'            => $id,
			'description'   => 'Shown as column ' . ( $i + 1 ) . ' in the site footer.',
			'before_widget' => '<div class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="text-sm font-bold tracking-widest uppercase mb-4 text-white/90">',
			'after_title'   => '</h5>',
		) );
	}
} );

/**
 * One-time default population so switching to widget-driven columns
 * doesn't leave an existing live site's footer blank. Only runs once
 * (guarded by an option flag) and only fills columns that are still
 * completely empty — never overwrites anything an admin has already
 * set up, including a partially-customized footer.
 */
add_action( 'widgets_init', function () {
	if ( get_option( 'bootg_footer_widgets_seeded' ) ) {
		return;
	}

	$sidebars_widgets = wp_get_sidebars_widgets();

	// Scans the actual widget_{id_base} option for the highest numeric
	// instance key and hands out sequential numbers from there — doesn't
	// rely on $wp_widget_factory's internal counter, which never advances
	// from these direct option writes (only from the normal add-widget UI).
	$next_numbers = array();
	$next_widget_number = function ( $id_base ) use ( &$next_numbers ) {
		if ( ! isset( $next_numbers[ $id_base ] ) ) {
			$existing = (array) get_option( 'widget_' . $id_base, array() );
			$max      = 0;
			foreach ( array_keys( $existing ) as $key ) {
				if ( is_numeric( $key ) ) {
					$max = max( $max, (int) $key );
				}
			}
			$next_numbers[ $id_base ] = $max;
		}
		return ++$next_numbers[ $id_base ];
	};

	// Column 1: Business Info.
	if ( empty( $sidebars_widgets['footer-column-1'] ) ) {
		$n = $next_widget_number( 'bootg_footer_brand' );
		$existing = (array) get_option( 'widget_bootg_footer_brand', array() );
		$existing[ $n ] = array();
		$existing['_multiwidget'] = 1;
		update_option( 'widget_bootg_footer_brand', $existing );
		$sidebars_widgets['footer-column-1'] = array( 'bootg_footer_brand-' . $n );
	}

	// Column 2: Quick Links (reuses the existing "footer" nav menu location's menu, if one is assigned).
	if ( empty( $sidebars_widgets['footer-column-2'] ) ) {
		$locations = get_nav_menu_locations();
		if ( ! empty( $locations['footer'] ) ) {
			$n = $next_widget_number( 'nav_menu' );
			$existing = (array) get_option( 'widget_nav_menu', array() );
			$existing[ $n ] = array( 'title' => 'Quick Links', 'nav_menu' => $locations['footer'] );
			$existing['_multiwidget'] = 1;
			update_option( 'widget_nav_menu', $existing );
			$sidebars_widgets['footer-column-2'] = array( 'nav_menu-' . $n );
		}
	}

	// Column 3: Specialist Areas — no nav menu location existed for this before
	// (it was 3 hardcoded links), so create a real menu for it if one doesn't
	// already exist, then assign it the same way as Quick Links.
	if ( empty( $sidebars_widgets['footer-column-3'] ) ) {
		$menu = get_term_by( 'name', 'Specialist Areas', 'nav_menu' );
		if ( ! $menu ) {
			$menu_id = wp_create_nav_menu( 'Specialist Areas' );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Payroll Specialists in Perth',
				'menu-item-url'   => home_url( '/payroll-specialists-perth/' ),
				'menu-item-status' => 'publish',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Public Trustee Reporting',
				'menu-item-url'   => home_url( '/public-trustee-reporting/' ),
				'menu-item-status' => 'publish',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Nonprofit Compliance Accounting',
				'menu-item-url'   => home_url( '/nonprofit-compliance-accounting/' ),
				'menu-item-status' => 'publish',
			) );
		} else {
			$menu_id = $menu->term_id;
		}
		$n = $next_widget_number( 'nav_menu' );
		$existing = (array) get_option( 'widget_nav_menu', array() );
		$existing[ $n ] = array( 'title' => 'Specialist Areas', 'nav_menu' => $menu_id );
		$existing['_multiwidget'] = 1;
		update_option( 'widget_nav_menu', $existing );
		$sidebars_widgets['footer-column-3'] = array( 'nav_menu-' . $n );
	}

	// Column 4: Newsletter signup.
	if ( empty( $sidebars_widgets['footer-column-4'] ) ) {
		$n = $next_widget_number( 'bootg_footer_newsletter' );
		$existing = (array) get_option( 'widget_bootg_footer_newsletter', array() );
		$existing[ $n ] = array();
		$existing['_multiwidget'] = 1;
		update_option( 'widget_bootg_footer_newsletter', $existing );
		$sidebars_widgets['footer-column-4'] = array( 'bootg_footer_newsletter-' . $n );
	}

	// Retire the old single-column "footer-widgets" sidebar (no longer
	// registered) so it stops showing up as an empty leftover area.
	unset( $sidebars_widgets['footer-widgets'] );

	wp_set_sidebars_widgets( $sidebars_widgets );
	update_option( 'bootg_footer_widgets_seeded', 1 );
} );

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_panel( 'bootg_footer', array(
		'title'    => 'Footer',
		'priority' => 200,
	) );

	$wp_customize->add_section( 'bootg_footer_design', array(
		'title' => 'Footer Design',
		'panel' => 'bootg_footer',
	) );

	$wp_customize->add_setting( 'bootg_footer_layout', array(
		'default'           => 'default',
		'sanitize_callback' => function ( $value ) {
			return in_array( $value, array( 'default', 'boxed', 'full' ), true ) ? $value : 'default';
		},
	) );
	$wp_customize->add_control( 'bootg_footer_layout', array(
		'label'   => 'Container Structure',
		'section' => 'bootg_footer_design',
		'type'    => 'radio',
		'choices' => array(
			'default' => 'Default (max-width, centered)',
			'boxed'   => 'Boxed (narrower, extra side padding)',
			'full'    => 'Full Width',
		),
	) );

	$wp_customize->add_setting( 'bootg_footer_background', array(
		'default'           => '#2A1638',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bootg_footer_background', array(
		'label'   => 'Container Background',
		'section' => 'bootg_footer_design',
	) ) );

	$wp_customize->add_setting( 'bootg_footer_padding_top', array(
		'default'           => 64,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'bootg_footer_padding_top', array(
		'label'       => 'Padding Top (px)',
		'section'     => 'bootg_footer_design',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 0, 'max' => 200 ),
	) );

	$wp_customize->add_setting( 'bootg_footer_padding_bottom', array(
		'default'           => 32,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'bootg_footer_padding_bottom', array(
		'label'       => 'Padding Bottom (px)',
		'section'     => 'bootg_footer_design',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 0, 'max' => 200 ),
	) );
} );

/** Container class for the chosen layout. */
function bootg_footer_container_class() {
	switch ( get_theme_mod( 'bootg_footer_layout', 'default' ) ) {
		case 'boxed':
			return 'max-w-5xl mx-auto px-6 sm:px-10 lg:px-12';
		case 'full':
			return 'w-full px-4 sm:px-6 lg:px-8';
		default:
			return 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8';
	}
}

function bootg_footer_style_attr() {
	$bg      = get_theme_mod( 'bootg_footer_background', '#2A1638' );
	$padding = sprintf( '%dpx 0 %dpx', (int) get_theme_mod( 'bootg_footer_padding_top', 64 ), (int) get_theme_mod( 'bootg_footer_padding_bottom', 32 ) );
	return 'background-color:' . esc_attr( $bg ) . ';padding:' . esc_attr( $padding ) . ';';
}
