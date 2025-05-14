<?php
function rvpa_register_settings() {
	add_option( 'rvpa_number_of_products', 5 );
	register_setting( 'rvpa_options_group', 'rvpa_number_of_products', [
		'type' => 'integer',
		'sanitize_callback' => 'absint',
		'default' => 5,
	]);
}
add_action( 'admin_init', 'rvpa_register_settings' );

function rvpa_settings_page() {
	?>
	<div class="wrap">
		<h2>Recently Viewed Products Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'rvpa_options_group' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="rvpa_number_of_products">Number of Products</label></th>
					<td><input type="number" id="rvpa_number_of_products" name="rvpa_number_of_products" value="<?php echo esc_attr( get_option( 'rvpa_number_of_products', 5 ) ); ?>" min="1" max="20" /></td>
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
