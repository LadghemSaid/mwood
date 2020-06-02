<?php

namespace DgoraWcas\Integrations\Plugins\WooCommerceProductsFilter;

/**
 * Integration with WOOF – Products Filter for WooCommerce
 *
 * Plugin URL: https://wordpress.org/plugins/woocommerce-products-filter/
 * Author: realmag777
 */
class WooCommerceProductsFilter {
	public function init() {
		if ( ! defined( 'WOOF_VERSION' ) ) {
			return;
		}
		if ( version_compare( WOOF_VERSION, '1.2.3' ) < 0 ) {
			return;
		}
		if ( ! $this->is_search() ) {
			return;
		}

		add_action( 'pre_get_posts', array( $this, 'search_products' ), 900000 );

		add_filter( 'woof_print_content_before_search_form', array( $this, 'inject_search_filter' ) );

		add_filter( 'woof_get_filtered_price_query', array( $this, 'get_filtered_price_query' ) );

		add_filter( 'woof_get_terms_args', array( $this, 'get_terms_args' ) );
	}

	/**
	 * Set search query var if our custom search param is present
	 *
	 * @param \WP_Query $query
	 */
	public function search_products( $query ) {
		$dgwt_wcas_s = isset( $_GET['dgwt_wcas_s'] ) && ! empty( trim( $_GET['dgwt_wcas_s'] ) ) ? trim( $_GET['dgwt_wcas_s'] ) : '';

		if ( ! empty( $dgwt_wcas_s ) ) {
			$query->set( 's', $dgwt_wcas_s );
			$query->is_search = true;
		}
	}

	/**
	 * Inject our custom search param to object with plugin's filters
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function inject_search_filter( $content = '' ) {
		ob_start();
		?>
		<script>
			function dgwt_wcas_s_init() {
				woof_current_values.dgwt_wcas_s = '<?php echo esc_js( get_search_query() ) ?>';
			}

			if (document.readyState != 'loading') {
				dgwt_wcas_s_init();
			} else {
				document.addEventListener('DOMContentLoaded', dgwt_wcas_s_init);
			}
		</script>
		<?php
		$content .= ob_get_clean();

		return $content;
	}

	/**
	 * Passing our search results to plugin's price filter
	 *
	 * The plugin will use our products IDs to determine the values in the displayed filters.
	 *
	 * @param string $sql
	 *
	 * @return string
	 */
	public function get_filtered_price_query( $sql ) {
		global $wpdb;

		$post_ids = apply_filters( 'dgwt/wcas/search_page/result_post_ids', array() );

		if ( $post_ids ) {
			$sql .= " AND $wpdb->posts.ID IN(" . implode( ',', $post_ids ) . ")";
		}

		return $sql;
	}

	/**
	 * Passing our search results to plugin's terms filters
	 *
	 * The plugin will use our products IDs to determine terms in the displayed filters.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_terms_args( $args ) {
		$post_ids = apply_filters( 'dgwt/wcas/search_page/result_post_ids', array() );

		if ( $post_ids ) {
			$args['object_ids'] = $post_ids;
		}

		return $args;
	}

	/**
	 * Check if it's search page
	 *
	 * @return bool
	 */
	private function is_search() {
		if (
			isset( $_GET['dgwt_wcas'] ) && $_GET['dgwt_wcas'] === '1' &&
			isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' &&
			(
				( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) ||
				( isset( $_GET['dgwt_wcas_s'] ) && ! empty( $_GET['dgwt_wcas_s'] ) )
			)
		) {
			return true;
		}

		return false;
	}
}
