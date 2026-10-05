<?php
/**
 * This site's own details — everything the (generic) Weavit Engine plugin
 * deliberately doesn't hardcode. Each value is only a starting default: once
 * something is saved in wp-admin (Weavit > Site Options, SEO > Schema, ...)
 * the saved value wins.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Weavit > Site Options starting values.
add_filter( 'bootg_default_options', function ( $defaults ) {
	return array_merge( $defaults, array(
		'phone'            => '08 6249 0115',
		'phone_link'       => '0862490115',
		'email'            => 'info@bookkeepingonthego.net.au',
		'facebook_url'     => 'https://www.facebook.com/bookkeeperperthwa/',
		'twitter_url'      => 'https://x.com/go_bookkeeping',
		'instagram_url'    => 'https://www.instagram.com/bookkeeping_on_the_go_perth',
		'linkedin_url'     => 'https://www.linkedin.com/in/natalie-adams-0159b582',
		'footer_tagline'   => 'Professional, experienced bookkeeping, payroll, and BAS services based in Perth and operating virtually across Australia.',
		'footer_copyright' => '&copy; ' . gmdate( 'Y' ) . ' Bookkeeping On The Go. All rights reserved.',
	) );
} );

// Weavit > SEO > Schema starting values: a Perth accounting/bookkeeping business.
add_filter( 'weavit_seo_defaults', function ( $defaults ) {
	return array_merge( $defaults, array(
		'schema_type' => 'AccountingService',
		'locality'    => 'Perth',
		'region'      => 'WA',
		'country'     => 'AU',
		'area_served' => "Perth, Western Australia\nAustralia",
	) );
} );

// The analyser's "fix the homepage title/description" link goes to this theme's Homepage Content screen.
add_filter( 'weavit_seo_homepage_fix_url', function () {
	return admin_url( 'themes.php?page=bootg-homepage-sections' );
} );

// Weavit > Bookkeeping overview: this site's compliance, resource and specialist pages.
add_filter( 'weavit_bookkeeping_pages', function () {
	return array(
		'Compliance &amp; resources' => array(
			'calculators'       => 'Calculators',
			'ato-compliance'    => 'ATO Compliance',
			'dates-to-remember' => 'Dates to Remember',
			'key-dates'         => 'Key Dates',
			'7-steps'           => '7 Steps to Increasing Profit',
			'resources'         => 'Templates &amp; Checklists',
		),
		'Specialist pages'           => array(
			'payroll-specialists-perth'       => 'Payroll Specialists in Perth',
			'public-trustee-reporting'        => 'Public Trustee Reporting',
			'nonprofit-compliance-accounting' => 'Nonprofit Compliance Accounting',
		),
	);
} );
