<?php
/**
 * CPT-driven homepage pieces (Services grid, Partner logos, Testimonial
 * spotlight, Latest post), exposed as classic shortcodes so they can be
 * dropped into any raw-HTML section on the Homepage Content admin page
 * (see inc/homepage-sections.php) — e.g. [bootg_services_grid].
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	add_shortcode( 'bootg_services_grid', 'bootg_render_services_grid_section' );
	add_shortcode( 'bootg_partner_logos', 'bootg_render_partner_logos_section' );
	add_shortcode( 'bootg_testimonial_spotlight', 'bootg_render_testimonial_spotlight' );
	add_shortcode( 'bootg_latest_post', 'bootg_render_latest_post_section' );
} );

/** Resolves a page by slug to its permalink, falling back to /slug/ if the page doesn't exist yet. */
function bootg_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

function bootg_render_services_grid_section() {
	$services = get_posts( array(
		'post_type'      => 'service',
		'posts_per_page' => 3,
		'orderby'        => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
	) );

	$cards = '';
	if ( ! $services ) {
		$cards = '<p class="text-slate-400 col-span-3 text-center">Add Services from wp-admin to populate this section.</p>';
	}
	foreach ( $services as $service ) {
		$summary   = get_post_meta( $service->ID, 'card_summary', true ) ?: $service->post_content;
		$cta_url   = get_post_meta( $service->ID, 'cta_url', true ) ?: get_permalink( $service );
		$cta_label = get_post_meta( $service->ID, 'cta_label', true ) ?: 'Learn More';
		$image     = get_the_post_thumbnail( $service, 'medium', array( 'class' => 'svc-circle' ) );

		$cards .= '<div class="service-card svc-photo-card bg-mist rounded-xl border border-slate-200 shadow-sm px-8 pb-8 flex flex-col text-center reveal">';
		$cards .= $image;
		$cards .= '<h3 class="svc-title text-xl font-bold mb-3">' . esc_html( get_the_title( $service ) ) . '</h3>';
		$cards .= '<p class="text-slate-500 text-sm leading-relaxed mb-6">' . esc_html( wp_strip_all_tags( $summary ) ) . '</p>';
		$cards .= '<a href="' . esc_url( $cta_url ) . '" class="service-link font-bold text-sm mt-auto mx-auto">' . esc_html( $cta_label ) . ' <span aria-hidden="true">&rarr;</span></a>';
		$cards .= '</div>';
	}

	return '
	<section class="py-16 lg:py-24 bg-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="max-w-2xl mb-14 reveal">
				<p class="chapter-tag"><span class="chapter-num">02</span><span class="chapter-label">What We Do</span></p>
				<h2 class="text-3xl sm:text-4xl font-extrabold text-navy mb-4">Holistic Bookkeeping to Support Your Business</h2>
				<p class="text-base md:text-lg text-slate-500">Comprehensive financial support so you can focus on driving your core enterprise.</p>
			</div>
			<div class="grid md:grid-cols-3 gap-6 pt-12">' . $cards . '</div>
		</div>
	</section>';
}

function bootg_render_partner_logos_section() {
	$badges = '';
	foreach ( bootg_homepage_marquee_logo_keys() as $key ) {
		$badges .= bootg_render_partner_logo_img( $key );
	}

	return '
	<section class="bg-white border-b border-slate-200 py-10">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<p class="uppercase text-slate-400 text-xs font-bold tracking-[0.2em] mb-6">Trusted Partners &amp; Certified Specialists</p>
			<div class="flex flex-wrap justify-center items-center gap-x-10 gap-y-6" data-testid="partner-logos">' . $badges . '</div>
		</div>
	</section>';
}

/**
 * Auto-picks a testimonial (the one flagged "spotlight", else the most
 * recent) and renders it with bootg_render_testimonial_card() -- the same
 * function the per-entry [bootg_testimonial id] shortcode uses, so the
 * homepage spotlight and a manually-embedded structured-fields testimonial
 * always look identical and can't drift apart.
 */
function bootg_render_testimonial_spotlight() {
	$spotlight = get_posts( array(
		'post_type'      => 'testimonial',
		'posts_per_page' => 1,
		'meta_key'       => 'is_spotlight',
		'meta_value'     => 1,
	) );
	if ( ! $spotlight ) {
		$spotlight = get_posts( array( 'post_type' => 'testimonial', 'posts_per_page' => 1 ) );
	}
	if ( ! $spotlight || ! function_exists( 'bootg_render_testimonial_card' ) ) {
		return '';
	}
	return bootg_render_testimonial_card( $spotlight[0] );
}

function bootg_render_latest_post_section() {
	$posts = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 1 ) );

	ob_start();
	?>
	<section class="py-16 lg:py-24 bg-mist border-y border-slate-200">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10 reveal">
				<div>
					<p class="chapter-tag"><span class="chapter-num">05</span><span class="chapter-label">Free Tools &amp; Guides</span></p>
					<h2 class="text-3xl sm:text-4xl font-extrabold text-navy mb-2">Want to grow your business?</h2>
					<p class="text-base md:text-lg text-slate-500 mb-0">Explore our free tools, calculators, and expert guides.</p>
				</div>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>" class="btn btn-outline px-6 py-3 text-sm shrink-0">Explore Free Resources</a>
			</div>
			<?php if ( $posts ) : $p = $posts[0]; ?>
				<article class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 lg:p-10 reveal reveal-d1">
					<div class="grid lg:grid-cols-12 gap-6 items-center">
						<div class="lg:col-span-9">
							<div class="flex items-center gap-3 mb-3">
								<span class="text-slate-400 text-sm"><?php echo esc_html( get_the_date( '', $p ) ); ?></span>
							</div>
							<h3 class="text-xl sm:text-2xl font-bold text-navy mb-2"><?php echo esc_html( get_the_title( $p ) ); ?></h3>
							<p class="text-sm text-slate-500 leading-relaxed mb-0 max-w-2xl"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $p->post_content ), 28 ) ); ?></p>
						</div>
						<div class="lg:col-span-3 lg:text-right">
							<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" class="btn btn-dark px-6 py-3 text-sm">Read Full Article</a>
						</div>
					</div>
				</article>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Theme plumbing only: renders the ordered list of raw-HTML sections from
 * the Homepage Content admin page (inc/homepage-sections.php), expanding
 * shortcodes in each. This is the one block templates/front-page.html
 * needs to satisfy the block-theme template requirement — the admin never
 * touches the block editor; they only ever see the classic textarea page.
 */
add_action( 'init', function () {
	register_block_type( 'bootg/home-content', array(
		'render_callback' => 'bootg_render_home_sections',
	) );
} );

function bootg_render_home_sections() {
	$sections = get_option( 'bootg_home_sections', array() );
	if ( ! $sections ) {
		$sections = bootg_home_default_sections();
	}
	$out = '';
	foreach ( $sections as $section ) {
		$html = is_array( $section ) ? ( $section['html'] ?? '' ) : $section;
		$out .= do_shortcode( $html );
	}
	return $out;
}
