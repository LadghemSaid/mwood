<?php

namespace DgoraWcas\Integrations\Themes\Enfold;

use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Enfold {

	private $themeSlug = 'enfold';

	private $themeName = 'Enfold';

	public function __construct() {

		add_action( 'init', function () {

			add_action( 'wp_head', array( $this, 'customCSS' ) );
			add_action( 'wp_footer', array( $this, 'customJS' ) );

		} );
	}


	/**
	 * Custom CSS
	 *
	 * @return void
	 */
	public function customCSS() {
		?>
		<style>
			#top .dgwt-wcas-no-submit .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input {
				padding: 10px 15px 10px 40px;
				margin: 0;
			}

			#top.rtl .dgwt-wcas-no-submit .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input {
				padding: 10px 40px 10px 15px
			}
		</style>
		<?php
	}

	/**
	 * Custom JS
	 *
	 * @return void
	 */
	public function customJS() {
		?>
		<script>
			(function ($) {
				function avia_apply_quant_btn() {
					jQuery(".quantity input[type=number]").each(function () {
						var number = $(this),
							max = parseFloat(number.attr('max')),
							min = parseFloat(number.attr('min')),
							step = parseInt(number.attr('step'), 10),
							newNum = jQuery(jQuery('<div />').append(number.clone(true)).html().replace('number', 'text')).insertAfter(number);
						number.remove();

						setTimeout(function () {
							if (newNum.next('.plus').length === 0) {
								var minus = jQuery('<input type="button" value="-" class="minus">').insertBefore(newNum),
									plus = jQuery('<input type="button" value="+" class="plus">').insertAfter(newNum);

								minus.on('click', function () {
									var the_val = parseInt(newNum.val(), 10) - step;
									the_val = the_val < 0 ? 0 : the_val;
									the_val = the_val < min ? min : the_val;
									newNum.val(the_val).trigger("change");
								});
								plus.on('click', function () {
									var the_val = parseInt(newNum.val(), 10) + step;
									the_val = the_val > max ? max : the_val;
									newNum.val(the_val).trigger("change");

								});
							}
						}, 10);

					});
				}

				$(document).ready(function () {

					$(document).on('dgwtWcasDetailsPanelLoaded', function(){
						avia_apply_quant_btn();
					});
				});

			}(jQuery));
		</script>
		<?php
	}

}
