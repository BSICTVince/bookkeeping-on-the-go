<?php
/**
 * Homepage Content — classic admin page. The homepage body is an ordered
 * list of raw-HTML sections ("Section 1", "Section 2", ...), each a plain
 * textarea you can edit directly. Add/remove sections with the buttons;
 * drop [bootg_services_grid], [bootg_partner_logos],
 * [bootg_testimonial_spotlight], or [bootg_latest_post] into any section
 * to embed the live, CPT-driven pieces. No ACF, no page builder, no block
 * editor involved — just HTML you write, saved via the core Settings API.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOOTG_HOME_SECTIONS_OPTION', 'bootg_home_sections' );
define( 'BOOTG_HOME_SEO_OPTION', 'bootg_home_seo' );
define( 'BOOTG_HERO_IMAGE_OPTION', 'bootg_hero_image_media' );
define( 'BOOTG_HERO_IMAGE_SRC', 'https://bookkeepingonthego.net.au/app/uploads/BOOKKEEPING-ON-THE-GO-STAY-COMPLIANT-WHILE-GETTING-YOUR-TIME-BACK.jpg' );

/** Local (sideloaded) URL for the homepage hero image if we have one, else the original external URL. */
function bootg_hero_image_url() {
	$media_id = get_option( BOOTG_HERO_IMAGE_OPTION );
	if ( $media_id ) {
		$url = wp_get_attachment_image_url( $media_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return BOOTG_HERO_IMAGE_SRC;
}

/** One-click sideload of the homepage hero image into the Media Library. Safe to run again. */
add_action( 'admin_post_bootg_migrate_hero_image', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_migrate_hero_image' );

	if ( ! function_exists( 'media_sideload_image' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$existing = get_option( BOOTG_HERO_IMAGE_OPTION );
	if ( ! $existing || ! get_post( $existing ) ) {
		$attachment_id = media_sideload_image( BOOTG_HERO_IMAGE_SRC, 0, 'Bookkeeping On The Go — hero', 'id' );
		if ( ! is_wp_error( $attachment_id ) ) {
			update_option( BOOTG_HERO_IMAGE_OPTION, $attachment_id );
		}
	}

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_hero_image' => 'done' ),
		admin_url( 'themes.php' )
	) );
	exit;
} );

function bootg_home_default_sections() {
	$contact_url  = bootg_page_url( 'contact' );
	$services_url = bootg_page_url( 'services' );

	return array(
		// Section 1 — Hero
		'
<section class="relative hero-scrub" data-testid="hero-section">
	<div class="hero-pin relative overflow-hidden bg-mist border-b border-slate-200">
	<div class="hero-bg-media hero-bg-media--photo" aria-hidden="true">
		<img src="' . esc_url( bootg_hero_image_url() ) . '" alt="" loading="eager" fetchpriority="high">
	</div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-20 lg:pt-24 lg:pb-36 w-full">
		<div class="grid lg:grid-cols-12 gap-14 items-center">
			<div class="lg:col-span-7">
				<span class="reveal inline-block bg-navy text-white text-xs sm:text-sm font-semibold tracking-wide uppercase rounded-full px-4 py-2 mb-7" data-testid="hero-badge">Professional Bookkeepers Perth &amp; Beyond</span>
				<h1 class="reveal reveal-d1 text-5xl sm:text-6xl lg:text-[3.6rem] xl:text-[4.3rem] font-extrabold text-navy leading-[1.05] tracking-tight mb-6">
					Reclaim your <em>weekends.</em><br>
					<span class="text-action">Stay 100% compliant.</span>
				</h1>
				<p class="reveal reveal-d1 text-base md:text-lg leading-relaxed mb-9 max-w-xl" style="color:#564D60">We empower small-to-medium businesses and nonprofits across Australia with stress-free, professional bookkeeping, payroll, and BAS/IAS services.</p>
				<div class="reveal reveal-d2 flex flex-wrap gap-4">
					<a href="' . esc_url( $contact_url ) . '" class="btn btn-primary px-7 py-3.5 text-base">Book a Free Consultation</a>
					<a href="' . esc_url( $services_url ) . '" class="btn btn-outline px-7 py-3.5 text-base">Explore Our Services</a>
				</div>
				<div class="reveal reveal-d3 mt-10 flex flex-wrap items-center gap-x-8 gap-y-3 text-sm font-semibold" style="color:#6E6478">
					<span class="flex items-center gap-2"><svg class="w-4 h-4 text-action" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> Registered BAS Agent</span>
					<span class="flex items-center gap-2"><svg class="w-4 h-4 text-action" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> Xero Gold Partner</span>
					<span class="flex items-center gap-2"><svg class="w-4 h-4 text-action" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> No-obligation consult</span>
				</div>
			</div>
			<div class="lg:col-span-5">
				<div class="hero-stage">
					<figure class="hero-frame">
						<img src="https://bookkeepingonthego.net.au/app/uploads/Bookkeeping-on-the-go-Qualified-Bookkeeping-and-BASIAS-Services-in-Perth-and-Beyond.jpg" alt="Qualified bookkeeping and BAS/IAS services in Perth and beyond">
					</figure>
					<div class="bg-white rounded-xl border p-6 relative mt-6 lg:mt-0 lg:absolute lg:-bottom-10 lg:-left-12 lg:max-w-[19rem] z-10" style="box-shadow:0 30px 60px -25px rgba(42,22,56,.35);border-color:#EAE1F1">
						<div class="absolute -top-3 left-6 bg-action text-white text-[11px] font-bold tracking-widest uppercase rounded-full px-3 py-1">Free Setup Review</div>
						<h3 class="text-lg font-bold text-navy mb-1.5 mt-1">Ready to Switch to Cloud Bookkeeping?</h3>
						<p class="text-sm leading-relaxed mb-5" style="color:#6E6478">Let our certified team configure the perfect software stack for your business workflow.</p>
						<a href="' . esc_url( $contact_url ) . '" class="btn btn-dark w-full py-3 text-sm tracking-wide">Help Me Get Set Up</a>
						<p class="text-xs text-center mt-3 mb-0" style="color:#8F8699">Response within one business day</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</section>',

		// Section 2 — Trusted partners (dynamic)
		'[bootg_partner_logos]',

		// Section 3 — Story
		'
<section class="relative overflow-hidden bg-navydeep text-white py-20 lg:py-28" data-testid="story-section">
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-14">
		<div class="lg:sticky lg:top-32 self-start reveal">
			<p class="chapter-tag chapter-light"><span class="chapter-num">01</span><span class="chapter-label">Sound Familiar?</span></p>
			<h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.1] mb-6">Your weekends weren\'t meant for <span class="text-action">bookkeeping.</span></h2>
			<p class="text-white/60 text-base md:text-lg leading-relaxed mb-8 max-w-md">Most business owners we meet are drowning in receipts, guessing at GST, and losing Sundays to payroll. It doesn\'t have to be your story.</p>
			<a href="' . esc_url( $contact_url ) . '" class="btn btn-primary px-7 py-3.5 text-base">Change The Story</a>
		</div>
		<div class="space-y-5">
			<div class="pain-card flex gap-5 reveal">
				<div class="pain-icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div>
				<div>
					<h3 class="font-bold text-lg mb-1.5">Receipts piling up</h3>
					<p class="text-sm text-white/60 leading-relaxed">The shoebox is overflowing, deductions are being missed, and every BAS-audit nightmare starts with "I\'ll sort it later".</p>
				</div>
			</div>
			<div class="pain-card flex gap-5 reveal reveal-d1">
				<div class="pain-icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
				<div>
					<h3 class="font-bold text-lg mb-1.5">BAS deadline dread</h3>
					<p class="text-sm text-white/60 leading-relaxed">Every quarter the date creeps up. You rush the numbers, cross your fingers, and hope the ATO stays happy.</p>
				</div>
			</div>
			<div class="pain-card flex gap-5 reveal reveal-d2">
				<div class="pain-icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg></div>
				<div>
					<h3 class="font-bold text-lg mb-1.5">Payroll eating your Sundays</h3>
					<p class="text-sm text-white/60 leading-relaxed">STP, super, awards, leave — hours of admin every week that keeps you from your family and your actual work.</p>
				</div>
			</div>
			<div class="rounded-2xl p-7 bg-action text-white reveal reveal-d3">
				<h3 class="font-extrabold text-xl mb-1.5">Now imagine it all just&hellip; handled.</h3>
				<p class="text-sm text-white/85 leading-relaxed mb-0">Accurate books, on-time lodgments, payroll that runs itself — and your weekends back. That\'s the chapter we write next.</p>
			</div>
		</div>
	</div>
</section>',

		// Section 4 — Stats
		'
<section class="py-16 lg:py-20 bg-white border-b border-slate-200">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid grid-cols-2 lg:grid-cols-4 gap-10 text-center">
			<div class="reveal">
				<p class="stat-num mb-2">20+</p>
				<p class="text-sm font-semibold text-slate-500">Years combined experience</p>
			</div>
			<div class="reveal reveal-d1">
				<p class="stat-num mb-2">500+</p>
				<p class="text-sm font-semibold text-slate-500">BAS &amp; IAS lodgments</p>
			</div>
			<div class="reveal reveal-d2">
				<p class="stat-num mb-2">' . (int) wp_count_posts( 'integration' )->publish . '+</p>
				<p class="text-sm font-semibold text-slate-500">Certified platform partners</p>
			</div>
			<div class="reveal reveal-d3">
				<p class="stat-num mb-2">AU</p>
				<p class="text-sm font-semibold text-slate-500">Virtual, Australia-wide</p>
			</div>
		</div>
	</div>
</section>',

		// Section 5 — Services grid (dynamic)
		'[bootg_services_grid]',

		// Section 6 — Process timeline
		'
<section class="py-16 lg:py-24 bg-mist border-y border-slate-200">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-12 gap-12">
		<div class="lg:col-span-5 lg:sticky lg:top-32 self-start reveal">
			<p class="chapter-tag"><span class="chapter-num">03</span><span class="chapter-label">How It Works</span></p>
			<h2 class="text-3xl sm:text-4xl font-extrabold text-navy mb-5">Three Steps to Sorted Books</h2>
			<p class="text-slate-500 leading-relaxed mb-8">No jargon, no lock-in surprises. Just a clear path from where your books are now to where they should be.</p>
			<a href="' . esc_url( $contact_url ) . '" class="btn btn-primary px-7 py-3.5 text-base">Start With Step One</a>
		</div>
		<div class="lg:col-span-7">
			<div class="timeline" id="processTimeline">
				<div class="timeline_track"><div class="timeline_fill" id="timelineFill"></div></div>
				<div class="timeline_step reveal">
					<div class="timeline_dot"></div>
					<p class="step-num mb-3">01</p>
					<h3 class="text-xl font-bold text-navy mb-2">Free Consultation</h3>
					<p class="text-slate-500 leading-relaxed">Tell us about your business and where your books are at. We listen first, then give you an honest read — no obligation, no jargon.</p>
				</div>
				<div class="timeline_step reveal reveal-d1">
					<div class="timeline_dot"></div>
					<p class="step-num mb-3">02</p>
					<h3 class="text-xl font-bold text-navy mb-2">Tailored Setup</h3>
					<p class="text-slate-500 leading-relaxed">We configure the right cloud stack — Xero, MYOB or QuickBooks, with Dext or Hubdoc doing the paperwork — and take over the day-to-day at a pace that suits you.</p>
				</div>
				<div class="timeline_step reveal reveal-d2" style="padding-bottom:0">
					<div class="timeline_dot"></div>
					<p class="step-num mb-3">03</p>
					<h3 class="text-xl font-bold text-navy mb-2">Ongoing Support</h3>
					<p class="text-slate-500 leading-relaxed">Accurate books, on-time BAS, payroll that runs itself, and a team on call whenever you need answers. Weekends: officially reclaimed.</p>
				</div>
			</div>
		</div>
	</div>
</section>',

		// Section 7 — Testimonial spotlight (dynamic)
		'[bootg_testimonial_spotlight]',

		// Section 8 — Latest post / resources (dynamic)
		'[bootg_latest_post]',

		// Section 9 — Final CTA
		'
<section class="relative overflow-hidden py-20 lg:py-28 bg-navydeep text-white" data-testid="home-cta-band">
	<div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
		<p class="chapter-tag chapter-light"><span class="chapter-num">06</span><span class="chapter-label">Your Next Chapter</span></p>
		<h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.1] mb-5">Your next chapter starts with <span class="text-action">sorted books.</span></h2>
		<p class="text-base md:text-lg text-white/60 max-w-xl mx-auto mb-9">Book a free, no-obligation consultation and find out exactly what stress-free bookkeeping looks like for your business.</p>
		<div class="flex flex-wrap justify-center gap-4">
			<a href="' . esc_url( $contact_url ) . '" class="btn btn-primary px-8 py-4 text-base">Book a Free Consultation</a>
			<a href="tel:' . esc_attr( bootg_get_option( 'phone_link' ) ) . '" class="btn btn-outline-light px-8 py-4 text-base">Call ' . esc_html( bootg_get_option( 'phone' ) ) . '</a>
		</div>
	</div>
</section>',
	);
}

function bootg_home_default_seo() {
	return array(
		'title'       => 'Bookkeeping On The Go — Perth Bookkeepers & Nationwide Virtual Experts',
		'description' => 'Stress-free, professional bookkeeping, payroll, and BAS/IAS services for small-to-medium businesses and nonprofits across Australia.',
		'keywords'    => 'bookkeeping perth, bas agent, payroll services, xero bookkeeper, cloud accounting',
	);
}

add_action( 'admin_menu', function () {
	$hook = add_theme_page( 'Homepage Content', 'Homepage Content', 'manage_options', 'bootg-homepage-sections', 'bootg_render_homepage_sections_page' );
	add_action( 'admin_enqueue_scripts', function ( $current_hook ) use ( $hook ) {
		if ( $current_hook !== $hook ) {
			return;
		}
		// Same drag-and-drop library core uses on Appearance > Menus.
		wp_enqueue_script( 'jquery-ui-sortable' );
	} );
} );

add_action( 'admin_init', function () {
	register_setting( 'bootg_home_sections_group', BOOTG_HOME_SECTIONS_OPTION, 'bootg_sanitize_home_sections' );
	register_setting( 'bootg_home_sections_group', BOOTG_HOME_SEO_OPTION, 'bootg_sanitize_home_seo' );
} );

function bootg_sanitize_home_sections( $input ) {
	$sections = array();
	if ( is_array( $input ) ) {
		foreach ( $input as $row ) {
			$html = $row['html'] ?? '';
			if ( '' === trim( $html ) ) {
				continue;
			}
			// Full HTML preserved for admins (trusted, capability-gated) — matches
			// core's own unfiltered_html behavior for the same role.
			$sections[] = array(
				'label' => sanitize_text_field( wp_unslash( $row['label'] ?? '' ) ),
				'html'  => current_user_can( 'unfiltered_html' ) ? wp_unslash( $html ) : wp_kses_post( wp_unslash( $html ) ),
			);
		}
	}
	return $sections ?: bootg_home_default_sections_wrapped();
}

/** Friendly default labels, paired positionally with bootg_home_default_sections(). */
function bootg_home_default_section_labels() {
	return array(
		'Hero',
		'Trusted Partners',
		'Story',
		'Stats',
		'Services Grid',
		'Process Timeline',
		'Testimonial Spotlight',
		'Latest Post / Resources',
		'Final CTA',
	);
}

function bootg_home_default_sections_wrapped() {
	$labels = bootg_home_default_section_labels();
	return array_values( array_map( function ( $i, $html ) use ( $labels ) {
		return array( 'label' => $labels[ $i ] ?? '', 'html' => $html );
	}, array_keys( bootg_home_default_sections() ), bootg_home_default_sections() ) );
}

function bootg_sanitize_home_seo( $input ) {
	return array(
		'title'       => sanitize_text_field( $input['title'] ?? '' ),
		'description' => sanitize_textarea_field( $input['description'] ?? '' ),
		'keywords'    => sanitize_text_field( $input['keywords'] ?? '' ),
	);
}

function bootg_get_home_sections() {
	$sections = get_option( BOOTG_HOME_SECTIONS_OPTION, array() );
	return $sections ?: bootg_home_default_sections_wrapped();
}

function bootg_get_home_seo( $key ) {
	$seo = wp_parse_args( get_option( BOOTG_HOME_SEO_OPTION, array() ), bootg_home_default_seo() );
	return $seo[ $key ] ?? '';
}

/** Homepage-specific <title>/meta output (only when the homepage isn't a real singular page). */
add_filter( 'pre_get_document_title', function ( $title ) {
	if ( ! is_front_page() || is_singular() ) {
		return $title;
	}
	return bootg_get_home_seo( 'title' ) ?: $title;
}, 20 );

add_action( 'wp_head', function () {
	if ( ! is_front_page() || is_singular() ) {
		return;
	}
	$description = bootg_get_home_seo( 'description' );
	$keywords    = bootg_get_home_seo( 'keywords' );
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $keywords ) {
		echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">' . "\n";
	}
}, 1 );

/**
 * Renders one <li> row: drag handle + collapsible title bar + HTML textarea.
 * $index is an int for real rows, or the literal string '__INDEX__' when
 * rendering the <template> used by "+ Add Section" (JS swaps it in via
 * renumber(), same approach core uses for the Add Menu Items template).
 */
function bootg_render_section_row( $index, $html, $is_template = false, $label = '' ) {
	$placeholder    = $is_template ? 'Section' : 'Section ' . ( (int) $index + 1 );
	$label_name     = $is_template ? '' : esc_attr( BOOTG_HOME_SECTIONS_OPTION ) . '[' . (int) $index . '][label]';
	$html_name      = $is_template ? '' : esc_attr( BOOTG_HOME_SECTIONS_OPTION ) . '[' . (int) $index . '][html]';
	// Existing sections start collapsed (like Appearance > Menus); a freshly
	// added section (the template) starts open so you can type right away.
	$row_class = $is_template ? 'bootg-section' : 'bootg-section is-collapsed';
	ob_start();
	?>
	<li class="<?php echo esc_attr( $row_class ); ?>">
		<div class="bootg-section-bar">
			<span class="dashicons dashicons-menu bootg-section-handle" title="Drag to reorder"></span>
			<input type="text" class="bootg-section-label" name="<?php echo $label_name; // phpcs:ignore ?>" value="<?php echo esc_attr( $label ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" onclick="event.stopPropagation()">
			<button type="button" class="button-link bootg-section-toggle" aria-label="Toggle section">
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</button>
			<button type="button" class="button bootg-remove-section">Remove</button>
		</div>
		<div class="bootg-section-body">
			<textarea name="<?php echo $html_name; // phpcs:ignore ?>" rows="10" placeholder="<section>...</section> or a shortcode like [bootg_services_grid]"><?php echo esc_textarea( $html ); ?></textarea>
		</div>
	</li>
	<?php
	return ob_get_clean();
}

function bootg_render_homepage_sections_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$sections = bootg_get_home_sections();
	$seo      = wp_parse_args( get_option( BOOTG_HOME_SEO_OPTION, array() ), bootg_home_default_seo() );
	?>
	<div class="wrap">
		<h1>Homepage Content</h1>
		<p class="description">Each section below is raw HTML, rendered top to bottom on the homepage. Add or remove sections freely. Drop <code>[bootg_services_grid]</code>, <code>[bootg_partner_logos]</code>, <code>[bootg_testimonial_spotlight]</code>, or <code>[bootg_latest_post]</code> into any section to embed the live, data-driven pieces.</p>

		<form action="options.php" method="post">
			<?php settings_fields( 'bootg_home_sections_group' ); ?>

			<h2>SEO</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th style="width:220px;"><label for="seo_title">Meta Title</label></th>
					<td><input type="text" id="seo_title" name="<?php echo esc_attr( BOOTG_HOME_SEO_OPTION ); ?>[title]" value="<?php echo esc_attr( $seo['title'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th><label for="seo_description">Meta Description</label></th>
					<td><textarea id="seo_description" name="<?php echo esc_attr( BOOTG_HOME_SEO_OPTION ); ?>[description]" rows="3" class="large-text"><?php echo esc_textarea( $seo['description'] ); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="seo_keywords">Keywords</label></th>
					<td><input type="text" id="seo_keywords" name="<?php echo esc_attr( BOOTG_HOME_SEO_OPTION ); ?>[keywords]" value="<?php echo esc_attr( $seo['keywords'] ); ?>" class="large-text"></td>
				</tr>
			</table>

			<h2>Body — Sections</h2>
			<p class="description">Drag the <span class="dashicons dashicons-menu" style="font-size:16px;vertical-align:text-bottom;"></span> handle to reorder, exactly like Appearance &rarr; Menus. Click a section's title bar to collapse or expand it.</p>
			<ul id="bootg-sections" class="bootg-sections-list">
				<?php foreach ( $sections as $i => $section ) : ?>
					<?php echo bootg_render_section_row( $i, $section['html'], false, $section['label'] ?? '' ); // phpcs:ignore ?>
				<?php endforeach; ?>
			</ul>

			<p>
				<button type="button" class="button" id="bootg-add-section">+ Add Section</button>
				<button type="button" class="button" id="bootg-reset-sections">Reset All Sections to Default</button>
			</p>

			<?php submit_button( 'Save Homepage Content' ); ?>
		</form>
	</div>

	<template id="bootg-section-template">
		<?php echo bootg_render_section_row( '__INDEX__', '', true ); // phpcs:ignore ?>
	</template>

	<style>
	.bootg-sections-list{margin:0;padding:0;list-style:none}
	.bootg-section{background:#fff;border:1px solid #dcdcde;margin-bottom:9px;}
	.bootg-section.ui-sortable-helper{box-shadow:0 4px 14px rgba(0,0,0,.15)}
	.bootg-section-placeholder{background:#f0f0f1;border:1px dashed #c3c4c7;margin-bottom:9px;height:42px}
	.bootg-section-bar{display:flex;align-items:center;gap:8px;padding:8px 12px;cursor:pointer;user-select:none}
	.bootg-section-handle{cursor:move;color:#787c82;padding:2px;flex-shrink:0}
	.bootg-section-handle:hover{color:#1d2327}
	.bootg-section-label{flex:1;font-weight:600;border:1px solid transparent;background:transparent;padding:4px 6px;border-radius:3px;font-size:13px}
	.bootg-section-label:hover{border-color:#dcdcde}
	.bootg-section-label:focus{border-color:#2271b1;background:#fff;outline:none;box-shadow:0 0 0 1px #2271b1}
	.bootg-section-toggle{background:none;border:0;cursor:pointer;color:#787c82;padding:2px;flex-shrink:0;transition:transform .15s ease}
	.bootg-section.is-collapsed .bootg-section-toggle{transform:rotate(-90deg)}
	.bootg-section-body{padding:0 12px 12px;border-top:1px solid #f0f0f1}
	.bootg-section.is-collapsed .bootg-section-body{display:none}
	.bootg-section-body textarea{width:100%;font-family:Consolas,Monaco,monospace;font-size:12px;margin-top:10px}
	</style>

	<script>
	// Deferred to jQuery's ready handler (fires on DOMContentLoaded) rather than
	// running immediately: this inline block is printed in the page body, before
	// wp_footer() outputs jquery-ui-sortable's own <script> tag, so `jQuery.fn.sortable`
	// doesn't exist yet if we check for it right away.
	jQuery(function ($) {
		var wrap = document.getElementById('bootg-sections');
		var optionName = <?php echo wp_json_encode( BOOTG_HOME_SECTIONS_OPTION ); ?>;

		function renumber() {
			var rows = wrap.querySelectorAll('.bootg-section');
			rows.forEach(function (row, i) {
				var labelInput = row.querySelector('.bootg-section-label');
				if (labelInput) {
					labelInput.placeholder = 'Section ' + (i + 1);
					labelInput.setAttribute('name', optionName + '[' + i + '][label]');
				}
				var textarea = row.querySelector('textarea');
				if (textarea) { textarea.setAttribute('name', optionName + '[' + i + '][html]'); }
			});
		}

		function addSection(section) {
			section = section || {};
			var tpl = document.getElementById('bootg-section-template');
			var clone = tpl.content.cloneNode(true);
			if (section.html) { clone.querySelector('textarea').value = section.html; }
			if (section.label) { clone.querySelector('.bootg-section-label').value = section.label; }
			wrap.appendChild(clone);
			renumber();
		}

		document.getElementById('bootg-add-section').addEventListener('click', function () {
			addSection();
		});

		wrap.addEventListener('click', function (e) {
			if (e.target.closest('.bootg-remove-section')) {
				e.preventDefault();
				e.target.closest('.bootg-section').remove();
				renumber();
				return;
			}
			var bar = e.target.closest('.bootg-section-bar');
			if (bar && !e.target.closest('.bootg-remove-section')) {
				bar.closest('.bootg-section').classList.toggle('is-collapsed');
			}
		});

		if (window.jQuery && jQuery.fn.sortable) {
			jQuery(wrap).sortable({
				handle: '.bootg-section-handle',
				axis: 'y',
				placeholder: 'bootg-section-placeholder',
				forcePlaceholderSize: true,
				update: renumber
			});
		}

		document.getElementById('bootg-reset-sections').addEventListener('click', function () {
			if (!confirm('Replace ALL sections with the original default content? This cannot be undone after you save.')) {
				return;
			}
			fetch(ajaxurl + '?action=bootg_get_default_sections', { credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (data) {
					wrap.innerHTML = '';
					data.sections.forEach(function (section) { addSection(section); });
				});
		});
	});
	</script>
	<?php
}

add_action( 'wp_ajax_bootg_get_default_sections', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( null, 403 );
	}
	wp_send_json( array( 'sections' => bootg_home_default_sections_wrapped() ) );
} );
