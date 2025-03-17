<?php
/**
 * Slider Control
 * 
 * @since 1.0.0
 */

/**
 * Slider Custom Control
 *
 * @author Anthony Hortin <http://maddisondesigns.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 * @link https://github.com/maddisondesigns
 */
if ( class_exists( 'WP_Customize_Control' ) ) :

    class HybridMag_Slider_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'hybridmag-slider-control';
        /**
         * Enqueue our scripts and styles
         */
        public function enqueue() {
            wp_enqueue_script( 'hybridmag-slider-control', get_template_directory_uri() . '/inc/customizer/custom-controls/slider/slider.js', array( 'jquery', 'jquery-ui-core' ), '1.0', true );
            wp_enqueue_style( 'hybridmag-slider-control', get_template_directory_uri() . '/inc/customizer/custom-controls/slider/slider.css', array(), '1.0', 'all' );
        }
        /**
         * Render the control in the customizer
         */
        public function render_content() {
            $default_value = isset( $this->setting->default ) ? $this->setting->default : '';
            $min_value = isset( $this->input_attrs['min'] ) ? $this->input_attrs['min'] : ''; 
            $max_value = isset( $this->input_attrs['max'] ) ? $this->input_attrs['max'] : '';
            $step = isset( $this->input_attrs['step'] ) ? $this->input_attrs['step'] : 1;
        ?>
            <div class="hybridmag-slider-control">
                <span class="customize-control-title">
                    <?php echo esc_html( $this->label ); ?>
                </span>
                <div class="hybridmag-slider-control-wrap">
                    <div class="slider" slider-min-value="<?php echo esc_attr( $min_value ); ?>" slider-max-value="<?php echo esc_attr( $max_value ); ?>" slider-step-value="<?php echo esc_attr( $step ); ?>"></div>
                    <input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-slider-value" <?php $this->link(); ?> />
                    <span class="slider-reset dashicons dashicons-image-rotate" slider-reset-value="<?php echo esc_attr( $default_value ); ?>"></span>
                </div>
            </div>
        <?php
        }
    }

endif;