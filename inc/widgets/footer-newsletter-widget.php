<?php
/**
 * Footer "Newsletter Signup" widget — wraps the existing
 * bootg_render_newsletter_form() (inc/blocks.php), no new form logic.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bootg_Footer_Newsletter_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct( 'bootg_footer_newsletter', 'Newsletter Signup (Footer)', array(
			'description' => 'The "Stay Connected" email signup form.',
		) );
	}

	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Stay Connected';
		$text  = ! empty( $instance['text'] ) ? $instance['text'] : 'Sign up to receive news, updates, and compliance alerts.';

		echo $args['before_widget']; // phpcs:ignore
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore
		}
		if ( $text ) {
			echo '<p class="text-sm text-white/60 mb-4">' . esc_html( $text ) . '</p>'; // phpcs:ignore
		}
		echo bootg_render_newsletter_form(); // phpcs:ignore
		echo $args['after_widget']; // phpcs:ignore
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Stay Connected';
		$text  = ! empty( $instance['text'] ) ? $instance['text'] : 'Sign up to receive news, updates, and compliance alerts.';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Title</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>">Intro text</label>
			<textarea class="widefat" rows="2" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $text ); ?></textarea>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		return array(
			'title' => sanitize_text_field( $new_instance['title'] ?? '' ),
			'text'  => sanitize_textarea_field( $new_instance['text'] ?? '' ),
		);
	}
}

add_action( 'widgets_init', function () {
	register_widget( 'Bootg_Footer_Newsletter_Widget' );
} );
