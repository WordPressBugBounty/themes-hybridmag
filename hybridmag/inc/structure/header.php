<?php

/**
 * Header Layout.
 */
function hybridmag_get_header_layout() {
    return get_theme_mod( 'hybridmag_header_layout', 'default' );
}

/**
 * Header Top Bar
 */
function hybridmag_header_top_bar() {
    if ( has_nav_menu( 'secondary' ) || ( has_nav_menu( 'social' ) && true === get_theme_mod( 'hybridmag_display_social_topbar', false ) ) ) : ?>
        <div class="hm-top-bar desktop-only">
            <div class="top-bar-inner hm-container">
                
                <?php do_action( 'hybridmag_before_top_bar_main' ); ?>

                <?php do_action( 'hybridmag_top_bar_main' ); ?>

                <?php do_action( 'hybridmag_after_top_bar_main' ); ?>

            </div><!-- .top-bar-inner .hm-container -->
        </div><!-- .hm-top-bar -->
    <?php endif; 
}
add_action( 'hybridmag_header_top', 'hybridmag_header_top_bar' );

/**
 * Header top bar menu.
 */
function hybridmag_header_top_menu() {
    if ( has_nav_menu( 'secondary') ) : ?>
        <nav class="secondary-menu hm-menu" area-label="<?php esc_attr_e( 'Secondary Menu', 'hybridmag' ); ?>">
            <?php hybridmag_secondary_nav(); ?>
        </nav>
    <?php endif; 
}
add_action( 'hybridmag_top_bar_main', 'hybridmag_header_top_menu' );

/**
 * Header Image.
 */
function hybridmag_header_image_location() {
    $hybridmag_header_image_loc = get_theme_mod( 'hybridmag_header_image_location', 'before-header-inner' );
    if ( 'before-header-inner' === $hybridmag_header_image_loc ) {
        add_action( 'hybridmag_header_top', 'hybridmag_header_image', 15 );
    } elseif ( 'after-header-inner' === $hybridmag_header_image_loc ) {
        add_action( 'hybridmag_header_bottom', 'hybridmag_header_image', 5 );
    } elseif ( 'before-site-header' === $hybridmag_header_image_loc ) {
        add_action( 'hybridmag_before_header', 'hybridmag_header_image', 15 );
    } elseif ( 'after-site-header' === $hybridmag_header_image_loc ) {
        add_action( 'hybridmag_after_header', 'hybridmag_header_image', 5 );
    }
}
add_action( 'wp', 'hybridmag_header_image_location' );


/**
 * Sidebar header after.
 */
function hybridmag_sidebar_after_header() {
    if ( is_active_sidebar( 'header-3' ) ) :
    ?>
		<div class="hm-sidebar-header-after">
			<div class="hm-container">
				<?php dynamic_sidebar( 'header-3' ); ?>
			</div>
		</div>
    <?php
    endif;
}
add_action( 'hybridmag_after_header', 'hybridmag_sidebar_after_header' );

/**
 * Get Header Template Part.
 */
function hybridmag_header_template() {
    get_template_part( 'template-parts/header/header' );
}
add_action( 'hybridmag_header', 'hybridmag_header_template' );

/**
 * Header Widgets area
 */
function hybridmag_header_inner_gadgets() {

    if ( has_action( 'hybridmag_header_inner_gadgets' ) ) : 
    ?>
        <div class="hm-header-gadgets">
            <?php 
                do_action( 'hybridmag_header_inner_gadgets' ); 
            ?>
        </div>
    <?php
    endif;
}
add_action( 'hybridmag_after_header_main', 'hybridmag_header_inner_gadgets' );


if ( ! function_exists( 'hybridmag_header_sidebar' ) ) :
/**
 * Header Sidebar
 */
function hybridmag_header_sidebar() {
    if ( is_active_sidebar( 'header-2' ) ) :
    ?>
        <div class="hm-header-sidebar">
            <div class="hm-header-sidebar-inner">
                <?php dynamic_sidebar( 'header-2' ); ?>
            </div>
        </div>
    <?php
    endif;
}
add_action( 'hybridmag_after_header_main', 'hybridmag_header_sidebar', 20 );
endif;

/**
 * Container Header inner right side
 */
function hybridmag_header_inner_right_container() {
    
    ?>
        <div class="hm-header-inner-right">
            <?php do_action( 'hybridmag_header_inner_right' ); ?>
        </div>
    <?php
    
}
add_action( 'hybridmag_after_header_main', 'hybridmag_header_inner_right_container', 15 );

/**
 * Container Header inner left side.
 * @To do decide priority
 */
function hybridmag_header_inner_left_container() {

    ?>

        <div class="hm-header-inner-left">
            <?php do_action( 'hybridmag_header_inner_left' ); ?>
        </div>

    <?php
    
}
add_action( 'hybridmag_before_header_main', 'hybridmag_header_inner_left_container', 5 );

/**
 * Mobile Menu toggle.
 */
function hybridmag_mobile_menu_toggle() {
    ?>
        <button class="hm-mobile-menu-toggle">
            <span class="screen-reader-text"><?php esc_html_e( 'Main Menu', 'hybridmag' ); ?></span>
            <?php hybridmag_the_icon_svg( 'menu-bars' ); ?>
        </button>
    <?php
}
add_action( 'hybridmag_after_header_main', 'hybridmag_mobile_menu_toggle', 20 );

if ( ! function_exists( 'hybridmag_light_dark_toggle' ) ) :
    /**
     * Dark mode toggle.
     */
    function hybridmag_light_dark_toggle() {
        ?>
            <div class="hm-light-dark-switch">
                <button class="hm-light-dark-toggle">
                    <span class="hm-light-icon">
                        <?php hybridmag_the_icon_svg( 'sun1' ); ?>
                    </span>
                    <span class="hm-dark-icon">
                        <?php hybridmag_the_icon_svg( 'moon' ); ?>
                    </span>
                </button>
            </div>
        <?php
    }
endif;


/**
 * Place dark light toggle.
 */
function hybridmag_locate_light_dark_toggle() {
    if ( false === get_theme_mod( 'hybridmag_show_dark_toggle', true ) ) {
        return;
    }

    $header_layout = hybridmag_get_header_layout();

    if ( 'large' == $header_layout ) { 
        add_action( 'hybridmag_after_primary_nav', 'hybridmag_light_dark_toggle', 8 );
    } elseif ( 'default' == $header_layout ) {
        add_action( 'hybridmag_header_inner_gadgets', 'hybridmag_light_dark_toggle' );
    }

    add_action( 'hybridmag_mobile_sidebar_bottom', 'hybridmag_light_dark_toggle' );
}
add_action( 'wp', 'hybridmag_locate_light_dark_toggle' );

if ( ! function_exists( 'hybridmag_header_cta' ) ) :
    /**
     * Header CTA action button.
     */
    function hybridmag_header_cta() {
        $cta_txt = get_theme_mod( 'hybridmag_header_cta_txt', esc_html__( 'Subscribe', 'hybridmag' ) );
        $cta_url = get_theme_mod( 'hybridmag_header_cta_url', '' );
        if ( ! $cta_url ) {
            $cta_url = "#";
        }
        $cta_target = get_theme_mod( 'hybridmag_header_cta_target', false );

        ?>
            <a href="<?php echo esc_url( $cta_url ); ?>" class="hm-cta-btn"
                <?php if ( true == $cta_target ) {
                    echo 'target="_blank"';
                } ?>
            ><?php echo esc_html( $cta_txt ); ?></a>

        <?php
    }
endif;

/**
 * Place the cta button.
 */
function hybridmag_locate_cta_btn() {

    if ( false === get_theme_mod( 'hybridmag_show_header_cta', false ) ) {
        return;
    }

    $header_layout = hybridmag_get_header_layout();
    if ( 'large' == $header_layout ) {
        $logo_align = get_theme_mod( 'hybridmag_logo_align', 'left' );

        if ( 'left' === $logo_align || 'center' === $logo_align ) {
            add_action( 'hybridmag_header_inner_right', 'hybridmag_header_cta' );
        } elseif ( 'right' === $logo_align ) {
            add_action( 'hybridmag_header_inner_left', 'hybridmag_header_cta' );
        }
    } elseif ( 'default' == $header_layout ) {
        add_action( 'hybridmag_header_inner_gadgets', 'hybridmag_header_cta', 8 );
    }
    
}
add_action( 'wp', 'hybridmag_locate_cta_btn' );

/**
 * Place social menu based on the header settings.
 */
function hybridmag_locate_social_menu() {
    $header_layout = hybridmag_get_header_layout();
    if ( 'large' == $header_layout ) {
        $logo_align = get_theme_mod( 'hybridmag_logo_align', 'left' );

        if ( true == get_theme_mod( 'hybridmag_social_beside_logo', false ) ) {
            if ( 'left' === $logo_align ) {
                add_action( 'hybridmag_header_inner_right', 'hybridmag_social_nav', 15 );
            } elseif ( 'center' === $logo_align || 'right' === $logo_align ) {
                add_action( 'hybridmag_header_inner_left', 'hybridmag_social_nav' );
            }
        }

        if ( true === get_theme_mod( 'hybridmag_social_beside_pmenu', false ) ) {
            add_action( 'hybridmag_after_primary_nav', 'hybridmag_social_nav', 5 );
        }
        
    } elseif ( 'default' == $header_layout ) {
        if ( true === get_theme_mod( 'hybridmag_social_beside_pmenu', false ) ) {
            add_action( 'hybridmag_header_inner_gadgets', 'hybridmag_social_nav', 5 );
        }
    }

    if ( true === get_theme_mod( 'hybridmag_display_social_topbar', false ) ) {
        add_action( 'hybridmag_after_top_bar_main', 'hybridmag_social_nav' );
    }

}   
add_action( 'wp', 'hybridmag_locate_social_menu' );

/**
 * Site branding.
 */
if ( ! function_exists( 'hybridmag_site_title' ) ) : 

	function hybridmag_site_title() {

		$hybridmag_site_title = get_bloginfo( 'title' );
		$hybridmag_description = get_bloginfo( 'description', 'display' );

		$hide_title = ( get_theme_mod( 'hybridmag_hide_site_title', false ) || '' == $hybridmag_site_title ) ? true : false;
		$hide_tagline = ( get_theme_mod( 'hybridmag_hide_site_tagline', true ) || '' == $hybridmag_description ) ? true : false;

		?>
		<div class="site-branding-container">
			<?php if ( has_custom_logo() ) : ?>
				<div class="site-logo hm-light-mode-logo">
					<?php the_custom_logo(); ?>
				</div>
			<?php endif; ?>
            <?php if ( hybridmag_has_dark_mode_logo() ) : ?>
                <div class="site-logo hm-dark-mode-logo">
                    <?php hybridmag_the_dark_mode_logo(); ?>
                </div>
            <?php endif; ?>

			<div class="site-branding">
				<?php

				if ( ! $hide_title ) :

					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					else :
						?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					endif;

				endif;
				
				if ( ! $hide_tagline ) :
					?>
					<p class="site-description"><?php echo $hybridmag_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php endif; ?>
			</div><!-- .site-branding -->
		</div><!-- .site-branding-container -->
		<?php
	}
    add_action( 'hybridmag_before_header_main', 'hybridmag_site_title' );

endif;