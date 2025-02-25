<?php declare(strict_types = 1);

namespace RemoteDataBlocks\Example\Shopify;

use RemoteDataBlocks\Config\QueryContext\GraphqlMutationContext;

class ShopifyAddToCartMutation extends GraphqlMutationContext {
	public function get_input_schema(): array {
		return [
			'cart_id'    => [
				'type' => 'id',
			],
			'variant_id' => [
				'type' => 'id',
			],
			'quantity'   => [
				'type' => 'number',
			],
		];
	}

	public function get_output_schema(): array {
		return [
			'root_path'     => '$.data.cartLinesAdd.cart.lines.edges[*]',
			'is_collection' => true,
			'mappings'      => [
				'id'            => [
					'name' => 'Line ID',
					'path' => '$.node.id',
					'type' => 'id',
				],
				'quantity'      => [
					'name' => 'Quantity',
					'path' => '$.node.quantity',
					'type' => 'string',
				],
				'variant_id'    => [
					'name' => 'Variant ID',
					'path' => '$.node.merchandise.id',
					'type' => 'id',
				],
				'variant_title' => [
					'name' => 'Title',
					'path' => '$.node.merchandise.title',
					'type' => 'string',
				],
			],
		];
	}

	public function get_mutation(): string {
		return '
			mutation AddProductToCart( $cart_id: ID!, $variant_id: ID!, $quantity: Int = 1 ){
				cartLinesAdd(cartId: $cart_id, lines: [{ quantity: $quantity, merchandiseId: $variant_id }]) {
					cart {
						id
						lines(first: 10) {
							edges {
								node {
									id
									quantity
									merchandise {
										... on ProductVariant {
											id
											title
											product {
												title
											}
										}
									}
								}
							}
						}
					}
				}
			}';
	}
}
