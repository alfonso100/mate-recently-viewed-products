function rvpaSetCookie(productId, limit = 5) {
	const cookieName = 'rvpa_recently_viewed';
	const existing = Cookies.get(cookieName);
	let ids = existing ? existing.split(',') : [];

	ids = ids.filter(id => id !== productId.toString());
	ids.unshift(productId);
	ids = ids.slice(0, limit);

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
		url: rvpa_ajax.url,
		method: 'POST',
		data: {
			action: 'rvpa_get_products',
			nonce: rvpa_ajax.nonce,
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
