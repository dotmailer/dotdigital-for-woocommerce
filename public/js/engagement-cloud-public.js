/**
 * Javascript configurations for storefront
 *
 * @package dotdigital
 * @since 1.0.0
 */

(function( $ ) {
	'use strict';

		/**
		 * All of the code for your public-facing JavaScript source
		 * should reside in this file.
		 *
		 * Note: It has been assumed you will write jQuery code here, so the
		 * $ function reference has been prepared for usage within the scope
		 * of this function.
		 *
		 * This enables you to define handlers, for when the DOM is ready:
		 *
		 * $(function() {
		 *
		 * });
		 *
		 * When the window is loaded:
		 *
		 * $( window ).load(function() {
		 *
		 * });
		 *
		 * ...and/or other possibilities.
		 *
		 * Ideally, it is not considered best practise to attach more than a
		 * single DOM-ready or window-load handler for a particular page.
		 * Although scripts in the WordPress core, Plugins and Themes may be
		 * practising this, we should strive to set a better example in our own work.
		 */

	$(
		function() {
			$( "form.ec-ajax" ).on(
				'submit',
				function(e){
					e.preventDefault();
					$.ajax(
						{
							url: ec_ajax_handler.ajax_url,
							type: 'POST',
							dataType: 'text',
							data: {
								action: 'subscribe_to_newsletter',
								email: $( "#ec-email" ).val(),
								nonce: ec_ajax_handler.nonce
							},
							beforeSend: function() {
								$("#ec-submit").prop('disabled', true);
							},
							success: function(response){
								$("#ec-submit").prop('disabled', false);
								var response = jQuery.parseJSON( response );

								if (response.success) {
									$( ".ec-ajax" )[0].reset();
									$( ".ec-success-msg" )
										.css( "display","block" )
										.fadeIn( 'fast' )
										.delay( 3000 )
										.fadeOut( 'slow' );
								} else {
									$( ".ec-error-msg" )
										.css( "display","block" )
										.html( response.message )
										.fadeIn( 'fast' )
										.delay( 3000 )
										.fadeOut( 'slow' );
								}
							}, error: function(data){
								$( ".ec-error-msg" ).css( "display","block" );
								$( ".ec-error-msg" ).fadeIn( 'fast' ).delay( 3000 ).fadeOut( 'slow' );
							}
						}
					);
				}
			);
		}
	);
})( jQuery );
