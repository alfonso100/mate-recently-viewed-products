<?php
/**
 * Plugin Name: MATE Recently Viewed Products – Cache Compatible for WooCommerce
 * Plugin URI:  https://alfonsocatron.com
 * Description: AJAX-powered recently viewed products for WooCommerce. Works with caching. Includes shortcode and block.
 * Version:     1.0.0
 * Author:      Alfonso Catrón
 * Author URI:  https://alfonsocatron.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mate-recently-viewed-products
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MRVP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once MRVP_PLUGIN_DIR . 'includes/settings.php';
require_once MRVP_PLUGIN_DIR . 'includes/ajax.php';
require_once MRVP_PLUGIN_DIR . 'includes/frontend.php';


add_action( 'enqueue_block_editor_assets', function() {
    wp_enqueue_script(
        'mrvp-block',
        plugins_url( 'assets/js/mrvp-block.js', __FILE__ ),
        [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor' ],
        '1.0.0',
        true
    );
});

add_action( 'init', function() {
    register_block_type( 'mrvp/recently-viewed', [
        'render_callback' => 'mrvp_render_block',
        'attributes' => [
            'title' => [ 'type' => 'string', 'default' => '' ],
            'count' => [ 'type' => 'number', 'default' => 5 ],
            'showImage' => [ 'type' => 'boolean', 'default' => true ],
            'showPrice' => [ 'type' => 'boolean', 'default' => false ],
            'showExcerpt' => [ 'type' => 'boolean', 'default' => false ],
        ],  
        'supports' => [
            'inserter' => true,
        ]
    ]);
});

function mrvp_render_block( $attributes ) {
    $title  = $attributes['title'] ?? '';
    $count  = $attributes['count'] ?? 5;
    $show_image = isset( $attributes['showImage'] ) ? (bool) $attributes['showImage'] : true;
    $show_price = isset( $attributes['showPrice'] ) ? (bool) $attributes['showPrice'] : false;
    $show_excerpt = isset( $attributes['showExcerpt'] ) ? (bool) $attributes['showExcerpt'] : false;

    return do_shortcode(
        '[mrvp_recent_products title="' . esc_attr( $title ) . '" count="' . $count . '" show_image="' . ( $show_image ? '1' : '0' ) . '" show_price="' . ( $show_price ? '1' : '0' ) . '"  show_excerpt="' . ( $show_excerpt ? '1' : '0' ) . '"]'
    );
    

}