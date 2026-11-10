<?php
/**
 * 301 redirects from the previous site's URLs (still indexed by Google, e.g.
 * /about-us/ and /contact-us/ sitelinks) to their equivalents on this theme.
 *
 * Only fires when WordPress would otherwise serve a 404, so a real page or
 * post at the same path always wins. Map is old path => new path.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bootg_legacy_redirect_map() {
	$map = array(
		// Core pages.
		'about-us'               => '/about/',
		'contact-us'             => '/contact/',
		'meet-our-team'          => '/team/',
		'meet-natalie'           => '/team/',
		'privacy'                => '/privacy-policy/',

		// Services.
		'accounting'             => '/services/',
		'accounting-bookkeeping' => '/services/bookkeeping-reconciliations/',
		'bas-and-ias'            => '/services/bas-ias-lodgments/',

		// Partners / software.
		'xero'                   => '/partners/xero/',
		'xero-resources'         => '/partners/xero/',
		'myob'                   => '/partners/myob/',
		'myob-resources'         => '/partners/myob/',
		'myob-accountedge'       => '/partners/myob/',
		'myob-accountright'      => '/partners/myob/',
		'myob-essentials'        => '/partners/myob/',
		'myob-invoices'          => '/partners/myob/',
		'quickbooks-online'      => '/partners/quickbooks-online/',
		'quickbooks-resources'   => '/partners/quickbooks-online/',
		'dext'                   => '/partners/dext/',
		'hubdoc'                 => '/partners/hubdoc/',
		'mechanicdesk'           => '/partners/mechanicdesk/',
		'cloud-accounting'       => '/partners/cloud-accounting/',

		// Compliance & resources.
		'compliance-due-dates'   => '/key-dates/',
		'tax-compliance'         => '/ato-compliance/',
		'tax'                    => '/ato-compliance/',
		'business-guides'        => '/guides/',
		'covid-19'               => '/resources/',
		'covid-19-resources'     => '/resources/',

		// Old blog hubs and categories.
		'bookkeeping-blog'       => '/blog/',
		'business-updates'       => '/blog/',
		'firm-news'              => '/blog/',
		'weekly-digests'         => '/blog/',
		'government-updates'     => '/blog/',
		'navigating-cash-flow-challenges' => '/cash-flow/navigating-cash-flow-challenges/',
	);

	// Old business-guide topic hubs -> the Business Guides archive.
	$guide_topics = array(
		'boost-your-business', 'collecting-your-money-faster', 'customer-success',
		'cybersecurity-tips-for-business', 'demystifying-inflation', 'driving-sales-and-revenue',
		'finance-funding', 'financial-tips', 'find-and-fix-bottlenecks', 'growth',
		'how-crowdfunding-works', 'identifying-warning-triggers', 'impact-of-discounting',
		'improving-break-even', 'leadership', 'marketing', 'operations', 'personal-finance',
		'planning-the-year-ahead', 'productivity', 'protecting-your-business-online',
		'raising-equity-capital', 'restructuring-if-cash-stressed', 'sales-marketing',
		'save-time-with-technology', 'social-media-marketing-basics', 'starting-up',
		'succession-exit', 'surviving-supply-chain-disruption', 'technology',
		'the-importance-of-bookkeeping', 'unlock-sales-success', 'why-online-reviews-matter',
		'workable-new-year-resolutions', 'cash',
	);
	foreach ( $guide_topics as $slug ) {
		$map[ $slug ] = '/guides/';
	}

	return apply_filters( 'bootg_legacy_redirect_map', $map );
}

add_action( 'template_redirect', function () {
	if ( ! is_404() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' ); // phpcs:ignore
	$path = preg_replace( '#/embed$#', '', strtolower( $path ) );
	$map  = bootg_legacy_redirect_map();
	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301, 'Bookkeeping On The Go' );
		exit;
	}
}, 1 );
