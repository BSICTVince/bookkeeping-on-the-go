<?php
/**
 * Footer "Business Info" widget — site name, tagline, phone, email, address.
 * Same markup the footer used to hardcode; now editable (text only —
 * the data itself still comes from Site Options) and placeable in any
 * footer column via Appearance > Widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bootg_Footer_Brand_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct( 'bootg_footer_brand', 'Business Info (Footer)', array(
			'description' => 'Site name, tagline, phone, email and address — pulled from Weavit → Site Options.',
		) );
	}

	public function widget( $args, $instance ) {
		$phone      = bootg_get_option( 'phone' );
		$phone_link = bootg_get_option( 'phone_link' );
		$email      = bootg_get_option( 'email' );
		$address    = function_exists( 'weavit_business_address' ) ? weavit_business_address() : bootg_get_option( 'address' );
		$map_url    = function_exists( 'weavit_business_map_url' ) ? weavit_business_map_url() : '';

		echo $args['before_widget']; // phpcs:ignore
		?>
		<h4 class="text-lg font-bold mb-4"><?php bloginfo( 'name' ); ?></h4>
		<p class="text-sm text-white/60 leading-relaxed mb-5"><?php echo esc_html( bootg_get_option( 'footer_tagline' ) ); ?></p>
		<?php if ( $phone ) : ?>
			<p class="text-sm text-white/60 mb-1"><strong class="text-white/90">Phone:</strong> <a href="tel:<?php echo esc_attr( $phone_link ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $phone ); ?></a></p>
		<?php endif; ?>
		<?php if ( $email ) : ?>
			<p class="text-sm text-white/60 mb-1"><strong class="text-white/90">Email:</strong> <a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $email ); ?></a></p>
		<?php endif; ?>
		<?php if ( $address ) : ?>
			<p class="text-sm text-white/60"><strong class="text-white/90">Address:</strong> <a href="<?php echo esc_url( $map_url ?: 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $address ) ); ?>" target="_blank" rel="noopener" class="hover:text-white transition-colors"><?php echo esc_html( $address ); ?></a></p>
		<?php endif; ?>
		<?php
		echo $args['after_widget']; // phpcs:ignore
	}
}

add_action( 'widgets_init', function () {
	register_widget( 'Bootg_Footer_Brand_Widget' );
} );
