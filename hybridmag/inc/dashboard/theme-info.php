<?php

function hybridmag_enqueue_admin_scripts( $hook ) {
    if ( $hook === 'post.php' || $hook === 'post-new.php' || 'appearance_page_hybridmag' == $hook ) {
        wp_register_style( 'hybridmag-admin-css', get_template_directory_uri() . '/inc/dashboard/css/admin.css', false, HYBRIDMAG_VERSION );
        wp_enqueue_style( 'hybridmag-admin-css' );
    }
    if ( 'appearance_page_hybridmag' === $hook ) {
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
 * Add admin notice when active theme
 */
function hybridmag_admin_notice() {
    ?>
    <div style="position: relative; background: #fff; padding: 20px; border-left: 4px solid #046bd2; margin-bottom: 20px;" class="notice notice-succes is-dismissible">
        <p style="font-size: 14px; margin: 0 0 10px;"><?php esc_html_e( 'Welcome to HybridMag! To get started with HybridMag please visit the theme admin page.', 'hybridmag' ); ?></p>
        <p><a class="button" href="<?php echo esc_url( admin_url( 'themes.php?page=hybridmag' ) ); ?>" style="border-radius: 3px; padding: 12px 24px; background: #046bd2; color: #fff; font-size: 15px; font-weight: 500; line-height:20px;"><?php _e( 'HybridMag Admin Page', 'hybridmag' ) ?></a></p>
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


function hybridmag_add_themeinfo_page() {

    // Menu title can be displayed with recommended actions count.
    $menu_title = esc_html__( 'HybridMag Theme', 'hybridmag' );

    add_theme_page( esc_html__( 'HybridMag Theme', 'hybridmag' ), $menu_title , 'edit_theme_options', 'hybridmag', 'hybridmag_themeinfo_page_render', 1 );

}
add_action( 'admin_menu', 'hybridmag_add_themeinfo_page' );

function hybridmag_themeinfo_page_render() { ?>

    <div class="th-theme-info-page">

        <div class="th-admin-header-section">
            <div class="th-admin-container th-admin-header-inner">
                <div class="th-admin-theme-name"><?php echo esc_html__( 'HybridMag', 'hybridmag'); ?></div>
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
                    <a class="th-nav-tab <?php if ( $_GET['page'] == 'hybridmag' && ! isset( $_GET['tab'] ) ) echo 'th-nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'hybridmag' ), 'themes.php' ) ) ); ?>">
                        <?php esc_html_e( 'Welcome', 'hybridmag' ); ?>
                    </a>
                    <a class="th-nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'starter-templates' ) echo 'th-nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'hybridmag', 'tab' => 'starter-templates' ), 'themes.php' ) ) ); ?>">
                        <?php esc_html_e( 'Starter Templates', 'hybridmag' ); ?>
                    </a>
                    <?php if ( ! defined( 'HYBRIDMAG_PRO_VERSION' ) ) { ?>
                        <a class="th-nav-tab menu-get-pro-link" href="https://themezhut.com/themes/hybridmag-pro/" target="_blank">
                            <?php 
                                esc_html_e( 'Get HybridMag Pro', 'hybridmag' ); 
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

                if ( $current_tab == 'starter-templates' ) {
                    echo '<div class="th-admin-container">';
                        hybridmag_starter_templates();
                    echo '</div>';
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
                <div class="th-admin-theme-settings">
                    <div class="th-admin-theme-setting-header">
                        <h3 class="th-admin-theme-setting-title"><?php echo esc_html__( 'Get Started', 'hybridmag' ); ?></h3>
                        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button hm-admin-go-customizer"><?php echo esc_html__( 'Go to Customizer', 'hybridmag' ); ?></a>
                    </div>
                    
                    <div class="th-admin-theme-setting-links">
                        <div class="th-admin-theme-setting-box">
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ); ?>" target="_blank">
                                <div class="th-admin-qsc-name"><?php echo esc_html__( 'Upload Logo', 'hybridmag' ); ?></div>
                                <div class="th-admin-qsc-desc"><?php echo esc_html__( 'Site Branding', 'hybridmag' ); ?></div>
                            </a>
                        </div>

                        <div class="th-admin-theme-setting-box">
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=hybridmag_panel_header' ) ); ?>" target="_blank">
                                <div class="th-admin-qsc-name"><?php echo esc_html__( 'Header Options', 'hybridmag' ); ?></div>
                                <div class="th-admin-qsc-desc"><?php echo esc_html__( 'Header Layout, Menu Options, Social Menu, Top Bar', 'hybridmag' ); ?></div>
                            </a>
                        </div>

                        <div class="th-admin-theme-setting-box">
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=hybridmag_colors_panel' ) ); ?>" target="_blank">
                                <div class="th-admin-qsc-name"><?php echo esc_html__( 'Set Colors', 'hybridmag' ); ?></div>
                                <div class="th-admin-qsc-desc"><?php echo esc_html__( 'Customize site colors', 'hybridmag' ); ?></div>
                            </a>
                        </div>

                        <div class="th-admin-theme-setting-box">
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=hybridmag_panel_blog' ) ); ?>" target="_blank">
                                <div class="th-admin-qsc-name"><?php echo esc_html__( 'Blog Options', 'hybridmag' ); ?></div>
                                <div class="th-admin-qsc-desc"><?php echo esc_html__( 'Post display & formatting', 'hybridmag' ); ?></div>
                            </a>
                        </div>

                        <div class="th-admin-theme-setting-box">
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=hybridmag_typography_panel' ) ); ?>" target="_blank">
                                <div class="th-admin-qsc-name"><?php echo esc_html__( 'Fonts', 'hybridmag' ); ?></div>
                                <div class="th-admin-qsc-desc"><?php echo esc_html__( 'Typography & text styles', 'hybridmag' ); ?></div>
                            </a>
                        </div>

                        <div class="th-admin-theme-setting-box">
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=hybridmag_blog_layout_section' ) ); ?>" target="_blank">
                                <div class="th-admin-qsc-name"><?php echo esc_html__( 'Blog Layout', 'hybridmag' ); ?></div>
                                <div class="th-admin-qsc-desc"><?php echo esc_html__( 'Blog layout options', 'hybridmag' ); ?></div>
                            </a>
                        </div>

                        <div class="th-admin-theme-setting-box">
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=hybridmag_panel_footer' ) ); ?>" target="_blank">
                                <div class="th-admin-qsc-name"><?php echo esc_html__( 'Footer Options', 'hybridmag' ); ?></div>
                                <div class="th-admin-qsc-desc"><?php echo esc_html__( 'Copyright info & footer widgets', 'hybridmag' ); ?></div>
                            </a>
                        </div>

                        <div class="th-admin-theme-setting-box">
                            <a href="<?php echo esc_url( admin_url( 'themes.php?page=hybridmag&tab=starter-templates' ) ); ?>">
                                <div class="th-admin-qsc-name"><?php echo esc_html__( 'Starter Templates', 'hybridmag' ); ?></div>
                                <div class="th-admin-qsc-desc"><?php echo esc_html__( 'Select and install pre-built demos', 'hybridmag' ); ?></div>
                            </a>
                        </div>
                    </div><!-- .th-admin-theme-setting-links -->
                </div><!-- .th-admin-theme-settings -->

                <div class="th-admin-pro-feature-box">
                    <div class="th-admin-pro-features-header">
                        <div class="th-admin-pro-header-wrap">
                            <h3 class="th-admin-pro-heading"><?php echo esc_html__( 'Take Your HybridMag Site Further', 'hybridmag' ); ?></h3>
                            <p class="th-admin-pro-desc"><?php echo esc_html__( 'HybridMag Pro is a dedicated extension plugin that adds advanced design controls, flexible layouts, and enhanced features to your existing HybridMag website.', 'hybridmag' ); ?></p>
                        </div>
                        <?php if ( ! defined( 'HYBRIDMAG_PRO_VERSION' ) ) { ?>
                            <a href="https://themezhut.com/themes/hybridmag-pro/" class="button hm-admin-go-pro" target="_blank">
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
                    </div>
                </div>
            </div><!-- .th-admin-theme-content -->
            <div class="th-admin-theme-sidebar">
                <?php do_action( 'hybridmag_admin_page_before_sidebar' ); ?>
                <div class="th-admin-quick-access-links">
                    <h4><?php echo esc_html__( 'Quick Links', 'hybridmag' ); ?></h4>
                    <ul>
                        <li>
                            <a href="https://themezhut.com/hybridmag-wordpress-theme-documentation/" target="_blank"><span class="dashicons dashicons-book-alt"></span><?php echo esc_html__( 'Documentation / Theme Setup Guide', 'hybridmag' ); ?></a>
                        </li>
                        <li>
                            <a href="https://themezhut.com/contact/" target="_blank"><span class="dashicons dashicons-email-alt"></span><?php echo esc_html__( 'Contact Support', 'hybridmag' ); ?></a>
                        </li>                        
                        <li>
                            <a href="https://themezhut.com/hybridmag-and-hybridmag-pro-changelog/" target="_blank"><span class="dashicons dashicons-list-view"></span><?php echo esc_html__( 'Changelog', 'hybridmag' ); ?></a>
                        </li>
                        <li>
                            <a href="https://themezhut.com/contact/" target="_blank"><span class="dashicons dashicons-lightbulb"></span><?php echo esc_html__( 'Feature Requests', 'hybridmag' ); ?></a>
                        </li>
                        <li>
                            <a style="font-weight: 600;" href="https://themezhut.com/themes/hybridmag-pro/#free-vs-pro" target="_blank"><span class="dashicons dashicons-star-filled"></span><?php echo esc_html__( 'Free vs Pro', 'hybridmag' ); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="th-admin-review-box">
                    <h4><?php echo esc_html__( 'Leave us a review', 'hybridmag' ); ?></h4>
                    <p><?php echo esc_html__( 'Are you enjoying HybridMag? We would love to hear your feedback.', 'hybridmag' ); ?></p>
                    <p>
                        <a href="https://wordpress.org/support/theme/hybridmag/reviews/#new-post" target="_blank">    
                            <?php echo esc_html__( 'Submit a review', 'hybridmag' ); ?>
                            <?php hybridmag_the_icon_svg( 'newtab' ); ?>
                        </a>
                    </p>
                </div>
                <?php do_action( 'hybridmag_admin_page_after_sidebar' ); ?>
            </div><!-- .th-admin-theme-sidebar -->

        </div>
    </div>

    <?php
}