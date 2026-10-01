<?php
/**
 * Registers this theme as a "starter site" for the Weavit Engine's Starter
 * Sites importer (Weavit → Starter Sites) — wraps the existing Content
 * Tools actions (inc/content-tools.php, inc/seed-content.php, and friends)
 * into one ordered, re-runnable one-click import with live progress.
 *
 * Each step just calls the same function its Content Tools button already
 * calls — nothing here duplicates import logic, it only sequences it.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bootg_starter_site_steps() {
	return array(
		'seed_content'            => array(
			'label'  => 'Import Services, Integrations, Testimonials, Team',
			'run'    => 'bootg_seed_content',
			'format' => function ( $r ) { return $r . ' item(s) created.'; },
		),
		'fix_service_content'     => array(
			'label'  => 'Fill in full Service content',
			'run'    => 'bootg_fix_service_content',
			'format' => function ( $r ) { return $r . ' service(s) updated.'; },
		),
		'fix_integration_content' => array(
			'label'  => 'Fill in full Integration content',
			'run'    => 'bootg_fix_integration_content',
			'format' => function ( $r ) { return $r . ' integration(s) updated.'; },
		),
		'hero_image'              => array(
			'label'  => 'Download homepage hero image',
			'run'    => 'bootg_migrate_hero_image',
			'format' => function () { return 'Hero image ready.'; },
		),
		'partner_logos'           => array(
			'label'  => 'Download partner logos',
			'run'    => 'bootg_migrate_partner_logos',
			'format' => function ( $r ) { return 'partial' === $r ? 'Some logos failed to download — check your connection.' : 'Partner logos ready.'; },
		),
		'contact_page'            => array(
			'label'  => 'Create Contact page',
			'run'    => 'bootg_create_contact_page',
			'format' => 'bootg_starter_site_page_message',
		),
		'about_page'              => array(
			'label'  => 'Create About Us page',
			'run'    => 'bootg_create_about_page',
			'format' => 'bootg_starter_site_page_message',
		),
		'compliance_pages'        => array(
			'label'  => 'Create Compliance pages',
			'run'    => 'bootg_create_compliance_pages',
			'format' => 'bootg_starter_site_page_message',
		),
		'specialist_pages'        => array(
			'label'  => 'Create Specialist Area pages',
			'run'    => 'bootg_create_specialist_pages',
			'format' => 'bootg_starter_site_page_message',
		),
		'resource_pages'          => array(
			'label'  => 'Create Resource pages',
			'run'    => 'bootg_create_resource_pages',
			'format' => 'bootg_starter_site_page_message',
		),
		'legal_pages'             => array(
			'label'  => 'Create legal & service pages',
			'run'    => 'bootg_create_legal_pages',
			'format' => function ( $r ) { return 'error' === $r ? 'One or more pages failed.' : 'Legal pages ready.'; },
		),
		'blog_page'               => array(
			'label'  => 'Set up the Blog page',
			'run'    => 'bootg_setup_blog_page',
			'format' => function ( $r ) { return $r ? 'Blog page ready.' : 'Could not set up the Blog page.'; },
		),
		'blog_posts'              => array(
			'label'  => 'Import Blog posts',
			'run'    => 'bootg_seed_blog_posts',
			'format' => function ( $r ) { return $r . ' blog post(s) created.'; },
		),
		'menus'                   => array(
			'label'  => 'Build navigation menus',
			'run'    => 'bootg_seed_menus',
			'format' => function ( $r ) { return $r; },
		),
		'fix_menu_links'          => array(
			'label'  => 'Point menu links at the pages just created',
			'run'    => 'bootg_fix_stale_menu_links',
			'format' => function ( $r ) { return $r . ' menu link(s) repointed.'; },
		),
		'page_templates'          => array(
			'label'  => 'Apply the full-width page template',
			'run'    => 'bootg_fix_page_templates',
			'format' => function ( $r ) { return $r . ' page template(s) switched.'; },
		),
	);
}

function bootg_starter_site_page_message( $status ) {
	switch ( $status ) {
		case 'created':
			return 'Page created.';
		case 'exists':
			return 'Already exists — skipped.';
		default:
			return 'Could not create the page.';
	}
}

add_filter( 'weavit_starter_sites', function ( $sites ) {
	$steps_meta = array();
	foreach ( bootg_starter_site_steps() as $key => $step ) {
		$steps_meta[] = array( 'key' => $key, 'label' => $step['label'] );
	}

	$sites[] = array(
		'id'          => 'bootg',
		'title'       => 'Bookkeeping On The Go',
		'description' => 'The full starter site this theme ships with — services, partners, team, testimonials, blog posts, and every core page, wired up and ready to edit.',
		'preview_url' => home_url( '/' ),
		'steps'       => $steps_meta,
	);

	return $sites;
} );

add_filter( 'weavit_starter_site_run_step', function ( $result, $site, $step ) {
	if ( 'bootg' !== $site ) {
		return $result;
	}

	$steps = bootg_starter_site_steps();
	if ( ! isset( $steps[ $step ] ) ) {
		return new WP_Error( 'unknown_step', 'Unknown step.' );
	}

	$raw     = call_user_func( $steps[ $step ]['run'] );
	$message = call_user_func( $steps[ $step ]['format'], $raw );

	return array( 'message' => $message );
}, 10, 3 );
