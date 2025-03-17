<?php

/**
 * Custom Link Control to jump to a particular section inside the customizer.
 * 
 * "id" should be called from js file to focus.
 */

if ( class_exists( 'WP_Customize_Control' ) ) :

    class HybridMag_Custom_Link_Control extends WP_Customize_Control {
        
        public $type = 'hybridmag_custom_link';

        public function render_content() {
            ?>
            <label>
                <a href="#" id="<?php echo esc_attr( $this->id ); ?>"><?php echo esc_html( $this->label ); ?></a>
            </label>
            <?php
        }
    }

endif;