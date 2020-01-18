<?php
/**
 * WooCommerce
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 * @see  acoustics_woocommerce_header_cart()
 */
if ( function_exists( 'acoustics_woocommerce_header_cart' ) ):
	add_action( 'acoustics_navigation', 'acoustics_woocommerce_header_cart', 10 );
endif;
