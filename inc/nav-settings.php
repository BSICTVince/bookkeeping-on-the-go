<?php
/**
 * Navigation tab on Site Options — theme-specific presentation settings
 * (which pages get a transparent, hero-overlaying nav; the logo's size)
 * that don't belong in the generic, reusable-across-themes Weavit Engine
 * Site Options screen. Registers itself into that screen's tab system via
 * the `bootg_site_options_tabs` filter rather than duplicating the page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOOTG_NAV_OPTION', 'bootg_nav_options' );

function bootg_nav_defaults() {
	return array(
		'transparent_homepage'   => false,
		'transparent_pages'      => array(),
		'logo_height'            => 52,
		'text_color'             => '#ffffff',
		'text_hover_color'       => '#643486',
	);
}

function bootg_get_nav_settings() {
	return wp_parse_args( get_option( BOOTG_NAV_OPTION, array() ), bootg_nav_defaults() );
}

add_action( 'admin_init', function () {
	register_setting( 'bootg_nav_options_group', BOOTG_NAV_OPTION, 'bootg_sanitize_nav_settings' );
} );

function bootg_sanitize_nav_settings( $input ) {
	$output                          = array();
	$output['transparent_homepage']  = ! empty( $input['transparent_homepage'] );
	$output['transparent_pages']     = array_map( 'absint', (array) ( $input['transparent_pages'] ?? array() ) );
	$height                          = absint( $input['logo_height'] ?? 52 );
	$output['logo_height']           = min( 200, max( 1, $height ?: 52 ) );
	$output['text_color']            = sanitize_hex_color( $input['text_color'] ?? '' ) ?: '#ffffff';
	$output['text_hover_color']      = sanitize_hex_color( $input['text_hover_color'] ?? '' ) ?: '#643486';
	return $output;
}

add_filter( 'bootg_site_options_tabs', function ( $tabs ) {
	$tabs['navigation'] = array( 'label' => 'Navigation', 'render' => 'bootg_render_nav_settings_tab' );
	return $tabs;
} );

/**
 * Is the current front-end request one where the transparent nav (overlaid
 * on the hero, solidifying on scroll) should be used instead of the
 * default solid nav? Only ever true on the front end; wp-admin/previews
 * inside the editor are unaffected.
 */
function bootg_nav_is_transparent_here() {
	$settings = bootg_get_nav_settings();
	if ( is_front_page() ) {
		return ! empty( $settings['transparent_homepage'] );
	}
	if ( is_page() ) {
		return in_array( get_queried_object_id(), $settings['transparent_pages'], true );
	}
	return false;
}

/**
 * Lets page content (the homepage hero especially) tell whether it's sitting
 * under an absolutely-positioned transparent nav instead of a normal in-flow
 * one, so it can add its own clearance -- the nav no longer pushes content
 * down the way a solid, sticky-from-the-top nav does.
 */
add_filter( 'body_class', function ( $classes ) {
	if ( bootg_nav_is_transparent_here() ) {
		$classes[] = 'bootg-transparent-nav';
	}
	return $classes;
} );

/**
 * Logo height as a CSS custom property, consumed by .navBar_logo /
 * .custom-logo in site.css via clamp() so it scales smoothly on narrower
 * screens instead of jumping at a fixed breakpoint.
 */
add_action( 'wp_head', function () {
	$settings = bootg_get_nav_settings();
	printf(
		'<style>:root{--bootg-logo-height:%dpx;--bootg-nav-text-color:%s;--bootg-nav-text-hover-color:%s}</style>',
		(int) $settings['logo_height'],
		esc_attr( $settings['text_color'] ),
		esc_attr( $settings['text_hover_color'] )
	);
} );

/** Native WP color picker on the Navigation tab only. */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( 'appearance_page_bootg-site-options' !== $hook || 'navigation' !== ( $_GET['tab'] ?? '' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only tab check.
		return;
	}
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_add_inline_script( 'wp-color-picker', 'jQuery(function($){$(".bootg-color-picker").wpColorPicker();});' );
} );

function bootg_render_nav_settings_tab() {
	$settings = bootg_get_nav_settings();
	$pages    = get_pages( array( 'sort_column' => 'menu_order, post_title' ) );
	?>
	<style>
		.bootg-nav-card{background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:24px 28px;margin-bottom:24px}
		.bootg-nav-card h2{margin-top:0;font-size:15px}
		.bootg-nav-card .description{margin-bottom:18px}
		.bootg-page-toggle{display:flex;align-items:center;gap:10px;padding:10px 4px;border-bottom:1px solid #f0f0f1}
		.bootg-page-toggle:last-child{border-bottom:0}
		.bootg-page-toggle label{display:flex;align-items:center;gap:10px;cursor:pointer;flex:1;font-weight:500}
		.bootg-page-toggle .dashicons{color:#8c8f94}
		.bootg-logo-slider-row{display:flex;align-items:center;gap:16px;max-width:520px}
		.bootg-logo-slider-row input[type=range]{flex:1}
		.bootg-logo-readout{min-width:64px;text-align:center;font-weight:600;font-size:14px;background:#f0f0f1;border-radius:6px;padding:6px 10px}
		.bootg-logo-preview{margin-top:18px;padding:24px;background:#2A1638;border-radius:8px;display:flex;align-items:center;justify-content:center}
		.bootg-logo-preview img{width:auto;display:block;transition:height .1s ease}
	</style>

	<form action="options.php" method="post">
		<?php settings_fields( 'bootg_nav_options_group' ); ?>

		<div class="bootg-nav-card">
			<h2>Transparent Navigation</h2>
			<p class="description">On selected pages, the nav bar sits transparently over the page's hero image instead of a solid white bar — it solidifies automatically once the visitor scrolls past the hero. Pick which pages use it, the same way you'd toggle a page-level setting in a builder.</p>

			<div class="bootg-page-toggle">
				<label>
					<input type="checkbox" name="<?php echo esc_attr( BOOTG_NAV_OPTION ); ?>[transparent_homepage]" value="1" <?php checked( $settings['transparent_homepage'] ); ?>>
					<span class="dashicons dashicons-admin-home"></span>
					Homepage
				</label>
			</div>
			<?php foreach ( $pages as $page ) : ?>
				<div class="bootg-page-toggle">
					<label>
						<input type="checkbox" name="<?php echo esc_attr( BOOTG_NAV_OPTION ); ?>[transparent_pages][]" value="<?php echo esc_attr( $page->ID ); ?>" <?php checked( in_array( $page->ID, $settings['transparent_pages'], true ) ); ?>>
						<span class="dashicons dashicons-admin-page"></span>
						<?php echo esc_html( $page->post_title ); ?>
					</label>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="bootg-nav-card">
			<h2>Transparent Nav Text Colors</h2>
			<p class="description">The menu text, phone number, and logo sit directly over a photo while the nav above is transparent — if a particular hero image makes the default white hard to read, adjust the colors here. Only affects pages with Transparent Navigation enabled; the solid nav (and the same nav once scrolled) always uses the theme's normal colors.</p>
			<table class="form-table" role="presentation" style="margin:0;">
				<tr>
					<th style="width:160px;padding-left:0;"><label for="bootg_text_color">Text color</label></th>
					<td><input type="text" id="bootg_text_color" name="<?php echo esc_attr( BOOTG_NAV_OPTION ); ?>[text_color]" value="<?php echo esc_attr( $settings['text_color'] ); ?>" class="bootg-color-picker" data-default-color="#ffffff"></td>
				</tr>
				<tr>
					<th style="width:160px;padding-left:0;"><label for="bootg_text_hover_color">Hover color</label></th>
					<td><input type="text" id="bootg_text_hover_color" name="<?php echo esc_attr( BOOTG_NAV_OPTION ); ?>[text_hover_color]" value="<?php echo esc_attr( $settings['text_hover_color'] ); ?>" class="bootg-color-picker" data-default-color="#643486"></td>
				</tr>
			</table>
		</div>

		<div class="bootg-nav-card">
			<h2>Logo Size</h2>
			<p class="description">Sets the logo's height at desktop width; it scales down smoothly on smaller screens rather than jumping at a breakpoint, so it always fits the nav bar.</p>
			<div class="bootg-logo-slider-row">
				<input type="range" id="bootg_logo_height" name="<?php echo esc_attr( BOOTG_NAV_OPTION ); ?>[logo_height]" min="1" max="200" step="1" value="<?php echo esc_attr( $settings['logo_height'] ); ?>" oninput="document.getElementById('bootg_logo_readout').textContent=this.value+'px';document.getElementById('bootg_logo_preview_img').style.height=this.value+'px';">
				<span class="bootg-logo-readout" id="bootg_logo_readout"><?php echo (int) $settings['logo_height']; ?>px</span>
			</div>
			<div class="bootg-logo-preview">
				<?php if ( has_custom_logo() ) :
					$logo_id  = get_theme_mod( 'custom_logo' );
					$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
					?>
					<img id="bootg_logo_preview_img" src="<?php echo esc_url( $logo_url ); ?>" alt="" style="height:<?php echo (int) $settings['logo_height']; ?>px;">
				<?php else : ?>
					<span style="color:#fff;font-family:sans-serif;font-weight:800;font-size:<?php echo (int) $settings['logo_height'] * 0.4; ?>px;" id="bootg_logo_preview_img"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
	<?php
}
