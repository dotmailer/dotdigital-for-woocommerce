/**
 * Javascript configurations for ROI script.
 *
 * @package dotdigital
 * @since 1.2.0
 */

(function() {
    'use strict';
        if (typeof order_data != "undefined") {
            var lineItemCount = order_data.line_items.length,
                i = 0;

            for (i; i < lineItemCount; i++) {
                window._dmTrack('product', order_data.line_items[i]);
            }
            window._dmTrack('CheckOutAmount', order_data.total);
        }
})();
