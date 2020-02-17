(function ($) {
    "use strict";

    function tc_adjust_product_total_price( product_total_price, total_plus_fee, extra_fee, total, cart_fee_options_total, $totals_holder ) {

        //WooTour - Exthemes
        if($('[name=wt_number_adult]').length){
            var adult = parseFloat ( $('[name=wt_number_adult]').val() ),
                child = parseFloat ( $('[name=wt_number_child]').length ? $('[name=wt_number_child]').val() : 0 ),
                infant = parseFloat ( $('[name=wt_number_infant]').length ? $('[name=wt_number_infant]').val() :0 ),
                ct1 = parseFloat( $('[name=wt_number_ct1]').length ? $('[name=wt_number_ct1]').val() :0 ),
                ct2 = parseFloat( $('[name=wt_number_ct1]').length ? $('[name=wt_number_ct1]').val() :0 );
            if($('.product-type-variable').length){
                var price_child = $('.woocommerce-variation-wt-child-price span > .amount').text().replace( /^\D+/g, ''),
                    price_infant = $('.woocommerce-variation-wt-infant-price span > .amount').text().replace( /^\D+/g, ''),
                    price_ct1 = $('.woocommerce-variation-wt-ct1-price span > .amount').text().replace( /^\D+/g, ''),
                    price_ct2 = $('.woocommerce-variation-wt-ct2-price span > .amount').text().replace( /^\D+/g, '');
            }else{
                var price_child = $('._child_select > span > span.woocommerce-Price-amount.amount').text().replace( /^\D+/g, ''),
                    price_infant = $('._infant_select > span > span.woocommerce-Price-amount.amount').text().replace( /^\D+/g, ''),
                    price_ct1 = $('._ct1_select span > span.woocommerce-Price-amount.amount').text().replace( /^\D+/g, ''),
                    price_ct2 = $('._ct2_select span > span.woocommerce-Price-amount.amount').text().replace( /^\D+/g, '');
            }
            var woo_product_total_price = parseFloat(product_total_price*adult + parseFloat(price_child*child) + parseFloat(price_infant*infant) + parseFloat(price_ct1*ct1) + parseFloat(price_ct2*ct2) );
            if (!isNaN(woo_product_total_price)){
                product_total_price = woo_product_total_price;
            }
        }
        // End support WooTour        
        return product_total_price;

    }

    $(document).ready(function () {
        
        $.tc_add_filter("tc_adjust_product_total_price", tc_adjust_product_total_price, 10, 6);

    });
})(jQuery);