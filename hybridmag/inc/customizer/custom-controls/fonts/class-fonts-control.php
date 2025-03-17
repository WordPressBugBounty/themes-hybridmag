<?php
/**
 * Fonts Control
 * 
 * @since 1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ) :

    class HybridMag_Fonts_Control extends WP_Customize_Control {

        public $type = 'hm-fonts';

        /**
         * Render the control's content.
         */
        public function render_content() { ?>
            <label>
                <?php if ( ! empty( $this->label ) ) : ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php endif; ?>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
                <?php endif; ?>

                <select class="hm-fonts-select" <?php $this->link() ?>>
                    <option value="" <?php if ( ! $this->value() ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Inherit', 'hybridmag' ); ?></option>
                    
                    <?php
                        // System Fonts. 
                        ?>
                        <optgroup label="<?php esc_attr_e( 'System Font Stack', 'hybridmag' ); ?>">
                            <option value="system-stack" <?php selected( 'system-stack', $this->value() ); ?>><?php echo esc_html__( 'System Font', 'hybridmag' ); ?></option>
                        </optgroup>

                    <?php 

                    // Standard Fonts.
                    if ( $standard_fonts = hybridmag_get_standard_fonts() ) { ?>
                        <optgroup label="<?php esc_attr_e( 'Standard Fonts', 'hybridmag' ); ?>">
                            <?php
                            // Add each font to the list
                            foreach ( $standard_fonts as $font ) { ?>
                                <option value="<?php echo esc_html( $font ); ?>" <?php selected( $font, $this->value() ); ?>><?php echo esc_html( $font ); ?></option>
                            <?php } ?>
                        </optgroup>
                    <?php }
                    
                    // Google Fonts.
                    if ( $google_fonts = hybridmag_get_google_fonts() ) { ?>
                        <optgroup label="<?php esc_attr_e( 'Google Fonts', 'hybridmag' ); ?>">
                            <?php
                            // Add each font to the list
                            foreach ( $google_fonts as $font ) { ?>
                                <option value="<?php echo esc_html( $font ); ?>" <?php selected( $font, $this->value() ); ?>><?php echo esc_html( $font ); ?></option>
                            <?php } ?>
                        </optgroup>
                    <?php } ?>   
                
                </select>

            </label>

            <?php
        }
    
    }

endif;