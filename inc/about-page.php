<?php
/**
 * About Us page — hero + story + values + founder + certifications + CTA.
 * Exposed as [bootg_about_page], same shortcode pattern as the homepage
 * sections and the contact page: lives in a normal WP Page's content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	add_shortcode( 'bootg_about_page', 'bootg_render_about_page' );
} );

function bootg_render_about_page() {
	$phone      = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero" style="background-image:linear-gradient(rgba(42,22,56,.92),rgba(42,22,56,.82)),url('https://bookkeepingonthego.net.au/app/uploads/Bookkeeping-on-the-go-Qualified-Bookkeeping-and-BASIAS-Services-in-Perth-and-Beyond.jpg');background-size:cover;background-position:center">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white">About Us</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">Qualified Bookkeeping in Perth, <span class="text-action">and Beyond</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl reveal reveal-d2">We have decades of experience supporting small and medium-sized businesses in Perth and surrounding areas — and we're now virtual, helping any Australian business remotely.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="about-story-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
			<div class="reveal">
				<p class="chapter-tag"><span class="chapter-num">01</span><span class="chapter-label">Our Story</span></p>
				<h2 class="text-3xl sm:text-4xl font-extrabold text-navy mb-5">Big-Company Expertise, Small-Business Care</h2>
				<p class="text-slate-500 leading-relaxed mb-4">Bookkeeping On The Go started with a simple idea: small businesses deserve the same calibre of financial support as the big end of town — without the big-firm price tag or the jargon.</p>
				<p class="text-slate-500 leading-relaxed mb-4">Today we deliver professional, experienced bookkeeping, payroll, and BAS services from Perth to clients right across Australia, maintaining the high level of customer service we're known for.</p>
				<p class="text-slate-500 leading-relaxed">We always keep your business's best interests in mind — whether that means rescuing overdue books, streamlining your payroll, or getting your nonprofit audit-ready.</p>
			</div>
			<div class="space-y-4 reveal reveal-d1">
				<div class="bg-mist rounded-xl border border-slate-200 p-6 flex items-center gap-5">
					<div class="w-12 h-12 rounded-lg bg-navy text-white flex items-center justify-center shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
					</div>
					<div>
						<p class="font-bold text-navy">Decades of Experience</p>
						<p class="text-sm text-slate-500">Supporting SMBs across every industry, from tradies to nonprofits.</p>
					</div>
				</div>
				<div class="bg-mist rounded-xl border border-slate-200 p-6 flex items-center gap-5">
					<div class="w-12 h-12 rounded-lg bg-action text-white flex items-center justify-center shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
					</div>
					<div>
						<p class="font-bold text-navy">100% Virtual, Australia-Wide</p>
						<p class="text-sm text-slate-500">Perth-based, serving clients remotely wherever they are.</p>
					</div>
				</div>
				<div class="bg-mist rounded-xl border border-slate-200 p-6 flex items-center gap-5">
					<div class="w-12 h-12 rounded-lg bg-navydeep text-white flex items-center justify-center shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
					</div>
					<div>
						<p class="font-bold text-navy">Registered BAS Agent</p>
						<p class="text-sm text-slate-500">Registered with the Tax Practitioners Board — compliance you can trust.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-mist border-y border-slate-200" data-testid="about-values-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="max-w-2xl mb-12 reveal">
				<p class="chapter-tag"><span class="chapter-num">02</span><span class="chapter-label">How We Work</span></p>
				<h2 class="text-3xl sm:text-4xl font-extrabold text-navy">What You Can Count On</h2>
			</div>
			<div class="grid md:grid-cols-3 gap-6">
				<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 reveal">
					<h3 class="text-xl font-bold text-navy mb-3">Accuracy First</h3>
					<p class="text-sm text-slate-500 leading-relaxed">Every transaction coded correctly, every reconciliation checked, every lodgment on time. Precision is the whole point.</p>
				</div>
				<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 reveal reveal-d1">
					<h3 class="text-xl font-bold text-navy mb-3">Plain-English Advice</h3>
					<p class="text-sm text-slate-500 leading-relaxed">We translate the numbers into decisions you can act on — no accounting degree required.</p>
				</div>
				<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 reveal reveal-d2">
					<h3 class="text-xl font-bold text-navy mb-3">Your Business, First</h3>
					<p class="text-sm text-slate-500 leading-relaxed">Your best interests drive every recommendation, from software choices to compliance strategy.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="about-founder-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="bg-mist rounded-2xl border border-slate-200 p-8 lg:p-12 grid lg:grid-cols-12 gap-8 items-center reveal">
				<div class="lg:col-span-3">
					<img src="https://bookkeepingonthego.net.au/app/uploads/Natalie-img.jpg" alt="Natalie Adams — Founder, Bookkeeping On The Go" class="w-32 h-32 rounded-full object-cover mx-auto lg:mx-0 ring-4 ring-white shadow-xl">
				</div>
				<div class="lg:col-span-9 text-center lg:text-left">
					<p class="chapter-tag"><span class="chapter-num">03</span><span class="chapter-label">Meet The Founder</span></p>
					<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mb-3">Natalie Adams</h2>
					<p class="text-slate-500 leading-relaxed mb-6 max-w-2xl">Founder of Bookkeeping On The Go and a registered BAS agent, Natalie has spent decades helping small and medium-sized businesses get — and stay — on top of their finances. She leads a certified team across Xero, MYOB and QuickBooks, with specialist expertise in payroll and nonprofit compliance.</p>
					<a href="https://www.linkedin.com/in/natalie-adams-0159b582" target="_blank" rel="noopener" class="btn btn-outline px-6 py-3 text-sm">
						<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16"><path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/></svg>
						Connect on LinkedIn
					</a>
				</div>
			</div>
		</div>
	</section>

	<section class="py-14 bg-mist border-t border-slate-200" data-testid="about-certifications-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<p class="uppercase text-slate-400 text-xs font-bold tracking-[0.2em] mb-8">Our Certifications &amp; Partnerships</p>
			<div class="flex flex-wrap justify-center items-center gap-x-10 gap-y-6">
				<?php foreach ( array_keys( bootg_partner_logo_defs() ) as $logo_key ) : ?>
					<?php echo bootg_render_partner_logo_img( $logo_key ); // phpcs:ignore ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-navydeep text-white" data-testid="about-cta-band">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Let's Talk About Your Business</h2>
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

/** One-click creation of the "About Us" page (slug: about) with the shortcode as its content. */
add_action( 'admin_post_bootg_create_about_page', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_create_about_page' );

	$existing = get_page_by_path( 'about' );
	if ( $existing ) {
		wp_safe_redirect( add_query_arg( array( 'page' => 'bootg-content-tools', 'bootg_about_page' => 'exists' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	$page_id = wp_insert_post( array(
		'post_type'     => 'page',
		'post_title'    => 'About Us',
		'post_name'     => 'about',
		'post_status'   => 'publish',
		'post_content'  => '[bootg_about_page]',
		'page_template' => 'page-full-width',
	), true );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_about_page' => is_wp_error( $page_id ) ? 'error' : 'created' ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
