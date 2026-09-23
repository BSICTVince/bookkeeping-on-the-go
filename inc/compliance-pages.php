<?php
/**
 * Compliance Resources pages — ATO Compliance + Dates to Remember.
 * Exposed as [bootg_ato_compliance] / [bootg_dates_to_remember], same
 * shortcode pattern as the About/Contact pages: live in normal WP Pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	add_shortcode( 'bootg_ato_compliance', 'bootg_render_ato_compliance' );
	add_shortcode( 'bootg_dates_to_remember', 'bootg_render_dates_to_remember' );
} );

function bootg_compliance_cta( $heading ) {
	$phone      = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );
	ob_start();
	?>
	<section class="py-16 lg:py-20 bg-navydeep text-white" data-testid="info-cta-band">
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

function bootg_render_ato_compliance() {
	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> Compliance Resources <span class="mx-2">/</span> <span class="text-white">ATO Compliance</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">ATO <span class="text-action">Compliance</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8 reveal reveal-d2">How we keep your business on the right side of the ATO — and protected while we do it.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="info-section">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Registered BAS Agent</h2>
				<p class="text-slate-500 leading-relaxed mb-0">Bookkeeping On The Go is a registered BAS agent with the Tax Practitioners Board (TPB). That means we meet strict education, experience and ongoing training requirements, and we're bound by the TPB Code of Professional Conduct.</p>
			</div>
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">How You're Protected</h2>
				<p class="text-slate-500 leading-relaxed mb-0">Working with a registered agent gives you access to safe harbour protection for certain penalties, extended lodgment windows, and the assurance of professional indemnity insurance. Your obligations stay yours — but the workload and the worry become ours.</p>
			</div>
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Your Records, Audit-Ready</h2>
				<p class="text-slate-500 leading-relaxed mb-0">Every transaction coded, every source document attached, every lodgment on time. If the ATO ever comes calling, your file tells a clean, complete story.</p>
			</div>
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Our Formal Statements</h2>
				<p class="text-slate-500 leading-relaxed mb-0">In line with the Tax Agent Services Act 2009, our policy and disclosure statements are available for you to read:</p>
				<div class="flex flex-wrap gap-3 mt-6">
					<a href="https://bookkeepingonthego.net.au/app/uploads/Policy-Statement.pdf" target="_blank" rel="noopener" class="btn btn-outline px-5 py-2.5 text-sm">Policy Statement</a>
					<a href="https://bookkeepingonthego.net.au/app/uploads/Tax-Agents-Services-Act-2009-Disclosure-Statement.pdf" target="_blank" rel="noopener" class="btn btn-outline px-5 py-2.5 text-sm">TASA 2009 Disclosure</a>
				</div>
			</div>
		</div>
	</section>

	<?php echo bootg_compliance_cta( 'Want compliance handled properly?' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

function bootg_dates_to_remember_items() {
	return array(
		array( 'tag' => 'Monthly', 'title' => '21st — Monthly BAS', 'body' => 'Monthly activity statements lodge and pay by the 21st of the following month.' ),
		array( 'tag' => 'Quarterly', 'title' => '28th — BAS & Super', 'body' => 'Quarterly BAS for self-lodgers: 28 Oct, 28 Feb, 28 Apr, 28 Jul.' ),
		array( 'tag' => 'Agent Perk', 'title' => 'Agent Lodgment Extensions', 'body' => 'Registered agent electronic lodgment extends quarterly BAS to 25 Aug, 25 Nov, 29 Mar and 26 May.' ),
		array( 'tag' => 'Yearly', 'title' => '14 July — STP Finalisation', 'body' => 'Finalise Single Touch Payroll data so employee income statements become "tax ready".' ),
		array( 'tag' => 'Yearly', 'title' => '31 October — Tax Returns', 'body' => 'Self-lodger deadline — or engage a registered agent by this date to extend to 15 May.' ),
		array( 'tag' => 'Yearly', 'title' => '28 August — TPAR', 'body' => 'Taxable Payments Annual Report for construction, cleaning, courier, IT and security businesses.' ),
	);
}

function bootg_render_dates_to_remember() {
	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> Compliance Resources <span class="mx-2">/</span> <span class="text-white">Dates to Remember</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">Dates to <span class="text-action">Remember</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8 reveal reveal-d2">Compliance deadlines worth circling — or better, worth handing to us.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="listing-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php foreach ( bootg_dates_to_remember_items() as $i => $item ) :
					$delay = $i % 3 === 1 ? ' reveal-d1' : ( $i % 3 === 2 ? ' reveal-d2' : '' );
					?>
					<div class="bg-mist rounded-xl border border-slate-200 p-7 flex flex-col service-block reveal<?php echo esc_attr( $delay ); ?>">
						<span class="bg-action/10 text-action text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1 self-start mb-4"><?php echo esc_html( $item['tag'] ); ?></span>
						<h3 class="text-lg font-bold text-navy mb-2"><?php echo esc_html( $item['title'] ); ?></h3>
						<p class="text-sm text-slate-500 leading-relaxed mb-0"><?php echo esc_html( $item['body'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php echo bootg_compliance_cta( 'Hand us the calendar' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

/** One-click creation of the Compliance pages. */
function bootg_create_compliance_page( $slug, $title, $shortcode ) {
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
		'page_template' => 'page-full-width.html',
	), true );
	return is_wp_error( $page_id ) ? 'error' : 'created';
}

add_action( 'admin_post_bootg_create_compliance_pages', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_create_compliance_pages' );

	$ato   = bootg_create_compliance_page( 'ato-compliance', 'ATO Compliance', '[bootg_ato_compliance]' );
	$dates = bootg_create_compliance_page( 'dates-to-remember', 'Dates to Remember', '[bootg_dates_to_remember]' );

	$result = ( 'error' === $ato || 'error' === $dates ) ? 'error' : ( ( 'created' === $ato || 'created' === $dates ) ? 'created' : 'exists' );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_compliance_pages' => $result ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
