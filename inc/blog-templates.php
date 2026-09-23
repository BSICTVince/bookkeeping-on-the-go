<?php
/**
 * Blog archive + single post bodies, ported from the original site's
 * blog.html (featured story + latest-articles grid) and its per-post
 * pages (hero + long-form article + callout + CTA band). Same
 * server-rendered-block pattern as the other CPT templates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_block_type( 'bootg/blog-archive', array( 'render_callback' => 'bootg_render_blog_archive' ) );
	register_block_type( 'bootg/post-single', array( 'render_callback' => 'bootg_render_post_single' ) );
} );

function bootg_post_category_label( $post ) {
	$cats = get_the_category( $post );
	return $cats ? $cats[0]->name : '';
}

function bootg_post_byline( $post ) {
	$author = get_post_meta( $post->ID, 'byline', true ) ?: get_the_author_meta( 'display_name', $post->post_author );
	return $author;
}

function bootg_render_blog_archive() {
	$posts = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 6, 'post_status' => 'publish' ) );

	if ( ! $posts ) {
		ob_start();
		?>
		<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
			<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
				<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5">From Our <span class="text-action">Blog</span></h1>
				<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl">Practical bookkeeping, compliance and growth advice for Australian small businesses and nonprofits.</p>
			</div>
		</section>
		<section class="py-16 lg:py-24 bg-white">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<p class="text-slate-400 text-center">No articles published yet — add posts from wp-admin to populate this page.</p>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}

	$featured = array_shift( $posts );
	$rest     = $posts;

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white">Blog</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1">From Our <span class="text-action">Blog</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8 reveal reveal-d2">Practical bookkeeping, compliance and growth advice for Australian small businesses and nonprofits.</p>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-white" data-testid="blog-featured-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<p class="chapter-tag"><span class="chapter-num">01</span><span class="chapter-label">Featured Story</span></p>
			<article class="bg-mist rounded-2xl border border-slate-200 overflow-hidden grid lg:grid-cols-12 reveal">
				<?php if ( has_post_thumbnail( $featured ) ) : ?>
					<div class="lg:col-span-4 h-56 lg:h-auto">
						<?php echo get_the_post_thumbnail( $featured, 'large', array( 'class' => 'w-full h-full object-cover' ) ); // phpcs:ignore ?>
					</div>
				<?php endif; ?>
				<div class="<?php echo has_post_thumbnail( $featured ) ? 'lg:col-span-8' : 'lg:col-span-12'; ?> p-8 lg:p-12">
					<div class="flex flex-wrap items-center gap-3 mb-4">
						<span class="bg-action text-white text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1">Featured</span>
						<?php if ( bootg_post_category_label( $featured ) ) : ?>
							<span class="bg-action/10 text-action text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1"><?php echo esc_html( bootg_post_category_label( $featured ) ); ?></span>
						<?php endif; ?>
						<span class="text-slate-400 text-sm"><?php echo esc_html( get_the_date( 'd/m/Y', $featured ) ); ?> &middot; <?php echo esc_html( bootg_post_byline( $featured ) ); ?></span>
					</div>
					<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mb-3"><?php echo esc_html( get_the_title( $featured ) ); ?></h2>
					<p class="text-slate-500 leading-relaxed mb-6 max-w-3xl"><?php echo esc_html( has_excerpt( $featured ) ? get_the_excerpt( $featured ) : wp_trim_words( wp_strip_all_tags( $featured->post_content ), 34 ) ); ?></p>
					<a href="<?php echo esc_url( get_permalink( $featured ) ); ?>" class="btn btn-dark px-6 py-3 text-sm">Read Full Article</a>
				</div>
			</article>
		</div>
	</section>

	<section class="pb-16 lg:pb-24 bg-white" data-testid="blog-grid-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<p class="chapter-tag reveal"><span class="chapter-num">02</span><span class="chapter-label">Latest Articles</span></p>
			<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mb-8 reveal">Latest Articles</h2>
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php foreach ( $rest as $i => $post ) :
					$delay = $i % 3 === 1 ? ' reveal-d1' : ( $i % 3 === 2 ? ' reveal-d2' : '' );
					?>
					<article class="bg-mist rounded-xl border border-slate-200 p-7 flex flex-col service-block reveal<?php echo esc_attr( $delay ); ?>">
						<div class="flex items-center gap-3 mb-4">
							<?php if ( bootg_post_category_label( $post ) ) : ?>
								<span class="bg-action/10 text-action text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1"><?php echo esc_html( bootg_post_category_label( $post ) ); ?></span>
							<?php endif; ?>
							<span class="text-slate-400 text-xs"><?php echo esc_html( get_the_date( 'd/m/Y', $post ) ); ?></span>
						</div>
						<h3 class="text-lg font-bold text-navy mb-2"><?php echo esc_html( get_the_title( $post ) ); ?></h3>
						<p class="text-sm text-slate-500 leading-relaxed mb-5"><?php echo esc_html( has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 24 ) ); ?></p>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="service-link font-bold text-sm mt-auto">Read More <span aria-hidden="true">&rarr;</span></a>
					</article>
				<?php endforeach; ?>
				<article class="bg-navy rounded-xl p-7 flex flex-col text-white service-block reveal">
					<div class="flex items-center gap-3 mb-4">
						<span class="bg-white/15 text-white text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1">Free Tools</span>
					</div>
					<h3 class="text-lg font-bold mb-2">Want more than articles?</h3>
					<p class="text-sm text-white/70 leading-relaxed mb-5">Explore our free guides, templates and checklists — built to help you grow and manage your business.</p>
					<a href="<?php echo esc_url( bootg_page_url( 'resources' ) ); ?>" class="btn btn-primary px-5 py-2.5 text-sm mt-auto self-start">Explore Free Resources</a>
				</article>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_post_single() {
	$post = get_post();
	if ( ! $post || 'post' !== $post->post_type ) {
		return '';
	}

	$category = bootg_post_category_label( $post );
	$byline   = bootg_post_byline( $post );
	$body     = apply_filters( 'the_content', $post->post_content );
	$callout  = get_post_meta( $post->ID, 'callout', true );
	$subtitle = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>" class="hover:text-white transition-colors">Blog</a> <span class="mx-2">/</span> <span class="text-white">Blog</span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1"><?php echo esc_html( get_the_title( $post ) ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl mb-8 reveal reveal-d2"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-white" data-testid="article-section">
		<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex flex-wrap items-center gap-3 mb-8 reveal">
				<?php if ( $category ) : ?>
					<span class="bg-action/10 text-action text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1"><?php echo esc_html( $category ); ?></span>
				<?php endif; ?>
				<span class="text-slate-400 text-sm"><?php echo esc_html( get_the_date( 'd/m/Y', $post ) ); ?> &middot; <?php echo esc_html( $byline ); ?></span>
			</div>
			<?php if ( has_post_thumbnail( $post ) ) : ?>
				<?php echo get_the_post_thumbnail( $post, 'large', array( 'class' => 'w-full rounded-xl border border-slate-200 shadow-sm mb-10 reveal' ) ); // phpcs:ignore ?>
			<?php endif; ?>
			<?php echo $body; // phpcs:ignore ?>
			<?php if ( $callout ) : ?>
				<div class="bg-mist border-l-4 border-action rounded-r-xl p-6 my-10 reveal">
					<p class="text-navy font-bold leading-relaxed mb-0"><?php echo esc_html( $callout ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-navydeep text-white" data-testid="article-cta-band">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
			<h2 class="text-3xl sm:text-4xl font-extrabold mb-4"><?php echo esc_html( get_post_meta( $post->ID, 'cta_heading', true ) ?: "Let's Talk About Your Business" ); ?></h2>
			<p class="text-base md:text-lg text-white/60 max-w-xl mx-auto mb-8">Get in touch for a free, no-obligation consultation today.</p>
			<div class="flex flex-wrap justify-center gap-4">
				<a href="<?php echo esc_url( bootg_page_url( 'contact' ) ); ?>" class="btn btn-primary px-7 py-3.5 text-base">Contact Us</a>
				<?php $phone = bootg_get_option( 'phone' ); ?>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( bootg_get_option( 'phone_link' ) ); ?>" class="btn btn-outline-light px-7 py-3.5 text-base">Call <?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
