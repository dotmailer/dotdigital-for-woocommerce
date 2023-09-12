/**
 * Javascript configurations for storefront
 *
 * @package
 * @since 1.0.0
 */

/**
 * Get the global variable
 *
 * @param {string} variable_name
 * @return {*|boolean} The global variable
 */
const get_global_variable = ( variable_name ) => {
	const global = typeof window[ variable_name ] !== 'undefined' ? window[ variable_name ] : null;

	if ( typeof variable_name === 'undefined' || variable_name === null ) {
		return false;
	}

	return global;
};

/**
 * Get the error messages
 *
 * @return {unknown} The error message
 * @param {number|string} message_code The message code
 */
const get_error_messages = ( message_code ) => {
	const dotdigital_intl = get_global_variable( 'dotdigital_intl' );
	const error_map = Object.values( dotdigital_intl.error_messages );

	if ( error_map[ message_code ] !== undefined ) {
		return error_map[ message_code ];
	}

	return error_map[ 0 ];
};

/**
 * Validate the input
 *
 * @param {Event}   event   The event
 * @param {Element} element The element
 */
const validate_phone_input = ( event, element ) => {
	const dotdigital_intl = get_global_variable( 'dotdigital_intl' );
	const input = element ?? jQuery( event.currentTarget );
	const row = input.closest( `.form-row` );
	const valid = input.intlTelInput( 'isValidNumber' );
	const phone_valid_input = jQuery( `.${ dotdigital_intl.plugin_name }-phone-input-hidden` ).find( 'input' );

	if ( valid ) {
		row.removeClass( 'woocommerce-invalid' );
		row.removeClass( 'woocommerce-invalid-phone' );
		row.addClass( 'woocommerce-validated' );
		input.val( input.intlTelInput( 'getNumber' ) );
		phone_valid_input.val( true );
	} else {
		setTimeout( () => {
			row.addClass( 'woocommerce-invalid' );
			row.addClass( 'woocommerce-invalid-phone' );
			phone_valid_input.val( get_error_messages( input.intlTelInput( 'getValidationError' ) ) );
		}, 10 );
	}
};

/**
 * Show or hide consent phone validation input
 *
 * @param {Event}   event   The event
 * @param {Element} element The element
 */
const marketing_consent_visibility = ( event, element ) => {
	const dotdigital_intl = get_global_variable( 'dotdigital_intl' );
	const input = element ?? jQuery( event.currentTarget );
	const consent_wrapper = jQuery( `.${ dotdigital_intl.plugin_name }-phone-input` );
	const description = consent_wrapper.find( `.description` );

	if ( input.is( ':checked' ) ) {
		consent_wrapper.show();
		description.show();
	} else {
		consent_wrapper.hide();
		description.hide();
	}
};

( function( $ ) {
	'use strict';

	$(
		function() {
			const dd_ajax_handler = get_global_variable( 'dd_ajax_handler' );
			$( 'form.dd-ajax' ).on(
				'submit',
				function( e ) {
					e.preventDefault();
					$.ajax(
						{
							url: dd_ajax_handler.ajax_url,
							type: 'POST',
							dataType: 'json',
							data: {
								action: 'subscribe_to_newsletter',
								email: $( '#dd-email' ).val(),
								nonce: dd_ajax_handler.nonce,
							},
							beforeSend() {
								$( '#dd-submit' ).prop( 'disabled', true );
							},
							success( response ) {
								$( '#dd-submit' ).prop( 'disabled', false );

								if ( response.success ) {
									$( '.dd-ajax' )[ 0 ].reset();
									$( '.dd-success-msg' )
										.css( 'display', 'block' )
										.fadeIn( 'fast' )
										.delay( 3000 )
										.fadeOut( 'slow' );
								} else {
									$( '.dd-error-msg' )
										.css( 'display', 'block' )
										.html( response.message )
										.fadeIn( 'fast' )
										.delay( 3000 )
										.fadeOut( 'slow' );
								}
							}, error() {
								$( '.dd-error-msg' ).css( 'display', 'block' );
								$( '.dd-error-msg' ).fadeIn( 'fast' ).delay( 3000 ).fadeOut( 'slow' );
							},
						}
					);
				}
			);
		}
	);

	$( document ).ready( function() {
		const dotdigital_intl = get_global_variable( 'dotdigital_intl' );
		if ( ! dotdigital_intl ) {
			return;
		}
		// eslint-disable-next-line no-undef
		const itl_input = $( `.${ dotdigital_intl.plugin_name }-phone-input` )
			.find( 'input' )
			.intlTelInput( {
				initialCountry: dotdigital_intl.default_country,
				onlyCountries: Object.keys( dotdigital_intl.allowed_countries ),
				utilsScript: dotdigital_intl.utils_script,
			} )
			.on( 'countrychange', ( event ) => validate_phone_input( event ) )
			.on( 'keyup', ( event ) => validate_phone_input( event ) )
			.on( 'valid', ( event ) => validate_phone_input( event ) )
			.on( 'change', ( event ) => validate_phone_input( event ) );

		const marketing_consent_checkbox = $( `.${ dotdigital_intl.plugin_name }-sms-checkbox` )
			.find( 'input' )
			.on( 'load', ( event ) => marketing_consent_visibility( event ) )
			.on( 'change', ( event ) => marketing_consent_visibility( event ) );

		$( 'form[name="checkout"]' )
			.on( 'validate', ( event ) => validate_phone_input( event, itl_input ) );

		marketing_consent_visibility( null, marketing_consent_checkbox );
		validate_phone_input( null, itl_input );
	} );
}( jQuery ) );
