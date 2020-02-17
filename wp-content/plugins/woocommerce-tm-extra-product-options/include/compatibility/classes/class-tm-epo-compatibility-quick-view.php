<?php
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

final class TM_EPO_COMPATIBILITY_quick_view {

	protected static $_instance = NULL;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {

		add_action( 'wc_epo_add_compatibility', array( $this, 'add_compatibility' ) );

	}

	public function init() {

	}

	public function add_compatibility() {

		add_filter( 'woocommerce_tm_quick_view', array( $this, 'woocommerce_tm_quick_view' ), 10, 3 );
		add_filter( 'wc_epo_get_quickview_array', array( $this, 'get_epo_quickview_array' ) );
		add_filter( 'wc_epo_get_quickview_containers', array( $this, 'wc_epo_get_quickview_containers' ) );
	
	}

	public function wc_epo_get_quickview_containers() {

		$quickview_array = $this->get_epo_quickview_array();
		$qv = array();

		foreach ($quickview_array as $key => $value) {
			$qv[ $key ] = $value ['container'];
		}

		return $qv;

	}

	public function get_epo_quickview_array() {

		$quickview_array = array(
			'woothemes_quick_view' 			=> array( 'container' => '.woocommerce.quick-view', 														'is' => (isset( $_GET['wc-api'] ) && $_GET['wc-api'] == 'WC_Quick_View') ),
			'theme_flatsome_quick_view'		=> array( 'container' => '.product-lightbox', 																'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'jck_quickview' || $_POST['action'] == 'ux_quickview' || $_POST['action'] == 'flatsome_quickview')) ),
			'theme_kleo_quick_view'			=> array( 'container' => '#productModal', 																	'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'woo_quickview')) ),
			'yith_quick_view' 				=> array( 'container' => '#yith-quick-view-modal,.yith-quick-view.yith-modal,.yith-quick-view.yith-inline', 'is' => ( (isset( $_POST['action'] ) && ($_POST['action'] == 'yith_load_product_quick_view')) || (isset( $_GET['action'] ) && ($_GET['action'] == 'yith_load_product_quick_view')) ) ),
			'venedor_quick_view' 			=> array( 'container' => '.quickview-wrap', 																'is' => (isset( $_GET['action'] ) && ($_GET['action'] == 'venedor_product_quickview')) ),
			'rubbez_quick_view' 			=> array( 'container' => '#quickview-content', 																'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'product_quickview')) ),
			'jckqv_quick_view' 				=> array( 'container' => '#jckqv', 																			'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'jckqv')) ),// http://codecanyon.net/item/woocommerce-quickview/4378284
			'themify_quick_view' 			=> array( 'container' => '#product_single_wrapper', 														'is' => (isset( $_GET['ajax'] ) && $_GET['ajax'] == 'true') ),
			'porto_quick_view' 				=> array( 'container' => '.quickview-wrap', 																'is' => (isset( $_GET['action'] ) && ($_GET['action'] == 'porto_product_quickview')) ),
			'woocommerce_product_layouts' 	=> array( 'container' => '.dhvc-woo-product-quickview', 													'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'dhvc_woo_product_quickview')) ),// http://codecanyon.net/item/woocommerce-products-layouts/7384574?
			'nm_getproduct' 				=> array( 'container' => '#popup', 																			'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'nm_getproduct')) ),// Woo Product Quick View http://codecanyon.net/item/woocommerce-product-quick-view/11293528?
			'lightboxpro' 					=> array( 'container' => '.wpb_wl_quick_view_content', 														'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'wpb_wl_quickview')) ),// WooCommerce LightBox PRO http://wpbean.com/
			'woodmart_quick_view' 			=> array( 'container' => '.product-quick-view', 															'is' => (isset( $_GET['action'] ) && ($_GET['action'] == 'woodmart_quick_view')) ),// woodmart theme
			'thegem_product_quick_view'		=> array( 'container' => '.woo-modal-product', 																'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'thegem_product_quick_view')) ),// the gem theme
			'wooqv_quick_view' 				=> array( 'container' => '.woo-quick-view', 																'is' => (isset( $_POST['action'] ) && ($_POST['action'] == 'wooqv_quick_view')) ),// WooCommerce Interactive Product Quick View
		);

		return apply_filters( 'wc_epo_quickview_array', $quickview_array );

	}

	public function woocommerce_tm_quick_view( $qv ) {

		$quickview_array = $this->get_epo_quickview_array();

		foreach ($quickview_array as $key => $value) {
			if ( ! empty( $value['is'] ) ){
				$qv = TRUE;
			}
		}

		return apply_filters( 'wc_epo_is_quickview', $qv );

	}


}


