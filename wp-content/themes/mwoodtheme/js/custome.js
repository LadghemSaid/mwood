
(function($) {
    console.log('ok');

    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 150) {
            $("#site-header-cart-fixed").addClass("--visible");
            $("#header-fixed").addClass("--visible");
        } else {
            $("#site-header-cart-fixed").removeClass("--visible");
            $("#header-fixed").removeClass("--visible");
        }
    });
})(jQuery);
