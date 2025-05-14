<?php
add_action( 'wp_footer', function() {
	if ( is_product() ) {
		echo '<script>document.body.dataset.productId = "' . esc_attr( get_the_ID() ) . '";</script>';
	}
	echo '<style>
	.rvpa-wrapper h4 {
		margin-bottom: 10px;
		font-size: 18px;
	}
	.rvpa-product-list {
		list-style: none;
		padding: 0;
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
	}
	.rvpa-product-list li {
		text-align: center;
		width: 120px;
	}
	.rvpa-product-list img {
		width: 100%;
		height: auto;
		border-radius: 4px;
	}
	.rvpa-product-link {
		text-decoration: none;
		color: inherit;
		display: block;
	}
	.rvpa-loading {
		text-align: center;
		padding: 10px;
	}
	.rvpa-spinner {
		width: 24px;
		height: 24px;
		border: 3px solid #ccc;
		border-top: 3px solid #000;
		border-radius: 50%;
		animation: spin 0.8s linear infinite;
		margin: auto;
	}
	@keyframes spin {
		from { transform: rotate(0deg); }
		to { transform: rotate(360deg); }
	}
	</style>';
});
