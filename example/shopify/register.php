<?php declare(strict_types = 1);

namespace RemoteDataBlocks\Example\Shopify;

use RemoteDataBlocks\Editor\BlockManagement\ConfigRegistry;
use RemoteDataBlocks\Integrations\Shopify\Queries\ShopifyGetProductQuery;
use RemoteDataBlocks\Integrations\Shopify\Queries\ShopifySearchProductsQuery;
use RemoteDataBlocks\Integrations\Shopify\ShopifyDataSource;
use RemoteDataBlocks\Logging\LoggerManager;

require_once __DIR__ . '/inc/interactivity-store/interactivity-store.php';
require_once __DIR__ . '/inc/queries/class-shopify-add-to-cart-mutation.php';
require_once __DIR__ . '/inc/queries/class-shopify-create-cart-mutation.php';
require_once __DIR__ . '/inc/queries/class-shopify-remove-from-cart-mutation.php';

function register_shopify_block() {
	$block_name   = 'Shopify Example';
	$access_token = \RemoteDataBlocks\Example\get_access_token( 'shopify' );
	$store_name   = 'stoph-test';

	if ( empty( $access_token ) ) {
		$logger = LoggerManager::instance();
		$logger->warning( sprintf( '%s is not defined, cannot register %s block', 'EXAMPLE_SHOPIFY_ACCESS_TOKEN', $block_name ) );
		return;
	}

	$shopify_data_source           = ShopifyDataSource::create( $access_token, $store_name );
	$shopify_search_products_query = new ShopifySearchProductsQuery( $shopify_data_source );
	$shopify_get_product_query     = new ShopifyGetProductQuery( $shopify_data_source );

	register_remote_data_block( $block_name, $shopify_get_product_query );
	register_remote_data_search_query( $block_name, $shopify_search_products_query );

	// Registering ad hoc queries and mutations is an unstable, undocumented feature.
	ConfigRegistry::register_query( $block_name, new ShopifyCreateCartMutation( $shopify_data_source ) );
	ConfigRegistry::register_query( $block_name, new ShopifyAddToCartMutation( $shopify_data_source ) );
	ConfigRegistry::register_query( $block_name, new ShopifyRemoveFromCartMutation( $shopify_data_source ) );

	$block_pattern = file_get_contents( REMOTE_DATA_BLOCKS__PLUGIN_DIRECTORY . '/inc/integrations/shopify/Patterns/product-teaser.html' );
	register_remote_data_block_pattern( $block_name, 'Shopify Product Teaser', $block_pattern );

	register_block_type( __DIR__ . '/build/blocks/shopify-cart' );
	register_block_type( __DIR__ . '/build/blocks/shopify-cart-button' );
}
add_action( 'init', __NAMESPACE__ . '\\register_shopify_block' );
