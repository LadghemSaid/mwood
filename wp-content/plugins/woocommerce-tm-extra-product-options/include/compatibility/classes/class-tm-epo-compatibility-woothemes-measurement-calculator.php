<?php 
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

final class TM_EPO_COMPATIBILITY_woothemes_measurement_calculator {

	protected static $_instance = NULL;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {

		add_filter( 'wc_epo_get_settings', array( $this, 'wc_epo_get_settings' ), 10, 1 );
		add_action( 'plugins_loaded', array( $this, 'add_compatibility' ) );
		add_action( 'init', array( $this, 'template_redirect' ), 11 );
		add_action( 'template_redirect', array( $this, 'template_redirect' ), 11 );
	}

	public function init() {

	}

	public function add_compatibility() {

		
		if ( !class_exists( 'WC_Measurement_Price_Calculator' ) ) {
			return;
		}

		add_filter( 'tm_epo_settings_headers', array( $this, 'tm_epo_settings_headers' ), 10, 1 );
		add_filter( 'tm_epo_settings_settings', array( $this, 'tm_epo_settings_settings' ), 10, 1 );
		

		add_filter( 'wc_epo_add_cart_item_original_price', array( $this, 'wc_epo_add_cart_item_original_price' ), 10, 2 );
		add_filter( 'wc_epo_option_price_correction', array( $this, 'wc_epo_option_price_correction' ), 10, 2 );
		//add_filter( 'wc_epo_add_cart_item_calculated_price1', array( $this, 'wc_epo_add_cart_item_calculated_price' ), 10, 2 );
		//add_filter( 'wc_epo_add_cart_item_calculated_price2', array( $this, 'wc_epo_add_cart_item_calculated_price' ), 10, 2 );
		//add_filter( 'wc_epo_add_cart_item_calculated_price3', array( $this, 'wc_epo_add_cart_item_calculated_price3' ), 10, 3 );
		add_filter( 'woocommerce_tm_epo_price_on_cart', array( $this, 'woocommerce_tm_epo_price_on_cart' ), 10, 2 );
		
	}

	public function template_redirect(){
		// Disable EPO price filters
		remove_filter( 'woocommerce_get_price_html', array( TM_EPO(), 'get_price_html' ), 10, 2 );
		remove_filter( 'woocommerce_product_get_price', array( TM_EPO(), 'tm_woocommerce_get_price' ), 1, 2 );
	} 



	/** Admin settings **/
	public function tm_epo_settings_headers( $headers = array() ) {
		$headers["bookings"] = __( 'WooCommerce Measurement Calculator', 'woocommerce-tm-extra-product-options' );

		return $headers;
	}

	public function wc_epo_get_settings( $settings = array() ) {
		//if ( class_exists( 'WC_Measurement_Price_Calculator' ) ) {
			$settings["tm_epo_measurement_calculate_mode"] = "no";
		//}

		return $settings;
	}

	/** Admin settings **/
	public function tm_epo_settings_settings( $settings = array() ) {
		$label = __( 'WooCommerce Measurement Calculator', 'woocommerce-tm-extra-product-options' );
		$settings["measurement"] = array(
			array(
				'type'  => 'tm_title',
				'id'    => 'epo_page_options',
				'title' => $label,
			),
			array(
				'title'    => __( 'Multiply options cost by area', 'woocommerce-tm-extra-product-options' ),
				'desc'     => '<span>' . __( 'Enabling this will multiply the options price by the calculated area.', 'woocommerce-tm-extra-product-options' ) . '</span>',
				'id'       => 'tm_epo_measurement_calculate_mode',
				'class'    => 'chosen_select',
				'css'      => 'min-width:300px;',
				'default'  => 'no',
				'type'     => 'select',
				'options'  => array(
					'no'  => __( 'Disable', 'woocommerce-tm-extra-product-options' ),
					'yes' => __( 'Enable', 'woocommerce-tm-extra-product-options' ),
				),
				'desc_tip' => FALSE,
			),
			
			array( 'type' => 'tm_sectionend', 'id' => 'epo_page_options' ),
		);

		return $settings;
	}

	public function woocommerce_tm_epo_price_on_cart( $price = "",  $cart_item = "" ) {

		if ( is_array( $cart_item ) && isset( $cart_item['pricing_item_meta_data'] ) && ! empty( $cart_item['pricing_item_meta_data']['_quantity'] ) ){
			$new_quantity = $cart_item['quantity'] / $cart_item['pricing_item_meta_data']['_quantity'];
			$original_price = $price;
			$original_price = $original_price * $new_quantity;

			$price = $original_price;
		}

		return $price;

	}

	public function wc_epo_option_price_correction( $price = "", $cart_item = "" ) {
		
		if (isset( TM_EPO()->tm_epo_measurement_calculate_mode ) && TM_EPO()->tm_epo_measurement_calculate_mode == 'yes'){

			if ( is_array( $cart_item ) && isset( $cart_item['pricing_item_meta_data'] ) && ! empty( $cart_item['pricing_item_meta_data']['_measurement_needed'] ) ){
				$price = $price * floatval( $cart_item['pricing_item_meta_data']['_measurement_needed'] );
			}

		}

		return $price;
	}

	/*public function wc_epo_add_cart_item_calculated_price3( $price = "", $price2 = 0, $cart_item = "" ) {

		if ( is_array( $cart_item ) && isset( $cart_item['pricing_item_meta_data'] ) && ! empty( $cart_item['pricing_item_meta_data']['_quantity'] ) ){
			$new_quantity = $cart_item['quantity'] / $cart_item['pricing_item_meta_data']['_quantity'];
			var_dump_pre($new_quantity);
			if ( $new_quantity ){
				$original_price = $price - $price2;
				$original_price = $original_price / $new_quantity;

				$price = $original_price + $price2;			
			}
		}

		return $price;

	}

	public function wc_epo_add_cart_item_calculated_price( $price = "", $cart_item = "" ) {

		if ( class_exists('WC_Price_Calculator_Settings') && class_exists('WC_Price_Calculator_Product') && class_exists('SV_WC_Product_Compatibility') ){
		
			$product  = isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] ? wc_get_product( $cart_item['product_id'] ) : $cart_item['data'];
			$settings = new WC_Price_Calculator_Settings( $product );

			if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) && ! WC_Price_Calculator_Product::pricing_calculator_inventory_enabled( $product ) ) {

				// pricing inventory management *not* enabled so the item price = item unit price (ie 1 item 10 ft long at $1/foot, the price is $10)
				$cart_item['data']->set_price( (float) $cart_item['pricing_item_meta_data']['_price'] );

			} elseif ( WC_Price_Calculator_Product::pricing_calculator_inventory_enabled( $product ) ) {

				if ( $settings->pricing_rules_enabled() ) {
					// a calculated inventory product with pricing rules enabled will have no configured price, so set it based on the measurement
					$measurement = new WC_Price_Calculator_Measurement( $cart_item['pricing_item_meta_data']['_measurement_needed_unit'], $cart_item['pricing_item_meta_data']['_measurement_needed'] );
					//var_dump_pre($settings->get_pricing_rules_price( $measurement ));
					$cart_item['data']->set_price( $settings->get_pricing_rules_price( $measurement ) );
				}else{
					//var_dump('e');
					//var_dump($price);
					//$price = $price / ( $cart_item['quantity'] / $cart_item['pricing_item_meta_data']['_quantity'] );
					//$cart_item['data']->set_price( $price );
				}

				// is there a minimum price to use?
				$min_price = SV_WC_Product_Compatibility::get_meta( $product, '_wc_measurement_price_calculator_min_price', true );

				if ( is_numeric( $min_price ) && $min_price > $cart_item['data']->get_price() * ( $cart_item['quantity'] / $cart_item['pricing_item_meta_data']['_quantity'] ) ) {

					$cart_item['data']->set_price( $min_price / ( $cart_item['quantity'] / $cart_item['pricing_item_meta_data']['_quantity'] ) );
				}
			}

		}

		return $price;

	}*/

	public function wc_epo_add_cart_item_original_price( $price = "", $cart_item = "" ) {
		if ( isset( $cart_item['pricing_item_meta_data'] ) && isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
			$price = $cart_item['pricing_item_meta_data']['_price'];
		}

		return $price;
	}

	public function cart_item_price( $item_price = "", $cart_item = "", $cart_item_key = "" ) {

		if (
			!empty( $cart_item['tmcartepo'] ) &&
			isset( $cart_item['tm_epo_product_price_with_options'] ) &&
			isset( $cart_item['pricing_item_meta_data'] ) &&
			!empty( $cart_item['pricing_item_meta_data']['_quantity'] ) &&
			!empty( $cart_item['quantity'] )
		) {
			$item_price = wc_price( (float) $cart_item['data']->get_price() * floatval( $cart_item['quantity'] ) );
		}

		return $item_price;
	}

}


