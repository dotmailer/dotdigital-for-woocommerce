<?php
/**
 * Defines and handles EC subscription form widget.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes
 */

namespace Dotdigital_WooCommerce\Includes\Widgets;

use WP_Widget;

/**
 * Class Dotdigital_WooCommerce_Widget
 */
class Dotdigital_WooCommerce_Widget extends WP_Widget {

	/**
	 * Dotdigital_WooCommerce_Widget constructor.
	 */
	public function __construct() {
		parent::__construct(
			'dd_signup',
			'Dotdigital for WooCommerce Signup Form',
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	/**
	 * Initializes the backend configuration.
	 *
	 * @param array $instance Form instance.
	 * @return string|void
	 */
	public function form( $instance ) {

		$defaults = array(
			'form_title'       => 'Newsletter Signup',
			'button_text'      => 'Submit',
			'success_text'     => 'Success',
			'placeholder_text' => 'Enter your email',
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<?php // Form Title. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'form_title' ) ); ?>"><?php esc_html_e( 'Form Title', 'dotdigital-for-woocommerce' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['form_title'] ); ?>" />
		</p>

		<?php // Button Text. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text', 'dotdigital-for-woocommerce' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['button_text'] ); ?>" />
		</p>

		<?php // Success Text. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'success_text' ) ); ?>"><?php esc_html_e( 'Success Text', 'dotdigital-for-woocommerce' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'success_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'success_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['success_text'] ); ?>" />
		</p>

		<?php // Placeholder Text. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'placeholder_text' ) ); ?>"><?php esc_html_e( 'Placeholder Text', 'dotdigital-for-woocommerce' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['placeholder_text'] ); ?>" />
		</p>

		<?php
	}

	/**
	 * Update form configurations.
	 *
	 * @param array $new_instance New Instance vars.
	 * @param array $old_instance Old Instance vars.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                     = $old_instance;
		$instance['form_title']       = isset( $new_instance['form_title'] ) ? wp_strip_all_tags( $new_instance['form_title'] ) : '';
		$instance['button_text']      = isset( $new_instance['button_text'] ) ? wp_strip_all_tags( $new_instance['button_text'] ) : '';
		$instance['success_text']     = isset( $new_instance['success_text'] ) ? wp_strip_all_tags( $new_instance['success_text'] ) : '';
		$instance['placeholder_text'] = isset( $new_instance['placeholder_text'] ) ? wp_strip_all_tags( $new_instance['placeholder_text'] ) : '';

		return $instance;
	}

	/**
	 * Frontend appearance.
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {

		$form_title   = isset( $instance['form_title'] ) ? apply_filters( 'widget_title', $instance['form_title'] ) : '';
		$button_text  = isset( $instance['button_text'] ) ? $instance['button_text'] : '';
		$success_text = isset( $instance['success_text'] ) ? $instance['success_text'] : '';
		$placeholder_text = isset( $instance['placeholder_text'] ) ? $instance['placeholder_text'] : '';

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $form_title ) {
			echo $args['before_title'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo esc_html( $form_title );
			echo $args['after_title'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>

		<div class="dd-sign-up-form">
			<form action="" method="post" class="dd-ajax"
				  enctype="multipart/form-data">

				<div class="dd-form-content">
					<input type="email" placeholder="<?php echo esc_attr( $placeholder_text ); ?>" name="email" id="dd-email" class="dd-email" required>
					<input type="submit" id="dd-submit" class="dd-submit-btn" value="<?php echo esc_attr( $button_text ); ?>">
				</div>

				<div class="dd-success-msg" style="display: none"><?php echo esc_html( $success_text ); ?> </div>
				<div class="dd-error-msg" style="display: none">There was an error during subscription.</div>
			</form>
		</div>
		<?php
		echo $args['after_widget'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
