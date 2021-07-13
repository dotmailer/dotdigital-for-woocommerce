<?php
/**
 * Defines and handles EC subscription form widget.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes
 */

/**
 * Class Engagement_Cloud_Widget
 */
class Engagement_Cloud_Widget extends WP_Widget {

	/**
	 * Engagement_Cloud_Widget constructor.
	 */
	public function __construct() {
		parent::__construct(
			'ec_signup',
			'Engagement Cloud Sign-up Form',
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	/**
	 * Initializes the backend configuration .
	 *
	 * @param array $instance Form instance .
	 * @return string|void
	 */
	public function form( $instance ) {

		$defaults = array(
			'form_title'   => '',
			'button_text'  => '',
			'success_text' => '',
		);
        // @codingStandardsIgnoreStart
        extract( wp_parse_args( (array) $instance, $defaults ) ); ?>

		<?php // Form Title. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'form_title' ) ); ?>"><?php _e( 'Form Title', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_title' ) ); ?>" type="text" value="<?php echo esc_attr( $form_title ); ?>" />
		</p>

		<?php // Button Text. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php _e( 'Button Text', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" />
		</p>

		<?php // Success Text. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'success_text' ) ); ?>"><?php _e( 'Success Text:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'success_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'success_text' ) ); ?>" type="text" value="<?php echo esc_attr( $success_text ); ?>" />
		</p>

		<?php
        // @codingStandardsIgnoreEnd
	}

	/**
	 * Update form configurations .
	 *
	 * @param array $new_instance New Instance vars.
	 * @param array $old_instance Old Instance vars.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = $old_instance;
		$instance['form_title']   = isset( $new_instance['form_title'] ) ? wp_strip_all_tags( $new_instance['form_title'] ) : '';
		$instance['button_text']  = isset( $new_instance['button_text'] ) ? wp_strip_all_tags( $new_instance['button_text'] ) : '';
		$instance['success_text'] = isset( $new_instance['success_text'] ) ? wp_strip_all_tags( $new_instance['success_text'] ) : '';

		return $instance;
	}

	/**
	 * Frontend appearance .
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {

        // @codingStandardsIgnoreStart
        extract( $args );

		// Check the widget options .
		$form_title   = isset( $instance['form_title'] ) ? apply_filters( 'widget_title', $instance['form_title'] ) : '';
		$button_text  = isset( $instance['button_text'] ) ? $instance['button_text'] : '';
		$success_text = isset( $instance['success_text'] ) ? $instance['success_text'] : '';
		echo $before_widget;

		// Display the widget title .
			echo $before_title . $form_title . $after_title;
		?>

		<div class="engagement-cloud-sign-up-form">
			<form action="" method="post" class="engagement-cloud-ajax"
				  enctype="multipart/form-data">

				<div class="ec-form-content">
					<input type="email" placeholder="Enter your Email" name="email" required class="email">
					<input type="submit" class="submitbtn" value="<?php echo $button_text; ?>">
				</div>

				<div class="success_msg" style="display: none"><?php echo $success_text; ?> </div>
				<div class="error_msg" style="display: none">There was an error during subscription.</div>
			</form>
		</div>
		<?php
		echo $after_widget;
        // @codingStandardsIgnoreEnd
	}
}
