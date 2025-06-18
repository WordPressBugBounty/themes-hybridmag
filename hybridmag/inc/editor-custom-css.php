<?php

/**
 * Custom CSS for gutenberg editor.
 * 
 * @package HybridMag
 */

 function hybridmag_get_editor_typography_elements() {
    return apply_filters( 'hybridmag_editor_typography_elements', array (
        'body'  => array(
            'title'     => esc_html__( 'Body', 'hybridmag' ),
            'target'    => 'html .editor-styles-wrapper',
            'defaults'  => array(
                'font-family' => 'Figtree',
                'line-height' => 1.5
            )
        ),

        'headings'  => array(
            'title'     => esc_html__( 'Headings', 'hybridmag' ),
            'target'    => 'html .editor-styles-wrapper h1, html .editor-styles-wrapper h2, html .editor-styles-wrapper h3, html .editor-styles-wrapper h4, html .editor-styles-wrapper h5, html .editor-styles-wrapper h6',
            'defaults'  => array(
                'font-family' => 'Figtree',
                'line-height' => 1.3
            ),
            'exclude'   => array( 'font-size' ),
        ),

        'single_post_body'  => array(
            'title'     => esc_html__( 'Single Post Content', 'hybridmag' ),
            'target'    => 'html .editor-styles-wrapper',
            'defaults'  => array(
                'line-height' => 1.7
            ),
            'exclude'   => array( 'font-family' )
        )

        )
    );
}


/**
 * Theme Customizations Specific to Block Editor Custom CSS.
 */
function hybridmag_block_editor_custom_css() {

    $css = '';

    // SETUP CSS VARIABLES
    $css_variables = "";

    $primary_color = get_theme_mod( 'hybridmag_primary_color', '#65bc7b' );
    $inner_background_color = get_theme_mod( 'hybridmag_inner_bg_color', '' );
    $sep_content_bg_color = get_theme_mod( 'hybridmag_sep_content_bg_color', '' );
    $text_color = get_theme_mod( 'hybridmag_text_color', '#404040' );
    $headings_text_color = get_theme_mod( 'hybridmag_headings_text_color', '#222222' );
    $sep_content_outer_text_color = get_theme_mod( 'hybridmag_sep_content_outer_text_color', '' );
    $links_color = get_theme_mod( 'hybridmag_links_color', '#0a0808' );
    $links_hover_color = get_theme_mod( 'hybridmag_links_hover_color', '' );
    $button_bg_color = get_theme_mod( 'hybridmag_button_bg_color', '' );
    $button_bg_hover_color = get_theme_mod( 'hybridmag_button_bg_hover_color', '' );
    $button_text_color = get_theme_mod( 'hybridmag_button_text_color', '' );
    $button_text_hover_color = get_theme_mod( 'hybridmag_button_text_hover_color', '' );
    $global_border_radius = get_theme_mod( 'hybridmag_global_border_radius', 6 );

    if ( ! empty( $primary_color ) && '#65bc7b' != $primary_color ) {
        $css_variables .= '
            --hybridmag-color-primary: '. esc_attr( $primary_color ) .';
        ';
    }

    if ( ! empty( $sep_content_bg_color ) ) {
        $css_variables .= '
            --hybridmag-color-bg-cl-sep-content: '. esc_attr( $sep_content_bg_color ) .';
        ';
    }

    if ( ! empty( $text_color ) && '#404040' != $text_color ) {
        $css_variables .= '
            --hybridmag-color-text-main: '. esc_attr( $text_color ) .';
        ';
    }

    if ( ! empty( $headings_text_color ) && '#222222' != $headings_text_color ) {
        $css_variables .= '
            --hybridmag-color-text-headings: '. esc_attr( $headings_text_color ) .';
        ';
    }

    if ( ! empty( $links_color ) && '#0a0808' != $links_color ) {
        $css_variables .= '
            --hybridmag-color-link: '. esc_attr( $links_color ) .';
        ';
    }

    if ( ! empty( $links_hover_color ) ) {
        $css_variables .= '
            --hybridmag-color-link-hover: '. esc_attr( $links_hover_color ) .';
        ';
    }

    if ( ! empty( $button_bg_color ) ) {
        $css_variables .= '
            --hybridmag-color-button-background: '. esc_attr( $button_bg_color ) .';
        ';
    }

    if ( ! empty( $button_bg_hover_color ) ) {
        $css_variables .= '
            --hybridmag-color-button-hover-background: '. esc_attr( $button_bg_hover_color ) .';
        ';
    }

    if ( ! empty( $button_text_color ) ) {
        $css_variables .= '
            --hybridmag-color-button-text: '. esc_attr( $button_text_color ) .';
        ';
    }
    
    if ( ! empty( $button_text_hover_color ) ) {
        $css_variables .= '
            --hybridmag-color-button-hover-text: '. esc_attr( $button_text_hover_color ) .';
        ';
    }
    
    if ( 6 != $global_border_radius ) {
        $css_variables .= '
            --hybridmag-global-border-radius: '. esc_attr( $global_border_radius ) .'px;
        ';
    }

    $css .= '
        html .editor-styles-wrapper { ' . $css_variables . ' }
    ';

    // TYPOGRAPHY

    $elements = hybridmag_get_editor_typography_elements();

    foreach ( $elements as $element => $values ) {
        
        $properties = array(
            'font-family',
            'font-weight',
            'font-style',
            'text-transform',
            'font-size',
            'line-height'
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

            } elseif ( $property == 'font-family' ) {

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

    // Editor Background Color Select - Currently only for the magazine template.
    $site_layout = get_theme_mod( 'hybridmag_site_layout', 'wide' );
    $content_layout = get_theme_mod( 'hybridmag_content_layout', 'separate-containers' );
    $custom_bg_color = get_background_color();

    if ( 'wide' === $site_layout ) {
        if ( ! empty( $custom_bg_color ) ) {
            if ( 'separate-containers' === $content_layout ) {
                $css .= '
                    body.hybridmag-wide.page-template-template-magazine.hm-cl-sep .editor-styles-wrapper {
                        background: #'. esc_attr( $custom_bg_color ) .' !important;
                    }
                ';
            } elseif ( 'one-container' === $content_layout ) {
                $css .= '
                    body.hybridmag-wide.page-template-template-magazine.hm-cl-one .editor-styles-wrapper {
                        background: #'. esc_attr( $custom_bg_color ) .' !important;
                    }
                ';
            }
        }
    }

    if ( 'boxed' === $site_layout ) {
        if ( ! empty( $inner_background_color ) ) {
            if ( 'separate-containers' === $content_layout ) {
                $css .= '
                    body.hybridmag-boxed.page-template-template-magazine.hm-cl-sep .editor-styles-wrapper {
                        background: '. esc_attr( $inner_background_color ) .' !important;
                    }
                ';
            } elseif ( 'one-container' === $content_layout ) {
                $css .= '
                    body.hybridmag-boxed.page-template-template-magazine.hm-cl-one .editor-styles-wrapper {
                        background: '. esc_attr( $inner_background_color ) .' !important;
                    }
                ';
            }
        }
    }

    return $css;

}


/**
 * Add Block Editor Styles.
 */
function hybridmag_block_editor_styles() {

    $fonts_arr = hybridmag_typography_loop( 'fonts' );

    if ( ! empty( $fonts_arr ) ) {
        $fonts_arr = array_unique( $fonts_arr );

        $default_font_index = array_search( 'Figtree', $fonts_arr );
    
        if ( $default_font_index !== false ) {
    
            // Enqueue Default Font CSS from theme.
            wp_enqueue_style( 'hybridmag-font-figtree', get_theme_file_uri( '/assets/css/font-figtree.css' ), array(), null );
    
            unset( $fonts_arr[ $default_font_index ] );
            $fonts_arr = array_values( $fonts_arr );
    
        }
    }

    // Check again $fonts_arr after changing the array above.
	if ( ! empty( $fonts_arr ) ) {
		wp_enqueue_style( 'hybridmag-block-editor-fonts', hybridmag_get_google_font_uri( $fonts_arr ), array(), null );
	}

    // Block styles.
    wp_enqueue_style( 'hybridmag-block-editor-styles', get_theme_file_uri( '/assets/css/block-editor-style.css' ), false, HYBRIDMAG_VERSION, 'all' );

    $theme_customizations = '';

    // Get theme customizations related to block editor.
    $theme_customizations .= hybridmag_block_editor_custom_css();

	if ( $theme_customizations ) {
		wp_add_inline_style( 'hybridmag-block-editor-styles', $theme_customizations );
	}

    $block_editor_inline_styles = hybridmag_inline_block_editor_styles();

    if ( $block_editor_inline_styles ) {
		wp_add_inline_style( 'hybridmag-block-editor-styles', $block_editor_inline_styles );
	}

}
if ( is_admin() && version_compare( $GLOBALS['wp_version'], '6.3', '>=' ) ) {
	add_action( 'enqueue_block_assets', 'hybridmag_block_editor_styles', 1, 1 );
} else {
	add_action( 'enqueue_block_editor_assets', 'hybridmag_block_editor_styles', 1, 1 );
}