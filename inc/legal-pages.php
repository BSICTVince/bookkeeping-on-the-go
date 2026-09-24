<?php
/**
 * Privacy Policy, Terms & Conditions, and two local-SEO service landing
 * pages ("Reporting to Public Trustees", "Payroll Specialists"), migrated
 * from the original site's copy. Same shortcode-in-a-Page pattern as
 * About/Contact/Compliance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	add_shortcode( 'bootg_privacy_policy', 'bootg_render_privacy_policy' );
	add_shortcode( 'bootg_terms_conditions', 'bootg_render_terms_conditions' );
	add_shortcode( 'bootg_public_trustees', 'bootg_render_public_trustees' );
	add_shortcode( 'bootg_payroll_specialists', 'bootg_render_payroll_specialists' );
} );

function bootg_legal_hero( $crumb, $title_html, $intro ) {
	ob_start();
	?>
	<section class="relative overflow-hidden bg-navydeep text-white" data-testid="page-hero">
		<div class="hero-blob w-[420px] h-[420px] bg-action/20 -top-32 -right-24"></div>
		<div class="hero-blob w-[280px] h-[280px] bg-white/5 bottom-0 -left-20"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
			<p class="text-sm font-semibold text-white/50 mb-4 reveal"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a> <span class="mx-2">/</span> <span class="text-white"><?php echo esc_html( $crumb ); ?></span></p>
			<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.08] mb-5 reveal reveal-d1"><?php echo $title_html; // phpcs:ignore ?></h1>
			<?php if ( $intro ) : ?>
				<p class="text-base md:text-lg text-white/70 leading-relaxed max-w-2xl reveal reveal-d2"><?php echo esc_html( $intro ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function bootg_render_privacy_policy() {
	ob_start();
	echo bootg_legal_hero( 'Privacy Policy', 'Privacy <span class="text-action">Policy.</span>', 'How we collect, use, and protect the information you share with us.' ); // phpcs:ignore
	?>
	<section class="py-16 lg:py-24 bg-white" data-testid="info-section">
		<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose">
			<p>This privacy policy sets out how Bookkeeping On The Go uses and protects any information that you give Bookkeeping On The Go when you use this website.</p>
			<p>Bookkeeping On The Go is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement.</p>
			<p>Bookkeeping On The Go may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">What we collect</h2>
			<p>We may collect the following information:</p>
			<ul>
				<li>Name</li>
				<li>Contact information including email address</li>
				<li>Demographic information such as postcode, preferences and interests</li>
				<li>Other information relevant to customer surveys and/or offers</li>
			</ul>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">What we do with the information we gather</h2>
			<p>We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:</p>
			<ul>
				<li>Internal record keeping.</li>
				<li>We may use the information to improve our products and services.</li>
				<li>We may periodically send promotional emails about new products, special offers or other information which we think you may find interesting using the email address which you have provided.</li>
				<li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email, phone, fax or mail. We may use the information to customise the website according to your interests.</li>
			</ul>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">Security</h2>
			<p>We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure, we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">How we use cookies</h2>
			<p>A cookie is a small file which asks permission to be placed on your computer's hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.</p>
			<p>We use traffic log cookies to identify which pages are being used. This helps us analyse data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system.</p>
			<p>Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.</p>
			<p>You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">Links to other websites</h2>
			<p>Our website may contain links to other websites of interest. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">Controlling your personal information</h2>
			<p>You may choose to restrict the collection or use of your personal information in the following ways:</p>
			<ul>
				<li>Whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes.</li>
				<li>If you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or using the contact page on this website.</li>
			</ul>
			<p>We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen.</p>
		</div>
	</section>
	<?php echo bootg_resource_cta( 'Questions about your data?' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

function bootg_render_terms_conditions() {
	ob_start();
	echo bootg_legal_hero( 'Terms & Conditions', 'Terms &amp; <span class="text-action">Conditions.</span>', 'The terms that govern your use of this website.' ); // phpcs:ignore
	?>
	<section class="py-16 lg:py-24 bg-white" data-testid="info-section">
		<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose">
			<h2 class="text-2xl font-extrabold text-navy mt-0 mb-4">1. About this website</h2>
			<p>Website Owner = Bookkeeping On The Go ("Bookkeeping On The Go") (Domain name = www.bookkeepingonthego.net.au, the "website").</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">2. About these terms of use</h2>
			<p>These website terms of use ("terms of use") and the associated Privacy Policy govern your access to and use of the website. You should read these terms of use and the privacy policy carefully before using this website.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">3. Your access/use implies agreement</h2>
			<p>The website is available for your use only on condition that you agree to these terms of use. By accessing/using the website, you are signifying that you agree to be bound by these terms.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">4. Modifications to the terms</h2>
			<p>Bookkeeping On The Go may revise and update these terms of use at any time. Your continued usage of the website after any changes to these terms of use will mean you accept those changes.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">5. Modifications to the information</h2>
			<p>Bookkeeping On The Go does not warrant the accuracy, adequacy or completeness of material on this website. All information may be changed, supplemented, deleted or updated without notice at the sole discretion of Bookkeeping On The Go.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">6. Errors and problems</h2>
			<p>Bookkeeping On The Go does not guarantee that the website will be free from viruses, or that access to the website will be uninterrupted.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">7. License and ownership</h2>
			<p>The copyright for the content on this website is owned or licensed by Bookkeeping On The Go and is protected under copyright laws in both Australia and other countries. No material on this website may be reproduced, adapted, uploaded to a third party, linked to, framed, performed in public, distributed or transmitted in any form by any process without the specific written consent of Bookkeeping On The Go.</p>
			<p>All custom graphics, icons, and other items that appear on the website and all associated trademarks, are trademarks of Bookkeeping On The Go.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">8. Privacy policy</h2>
			<p>Our <a href="<?php echo esc_url( bootg_get_option( 'privacy_url' ) ?: bootg_page_url( 'privacy-policy' ) ); ?>">Privacy Policy</a> governs the use of information collected from or provided by you at the website.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">9. Feedback, suggestions, comments or requests</h2>
			<p>Bookkeeping On The Go does not encourage you to make feedback, suggestions, comments or requests ("comments") but these comments may be made at the <a href="<?php echo esc_url( bootg_page_url( 'contact' ) ); ?>">contact page</a> of this website, and if you do make comments, you acknowledge that: they will not be considered confidential or proprietary, and Bookkeeping On The Go is under no obligation to keep such information confidential, and Bookkeeping On The Go will have an unrestricted, irrevocable, world-wide, royalty free right to use, communicate, reproduce, publish, display, distribute and exploit such comments in any manner it chooses.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">10. Outbound links</h2>
			<p>The website may contain links to third-party websites and resources ("linked sites"). These linked sites are provided solely as a convenience to you and not as an endorsement by Bookkeeping On The Go. Bookkeeping On The Go makes no representations or warranties regarding the availability, correctness, accuracy, performance or quality of the linked site or any content, software, service or application found at any linked site. Bookkeeping On The Go may receive payments and/or commissions from operators of linked sites in relation to goods or services supplied by the operator as a result of you linking to the third-party website from the Bookkeeping On The Go website.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">11. Inbound links</h2>
			<p>Bookkeeping On The Go generally encourages and agrees to your linking to the Home page through a plain text link on your website without the need for agreement between yourself and Bookkeeping On The Go. However, linking to any other page of the website is strictly prohibited, without express written permission from Bookkeeping On The Go.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">12. Disclaimer of warranties</h2>
			<p>Bookkeeping On The Go makes no representations or warranties about the accuracy, completeness, security or timeliness of the content, information or services provided by the website and disclaims all warranties, either express or implied, statutory or otherwise, including but not limited to the implied warranties of merchantability, non-infringement of third parties' rights, and fitness for a particular purpose.</p>

			<h2 class="text-2xl font-extrabold text-navy mt-10 mb-4">13. Limitation of liability</h2>
			<p>If Bookkeeping On The Go is found responsible for any damages, Bookkeeping On The Go is responsible for actual damages only. In no event shall Bookkeeping On The Go be liable for any incidental, indirect, exemplary, punitive and/or consequential damages, lost profits, or damages resulting from lost data or business interruption resulting from the use of or inability to use the website.</p>
		</div>
	</section>
	<?php echo bootg_resource_cta( 'Questions about these terms?' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

function bootg_render_public_trustees() {
	ob_start();
	echo bootg_legal_hero( 'Reporting to Public Trustees', 'Reporting to <span class="text-action">Public Trustees.</span>', 'Specialised administration bookkeeping for annual statements of accounts — done right, and on time.' ); // phpcs:ignore
	?>
	<section class="py-16 lg:py-24 bg-white" data-testid="info-section">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Are you required to report to the public trustees?</h2>
				<p class="text-slate-500 leading-relaxed mb-0">Financial reporting to public trustees requires specialised bookkeeping. Annual statements of accounts are required, and there are hefty fines involved if they're not submitted. Completing them is often an understandable source of stress for administrators.</p>
			</div>
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Years of experience with administration bookkeeping</h2>
				<p class="text-slate-500 leading-relaxed mb-0">We have years of experience in administration bookkeeping for public trustees and other similar bodies. Our team understands the nuances of bookkeeping under these circumstances.</p>
			</div>
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Onsite or remote, across WA and beyond</h2>
				<p class="text-slate-500 leading-relaxed mb-0">Our office is in Gosnells and we serve clients in surrounding areas including Perth, Armadale, Canning Vale, Cannington, Victoria Park, Cockburn, Fremantle, Morley, Booragoon and more. We're set up to work remotely and can support your business in any part of Australia — happy to come to your office if you're local, or to help virtually no matter where you are.</p>
			</div>
		</div>
	</section>
	<?php echo bootg_resource_cta( "Let's talk about your reporting obligations" ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

function bootg_render_payroll_specialists() {
	ob_start();
	echo bootg_legal_hero( 'Payroll Specialists', 'Payroll <span class="text-action">Specialists.</span>', 'Accurate, affordable payroll for Perth businesses — built around local rules, not generic templates.' ); // phpcs:ignore
	?>
	<section class="py-16 lg:py-24 bg-white" data-testid="info-section">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Accurate and affordable payroll services in Perth</h2>
				<p class="text-slate-500 leading-relaxed mb-0">When choosing your bookkeeper, you want someone who understands the importance of managing your payroll in Perth. Go with an expert who understands the regulations in the area, and can customise your payroll accordingly.</p>
			</div>
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Local taxes, holidays and awards — never forgotten</h2>
				<p class="text-slate-500 leading-relaxed mb-0">Local taxes and fees can be configured, public holidays will always be accounted for, and working rules and regulations are never forgotten. That's the benefit of working with someone who understands where you're coming from.</p>
			</div>
			<div class="bg-mist rounded-xl border border-slate-200 p-8 lg:p-10 mb-6 service-block reveal">
				<h2 class="text-2xl font-extrabold text-navy mb-3">Payroll customised for your business</h2>
				<p class="text-slate-500 leading-relaxed mb-0">No matter what industry you're in, it's important to work with a bookkeeper who understands the specifics of doing business in your state — tax laws can vary by state, and even locally. That's why we always get you set up so that your bookkeeping tools work for your situation.</p>
			</div>
		</div>
	</section>
	<?php echo bootg_resource_cta( 'Get payroll set up properly' ); // phpcs:ignore ?>
	<?php
	return ob_get_clean();
}

/** One-click creation/update of all four pages. Safe to run again — existing pages get their shortcode content refreshed, not duplicated. */
function bootg_upsert_legal_page( $slug, $title, $shortcode ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		wp_update_post( array(
			'ID'            => $existing->ID,
			'post_status'   => 'publish',
			'post_content'  => $shortcode,
			'page_template' => 'page-full-width',
		) );
		return array( 'status' => 'updated', 'id' => $existing->ID );
	}
	$page_id = wp_insert_post( array(
		'post_type'     => 'page',
		'post_title'    => $title,
		'post_name'     => $slug,
		'post_status'   => 'publish',
		'post_content'  => $shortcode,
		'page_template' => 'page-full-width',
	), true );
	return is_wp_error( $page_id ) ? array( 'status' => 'error' ) : array( 'status' => 'created', 'id' => $page_id );
}

add_action( 'admin_post_bootg_create_legal_pages', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}
	check_admin_referer( 'bootg_create_legal_pages' );

	$privacy   = bootg_upsert_legal_page( 'privacy-policy', 'Privacy Policy', '[bootg_privacy_policy]' );
	$terms     = bootg_upsert_legal_page( 'terms-and-conditions', 'Terms and Conditions', '[bootg_terms_conditions]' );
	$trustees  = bootg_upsert_legal_page( 'reporting-to-public-trustees', 'Reporting to Public Trustees', '[bootg_public_trustees]' );
	$payroll   = bootg_upsert_legal_page( 'payroll-specialists', 'Payroll Specialists', '[bootg_payroll_specialists]' );

	$results = array( $privacy, $terms, $trustees, $payroll );
	$result  = in_array( 'error', wp_list_pluck( $results, 'status' ), true ) ? 'error' : 'done';

	// Keep the footer's Privacy/Terms links (site-options.php) pointing at these pages.
	if ( 'error' !== $privacy['status'] ) {
		$options                 = wp_parse_args( get_option( BOOTG_OPTION, array() ), bootg_default_options() );
		$options['privacy_url']  = get_permalink( $privacy['id'] );
		$options['terms_url']    = get_permalink( $terms['id'] );
		update_option( BOOTG_OPTION, $options );
	}

	wp_safe_redirect( add_query_arg(
		array( 'page' => 'bootg-content-tools', 'bootg_legal_pages' => $result ),
		admin_url( 'themes.php' )
	) );
	exit;
} );
