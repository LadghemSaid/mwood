<?php

namespace DgoraWcas\Integrations\Plugins\WooCommerceProductFilters;

use DgoraWcas\Helpers;

/**
 * Integration with Product Filters for WooCommerce
 *
 * Plugin URL: https://woocommerce.com/products/product-filters/
 * Author: Automattic, developed by Nexter
 */
class WooCommerceProductFilters {
	public function init() {
		if ( ! defined( 'WC_PRODUCT_FILTER_VERSION' ) ) {
			return;
		}
		if ( version_compare( WC_PRODUCT_FILTER_VERSION, '1.1.16' ) < 0 ) {
			return;
		}
		if ( ! Helpers::isProductSearchPage() ) {
			return;
		}

		add_filter( 'wcpf_product_counts_search_sql', array( $this, 'wcpf_product_counts_search_sql' ) );
		add_filter( 'woocommerce_price_filter_sql', array( $this, 'woocommerce_price_filter_sql' ), 10, 3 );
	}

	/**
	 * Narrowing the list of products to determine the number assigned to terms, to those returned by our search engine
	 *
	 * @param string $sql
	 *
	 * @return string
	 */
	public function wcpf_product_counts_search_sql( $sql ) {
		global $wpdb;

		$post_ids = apply_filters( 'dgwt/wcas/search_page/result_post_ids', array() );

		if ( $post_ids ) {
			$sql = " AND $wpdb->posts.ID IN(" . implode( ',', $post_ids ) . ")";
		}

		return $sql;
	}

	/**
	 * Narrowing the list of products for determining edge prices to those returned by our search engine
	 *
	 * @param string $sql
	 *
	 * @return string
	 */
	public function woocommerce_price_filter_sql( $sql, $meta_query_sql, $tax_query_sql ) {
		global $wpdb;

		if ( ! Helpers::is_running_inside_class( 'WooCommerce_Product_Filter_Plugin\Field\Price_Slider\Filter_Component' ) ) {
			return $sql;
		}

		$post_ids = apply_filters( 'dgwt/wcas/search_page/result_post_ids', array() );

		if ( $post_ids ) {
			$sql .= " AND $wpdb->posts.ID IN(" . implode( ',', $post_ids ) . ")";
		}

		return $sql;
	}
}
