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

/** Blog page address (the Posts page, else the homepage). */
function bootg_blog_base_url() {
	$page = (int) get_option( 'page_for_posts' );
	return $page ? get_permalink( $page ) : home_url( '/' );
}

/** Search / category / sort / page from the query string, sanitised. */
function bootg_blog_filters() {
	$q    = isset( $_GET['blog_q'] ) ? sanitize_text_field( wp_unslash( $_GET['blog_q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$cat  = isset( $_GET['blog_cat'] ) ? sanitize_title( wp_unslash( $_GET['blog_cat'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$sort = isset( $_GET['blog_sort'] ) && 'oldest' === $_GET['blog_sort'] ? 'oldest' : 'newest'; // phpcs:ignore WordPress.Security.NonceVerification
	$page = isset( $_GET['blog_page'] ) ? max( 1, (int) $_GET['blog_page'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification
	return array( 'q' => $q, 'cat' => $cat, 'sort' => $sort, 'page' => $page );
}

/** Blog URL carrying the given filters (empty values dropped). */
function bootg_blog_url( $f, $override = array() ) {
	$f    = array_merge( $f, $override );
	$args = array();
	if ( '' !== $f['q'] ) {
		$args['blog_q'] = $f['q'];
	}
	if ( '' !== $f['cat'] ) {
		$args['blog_cat'] = $f['cat'];
	}
	if ( 'oldest' === $f['sort'] ) {
		$args['blog_sort'] = 'oldest';
	}
	if ( $f['page'] > 1 ) {
		$args['blog_page'] = $f['page'];
	}
	return add_query_arg( rawurlencode_deep( $args ), bootg_blog_base_url() );
}

function bootg_render_blog_archive() {
	$f        = bootg_blog_filters();
	$filtered = '' !== $f['q'] || '' !== $f['cat'] || 'oldest' === $f['sort'];

	// The newest post is the "featured story" on the unfiltered view, and is left out of the grid there.
	$featured = null;
	if ( ! $filtered ) {
		$newest   = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 1, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
		$featured = $newest ? $newest[0] : null;
	}

	$query = new WP_Query( array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 9,
		'paged'               => $f['page'],
		's'                   => $f['q'],
		'category_name'       => $f['cat'],
		'order'               => 'oldest' === $f['sort'] ? 'ASC' : 'DESC',
		'orderby'             => 'date',
		'ignore_sticky_posts' => true,
		'post__not_in'        => $featured ? array( $featured->ID ) : array(),
	) );
	$posts = $query->posts;
	$total = (int) $query->found_posts + ( $featured ? 1 : 0 );
	$pages = (int) $query->max_num_pages;
	$cats  = get_categories( array( 'hide_empty' => true ) );
	$show_featured = $featured && 1 === $f['page'];

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

	<section class="py-8 bg-white border-b border-slate-200" id="blog-filters" data-testid="blog-filters">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="blog-filter-bar">
			<?php if ( $cats ) : ?>
				<nav class="blog-filter-chips" aria-label="Filter by category">
					<a href="<?php echo esc_url( bootg_blog_url( $f, array( 'cat' => '', 'page' => 1 ) ) . '#blog-filters' ); ?>" class="blog-chip<?php echo '' === $f['cat'] ? ' is-active' : ''; ?>"<?php echo '' === $f['cat'] ? ' aria-current="true"' : ''; ?>>All</a>
					<?php foreach ( $cats as $c ) : ?>
						<a href="<?php echo esc_url( bootg_blog_url( $f, array( 'cat' => $c->slug, 'page' => 1 ) ) . '#blog-filters' ); ?>" class="blog-chip<?php echo $f['cat'] === $c->slug ? ' is-active' : ''; ?>"<?php echo $f['cat'] === $c->slug ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $c->name ); ?> <span class="blog-chip-count"><?php echo (int) $c->count; ?></span></a>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
			<form method="get" action="<?php echo esc_url( bootg_blog_base_url() ); ?>" class="blog-filter-form" role="search" aria-label="Search and filter articles">
				<div class="blog-filter-row">
					<label class="screen-reader-text" for="blog-q">Search articles</label>
					<input type="search" id="blog-q" name="blog_q" value="<?php echo esc_attr( $f['q'] ); ?>" placeholder="Search articles" class="blog-filter-input">
					<label class="screen-reader-text" for="blog-sort">Sort articles</label>
					<select id="blog-sort" name="blog_sort" class="blog-filter-select" onchange="this.form.submit()">
						<option value="newest"<?php selected( $f['sort'], 'newest' ); ?>>Newest first</option>
						<option value="oldest"<?php selected( $f['sort'], 'oldest' ); ?>>Oldest first</option>
					</select>
					<button type="submit" class="btn btn-dark px-6 py-3 text-sm">Search</button>
				</div>
				<?php if ( '' !== $f['cat'] ) : ?>
					<input type="hidden" name="blog_cat" value="<?php echo esc_attr( $f['cat'] ); ?>">
				<?php endif; ?>
			</form>
			</div>
			<?php if ( $filtered ) : ?>
				<p class="text-sm text-ink mt-4" role="status">
					<?php
					echo esc_html( sprintf( _n( '%d article found', '%d articles found', $total, 'bookkeeping-on-the-go' ), $total ) );
					if ( '' !== $f['q'] ) {
						echo ' for &ldquo;' . esc_html( $f['q'] ) . '&rdquo;';
					}
					?>
					&middot; <a href="<?php echo esc_url( bootg_blog_base_url() ); ?>" class="font-bold text-action hover:underline">Clear filters</a>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $show_featured ) : ?>
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
						<span class="text-ink text-sm"><?php echo esc_html( get_the_date( 'd/m/Y', $featured ) ); ?> &middot; <?php echo esc_html( bootg_post_byline( $featured ) ); ?></span>
					</div>
					<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mb-3"><?php echo esc_html( get_the_title( $featured ) ); ?></h2>
					<p class="text-ink leading-relaxed mb-6 max-w-3xl"><?php echo esc_html( has_excerpt( $featured ) ? get_the_excerpt( $featured ) : wp_trim_words( wp_strip_all_tags( $featured->post_content ), 34 ) ); ?></p>
					<a href="<?php echo esc_url( get_permalink( $featured ) ); ?>" class="btn btn-dark px-6 py-3 text-sm">Read Full Article</a>
				</div>
			</article>
		</div>
	</section>
	<?php endif; ?>

	<section class="pb-16 lg:pb-24 bg-white<?php echo $show_featured ? '' : ' pt-12'; ?>" data-testid="blog-grid-section">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<?php if ( $show_featured ) : ?>
				<p class="chapter-tag reveal"><span class="chapter-num">02</span><span class="chapter-label">Latest Articles</span></p>
			<?php endif; ?>
			<h2 class="text-2xl sm:text-3xl font-extrabold text-navy mb-8 reveal"><?php echo $filtered ? 'Search Results' : 'Latest Articles'; ?></h2>
			<?php if ( ! $posts && ! $show_featured ) : ?>
				<p class="text-ink text-center py-8"><?php echo $filtered ? 'No articles match your search. Try a different word or clear the filters.' : 'No articles published yet — add posts from wp-admin to populate this page.'; ?></p>
			<?php else : ?>
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php foreach ( $posts as $i => $post ) :
					$delay = $i % 3 === 1 ? ' reveal-d1' : ( $i % 3 === 2 ? ' reveal-d2' : '' );
					?>
					<article class="bg-mist rounded-xl border border-slate-200 p-7 flex flex-col service-block reveal<?php echo esc_attr( $delay ); ?>">
						<div class="flex items-center gap-3 mb-4">
							<?php if ( bootg_post_category_label( $post ) ) : ?>
								<span class="bg-action/10 text-action text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1"><?php echo esc_html( bootg_post_category_label( $post ) ); ?></span>
							<?php endif; ?>
							<span class="text-ink text-xs"><?php echo esc_html( get_the_date( 'd/m/Y', $post ) ); ?></span>
						</div>
						<h3 class="text-lg font-bold text-navy mb-2"><?php echo esc_html( get_the_title( $post ) ); ?></h3>
						<p class="text-sm text-ink leading-relaxed mb-5"><?php echo esc_html( has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 24 ) ); ?></p>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="service-link font-bold text-sm mt-auto">Read More <span aria-hidden="true">&rarr;</span></a>
					</article>
				<?php endforeach; ?>
				<?php if ( ! $filtered && ( 1 === $f['page'] ) ) : ?>
				<article class="bg-navy rounded-xl p-7 flex flex-col text-white service-block reveal">
					<div class="flex items-center gap-3 mb-4">
						<span class="bg-white/15 text-white text-xs font-bold tracking-wide uppercase rounded-full px-3 py-1">Free Tools</span>
					</div>
					<h3 class="text-lg font-bold mb-2">Want more than articles?</h3>
					<p class="text-sm text-white/70 leading-relaxed mb-5">Explore our free guides, templates and checklists — built to help you grow and manage your business.</p>
					<a href="<?php echo esc_url( bootg_page_url( 'resources' ) ); ?>" class="btn btn-primary px-5 py-2.5 text-sm mt-auto self-start">Explore Free Resources</a>
				</article>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $pages > 1 ) :
				$base = bootg_blog_url( $f, array( 'page' => 1 ) );
				$base .= ( false === strpos( $base, '?' ) ? '?' : '&' ) . 'blog_page=%#%';
				?>
				<nav class="blog-pagination" aria-label="Articles pagination">
					<?php
					echo wp_kses_post( paginate_links( array(
						'base'      => $base,
						'format'    => '',
						'current'   => $f['page'],
						'total'     => $pages,
						'add_fragment' => '#blog-grid-section',
						'prev_text' => '&larr; Previous',
						'next_text' => 'Next &rarr;',
					) ) );
					?>
				</nav>
			<?php endif; ?>
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
				<span class="text-ink text-sm"><?php echo esc_html( get_the_date( 'd/m/Y', $post ) ); ?> &middot; <?php echo esc_html( $byline ); ?></span>
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
