function rvpaSetCookie(productId) {
	const cookieName = 'mrvp_recently_viewed';
	const existing = Cookies.get(cookieName);
	let ids = existing ? existing.split(',') : [];

	ids = ids.filter(id => id !== productId.toString());
	ids.unshift(productId);

	const max = mrvp_ajax.max_count || 5;
	ids = ids.slice(0, max);

	Cookies.set(cookieName, ids.join(','), { expires: 1, path: '/' });
}

jQuery(document).ready(function($) {
	const $container = $('#rvpa-recently-viewed');
	const $loader = $('.rvpa-loading');
	const count = $container.data('rvpa-count') || 5;

	const viewedProductId = $('body').data('product-id');
	if (viewedProductId) {
		rvpaSetCookie(viewedProductId, count);
	}
	
	$loader.show();
	
	$.ajax({
		url: mrvp_ajax.url,
		method: 'POST',
		data: {
			action: 'mrvp_get_products',
			nonce: mrvp_ajax.nonce,
			count: count,
			exclude: viewedProductId || ''
		},
		success: function(response) {
			if (response.success) {
				$container.html(response.data);
			}
		},
		complete: function() {
			$loader.hide();
		}
	});
});
