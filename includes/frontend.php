<?php
add_action( 'wp_footer', function() {
	if ( is_product() ) {
		echo '<script>document.body.dataset.productId = "' . esc_attr( get_the_ID() ) . '";</script>';
	}
	echo '<style>
	.mrvp-wrapper h4 {
		margin-bottom: 10px;
		font-size: 18px;
	}
	
	.mrvp-product-list {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	
	
	.mrvp-product-list li {
		display: flex;
		align-items: center;
		padding: 10px 0;
		border-top: 1px solid #ccc;
	}
	
	.mrvp-product-list li:first-child {
		border-top: none;
	}
	
	.mrvp-info {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}
	
	.mrvp-title {
		font-weight: bold;
	}
	.mrvp-product-link {
		display: flex;
		align-items: flex-start; 
		text-decoration: none;
		color: inherit;
		width: 100%;
	}
	
	.mrvp-product-link img {
		width: 60px;
		height: auto;
		border-radius: 4px;
		margin-right: 12px;
		flex-shrink: 0;
	}
	
	.mrvp-product-link span {
		font-size: 16px;
	}
	
	.mrvp-loading {
		padding: 10px;
		text-align: left;
		display: none;
	}
	.mrvp-spinner {
		width: 24px;
		height: 24px;
		border: 3px solid #ccc;
		border-top: 3px solid #000;
		border-radius: 50%;
		animation: spin 0.8s linear infinite;
	}
	@keyframes spin {
		from { transform: rotate(0deg); }
		to { transform: rotate(360deg); }
	}
	</style>';
});
