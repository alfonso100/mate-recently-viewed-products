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
	const $loader = $container.find('.mrvp-loading'); 

	const count  = $container.data('mrvp-count') || 5;
	const title  = $container.data('mrvp-title') || '';
	const layout = $container.data('mrvp-layout') || '';
	const viewedProductId = $('body').data('product-id');

	// Set the cookie based on the product ID
	if (viewedProductId) {
		mrvpSetCookie(viewedProductId, count);
console.log(mrvp_ajax.show_spinner);
console.log($loader);

	}

console.log('Show condition is:', parseInt(mrvp_ajax.show_spinner) === 1);

	// Show spinner only if enabled in settings
	if (parseInt(mrvp_ajax.show_spinner) === 1) {
		$loader.show();
	}

	// AJAX request to fetch recently viewed products
	$.ajax({
		url: mrvp_ajax.url,
		method: 'POST',
		data: {
			action: 'mrvp_get_products',
			nonce: mrvp_ajax.nonce,
			count: count,
			exclude: viewedProductId || '',
			title: title,
			layout: layout,
			show_price: mrvp_ajax.show_price ? 1 : 0,
			show_excerpt: mrvp_ajax.show_excerpt ? 1 : 0
		},
		success: function(response) {
			if (response.success) {
				$container.html(response.data);
			}
		},
		complete: function() {
			$loader.hide(); // Always hide it after loading
		}
	});
});
