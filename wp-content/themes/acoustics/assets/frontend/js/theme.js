/**
 * Acoustics Theme Scripts
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
(function($) {
  var navMenu = {
	    init: function(){
			var $selector = $( '.menu-item-has-children, .page_item_has_children' );
  $selector.append('<span class="item-toggle"><i class="fa fa-angle-down"></i></span>');

			$('.item-toggle').on('click', function(){
				$(this).siblings('.sub-menu, .children').toggleClass('active');
});
		}
    };

var productSlider = {
  init: function(){
			$('.products-grid').each(function( index ) {
				var data = $(this).parents('.section-products').attr('type');
  $(this).owlCarousel({
					loop: true,
				    margin:0,
					lazyLoad: true,
				    responsiveClass:true,
					dots: false,
					navContainer: '.owl-nav-'+data,
					navText: [$('.owl-next-'+data),$('.owl-prev-'+data)],
				    responsive:{
				        0:{
				            items:1,
				            nav:true,
							autoplay:true,
						    autoplayTimeout:2000,
						    autoplayHoverPause:true
},
600: {
  items: 3,
				            nav:true,
							autoplay:true,
						    autoplayTimeout:2000,
						    autoplayHoverPause:true
},
1000: {
  items: 4,
				            nav:true,
				            loop:false
}
				    }
				});
			});
	    }
	};

navMenu.init();
    productSlider.init();
})(window.jQuery );
