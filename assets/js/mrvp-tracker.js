function mrvpSetCookie(productId) {
	const cookieName = 'mrvp_recently_viewed';
	const existing = Cookies.get(cookieName);
	let ids = existing ? existing.split(',') : [];

	ids = ids.filter(id => id !== productId.toString());
	ids.unshift(productId);

	const max = 5; // default max, or make this dynamic later
	ids = ids.slice(0, max);

	Cookies.set(cookieName, ids.join(','), { expires: 1, path: '/' });
}

jQuery(document).ready(function($) {
	const viewedProductId = $('body').data('product-id');
	if (viewedProductId) {
		mrvpSetCookie(viewedProductId);
	}
});
