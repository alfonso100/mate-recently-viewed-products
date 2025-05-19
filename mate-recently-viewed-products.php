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
            'layout' => [ 'type' => 'string', 'default' => 'thumbs_titles' ],
        ]
    ]);
});

function mrvp_render_block( $attributes ) {
    $title  = $attributes['title'] ?? '';
    $count  = $attributes['count'] ?? 5;
    $layout = $attributes['layout'] ?? 'thumbs_titles';

    return do_shortcode('[mrvp_recent_products title="' . esc_attr($title) . '" count="' . $count . '" layout="' . esc_attr($layout) . '"]');
}