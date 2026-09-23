<?php
/**
 * Wires this theme into GitHub Releases for update checks, via the Plugin
 * Update Checker library (works for themes too, despite the name) — mirrors
 * the same setup in the Weavit Engine plugin. Also shows the same
 * Elementor-style "update available" card, reusing the plugin's shared
 * renderer since this theme already requires Weavit Engine to function.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once BOOTG_DIR . '/lib/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

add_action( 'init', function () {
	$update_checker = PucFactory::buildUpdateChecker(
		'https://github.com/BSICTVince/bookkeeping-on-the-go',
		BOOTG_DIR . '/style.css',
		'bookkeeping-on-the-go'
	);
	// Tag-based checking: PUC finds the highest version-numbered git tag and
	// builds its download URL automatically -- a plain `git tag vX.Y.Z` + push
	// is enough. (No GitHub Release object or manually-uploaded zip needed --
	// that's a separate, stricter mode this theme doesn't use.)
	$update_checker->setBranch( 'main' );
} );

add_action( 'admin_notices', function () {
	if ( ! function_exists( 'bootg_render_update_notice_card' ) ) {
		return; // Weavit Engine plugin (which provides the card renderer) isn't active.
	}
	bootg_render_update_notice_card(
		'theme',
		get_stylesheet(),
		'Bookkeeping On The Go',
		BOOTG_URI . '/assets/images/theme-icon.png'
	);
} );
