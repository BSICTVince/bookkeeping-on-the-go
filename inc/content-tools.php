<?php
/**
 * One-click starter-content tools — theme-specific migration triggers
 * (create pages, seed CPTs, migrate blog posts, fix menu links, etc.).
 * Kept in the theme rather than the Weavit Engine plugin because every
 * button here calls theme-owned seed data (inc/seed-content.php,
 * inc/blog-seed.php, inc/forms/forms-migrate.php, inc/partner-logos.php)
 * specific to this site's original content — not reusable engine logic.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', function () {
	add_theme_page(
		'Content Tools',
		'Content Tools',
		'manage_options',
		'bootg-content-tools',
		'bootg_render_content_tools_page'
	);
} );

/**
 * The shortcode-driven pages (Contact, About Us, Compliance, Resources)
 * render their own full-bleed hero and heading, so they need the
 * page-full-width.html template (no core post-title, no constrained
 * layout) rather than the default page.html. New pages get this set at
 * creation time; this backfills it onto any created before that template
 * existed. Safe to run again.
 */
function bootg_fix_page_templates() {
	$slugs = array( 'contact', 'about', 'ato-compliance', 'dates-to-remember', '7-steps', 'key-dates', 'resources', 'calculators' );
	$fixed = 0;

	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			continue;
		}
		if ( 'page-full-width' === get_page_template_slug( $page->ID ) ) {
			continue;
		}
		update_post_meta( $page->ID, '_wp_page_template', 'page-full-width' );
		++$fixed;
	}

	return $fixed;
}

add_action( 'admin_post_bootg_fix_page_templates', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_fix_page_templates' );

	$fixed = bootg_fix_page_templates();

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_page_templates_fixed' => $fixed ),
		admin_url( 'themes.php' )
	) );
	exit;
} );

function bootg_render_content_tools_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1>Content Tools</h1>
		<p class="description">One-time / re-runnable tools that create pages and seed starter content for this site. Business details (phone, email, socials) live on <a href="<?php echo esc_url( admin_url( 'themes.php?page=bootg-site-options' ) ); ?>">Appearance &rarr; Site Options</a>.</p>

		<?php if ( isset( $_GET['bootg_page_templates_fixed'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo (int) $_GET['bootg_page_templates_fixed']; ?> page template(s) switched to Full-Width Content Page.</p></div>
		<?php endif; ?>

		<h2>Fix Page Templates</h2>
		<p class="description">Contact, About Us, Compliance and Resources pages render their own full-width hero and heading — this switches them from the default <strong>Pages</strong> template (which adds a redundant title and a narrow content width) to <strong>Full-Width Content Page</strong>. New pages already get this automatically; this is only needed for pages created before that template existed. Safe to run again.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_fix_page_templates' ); ?>
			<input type="hidden" name="action" value="bootg_fix_page_templates">
			<?php submit_button( 'Fix Page Templates', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_hero_image'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>Homepage hero image downloaded into the Media Library.</p></div>
		<?php endif; ?>

		<h2>Homepage Hero Image</h2>
		<p class="description">Downloads the homepage hero photo into this site's own Media Library so it never depends on the old production domain. Safe to run again.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_migrate_hero_image' ); ?>
			<input type="hidden" name="action" value="bootg_migrate_hero_image">
			<?php submit_button( 'Download Hero Image', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_contact_page'] ) ) : ?>
			<?php if ( 'created' === $_GET['bootg_contact_page'] ) : ?>
				<div class="notice notice-success is-dismissible"><p>Contact page created at <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" target="_blank"><?php echo esc_url( home_url( '/contact/' ) ); ?></a>.</p></div>
			<?php elseif ( 'exists' === $_GET['bootg_contact_page'] ) : ?>
				<div class="notice notice-info is-dismissible"><p>A page at <code>/contact/</code> already exists — nothing changed.</p></div>
			<?php else : ?>
				<div class="notice notice-error is-dismissible"><p>Could not create the Contact page.</p></div>
			<?php endif; ?>
		<?php endif; ?>

		<h2>Contact Page</h2>
		<p class="description">One-time creation of a <strong>Contact</strong> page at <code>/contact/</code> with a real, working contact form (sends to the Email address set in Site Options via <code>wp_mail()</code> — no plugin). Every "Book a Free Consultation" link on the site already points here.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_create_contact_page' ); ?>
			<input type="hidden" name="action" value="bootg_create_contact_page">
			<?php submit_button( 'Create Contact Page', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_about_page'] ) ) : ?>
			<?php if ( 'created' === $_GET['bootg_about_page'] ) : ?>
				<div class="notice notice-success is-dismissible"><p>About Us page created at <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" target="_blank"><?php echo esc_url( home_url( '/about/' ) ); ?></a>.</p></div>
			<?php elseif ( 'exists' === $_GET['bootg_about_page'] ) : ?>
				<div class="notice notice-info is-dismissible"><p>A page at <code>/about/</code> already exists — nothing changed.</p></div>
			<?php else : ?>
				<div class="notice notice-error is-dismissible"><p>Could not create the About Us page.</p></div>
			<?php endif; ?>
		<?php endif; ?>

		<h2>About Us Page</h2>
		<p class="description">One-time creation of an <strong>About Us</strong> page at <code>/about/</code> (story, values, founder, certifications, CTA). Edit its content anytime from Pages &rarr; About Us.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_create_about_page' ); ?>
			<input type="hidden" name="action" value="bootg_create_about_page">
			<?php submit_button( 'Create About Us Page', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_compliance_pages'] ) ) : ?>
			<?php if ( 'created' === $_GET['bootg_compliance_pages'] ) : ?>
				<div class="notice notice-success is-dismissible"><p>Compliance pages created: <a href="<?php echo esc_url( home_url( '/ato-compliance/' ) ); ?>" target="_blank">/ato-compliance/</a> and <a href="<?php echo esc_url( home_url( '/dates-to-remember/' ) ); ?>" target="_blank">/dates-to-remember/</a>.</p></div>
			<?php elseif ( 'exists' === $_GET['bootg_compliance_pages'] ) : ?>
				<div class="notice notice-info is-dismissible"><p>Those Compliance pages already exist — nothing changed.</p></div>
			<?php else : ?>
				<div class="notice notice-error is-dismissible"><p>Could not create one or more Compliance pages.</p></div>
			<?php endif; ?>
		<?php endif; ?>

		<h2>Compliance Pages</h2>
		<p class="description">One-time creation of <strong>ATO Compliance</strong> (<code>/ato-compliance/</code>) and <strong>Dates to Remember</strong> (<code>/dates-to-remember/</code>) pages, matching the Compliance Resources nav menu.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_create_compliance_pages' ); ?>
			<input type="hidden" name="action" value="bootg_create_compliance_pages">
			<?php submit_button( 'Create Compliance Pages', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_partner_logos'] ) ) : ?>
			<?php if ( 'done' === $_GET['bootg_partner_logos'] ) : ?>
				<div class="notice notice-success is-dismissible"><p>Partner logos downloaded into the Media Library.</p></div>
			<?php else : ?>
				<div class="notice notice-error is-dismissible"><p>Some partner logos could not be downloaded — check your internet connection and try again. The homepage and About page still show a working image (from the original source) for any that failed.</p></div>
			<?php endif; ?>
		<?php endif; ?>

		<h2>Partner Logos</h2>
		<p class="description">The Xero/MYOB/QuickBooks/Dext/Hubdoc/TPB badges shown on the homepage and About page currently link to the old production site's images. One click downloads them into this site's own Media Library so they never depend on that domain. Safe to run again.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_migrate_partner_logos' ); ?>
			<input type="hidden" name="action" value="bootg_migrate_partner_logos">
			<?php submit_button( 'Download Partner Logos', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_resource_pages'] ) ) : ?>
			<?php if ( 'created' === $_GET['bootg_resource_pages'] ) : ?>
				<div class="notice notice-success is-dismissible"><p>Resource pages created: <a href="<?php echo esc_url( home_url( '/7-steps/' ) ); ?>" target="_blank">/7-steps/</a>, <a href="<?php echo esc_url( home_url( '/key-dates/' ) ); ?>" target="_blank">/key-dates/</a>, <a href="<?php echo esc_url( home_url( '/resources/' ) ); ?>" target="_blank">/resources/</a>, and <a href="<?php echo esc_url( home_url( '/calculators/' ) ); ?>" target="_blank">/calculators/</a>.</p></div>
			<?php elseif ( 'exists' === $_GET['bootg_resource_pages'] ) : ?>
				<div class="notice notice-info is-dismissible"><p>Those Resource pages already exist — nothing changed.</p></div>
			<?php else : ?>
				<div class="notice notice-error is-dismissible"><p>Could not create one or more Resource pages.</p></div>
			<?php endif; ?>
		<?php endif; ?>

		<h2>Resources Pages</h2>
		<p class="description">One-time creation of <strong>7 Steps to Increasing Profit</strong> (<code>/7-steps/</code>, with a real working course signup form), <strong>Key Dates</strong> (<code>/key-dates/</code>), <strong>Templates &amp; Checklists</strong> (<code>/resources/</code>), and <strong>Calculators</strong> (<code>/calculators/</code>).</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_create_resource_pages' ); ?>
			<input type="hidden" name="action" value="bootg_create_resource_pages">
			<?php submit_button( 'Create Resources Pages', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_seed_created'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( (int) $_GET['bootg_seed_created'] ); ?> item(s) created.</p></div>
		<?php endif; ?>

		<?php if ( isset( $_GET['bootg_service_content_fixed'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo (int) $_GET['bootg_service_content_fixed']; ?> service(s) updated with real "What's Included" content.</p></div>
		<?php endif; ?>

		<?php if ( isset( $_GET['bootg_integration_content_fixed'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo (int) $_GET['bootg_integration_content_fixed']; ?> partner integration(s) updated with real content.</p></div>
		<?php endif; ?>

		<h2>Fix Integration Content</h2>
		<p class="description">Same gap as Services — the 7 partner integrations were seeded with subtitle/intro/badge but no feature checklist. This fills in the real description and checklist from the original site. Safe to run again.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_fix_integration_content' ); ?>
			<input type="hidden" name="action" value="bootg_fix_integration_content">
			<?php submit_button( 'Fix Integration Content', 'secondary', 'submit', false ); ?>
		</form>

		<h2>Fix Service Content</h2>
		<p class="description">The 3 Services were seeded with just a title and short card summary — this fills in the real "What's Included" checklist, hero headline, and description paragraph from the original site. Safe to run again.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_fix_service_content' ); ?>
			<input type="hidden" name="action" value="bootg_fix_service_content">
			<?php submit_button( 'Fix Service Content', 'secondary', 'submit', false ); ?>
		</form>

		<h2>Migrate Starter Content</h2>
		<p class="description">One-time import of Services, Integrations, a Testimonial, and Team Members from the original site copy. Safe to run again — existing items (matched by title) are skipped.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_seed_content' ); ?>
			<input type="hidden" name="action" value="bootg_seed_content">
			<?php submit_button( 'Import Starter Content', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_blog_page_setup'] ) ) : ?>
			<?php if ( '1' === $_GET['bootg_blog_page_setup'] ) : ?>
				<div class="notice notice-success is-dismissible"><p>Blog page set up — visit <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" target="_blank">/blog/</a>. The "Blog" nav menu item(s) were repointed to it.</p></div>
			<?php else : ?>
				<div class="notice notice-error is-dismissible"><p>Could not set up the Blog page.</p></div>
			<?php endif; ?>
		<?php endif; ?>

		<h2>Set Up Blog Page</h2>
		<p class="description">A block theme's <code>front-page.html</code> only takes over the homepage when Reading Settings uses "a static page" — that same setting is what gives the blog archive a real <code>/blog/</code> URL. One click creates minimal Home/Blog pages, configures Reading Settings, and repoints the "Blog" nav item(s). Safe to run again.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_setup_blog_page' ); ?>
			<input type="hidden" name="action" value="bootg_setup_blog_page">
			<?php submit_button( 'Set Up Blog Page', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_blog_seeded'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo (int) $_GET['bootg_blog_seeded']; ?> blog post(s) created.</p></div>
		<?php endif; ?>

		<h2>Migrate Blog Posts</h2>
		<p class="description">One-time import of the 6 real articles from the original site's blog, with categories, excerpts, and the featured post's image. Safe to run again — existing posts (matched by title) are skipped.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_seed_blog_posts' ); ?>
			<input type="hidden" name="action" value="bootg_seed_blog_posts">
			<?php submit_button( 'Import Blog Posts', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_menus_created'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( wp_unslash( $_GET['bootg_menus_created'] ) ); ?></p></div>
		<?php endif; ?>

		<h2>Build Navigation Menus</h2>
		<p class="description">One-time build of the Primary, Client Area, and Footer menus, reproducing the original site's structure and linking to Services/Integrations/Team/Testimonials where that content already exists (placeholder links elsewhere until those pages are built). Skips any location that's already assigned — edit menu items directly at <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">Appearance &rarr; Menus</a> afterwards.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_seed_menus' ); ?>
			<input type="hidden" name="action" value="bootg_seed_menus">
			<?php submit_button( 'Build Navigation Menus', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $_GET['bootg_menu_links_fixed'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo (int) $_GET['bootg_menu_links_fixed']; ?> menu link(s) repointed to their real pages.</p></div>
		<?php endif; ?>

		<?php if ( isset( $_GET['bootg_legal_pages'] ) ) : ?>
			<?php if ( 'done' === $_GET['bootg_legal_pages'] ) : ?>
				<div class="notice notice-success is-dismissible"><p>Privacy Policy, Terms and Conditions, and the two service pages were created/updated with the migrated content from the original site.</p></div>
			<?php else : ?>
				<div class="notice notice-error is-dismissible"><p>Could not create/update one or more legal/service pages.</p></div>
			<?php endif; ?>
		<?php endif; ?>

		<h2>Legal &amp; Service Pages</h2>
		<p class="description">One-click creation (or content refresh, if they already exist) of <strong>Privacy Policy</strong> (<code>/privacy-policy/</code>), <strong>Terms and Conditions</strong> (<code>/terms-and-conditions/</code>), <strong>Reporting to Public Trustees</strong> (<code>/reporting-to-public-trustees/</code>), and <strong>Payroll Specialists</strong> (<code>/payroll-specialists/</code>) — content migrated from the original site. Also points the footer's Privacy/Terms links at the new pages. Safe to run again.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_create_legal_pages' ); ?>
			<input type="hidden" name="action" value="bootg_create_legal_pages">
			<?php submit_button( 'Create / Update Legal & Service Pages', 'secondary', 'submit', false ); ?>
		</form>

		<h2>Fix Menu Links</h2>
		<p class="description">As pages (About Us, Contact, 7 Steps, Calculators, Key Dates, Templates &amp; Checklists, ATO Compliance, Dates to Remember) get built after the menus were first created, their menu items are left pointing at <code>#</code> placeholders. This repoints exactly those — dropdown category headers (About Us, Services, Partners, Resources, Compliance Resources) are left alone since they never had one single destination. Safe to run again.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom:2em;">
			<?php wp_nonce_field( 'bootg_fix_menu_links' ); ?>
			<input type="hidden" name="action" value="bootg_fix_menu_links">
			<?php submit_button( 'Fix Menu Links', 'secondary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}
