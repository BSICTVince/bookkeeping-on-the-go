<?php
/**
 * Contact page — hero + contact-details card + the site's Contact Form,
 * built with the Forms engine (inc/forms/) so submissions are stored as
 * entries in wp-admin in addition to the wp_mail() notification. Exposed
 * as [bootg_contact_page] so it can live in a normal WP Page's content,
 * same shortcode pattern as the homepage sections.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	add_shortcode( 'bootg_contact_page', 'bootg_render_contact_page' );
} );

function bootg_contact_topics() {
	return array(
		'Bookkeeping & Reconciliations',
		'BAS / IAS Lodgments',
		'Payroll & STP',
		'Cloud Bookkeeping Setup',
		'Nonprofit / Public Trustee Reporting',
		'Something else',
	);
}

function bootg_render_contact_page() {
	$phone      = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );
	$email      = bootg_get_option( 'email' );
	$address    = bootg_get_option( 'address' ) ?: 'Perth WA & virtual, Australia-wide';

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white">Contact</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5">Let's <span class="text-action">Talk</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl">Get in touch for a free, no-obligation consultation today. We'll get back to you within one business day.</p>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-mist">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-5 gap-8 items-start">

			<div class="lg:col-span-2 space-y-6 reveal">
				<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
					<h2 class="text-xl font-bold text-navy mb-6">Contact Details</h2>
					<?php if ( $phone ) : ?>
						<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="flex items-center gap-4 mb-5 group">
							<div class="w-11 h-11 rounded-lg bg-action/10 text-action flex items-center justify-center shrink-0 group-hover:bg-action group-hover:text-white transition-colors">
								<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16"><path d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/></svg>
							</div>
							<div>
								<p class="text-xs font-bold tracking-widest uppercase text-slate-400">Phone</p>
								<p class="font-bold text-navy group-hover:text-action transition-colors"><?php echo esc_html( $phone ); ?></p>
							</div>
						</a>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<a href="mailto:<?php echo esc_attr( $email ); ?>" class="flex items-center gap-4 mb-5 group">
							<div class="w-11 h-11 rounded-lg bg-action/10 text-action flex items-center justify-center shrink-0 group-hover:bg-action group-hover:text-white transition-colors">
								<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
							</div>
							<div>
								<p class="text-xs font-bold tracking-widest uppercase text-slate-400">Email</p>
								<p class="font-bold text-navy group-hover:text-action transition-colors break-all"><?php echo esc_html( $email ); ?></p>
							</div>
						</a>
					<?php endif; ?>
					<div class="flex items-center gap-4">
						<div class="w-11 h-11 rounded-lg bg-action/10 text-action flex items-center justify-center shrink-0">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
						</div>
						<div>
							<p class="text-xs font-bold tracking-widest uppercase text-slate-400">Service Area</p>
							<p class="font-bold text-navy"><?php echo esc_html( $address ); ?></p>
						</div>
					</div>
				</div>

				<div class="bg-navy rounded-xl p-8 text-white">
					<h3 class="font-bold text-lg mb-4">What To Expect</h3>
					<ul class="space-y-3 text-sm text-white/80">
						<li class="flex gap-3"><?php echo bootg_check_icon(); // phpcs:ignore ?> Free, no-obligation consultation</li>
						<li class="flex gap-3"><?php echo bootg_check_icon(); // phpcs:ignore ?> Response within one business day</li>
						<li class="flex gap-3"><?php echo bootg_check_icon(); // phpcs:ignore ?> Plain-English advice, zero pressure</li>
					</ul>
				</div>
			</div>

			<div class="lg:col-span-3 reveal reveal-d1">
				<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 lg:p-10">
					<h2 class="text-2xl font-extrabold text-navy mb-2">Send Us a Message</h2>
					<p class="text-sm text-slate-500 mb-8">Tell us a little about your business and what you need help with.</p>
					<?php echo bootg_render_form( bootg_get_contact_form_id() ); // phpcs:ignore ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** One-click creation of the "Contact" page (slug: contact) with the shortcode as its content. */
add_action( 'admin_post_bootg_create_contact_page', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_create_contact_page' );

	$existing = get_page_by_path( 'contact' );
	if ( $existing ) {
		wp_safe_redirect( add_query_arg( array( 'page' => 'bootg-content-tools', 'bootg_contact_page' => 'exists' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	$page_id = wp_insert_post( array(
		'post_type'     => 'page',
		'post_title'    => 'Contact',
		'post_name'     => 'contact',
		'post_status'   => 'publish',
		'post_content'  => '[bootg_contact_page]',
		'page_template' => 'page-full-width',
	), true );

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_contact_page' => is_wp_error( $page_id ) ? 'error' : 'created' ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
