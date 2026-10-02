<?php
/**
 * Specialist Area landing pages — Payroll Specialists, Public Trustee
 * Reporting, Nonprofit Compliance Accounting. Same shortcode-in-a-Page
 * pattern as About/Contact/Compliance: [bootg_specialist_*] renders into
 * a normal WP Page. Content adapted from the client's original site.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	add_shortcode( 'bootg_specialist_payroll', 'bootg_render_specialist_payroll' );
	add_shortcode( 'bootg_specialist_public_trustee', 'bootg_render_specialist_public_trustee' );
	add_shortcode( 'bootg_specialist_nonprofit', 'bootg_render_specialist_nonprofit' );
} );

/**
 * Shared renderer for a Specialist Area landing page: page-hero, one or
 * more mist-card content blocks, and the standard compliance-style CTA
 * band (bootg_compliance_cta(), defined in inc/compliance-pages.php).
 */
function bootg_render_specialist_page( $crumb, $title_html, $subtitle, $blocks, $cta_heading ) {
	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white"><?php echo esc_html( $crumb ); ?></span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1"><?php echo $title_html; // phpcs:ignore ?></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl reveal reveal-d2"><?php echo esc_html( $subtitle ); ?></p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="info-section">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<?php foreach ( $blocks as $block ) : ?>
				<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
					<h2 class="text-2xl font-extrabold text-navy mb-3"><?php echo esc_html( $block['heading'] ); ?></h2>
					<p class="text-ink leading-relaxed mb-0"><?php echo esc_html( $block['body'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<?php echo bootg_compliance_cta( $cta_heading ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

function bootg_render_specialist_payroll() {
	return bootg_render_specialist_page(
		'Payroll Specialists in Perth',
		'Payroll Specialists in Perth <span class="text-action">and Surrounding Areas</span>',
		'Accurate, affordable payroll — configured for Perth from day one.',
		array(
			array(
				'heading' => 'Payroll That Understands Perth',
				'body'    => "When choosing your bookkeeper, you want someone who understands the importance of managing your payroll in Perth. Go with a team who understands the regulations in the area, and can customise your payroll accordingly. Local taxes and fees are configured, public holidays are always accounted for, and working rules and regulations are never forgotten — that's the benefit of working with someone who understands where you're coming from.",
			),
			array(
				'heading' => 'Customised for Your Industry and State',
				'body'    => "No matter what industry you're in, it's important to work with a bookkeeper who understands the specifics of doing business in your state — tax laws can vary by state, and even locally. That's why we always get you set up so your bookkeeping tools work for your situation. Give us a call to discuss how we can help you get set up so your payroll always runs smoothly, no matter what state you do business in.",
			),
		),
		'Get Your Payroll Set Up Right'
	);
}

function bootg_render_specialist_public_trustee() {
	return bootg_render_specialist_page(
		'Public Trustee Reporting',
		'Your Trusted Bookkeeper for <span class="text-action">Public Trustee Reporting</span>',
		'Specialised administration bookkeeping for reporting to public trustees, delivered onsite or remotely.',
		array(
			array(
				'heading' => 'Are You Required to Report to the Public Trustees?',
				'body'    => "Financial reporting to public trustees requires specialised bookkeeping. Annual statements of accounts are required, and there are hefty fines involved if they're not submitted — completing them is often an understandable source of stress for administrators. We have years of experience in administration bookkeeping for public trustees and other similar bodies, and our team understands the nuances of bookkeeping under these circumstances.",
			),
			array(
				'heading' => 'Professional Administration Bookkeeping in WA',
				'body'    => "Our office is in Gosnells and we serve clients across Perth, Armadale, Canning Vale, Cannington, Victoria Park, Cockburn, Fremantle, Morley, Booragoon and beyond. We're set up to work remotely and can support your business in any part of Australia — happy to come to your office if you're local, or help you virtually no matter where you are. Give us a call to learn more about how we can support you through this process.",
			),
		),
		'Talk to Us About Public Trustee Reporting'
	);
}

function bootg_render_specialist_nonprofit() {
	return bootg_render_specialist_page(
		'Nonprofit Compliance Accounting',
		'Nonprofit Compliance <span class="text-action">Accounting</span>',
		'Outsourced bookkeeping that keeps your nonprofit compliant, without adding to an already-stretched team.',
		array(
			array(
				'heading' => 'Give Your Team Their Time Back',
				'body'    => "Running a nonprofit means balancing your mission with day-to-day operations, and bookkeeping is often the most time-consuming task of all. When financial work is handled in-house without specialised knowledge, it can consume far more hours than anticipated — manual data entry, reconciling restricted funds, and preparing reports for the board or for grants. Outsourcing removes that time pressure, so staff can get back to programs, fundraising, and donor engagement.",
			),
			array(
				'heading' => 'Built for Nonprofit Compliance',
				'body'    => "Nonprofits face unique obligations — grant requirements, payroll obligations, Form 990-style reporting, and donor-restricted funds — where mistakes can mean delayed grants or reputational damage. We apply nonprofit-specific accounting standards, separate restricted and unrestricted funds correctly, track grant deadlines, and maintain the audit trail your board and funders expect, so compliance becomes routine rather than a reactive scramble.",
			),
		),
		'Talk to Us About Your Nonprofit\'s Books'
	);
}

/** One-click creation of a Specialist Area page with the given shortcode as its content. */
function bootg_create_specialist_page( $slug, $title, $shortcode ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		return 'exists';
	}
	$page_id = wp_insert_post( array(
		'post_type'     => 'page',
		'post_title'    => $title,
		'post_name'     => $slug,
		'post_status'   => 'publish',
		'post_content'  => $shortcode,
		'page_template' => 'page-full-width',
	), true );
	return is_wp_error( $page_id ) ? 'error' : 'created';
}

/** Creates all 3 Specialist Area pages. Returns 'exists', 'created', or 'error'. */
function bootg_create_specialist_pages() {
	$payroll   = bootg_create_specialist_page( 'payroll-specialists-perth', 'Payroll Specialists in Perth', '[bootg_specialist_payroll]' );
	$trustee   = bootg_create_specialist_page( 'public-trustee-reporting', 'Public Trustee Reporting', '[bootg_specialist_public_trustee]' );
	$nonprofit = bootg_create_specialist_page( 'nonprofit-compliance-accounting', 'Nonprofit Compliance Accounting', '[bootg_specialist_nonprofit]' );

	$results = array( $payroll, $trustee, $nonprofit );
	return in_array( 'error', $results, true ) ? 'error' : ( in_array( 'created', $results, true ) ? 'created' : 'exists' );
}

add_action( 'admin_post_bootg_create_specialist_pages', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_create_specialist_pages' );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_specialist_pages' => bootg_create_specialist_pages() ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
