/**
 * Javascript configurations for storefront
 *
 * @package dotdigital
 * @since 1.0.0
 */

(function( $ ) {
	'use strict';


	$(
		function() {
			$( "form.dd-ajax" ).on(
				'submit',
				function(e){
					e.preventDefault();
					$.ajax(
						{
							url: dd_ajax_handler.ajax_url,
							type: 'POST',
							dataType: 'json',
							data: {
								action: 'subscribe_to_newsletter',
								email: $( "#dd-email" ).val(),
								nonce: dd_ajax_handler.nonce
							},
							beforeSend: function() {
								$("#dd-submit").prop('disabled', true);
							},
							success: function(response){
								$("#dd-submit").prop('disabled', false);

								if (response.success) {
									$( ".dd-ajax" )[0].reset();
									$( ".dd-success-msg" )
										.css( "display","block" )
										.fadeIn( 'fast' )
										.delay( 3000 )
										.fadeOut( 'slow' );
								} else {
									$( ".dd-error-msg" )
										.css( "display","block" )
										.html( response.message )
										.fadeIn( 'fast' )
										.delay( 3000 )
										.fadeOut( 'slow' );
								}
							}, error: function(data){
								$( ".dd-error-msg" ).css( "display","block" );
								$( ".dd-error-msg" ).fadeIn( 'fast' ).delay( 3000 ).fadeOut( 'slow' );
							}
						}
					);
				}
			);
		}
	);
})( jQuery );
