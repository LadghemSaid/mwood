(function ($) {
    "use strict";

    var $onePageNavWrap;
    
	var Ekit_OnePageScroll_Editor = {
		init: function () {
			$onePageNavWrap = elementor.$previewContents.find('#onepage_scroll_nav_wrap');

			elementor.settings.page.addChangeCallback('ekit_onepagescroll', Ekit_OnePageScroll_Editor.updateMarkup);
			elementor.settings.page.addChangeCallback('ekit_onepagescroll_nav', Ekit_OnePageScroll_Editor.updateMarkup);
			elementor.settings.page.addChangeCallback('ekit_onepagescroll_nav_pos', Ekit_OnePageScroll_Editor.updateMarkup);
			elementor.settings.page.addChangeCallback('ekit_onepagescroll_nav_icon', Ekit_OnePageScroll_Editor.updateMarkup);

			Ekit_OnePageScroll_Editor.updateMarkup();
		},

		updateMarkup: function () {
			$.post(ajaxurl, {
				action: 'generate_navigation_markup',
				navStyle: elementorFrontend.getPageSettings('ekit_onepagescroll_nav'),
				navPos: elementorFrontend.getPageSettings('ekit_onepagescroll_nav_pos'),
				navIcon: elementorFrontend.getPageSettings('ekit_onepagescroll_nav_icon')
			}, function (res) {
				if ( res !== '0' ) {
					var $li = $onePageNavWrap.html( res ).find('li'),
						$parent = $li.parent();

					$.each(elementor.elements.models, function (i) {
						var isDot = this.attributes.settings.attributes.ekit_has_onepagescroll_dot;

						if ( isDot && i !== 0 ) {
							$li.clone().appendTo( $parent );
						}
					});
				}
			});
		}
	};

	$(window).on('load', Ekit_OnePageScroll_Editor.init);

}(jQuery));
