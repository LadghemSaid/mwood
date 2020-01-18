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

if ( class_exists( 'WP_Customize_Control' ) ) {
	class Acoustics_Customize_Control_Information extends WP_Customize_Control {
		public function render_content() { ?>
			<div class="customizer-information">
				<?php if ( ! empty( $this->label ) && isset( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) && isset( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
			</div>
		<?php
	  }
	}
}
