<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
		'show_spinner'  => (int) get_option( 'mrvp_show_spinner', 1 ),
		'show_widgettitle'    => (int) get_option( 'mrvp_show_widgettitle', 1 ),
		'show_price'    => (bool) get_option( 'mrvp_show_price', 0 ),
		'show_excerpt'  => (bool) get_option( 'mrvp_show_excerpt', 0 ),
	]);
}

add_action( 'wp_ajax_nopriv_mrvp_get_products', 'mrvp_ajax_get_products' ); // for visitors
add_action( 'wp_ajax_mrvp_get_products', 'mrvp_ajax_get_products' ); 

function mrvp_ajax_get_products() {
	check_ajax_referer( 'mrvp_nonce', 'nonce' );
	$count = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 5;
	$exclude = isset( $_POST['exclude'] ) ? absint( $_POST['exclude'] ) : 0;
	$show_widgettitle   = isset( $_POST['show_widgettitle'] ) ? (bool) $_POST['show_widgettitle'] : true;
	$show_image = isset( $_POST['show_image'] )	? (bool) $_POST['show_image'] : (bool) get_option( 'mrvp_show_image', 1 );
	$show_price = isset( $_POST['show_price'] )	? (bool) $_POST['show_price'] : (bool) get_option( 'mrvp_show_price', 0 );
	$show_excerpt = isset( $_POST['show_excerpt'] ) ? (bool) $_POST['show_excerpt'] : false;
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

	$title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
	$title = $title !== '' ? $title : get_option( 'mrvp_widget_title', 'Recently Viewed' );
	$show_widgettitle = (int) get_option( 'mrvp_show_widgettitle', 1 );

	
	echo '<div class="mrvp-wrapper">';
	if ( $show_widgettitle ) {
	echo '<h4>' . esc_html( $title ) . '</h4>';
	}
	
	echo '<ul class="mrvp-product-list">';
	
	while ( $products->have_posts() ) {
		$products->the_post();
		$product_id = get_the_ID();
		$product_link = get_permalink( $product_id );
		$product_title = get_the_title( $product_id );
	
		echo '<li>';
		echo '<a href="' . esc_url( $product_link ) . '" class="mrvp-product-link">';
	
		if ( $show_image ) {
			$product_image = get_the_post_thumbnail( $product_id, 'shop_thumbnail' );
			echo wp_kses_post( $product_image );
		}
	
		echo '<div class="mrvp-info">';
		echo '<div class="mrvp-title">' . esc_html( $product_title ) . '</div>';

		if ( $show_price ) {
			$product = wc_get_product( $product_id );
			echo '<div class="mrvp-price">' . wp_kses_post( $product->get_price_html() ) . '</div>';
		}
		
		if ( $show_excerpt ) {
			$excerpt = wp_trim_words( get_the_excerpt(), 15, '…' );
			echo '<div class="mrvp-excerpt">' . wp_kses_post( $excerpt ) . '</div>';
		}
		echo '</div>';
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
		'count' 			=> get_option( 'mrvp_number_of_products', 5 ),
		'title' 			=> get_option( 'mrvp_widget_title', 'Recently Viewed' ),
		'show_image' 		=> get_option( 'mrvp_show_image', 1 ),
		'show_price'        => get_option( 'mrvp_show_price', 0 ),
		'show_excerpt'      => get_option( 'mrvp_show_excerpt', 0 ),
		'show_spinner'      => get_option( 'mrvp_show_spinner', 1 ),
		'show_widgettitle'  => get_option( 'mrvp_show_widgettitle', 1 ),
	], $atts );

	$count  = absint( $atts['count'] );
	$title  = sanitize_text_field( $atts['title'] );
	$show_image        = (int) $atts['show_image'];
	$show_price        = (int) $atts['show_price'];
	$show_excerpt      = (int) $atts['show_excerpt'];
	$show_spinner      = (int) $atts['show_spinner'];
	$show_widgettitle  = (int) $atts['show_widgettitle'];

	$unique_id = 'mrvp-' . uniqid();

	return '<div class="mrvp-container">
		<div class="mrvp-recently-viewed"
		id="' . esc_attr( $unique_id ) . '"
		data-mrvp-count="' . esc_attr( $count ) . '"
		data-mrvp-title="' . esc_attr( $title ) . '"
		data-mrvp-show-image="' . esc_attr( $show_image ) . '"
		data-mrvp-show-price="' . esc_attr( $show_price ) . '"
		data-mrvp-show-excerpt="' . esc_attr( $show_excerpt ) . '"
		data-mrvp-show-spinner="' . esc_attr( $show_spinner ) . '"
		data-mrvp-show-title="' . esc_attr( $show_widgettitle ) . '"
		><div class="mrvp-loading"><div class="mrvp-spinner"></div></div></div>
	</div>';
});