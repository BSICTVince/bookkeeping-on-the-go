<?php
/**
 * 404 and search-results templates. A block theme falls back to index.html
 * (the blog archive block) for both when these don't exist — which would
 * show the blog feed instead of a real "not found" or "no results" page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_block_type( 'bootg/not-found', array( 'render_callback' => 'bootg_render_not_found' ) );
	register_block_type( 'bootg/search-results', array( 'render_callback' => 'bootg_render_search_results' ) );
} );

function bootg_render_page_search_box( $placeholder = 'Search the site…' ) {
	ob_start();
	?>
	<form role="search" method="get" class="flex gap-3 max-w-lg" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="sr-only" for="bootg-site-search">Search</label>
		<input type="search" id="bootg-site-search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-charcoal placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-action focus:border-transparent">
		<button type="submit" class="btn btn-primary px-6 py-3 text-sm whitespace-nowrap">Search</button>
	</form>
	<?php
	return ob_get_clean();
}

function bootg_render_not_found() {
	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">
			<p class="text-sm font-bold tracking-widest uppercase text-action mb-5">404</p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5">That page has <span class="text-action">wandered off.</span></h1>
			<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-xl mx-auto mb-10">The page you're looking for doesn't exist or may have moved. Try a search, or jump to one of the links below.</p>
			<div class="flex justify-center">
				<?php echo bootg_render_page_search_box( 'Search for what you need…' ); // phpcs:ignore ?>
			</div>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-mist">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
			<p class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-6">Or head to</p>
			<div class="flex flex-wrap justify-center gap-4">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline px-6 py-3 text-sm">Home</a>
				<a href="<?php echo esc_url( bootg_page_url( 'services' ) ); ?>" class="btn btn-outline px-6 py-3 text-sm">Services</a>
				<a href="<?php echo esc_url( bootg_page_url( 'contact' ) ); ?>" class="btn btn-outline px-6 py-3 text-sm">Contact</a>
				<a href="tel:<?php echo esc_attr( bootg_get_option( 'phone_link' ) ); ?>" class="btn btn-primary px-6 py-3 text-sm">Call <?php echo esc_html( bootg_get_option( 'phone' ) ); ?></a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_search_results() {
	$query = get_search_query();
	$q     = new WP_Query( array(
		's'              => $query,
		'post_type'      => array( 'post', 'page', 'service', 'integration', 'guide' ),
		'posts_per_page' => 10,
		'paged'          => max( 1, get_query_var( 'paged' ) ),
	) );

	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white">Search</span></p>
			<h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.1] mb-6"><?php echo $query ? sprintf( 'Results for &ldquo;<span class="text-action">%s</span>&rdquo;', esc_html( $query ) ) : 'Search'; // phpcs:ignore ?></h1>
			<?php echo bootg_render_page_search_box(); // phpcs:ignore ?>
		</div>
	</section>

	<section class="py-16 lg:py-20 bg-mist">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<?php if ( $q->have_posts() ) : ?>
				<p class="text-sm text-slate-500 mb-8"><?php echo (int) $q->found_posts; ?> result<?php echo 1 === (int) $q->found_posts ? '' : 's'; ?> found.</p>
				<div class="space-y-5">
					<?php while ( $q->have_posts() ) : $q->the_post(); ?>
						<a href="<?php the_permalink(); ?>" class="block bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:border-action transition-colors">
							<p class="text-xs font-bold tracking-widest uppercase text-action mb-2"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></p>
							<h2 class="text-lg font-bold text-navy mb-2"><?php the_title(); ?></h2>
							<p class="text-sm text-slate-500 leading-relaxed"><?php echo esc_html( wp_trim_words( get_the_excerpt() ?: wp_strip_all_tags( get_the_content() ), 28 ) ); ?></p>
						</a>
					<?php endwhile; ?>
				</div>
				<?php if ( $q->max_num_pages > 1 ) : ?>
					<div class="mt-10 flex justify-center gap-2">
						<?php echo paginate_links( array( 'total' => $q->max_num_pages, 'prev_text' => '&larr;', 'next_text' => '&rarr;' ) ); // phpcs:ignore ?>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="text-center py-12">
					<p class="text-lg font-bold text-navy mb-2">No results found.</p>
					<p class="text-sm text-slate-500 mb-8">Try a different search, or get in touch and we'll point you in the right direction.</p>
					<a href="<?php echo esc_url( bootg_page_url( 'contact' ) ); ?>" class="btn btn-primary px-6 py-3 text-sm">Contact Us</a>
				</div>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
