<?php
/**
 * Weavit Page Designer — Hero component. Image + title + description +
 * a nested Button (InnerBlocks, restricted to weavit/button for now).
 * Dynamic block: save() only emits the InnerBlocks placeholder, PHP
 * renders everything else — $content already holds the rendered button.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	weavit_register_component( 'weavit/hero', array(
		'api_version'     => 3,
		'title'           => 'Hero',
		'description'     => 'An image + headline + description section, with a button underneath.',
		'attributes'      => array(
			'title'            => array( 'type' => 'string', 'default' => '' ),
			'description'      => array( 'type' => 'string', 'default' => '' ),
			'layout'           => array( 'type' => 'string', 'default' => 'text-left' ), // text-left | text-right | text-center
			'desktopImageUrl'  => array( 'type' => 'string', 'default' => '' ),
			'desktopImageId'   => array( 'type' => 'number', 'default' => 0 ),
			'mobileImageUrl'   => array( 'type' => 'string', 'default' => '' ),
			'mobileImageId'    => array( 'type' => 'number', 'default' => 0 ),
			'imageAlt'         => array( 'type' => 'string', 'default' => '' ),
		),
		'render_callback' => 'bootg_render_weavit_hero',
	) );
} );

function bootg_render_weavit_hero( $attributes, $content ) {
	$title        = $attributes['title'] ?? '';
	$description  = $attributes['description'] ?? '';
	$layout       = in_array( $attributes['layout'] ?? 'text-left', array( 'text-left', 'text-right', 'text-center' ), true ) ? $attributes['layout'] : 'text-left';
	$desktop_img  = $attributes['desktopImageUrl'] ?? '';
	$mobile_img   = $attributes['mobileImageUrl'] ?? $desktop_img;
	$alt          = $attributes['imageAlt'] ?? '';

	$text_align   = 'text-center' === $layout ? 'text-center items-center' : 'text-left items-start';
	$image_order  = 'text-right' === $layout ? ' lg:order-2' : '';

	ob_start();
	?>
	<div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center py-10 lg:py-16">
		<?php if ( $desktop_img ) : ?>
			<div class="relative<?php echo esc_attr( $image_order ); ?>">
				<picture>
					<source media="(min-width:1024px)" srcset="<?php echo esc_url( $desktop_img ); ?>">
					<img src="<?php echo esc_url( $mobile_img ?: $desktop_img ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="w-full h-auto rounded-xl object-cover" loading="lazy">
				</picture>
			</div>
		<?php endif; ?>
		<div class="flex flex-col <?php echo esc_attr( $text_align ); ?>">
			<?php if ( $title ) : ?>
				<h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-navy mb-5 leading-[1.1]"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $description ) : ?>
				<p class="text-base md:text-lg text-slate-600 leading-relaxed mb-8 max-w-xl"><?php echo wp_kses_post( $description ); ?></p>
			<?php endif; ?>
			<?php if ( $content ) : ?>
				<div><?php echo $content; // phpcs:ignore — already-rendered inner block markup ?></div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
