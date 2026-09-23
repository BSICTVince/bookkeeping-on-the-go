<?php
/**
 * Site chrome as server-rendered blocks (bootg/site-header, bootg/site-footer).
 *
 * These are structural, not content blocks: they aren't meant to be dragged
 * into arbitrary post content, only referenced once from the header/footer
 * template parts. All editable data (menus, phone, socials, footer text)
 * comes from core WP APIs — wp_nav_menu(), the Settings API options page,
 * and add_theme_support( 'custom-logo' ) — no ACF, no builder plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Footer newsletter signup — compact inline layout (kept from the original
 * design), wired to the Forms engine's shared submit endpoint so signups
 * land as entries in wp-admin instead of vanishing into a front-end-only
 * mock handler.
 */
function bootg_render_newsletter_form() {
	$form_id  = bootg_get_newsletter_form_id();
	$fields   = bootg_get_form_schema( $form_id );
	$field_id = $fields[0]['field_id'] ?? 'email';
	$settings = bootg_get_form_settings( $form_id );

	$status = '';
	if ( isset( $_GET['bootg_form'] ) && (int) $_GET['bootg_form'] === $form_id ) {
		if ( 'success' === ( $_GET['status'] ?? '' ) ) {
			$status = '<p class="text-sm mt-3" style="color:#6EE7B7" role="status">' . esc_html( $settings['success_message'] ) . '</p>';
		} elseif ( 'error' === ( $_GET['status'] ?? '' ) ) {
			$status = '<p class="text-sm mt-3" style="color:#FCA5A5" role="alert">Please enter a valid email address.</p>';
		}
	}

	ob_start();
	?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="flex">
		<?php wp_nonce_field( 'bootg_form_submit_' . $form_id ); ?>
		<input type="hidden" name="action" value="bootg_form_submit">
		<input type="hidden" name="bootg_form_id" value="<?php echo esc_attr( $form_id ); ?>">
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( bootg_current_url() ); ?>">
		<p style="position:absolute;left:-9999px;" aria-hidden="true">
			<label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
		</p>
		<label for="newsletterEmail" class="sr-only">Your email address</label>
		<input id="newsletterEmail" name="bootg_field[<?php echo esc_attr( $field_id ); ?>]" type="email" required placeholder="Your email address"
			class="w-full min-w-0 rounded-l-lg border border-white/20 bg-white/10 px-4 py-2.5 text-sm text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-action focus:border-transparent">
		<button type="submit" class="btn btn-primary rounded-l-none rounded-r-lg px-5 py-2.5 text-sm shrink-0"><?php echo esc_html( $settings['submit_label'] ); ?></button>
	</form>
	<?php echo $status; // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

add_action( 'init', function () {
	register_block_type( 'bootg/site-header', array(
		'render_callback' => 'bootg_render_site_header',
	) );

	register_block_type( 'bootg/site-footer', array(
		'render_callback' => 'bootg_render_site_footer',
	) );
} );

/**
 * Shared SVG sprite used by header + footer icons. Printed once.
 */
function bootg_icon_sprite() {
	static $printed = false;
	if ( $printed ) {
		return '';
	}
	$printed = true;
	return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" style="display:none">
  <symbol id="shape-telephone" viewBox="0 0 16 16">
    <path d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
  </symbol>
  <symbol id="shape-chevron-down" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
  </symbol>
  <symbol id="shape-facebook" viewBox="0 0 16 16">
    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
  </symbol>
  <symbol id="shape-linkedin" viewBox="0 0 16 16">
    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
  </symbol>
  <symbol id="shape-instagram" viewBox="0 0 16 16">
    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
  </symbol>
</svg>
SVG;
}

function bootg_social_icon( $url, $class_suffix, $symbol, $label ) {
	if ( empty( $url ) ) {
		return '';
	}

	// X/Twitter has no sprite symbol in the original markup — it's an inline SVG path.
	if ( 'twitter' === $symbol ) {
		$svg = '<svg class="u-scalingSvg_shape" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"></path></svg>';
	} else {
		$svg = '<svg class="u-scalingSvg_shape"><use xlink:href="#shape-' . esc_attr( $symbol ) . '"></use></svg>';
	}

	return sprintf(
		'<a href="%s" class="socialIcons_icon socialIcons_icon-%s" target="_blank" rel="noopener" aria-label="%s"><div class="u-scalingSvg">%s</div></a>',
		esc_url( $url ),
		esc_attr( $class_suffix ),
		esc_attr( $label ),
		$svg
	);
}

function bootg_render_site_header() {
	$phone      = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );

	ob_start();
	?>
	<?php echo bootg_icon_sprite(); // phpcs:ignore ?>

	<div class="navBar <?php echo bootg_nav_is_transparent_here() ? 'navBar-transparent' : 'navBar-light'; ?>" data-testid="main-nav">
		<div class="container">
			<div class="navBar_header navBar_section-header">
				<?php if ( has_custom_logo() ) : ?>
					<div class="navBar_brand front"><?php the_custom_logo(); ?></div>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navBar_brand front">
						<span class="navBar_logo font-display font-extrabold text-navy text-xl"><?php bloginfo( 'name' ); ?></span>
					</a>
				<?php endif; ?>

				<div class="navBar_menuToggle">
					<a href="#mainNavBar" class="burger burger-animated" data-toggle="collapse" aria-expanded="false" aria-controls="mainNavBar">
						<span class="sr-only">Toggle navigation</span>
						<span class="burger_bar"></span>
						<span class="burger_bar"></span>
						<span class="burger_bar"></span>
					</a>

					<?php if ( $phone ) : ?>
						<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="navBar_phone">
							<div class="navBar_phoneIcon">
								<div class="u-scalingSvg" style="padding-bottom: 88%;">
									<svg class="u-scalingSvg_shape"><use xlink:href="#shape-telephone"></use></svg>
								</div>
							</div>
							<?php echo esc_html( $phone ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<div id="mainNavBar" class="navBar_menu collapse">
				<div class="navBar_socialContainer">
					<div class="navBar_clientArea navBar_section-clientArea">
						<div class="dropButton">
							<div class="dropdown">
								<button class="dropButton_button dropdown-toggle" type="button" aria-expanded="false" aria-haspopup="true">
									Client Area
									<div class="dropButton_chevron">
										<div class="u-scalingSvg">
											<svg class="u-scalingSvg_shape"><use xlink:href="#shape-chevron-down"></use></svg>
										</div>
									</div>
								</button>
								<?php
								wp_nav_menu( array(
									'theme_location' => 'client-area',
									'container'      => false,
									'items_wrap'      => '<ul id="menu-client-area" class="dropdown-menu dropdown-menu-right">%3$s</ul>',
									'fallback_cb'     => 'bootg_client_area_fallback',
								) );
								?>
							</div>
						</div>
					</div>

					<?php if ( $phone ) : ?>
						<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="navBar_phone">
							<div class="navBar_phoneIcon">
								<div class="u-scalingSvg" style="padding-bottom: 88%;">
									<svg class="u-scalingSvg_shape"><use xlink:href="#shape-telephone"></use></svg>
								</div>
							</div>
							<?php echo esc_html( $phone ); ?>
						</a>
					<?php endif; ?>

					<div class="socialIcons navBar_section-socialIcons">
						<div class="socialIcons_layout">
							<?php
							echo bootg_social_icon( bootg_get_option( 'facebook_url' ), 'fb', 'facebook', 'Facebook' ); // phpcs:ignore
							echo bootg_social_icon( bootg_get_option( 'twitter_url' ), 'twitter', 'twitter', 'X (Twitter)' ); // phpcs:ignore
							echo bootg_social_icon( bootg_get_option( 'instagram_url' ), 'instagram', 'instagram', 'Instagram' ); // phpcs:ignore
							echo bootg_social_icon( bootg_get_option( 'linkedin_url' ), 'linkedin', 'linkedin', 'LinkedIn' ); // phpcs:ignore
							?>
						</div>
					</div>
				</div>

				<div class="navBar_menuContainer navBar_section-mainMenu">
					<div class="mainMenu">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'container'      => false,
							'items_wrap'      => '<ul id="menu-main" class="navList">%3$s</ul>',
							'walker'          => new Bootg_Nav_Walker(),
							'fallback_cb'     => 'bootg_primary_menu_fallback',
						) );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function bootg_render_site_footer() {
	$phone      = bootg_get_option( 'phone' );
	$phone_link = bootg_get_option( 'phone_link' );
	$email      = bootg_get_option( 'email' );

	ob_start();
	?>
	<footer class="bg-navydeep text-white pt-16 pb-8">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid gap-10 lg:grid-cols-12 mb-12">
				<div class="lg:col-span-4">
					<h4 class="text-lg font-bold mb-4"><?php bloginfo( 'name' ); ?></h4>
					<p class="text-sm text-white/60 leading-relaxed mb-5"><?php echo esc_html( bootg_get_option( 'footer_tagline' ) ); ?></p>
					<?php if ( $phone ) : ?>
						<p class="text-sm text-white/60 mb-1"><strong class="text-white/90">Phone:</strong> <a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $phone ); ?></a></p>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<p class="text-sm text-white/60"><strong class="text-white/90">Email:</strong> <a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $email ); ?></a></p>
					<?php endif; ?>
				</div>

				<div class="lg:col-span-2">
					<h5 class="text-sm font-bold tracking-widest uppercase mb-4 text-white/90">Quick Links</h5>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'items_wrap'      => '<ul class="space-y-2.5 text-sm text-white/60">%3$s</ul>',
						'link_before'     => '',
						'fallback_cb'     => 'bootg_footer_menu_fallback',
					) );
					?>
				</div>

				<div class="lg:col-span-3">
					<h5 class="text-sm font-bold tracking-widest uppercase mb-4 text-white/90">Specialist Areas</h5>
					<ul class="space-y-2.5 text-sm text-white/60">
						<li>Payroll Specialists in Perth</li>
						<li>Public Trustee Reporting</li>
						<li>Nonprofit Compliance Accounting</li>
					</ul>
				</div>

				<div class="lg:col-span-3">
					<h5 class="text-sm font-bold tracking-widest uppercase mb-4 text-white/90">Stay Connected</h5>
					<p class="text-sm text-white/60 mb-4">Sign up to receive news, updates, and compliance alerts.</p>
					<?php echo bootg_render_newsletter_form(); // phpcs:ignore ?>
				</div>
			</div>

			<hr class="border-white/10 mb-6">
			<div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 text-sm text-white/50">
				<p class="mb-0"><?php echo wp_kses_post( bootg_get_option( 'footer_copyright' ) ); ?></p>
				<div class="flex gap-5">
					<?php if ( bootg_get_option( 'privacy_url' ) ) : ?>
						<a href="<?php echo esc_url( bootg_get_option( 'privacy_url' ) ); ?>" target="_blank" rel="noopener" class="hover:text-white transition-colors">Privacy</a>
					<?php endif; ?>
					<?php if ( bootg_get_option( 'terms_url' ) ) : ?>
						<a href="<?php echo esc_url( bootg_get_option( 'terms_url' ) ); ?>" target="_blank" rel="noopener" class="hover:text-white transition-colors">Terms</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</footer>

	<div class="sticky-cta" id="stickyCta">
		<?php if ( $phone ) : ?>
			<a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="btn btn-outline flex-1 py-3 text-sm !bg-white">
				<svg class="w-4 h-4 text-action" fill="currentColor" viewBox="0 0 16 16"><use xlink:href="#shape-telephone"></use></svg>
				<?php echo esc_html( $phone ); ?>
			</a>
		<?php endif; ?>
		<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/contact/' ) ); ?>" class="btn btn-primary flex-1 py-3 text-sm">Free Consultation</a>
	</div>
	<?php
	return ob_get_clean();
}

/** Fallbacks so the site looks right before menus are configured in Appearance > Menus. */
function bootg_primary_menu_fallback() {
	echo '<ul id="menu-main" class="navList">';
	echo '<li class="menu-item active"><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
	echo '<li class="menu-item"><a href="' . esc_url( home_url( '/contact/' ) ) . '">Contact</a></li>';
	echo '</ul>';
}

function bootg_client_area_fallback() {
	echo '<ul id="menu-client-area" class="dropdown-menu dropdown-menu-right">';
	echo '<li class="menu-item"><a target="_blank" href="https://login.xero.com">Xero Login</a></li>';
	echo '<li class="menu-item"><a target="_blank" href="https://app.qbo.intuit.com/app/login">QuickBooks Online Login</a></li>';
	echo '</ul>';
}

function bootg_footer_menu_fallback() {
	echo '<ul class="space-y-2.5 text-sm text-white/60">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="hover:text-white transition-colors">Home</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '" class="hover:text-white transition-colors">Contact</a></li>';
	echo '</ul>';
}
