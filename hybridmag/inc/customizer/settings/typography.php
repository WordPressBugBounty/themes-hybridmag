<?php
/**
 * Typography Settings.
 */

function hybridmag_get_typography_elements() {
    return apply_filters( 'hybridmag_typography_elements', array(
        'body'  => array(
            'title'     => esc_html__( 'Body', 'hybridmag' ),
            'target'    => 'body, button, input, select, optgroup, textarea',
            'defaults'  => array(
                'font-family' => 'Figtree',
                'line-height' => 1.5
            )
        ),

        'headings'  => array(
            'title'     => esc_html__( 'Headings', 'hybridmag' ),
            'target'    => 'h1, h2, h3, h4, h5, h6, .site-title, .post-navigation .nav-links .post-title, button, input[type="button"], input[type="reset"], input[type="submit"], .hm-readmore-btn, .hm-cta-btn',
            'defaults'  => array(
                'font-family' => 'Figtree',
                'line-height' => 1.3
            ),
            'exclude'   => array( 'font-size' ),
        ),

        'logo'  => array(
            'title'     => esc_html__( 'Site Title', 'hybridmag' ),
            'target'    => '.site-title',
            'defaults'  => array(
                'line-height' => 1.2
            ), 
        ),

        'blog_entry_title'  => array(
            'title'     => esc_html__( 'Blog Entry Title', 'hybridmag' ),
            'target'    => '.hm-entry .entry-title',
            'defaults'  => array(
                'line-height' => 1.3
            ),
            'exclude'   => array( 'font-family' )
        ),

        'single_post_body'  => array(
            'title'     => esc_html__( 'Single Post Content', 'hybridmag' ),
            'target'    => '.hm-entry-single .entry-content',
            'defaults'  => array(
                'line-height' => 1.7
            ),
            'exclude'   => array( 'font-family' )
        ),

        )
    );
}

function hybridmag_customize_typography( $wp_customize ) {

    $elements = hybridmag_get_typography_elements();

    // Typography Settings Panel
	$wp_customize->add_panel(
		'hybridmag_typography_panel',
		array(
			'priority' 			=> 24,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Typography', 'hybridmag' )
		)
	);

    foreach ( $elements as $element => $values ) {

        $exclude_attributes = ! empty( $values['exclude'] ) ? $values['exclude'] : false;

        if ( ! empty ( $values['attributes'] ) ) {
            $attributes = $values['attributes'];
        } else {
            $attributes = array(
                'font-family',
                'font-weight',
                'font-style',
                'text-transform',
                'font-size',
                'line-height',
                //'color'
            );
        }

        $attributes = array_combine( $attributes, $attributes );
        
        if( $exclude_attributes ) {
            foreach ( $exclude_attributes as $key => $value ) {
                unset( $attributes[ $value ] );
            }
        }

        /**
         * Fonts Section
         */
        $wp_customize->add_section(
            'hybridmag_'. $element .'_typography_section',
            array(
                'title'			=> $values['title'],
                'panel'			=> 'hybridmag_typography_panel',
                'priority'      => 26
            )
        );

        /**
         * Font Family
         */
        if ( in_array( 'font-family', $attributes ) ) {

            $default_font = ! empty( $values[ 'defaults' ][ 'font-family' ] ) ? $values[ 'defaults' ][ 'font-family' ] : '';

            $wp_customize->add_setting( 
                'hybridmag_'. $element .'_font_family',
                array(
                    'default'           => $default_font,
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );
            $wp_customize->add_control( 
                new HybridMag_Fonts_Control( $wp_customize, 'hybridmag_'. $element .'_font_family',
                array(
                    'label'         => esc_html__( 'Font Family', 'hybridmag' ),
                    'section'       => 'hybridmag_'. $element .'_typography_section',
                    'settings'      => 'hybridmag_'. $element .'_font_family'
                )
            ) );

        }

        /**
         * Font Weight
         */
        if ( in_array( 'font-weight', $attributes ) ) {

            $font_weight = ! empty( $values[ 'defaults' ][ 'font-weight' ] ) ? $values[ 'defaults' ][ 'font-weight' ] : '';

            $wp_customize->add_setting(
                'hybridmag_'. $element .'_font_weight',
                array(
                    'default'			=> $font_weight,
                    'type'				=> 'theme_mod',
                    'capability'		=> 'edit_theme_options',
                    'sanitize_callback'	=> 'hybridmag_sanitize_select'
                )
            );
            $wp_customize->add_control(
                'hybridmag_'. $element .'_font_weight',
                array(
                    'settings'		=> 'hybridmag_'. $element .'_font_weight',
                    'section'		=> 'hybridmag_'. $element .'_typography_section',
                    'type'			=> 'select',
                    'label'			=> esc_html__( 'Font Weight', 'hybridmag' ),
                    'choices'		=> array(
                        ''          => esc_html__( 'Default', 'hybridmag' ),
                        '100'       => esc_html__( 'Thin: 100', 'hybridmag' ),
                        '200'       => esc_html__( 'Extra Light: 200', 'hybridmag' ),
                        '300'       => esc_html__( 'Light: 300', 'hybridmag' ),
                        '400'       => esc_html__( 'Normal: 400', 'hybridmag' ),
                        '500'       => esc_html__( 'Medium: 500', 'hybridmag' ),
                        '600'       => esc_html__( 'Semi Bold: 600', 'hybridmag' ),
                        '700'       => esc_html__( 'Bold: 700', 'hybridmag' ),
                        '800'       => esc_html__( 'Extra Bold: 800', 'hybridmag' ),
                        '900'       => esc_html__( 'Black: 900', 'hybridmag' )
                    )
                )
            );

        }

        /**
         * Font Style
         */
        if ( in_array( 'font-style', $attributes ) ) {

            $font_style = ! empty( $values[ 'defaults' ][ 'font-style' ] ) ? $values[ 'defaults' ][ 'font-style' ] : '';

            $wp_customize->add_setting(
                'hybridmag_'. $element .'_font_style',
                array(
                    'default'			=> $font_style,
                    'type'				=> 'theme_mod',
                    'capability'		=> 'edit_theme_options',
                    'sanitize_callback'	=> 'hybridmag_sanitize_select'
                )
            );
            $wp_customize->add_control(
                'hybridmag_'. $element .'_font_style',
                array(
                    'settings'		=> 'hybridmag_'. $element .'_font_style',
                    'section'		=> 'hybridmag_'. $element .'_typography_section',
                    'type'			=> 'select',
                    'label'			=> esc_html__( 'Font Style', 'hybridmag' ),
                    'choices'		=> array(
                        ''          => esc_html__( 'Default', 'hybridmag' ),
                        'normal'    => esc_html__( 'Normal', 'hybridmag' ),
                        'italic'    => esc_html__( 'Italic', 'hybridmag' )
                    )
                )
            );   
        
        }
        
        
        /**
         * Text Transform
         */
        if ( in_array( 'text-transform', $attributes ) ) {

            $text_transform = ! empty( $values[ 'defaults' ][ 'text-transform' ] ) ? $values[ 'defaults' ][ 'text-transform' ] : '';

            $wp_customize->add_setting(
                'hybridmag_'. $element .'_text_transform',
                array(
                    'default'			=> $text_transform,
                    'type'				=> 'theme_mod',
                    'capability'		=> 'edit_theme_options',
                    'sanitize_callback'	=> 'hybridmag_sanitize_select'
                )
            );
            $wp_customize->add_control(
                'hybridmag_'. $element .'_text_transform',
                array(
                    'settings'		=> 'hybridmag_'. $element .'_text_transform',
                    'section'		=> 'hybridmag_'. $element .'_typography_section',
                    'type'			=> 'select',
                    'label'			=> esc_html__( 'Text Transform', 'hybridmag' ),
                    'choices'		=> array(
                        ''              => esc_html__( 'Default', 'hybridmag' ),
                        'uppercase'     => esc_html__( 'Uppercase', 'hybridmag' ),
                        'capitalize'    => esc_html__( 'Capitalize', 'hybridmag' ),
                        'lowercase'     => esc_html__( 'Lowercase', 'hybridmag' ),
                        'none'          => esc_html__( 'None', 'hybridmag' )
                    )
                )
            );

        }


        /**
         * Font Size
         */
        if ( in_array( 'font-size', $attributes ) ) {
        
            $desktop_font_size = ! empty( $values[ 'defaults' ][ 'font-size' ][ 'desktop' ] ) ? $values[ 'defaults' ][ 'font-size' ][ 'desktop' ] : '';
            $tablet_font_size = ! empty( $values[ 'defaults' ][ 'font-size' ][ 'tablet' ] ) ? $values[ 'defaults' ][ 'font-size' ][ 'tablet' ] : '';
            $mobile_font_size = ! empty( $values[ 'defaults' ][ 'font-size' ][ 'mobile' ] ) ? $values[ 'defaults' ][ 'font-size' ][ 'mobile' ] : '';
            
            // Font Size - Desktop.
            $wp_customize->add_setting(
                'hybridmag_'. $element .'_desktop_font_size',
                array(
                    'default'			=> $desktop_font_size,
                    'type'				=> 'theme_mod',
                    'capability'		=> 'edit_theme_options',
                    'sanitize_callback'	=> 'sanitize_text_field'
                )
            );
            // Font Size - Tab.
            $wp_customize->add_setting(
                'hybridmag_'. $element .'_tablet_font_size',
                array(
                    'default'			=> $tablet_font_size,
                    'type'				=> 'theme_mod',
                    'capability'		=> 'edit_theme_options',
                    'sanitize_callback'	=> 'sanitize_text_field'
                )
            );
            // Font Size - Mobile.
            $wp_customize->add_setting(
                'hybridmag_'. $element .'_mobile_font_size',
                array(
                    'default'			=> $mobile_font_size,
                    'type'				=> 'theme_mod',
                    'capability'		=> 'edit_theme_options',
                    'sanitize_callback'	=> 'sanitize_text_field'
                )
            );
            $wp_customize->add_control( 
                new HybridMag_Responsive_Number_Control( $wp_customize, 'hybridmag_'. $element .'_font_size',
                array(
                    'label'         => esc_html__( 'Font Size', 'hybridmag' ),
                    'description' 	=> esc_html__( 'You can add: px-em-rem', 'hybridmag' ),
                    'section'       => 'hybridmag_'. $element .'_typography_section',
                    'settings'      => array(
                        'desktop'   => 'hybridmag_'. $element .'_desktop_font_size',
                        'tablet'    => 'hybridmag_'. $element .'_tablet_font_size',
                        'mobile'    => 'hybridmag_'. $element .'_mobile_font_size'
                    )
                )
            ) );
        }

        /**
         * Line Height
         */
        if ( in_array( 'line-height', $attributes ) ) {

            $line_height = ! empty( $values[ 'defaults' ][ 'line-height' ] ) ? $values[ 'defaults' ][ 'line-height' ] : '';

            $wp_customize->add_setting( 
                'hybridmag_'. $element .'_line_height',
                array(
                    'default'           => $line_height,
                    'sanitize_callback' => 'hybridmag_sanitize_slider_number_input',
                )
            );
            $wp_customize->add_control( 
                new HybridMag_Slider_Control( $wp_customize, 'hybridmag_'. $element .'_line_height',
                array(
                    'label'         => esc_html__( 'Line Height', 'hybridmag' ),
                    'section'       => 'hybridmag_'. $element .'_typography_section',
                    'input_attrs'   => array(
                        'min'   => 0.5,
                        'max'   => 4,
                        'step'  => 0.1,
                    )
                )
            ) );
        }
    
    } // end foreach;

}
add_action( 'customize_register', 'hybridmag_customize_typography' );


/**
 * This goes through each elements and get values for all settings.
 */
function hybridmag_typography_loop( $return = 'css' ) {

    $css = '';
    $fonts = array();
    $elements = hybridmag_get_typography_elements();

    foreach ( $elements as $element => $values ) {
        
        $properties = array(
            'font-family',
            'font-weight',
            'font-style',
            'text-transform',
            'font-size',
            'line-height',
            //'color'
        );

        $common_css = '';
        $tablet_css = '';
        $mobile_css = '';

        foreach( $properties as $property ) {

            $setting = str_replace( '-', '_', $property );

            // font size css properties and values.
            if ( 'font-size' == $property ) {
                
                // Get default values for each device.
                $default_desktop        = isset( $values['defaults'][$property]['desktop'] ) ? $values['defaults'][$property]['desktop'] : '';
                $default_tablet         = isset( $values['defaults'][$property]['tablet'] ) ? $values['defaults'][$property]['tablet'] : '';
                $default_mobile         = isset( $values['defaults'][$property]['mobile'] ) ? $values['defaults'][$property]['mobile'] : '';

                // Theme mods for each setting.
                $theme_setting_desktop          = get_theme_mod( 'hybridmag_'. $element .'_desktop_'. $setting, $default_desktop );
                $theme_setting_tablet           = get_theme_mod( 'hybridmag_'. $element .'_tablet_'. $setting, $default_tablet );
                $theme_setting_mobile           = get_theme_mod( 'hybridmag_'. $element .'_mobile_'. $setting, $default_mobile );

                // CSS properties and values for desktop.
                if ( ! empty( $theme_setting_desktop ) && $default_desktop != $theme_setting_desktop ) {
                    $common_css .= $property .':'. esc_attr( $theme_setting_desktop ) .';';
                } 

                // CSS properties and values for tablet.
                if ( ! empty( $theme_setting_tablet ) && $default_tablet != $theme_setting_tablet ) {
                    $tablet_css .= $property .':'. esc_attr( $theme_setting_tablet ) .';';
                } 
                
                // CSS properties and values for mobile.
                if ( ! empty( $theme_setting_mobile ) && $default_mobile != $theme_setting_mobile ) {
                    $mobile_css .= $property .':'. esc_attr( $theme_setting_mobile ) .';';
                }

            } elseif ( 'font-family' == $property ) {

                // Default values defined in elements array.
                $default = isset( $values['defaults'][$property] ) ? $values['defaults'][$property] : '';

                // Theme mod.
                $theme_setting = get_theme_mod( 'hybridmag_'. $element .'_'. $setting, $default );

                // CSS properties and values.
                if ( ! empty( $theme_setting ) && $default != $theme_setting ) {
                    if ( $theme_setting === 'system-stack' ) {
                        // System Fonts Stack.
                        $system_stack = apply_filters( 'hybridmag_typography_system_stack', '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif' );
                        
                        $common_css .= $property .':'.  $system_stack .';';
                    } else {
                        $common_css .= $property .':'. esc_attr( $theme_setting ).';';
                    }
                }

                // Add the font family into fonts array.
                if ( 'fonts' === $return ) {
                    $fonts[] = $theme_setting;
                }

            } else {
                // Default values defined in elements array.
                $default = isset( $values['defaults'][$property] ) ? $values['defaults'][$property] : '';

                // Theme mod.
                $theme_setting = get_theme_mod( 'hybridmag_'. $element .'_'. $setting, $default );

                // CSS properties and values.
                if ( ! empty( $theme_setting ) && $default != $theme_setting ) {
                    $common_css .= $property .':'. esc_attr( $theme_setting ).';';
                }
            }

        }

        // Targeted selectors
        $selectors = $values['target'];

        // Common CSS appiled to all devices.
        if ( ! empty( $common_css ) ) {
            $css .= $selectors .'{'. $common_css .'}';
        }

        // CSS for tablet devices.
        if ( ! empty( $tablet_css ) ) {
            $css .= '@media(max-width: 768px){'. $selectors .'{'. $tablet_css .'}}';
        }

        // CSS for mobile devices.
        if ( ! empty( $mobile_css ) ) {
            $css .= '@media(max-width: 480px){'. $selectors .'{'. $mobile_css .'}}';
        }

    }

    // If asked for css
    if ( 'css' === $return ) {
        return $css;
    }

    // If asked for fonts
    if ( 'fonts' === $return ) {
        return $fonts;
    }

}

/**
 * Generates typography CSS.
 */
function hybridmag_typography_css( $output ) {
    // Get CSS
    $css = hybridmag_typography_loop('css');

    // Return CSS
    if ( ! empty( $css ) ) {
        $output .= '/* Typography CSS */'. $css;
    }

    // Return output css
    return $output;
}
add_filter( 'hybridmag_head_css', 'hybridmag_typography_css' );