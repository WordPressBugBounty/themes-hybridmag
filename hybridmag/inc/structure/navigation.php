<?php

function hybridmag_navigation_location() {
    $hybridmag_header_layout = hybridmag_get_header_layout();
    if ( 'default' == $hybridmag_header_layout ) {
        add_action( 'hybridmag_after_header_main', 'hybridmag_navigation_inline', 5 );
    } elseif ( 'large' == $hybridmag_header_layout ) {
        add_action( 'hybridmag_header_bottom', 'hybridmag_navigation_block' );
    }
}
add_action( 'wp', 'hybridmag_navigation_location' );

if ( ! function_exists( 'hybridmag_search_box' ) ) :
    /**
     * Displays the search 
     */
    function hybridmag_search_box() {
        ?>
            <div class="hm-search-container">
                <button class="hm-search-toggle">
                    <span class="hm-search-icon"><?php hybridmag_the_icon_svg( 'search' ) ?></span>
                    <span class="hm-close-icon"><?php hybridmag_the_icon_svg( 'close' ) ?></span>
                </button>
                <div class="hm-search-box">
                    <?php get_search_form(); ?>
                </div><!-- hm-search-box -->
            </div><!-- hm-search-container -->
        <?php
    }
    
endif;

function hybridmag_locate_search() {

    if ( false === get_theme_mod( 'hybridmag_show_search_onmenu', true ) ) {
        return;
    }

    if ( 'default' === hybridmag_get_header_layout() ) {
        add_action( 'hybridmag_header_inner_gadgets', 'hybridmag_search_box' );
    } else {
        add_action( 'hybridmag_after_primary_nav', 'hybridmag_search_box' );
        add_action( 'hybridmag_header_inner_right', 'hybridmag_search_box' );
    }
}
add_action( 'wp', 'hybridmag_locate_search' );

if ( ! function_exists( 'hybridmag_navigation_block' ) ) :

    function hybridmag_navigation_block() {

        $hybridmag_menu_width = get_theme_mod( 'hybridmag_menu_width', 'contained' );
        $hybridmag_menu_class = '';
        if ( 'contained' == $hybridmag_menu_width ) {
            $hybridmag_menu_class = 'hm-container';
        }

        $hybridmag_menu_inner_width = get_theme_mod( 'hybridmag_menu_inner_width', 'contained' );
        $hybridmag_menu_inner_class = '';
        if ( 'contained' == $hybridmag_menu_inner_width ) {
            $hybridmag_menu_inner_class = 'hm-container';
        }

        ?>
            <div class="hm-main-menu desktop-only <?php echo esc_attr( $hybridmag_menu_class ); ?>">
                <div class="hm-menu-wrapper <?php echo esc_attr( $hybridmag_menu_inner_class ); ?>">
                    <?php do_action( 'hybridmag_before_primary_nav' ); ?>

                    <nav id="site-navigation" class="main-navigation hm-menu">
                        <?php hybridmag_primary_nav(); ?>
                    </nav>

                    <?php do_action( 'hybridmag_after_primary_nav' ); ?>
                </div>
            </div>
        <?php
    }

endif;

if ( ! function_exists( 'hybridmag_navigation_inline' ) ) :

    function hybridmag_navigation_inline() {
        
        do_action( 'hybridmag_before_primary_nav' ); ?>

            <nav id="site-navigation" class="main-navigation hm-menu desktop-only">
                <?php hybridmag_primary_nav(); ?>
            </nav>

        <?php

        do_action( 'hybridmag_after_primary_nav' ); 

    }

endif;

if ( ! function_exists( 'hybridmag_slide_out_menu_toggle' ) ) : 

    function hybridmag_slide_out_menu_toggle() {
       ?>
            <button class="hm-slideout-toggle">
                <span class="hm-menu-bars"><?php echo hybridmag_the_icon_svg( 'menu-bars' ); ?></span>
                <span class="hm-menu-bars-close"><?php echo hybridmag_the_icon_svg( 'close' ); ?></span>
            </button>
        <?php 
    }
	
endif;

function hybridmag_slideout_menu_toggle_location() {

    if ( get_theme_mod( 'hybridmag_show_slideout_sb', false ) ) {

        $location = get_theme_mod( 'hybridmag_slideout_btn_loc', 'primary-menu' );

        if ( 'before-logo' === $location ) {
            add_action( 'hybridmag_before_header_main', 'hybridmag_slide_out_menu_toggle', 8 );
        } elseif ( 'top-bar' === $location ) {
            add_action( 'hybridmag_before_top_bar_main', 'hybridmag_slide_out_menu_toggle', 5 );
        } else {
            if ( 'default' === hybridmag_get_header_layout() ) {
                add_action( 'hybridmag_header_inner_gadgets', 'hybridmag_slide_out_menu_toggle', 15 );
            } else {
                add_action( 'hybridmag_after_primary_nav', 'hybridmag_slide_out_menu_toggle', 15 );
            }
        }

    }

}
add_action( 'wp', 'hybridmag_slideout_menu_toggle_location' );