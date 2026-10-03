<?php
/**
 * Bookkeeping On The Go theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOOTG_VERSION', '0.1.59' );
define( 'BOOTG_DIR', get_template_directory() );
define( 'BOOTG_URI', get_template_directory_uri() );

/** This theme requires the Weavit Engine plugin for its CPTs, SEO fields, SMTP, Site Options, and Forms engine. */
add_action( 'admin_notices', function () {
	if ( defined( 'WEAVIT_ENGINE_VERSION' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-error"><p><strong>Bookkeeping On The Go</strong> requires the <strong>Weavit Engine</strong> plugin to be installed and active — without it, Services, Integrations, Forms, SMTP, and Site Options will not work.</p></div>';
} );

/**
 * Content/data-model plumbing (CPTs, meta boxes, SEO fields, SMTP, Site
 * Options, the Forms engine) lives in the Weavit Engine plugin — this
 * theme only requires its own presentation layer plus theme-specific
 * starter content.
 */
require_once BOOTG_DIR . '/inc/class-nav-walker.php';
require_once BOOTG_DIR . '/inc/partner-logos.php';
require_once BOOTG_DIR . '/inc/content-tools.php';
require_once BOOTG_DIR . '/inc/seed-content.php';
require_once BOOTG_DIR . '/inc/starter-site-import.php';
require_once BOOTG_DIR . '/inc/widgets/footer-brand-widget.php';
require_once BOOTG_DIR . '/inc/widgets/footer-newsletter-widget.php';
require_once BOOTG_DIR . '/inc/footer-customizer.php';
require_once BOOTG_DIR . '/inc/blocks.php';
require_once BOOTG_DIR . '/inc/components/section.php';
require_once BOOTG_DIR . '/inc/components/hero.php';
require_once BOOTG_DIR . '/inc/components/button.php';
require_once BOOTG_DIR . '/inc/home-blocks.php';
require_once BOOTG_DIR . '/inc/homepage-sections.php';
require_once BOOTG_DIR . '/inc/cpt-templates.php';
require_once BOOTG_DIR . '/inc/blog-templates.php';
require_once BOOTG_DIR . '/inc/blog-seed.php';
require_once BOOTG_DIR . '/inc/contact-page.php';
require_once BOOTG_DIR . '/inc/about-page.php';
require_once BOOTG_DIR . '/inc/compliance-pages.php';
require_once BOOTG_DIR . '/inc/specialist-pages.php';
require_once BOOTG_DIR . '/inc/resource-pages.php';
require_once BOOTG_DIR . '/inc/legal-pages.php';
require_once BOOTG_DIR . '/inc/forms/forms-render.php';
require_once BOOTG_DIR . '/inc/forms/forms-migrate.php';
require_once BOOTG_DIR . '/inc/error-search-templates.php';
require_once BOOTG_DIR . '/inc/updates.php';
require_once BOOTG_DIR . '/inc/nav-settings.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'weavit-engine' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 52,
		'width'       => 220,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );

	// Loads our stylesheet inside the block editor's content iframe only
	// (not the surrounding wp-admin chrome) — see the enqueue_block_assets
	// note below for why that distinction matters.
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/css/tailwind-built.css', 'assets/css/site.css' ) );

	register_nav_menus( array(
		'primary'     => __( 'Primary Menu', 'bootg' ),
		'client-area' => __( 'Client Area Menu', 'bootg' ),
		'footer'      => __( 'Footer Quick Links', 'bootg' ),
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'bootg-google-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800&family=Fraunces:ital,opsz,wght@1,9..144,400;1,9..144,500&family=Source+Sans+3:ital,wght@0,400;0,600;0,700;1,400&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'bootg-tailwind', BOOTG_URI . '/assets/css/tailwind-built.css', array(), BOOTG_VERSION );
	wp_enqueue_style( 'bootg-site', BOOTG_URI . '/assets/css/site.css', array( 'bootg-tailwind' ), BOOTG_VERSION );

	wp_enqueue_script( 'bootg-site', BOOTG_URI . '/assets/js/site.js', array(), BOOTG_VERSION, true );
} );

add_action( 'init', function () {
	register_block_style( 'core/group', array(
		'name'  => 'bootg-mist',
		'label' => __( 'Mist background', 'bootg' ),
	) );
} );

/** Editor UI for the Weavit Page Designer components (Section/Hero/Button) — see assets/js/components.js. */
add_action( 'enqueue_block_editor_assets', function () {
	if ( ! function_exists( 'bootg_module_enabled' ) || ! bootg_module_enabled( 'page-designer' ) ) {
		return;
	}
	wp_enqueue_script(
		'bootg-components',
		BOOTG_URI . '/assets/js/components.js',
		array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-i18n' ),
		BOOTG_VERSION,
		true
	);
} );

