<?php
function mrvp_register_settings() {
	add_option( 'mrvp_number_of_products', 5 );
	add_option( 'mrvp_widget_title', 'Recently Viewed' );
	add_option( 'mrvp_show_image', 1 );
	add_option( 'mrvp_show_widgettitle', 1 );
	add_option( 'mrvp_show_spinner', 1 );
	add_option( 'mrvp_show_price', 0 );
	add_option( 'mrvp_show_excerpt', 0 );

	register_setting( 'mrvp_options_group', 'mrvp_number_of_products', [
		'type' => 'integer',
		'sanitize_callback' => 'absint',
		'default' => 5,
	]);

	register_setting( 'mrvp_options_group', 'mrvp_widget_title', [
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => 'Recently Viewed',
	]);

	register_setting( 'mrvp_options_group', 'mrvp_show_image', [
		'type' => 'boolean',
		'sanitize_callback' => 'absint',
		'default' => 1,
	]);
	
	register_setting( 'mrvp_options_group', 'mrvp_show_widgettitle', [
		'type' => 'boolean',
		'sanitize_callback' => 'absint',
		'default' => 1,
	]);

	register_setting( 'mrvp_options_group', 'mrvp_show_spinner', [
		'type' => 'boolean',
		'sanitize_callback' => 'absint',
		'default' => 1,
	]);
	
	register_setting( 'mrvp_options_group', 'mrvp_show_price', [
		'type' => 'boolean',
		'sanitize_callback' => 'absint',
		'default' => 0,
	]);
	
	register_setting( 'mrvp_options_group', 'mrvp_show_excerpt', [
		'type' => 'boolean',
		'sanitize_callback' => 'absint',
		'default' => 0,
	]);
}
add_action( 'admin_init', 'mrvp_register_settings' );

function mrvp_settings_page() {
	?>
	<div class="wrap">
		<h2>MATE Recently Viewed Products – Cache Compatible for WooCommerce</h2>
		
		<h2><?php esc_html_e( 'Global Settings', 'mate-recently-viewed-products' ); ?></h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'mrvp_options_group' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="mrvp_widget_title">Widget Title</label></th>
					<td><input type="text" id="mrvp_widget_title" name="mrvp_widget_title" value="<?php echo esc_attr( get_option( 'mrvp_widget_title', 'Recently Viewed' ) ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="mrvp_number_of_products">Number of Products</label></th>
					<td><input type="number" id="mrvp_number_of_products" name="mrvp_number_of_products" value="<?php echo esc_attr( get_option( 'mrvp_number_of_products', 5 ) ); ?>" min="1" max="20" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="mrvp_layout">Layout</label></th>
					<td>
						<label>
							<input type="checkbox" name="mrvp_show_image" value="1" <?php checked( get_option( 'mrvp_show_image', 1 ) ); ?> />
							Display the product image
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">Show Widget Title</th>
					<td>
						<label>
							<input type="checkbox" name="mrvp_show_widgettitle" value="1" <?php checked( get_option( 'mrvp_show_widgettitle', 1 ) ); ?> />
							Display the widget section title (h4)
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">Show Spinner</th>
					<td>
						<label>
							<input type="checkbox" name="mrvp_show_spinner" value="1" <?php checked( get_option( 'mrvp_show_spinner', 1 ) ); ?> />
							Display loading spinner while products load
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">Show Price</th>
					<td>
						<label>
							<input type="checkbox" name="mrvp_show_price" value="1" <?php checked( get_option( 'mrvp_show_price', 0 ) ); ?> />
							Display product price
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">Show Excerpt</th>
					<td>
						<label>
							<input type="checkbox" name="mrvp_show_excerpt" value="1" <?php checked( get_option( 'mrvp_show_excerpt', 0 ) ); ?> />
							Display product short description (excerpt)
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	
	
	<?php
	$options = get_option( 'mrvp_options_group', [] );
	
	$count            = isset( $options['mrvp_number_of_products'] ) ? absint( $options['mrvp_number_of_products'] ) : 5;
	$title            = isset( $options['mrvp_widget_title'] ) ? sanitize_text_field( $options['mrvp_widget_title'] ) : 'Recently Viewed';
	$show_price       = ! empty( $options['mrvp_show_price'] ) ? 1 : 0;
	$show_excerpt     = ! empty( $options['mrvp_show_excerpt'] ) ? 1 : 0;
	$show_image       = ! empty( $options['mrvp_show_image'] ) ? 1 : 0;
	$show_spinner     = ! empty( $options['mrvp_show_spinner'] ) ? 1 : 0;
	$show_widgettitle = ! empty( $options['mrvp_show_widgettitle'] ) ? 1 : 0;
	
	$shortcode = sprintf(
		'[mrvp_recent_products count="%d" title="%s" show_price="%d" show_excerpt="%d" show_image="%d" show_spinner="%d" show_widgettitle="%d"]',
		$count,
		esc_attr( $title ),
		$show_price,
		$show_excerpt,
		$show_image,
		$show_spinner,
		$show_widgettitle
	);
	?>
	
	<hr>
	<h2><?php esc_html_e( 'Generated Shortcode', 'mate-recently-viewed-products' ); ?></h2>
	<p><?php esc_html_e( 'You can use this shortcode anywhere on your site to display the widget with your current settings:', 'mate-recently-viewed-products' ); ?></p>
	<input type="text"  size="120" class="mrvp-shortcode-preview" readonly value="<?php echo esc_attr( $shortcode ); ?>" onclick="this.select();" />
	
	<style>
		input[readonly].mrvp-shortcode-preview {
			font-family: monospace;
			background-color: #FFF;
		}
	</style>
	
	<hr>
	<h2><?php esc_html_e( 'Usage Instructions', 'mate-recently-viewed-products' ); ?></h2>

<div style="background: #f8f8f8; border: 1px solid #ccc; padding: 15px; margin-top: 20px;">
	<h3><?php esc_html_e( 'How to Use This Plugin', 'mate-recently-viewed-products' ); ?></h3>
		<h4><?php esc_html_e( 'Option 1: Use the Shortcode', 'mate-recently-viewed-products' ); ?></h4>
		<ul style="list-style: disc; padding-left: 20px;">
			<li><?php esc_html_e( 'Copy the shortcode shown above.', 'mate-recently-viewed-products' ); ?></li>
			<li><?php esc_html_e( 'Paste it into any post, page, sidebar widget, or custom HTML block.', 'mate-recently-viewed-products' ); ?></li>
			<li><?php esc_html_e( 'To use it in a PHP template, insert:', 'mate-recently-viewed-products' ); ?>
				<code>&lt;?php echo do_shortcode( '[mrvp_recent_products ...]' ); ?&gt;</code>
			</li>
		</ul>
	
		<h4><?php esc_html_e( 'Option 2: Use the Gutenberg Block', 'mate-recently-viewed-products' ); ?></h4>
		<ul style="list-style: disc; padding-left: 20px;">
			<li><?php esc_html_e( 'In the WordPress editor, click the "+" button to add a new block.', 'mate-recently-viewed-products' ); ?></li>
			<li><?php esc_html_e( 'Search for "MATE Recently Viewed Products".', 'mate-recently-viewed-products' ); ?></li>
			<li><?php esc_html_e( 'Configure block options such as title, product count, and display settings directly in the sidebar.', 'mate-recently-viewed-products' ); ?></li>
		</ul>
	
		<p><strong><?php esc_html_e( 'Note:', 'mate-recently-viewed-products' ); ?></strong> <?php esc_html_e( 'Each instance of the block or shortcode works independently, and can override the global plugin settings.', 'mate-recently-viewed-products' ); ?></p>
</div>

	<?php
}
add_action( 'admin_menu', function() {
	add_options_page(
		'MATE – Recently Viewed Products', // Page title
		'MATE – Recently Viewed Products', // Menu label
		'manage_options',
		'mrvp',
		'mrvp_settings_page'
	);
});
