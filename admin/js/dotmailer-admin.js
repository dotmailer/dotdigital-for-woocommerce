(function ($) {
	'use strict';

	$(function () {
		var iframe = $('#dotmailer-settings');
		if (!iframe.length)
			return;

		var resizeTimeout = false,
			win = $(window),
			header = $('#wpadminbar'),
			adminMenu = $('#adminmenuwrap'),
			footer = $('#wpfooter');

		function setIframeHeight() {
			var winHeight = win.height(),
				headerHeight = header.outerHeight(true),
				menuHeight = adminMenu.outerHeight(true),
				footerHeight = footer.length && footer.is(':visible')
					? footer.outerHeight(true)
					: 4;

			if (winHeight < (headerHeight + menuHeight))
				iframe.height(menuHeight - footerHeight);
			else
				iframe.height(winHeight - headerHeight - footerHeight);
		}

		function setIframeHeightOnResize(){
			if (resizeTimeout !== false)
				clearTimeout(resizeTimeout);
			resizeTimeout = setTimeout(setIframeHeight, 100);
		}

		win.on('resize', setIframeHeightOnResize);
		win.on('unload', function () {
			win.off('resize', setIframeHeightOnResize);
		});

		setIframeHeight();
	});

})(jQuery);
