<?php

namespace WooLentor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*  Single product Ajax add to cart
*/
class Single_Product_Ajax_Add_To_Cart{

    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    function __construct(){

        // Ajax Callback
        add_action( 'wp_ajax_wl_singleproduct_ajax_add_to_cart', [ $this, 'woocommerce_ajax_add_to_cart' ] );
        add_action( 'wp_ajax_nopriv_wl_singleproduct_ajax_add_to_cart', [ $this, 'woocommerce_ajax_add_to_cart' ] );

    }

    // Ajax callback function
    public function woocommerce_ajax_add_to_cart() {
        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $quantity           = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
        $variation_id       = absint( $_POST['variation_id'] );
        $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
        $product_status     = get_post_status( $product_id );

        if ( $passed_validation && \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && 'publish' === $product_status ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
            \WC_AJAX::get_refreshed_fragments();
        } else {
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
            );
            echo wp_send_json($data);
        }
        wp_die();
    }


}

Single_Product_Ajax_Add_To_Cart::instance();