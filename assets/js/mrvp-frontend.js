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
	$('.mrvp-recently-viewed').each(function() {
		const $container = $(this);
	const $loader = $container.find('.mrvp-loading'); 

	const count  = $container.data('mrvp-count') || 5;
	const title  = $container.data('mrvp-title') || '';
	const showImage = parseInt($container.data('mrvp-show-image')) === 1;
	const showPrice = parseInt($container.data('mrvp-show-price')) === 1;
	const showExcerpt = parseInt($container.data('mrvp-show-excerpt')) === 1;

	const viewedProductId = $('body').data('product-id');

	// Set the cookie based on the product ID
	if (viewedProductId) {
		mrvpSetCookie(viewedProductId, count);
	}


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
			show_image: showImage ? 1 : 0,
			show_price: showPrice ? 1 : 0,
			show_excerpt: showExcerpt ? 1 : 0
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
});
