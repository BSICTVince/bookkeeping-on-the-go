<?php
/**
 * Single/archive page bodies for the content CPTs, ported from the
 * original site's per-service / per-integration page layouts (hero +
 * detail section with feature checklist + CTA band). Registered as
 * server-rendered blocks, referenced from templates/single-*.html and
 * templates/archive-*.html — same pattern as the homepage sections.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_block_type( 'bootg/service-single', array( 'render_callback' => 'bootg_render_service_single' ) );
	register_block_type( 'bootg/integration-single', array( 'render_callback' => 'bootg_render_integration_single' ) );
	register_block_type( 'bootg/team-archive', array( 'render_callback' => 'bootg_render_team_archive' ) );
	register_block_type( 'bootg/testimonial-archive', array( 'render_callback' => 'bootg_render_testimonial_archive' ) );
	register_block_type( 'bootg/service-archive', array( 'render_callback' => 'bootg_render_service_archive' ) );
	register_block_type( 'bootg/integration-archive', array( 'render_callback' => 'bootg_render_integration_archive' ) );
	register_block_type( 'bootg/guide-archive', array( 'render_callback' => 'bootg_render_guide_archive' ) );
	register_block_type( 'bootg/guide-single', array( 'render_callback' => 'bootg_render_guide_single' ) );
} );

function bootg_check_icon() {
	return '<svg class="w-5 h-5 text-action shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>';
}

function bootg_features_list( $raw ) {
	$lines = array_filter( array_map( 'trim', explode( "\n", (string) $raw ) ) );
	if ( ! $lines ) {
		return '';
	}
	$out = '<ul class="space-y-3 mb-8">';
	foreach ( $lines as $line ) {
		$out .= '<li class="check-item flex gap-2">' . bootg_check_icon() . '<span>' . esc_html( $line ) . '</span></li>';
	}
	return $out . '</ul>';
}

function bootg_render_service_single() {
	$post = get_post();
	if ( ! $post || 'service' !== $post->post_type ) {
		return '';
	}
	$summary   = get_post_meta( $post->ID, 'card_summary', true );
	$eyebrow   = get_post_meta( $post->ID, 'eyebrow', true );
	$headline  = get_post_meta( $post->ID, 'hero_headline', true ) ?: get_the_title( $post );
	$features  = bootg_features_list( get_post_meta( $post->ID, 'features', true ) );
	$body      = apply_filters( 'the_content', $post->post_content );
	$contact   = bootg_page_url( 'contact' );
	$services  = get_post_type_archive_link( 'service' ) ?: bootg_page_url( 'services' );
	$phone     = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <a href="<?php echo esc_url( $services ); ?>" class="hover:text-white transition-colors">Services</a> <span class="mx-2">/</span> <span class="text-white"><?php echo esc_html( get_the_title( $post ) ); ?></span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5"><?php echo wp_kses_post( $headline ); // phpcs:ignore ?></h1>
			<?php if ( $summary ) : ?>
				<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8"><?php echo esc_html( $summary ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Book a Free Consultation</a>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-12 gap-10 items-start">
			<div class="lg:col-span-7 reveal">
				<?php if ( $eyebrow ) : ?>
					<p class="text-action font-bold text-sm tracking-[0.18em] uppercase mb-3"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="text-3xl sm:text-4xl font-extrabold text-navy mb-5">What's Included</h2>
				<?php if ( $body ) : ?>
					<div class="text-slate-500 leading-relaxed mb-8 prose"><?php echo $body; // phpcs:ignore ?></div>
				<?php endif; ?>
				<?php echo $features; // phpcs:ignore ?>
				<div class="flex flex-wrap gap-4">
					<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-6 py-3 text-sm">Enquire Now</a>
					<a href="<?php echo esc_url( $services ); ?>" class="btn btn-outline px-6 py-3 text-sm">All Services</a>
				</div>
			</div>
			<div class="lg:col-span-5 reveal reveal-d1">
				<div class="bg-mist rounded-xl border border-slate-200 shadow-sm p-8 sticky top-28">
					<h3 class="text-xl font-bold text-navy mb-2">Ready to get started?</h3>
					<p class="text-sm text-slate-500 leading-relaxed mb-6">No judgement — just a plan. We'll get you sorted and keep you current.</p>
					<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-dark w-full py-3 text-sm mb-3">Book a Free Consultation</a>
					<?php if ( $phone ) : ?>
						<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="btn btn-outline w-full py-3 text-sm">Call <?php echo esc_html( $phone ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-navydeep text-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Ready for books that balance?</h2>
			<p class="text-base md:text-lg text-white/60 max-w-xl mx-auto mb-8">Get in touch for a free, no-obligation consultation today.</p>
			<div class="flex flex-wrap justify-center gap-4">
				<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Contact Us</a>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="btn btn-outline-light px-7 py-3.5 text-base">Call <?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_integration_single() {
	$post = get_post();
	if ( ! $post || 'integration' !== $post->post_type ) {
		return '';
	}
	$subtitle  = get_post_meta( $post->ID, 'subtitle', true );
	$intro     = get_post_meta( $post->ID, 'intro', true );
	$badge     = get_post_meta( $post->ID, 'partner_badge_label', true ) ?: get_the_title( $post ) . ' Partner';
	$login_url = get_post_meta( $post->ID, 'login_url', true );
	$features  = bootg_features_list( get_post_meta( $post->ID, 'features', true ) );
	$body      = apply_filters( 'the_content', $post->post_content );
	$contact   = bootg_page_url( 'contact' );
	$phone     = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );
	$logo      = has_post_thumbnail( $post ) ? get_the_post_thumbnail( $post, 'medium', array( 'class' => 'h-14 w-auto object-contain mx-auto' ) ) : '';

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5"><?php echo esc_html( get_the_title( $post ) ); ?><?php if ( $subtitle ) : ?>: <span class="text-action"><?php echo esc_html( $subtitle ); ?></span><?php endif; ?></h1>
			<?php if ( $intro ) : ?>
				<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8"><?php echo esc_html( $intro ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Book a Free Consultation</a>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-12 gap-10 items-start">
			<div class="lg:col-span-7 reveal">
				<p class="text-action font-bold text-sm tracking-[0.18em] uppercase mb-3"><?php echo esc_html( $badge ); ?></p>
				<h2 class="text-3xl sm:text-4xl font-extrabold text-navy mb-5">Why <?php echo esc_html( get_the_title( $post ) ); ?> for your business?</h2>
				<?php if ( $body ) : ?>
					<div class="text-slate-500 leading-relaxed mb-8 prose"><?php echo $body; // phpcs:ignore ?></div>
				<?php endif; ?>
				<?php echo $features; // phpcs:ignore ?>
				<div class="flex flex-wrap gap-4">
					<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-6 py-3 text-sm">Enquire Now</a>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'integration' ) ?: home_url( '/partners/' ) ); ?>" class="btn btn-outline px-6 py-3 text-sm">See All Partners</a>
				</div>
			</div>
			<div class="lg:col-span-5 reveal reveal-d1">
				<div class="bg-mist rounded-xl border border-slate-200 shadow-sm p-8 text-center sticky top-28">
					<span class="inline-block bg-navy text-white text-[11px] font-bold tracking-widest uppercase rounded-full px-3 py-1 mb-6"><?php echo esc_html( $badge ); ?></span>
					<?php if ( $logo ) : ?>
						<div class="mb-6"><?php echo $logo; // phpcs:ignore ?></div>
					<?php endif; ?>
					<p class="text-sm text-slate-500 leading-relaxed mb-6">We have the experience and the direct support channels to get your file right, fast.</p>
					<?php if ( $login_url ) : ?>
						<a href="<?php echo esc_url( $login_url ); ?>" target="_blank" rel="noopener" class="btn btn-outline w-full py-3 text-sm mb-3">Client Login</a>
					<?php endif; ?>
					<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-dark w-full py-3 text-sm">Help Me Get Set Up</a>
				</div>
			</div>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-navydeep text-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Not sure if <?php echo esc_html( get_the_title( $post ) ); ?> is the right fit?</h2>
			<p class="text-base md:text-lg text-white/60 max-w-xl mx-auto mb-8">We're certified across all the major platforms — we'll recommend what actually suits your workflow, not what's easiest for us.</p>
			<div class="flex flex-wrap justify-center gap-4">
				<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Contact Us</a>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="btn btn-outline-light px-7 py-3.5 text-base">Call <?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_team_archive() {
	$members = get_posts( array(
		'post_type'      => 'team_member',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
	) );

	$cards = '';
	foreach ( $members as $member ) {
		$role     = get_post_meta( $member->ID, 'role', true );
		$linkedin = get_post_meta( $member->ID, 'linkedin_url', true );
		$photo    = get_the_post_thumbnail( $member, 'medium', array( 'class' => 'w-32 h-32 rounded-full object-cover mx-auto mb-5 ring-4 ring-white shadow-xl' ) );

		$cards .= '<div class="bg-mist rounded-xl border border-slate-200 p-8 text-center reveal">';
		$cards .= $photo;
		$cards .= '<h2 class="text-lg font-bold text-navy">' . esc_html( get_the_title( $member ) ) . '</h2>';
		if ( $role ) {
			$cards .= '<p class="text-action text-xs font-bold tracking-widest uppercase mb-3 mt-1">' . esc_html( $role ) . '</p>';
		}
		if ( $member->post_content ) {
			$cards .= '<p class="text-sm text-slate-500 leading-relaxed mb-5">' . esc_html( wp_strip_all_tags( $member->post_content ) ) . '</p>';
		}
		if ( $linkedin ) {
			$cards .= '<a href="' . esc_url( $linkedin ) . '" target="_blank" rel="noopener" class="text-action font-bold text-sm hover:text-actiondark transition-colors">Connect on LinkedIn &rarr;</a>';
		}
		$cards .= '</div>';
	}

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 text-center">
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5">Meet Our <span class="text-action">Team.</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mx-auto">Dedicated, experienced bookkeepers who treat your books like their own.</p>
		</div>
	</section>
	<section class="py-16 lg:py-24 bg-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid sm:grid-cols-2 gap-8 max-w-3xl mx-auto"><?php echo $cards; // phpcs:ignore ?></div>
			<div class="mt-14 bg-navy rounded-2xl p-8 lg:p-12 text-white grid lg:grid-cols-12 gap-6 items-center reveal">
				<div class="lg:col-span-9">
					<h2 class="text-2xl sm:text-3xl font-extrabold mb-2">Work with a team that treats your books like their own</h2>
					<p class="text-white/70 leading-relaxed mb-0">Every client gets a dedicated bookkeeper backed by the whole team — so you're never left waiting.</p>
				</div>
				<div class="lg:col-span-3 lg:text-right">
					<a href="<?php echo esc_url( bootg_page_url( 'contact' ) ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Get In Touch</a>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_testimonial_archive() {
	$testimonials = get_posts( array( 'post_type' => 'testimonial', 'posts_per_page' => -1 ) );
	$star = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>';

	$cards = '';
	foreach ( $testimonials as $t ) {
		$quote    = get_post_meta( $t->ID, 'quote', true );
		$name     = get_post_meta( $t->ID, 'author_name', true ) ?: get_the_title( $t );
		$business = get_post_meta( $t->ID, 'author_business', true );
		$rating   = (int) get_post_meta( $t->ID, 'rating', true ) ?: 5;

		$cards .= '<div class="bg-mist rounded-xl border border-slate-200 p-8 reveal">';
		$cards .= '<div class="flex gap-1 text-action mb-4">' . str_repeat( $star, max( 1, min( 5, $rating ) ) ) . '</div>';
		$cards .= '<blockquote class="text-lg font-display font-bold text-navy leading-snug mb-4">&ldquo;' . esc_html( $quote ) . '&rdquo;</blockquote>';
		$cards .= '<p class="font-bold text-charcoal mb-0">' . esc_html( $name ) . '</p>';
		if ( $business ) {
			$cards .= '<p class="text-sm text-slate-400">' . esc_html( $business ) . '</p>';
		}
		$cards .= '</div>';
	}

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 text-center">
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5">Client <span class="text-action">Stories.</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mx-auto">Real results for real businesses across Australia.</p>
		</div>
	</section>
	<section class="py-16 lg:py-24 bg-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6"><?php echo $cards; // phpcs:ignore ?></div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_service_archive() {
	$services = get_posts( array(
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
	) );

	$blocks = '';
	foreach ( $services as $i => $service ) {
		$summary  = get_post_meta( $service->ID, 'card_summary', true ) ?: $service->post_content;
		$features = bootg_features_list( get_post_meta( $service->ID, 'features', true ) );
		$features = $features ?: '<p class="text-slate-500 leading-relaxed">' . esc_html( wp_strip_all_tags( $summary ) ) . '</p>';

		$blocks .= '<div class="service-block grid lg:grid-cols-2 gap-8 items-center bg-mist rounded-xl border border-slate-200 p-8 lg:p-12 reveal">';
		$blocks .= '<div><p class="chapter-tag"><span class="chapter-num">' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span></p>';
		$blocks .= '<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mb-4">' . esc_html( get_the_title( $service ) ) . '</h2>';
		$blocks .= '<p class="text-slate-500 leading-relaxed mb-6">' . esc_html( wp_strip_all_tags( $summary ) ) . '</p>';
		$blocks .= '<a href="' . esc_url( get_permalink( $service ) ) . '" class="btn btn-outline px-6 py-3 text-sm">Learn More</a></div>';
		$blocks .= $features;
		$blocks .= '</div>';
	}

	$contact    = bootg_page_url( 'contact' );
	$phone      = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white">Services</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5">Bookkeeping Services That <span class="text-action">Go Further</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8">We're beside you every step of the way, supporting you with comprehensive bookkeeping and BAS/IAS services.</p>
			<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Get Started</a>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8"><?php echo $blocks; // phpcs:ignore ?></div>
	</section>

	<section class="py-16 lg:py-20 bg-navydeep text-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Let's Talk About Your Books</h2>
			<p class="text-base md:text-lg text-white/60 max-w-xl mx-auto mb-8">Get in touch for a free, no-obligation consultation today.</p>
			<div class="flex flex-wrap justify-center gap-4">
				<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Contact Us</a>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="btn btn-outline-light px-7 py-3.5 text-base">Call <?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_integration_archive() {
	$integrations = get_posts( array(
		'post_type'      => 'integration',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );

	$cards = '';
	foreach ( $integrations as $integration ) {
		$intro = get_post_meta( $integration->ID, 'intro', true );
		$badge = get_post_meta( $integration->ID, 'partner_badge_label', true ) ?: get_the_title( $integration ) . ' Partner';
		$image = has_post_thumbnail( $integration ) ? get_the_post_thumbnail( $integration, 'medium', array( 'class' => 'h-12 w-auto object-contain mb-4' ) ) : '';

		$cards .= '<a href="' . esc_url( get_permalink( $integration ) ) . '" class="service-card bg-white rounded-xl border border-slate-200 shadow-sm p-8 flex flex-col reveal">';
		$cards .= $image;
		$cards .= '<p class="text-action font-bold text-xs tracking-widest uppercase mb-2">' . esc_html( $badge ) . '</p>';
		$cards .= '<h2 class="text-xl font-bold text-navy mb-2">' . esc_html( get_the_title( $integration ) ) . '</h2>';
		if ( $intro ) {
			$cards .= '<p class="text-slate-500 text-sm leading-relaxed mb-4">' . esc_html( $intro ) . '</p>';
		}
		$cards .= '<span class="service-link font-bold text-sm mt-auto">Learn More <span aria-hidden="true">&rarr;</span></span>';
		$cards .= '</a>';
	}

	$contact    = bootg_page_url( 'contact' );
	$phone      = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white">Partners</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5">Certified Across Every <span class="text-action">Major Platform</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8">We'll recommend what actually suits your workflow, not what's easiest for us.</p>
			<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Get Started</a>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-mist">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"><?php echo $cards; // phpcs:ignore ?></div>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-navydeep text-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Not Sure Which Platform Fits?</h2>
			<p class="text-base md:text-lg text-white/60 max-w-xl mx-auto mb-8">Get in touch for a free, no-obligation consultation today.</p>
			<div class="flex flex-wrap justify-center gap-4">
				<a href="<?php echo esc_url( $contact ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Contact Us</a>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="btn btn-outline-light px-7 py-3.5 text-base">Call <?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_guide_archive() {
	$guides = get_posts( array( 'post_type' => 'guide', 'posts_per_page' => -1 ) );

	$cards = '';
	if ( ! $guides ) {
		$cards = '<p class="text-slate-400 col-span-3 text-center">Add Guides from wp-admin to populate this page.</p>';
	}
	foreach ( $guides as $guide ) {
		$subtitle = get_post_meta( $guide->ID, 'subtitle', true );
		$excerpt  = has_excerpt( $guide ) ? get_the_excerpt( $guide ) : wp_trim_words( wp_strip_all_tags( $guide->post_content ), 24 );
		$image    = has_post_thumbnail( $guide ) ? get_the_post_thumbnail( $guide, 'medium', array( 'class' => 'w-full h-44 object-cover rounded-lg mb-5' ) ) : '';

		$cards .= '<a href="' . esc_url( get_permalink( $guide ) ) . '" class="service-card bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col reveal">';
		$cards .= $image;
		if ( $subtitle ) {
			$cards .= '<p class="text-action font-bold text-xs tracking-widest uppercase mb-2">' . esc_html( $subtitle ) . '</p>';
		}
		$cards .= '<h2 class="text-lg font-bold text-navy mb-2">' . esc_html( get_the_title( $guide ) ) . '</h2>';
		$cards .= '<p class="text-slate-500 text-sm leading-relaxed mb-4">' . esc_html( $excerpt ) . '</p>';
		$cards .= '<span class="service-link font-bold text-sm mt-auto">Read Guide <span aria-hidden="true">&rarr;</span></span>';
		$cards .= '</a>';
	}

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 text-center">
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5">Business <span class="text-action">Guides.</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mx-auto">Free, practical guides to help you run a healthier business.</p>
		</div>
	</section>
	<section class="py-16 lg:py-24 bg-mist">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"><?php echo $cards; // phpcs:ignore ?></div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_guide_single() {
	$post = get_post();
	if ( ! $post || 'guide' !== $post->post_type ) {
		return '';
	}
	$subtitle  = get_post_meta( $post->ID, 'subtitle', true );
	$cta_label = get_post_meta( $post->ID, 'cta_label', true ) ?: 'Book a Free Consultation';
	$cta_url   = get_post_meta( $post->ID, 'cta_url', true ) ?: bootg_page_url( 'contact' );
	$body      = apply_filters( 'the_content', $post->post_content );
	$image     = has_post_thumbnail( $post ) ? get_the_post_thumbnail( $post, 'large', array( 'class' => 'w-full rounded-xl mb-10' ) ) : '';

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ?: home_url( '/guides/' ) ); ?>" class="hover:text-white transition-colors">Guides</a> <span class="mx-2">/</span> <span class="text-white"><?php echo esc_html( get_the_title( $post ) ); ?></span></p>
			<?php if ( $subtitle ) : ?>
				<p class="text-action font-bold text-sm tracking-[0.18em] uppercase mb-3"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5"><?php echo esc_html( get_the_title( $post ) ); ?></h1>
		</div>
	</section>

	<section class="py-16 lg:py-24 bg-white">
		<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
			<?php echo $image; // phpcs:ignore ?>
			<?php if ( $body ) : ?>
				<div class="text-slate-600 leading-relaxed prose"><?php echo $body; // phpcs:ignore ?></div>
			<?php endif; ?>
			<div class="mt-10">
				<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-primary px-7 py-3.5 text-base"><?php echo esc_html( $cta_label ); ?></a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
