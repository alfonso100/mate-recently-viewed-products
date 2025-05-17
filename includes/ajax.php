<?php
add_action( 'wp_enqueue_scripts', 'mrvp_enqueue_scripts', 5 );
function mrvp_enqueue_scripts() {
	$plugin_url = plugin_dir_url( dirname( __FILE__ ) );

	wp_register_script( 'js-cookie', $plugin_url . 'assets/js/js.cookie.min.js', [], '2.2.1', true );
	wp_enqueue_script( 'js-cookie' );

	wp_enqueue_script( 'mrvp-frontend', $plugin_url . 'assets/js/mrvp-frontend.js', [ 'jquery', 'js-cookie' ], '1.0.0', true );
	wp_localize_script( 'mrvp-frontend', 'mrvp_ajax', [
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'mrvp_nonce' ),
		'max_count' => get_option( 'mrvp_number_of_products', 5 ),
	]);
}

add_action( 'wp_ajax_nopriv_mrvp_get_products', 'mrvp_ajax_get_products' ); // for visitors
add_action( 'wp_ajax_mrvp_get_products', 'mrvp_ajax_get_products' ); 
function mrvp_ajax_get_products() {
	check_ajax_referer( 'mrvp_nonce', 'nonce' );

	$count = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 5;
	$exclude = isset( $_POST['exclude'] ) ? absint( $_POST['exclude'] ) : 0;
	$raw_cookie = isset( $_COOKIE['mrvp_recently_viewed'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mrvp_recently_viewed'] ) ) : '';
	$cookie = $raw_cookie ? explode( ',', $raw_cookie ) : [];
		$cookie = array_filter( $cookie ); // remove empty values
		$cookie = array_map( 'absint', $cookie ); // sanitize integers
		
		if ( $exclude ) {
			$cookie = array_diff( $cookie, [ $exclude ] );
		}
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

	$layout = get_option( 'mrvp_layout', 'thumbs_titles' );
	$title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : get_option( 'mrvp_widget_title', 'Recently Viewed' );

	
	echo '<div class="mrvp-wrapper">';
	echo '<h4>' . esc_html( $title ) . '</h4>';
	echo '<ul class="mrvp-product-list">';
	
	while ( $products->have_posts() ) {
		$products->the_post();
		$product_id = get_the_ID();
		$product_link = get_permalink( $product_id );
		$product_title = get_the_title( $product_id );
	
		echo '<li>';
		echo '<a href="' . esc_url( $product_link ) . '" class="mrvp-product-link">';
	
		if ( $layout === 'thumbs_titles' ) {
			$product_image = get_the_post_thumbnail( $product_id, 'shop_thumbnail' );
			echo wp_kses_post( $product_image );
		}
	
		echo '<span>' . esc_html( $product_title ) . '</span>';
		echo '</a>';
		echo '</li>';
	}
	
	echo '</ul>';
	echo '</div>';

	wp_reset_postdata();

	wp_send_json_success( ob_get_clean() );
}

add_shortcode( 'mrvp_recent_products', function( $atts ) {
	$atts = shortcode_atts( [
		'count'  => get_option( 'mrvp_number_of_products', 5 ),
		'title'  => get_option( 'mrvp_widget_title', 'Recently Viewed' ),
		'layout' => get_option( 'mrvp_layout', 'thumbs_titles' ),
	], $atts );

	$count  = absint( $atts['count'] );
	$title  = sanitize_text_field( $atts['title'] );
	$layout = sanitize_text_field( $atts['layout'] );

	return '<div class="mrvp-container">
		<div class="mrvp-loading"><div class="mrvp-spinner"></div></div>
		<div id="mrvp-recently-viewed"
			data-mrvp-count="' . esc_attr( $count ) . '"
			data-mrvp-title="' . esc_attr( $title ) . '"
			data-mrvp-layout="' . esc_attr( $layout ) . '"
		></div>
	</div>';
});