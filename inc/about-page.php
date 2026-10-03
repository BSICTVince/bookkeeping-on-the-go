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
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero" style="background-image:linear-gradient(rgba(42,22,56,.92),rgba(42,22,56,.82)),url('<?php echo esc_url( get_theme_file_uri( 'assets/images/services/perth-bookkeeping-bas.jpg' ) ); ?>');background-size:cover;background-position:center">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white">About Us</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">Bookkeeping for Your <span class="text-action">Business</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl reveal reveal-d2">Natalie Adams established Bookkeeping On The Go in 2002 to help business owners succeed with high quality service and expert advice — a promise we've kept ever since.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="about-story-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
			<div class="reveal">
				<p class="chapter-tag"><span class="chapter-num">01</span><span class="chapter-label">Our Story</span></p>
				<h2 class="text-3xl sm:text-4xl font-extrabold text-navy mb-5">Bookkeeping and BAS Specialists in Perth &amp; Beyond</h2>
				<p class="text-ink leading-relaxed mb-4">Natalie Adams established Bookkeeping On The Go in 2002 as an independent bookkeeping and BAS service. Her vision was to help business owners by providing high quality service and expert advice. Natalie has stayed true to that since day one.</p>
				<p class="text-ink leading-relaxed mb-4">In that time, we've gained extensive experience and helped countless clients grow. Our diverse expertise covers a broad range of industries including real estate, security, building and construction, manufacturing, mechanical, transport, hospitality, labour hire, irrigation and trades, and many more.</p>
				<p class="text-ink leading-relaxed">Our team is committed to providing complete and holistic bookkeeping services so you have everything you need to succeed. We value integrity, professionalism, strong attention to detail, and have only our clients' best interests in mind.</p>
			</div>
			<div class="space-y-4 reveal reveal-d1">
				<div class="bg-mist rounded-xl border border-slate-200 p-6 flex items-center gap-5">
					<div class="w-12 h-12 rounded-lg bg-navy text-white flex items-center justify-center shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
					</div>
					<div>
						<p class="font-bold text-navy">Est. 2002</p>
						<p class="text-sm text-ink">Founded by Natalie Adams to help business owners succeed with high quality service and expert advice.</p>
					</div>
				</div>
				<div class="bg-mist rounded-xl border border-slate-200 p-6 flex items-center gap-5">
					<div class="w-12 h-12 rounded-lg bg-action text-white flex items-center justify-center shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
					</div>
					<div>
						<p class="font-bold text-navy">Gosnells, WA — or Fully Remote</p>
						<p class="text-sm text-ink">Happy to work at our office or yours if you're local, or completely remotely if you prefer.</p>
					</div>
				</div>
				<div class="bg-mist rounded-xl border border-slate-200 p-6 flex items-center gap-5">
					<div class="w-12 h-12 rounded-lg bg-navydeep text-white flex items-center justify-center shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
					</div>
					<div>
						<p class="font-bold text-navy">Registered BAS Agent</p>
						<p class="text-sm text-ink">Registered with the Tax Practitioners Board, holding a Certificate IV in Bookkeeping and Professional Indemnity Insurance.</p>
					</div>
				</div>
				<div class="bg-mist rounded-xl border border-slate-200 p-6 flex items-center gap-5">
					<div class="w-12 h-12 rounded-lg bg-navy text-white flex items-center justify-center shrink-0">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-15H6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 006 21h12a2.25 2.25 0 002.25-2.25V6.75L15.75 3z"/></svg>
					</div>
					<div>
						<p class="font-bold text-navy">Full-Service Support</p>
						<p class="text-sm text-ink">We liaise with your accountant, the ATO, and other regulators on your behalf, and prepare accurate financial reports — plus the small stuff like filing and debtors statements.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-mist border-y border-slate-200" data-testid="about-values-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="max-w-2xl mb-12 reveal">
				<p class="chapter-tag"><span class="chapter-num">02</span><span class="chapter-label">Our Mission</span></p>
				<h2 class="text-3xl sm:text-4xl font-extrabold text-navy mb-4">Mission Statement</h2>
				<p class="text-ink leading-relaxed">We provide peace of mind and high quality bookkeeping services to every client. We empower you to make the best possible decisions for your business by providing you the tools and information you need.</p>
			</div>
			<p class="uppercase text-ink text-xs font-bold tracking-[0.2em] mb-6">Company Values</p>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
				<?php
				$about_values = array( 'Personal Integrity', 'Professional Ethics', 'Accuracy', 'Accountability', 'Commitment', 'Excellent Customer Service', 'Positive Attitude' );
				foreach ( $about_values as $i => $value ) :
					$delay = 0 === $i % 4 ? '' : ( ' reveal-d' . ( $i % 3 + 1 ) );
					?>
					<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 text-center reveal<?php echo esc_attr( $delay ); ?>">
						<h3 class="text-base font-bold text-navy mb-0"><?php echo esc_html( $value ); ?></h3>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white" data-testid="about-founder-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="bg-mist rounded-2xl border border-slate-200 p-8 lg:p-12 grid lg:grid-cols-12 gap-8 items-center reveal">
				<div class="lg:col-span-3">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/team/natalie.jpg' ) ); ?>" alt="Natalie Adams — Founder, Bookkeeping On The Go" class="w-32 h-32 rounded-full object-cover mx-auto lg:mx-0 ring-4 ring-white shadow-xl">
				</div>
				<div class="lg:col-span-9 text-center lg:text-left">
					<p class="chapter-tag"><span class="chapter-num">03</span><span class="chapter-label">Meet The Founder</span></p>
					<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mb-3">Natalie Adams</h2>
					<p class="text-ink leading-relaxed mb-6 max-w-2xl">Natalie founded Bookkeeping On The Go in 2002, driven by a vision to help business owners succeed through high quality service and expert advice — a promise she's kept every day since. She and her team are registered BAS Agents with the Tax Practitioners Board, hold a Certificate IV in Bookkeeping, and carry Professional Indemnity Insurance, so you can feel confident in the support and knowledge guiding your business toward success.</p>
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
			<p class="uppercase text-ink text-xs font-bold tracking-[0.2em] mb-8">Our Certifications &amp; Partnerships</p>
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
			<p class="text-base md:text-lg text-white/60 max-w-xl mx-auto mb-8">Working with us is like adding a member to your team. We're invested in your success, and bring our best to help you succeed. Set up a free consultation to learn more about how we can work together to achieve your goals.</p>
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

/** One-click creation of the "About Us" page (slug: about) with the shortcode as its content. Returns 'exists', 'created', or 'error'. */
function bootg_create_about_page() {
	$existing = get_page_by_path( 'about' );
	if ( $existing ) {
		return 'exists';
	}

	$page_id = wp_insert_post( array(
		'post_type'     => 'page',
		'post_title'    => 'About Us',
		'post_name'     => 'about',
		'post_status'   => 'publish',
		'post_content'  => '[bootg_about_page]',
		'page_template' => 'page-full-width',
	), true );

	return is_wp_error( $page_id ) ? 'error' : 'created';
}

add_action( 'admin_post_bootg_create_about_page', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_create_about_page' );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_about_page' => bootg_create_about_page() ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
