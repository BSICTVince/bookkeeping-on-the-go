<?php
/**
 * Resources pages — 7 Steps course signup, Key Dates calendar, Templates &
 * Checklists directory. Exposed as shortcodes, same pattern as the other
 * standalone pages: live in normal WP Pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	add_shortcode( 'bootg_7_steps', 'bootg_render_7_steps' );
	add_shortcode( 'bootg_key_dates', 'bootg_render_key_dates' );
	add_shortcode( 'bootg_templates_checklists', 'bootg_render_templates_checklists' );
	add_shortcode( 'bootg_get_started_xero', 'bootg_render_get_started_xero' );
} );

function bootg_resource_cta( $heading ) {
	$phone      = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );
	ob_start();
	?>
	<section class="py-16 lg:py-20 bg-navydeep text-white" data-testid="resource-cta-band">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<h2 class="text-3xl sm:text-4xl font-extrabold mb-4"><?php echo esc_html( $heading ); ?></h2>
			<p class="text-base md:text-lg text-white/60 max-w-xl mx-auto mb-8">Get in touch for a free, no-obligation consultation today.</p>
			<div class="flex flex-wrap justify-center gap-4">
				<a href="<?php echo esc_url( bootg_page_url( 'contact' ) ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Contact Us</a>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="btn btn-outline-light px-7 py-3.5 text-base">Call <?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/* ---------------------------------------------------------------------
 * 7 Steps to Increasing Profit — free course signup
 * ------------------------------------------------------------------- */

function bootg_render_7_steps() {
	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <a href="<?php echo esc_url( bootg_page_url( 'resources' ) ); ?>" class="hover:text-white transition-colors">Resources</a> <span class="mx-2">/</span> <span class="text-white">7 Steps to Increasing Profit</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">7 Steps to Increasing Profit and <span class="text-action">Keeping More Cash</span> in the Business</h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8 reveal reveal-d2">Explore our tips on how to increase profits and keep more cash in your business.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="course-section">
		<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="bg-mist rounded-2xl border border-slate-200 p-8 lg:p-12 reveal">
				<span class="bg-action/10 text-action text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1">Free Course + Template</span>
				<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mt-4 mb-3">Sign up for the course</h2>
				<p class="text-ink leading-relaxed mb-8">Enter your details below and sign up for our course, which also includes a FREE template to help you plan your strategy.</p>
				<?php echo bootg_render_form( bootg_get_7_steps_form_id() ); // phpcs:ignore ?>
			</div>
		</div>
	</section>

	<?php echo bootg_resource_cta( 'Want profit advice tailored to your business?' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

/* ---------------------------------------------------------------------
 * Get Started with Xero — free 7-day email course signup
 * ------------------------------------------------------------------- */

function bootg_render_get_started_xero() {
	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <a href="<?php echo esc_url( bootg_page_url( 'resources' ) ); ?>" class="hover:text-white transition-colors">Resources</a> <span class="mx-2">/</span> <span class="text-white">Get Started with Xero</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">Get Started with <span class="text-action">Xero</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8 reveal reveal-d2">A free 7-day email crash course that gets you comfortable in Xero — a few minutes a day, no jargon.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="course-section">
		<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="bg-mist rounded-2xl border border-slate-200 p-8 lg:p-12 reveal">
				<span class="bg-action/10 text-action text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1">Free 7-Day Course</span>
				<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mt-4 mb-3">Sign up for the course</h2>
				<p class="text-ink leading-relaxed mb-6">One short, practical email a day for a week — each one covers a single Xero task so it actually sticks. By day 7 you'll be comfortable finding your way around on your own.</p>
				<ul class="space-y-2.5 mb-8">
					<li class="check-item"><?php echo bootg_check_icon(); // phpcs:ignore ?>Finding your way around the Xero dashboard</li>
					<li class="check-item"><?php echo bootg_check_icon(); // phpcs:ignore ?>Connecting your bank accounts</li>
					<li class="check-item"><?php echo bootg_check_icon(); // phpcs:ignore ?>Creating quotes and invoicing customers</li>
					<li class="check-item"><?php echo bootg_check_icon(); // phpcs:ignore ?>Paying suppliers and managing expenses</li>
					<li class="check-item"><?php echo bootg_check_icon(); // phpcs:ignore ?>Attaching files and staying organised</li>
				</ul>
				<?php echo bootg_render_form( bootg_get_xero_course_form_id() ); // phpcs:ignore ?>
			</div>
		</div>
	</section>

	<?php echo bootg_resource_cta( 'Want a hand setting Xero up properly?' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

/* ---------------------------------------------------------------------
 * Key Dates — month-by-month ATO compliance calendar
 * ------------------------------------------------------------------- */

function bootg_key_dates_months() {
	return array(
		'August 2026'    => array(
			'21 Aug — Monthly BAS due: lodge and pay July 2026 monthly activity statement',
			'25 Aug — Quarterly BAS (Q4): registered BAS/tax agent electronic lodgement extension',
			'28 Aug — TPAR due: Taxable Payments Annual Report for FY2025–26 (construction, cleaning, courier, IT, security)',
		),
		'September 2026' => array(
			'21 Sep — Monthly BAS due: lodge and pay August 2026 activity statement',
			'30 Sep — STP finalisation: closely held payees (directors, family members) extended deadline',
		),
		'October 2026'   => array(
			'21 Oct — Monthly BAS due: lodge and pay September 2026 activity statement',
			'28 Oct — Quarterly BAS due (Q1: Jul–Sep 2026) for self-lodgers, plus PAYG instalment notice',
			'31 Oct — Company, individual, trust & partnership tax returns due for FY2025–26 self-lodgers; also the deadline to engage a registered tax agent for extended lodgement to 15 May 2027',
		),
		'November 2026'  => array(
			'21 Nov — Monthly BAS due: lodge and pay October 2026 activity statement',
			'25 Nov — Quarterly BAS (Q1): registered agent electronic lodgement extension',
		),
		'December 2026'  => array(
			'21 Dec — Monthly BAS due: lodge and pay November 2026 activity statement',
			'Note — ATO Christmas concession: December and January BAS are both due 21 February 2027',
		),
		'January 2027'   => array(
			'15 Jan — Large/medium trust tax returns due (income over $10M, FY2026–27)',
			'Note — No standard monthly BAS: December and January BAS both due 21 February 2027',
		),
		'February 2027'  => array(
			'21 Feb — Monthly BAS due: December 2026 AND January 2027 activity statements both due',
			'28 Feb — Individual & trust tax returns (FY2026–27) where prior year liability was $20,000+; Quarterly BAS due (Q2) for self-lodgers',
		),
		'March 2027'     => array(
			'21 Mar — Monthly BAS due: lodge and pay February 2027 activity statement',
			'29 Mar — Quarterly BAS (Q2): registered agent electronic lodgement extension',
			'31 Mar — End of Fringe Benefits Tax (FBT) year',
		),
		'April 2027'     => array(
			'01 Apr — New FBT year begins (1 April 2027 – 31 March 2028)',
			'21 Apr — Monthly BAS due: lodge and pay March 2027 activity statement',
			'28 Apr — Quarterly BAS due (Q3: Jan–Mar 2027) for self-lodgers, plus PAYG instalment notice',
		),
		'May 2027'       => array(
			'15 May — Tax returns FY2026–27 via registered tax agent (standard concession)',
			'21 May — Monthly BAS due; FBT return and payment due (paper lodgement)',
			'26 May — Quarterly BAS (Q3): registered agent electronic lodgement extension',
		),
		'June 2027'      => array(
			'05 Jun — Concessional lodgement deadline for returns not required by 15 May',
			'21 Jun — Monthly BAS due; recommended super processing cut-off so FY2027 contributions clear by 30 June',
			'30 Jun — End of financial year: super must have cleared funds to be deductible; trust distribution resolutions signed; assets installed and ready for use for immediate deductions',
		),
		'July 2027'      => array(
			'14 Jul — STP finalisation due for FY2026–27 (marks employee income statements "tax ready" in myGov)',
			'21 Jul — Monthly BAS due: lodge and pay June 2027 activity statement',
			'28 Jul — Quarterly BAS due (Q4) for self-lodgers; payroll tax annual reconciliation due in most states',
			'30 Aug — TPAR due for FY2026–27 (construction, cleaning, courier, road freight, IT, security)',
		),
	);
}

function bootg_render_key_dates() {
	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> Resources <span class="mx-2">/</span> <span class="text-white">Key Dates</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">Key <span class="text-action">Dates</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8 reveal reveal-d2">The ATO dates that matter, straight from our compliance calendar. As your registered BAS agent we can often extend these.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="info-section">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<?php foreach ( bootg_key_dates_months() as $month => $items ) : ?>
				<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
					<h2 class="text-2xl font-extrabold text-navy mb-3"><?php echo esc_html( $month ); ?></h2>
					<ul class="space-y-2.5 mt-4">
						<?php foreach ( $items as $item ) : ?>
							<li class="check-item"><?php echo bootg_check_icon(); // phpcs:ignore ?><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<?php echo bootg_resource_cta( 'Never miss a date again' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

/* ---------------------------------------------------------------------
 * Templates & Checklists — resource directory
 * ------------------------------------------------------------------- */

/**
 * Content grouped the same way the original site's "Find Content By Type"
 * page organised it — Checklists / Templates grouped from the plain-
 * language Guides (weavit-engine CPT, our own content/guides.json), plus
 * a short Courses list of the two real email-course pages. Every link is
 * internal now; nothing points at the old production domain any more.
 */
function bootg_templates_checklists_groups() {
	return array(
		'Checklists' => array(
			'simple-ways-to-get-more-done-each-week',
			'building-a-rainy-day-fund-for-your-business',
			'is-your-business-ready-to-grow',
			'getting-your-new-business-off-the-ground',
		),
		'Templates'  => array(
			'small-changes-that-add-up-to-more-profit',
			'working-out-what-itll-really-cost-to-start-up',
			'how-many-sales-do-you-need-to-break-even',
			'pricing-your-products-so-you-actually-make-money',
			'writing-a-business-plan-without-the-jargon',
		),
	);
}

function bootg_templates_checklists_courses() {
	return array(
		array( 'title' => 'Get Started with Xero', 'url' => bootg_page_url( 'get-started-with-xero' ) ),
		array( 'title' => '7 Steps to Increasing Profit', 'url' => bootg_page_url( '7-steps' ) ),
	);
}

function bootg_render_resource_type_card( $heading, $items, $see_all_url = '' ) {
	ob_start();
	?>
	<div class="bg-mist rounded-xl border border-slate-200 overflow-hidden reveal">
		<div class="bg-navy px-6 py-4">
			<h3 class="text-white font-bold text-lg"><?php echo esc_html( $heading ); ?></h3>
		</div>
		<div class="p-6">
			<ul class="space-y-3 mb-2">
				<?php foreach ( $items as $item ) : ?>
					<li><a href="<?php echo esc_url( $item['url'] ); ?>" class="text-action font-semibold hover:text-action-dark transition-colors"><?php echo esc_html( $item['title'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $see_all_url ) : ?>
				<hr class="border-slate-200 my-4">
				<a href="<?php echo esc_url( $see_all_url ); ?>" class="text-navy font-bold text-sm">See All <span aria-hidden="true">&rarr;</span></a>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function bootg_render_templates_checklists() {
	$guides_url = get_post_type_archive_link( 'guide' ) ?: home_url( '/guides/' );

	$cards = array();
	foreach ( bootg_templates_checklists_groups() as $heading => $slugs ) {
		$items = array();
		foreach ( $slugs as $slug ) {
			$post = get_page_by_path( $slug, OBJECT, 'guide' );
			if ( $post ) {
				$items[] = array( 'title' => get_the_title( $post ), 'url' => get_permalink( $post ) );
			}
		}
		if ( $items ) {
			$cards[ $heading ] = $items;
		}
	}

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 text-center">
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">Find Content By <span class="text-action">Type</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mx-auto reveal reveal-d2">Free tools and resources to help you grow and manage your business.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="listing-section">
		<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid md:grid-cols-2 gap-6 mb-6">
				<?php foreach ( $cards as $heading => $items ) : ?>
					<?php echo bootg_render_resource_type_card( $heading, $items, $guides_url ); // phpcs:ignore ?>
				<?php endforeach; ?>
			</div>
			<div class="grid md:grid-cols-2 gap-6">
				<?php echo bootg_render_resource_type_card( 'Courses', bootg_templates_checklists_courses() ); // phpcs:ignore ?>
			</div>
		</div>
	</section>

	<?php echo bootg_resource_cta( 'Want these tailored to your business?' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

/* ---------------------------------------------------------------------
 * Calculators now live at /calculators/ entirely via the Weavit Engine
 * plugin's own `bootg_calculator` custom post type (see
 * weavit-engine/inc/calculators.php) — its own rewrite rules, archive,
 * and single templates, with one unique URL per calculator (e.g.
 * /calculators/gst/). No theme Page or shortcode needed here any more;
 * the plugin retires the old static "Calculators" Page automatically.
 * ------------------------------------------------------------------- */

/** One-click creation of the Resources pages. */
function bootg_create_resource_page( $slug, $title, $shortcode ) {
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

/** "How to nominate us as your authorised agent" (a child of the Resources page, so it lives at /resources/how-to-nominate-us-as-your-authorised-agent/). */
function bootg_create_nominate_page() {
	$slug   = 'how-to-nominate-us-as-your-authorised-agent';
	$parent = get_page_by_path( 'resources' );
	if ( get_page_by_path( ( $parent ? 'resources/' : '' ) . $slug ) ) {
		return 'exists';
	}

	$img     = esc_url( get_theme_file_uri( 'assets/images/ato-client-agent-linking-instructions.jpg' ) );
	$contact = esc_url( home_url( '/contact/' ) );

	$content = <<<HTML
<p>The ATO has implemented measures to strengthen the security of their online services and protect people from identity theft.</p>
<p>You now need to nominate your registered agent in Online services for business before they can access your account and act on your behalf.</p>
<p>To nominate <strong>Bookkeeping On The Go</strong> as your authorised Agent, you will need to complete the following steps below.</p>
<p><a href="{$img}" target="_blank" rel="noopener">Download image instructions</a></p>

<h2>Part 1: Set up Access to Online Services for Business</h2>
<p>You will require access to the ATO's 'Online services for Business' portal to complete the client-agent linking steps. If you already have a business portal, please scroll down to <strong>Part 2:</strong> Nominate your Authorised Agent. If you do not have a business portal, please start at <strong>Step 1.</strong></p>

<h3>Step 1: Set up your Digital Identity (myGovID)</h3>
<p>Differing from a myGov account, a myGovID is the Australian Government's Digital Identity app that allows you to login to the 'Online services for business' portal. For instruction on how to set up your myGovID, please visit <a href="https://www.mygovid.gov.au/set-up" target="_blank" rel="noopener">https://www.mygovid.gov.au/set-up</a>. Please be aware that when you're setting up your myGovID, you must hold a Standard Identity Strength level to complete the remaining steps.</p>

<h3>Step 2: Link your myGovID to your ABN</h3>
<p><a href="https://info.authorisationmanager.gov.au/" target="_blank" rel="noopener">Relationship Authorisation Manager (RAM)</a> is an authorisation service that grants you access to online services on behalf of a business. You will need to use RAM to link your myGovID to your Australian Business Number (ABN).</p>
<p>The person who is ultimately responsible for the business, also known as the principal authority, must be the first person to link your ABN in RAM. The way you link your myGovID to your ABN will depend on your role in the business (see below):</p>
<p><strong>You can link your myGovID to your ABN online if you meet the following criteria:</strong></p>
<ol>
<li>You have a strong myGovID identity strength; and</li>
<li>Your name is listed in the ABR.</li>
</ol>
<p><strong>You will need to link your myGovID to your ABN by contacting the ATO directly if you meet the following criteria:</strong></p>
<ol>
<li>You do not have a strong myGovID identity strength; or</li>
<li>You are not an individual associate listed in the ABR.</li>
</ol>

<h3>Step 3: Authorise others to act on your behalf (optional)</h3>
<p>You can authorise others to act on behalf of your business (for example, employees) in RAM. For instructions on how to authorise others, visit: <a href="https://info.authorisationmanager.gov.au/set-up-authorisations" target="_blank" rel="noopener">https://info.authorisationmanager.gov.au/set-up-authorisations</a>.</p>

<h2>Part 2: Nominate your Authorised Agent</h2>
<p>Now that you have created your myGovID and linked it to your ABN, you can nominate <strong>Bookkeeping On The Go</strong> as your Activity Statement Agent by following these steps.</p>

<h3>Step 1: Log in to Online services for business</h3>
<p>Use your myGovID to login to 'Online Services for business' through the following link: <a href="https://mygovid.gov.au/AuthSpa.UI/index.html#login" target="_blank" rel="noopener">https://mygovid.gov.au/AuthSpa.UI/index.html#login</a></p>

<h3>Step 2: Nominate Bookkeeping On The Go as your authorised agent</h3>
<p>Navigate through 'Online services for business' to nominate us as your Agent by following the steps below:</p>
<ol>
<li>From the Online services for business home page select Profile, then Agent details.</li>
<li>At the Agent nominations feature, select <strong>ADD</strong>.</li>
<li>On the nominate agent screen, go to "<strong>Search for Agent</strong>".</li>
<li>In the search bar, enter our Registered Agent Number (RAN), which is <strong>92390002</strong>, then press search.</li>
<li>From the results, select <em><strong>Bookkeeping On The Go</strong></em></li>
<li>Check that the agent's details are all correct.</li>
<li>Complete the Declaration.</li>
<li>Select Submit.</li>
</ol>

<h3>Step 3: Contact us to confirm you have completed the nomination</h3>
<p>It is important that you contact us once you have completed your nomination, as we only have 28 days to action the nomination before it expires. If your nomination expires, you will have to restart the nomination process.</p>
<p>When completing your nomination, please ensure that you select all authorisations that you would like us to be responsible for (for example, Activity Statement Agent, PAYG).</p>
<p>Finally, if you experience any errors or difficulties when completing the agent nomination process, please <a href="{$contact}">contact us</a> or contact the ATO directly for support.</p>
HTML;

	$page_id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_title'   => 'How to nominate us as your authorised agent',
		'post_name'    => $slug,
		'post_parent'  => $parent ? $parent->ID : 0,
		'post_status'  => 'publish',
		'post_content' => $content,
	), true );

	return is_wp_error( $page_id ) ? 'error' : 'created';
}

/** Creates the Resource pages (7 Steps, Key Dates, Templates & Checklists, Get Started with Xero, How to nominate us). Returns 'exists', 'created', or 'error'. */
function bootg_create_resource_pages() {
	$steps     = bootg_create_resource_page( '7-steps', '7 Steps to Increasing Profit', '[bootg_7_steps]' );
	$dates     = bootg_create_resource_page( 'key-dates', 'Key Dates', '[bootg_key_dates]' );
	$templates = bootg_create_resource_page( 'resources', 'Templates & Checklists', '[bootg_templates_checklists]' );
	$xero      = bootg_create_resource_page( 'get-started-with-xero', 'Get Started with Xero', '[bootg_get_started_xero]' );

	$nominate  = bootg_create_nominate_page();

	$results = array( $steps, $dates, $templates, $xero, $nominate );
	return in_array( 'error', $results, true ) ? 'error' : ( in_array( 'created', $results, true ) ? 'created' : 'exists' );
}

add_action( 'admin_post_bootg_create_resource_pages', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_create_resource_pages' );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_resource_pages' => bootg_create_resource_pages() ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
