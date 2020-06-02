<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EmbeddingViaMenu {
	const SEARCH_PLACEHOLDER = 'dgwt_wcas_search_box';

	public function init() {

		if ( is_admin() ) {
			add_action( 'admin_head-nav-menus.php', array( $this, 'addNavMenuMetaBoxes' ) );

			add_action( 'admin_head', array( $this, 'navMenuStyle' ) );
			add_action( 'admin_footer', array( $this, 'navMenuScripts' ) );

		} else {

			add_filter( 'walker_nav_menu_start_el', array( $this, 'processMenuItem' ), 50, 2 );
			add_filter( 'megamenu_walker_nav_menu_start_el', array( $this, 'processMenuItem' ), 50, 2 );

		}
	}

	/**
	 * Check if nav-menus screen is active
	 *
	 * @return bool
	 */
	private function isNavMenuScreen() {
		$isNav  = false;
		$screen = get_current_screen();

		if ( ! empty( $screen->id ) && ( $screen->id === 'nav-menus' ) ) {
			$isNav = true;
		}

		return $isNav;
	}

	/**
	 * Add custom nav meta box.
	 *
	 * Adapted from http://www.johnmorrisonline.com/how-to-add-a-fully-functional-custom-meta-box-to-wordpress-navigation-menus/.
	 *
	 * @return void
	 */
	public function addNavMenuMetaBoxes() {
		add_meta_box( 'dgwt_wcas_endpoints_nav_link', __( 'AJAX Search bar', 'ajax-search-for-woocommerce' ), array( $this, 'navMenuLinks' ), 'nav-menus', 'side',
			'low' );
	}

	/**
	 * Modifies the menu item display on frontend.
	 *
	 * @param string $itemOutput
	 *
	 * @return string
	 */
	public function processMenuItem( $itemOutput ) {

		if (
			! empty( $itemOutput )
			&& is_string( $itemOutput )
			&& strpos( $itemOutput, self::SEARCH_PLACEHOLDER ) !== false
		) {
			$itemOutput = do_shortcode( '[wcas-search-form]' );
		}

		return $itemOutput;
	}

	/**
	 * Output menu links.
	 *
	 * @return void
	 */
	public function navMenuLinks() {
		?>
		<div id="posttype-dgwt-wcas-endpoints" class="posttypediv">
			<p><?php _e( 'Add AJAX search bar as a menu item.', 'ajax-search-for-woocommerce' ) ?></p>
			<div id="tabs-panel-dgwt-wcas-endpoints" class="tabs-panel tabs-panel-active">
				<ul id="dgwt-wcas-endpoints-checklist" class="categorychecklist form-no-clear">
					<li>
						<label class="menu-item-title">
							<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]"
							       value="-1"/> <?php echo __( 'AJAX Search bar', 'ajax-search-for-woocommerce' ); ?>
						</label>
						<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom"/>
						<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="<?php echo self::SEARCH_PLACEHOLDER; ?>"/>
						<input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]"/>
					</li>
				</ul>
			</div>
			<p class="button-controls">
                <span class="add-to-menu">
					<button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'woocommerce' ); ?>"
					        name="add-post-type-menu-item" id="submit-posttype-dgwt-wcas-endpoints"><?php esc_html_e( 'Add to menu', 'woocommerce' ); ?></button>
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}

	public function getDescription() {
		$html = '<div class="dgwt-wcas-admin-menu-item-desc js-dgwt-wcas-admin-menu-item-desc">';
		$html .= '<img class="" src="' . DGWT_WCAS_URL . 'assets/img/logo-for-review.jpg" width="32" height="32" />';
		$html .= '<span>' . __( 'AJAX search bar will be displayed here.', 'ajax-search-for-woocommerce' ) . '</span>';
		$html .= '</div>';

		return $html;
	}

	public function navMenuStyle() {

		if ( ! $this->isNavMenuScreen() ) {
			return;
		}

		?>
		<style>
			.dgwt-wcas-admin-menu-item-desc {
				display: flex;
				flex-direction: row;
				justify-content: left;
				align-items: center;
			}

			.dgwt-wcas-admin-menu-item-desc img {
				display: block;
				margin-right: 15px;
				border-radius: 4px;
			}
		</style>

		<?php

	}

	public function navMenuScripts() {

		if ( ! $this->isNavMenuScreen() ) {
			return;
		}

		?>
		<script>
			(function ($) {

				function replaceLabels($menuItem) {

					var $menuItems = $('#menu-to-edit .menu-item-title');

					if ($menuItems.length > 0) {

						$menuItems.each(function () {
							if ($(this).text() === '<?php echo self::SEARCH_PLACEHOLDER; ?>') {

								var $menuItem = $(this).closest('.menu-item');

								$menuItem.find('.menu-item-title').text('AJAX Search bar');
								$menuItem.find('.item-type').text('<?php _e( 'Search bar', 'ajax-search-for-woocommerce' ); ?>');
								$menuItem.find('.menu-item-settings .edit-menu-item-title').closest('label').hide();
								$menuItem.find('.field-url').hide();


								if ($menuItem.find('.js-dgwt-wcas-admin-menu-item-desc').length == 0) {
									$menuItem.find('.menu-item-settings').prepend('<?php echo $this->getDescription(); ?>');
								}
							}
						});
					}
				}

				$(document).ready(function () {

					replaceLabels();

				});

				$(document).ajaxComplete(function (event, request, settings) {

					if (
						typeof settings != 'undefined'
						&& typeof settings.data == 'string'
						&& settings.data.indexOf('action=add-menu-item') !== -1
						&& settings.data.indexOf('dgwt_wcas_search_box') !== -1
					) {
						replaceLabels();

						setTimeout(function () {
							replaceLabels();
						}, 500)

					}

				});

			}(jQuery));
		</script>

		<?php

	}

}
