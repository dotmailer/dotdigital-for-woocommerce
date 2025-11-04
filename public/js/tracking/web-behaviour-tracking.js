if (wbt_data.region && wbt_data.profile_id ) {
	window.ddg.init(wbt_data.region, wbt_data.profile_id);

	if (typeof product_data !== 'undefined') {
		window.ddg.productBrowse(product_data.data);
	}
}
