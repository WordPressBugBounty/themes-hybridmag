<?php
/**
 * Toggle Switch Control
 * 
 */
if ( class_exists( 'WP_Customize_Control' ) ) :

    class HybridMag_Toggle_Switch_Control extends WP_Customize_Control {
        
        /**
		 * The type of control being rendered
		 */
        public $type = 'hybridmag-toggle-switch';

        /**
		 * Enqueue our scripts and styles
		 */
        public function enqueue() {
            wp_enqueue_style( 'hybridmag-toggle-switch-style', get_template_directory_uri() . '/inc/customizer/custom-controls/toggle-switch/toggle.css', array(), '1.0', 'all' );
        }

        /**
		 * Render the control in the customizer
		 */
		public function render_content() {
            ?>
                <div class="hm-toggle-switch-control">
                    <div class="hm-toggle-switch-desc">
                        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                        <?php if( !empty( $this->description ) ) { ?>
                            <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                        <?php } ?>
                    </div>
                    <div class="hm-toggle-switch">
                        <input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="hm-toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>>
                        <label class="hm-toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
                            <span class="hm-toggle-switch-inner"></span>
                            <span class="hm-toggle-switch-switch"></span>
                        </label>
                    </div>
                </div>
            <?php
        }

    }
    
endif;