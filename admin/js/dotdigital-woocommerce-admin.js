/**
 * Javascript configurations for admin panel
 *
 * @since 1.0.0
 * @package dotdigital
 */

(function ($) {
	'use strict';

	$(
		function () {
			var iframe = $( '#dotdigital-for-woocommerce-settings' );
			if ( ! iframe.length) {
				return;
			}

			var resizeTimeout = false,
			win               = $( window ),
			header            = $( '#wpadminbar' ),
			adminMenu         = $( '#adminmenuwrap' ),
			message           = $( '#message' ),
			footer            = $( '#wpfooter' );

			function setIframeHeight() {
				var winHeight = win.height(),
				headerHeight  = header.outerHeight( true ),
				menuHeight    = adminMenu.outerHeight( true ),
				messageHeight = message.length ? message.outerHeight( true ) : 0,
				footerHeight  = footer.length && footer.is( ':visible' )
					? footer.outerHeight( true )
					: 4;

				if (winHeight < (headerHeight + menuHeight)) {
					iframe.height( menuHeight - messageHeight - footerHeight );
				} else {
					iframe.height( winHeight - headerHeight - messageHeight - footerHeight );
				}
			}

			function setIframeHeightOnResize(){
				if (resizeTimeout !== false) {
					clearTimeout( resizeTimeout );
				}
				resizeTimeout = setTimeout( setIframeHeight, 100 );
			}

			win.on( 'resize', setIframeHeightOnResize );
			win.on(
				'unload',
				function () {
					win.off( 'resize', setIframeHeightOnResize );
				}
			);

			setIframeHeight();
		}
	);

})( jQuery );
