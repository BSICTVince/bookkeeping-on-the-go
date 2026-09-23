<?php
/**
 * Overrides the Weavit Engine plugin's generic field markup with this
 * theme's own Tailwind layout — pairs consecutive "half"-width fields
 * into a two-column grid row (e.g. Name+Email side by side, Phone+Topic
 * side by side), matching the original hand-built forms' design. Hooks
 * `weavit_render_form_fields`; see inc/forms/forms-render.php in the
 * Weavit Engine plugin for the structural <form> wrapper this fills in.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'weavit_render_form_fields', 'bootg_theme_render_form_fields', 10, 3 );
add_filter( 'weavit_form_submit_class', function () {
	return 'btn btn-primary px-8 py-3.5 text-base w-full sm:w-auto';
} );

/**
 * Renders the field list, pairing consecutive "half"-width fields into a
 * two-column grid row and giving the row immediately before the submit
 * button a slightly larger bottom margin (mb-6 vs mb-5), same as the
 * original markup.
 */
function bootg_theme_render_form_fields( $html, $form_id, $fields ) {
	$out   = '';
	$total = count( $fields );
	$i     = 0;

	while ( $i < $total ) {
		$field     = $fields[ $i ];
		$is_half   = 'half' === ( $field['width'] ?? 'full' ) && 'checkbox' !== $field['type'];
		$next      = $fields[ $i + 1 ] ?? null;
		$next_half = $next && 'half' === ( $next['width'] ?? 'full' ) && 'checkbox' !== $next['type'];

		if ( $is_half && $next_half ) {
			$is_last = ( $i + 1 === $total - 1 );
			$margin  = $is_last ? 'mb-6' : 'mb-5';
			$out    .= '<div class="grid sm:grid-cols-2 gap-5 ' . $margin . '">';
			$out    .= '<div>' . bootg_render_form_field_inner( $form_id, $field ) . '</div>';
			$out    .= '<div>' . bootg_render_form_field_inner( $form_id, $next ) . '</div>';
			$out    .= '</div>';
			$i      += 2;
			continue;
		}

		$is_last = ( $i === $total - 1 );
		$margin  = $is_last ? 'mb-6' : 'mb-5';

		if ( 'checkbox' === $field['type'] ) {
			$out .= bootg_render_checkbox_field( $form_id, $field, $margin );
		} else {
			$out .= '<div class="' . $margin . '">' . bootg_render_form_field_inner( $form_id, $field ) . '</div>';
		}
		$i++;
	}

	return $out;
}

function bootg_render_checkbox_field( $form_id, $field, $margin = 'mb-5' ) {
	$id       = 'bootg_field_' . $form_id . '_' . $field['field_id'];
	$name     = 'bootg_field[' . $field['field_id'] . ']';
	$required = ! empty( $field['required'] );

	ob_start();
	?>
	<div class="<?php echo esc_attr( $margin ); ?> bootg-form-field bootg-form-field--checkbox">
		<label class="flex items-start gap-3 text-sm text-charcoal">
			<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php echo $required ? 'required' : ''; ?> class="mt-1">
			<span><?php echo esc_html( $field['label'] ); ?><?php echo $required ? ' <span class="text-action">*</span>' : ''; ?></span>
		</label>
	</div>
	<?php
	return ob_get_clean();
}

/** Label + input only — no wrapping div/margin, so callers can place it standalone or inside a paired grid row. */
function bootg_render_form_field_inner( $form_id, $field ) {
	$id       = 'bootg_field_' . $form_id . '_' . $field['field_id'];
	$name     = 'bootg_field[' . $field['field_id'] . ']';
	$label    = $field['label'];
	$required = ! empty( $field['required'] );
	$common   = 'w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-charcoal placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-action focus:border-transparent';

	ob_start();
	?>
	<label for="<?php echo esc_attr( $id ); ?>" class="block text-sm font-bold text-charcoal mb-2"><?php echo esc_html( $label ); ?><?php echo $required ? ' <span class="text-action">*</span>' : ''; ?></label>
	<?php if ( 'textarea' === $field['type'] ) : ?>
		<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="5" <?php echo $required ? 'required' : ''; ?> placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" class="<?php echo esc_attr( $common ); ?>"></textarea>
	<?php elseif ( 'select' === $field['type'] ) : ?>
		<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php echo $required ? 'required' : ''; ?> class="<?php echo esc_attr( $common ); ?> bg-white">
			<option value="">Select&hellip;</option>
			<?php foreach ( (array) ( $field['options'] ?? array() ) as $opt ) : ?>
				<option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
			<?php endforeach; ?>
		</select>
	<?php else :
		$type = in_array( $field['type'], array( 'email', 'tel', 'number' ), true ) ? $field['type'] : 'text';
		?>
		<input type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php echo $required ? 'required' : ''; ?> placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" class="<?php echo esc_attr( $common ); ?>">
	<?php endif; ?>
	<?php
	return ob_get_clean();
}
