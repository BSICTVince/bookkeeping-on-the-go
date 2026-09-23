<?php
/**
 * One-click seeder for the 6 real blog articles from the original static
 * site (public/bookkeeping-on-the-go-post-*.html and blog.html), so the
 * new blog archive/single templates (inc/blog-templates.php) have real
 * content instead of just the default "Hello world!" post. Safe to run
 * again — skips posts that already exist by title.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bootg_blog_seed_data() {
	return bootg_load_json( BOOTG_DIR . '/content/blog-posts.json' );
}

function bootg_seed_blog_posts() {
	$created = 0;
	foreach ( bootg_blog_seed_data() as $post_data ) {
		$existing = get_posts( array(
			'post_type'      => 'post',
			'post_status'    => 'any',
			'title'          => $post_data['title'],
			'posts_per_page' => 1,
			'fields'         => 'ids',
		) );
		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post( array(
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_title'   => $post_data['title'],
			'post_excerpt' => $post_data['excerpt'],
			'post_content' => $post_data['content'],
			'post_date'    => $post_data['date'] . ' 09:00:00',
			'post_author'  => get_current_user_id() ?: 1,
		), true );

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		wp_set_post_categories( $post_id, array( get_cat_ID( $post_data['category'] ) ?: wp_create_category( $post_data['category'] ) ) );
		update_post_meta( $post_id, 'byline', 'Natalie Adams' );
		update_post_meta( $post_id, 'callout', $post_data['callout'] );
		update_post_meta( $post_id, 'cta_heading', $post_data['cta'] );

		if ( $post_data['image'] ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$attachment_id = media_sideload_image( $post_data['image'], $post_id, $post_data['title'], 'id' );
			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}

		$created++;
	}
	return $created;
}

/**
 * One-click "set up the Blog page" — a block theme's front-page.html only
 * takes over "/" when Reading is in "a static page" mode with a real page
 * assigned as the homepage; that same mode is also what gives the post
 * archive (templates/index.html) a real URL at all, via "Posts page". This
 * creates minimal "Home" (page_on_front) and "Blog" (page_for_posts) pages
 * and wires up Reading Settings, then repoints the Primary/Footer menus'
 * "Blog" item from its old "#"/home_url() fallback to the new /blog/ URL.
 * Safe to run again.
 */
function bootg_setup_blog_page() {
	$home_page = get_page_by_path( 'home' );
	if ( ! $home_page ) {
		$home_id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => 'Home',
			'post_name'   => 'home',
		), true );
	} else {
		$home_id = $home_page->ID;
	}

	// The "Homepage Content" SEO fields (inc/homepage-sections.php) only
	// output their <title>/meta tags when the front page ISN'T a real
	// singular page — that was true under "latest posts" mode, but Home is
	// now a real page, so that filter stops firing. Copy the already-
	// configured values onto the new page's own SEO fields (same fields
	// inc/seo-meta.php reads) so the title/description/keywords don't change.
	if ( ! is_wp_error( $home_id ) && ! get_post_meta( $home_id, 'meta_title', true ) ) {
		update_post_meta( $home_id, 'meta_title', bootg_get_home_seo( 'title' ) );
		update_post_meta( $home_id, 'meta_description', bootg_get_home_seo( 'description' ) );
		update_post_meta( $home_id, 'meta_keywords', bootg_get_home_seo( 'keywords' ) );
	}

	$blog_page = get_page_by_path( 'blog' );
	if ( ! $blog_page ) {
		$blog_id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => 'Blog',
			'post_name'   => 'blog',
		), true );
	} else {
		$blog_id = $blog_page->ID;
	}

	if ( is_wp_error( $home_id ) || is_wp_error( $blog_id ) ) {
		return false;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', $blog_id );

	$blog_url = get_permalink( $blog_id );
	$fixed    = 0;
	foreach ( get_posts( array( 'post_type' => 'nav_menu_item', 'posts_per_page' => -1, 'post_status' => 'any' ) ) as $item ) {
		if ( 'Blog' === $item->post_title ) {
			wp_update_nav_menu_item(
				(int) wp_get_post_terms( $item->ID, 'nav_menu', array( 'fields' => 'ids' ) )[0],
				$item->ID,
				array(
					'menu-item-title'     => 'Blog',
					'menu-item-url'       => $blog_url,
					'menu-item-status'    => 'publish',
					'menu-item-type'      => 'custom',
					'menu-item-parent-id' => (int) get_post_meta( $item->ID, '_menu_item_menu_item_parent', true ),
					'menu-item-position'  => $item->menu_order,
				)
			);
			$fixed++;
		}
	}

	return array( 'blog_url' => $blog_url, 'menu_items_fixed' => $fixed );
}

add_action( 'admin_post_bootg_setup_blog_page', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_setup_blog_page' );

	$result = bootg_setup_blog_page();

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_blog_page_setup' => $result ? '1' : 'error' ),
		admin_url( 'themes.php' )
	) );
	exit;
} );

add_action( 'admin_post_bootg_seed_blog_posts', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_seed_blog_posts' );

	$created = bootg_seed_blog_posts();

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_blog_seeded' => $created ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
