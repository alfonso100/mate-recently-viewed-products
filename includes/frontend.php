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
		margin: 0;
	}
	
	.rvpa-product-list li {
		display: flex;
		align-items: center;
		padding: 10px 0;
		border-top: 1px solid #ccc;
	}
	
	.rvpa-product-list li:first-child {
		border-top: none;
	}
	
	.rvpa-product-link {
		display: flex;
		align-items: center;
		text-decoration: none;
		color: inherit;
		width: 100%;
	}
	
	.rvpa-product-link img {
		width: 60px;
		height: auto;
		border-radius: 4px;
		margin-right: 12px;
		flex-shrink: 0;
	}
	
	.rvpa-product-link span {
		font-size: 16px;
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
