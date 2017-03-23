(function ($) {
	'use strict';

	$(function () {
		var iframe = $('#wcSettings');
		if (!iframe.length)
			return;

		var resizeTimeout = false,
			win = $(window),
			header = $('#wpadminbar'),
			footer = $('#wpfooter').filter(':visible');

		function setIframeHeight() {
			var winHeight = $(window).height();
			var docHeight = $(document).height();
			var menuHeight = $('#adminmenuwrap').outerHeight(true);

			var headerHeight = header.outerHeight(true);
			var footerHeight = footer.length
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
