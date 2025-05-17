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
