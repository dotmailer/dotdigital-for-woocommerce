(function( $ ) {
	'use strict';

	var data = cart_insight.data;

	if (data.customer_email) {
		window.dmPt( "identify", data.customer_email );
	}

	cartInsight(data);

	function cartInsight(data) {
		if (!data.cart_id) {
			return;
		}

		window.dmPt(
			"cartInsight",
			{
				"programID": data.program_id,
				"cartDelay": data.cart_delay,
				"cartID": data.cart_id,
				"cartPhase": data.cart_phase,
				"currency": data.currency,
				"subtotal": data.subtotal,
				"shipping": data.shipping,
				"discountAmount": data.discount_amount,
				"taxAmount": data.tax_amount,
				"grandTotal": data.grand_total,
				"cartUrl": data.cart_url,
				"lineItems": mapLineItems( data.line_items )
			}
		);
	}

	function mapBaseItem(item) {
		return {
			sku: item.sku,
			name: item.name,
			description: item.description,
			category: item.category,
			unitPrice: item.unit_price,
			salePrice: item.sale_price,
			quantity: item.quantity,
			totalPrice: item.total_price,
			totalPrice_incl_tax: item.total_price_incl_tax,
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
						window.dmPt('identify', args.email);
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
			'added_to_cart removed_from_cart updated_cart_totals',
			function() {
				ajaxRefreshCartInsight({
					action: 'update_cart'
				});
			}
		);

		$( document.body ).on(
			'blur',
			'input#dd-email, input#billing_email',
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
