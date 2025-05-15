<?php
function rvpa_register_settings() {
	add_option( 'rvpa_number_of_products', 5 );
	add_option( 'rvpa_widget_title', 'Recently Viewed' );
	add_option( 'rvpa_layout', 'thumbs_titles' );

	register_setting( 'rvpa_options_group', 'rvpa_number_of_products', [
		'type' => 'integer',
		'sanitize_callback' => 'absint',
		'default' => 5,
	]);

	register_setting( 'rvpa_options_group', 'rvpa_widget_title', [
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => 'Recently Viewed',
	]);

	register_setting( 'rvpa_options_group', 'rvpa_layout', [
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => 'thumbs_titles',
	]);
}
add_action( 'admin_init', 'rvpa_register_settings' );

function rvpa_settings_page() {
	?>
	<div class="wrap">
		<h2>Recently Viewed Products – AJAX & Cache Compatible for WooCommerce</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'rvpa_options_group' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="rvpa_widget_title">Widget Title</label></th>
					<td><input type="text" id="rvpa_widget_title" name="rvpa_widget_title" value="<?php echo esc_attr( get_option( 'rvpa_widget_title', 'Recently Viewed' ) ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="rvpa_number_of_products">Number of Products</label></th>
					<td><input type="number" id="rvpa_number_of_products" name="rvpa_number_of_products" value="<?php echo esc_attr( get_option( 'rvpa_number_of_products', 5 ) ); ?>" min="1" max="20" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="rvpa_layout">Layout</label></th>
					<td>
						<select name="rvpa_layout" id="rvpa_layout">
							<option value="titles_only" <?php selected( get_option( 'rvpa_layout' ), 'titles_only' ); ?>>Titles only</option>
							<option value="thumbs_titles" <?php selected( get_option( 'rvpa_layout' ), 'thumbs_titles' ); ?>>Thumbnail and Titles</option>
						</select>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
add_action( 'admin_menu', function() {
	add_options_page( 'Recently Viewed Products', 'Recently Viewed Products', 'manage_options', 'rvpa', 'rvpa_settings_page' );
});
