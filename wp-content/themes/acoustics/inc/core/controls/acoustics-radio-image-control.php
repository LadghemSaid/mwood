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
  class Acoustics_Customize_Control_Radio_Image extends WP_Customize_Control {

        public $type = 'radio-image';
		/**
		 * Render the control's content.
		 */
		public function render_content() {
            if ( empty( $this->choices ) ) {
				return;
            }
            $name = '_customize-radio-'.$this->id;
			?>
			<label>
                <?php if ( ! empty( $this->label ) && isset( $this->label ) ) : ?>
				    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php endif; ?>
                <?php if ( ! empty( $this->description ) && isset( $this->description ) ) : ?>
				    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
			</label>

            <div id="<?php echo $this->id; ?>" class="buttonset-setting buttonset image">
                <?php foreach ( $this->choices as $value => $data ) : ?>
                <div class="image-item">
                    <?php  $data = esc_url( sprintf( $data, get_template_directory_uri(), get_stylesheet_directory_uri() ) ); ?>
                    <input style = "display:none" class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo $this->id.esc_attr( $value ); ?>" <?php $this->link();  checked( $this->value(), $value ); ?>>
                        <label for="<?php echo $this->id.esc_attr( $value ); ?>">
                            <img src="<?php echo esc_url( $data ); ?>" alt="<?php if(isset( $value )) echo esc_html( $value ); ?>">
                            <?php if(isset( $value )): ?>
                               <span class="label"><?php echo esc_html( $value ); ?></span>
                            <?php endif; ?>
                        </label>
                    </input>
                </div>
                <?php endforeach; ?>
            </div>
			<?php
		}
	}
	
}
