<?php
/**
 * Output all the dynamic CSS
 * 
 * @package HybridMag
 */

if ( ! function_exists( 'hybridmag_custom_css' ) ) {

    /**
     * Generate CSS in the <head> section using the 
     */
    function hybridmag_custom_css( $output ) {

        $theme_css = "";

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

        $content_layout = get_theme_mod( 'hybridmag_content_layout', 'separate-containers' );

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

        $theme_css .= '
            :root { ' . $css_variables . ' }
        ';

        $site_layout = get_theme_mod( 'hybridmag_site_layout', 'wide' );
        if ( 'wide' === $site_layout ) {
            $container_width = get_theme_mod( 'hybridmag_container_width', 1200 );
            if ( 1200 != $container_width && ! empty( $container_width ) && $container_width >= 300 ) {
                $theme_css .= '
                    .hm-container {
                        width: '. esc_attr( $container_width ) .'px;
                    }
                ';
            }
        } elseif ( 'boxed' === $site_layout ) {
            $boxed_container_width = get_theme_mod( 'hybridmag_boxed_width', 1280 );
            if ( 1280 != $boxed_container_width && ! empty( $boxed_container_width ) && $boxed_container_width >= 300 ) {
                $theme_css .= '
                    body.hybridmag-boxed #page,
                    .hybridmag-boxed.hm-h-stk.hm-h-sticky-active .site-header,
                    .hybridmag-boxed.hm-m-stk.hm-h-sticky-active .site-header,
                    .hybridmag-boxed.hm-m-stk.hm-h-sticky-active .hm-main-menu {
                        width: '. esc_attr( $boxed_container_width ) .'px;
                    }
                ';
            }
        }

        $sidebar_width = get_theme_mod( 'hybridmag_sidebar_width', 32.916666667 );
        $sidebar_width = floatval( $sidebar_width );
        $content_area_width = 100 - $sidebar_width;
        if ( ! empty( $sidebar_width ) && 32.916666667 != $sidebar_width && 50 >= $sidebar_width && 15 <= $sidebar_width ) {
            $theme_css .= '
                @media only screen and (min-width: 768px) {
                    #primary {
                        width: '. esc_attr( $content_area_width ) .'%;
                    }
                    #secondary {
                        width: '. esc_attr( $sidebar_width ) .'%;
                    }
                }
            ';
        }

        // Logo Max Width
        $logo_max_width = get_theme_mod( 'hybridmag_logo_max_width_desktop', '' );
        if ( '' != $logo_max_width ) {
            $theme_css .= '
                .site-logo img {
                    max-width: '. esc_attr( $logo_max_width ) .'px;
                }
            ';
        }

        // Logo Max Height
        $logo_max_height = get_theme_mod( 'hybridmag_logo_max_height_desktop', '' );
        if ( '' != $logo_max_height ) {
            $theme_css .= '
                .site-logo img {
                    max-height: '. esc_attr( $logo_max_height ) .'px;
                    width: auto;
                }
            ';
        }

        // Logo Max Width Tablet
        $logo_max_width_t = get_theme_mod( 'hybridmag_logo_max_width_tablet', '' );
        if ( '' != $logo_max_width_t ) {
            $theme_css .= '
                @media (min-width: 480px) and (max-width: 768px) {
                    .site-logo img {
                        max-width: '. esc_attr( $logo_max_width_t ) .'px;
                    }
                }
            ';
        }

        // Logo Max Height Tablet
        $logo_max_height_t = get_theme_mod( 'hybridmag_logo_max_height_tablet', '' );
        if ( '' != $logo_max_height_t ) {
            $theme_css .= '
                @media (min-width: 480px) and (max-width: 768px) {
                    .site-logo img {
                        max-height: '. esc_attr( $logo_max_height_t ) .'px;
                        width: auto;
                    }
                }
            ';
        }

        // Logo Max Width Mobile
        $logo_max_width_m = get_theme_mod( 'hybridmag_logo_max_width_mobile', '' );
        if ( '' != $logo_max_width_m ) {
            $theme_css .= '
                @media (max-width: 480px) {
                    .site-logo img {
                        max-width: '. esc_attr( $logo_max_width_m ) .'px;
                    }
                }
            ';
        }

        // Logo Max Height Mobile
        $logo_max_height_m = get_theme_mod( 'hybridmag_logo_max_height_mobile', '' );
        if ( '' != $logo_max_height_m ) {
            $theme_css .= '
                @media (max-width: 480px) {
                    .site-logo img {
                        max-height: '. esc_attr( $logo_max_height_m ) .'px;
                        width: auto;
                    }
                }
            ';
        }

        if ( ! empty( $inner_background_color ) ) {

            if ( 'separate-containers' === $content_layout ) {
                $theme_css .= '
                    body.hybridmag-boxed #page {
                        background-color: '. esc_attr( $inner_background_color ) .';
                    }
                ';   
            } else {
                $theme_css .= '
                    body.hybridmag-boxed.hm-cl-one #page {
                        background-color: '. esc_attr( $inner_background_color ) .';
                    }
                ';     
            }
       
        }

        // Seperate Containers - Outer text color.
        if ( 'separate-containers' === $content_layout ) {
            if ( ! empty( $sep_content_outer_text_color ) ) {
                $theme_css .= '
                    .page-header .page-title,
                    .hm-featured-tabs .hm-tab-anchor,
                    .hm-tabs-view-more a,
                    .page-header .archive-description,
                    .hm-blog-entries-title {
                        color: '. $sep_content_outer_text_color .';
                    }
                ';
            }
        }

        $header_layout_inner_class = ".hm-h-lg .hm-header-inner";
        if ( 'default' === hybridmag_get_header_layout() ) {
            $header_layout_inner_class = ".hm-h-de .hm-header-inner-wrapper";
        }

        /**
         * Header Padding
         */

        // Header padding top.
        $header_padding_top = get_theme_mod( 'hybridmag_header_padding_top_desktop', '' );
        if ( '' != $header_padding_top ) {
            $theme_css .= '
                @media screen and (min-width: 768px) {
                    '.$header_layout_inner_class.' {
                        padding-top: '. esc_attr( $header_padding_top ) .'px;
                    }
                }
            ';
        }

        // Header padding bottom.
        $header_padding_bottom = get_theme_mod( 'hybridmag_header_padding_bottom_desktop', '' );
        if ( '' != $header_padding_bottom ) {
            $theme_css .= '
                @media screen and (min-width: 768px) {
                    '.$header_layout_inner_class.' {
                        padding-bottom: '. esc_attr( $header_padding_bottom ) .'px;
                    }
                }
            ';
        }

        // Header padding top Tablet
        $header_padding_top_t = get_theme_mod( 'hybridmag_header_padding_top_tablet', '' );
        if ( '' != $header_padding_top_t ) {
            $theme_css .= '
                @media (min-width: 480px) and (max-width: 768px) {
                    '.$header_layout_inner_class.' {
                        padding-top: '. esc_attr( $header_padding_top_t ) .'px;
                    }
                }
            ';
        }

        // Header padding bottom Tablet
        $header_padding_bottom_t = get_theme_mod( 'hybridmag_header_padding_bottom_tablet', '' );
        if ( '' != $header_padding_bottom_t ) {
            $theme_css .= '
                @media (min-width: 480px) and (max-width: 768px) {
                    '.$header_layout_inner_class.' {
                        padding-bottom: '. esc_attr( $header_padding_bottom_t ) .'px;
                    }
                }
            ';
        }

        // Header padding top mobile
        $header_padding_top_m = get_theme_mod( 'hybridmag_header_padding_top_mobile', '' );
        if ( '' != $header_padding_top_m ) {
            $theme_css .= '
                @media (max-width: 480px) {
                    '.$header_layout_inner_class.' {
                        padding-top: '. esc_attr( $header_padding_top_m ) .'px;
                    }
                }
            ';
        }

        // Header padding bottom mobile
        $header_padding_bottom_m = get_theme_mod( 'hybridmag_header_padding_bottom_mobile', '' );
        if ( '' != $header_padding_bottom_m ) {
            $theme_css .= '
                @media (max-width: 480px) {
                    '.$header_layout_inner_class.' {
                        padding-bottom: '. esc_attr( $header_padding_bottom_m ) .'px;
                    }
                }
            ';
        }

        // Large header - Primary menu line height.
        $header_line_height = get_theme_mod( 'hybridmag_pmenu_line_height', 62 );
        if ( 62 != $header_line_height ) {
            $theme_css .= '
                .hm-h-lg .main-navigation ul li a {
                    line-height: '. esc_attr( $header_line_height ) .'px;
                }
                .hm-h-lg .hm-main-menu .hm-social-menu li a,
                .hm-h-lg .hm-search-toggle/*,
                .hm-h-lg .hm-main-menu .hm-slideout-toggle */{
                    height: '. esc_attr( $header_line_height ) .'px;
                }
                .hm-h-lg .main-navigation ul ul li a {
                    line-height: initial;
                }
            ';
        }

        // Default header - Primary menu line height.
        $header_height = get_theme_mod( 'hybridmag_header_height', '' );
        if ( ! empty( $header_height ) ) {
            $theme_css .= '
                .hm-h-de .hm-header-inner {
                    min-height: '. esc_attr( $header_height ) .'px;
                }
            ';
        }

        // Display Header image as header background
        if ( has_header_image() ) {
            if ( 'header-background' === get_theme_mod( 'hybridmag_header_image_location', 'before-header-inner' ) ) {
                $header_image = get_header_image();
                $theme_css .= '
                    .hm-header-inner-wrapper {
                        background-image:url('. esc_url( $header_image ) .');
                        background-position: center center;
                        background-size: cover;
                    }
                ';
            }
        }

        /**
         * HEADER COLORS.
         */
        $header_layout = hybridmag_get_header_layout();
        $header_bg_color = get_theme_mod( 'hybridmag_header_bg_color', '' );
        $menu_bg_color = get_theme_mod( 'hybridmag_menu_bg_color', '' );
        $menu_link_color = get_theme_mod( 'hybridmag_menu_link_color', '' );
        $menu_link_hover_color = get_theme_mod( 'hybridmag_menu_link_hover_color', '' );
        $menu_link_action_hover_color = get_theme_mod( 'hybridmag_menu_link_action_hover_color', '' );
        $dropdown_menu_bg_color = get_theme_mod( 'hybridmag_dropdown_menu_bg_color', '' );
        $dropdown_menu_link_color = get_theme_mod( 'hybridmag_dropdown_menu_link_color', '' );
        $dropdown_menu_link_hover_color = get_theme_mod( 'hybridmag_dropdown_menu_link_hover_color', '' );
        $dropdown_menu_link_hover_bg_color = get_theme_mod( 'hybridmag_dropdown_menu_link_hover_bg_color', '' );

        if ( ! empty( $header_bg_color ) ) {
            $theme_css .= '
                .hm-nav-sticky.hm-header-inner-wrapper,
                .hm-header-inner-wrapper {
                    background-color: '. esc_attr( $header_bg_color ) .';
                }
            ';
        }

        /**
         * Single Line Header Layout Menu Styles.
         */
        if ( 'default' === $header_layout ) {

            if ( ! empty( $menu_bg_color ) ) {
                $theme_css .= '
                    .hm-h-de .main-navigation {
                        background-color: '. esc_attr( $menu_bg_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $menu_link_color ) ) {
                $theme_css .= '
                    .hm-h-de .main-navigation a,
                    .hm-h-de .hm-header-inner .hm-social-menu a {
                        color: '. esc_attr( $menu_link_color ) .';
                    }
                    .hm-h-de .hm-mobile-menu-toggle,
                    .hm-h-de .hm-slideout-toggle {
                        color: '. esc_attr( $menu_link_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $menu_link_hover_color ) ) {
                $theme_css .= '
                    .hm-h-de .main-navigation ul li a:hover,
                    .hm-h-de .hm-header-inner .hm-social-menu a:hover {
                        color: '. esc_attr( $menu_link_hover_color ) .';
                    }

                    .hm-h-de .hm-mobile-menu-toggle:hover,
                    .hm-h-de .hm-slideout-toggle:hover {
                        color: '. esc_attr( $menu_link_hover_color ) .';
                    }
    
                    .hm-h-de .main-navigation .current_page_item > a, 
                    .hm-h-de .main-navigation .current-menu-item > a, 
                    .hm-h-de .main-navigation .current_page_ancestor > a, 
                    .hm-h-de .main-navigation .current-menu-ancestor > a {
                        color: '. esc_attr( $menu_link_hover_color ) .';
                    }
                ';
            }
    
            // if ( ! empty( $menu_link_action_hover_color ) ) {
            //     $theme_css .= '
            //         .hm-h-lg .main-navigation ul li a:hover::after,
            //         .hm-h-lg .main-navigation .current_page_item > a::after, 
            //         .hm-h-lg .main-navigation .current-menu-item > a::after, 
            //         .hm-h-lg .main-navigation .current_page_ancestor > a::after, 
            //         .hm-h-lg .main-navigation .current-menu-ancestor > a::after {
            //             background-color: '. esc_attr( $menu_link_action_hover_color ) .';
            //         }
            //     ';
            // }
    
            if ( ! empty( $dropdown_menu_link_color ) ) {
                $theme_css .= '
                    .hm-h-de .main-navigation ul ul li a {
                        color: '. esc_attr( $dropdown_menu_link_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $dropdown_menu_link_hover_color ) ) {
                $theme_css .= '
                    .hm-h-de .main-navigation ul ul .current_page_item > a, 
                    .hm-h-de .main-navigation ul ul .current-menu-item > a, 
                    .hm-h-de .main-navigation ul ul .current_page_ancestor > a, 
                    .hm-h-de .main-navigation ul ul .current-menu-ancestor > a,
                    .hm-h-de .main-navigation ul ul li a:hover {
                        color: '. esc_attr( $dropdown_menu_link_hover_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $dropdown_menu_link_hover_bg_color ) ) {
                $theme_css .= '
                    .hm-h-de .main-navigation ul ul .current_page_item > a, 
                    .hm-h-de .main-navigation ul ul .current-menu-item > a, 
                    .hm-h-de .main-navigation ul ul .current_page_ancestor > a, 
                    .hm-h-de .main-navigation ul ul .current-menu-ancestor > a,
                    .hm-h-de .main-navigation ul ul li a:hover {
                        background-color: '. esc_attr( $dropdown_menu_link_hover_bg_color ) .';
                    }
                ';
            }

        } else {
            /**
             * DEFAULT HEADER Layout Menu Style.
             */
            if ( ! empty( $menu_bg_color ) ) {
                $theme_css .= '
                    .hm-h-lg .site-header .hm-main-menu {
                        background-color: '. esc_attr( $menu_bg_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $menu_link_color ) ) {
                $theme_css .= '
                    .hm-h-lg .main-navigation a {
                        color: '. esc_attr( $menu_link_color ) .';
                    }

                    .hm-h-lg .hm-slideout-toggle {
                        color: '. esc_attr( $menu_link_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $menu_link_hover_color ) ) {
                $theme_css .= '
                    .hm-h-lg .main-navigation a:hover {
                        color: '. esc_attr( $menu_link_hover_color ) .';
                    }
                    
                    .hm-h-lg .hm-slideout-toggle:hover {
                        color: '. esc_attr( $menu_link_hover_color ) .';
                    }
    
                    .hm-h-lg .main-navigation .current_page_item > a, 
                    .hm-h-lg .main-navigation .current-menu-item > a, 
                    .hm-h-lg .main-navigation .current_page_ancestor > a, 
                    .hm-h-lg .main-navigation .current-menu-ancestor > a {
                        color: '. esc_attr( $menu_link_hover_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $menu_link_action_hover_color ) ) {
                $theme_css .= '
                    .hm-h-lg .main-navigation ul li a:hover::before,
                    .hm-h-lg .main-navigation .current_page_item > a::before, 
                    .hm-h-lg .main-navigation .current-menu-item > a::before, 
                    .hm-h-lg .main-navigation .current_page_ancestor > a::before, 
                    .hm-h-lg .main-navigation .current-menu-ancestor > a::before {
                        background-color: '. esc_attr( $menu_link_action_hover_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $dropdown_menu_link_color ) ) {
                $theme_css .= '
                    .hm-h-lg .main-navigation ul ul li a {
                        color: '. esc_attr( $dropdown_menu_link_color ) .';
                    }
                ';
            }
    
            if ( ! empty( $dropdown_menu_link_hover_color ) ) {
                $theme_css .= '
                    .hm-h-lg .main-navigation ul ul li a:hover,
                    .hm-h-lg .main-navigation ul ul .current_page_item > a, 
                    .hm-h-lg .main-navigation ul ul .current-menu-item > a, 
                    .hm-h-lg .main-navigation ul ul .current_page_ancestor > a, 
                    .hm-h-lg .main-navigation ul ul .current-menu-ancestor > a {
                        color: '. esc_attr( $dropdown_menu_link_hover_color ) .';
                    }
                ';
            }

            if ( ! empty( $dropdown_menu_link_hover_bg_color ) ) {
                $theme_css .= '
                    .hm-h-lg .main-navigation ul ul .current_page_item > a, 
                    .hm-h-lg .main-navigation ul ul .current-menu-item > a, 
                    .hm-h-lg .main-navigation ul ul .current_page_ancestor > a, 
                    .hm-h-lg .main-navigation ul ul .current-menu-ancestor > a,
                    .hm-h-lg .main-navigation ul ul li a:hover {
                        background-color: '. esc_attr( $dropdown_menu_link_hover_bg_color ) .';
                    }
                ';
            }

        } // END HEADER LAYOUT IF()

        /**
         * Common menu styles for both header layouts.
         */
         if ( ! empty( $dropdown_menu_bg_color ) ) {
            $theme_css .= '
                #site-navigation.hm-menu ul ul {
                    background-color: '. esc_attr( $dropdown_menu_bg_color ) .';
                }
            ';
        }

         if ( ! empty( $menu_link_color ) ) {
            $theme_css .= '
                .hm-light-dark-toggle .hm-svg-icon,
                .hm-search-toggle .hm-svg-icon {
                    color: '. esc_attr( $menu_link_color ) .';
                }
            ';
        }

        /**
         * TOP BAR COLORS
         */
        $topbar_bg_color = get_theme_mod( 'hybridmag_topbar_bg_color', '' );
        $topbar_text_color = get_theme_mod( 'hybridmag_topbar_text_color', '' );
        $topbar_link_color = get_theme_mod( 'hybridmag_topbar_link_color', '' );
        $topbar_link_hover_color = get_theme_mod( 'hybridmag_topbar_link_hover_color', '' );

        if ( ! empty( $topbar_bg_color ) ) {
            $theme_css .= '
                .hm-top-bar {
                    background-color: '. esc_attr( $topbar_bg_color ) .';
                }
            ';
        }

        if ( ! empty( $topbar_text_color ) ) {
            $theme_css .= '
                .hm-top-bar {
                    color: '. esc_attr( $topbar_text_color ) .';
                }
            ';
        }

        if ( ! empty( $topbar_link_color ) ) {
            $theme_css .= '
                .secondary-menu a,
                .hm-top-bar a {
                    color: '. esc_attr( $topbar_link_color ) .';
                }

                .hm-top-bar .hm-slideout-toggle {
                    color: '. esc_attr( $topbar_link_color ) .';
                }
            ';
        }

        if ( ! empty( $topbar_link_hover_color ) ) {
            $theme_css .= '
                .hm-top-bar .hm-social-menu a:hover,
                .hm-top-bar .hm-slideout-toggle:hover,
                .hm-top-bar a:hover {
                    color: '. esc_attr( $topbar_link_hover_color ) .';
                }
            ';
        }

        // Footer Widget Area
        $footer_widget_bg_color = get_theme_mod( 'hybridmag_footer_widget_bg_color', '' );
        $footer_widget_text_color = get_theme_mod( 'hybridmag_footer_widget_text_color', '' );
        $footer_widget_link_color = get_theme_mod( 'hybridmag_footer_widget_link_color', '' );
        $footer_widget_link_hover_color = get_theme_mod( 'hybridmag_footer_widget_link_hover_color', '' );

        if ( ! empty( $footer_widget_bg_color ) ) {
            $theme_css .= '
                .hm-footer-widget-area {
                    background-color: '. esc_attr( $footer_widget_bg_color ) .';
                }
            ';
        }

        if ( ! empty( $footer_widget_text_color ) ) {
            $theme_css .= '
                .site-footer,
                .site-footer .widget-title {
                    color: '. esc_attr( $footer_widget_text_color ) .';
                }
            ';
        }

        if ( ! empty( $footer_widget_link_color ) ) {
            $theme_css .= '
                .site-footer a {
                    color: '. esc_attr( $footer_widget_link_color ) .';
                }
            ';
        }

        if ( ! empty( $footer_widget_link_hover_color ) ) {
            $theme_css .= '
                .site-footer a:hover {
                    color: '. esc_attr( $footer_widget_link_hover_color ) .';
                }
            ';
        }

        // Footer bottom Area
        $footer_bottom_bg_color = get_theme_mod( 'hybridmag_footer_bottom_bg_color', '' );
        $footer_bottom_text_color = get_theme_mod( 'hybridmag_footer_bottom_text_color', '' );
        $footer_bottom_link_color = get_theme_mod( 'hybridmag_footer_bottom_link_color', '' );
        $footer_bottom_link_hover_color = get_theme_mod( 'hybridmag_footer_bottom_link_hover_color', '' );

        if ( ! empty( $footer_bottom_bg_color ) ) {
            $theme_css .= '
                .hm-footer-bottom {
                    background-color: '. esc_attr( $footer_bottom_bg_color ) .';
                }
            ';
        }

        if ( ! empty( $footer_bottom_text_color ) ) {
            $theme_css .= '
                .hm-footer-bottom {
                    color: '. esc_attr( $footer_bottom_text_color ) .';
                }
            ';
        }

        if ( ! empty( $footer_bottom_link_color ) ) {
            $theme_css .= '
                .hm-footer-bottom a {
                    color: '. esc_attr( $footer_bottom_link_color ) .';
                }
            ';
        }

        if ( ! empty( $footer_bottom_link_hover_color ) ) {
            $theme_css .= '
                .hm-footer-bottom a:hover {
                    color: '. esc_attr( $footer_bottom_link_hover_color ) .';
                }
            ';
        }

        if ( true === get_theme_mod( 'hybridmag_hide_cta_mobile', false ) ) {
            $theme_css .= '
                @media ( max-width: 480px ) {
                    .hm-cta-btn {
                        display: none;
                    }
                }
            ';
        }

        // Category Colors.
        $categories = get_categories( array( 'hide_empty' => 1 ) );
        foreach ( $categories as $category ) {
            $term_id   = $category->term_id;
            $cat_color = get_theme_mod( 'hybridmag_cat_color_' . strtolower( $term_id ), '' );

            if ( ! empty( $cat_color ) ) {

                $theme_css .= '
                    .cat-links:not(.entry-meta .cat-links) a.cat-' . absint( $term_id ) . ',
                    .bnm-box-cat .bnm-cat-links a.cat-' . absint( $term_id ) . '  {
                        background: ' . esc_attr( $cat_color ) . ';
                    }
                ';

            }
        }

        $category_text_color = get_theme_mod( 'hybridmag_category_text_color', '' );
        if ( ! empty( $category_text_color ) ) {
            $theme_css .= '
                .cat-links:not(.entry-meta .cat-links) a {
                    color: ' . esc_attr( $category_text_color ) . ';
                }
            ';
        }

        if ( ! empty( $theme_css ) ) {
            $output .= '/* Custom CSS */'. $theme_css;
        }

        //return $output;

        /**
         * hybridmag_theme_custom_css hook since HybridMag 1.0.0
         */
        return $custom_css = apply_filters( 'hybridmag_theme_custom_css', $output );

    }
}
add_filter( 'hybridmag_head_css', 'hybridmag_custom_css' );