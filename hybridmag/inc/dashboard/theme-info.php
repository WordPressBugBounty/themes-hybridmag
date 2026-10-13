<?php

function hybridmag_enqueue_admin_scripts( $hook ) {
    if ( $hook === 'post.php' || $hook === 'post-new.php' || 'toplevel_page_hybridmag-dashboard' == $hook ) {
        wp_register_style( 'hybridmag-admin-css', get_template_directory_uri() . '/inc/dashboard/css/admin.css', false, HYBRIDMAG_VERSION );
        wp_enqueue_style( 'hybridmag-admin-css' );
    }
    if ( 'toplevel_page_hybridmag-dashboard' === $hook ) {
        wp_enqueue_script( 'updates' );
        wp_enqueue_script( 'hybridmag-recommended-plugins', get_template_directory_uri() . '/inc/dashboard/js/plugin-install.js', array( 'jquery' ) );
        wp_localize_script( 'hybridmag-recommended-plugins', 'hybridmag_plugins_object',
        array(
            'installing' => esc_html__( 'Installing', 'hybridmag' ),
            'activating' => esc_html__( 'Activating', 'hybridmag' ),
            'error'      => esc_html__( 'Error', 'hybridmag' ),
            'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
        ));
    }
}
add_action( 'admin_enqueue_scripts', 'hybridmag_enqueue_admin_scripts' );

/**
 * Checks if the theme is white labeled.
 */
function hybridmag_is_white_label_active() {
    return apply_filters( 'hybridmag_is_theme_whitelabeled', false );
}

/**
 * Add admin notice when active theme
 */
function hybridmag_admin_notice() {
    ?>
    <div style="position: relative; padding: 20px; margin-bottom: 20px;" class="notice notice-success is-dismissible">
        <p style="font-size: 14px; margin: 0 0 10px;"><?php esc_html_e( 'Welcome to HybridMag! To get started with HybridMag please visit the theme admin page.', 'hybridmag' ); ?></p>
        <p><a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=hybridmag-dashboard' ) ); ?>" style="border-radius: 3px; padding: 12px 24px; font-size: 15px; font-weight: 500; line-height:20px;"><?php _e( 'HybridMag Admin Page', 'hybridmag' ) ?></a></p>
    </div>
    <?php
}


function hybridmag_activation_admin_notice(){
    global $pagenow;
    if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
        add_action( 'admin_notices', 'hybridmag_admin_notice' );
    }
}
add_action( 'load-themes.php',  'hybridmag_activation_admin_notice'  );

function hybridmag_admin_menu_icon_css() {
    ?>
    <style>
        #adminmenu a.toplevel_page_hybridmag-dashboard div.wp-menu-image img {
            width: 20px;
            height: 20px;
            object-fit: contain;
            padding: 7px 0 0;
            opacity: 1;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'hybridmag_admin_menu_icon_css' );


function hybridmag_add_themeinfo_page() {

    // Menu title can be displayed with recommended actions count.
    $theme_name = apply_filters( 'hybridmag_ti_wl_theme_name', 'HybridMag' );

    $icon_url = '';

    if ( ! hybridmag_is_white_label_active() ) {
        $icon_url = get_template_directory_uri() . '/inc/dashboard/images/hybridmag-icon.svg';
    }

    add_menu_page( esc_html( $theme_name ), esc_html( $theme_name ) , 'edit_theme_options', 'hybridmag-dashboard', 'hybridmag_themeinfo_page_render', $icon_url, 59 );
    
    add_submenu_page(
        'hybridmag-dashboard',
        esc_html__( 'Dashboard', 'hybridmag' ),
        esc_html__( 'Dashboard', 'hybridmag' ),
        'edit_theme_options',
        'hybridmag-dashboard'
    );

    // Customize.
    add_submenu_page(
        'hybridmag-dashboard',
        esc_html__( 'Customize', 'hybridmag' ),
        esc_html__( 'Customize', 'hybridmag' ),
        'edit_theme_options',
        'customize.php'
    );
    
    // Demos.
    add_submenu_page(
        'hybridmag-dashboard',
        esc_html__( 'Demos', 'hybridmag' ),
        esc_html__( 'Demos', 'hybridmag' ),
        'edit_theme_options',
        'admin.php?page=hybridmag-dashboard&tab=starter-templates'
    );
    
}
add_action( 'admin_menu', 'hybridmag_add_themeinfo_page' );

/**
 * Highlight Demos Submenu.
 */
function hybridmag_admin_menu_highlight( $submenu_file, $parent_file ) {

    if (
        isset( $_GET['page'], $_GET['tab'] ) &&
        'hybridmag-dashboard' === $_GET['page'] &&
        'starter-templates' === $_GET['tab']
    ) {
        $parent_file  = 'hybridmag-dashboard';
        $submenu_file = 'admin.php?page=hybridmag-dashboard&tab=starter-templates';
    }

    return $submenu_file;
}
add_filter( 'submenu_file', 'hybridmag_admin_menu_highlight', 10, 2 );

function hybridmag_themeinfo_page_render() { 
    
    $show_starter_templates = apply_filters( 'hybridmag_display_starter_templates', true );
    $page_classes = 'th-theme-info-page';
    if ( defined( 'HYBRIDMAG_PRO_VERSION' ) ) {
        $page_classes .= ' th-hybridmag-pro-active';
    }

    ?>

    <div class="<?php echo esc_attr( $page_classes ); ?>">
        <div class="th-admin-header-section">
            <div class="th-admin-container th-admin-header-inner">
                <?php 
                    $theme_name =  apply_filters( 'hybridmag_ti_wl_theme_name', 'HybridMag' );
                ?>
                <div class="th-admin-theme-name">
                    <?php 
                        if ( ! hybridmag_is_white_label_active() ) {
                            $logo_url = apply_filters( 'hybridmag_ti_wl_theme_logo_url', get_template_directory_uri() . '/inc/dashboard/images/hybridmag-logo.png' );
                            echo '<div class="th-admin-theme-logo">';
                                echo '<img src="'. esc_url( $logo_url ).'">';
                            echo '</div>';
                        } 
                    ?>
                    <?php echo esc_html( $theme_name ); ?>
                </div>
                <div class="th-admin-theme-info">
                    <div class="th-admin-theme-version">
                        <?php echo esc_html__( 'Theme Version', 'hybridmag' ); ?>
                        <?php echo esc_html( wp_get_theme()->get( 'Version' ) ); ?>
                    </div>
                    <?php if ( defined( 'HYBRIDMAG_PRO_VERSION' ) ) { ?>
                        <div class="th-admin-pro-version">
                            <?php echo esc_html__( 'PRO Plugin Version', 'hybridmag' ); ?>
                            <?php echo esc_html( HYBRIDMAG_PRO_VERSION ); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="th-hm-admin-navbar">
            <div class="th-admin-container">
                <div class="th-nav-tab-wrapper">
                    <a class="th-nav-tab <?php if ( $_GET['page'] == 'hybridmag-dashboard' && ! isset( $_GET['tab'] ) ) echo 'th-nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'hybridmag-dashboard' ), 'admin.php' ) ) ); ?>">
                        <?php esc_html_e( 'Welcome', 'hybridmag' ); ?>
                    </a>
                    <?php if ( $show_starter_templates ) : ?>
                        <a class="th-nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'starter-templates' ) echo 'th-nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'hybridmag-dashboard', 'tab' => 'starter-templates' ), 'admin.php' ) ) ); ?>">
                            <?php esc_html_e( 'Starter Templates', 'hybridmag' ); ?>
                        </a>
                    <?php endif; ?>
                    <a class="th-nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'settings' ) echo 'th-nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'hybridmag-dashboard', 'tab' => 'settings' ), 'admin.php' ) ) ); ?>">
                        <?php esc_html_e( 'Settings', 'hybridmag' ); ?>
                    </a>
                    <?php if ( ! defined( 'HYBRIDMAG_PRO_VERSION' ) ) { ?>
                        <a class="th-nav-tab menu-get-pro-link" href="https://themezhut.com/themes/hybridmag-pro/?utm_source=hybridmag-theme&utm_medium=dashboard&utm_campaign=getpronow&utm_content=tabmenu-pro-link" target="_blank" rel="noopener noreferrer">
                            <?php 
                                esc_html_e( 'Get Full Access', 'hybridmag' ); 
                                hybridmag_the_icon_svg( 'newtab' ); 
                            ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="th-nav-tab-inner">

            <?php

                $current_tab = ! empty( $_GET['tab'] ) ? sanitize_title( $_GET['tab'] ) : '';

                if ( $current_tab === 'starter-templates' && $show_starter_templates ) {
                    echo '<div class="th-admin-container">';
                        hybridmag_starter_templates();
                    echo '</div>';
                } elseif ( $current_tab === 'settings' ) {
                    hybridmag_admin_settings_page();
                } else {
                    hybridmag_admin_welcome_page();
                }
            
            ?>

        </div><!-- .th-nav-tab-inner -->
        


    </div><!-- .th-theme-info-page -->

    <?php

}

function hybridmag_starter_templates() {
    if ( function_exists( 'bnmbt_display_demo_showcase' ) ) {
        bnmbt_display_demo_showcase();
    } else {
        hybridmag_plugin_for_demo_install();
    }
}

/**
 * Recommend Post Blocks & Tools plugin to install demos.
 */
function hybridmag_plugin_for_demo_install() {

    $slug = 'bnm-blocks';

    $is_installed = hybridmag_is_plugin_installed( $slug );
    $is_activated = function_exists( 'bnmbt_display_demo_showcase' );
    $plugin_path  = hybridmag_get_plugin_basename_from_slug( $slug );

    if ( ! $is_installed ) {

        $plugin_install_url = add_query_arg(
            array(
                'action' => 'install-plugin',
                'plugin' => $slug,
            ),
            self_admin_url( 'update.php' )
        );
        $plugin_install_url = wp_nonce_url( $plugin_install_url, 'install-plugin_' . $slug );
    
        $button_html = sprintf('<a class="themezhut-plugin-install install-now button-secondary button" data-slug="%1$s" href="%2$s" aria-label="%3$s" data-name="%4$s">%5$s</a>',
            esc_attr( $slug ),
            esc_url( $plugin_install_url ),
            /* translators: %s: plugin name */
            esc_html__( 'Install Post Blocks & Tools Plugin Now', 'hybridmag' ),
            esc_html__( 'Post Blocks & Tools', 'hybridmag' ),
            esc_html__( 'Install and activate', 'hybridmag' )
        );

    } elseif ( $is_installed && ! $is_activated ) {
        
        $plugin_activate_link = add_query_arg(
            array(
                'action'        => 'activate',
                'plugin'        => rawurlencode( $plugin_path ),
                'plugin_status' => 'all',
                'paged'         => '1',
                '_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin_path ),
            ), self_admin_url( 'plugins.php' )
        );

        global $pagenow;
        if ( "themes.php" != $pagenow && is_admin() ) {
            $activate_string = esc_html__( 'Activate', 'hybridmag' );
        } else {
            $activate_string = esc_html__( 'Activate Plugin', 'hybridmag' );
        }

        $button_html = sprintf('<a class="themezhut-plugin-activate activate-now button-primary button" data-slug="%1$s" href="%2$s" aria-label="%3$s" data-name="%4$s">%5$s</a>',
            esc_attr( $slug ),
            esc_url( $plugin_activate_link ),
            /* translators: %s: plugin name */
            esc_html__( 'Activate Post Blocks & Tools Plugin Now', 'hybridmag' ),
            esc_html__( 'Post Blocks & Tools', 'hybridmag' ),
            $activate_string
        );

    }
    ?>

    <div class="th-demo-plugin-notice">
        <p>
        <?php 
            echo esc_html__( 'To import demos, simply install and activate our "Post Blocks & Tools" plugin. Click to install and activate.', 'hybridmag' ); 
        ?>
        </p>
        <?php echo $button_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>
    </div>

    <?php

}

function hybridmag_get_plugin_basename_from_slug( $slug ) {
    $keys = array_keys( hybridmag_get_installed_plugins() );
    foreach ( $keys as $key ) {
        if ( preg_match( '|^' . $slug . '/|', $key ) ) {
            return $key;
        }
    }
    return $slug;
}

function hybridmag_get_installed_plugins() {
    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    return get_plugins();
}

function hybridmag_is_plugin_installed( $slug ) {
    $installed_plugins = hybridmag_get_installed_plugins(); // Retrieve a list of all installed plugins (WP cached).
    $file_path         = hybridmag_get_plugin_basename_from_slug( $slug );
    return ( ! empty( $installed_plugins[ $file_path ] ) );
}

function hybridmag_admin_welcome_page() {
    ?>
    <div class="th-admin-container">
        <div class="th-theme-details-page">        
            <div class="th-admin-theme-content">
                <?php if ( ! hybridmag_is_white_label_active() ) : ?>

                    <div class="th-theme-infoboxes">

                        <div class="th-theme-page-infobox">
                            <div class="th-theme-infobox-content">
                                <h3><?php esc_html_e( 'Theme Info', 'hybridmag' ); ?></h3>
                                <p><?php esc_html_e( 'Learn more about the HybridMag theme.', 'hybridmag' ) ?></p>
                                <a class="button" href="<?php echo esc_url( 'https://themezhut.com/themes/hybridmag/?utm_source=hybridmag-theme&utm_medium=dashboard&utm_campaign=awareness&utm_content=theme-info' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Theme Details','hybridmag' ); ?></a>
                            </div>
                        </div>

                        <div class="th-theme-page-infobox">
                            <div class="th-theme-infobox-content">
                                <h3><?php esc_html_e( 'Theme Documentation', 'hybridmag' ); ?></h3>
                                <p><?php esc_html_e( 'Learn how to set up and customize HybridMag.', 'hybridmag' ) ?></p>
                                <a class="button" href="<?php echo esc_url( 'https://themezhut.com/hybridmag-wordpress-theme-documentation/?utm_source=hybridmag-theme&utm_medium=dashboard&utm_campaign=awareness&utm_content=documentation' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Read the documentation','hybridmag' ); ?></a>
                            </div>
                        </div>

                        <div class="th-theme-page-infobox">
                            <div class="th-theme-infobox-content">
                            <h3><?php esc_html_e( 'Theme Customizer', 'hybridmag' ); ?></h3>
                            <p><?php esc_html_e( 'All HybridMag theme settings are available in the Customizer. Start customizing your website with the Customizer.', 'hybridmag' ) ?></p>
                            <a class="button" href="<?php echo esc_url( admin_url( '/customize.php' ) ); ?>" target="_blank"><?php esc_html_e( 'Go to customizer','hybridmag' ); ?></a>
                            </div>
                        </div>

                        <div class="th-theme-page-infobox">
                            <div class="th-theme-infobox-content">
                                <h3><?php esc_html_e( 'Theme Demos', 'hybridmag' ); ?></h3>
                                <p><?php esc_html_e( 'See the starter sites collection.', 'hybridmag' ) ?></p>
                                <a class="button" href="<?php echo esc_url( 'https://themezhutdemos.com/hybridmag/' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Theme Demos','hybridmag' ); ?></a>    
                            </div>
                        </div>

                        <div class="th-theme-page-infobox">
                            <div class="th-theme-infobox-content">
                                <h3><?php esc_html_e( 'Demo Installation', 'hybridmag' ); ?></h3>
                                <p><?php esc_html_e( 'Learn about starter sites.', 'hybridmag' ) ?></p>
                                <a class="button" href="<?php echo esc_url( 'https://themezhut.com/hybridmag-wordpress-theme-documentation/?utm_source=hybridmag-theme&utm_medium=dashboard&utm_campaign=awareness&utm_content=demo-docs#demos' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Read the documentation','hybridmag' ); ?></a>    
                            </div>
                        </div>

                    </div><!-- .th-theme-infoboxes -->

                <?php endif; ?>

                <?php 
                    if ( ! hybridmag_is_white_label_active() ) {
                        hybridmag_pro_features_info();
                    } 
                ?>

            </div><!-- .th-admin-theme-content -->

            <?php if ( defined( 'HYBRIDMAG_PRO_VERSION' ) ) : ?>
                <div class="th-admin-theme-sidebar">

                    <?php do_action( 'hybridmag_admin_page_before_sidebar' ); ?>

                    <?php do_action( 'hybridmag_admin_page_sidebar' ); ?>

                    <?php do_action( 'hybridmag_admin_page_after_sidebar' ); ?>
                    
                </div><!-- .th-admin-theme-sidebar -->
            <?php endif; ?>
        </div>
    </div>

    <?php
}

function hybridmag_pro_features_info() {
    ?>
    <div class="th-admin-pro-feature-box">
        <div class="th-admin-pro-features-header">
            <div class="th-admin-pro-header-wrap">
                <h3 class="th-admin-pro-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="1em" height="1em"><!--!Font Awesome Free v7.3.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M232.5 136L320 229L407.5 136L232.5 136zM447.9 163.1L375.6 240L504.6 240L448 163.1zM497.9 288L142.1 288L320 484.3L497.9 288zM135.5 240L264.5 240L192.2 163.1L135.6 240zM569.8 280.1L337.8 536.1C333.3 541.1 326.8 544 320 544C313.2 544 306.8 541.1 302.2 536.1L70.2 280.1C62.5 271.6 61.9 258.9 68.7 249.7L180.7 97.7C185.2 91.6 192.4 87.9 200 87.9L440 87.9C447.6 87.9 454.8 91.5 459.3 97.7L571.3 249.7C578.1 258.9 577.4 271.6 569.8 280.1z"/></svg>
                    <?php echo esc_html__( 'Premium features comes with the HybridMag Pro', 'hybridmag' ); ?>
                </h3>
            </div>
            <?php if ( ! defined( 'HYBRIDMAG_PRO_VERSION' ) ) { ?>
                <a href="https://themezhut.com/themes/hybridmag-pro/?utm_source=hybridmag-theme&utm_medium=dashboard&utm_campaign=getpronow&utm_content=pro-details-box-button" class="button hm-admin-go-pro" target="_blank" rel="noopener noreferrer">
                    <?php 
                        echo esc_html__( 'Get HybridMag Pro', 'hybridmag' ); 
                        hybridmag_the_icon_svg( 'newtab' );
                    ?>
                </a>
            <?php } ?>
        </div>

        <div class="th-pro-features-container">
            <div class="th-pro-feature-box">
                <div class="th-pro-feature-title-wrap">
                    <?php hybridmag_the_icon_svg( 'magic-wand' ); ?>
                    <h4 class="th-pro-feature-title"><?php echo esc_html__( 'Advanced Design Controls', 'hybridmag' ); ?></h4>
                    <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
                </div>
                <p class="th-pro-feature-desc"><?php echo esc_html__( 'Fine-tune typography, colors, layouts, and more to create a site that feels uniquely yours.', 'hybridmag' ); ?></p>
            </div>
            <div class="th-pro-feature-box">
                <div class="th-pro-feature-title-wrap">
                    <?php hybridmag_the_icon_svg( 'layers' ); ?>
                    <h4 class="th-pro-feature-title"><?php echo esc_html__( 'Advanced Post Blocks & Tools', 'hybridmag' ); ?></h4>
                    <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
                </div>
                <p class="th-pro-feature-desc"><?php echo esc_html__( 'Take your Post Blocks & Tools blocks further with AJAX-powered filtering and seamless previous/next navigation for a faster, more engaging content experience.', 'hybridmag' ); ?></p>
            </div>
            <div class="th-pro-feature-box">
                <div class="th-pro-feature-title-wrap">
                    <?php hybridmag_the_icon_svg( 'spinner' ); ?>
                    <h4 class="th-pro-feature-title"><?php echo esc_html__( 'Auto-Loading Single Posts', 'hybridmag' ); ?></h4>
                    <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
                </div>
                <p class="th-pro-feature-desc"><?php echo esc_html__( 'Keep readers exploring by automatically loading the next article when they reach the end of a post.', 'hybridmag' ); ?></p>
            </div>
            <div class="th-pro-feature-box">
                <div class="th-pro-feature-title-wrap">
                    <?php hybridmag_the_icon_svg( 'play' ); ?>
                    <h4 class="th-pro-feature-title"><?php echo esc_html__( 'Video Block', 'hybridmag' ); ?></h4>
                    <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
                </div>
                <p class="th-pro-feature-desc"><?php echo esc_html__( 'Showcase your YouTube playlists with an engaging grid layout designed for your content.', 'hybridmag' ); ?></p>
            </div>
            <div class="th-pro-feature-box">
                <div class="th-pro-feature-title-wrap">
                    <?php hybridmag_the_icon_svg( 'scroll' ); ?>
                    <h4 class="th-pro-feature-title"><?php echo esc_html__( 'Infinite Scroll', 'hybridmag' ); ?></h4>
                    <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
                </div>
                <p class="th-pro-feature-desc"><?php echo esc_html__( 'Keep readers discovering more content by automatically loading posts as they scroll.', 'hybridmag' ); ?></p>
            </div>
            <div class="th-pro-feature-box">
                <div class="th-pro-feature-title-wrap">
                    <?php hybridmag_the_icon_svg( 'cart' ); ?>
                    <h4 class="th-pro-feature-title"><?php echo esc_html__( 'WooCommerce Support', 'hybridmag' ); ?></h4>
                    <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
                </div>
                <p class="th-pro-feature-desc"><?php echo esc_html__( 'Turn your blog or magazine into an online store with enhanced WooCommerce layouts and controls.', 'hybridmag' ); ?></p>
            </div> 
            <div class="th-pro-feature-box">
                <div class="th-pro-feature-title-wrap">
                    <?php hybridmag_the_icon_svg( 'stamp' ); ?>
                    <h4 class="th-pro-feature-title"><?php echo esc_html__( 'White Label', 'hybridmag' ); ?></h4>
                    <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
                </div>
                <p class="th-pro-feature-desc"><?php echo esc_html__( 'Remove HybridMag branding and rebrand it as your own for client websites.', 'hybridmag' ); ?></p>
            </div>
            <div class="th-pro-feature-box">
                <div class="th-pro-feature-title-wrap">
                    <?php hybridmag_the_icon_svg( 'mail', 'social' ); ?>
                    <h4 class="th-pro-feature-title"><?php echo esc_html__( 'Priority Support', 'hybridmag' ); ?></h4>
                    <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
                </div>
                <p class="th-pro-feature-desc"><?php echo esc_html__( 'Get fast and reliable assistance directly from the developer.', 'hybridmag' ); ?></p>
            </div>                          
        </div><!-- .th-pro-features-container -->
    </div><!-- .th-admin-pro-feature-box -->

    <?php

}

/**
 * Render settings page.
 */
function hybridmag_admin_settings_page() {
    ?>
    <div class="th-admin-container th-admin-setting-page">
        <div class="th-admin-theme-content">
            <div class="th-admin-theme-settings">

                <form method="post" action="">

                <?php

                    wp_nonce_field(
                        'hybridmag_white_label_save',
                        'hybridmag_white_label_nonce'
                    );

                    do_action( 'hybridmag_white_label_settings' );

                ?>

                </form>

            </div>

            <?php 
                if ( ! hybridmag_is_white_label_active() && ! defined( 'HYBRIDMAG_PRO_VERSION' ) ) {
                    hybridmag_pro_features_info();
                } 
            ?>
        </div>      
    </div>
    <?php
}

/**
 * HybridMag Settings Placeholder.
 */
function hybridmag_white_label_settings_placeholder() { 
    ?>
    <div class="th-admin-theme-wl-setting-header">
        <h3 class="th-admin-theme-setting-section-title"><?php echo esc_html__( 'White Label Settings', 'hybridmag' ); ?></h3>
        <a href="https://themezhut.com/themes/hybridmag-pro/?utm_source=hybridmag-theme&utm_medium=dashboard&utm_campaign=getpronow&utm_content=whitelabel-box" class="button hm-admin-go-pro" target="_blank" rel="noopener noreferrer">
            <?php 
                echo esc_html__( 'Get HybridMag Pro', 'hybridmag' ); 
                hybridmag_the_icon_svg( 'newtab' );
            ?>
        </a>
    </div>

    <h4 class="th-admin-theme-setting-title">
        <?php echo esc_html__( 'Agency Branding', 'hybridmag' ); ?>
        <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
    </h4>
    <div class="th-admin-setting">
        <label for="agency_name" class="th-admin-setting-label"><?php echo esc_html__( 'Agency Name', 'hybridmag' ); ?></label>
        <input name="agency_name" class="th-admin-setting-input" type="text" disabled>
    </div>
    <div class="th-admin-setting">
        <label for="agency_url" class="th-admin-setting-label"><?php echo esc_html__( 'Agency URL', 'hybridmag' ); ?></label>
        <input name="agency_url" class="th-admin-setting-input" type="text" disabled>
    </div>
    
    <br/>

    <h4 class="th-admin-theme-setting-title">
        <?php echo esc_html__( 'Theme Branding', 'hybridmag' ); ?>
        <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
    </h4>
    <div class="th-admin-setting">
        <label for="theme_name" class="th-admin-setting-label"><?php echo esc_html__( 'Theme Name', 'hybridmag' ); ?></label>
        <input name="theme_name" class="th-admin-setting-input" type="text" disabled>
    </div>
    <div class="th-admin-setting">
        <label for="theme_description" class="th-admin-setting-label"><?php echo esc_html__( 'Theme Description', 'hybridmag' ); ?></label>
        <input name="theme_description" class="th-admin-setting-input" type="text" disabled>
    </div>
    <div class="th-admin-setting">
        <label for="theme_screenshot_url" class="th-admin-setting-label"><?php echo esc_html__( 'Theme Screenshot URL', 'hybridmag' ); ?></label>
        <input name="theme_screenshot_url" class="th-admin-setting-input" type="text" disabled>
    </div>

    <br/>

    <h4 class="th-admin-theme-setting-title">
        <?php echo esc_html__( 'Plugin Branding', 'hybridmag' ); ?>
        <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
    </h4>
    <div class="th-admin-setting">
        <label for="plugin_name" class="th-admin-setting-label"><?php echo esc_html__( 'Plugin Name', 'hybridmag' ); ?></label>
        <input name="plugin_name" id="plugin_name" class="th-admin-setting-input" type="text" disabled>
    </div>
    <div class="th-admin-setting">
        <label for="plugin_description" class="th-admin-setting-label"><?php echo esc_html__( 'Plugin Description', 'hybridmag' ); ?></label>
        <input name="plugin_description" id="plugin_description" class="th-admin-setting-input" type="text" disabled>
    </div>     

    <br/>

    <h4 class="th-admin-theme-setting-title">
        <?php echo esc_html__( 'Admin Visibility', 'hybridmag' ); ?>
        <span class="th-admin-pro-label"><?php echo esc_html__( 'PRO', 'hybridmag' ); ?></span>
    </h4>

    <div class="th-admin-setting">
        <label>
            <input
                name="hide_starter_sites"
                type="checkbox"
                value="1"
                disabled
            >
            <?php echo esc_html__( 'Hide Starter Templates Library.', 'hybridmag' ); ?>
        </label>
    </div>
    <div class="th-admin-setting">
        <label>
            <input
                name="hide_license_section"
                type="checkbox"
                value="1"
                disabled
            >
            <?php echo esc_html__( 'Enable License Hiding.', 'hybridmag' ); ?>
        </label>

    </div>

<br/>

    <button type="submit" id="submit" name="submit" class="button button-primary th-admin-setting-save-btn" disabled><?php echo esc_html__( 'Save Changes', 'hybridmag' ); ?></button>

    <?php
}
if ( ! defined( 'HYBRIDMAG_PRO_VERSION' ) ) {
    add_action( 'hybridmag_white_label_settings', 'hybridmag_white_label_settings_placeholder' );
}