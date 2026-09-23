<?php
/**
 * One-click migration seeder — pulls the real copy from the original static
 * site into the new CPTs so the theme isn't empty. Definitions live in
 * content/{services,integrations,testimonials,team-members,*-content-fixes}.json;
 * the generic importer (bootg_import_cpt_items()/bootg_import_content_fixes(),
 * Weavit Engine plugin) creates/fills them idempotently. Triggered once from
 * the Content Tools page. Safe to click more than once (skips existing titles).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bootg_fix_service_content() {
	return bootg_import_content_fixes( 'service', bootg_load_json( BOOTG_DIR . '/content/service-content-fixes.json' ) );
}

add_action( 'admin_post_bootg_fix_service_content', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_fix_service_content' );

	$fixed = bootg_fix_service_content();

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_service_content_fixed' => $fixed ),
		admin_url( 'themes.php' )
	) );
	exit;
} );

function bootg_fix_integration_content() {
	return bootg_import_content_fixes( 'integration', bootg_load_json( BOOTG_DIR . '/content/integration-content-fixes.json' ) );
}

add_action( 'admin_post_bootg_fix_integration_content', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_fix_integration_content' );

	$fixed = bootg_fix_integration_content();

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_integration_content_fixed' => $fixed ),
		admin_url( 'themes.php' )
	) );
	exit;
} );

add_action( 'admin_post_bootg_seed_content', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_seed_content' );

	$created = 0;
	$created += bootg_import_cpt_items( 'service', bootg_load_json( BOOTG_DIR . '/content/services.json' ) );
	$created += bootg_import_cpt_items( 'integration', bootg_load_json( BOOTG_DIR . '/content/integrations.json' ) );
	$created += bootg_import_cpt_items( 'testimonial', bootg_load_json( BOOTG_DIR . '/content/testimonials.json' ) );
	$created += bootg_import_cpt_items( 'team_member', bootg_load_json( BOOTG_DIR . '/content/team-members.json' ) );

	$redirect = add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_seed_created' => $created ),
		admin_url( 'themes.php' )
	);
	wp_safe_redirect( $redirect );
	exit;
} );

/**
 * One-click "fix stale menu links" — bootg_seed_menus() only had real pages
 * to link to for some items on first run; everything else got a "#"
 * placeholder. As those pages got built later in separate site-options
 * actions (About Us, Contact, 7 Steps, Calculators, Key Dates, Templates &
 * Checklists, ATO Compliance, Dates to Remember), the menu items were never
 * revisited. This repoints exactly those still-"#" items, matched by title
 * — never by hardcoded post ID, since nav-menu-item IDs are just ordinary
 * auto-increment post IDs and are not stable across two WordPress installs
 * (a hardcoded ID from one site's database is essentially a random number
 * on another). Only leaf items (no children of their own) are touched, so
 * the true dropdown headers (About Us, Services, Partners, Resources,
 * Compliance Resources — which never had one single destination, matching
 * the original static site) are left alone. Existing parent/position are
 * preserved untouched — only the URL is corrected. Safe to run again —
 * skips any item whose URL already matches the target.
 */
function bootg_fix_stale_menu_links() {
	// title => target URL (home_url()'d at call time since these must
	// reflect the live site's own domain, not wherever this was written).
	$targets = array(
		'About Us'                     => home_url( '/about/' ),
		'7 steps to increasing profit' => home_url( '/7-steps/' ),
		'Calculators'                  => home_url( '/calculators/' ),
		'Key Dates'                    => home_url( '/key-dates/' ),
		'Templates & Checklists'       => home_url( '/resources/' ),
		'ATO Compliance'               => home_url( '/ato-compliance/' ),
		'Dates to Remember'            => home_url( '/dates-to-remember/' ),
		'Contact'                      => home_url( '/contact/' ),
		'How to nominate us as your authorised agent' => 'https://bookkeepingonthego.net.au/resources/how-to-nominate-us-as-your-authorised-agent/',
	);

	$fixed = 0;
	foreach ( wp_get_nav_menus() as $menu ) {
		$items = wp_get_nav_menu_items( $menu->term_id );
		if ( ! $items ) {
			continue;
		}
		$parent_ids = array();
		foreach ( $items as $item ) {
			if ( $item->menu_item_parent ) {
				$parent_ids[ (int) $item->menu_item_parent ] = true;
			}
		}
		foreach ( $items as $item ) {
			if ( isset( $parent_ids[ $item->ID ] ) ) {
				continue; // Has its own children — it's a dropdown header, not a link to fix.
			}
			if ( ! isset( $targets[ $item->title ] ) || $targets[ $item->title ] === $item->url ) {
				continue;
			}
			wp_update_nav_menu_item( $menu->term_id, $item->ID, array(
				'menu-item-title'     => $item->title,
				'menu-item-url'       => $targets[ $item->title ],
				'menu-item-status'    => 'publish',
				'menu-item-type'      => 'custom',
				'menu-item-parent-id' => $item->menu_item_parent,
				'menu-item-position'  => $item->menu_order,
			) );
			++$fixed;
		}
	}

	return $fixed;
}

add_action( 'admin_post_bootg_fix_menu_links', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_fix_menu_links' );

	$fixed = bootg_fix_stale_menu_links();

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_menu_links_fixed' => $fixed ),
		admin_url( 'themes.php' )
	) );
	exit;
} );

/**
 * One-click nav menu builder — recreates the original site's Primary,
 * Client Area, and Footer menus using real WP menu items (post_type links
 * where the target CPT content already exists, custom "#" placeholders
 * for pages not built yet). Skips a location that's already assigned.
 */
function bootg_add_menu_item( $menu_id, $args ) {
	$defaults = array(
		'menu-item-title'     => '',
		'menu-item-status'    => 'publish',
		'menu-item-parent-id' => 0,
	);
	return wp_update_nav_menu_item( $menu_id, 0, wp_parse_args( $args, $defaults ) );
}

function bootg_cpt_link_args( $post_type, $slug, $title ) {
	$post = get_page_by_path( $slug, OBJECT, $post_type );
	if ( $post ) {
		return array(
			'menu-item-title'     => $title,
			'menu-item-object-id' => $post->ID,
			'menu-item-object'    => $post_type,
			'menu-item-type'      => 'post_type',
		);
	}
	return array( 'menu-item-title' => $title, 'menu-item-url' => '#', 'menu-item-type' => 'custom' );
}

function bootg_archive_link_args( $post_type, $title ) {
	$link = get_post_type_archive_link( $post_type );
	return array(
		'menu-item-title' => $title,
		'menu-item-url'   => $link ?: '#',
		'menu-item-type'  => 'custom',
	);
}

add_action( 'admin_post_bootg_seed_menus', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_seed_menus' );

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$report    = array();

	// ---- Primary Menu ----
	if ( empty( $locations['primary'] ) ) {
		$menu_id = wp_create_nav_menu( 'Primary Menu' );

		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Home', 'menu-item-url' => home_url( '/' ), 'menu-item-type' => 'custom' ) );

		$about = bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'About Us', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => 'About Us', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ), array( 'menu-item-parent-id' => $about ) ) );
		bootg_add_menu_item( $menu_id, array_merge( bootg_archive_link_args( 'team_member', 'Meet Our Team' ), array( 'menu-item-parent-id' => $about ) ) );
		bootg_add_menu_item( $menu_id, array_merge( bootg_archive_link_args( 'testimonial', 'Testimonials' ), array( 'menu-item-parent-id' => $about ) ) );

		$services = bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Services', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );
		bootg_add_menu_item( $menu_id, array_merge( bootg_cpt_link_args( 'service', 'bas-ias-lodgments', 'BAS and IAS' ), array( 'menu-item-parent-id' => $services ) ) );
		bootg_add_menu_item( $menu_id, array_merge( bootg_cpt_link_args( 'service', 'bookkeeping-reconciliations', 'Bookkeeping' ), array( 'menu-item-parent-id' => $services ) ) );
		bootg_add_menu_item( $menu_id, array_merge( bootg_cpt_link_args( 'service', 'payroll-cloud-setup', 'Cloud Bookkeeping Setup and Support' ), array( 'menu-item-parent-id' => $services ) ) );

		$partners = bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Partners', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );
		foreach ( array(
			'cloud-accounting'   => 'Cloud Accounting',
			'dext'               => 'Dext',
			'hubdoc'             => 'Hubdoc',
			'myob'               => 'MYOB',
			'mechanicdesk'       => 'MechanicDesk',
			'quickbooks-online'  => 'Quickbooks Online',
			'xero'               => 'Xero',
		) as $slug => $title ) {
			bootg_add_menu_item( $menu_id, array_merge( bootg_cpt_link_args( 'integration', $slug, $title ), array( 'menu-item-parent-id' => $partners ) ) );
		}

		$resources = bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Resources', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => '7 steps to increasing profit', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ), array( 'menu-item-parent-id' => $resources ) ) );
		bootg_add_menu_item( $menu_id, array_merge( bootg_archive_link_args( 'post', 'Blog' ), array( 'menu-item-parent-id' => $resources ) ) );
		bootg_add_menu_item( $menu_id, array_merge( bootg_archive_link_args( 'guide', 'Business Guides' ), array( 'menu-item-parent-id' => $resources ) ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => 'Calculators', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ), array( 'menu-item-parent-id' => $resources ) ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => 'Key Dates', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ), array( 'menu-item-parent-id' => $resources ) ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => 'Templates & Checklists', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ), array( 'menu-item-parent-id' => $resources ) ) );

		$compliance = bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Compliance Resources', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => 'ATO Compliance', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ), array( 'menu-item-parent-id' => $compliance ) ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => 'Dates to Remember', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ), array( 'menu-item-parent-id' => $compliance ) ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => 'Policy Statement', 'menu-item-url' => 'https://bookkeepingonthego.net.au/app/uploads/Policy-Statement.pdf', 'menu-item-type' => 'custom', 'menu-item-target' => '_blank' ), array( 'menu-item-parent-id' => $compliance ) ) );
		bootg_add_menu_item( $menu_id, array_merge( array( 'menu-item-title' => 'Tax Agents Services Act 2009', 'menu-item-url' => 'https://bookkeepingonthego.net.au/app/uploads/Tax-Agents-Services-Act-2009-Disclosure-Statement.pdf', 'menu-item-type' => 'custom', 'menu-item-target' => '_blank' ), array( 'menu-item-parent-id' => $compliance ) ) );

		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Contact', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );

		$locations['primary'] = $menu_id;
		$report[]             = 'Primary Menu created';
	}

	// ---- Client Area Menu ----
	if ( empty( $locations['client-area'] ) ) {
		$menu_id = wp_create_nav_menu( 'Client Area Menu' );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'How to nominate us as your authorised agent', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Xero Login', 'menu-item-url' => 'https://login.xero.com', 'menu-item-type' => 'custom', 'menu-item-target' => '_blank' ) );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'MYOB Login', 'menu-item-url' => 'https://www.myob.com/au', 'menu-item-type' => 'custom', 'menu-item-target' => '_blank' ) );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'QuickBooks Online Login', 'menu-item-url' => 'https://app.qbo.intuit.com/app/login', 'menu-item-type' => 'custom', 'menu-item-target' => '_blank' ) );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Dext Login', 'menu-item-url' => 'https://app.dext.com/login/', 'menu-item-type' => 'custom', 'menu-item-target' => '_blank' ) );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Hubdoc Login', 'menu-item-url' => 'https://app.hubdoc.com/login', 'menu-item-type' => 'custom', 'menu-item-target' => '_blank' ) );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'MechanicDesk Login', 'menu-item-url' => 'https://www.mechanicdesk.com.au/auto_workshop/login', 'menu-item-type' => 'custom', 'menu-item-target' => '_blank' ) );

		$locations['client-area'] = $menu_id;
		$report[]                 = 'Client Area Menu created';
	}

	// ---- Footer Quick Links ----
	if ( empty( $locations['footer'] ) ) {
		$menu_id = wp_create_nav_menu( 'Footer Quick Links' );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Home', 'menu-item-url' => home_url( '/' ), 'menu-item-type' => 'custom' ) );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'About Us', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );
		bootg_add_menu_item( $menu_id, array_merge( bootg_archive_link_args( 'post', 'Blog' ), array() ) );
		bootg_add_menu_item( $menu_id, array( 'menu-item-title' => 'Contact', 'menu-item-url' => '#', 'menu-item-type' => 'custom' ) );

		$locations['footer'] = $menu_id;
		$report[]            = 'Footer menu created';
	}

	set_theme_mod( 'nav_menu_locations', $locations );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_menus_created' => rawurlencode( implode( ', ', $report ) ?: 'none (already set up)' ) ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
