(function( $ ) {
	'use strict';

	var data = cart_insight.data;

	if (data.customer_email) {
		window.ddg.identify({ email: data.customer_email });
	}

	cartInsight (data);

	function cartInsight(data) {
		if (!data.cart_id) {
			return;
		}

		const cartData = {
			"cartId": data.cart_id,
			"cartPhase": data.cart_phase,
			"currency": data.currency,
			"subtotal": data.subtotal,
			"shipping": data.shipping,
			"discountAmount": data.discount_amount,
			"taxAmount": data.tax_amount,
			"grandTotal": data.grand_total,
			"cartUrl": data.cart_url,
			"products": mapLineItems( data.line_items )
		}

		if(data.order_id){
			cartData.orderId = data.order_id;
		}

		if (data.cart_phase === 'ORDER_COMPLETE') {
			window.ddg.purchaseComplete(cartData);
		} else if (data.cart_phase === 'ORDER_CHECKOUT') {
			window.ddg.checkout(cartData);
		} else {
			window.ddg.cartUpdate(cartData);
		}
	}

	function mapBaseItem(item) {
		return {
			productId: item.id,
			sku: item.sku,
			name: item.name,
			description: item.description,
			category: item.category,
			unitPrice: item.unit_price,
			salePrice: item.sale_price,
			quantity: item.quantity,
			totalPrice: item.total_price,
			totalPriceInclTax: item.total_price_incl_tax,
			imageUrl: item.image_url,
			productUrl: item.product_url
		};
	}

	function mapLineItems(lineItems) {
		var mapped = [];
		if (lineItems && lineItems.length) {
			mapped = lineItems.map( mapBaseItem );
		}
		return mapped;
	}

	function ajaxRefreshCartInsight(args) {
		$.ajax({
			url: cart_insight.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: args,
			success: function (response) {
				if (response.data) {
					if (args.email && typeof window.dmPt !== 'undefined') {
						window.ddg.identify({ email: args.email });
					}
					if (response.data.cart_id) {
						cartInsight(response.data);
					}
				}
			}
		});
	}

	$(function() {

		$( document.body ).on(
			'added_to_cart removed_from_cart updated_cart_totals wc_cart_emptied cart_page_refreshed cart_totals_refreshed',
			function(e) {
				ajaxRefreshCartInsight({
					action: 'update_cart'
				});
			}
		);

		$( document.body ).on(
			'wc-blocks_added_to_cart wc-blocks_removed_from_cart',
			function(e) {
				ajaxRefreshCartInsight({
					action: 'update_cart'
				});
			}
		);

		// WooCommerce Blocks - Cart page events
		if (typeof wp !== 'undefined' && wp.hooks) {
			wp.hooks.addAction('experimental__woocommerce_blocks-cart-set-item-quantity', 'cart-insight', function() {
				ajaxRefreshCartInsight({ action: 'update_cart' });
			});

			wp.hooks.addAction('experimental__woocommerce_blocks-cart-remove-item', 'cart-insight', function() {
				ajaxRefreshCartInsight({ action: 'update_cart' });
			});
		}

		$( document.body ).on(
			'blur',
			'input#dd-email, input#billing_email, .wc-block-components-address-form input#email',
			function() {
				ajaxRefreshCartInsight({
					action: 'update_session',
					email: $(this).val(),
					nonce: cart_insight.nonce
				});
			}
		);
	});

}(jQuery) );
