(function($){
"use strict";

    $(document).on( 'click', '.single_add_to_cart_button:not(.disabled)', function (e) {
        e.preventDefault();

        var $this = $(this),
            $form           = $this.closest('form.cart'),
            product_qty     = $form.find('input[name=quantity]').val() || 1,
            product_id      = $form.find('input[name=product_id]').val() || $this.val(),
            variation_id    = $form.find('input[name=variation_id]').val() || 0;

        var data = {
            action: 'wl_singleproduct_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
        };

        $( document.body ).trigger('adding_to_cart', [$this, data]);

        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,

            beforeSend: function (response) {
                $this.removeClass('added').addClass('loading');
            },

            complete: function (response) {
                $this.addClass('added').removeClass('loading');
            },

            success: function (response) {
                if ( response.error & response.product_url ) {
                    window.location = response.product_url;
                    return;
                } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $this]);
                }
            },

        });

        return false;
    });

})(jQuery);