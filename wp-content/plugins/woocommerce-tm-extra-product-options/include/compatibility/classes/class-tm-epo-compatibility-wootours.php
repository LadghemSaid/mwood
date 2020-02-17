<?php
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

final class TM_EPO_COMPATIBILITY_wootours {

	protected static $_instance = NULL;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {

		add_action( 'init', array( $this, 'add_compatibility' ) );

	}

	public function init() {
	}

	public function wp_enqueue_scripts() {
		if (
			((class_exists( 'WC_Quick_View' ) || TM_EPO()->is_supported_quick_view()) && (is_shop() || is_product_category() || is_product_tag()))
			|| TM_EPO()->is_enabled_shortcodes()
			|| is_product()
			|| is_cart()
			|| is_checkout()
			|| is_order_received_page()
			|| (TM_EPO()->tm_epo_enable_in_shop == "yes" && (is_shop() || is_product_category() || is_product_tag()))
		) {
			wp_enqueue_script( 'tc-comp-wootours', TM_EPO_PLUGIN_URL . '/include/compatibility/assets/js/wootours.js', array( 'jquery' ), TM_EPO()->version, TRUE );
		}
	}

	public function add_compatibility() {

		/** WooTours support **/
		if ( !class_exists( 'EX_WooTour' ) ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 4 );

	}

}


