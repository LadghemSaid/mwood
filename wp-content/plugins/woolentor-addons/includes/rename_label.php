<?php
/*
* Shop Page
*/

// Add to Cart Button Text
add_filter( 'woocommerce_product_add_to_cart_text', 'woolentor_custom_add_cart_button_shop_page', 99, 2 );
function woolentor_custom_add_cart_button_shop_page( $label ) {
   return __( woolentor_get_option_label_text( 'wl_shop_add_to_cart_txt', 'woolentor_rename_label_tabs', 'Add to Cart' ), 'woolentor' );
}

/*
* Product Details Page
*/

// Add to Cart Button Text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woolentor_custom_add_cart_button_single_product' );
function woolentor_custom_add_cart_button_single_product( $label ) {
   return __( woolentor_get_option_label_text( 'wl_add_to_cart_txt', 'woolentor_rename_label_tabs', 'Add to Cart' ), 'woolentor-pro' );
}

// Translate
add_filter( 'gettext', 'woolentor_translate_text', 20, 3 );
function woolentor_translate_text( $translated, $untranslated, $domain ) {
    $wltext = '';

    // Checkout Page
    if( is_checkout() ){
        switch ( $untranslated ) {

            case 'Billing details':
                $wltext = woolentor_get_option_label_text( 'wl_checkout_billig_form_title', 'woolentor_rename_label_tabs', 'Billing details' );
                $translated = $wltext;
                break;
                
        }
    }

    return $translated;
}