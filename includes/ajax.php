<?php
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script( 'js-cookie', plugin_dir_url( __FILE__ ) . '../assets/js/js.cookie.min.js', [], '2.2.1', true );
	wp_enqueue_script( 'rvpa-frontend', plugin_dir_url( __FILE__ ) . '../assets/js/rvpa-frontend.js', [ 'jquery', 'js-cookie' ], '1.0.0', true );
	wp_localize_script( 'rvpa-frontend', 'rvpa_ajax', [
		'url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'rvpa_nonce' )
	]);
});

add_action( 'wp_ajax_nopriv_rvpa_get_products', 'rvpa_ajax_get_products' );
function rvpa_ajax_get_products() {
	check_ajax_referer( 'rvpa_nonce', 'nonce' );

	$count = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 5;
	$raw_cookie = isset( $_COOKIE['rvpa_recently_viewed'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['rvpa_recently_viewed'] ) ) : '';
	$cookie = $raw_cookie ? explode( ',', $raw_cookie ) : [];
	$cookie = array_slice( $cookie, 0, $count );

	if ( empty( $cookie ) ) {
		wp_send_json_success( [] );
	}

	$args = [
		'post_type' => 'product',
		'post__in'  => $cookie,
		'orderby'   => 'post__in',
		'posts_per_page' => count( $cookie ),
	];

	$products = new WP_Query( $args );
	ob_start();

	echo '<div class="rvpa-wrapper">';
	echo '<h4>Recently Viewed</h4>';
	echo '<ul class="rvpa-product-list">';

	while ( $products->have_posts() ) {
		$products->the_post();
		$product_id = get_the_ID();
		$product_link = get_permalink( $product_id );
		$product_title = get_the_title( $product_id );
		$product_image = get_the_post_thumbnail( $product_id, 'woocommerce_thumbnail' );

		echo '<li>';
		echo '<a href="' . esc_url( $product_link ) . '" class="rvpa-product-link">';
		echo wp_kses_post( $product_image );
		echo '<div>' . esc_html( $product_title ) . '</div>';
		echo '</a>';
		echo '</li>';
	}

	echo '</ul>';
	echo '</div>';

	wp_reset_postdata();

	wp_send_json_success( ob_get_clean() );
}

add_shortcode( 'rvpa_recent_products', function( $atts ) {
	$atts = shortcode_atts( [
		'count' => get_option( 'rvpa_number_of_products', 5 ),
	], $atts );

	$count = absint( $atts['count'] );

	return '<div class="rvpa-container">
		<div class="rvpa-loading"><div class="rvpa-spinner"></div></div>
		<div id="rvpa-recently-viewed" data-rvpa-count="' . esc_attr( $count ) . '"></div>
	</div>';
});
