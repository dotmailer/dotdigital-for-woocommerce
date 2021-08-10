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
			$( "form.ec-ajax" ).on(
				'submit',
				function(e){
					e.preventDefault();
					$.ajax(
						{
							url: ec_ajax_handler.ajax_url,
							type: 'POST',
							dataType: 'json',
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
