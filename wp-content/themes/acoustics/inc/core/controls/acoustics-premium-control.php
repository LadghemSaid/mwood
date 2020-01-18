<?php
/**
 * Radio Image Customizer Control.
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @subpackage  Controls
 * @since       1.0.0
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'WP_Customize_Control' ) && class_exists( 'WP_Customize_Section' ) ) {
  class Acoustics_Customize_Control_Premium extends WP_Customize_Section {

	  /**
	   * Customize settings type.
	   *
	   * @since  1.0.9
	   * @access public
	   * @var    string
	   */
	   public $type = 'premium';

	   /**
         * Button
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $button = '';

        /**
         * Custom pro button URL.
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $link = '';

        /**
         * Add custom parameters to pass to the JS via JSON.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function json() {
            $json = parent::json();
            $json['button'] = $this->button;
            $json['link']  = $this->link;
            return $json;
        }


	   /**
		* Render the control's content.
		*/
		protected function render_template() { ?>
			<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand section-premium" style="min-height: 115px;">
	            <h3 class="accordion-section-title">
	                {{ data.title }}
	                <# if ( data.button && data.link ) { #>
	                <a href="{{ data.link }}" class="button button-secondary alignright" target="_blank">{{ data.button }}</a>
	                <# } #>
	            </h3>
	        </li>
			<?php
		}
	}

}
