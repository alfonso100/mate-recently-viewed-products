<?php
function mrvp_register_settings() {
	add_option( 'mrvp_number_of_products', 5 );
	add_option( 'mrvp_widget_title', 'Recently Viewed' );
	add_option( 'mrvp_layout', 'thumbs_titles' );
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

	register_setting( 'mrvp_options_group', 'mrvp_layout', [
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => 'thumbs_titles',
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
						<select name="mrvp_layout" id="mrvp_layout">
							<option value="titles_only" <?php selected( get_option( 'mrvp_layout' ), 'titles_only' ); ?>>Titles only</option>
							<option value="thumbs_titles" <?php selected( get_option( 'mrvp_layout' ), 'thumbs_titles' ); ?>>Thumbnail and Titles</option>
						</select>
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
