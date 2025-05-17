function mrvpSetCookie(productId) {
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
	const $container = $('#mrvp-recently-viewed');
	const $loader = $('.mrvp-loading');
	const count = $container.data('mrvp-count') || 5;
	const title = $container.data('mrvp-title') || '';

	const viewedProductId = $('body').data('product-id');
	if (viewedProductId) {
		mrvpSetCookie(viewedProductId, count);
	}
	
	if (mrvp_ajax.show_spinner) {
		$loader.show();
	}
	
	$.ajax({
		url: mrvp_ajax.url,
		method: 'POST',
		data: {
		action: 'mrvp_get_products',
		nonce: mrvp_ajax.nonce,
		count: count,
		exclude: viewedProductId || '',
		title: $container.data('rvpa-title') || '',
		layout: $container.data('rvpa-layout') || '',
		show_price: mrvp_ajax.show_price ? 1 : 0,
		show_excerpt: mrvp_ajax.show_excerpt ? 1 : 0,			
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
