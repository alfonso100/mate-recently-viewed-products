=== MATE Recently Viewed Products – Cache Compatible for WooCommerce ===
Contributors: alfonsocatron
Tags: woocommerce, recently viewed, ajax, caching, mate
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Track recently viewed WooCommerce products with AJAX and cookies. Fully cache-compatible. Includes shortcode and block.

== Description ==

MATE (Memory-Aware Tracking Engine) lets you display recently viewed WooCommerce products with full cache compatibility. It stores visits in a cookie and renders products via AJAX, ensuring accurate output even with aggressive caching.

You can configure the title, layout (image + title or title-only), and product count from the settings page. Display the list anywhere with a shortcode or Gutenberg block.

Perfect for boosting engagement, cross-selling, or giving customers a personalized touch.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin via WP Admin → Plugins
3. Adjust settings via **Settings → Recently Viewed Products**
4. Use the shortcode `[mrvp_recent_products count=5 layout="thumbs_titles" title="Your Title"]` or insert the **MATE** block in the editor

== Changelog ==

= 1.0.0 =
* First public release: settings page, shortcode, block, full cache compatibility

== Frequently Asked Questions ==

= Does it work with caching plugins? =
Yes. MATE uses JavaScript and AJAX to fetch the recently viewed list, so it's fully compatible with page caching.

= What data does it store? =
MATE stores product IDs in a browser cookie — no database writes required.

= Can I change the layout? =
Yes. You can choose between \"titles only\" and \"thumbnail + titles\" from the settings page or block editor.
