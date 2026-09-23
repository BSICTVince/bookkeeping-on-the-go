<?php
/**
 * Certification / partner-logo images (Xero, MYOB, QuickBooks, Dext,
 * Hubdoc, TPB) used on the homepage marquee and the About page. These are
 * the original site's badge graphics — sideloaded into the Media Library
 * (one click, from Site Options) so the site never depends on hotlinking
 * the old production domain. Until sideloaded, callers still get a working
 * image via the original URL as a fallback, so nothing ever renders broken.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOOTG_PARTNER_LOGO_OPTION', 'bootg_partner_logo_media' );

function bootg_partner_logo_defs() {
	static $defs = null;
	if ( null === $defs ) {
		$defs = bootg_load_json( BOOTG_DIR . '/content/partner-logos.json' );
	}
	return $defs;
}

/** The keys shown in the homepage marquee — a fixed subset, matching the original site's homepage (not every certification, just this row). */
function bootg_homepage_marquee_logo_keys() {
	return array( 'xero-gold-partner', 'quickbooks-proadvisor', 'myob-certified-consultant', 'dext-partner', 'hubdoc-partner', 'tpb-bas-agent' );
}

/** Local (sideloaded) URL for a partner logo if we have one, else the original external URL — always resolves to a working image. */
function bootg_get_partner_logo_url( $key ) {
	$defs = bootg_partner_logo_defs();
	if ( ! isset( $defs[ $key ] ) ) {
		return '';
	}
	$media = get_option( BOOTG_PARTNER_LOGO_OPTION, array() );
	if ( ! empty( $media[ $key ] ) ) {
		$url = wp_get_attachment_image_url( $media[ $key ], 'medium' );
		if ( $url ) {
			return $url;
		}
	}
	return $defs[ $key ]['url'];
}

function bootg_render_partner_logo_img( $key, $extra_class = '' ) {
	$defs = bootg_partner_logo_defs();
	if ( ! isset( $defs[ $key ] ) ) {
		return '';
	}
	$url   = bootg_get_partner_logo_url( $key );
	$label = $defs[ $key ]['label'];
	return '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $label ) . '" class="partner-logo' . ( $extra_class ? ' ' . esc_attr( $extra_class ) : '' ) . '" loading="lazy">';
}

/** One-click sideload of every partner-logo image into the Media Library. Safe to run again — skips ones already sideloaded. */
add_action( 'admin_post_bootg_migrate_partner_logos', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_migrate_partner_logos' );

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$media  = get_option( BOOTG_PARTNER_LOGO_OPTION, array() );
	$failed = 0;

	foreach ( bootg_partner_logo_defs() as $key => $def ) {
		if ( ! empty( $media[ $key ] ) && get_post( $media[ $key ] ) ) {
			continue;
		}
		$attachment_id = media_sideload_image( $def['url'], 0, $def['label'], 'id' );
		if ( is_wp_error( $attachment_id ) ) {
			$failed++;
			continue;
		}
		$media[ $key ] = $attachment_id;
	}

	update_option( BOOTG_PARTNER_LOGO_OPTION, $media );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_partner_logos' => $failed ? 'partial' : 'done' ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
