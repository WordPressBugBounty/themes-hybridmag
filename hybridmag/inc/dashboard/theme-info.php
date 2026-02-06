<?php

function hybridmag_enqueue_admin_scripts( $hook ) {
    if ( $hook === 'post.php' || $hook === 'post-new.php' || 'appearance_page_hybridmag' == $hook ) {
        wp_register_style( 'hybridmag-admin-css', get_template_directory_uri() . '/inc/dashboard/css/admin.css', false, '1.0.0' );
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
    <div class="updated notice notice-info is-dismissible">
        <p><?php esc_html_e( 'Welcome to HybridMag! To get started with HybridMag please visit the theme Welcome page.', 'hybridmag' ); ?></p>
        <p><a class="button" href="<?php echo esc_url( admin_url( 'themes.php?page=hybridmag' ) ); ?>"><?php _e( 'Get Started with HybridMag', 'hybridmag' ) ?></a></p>
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

    add_theme_page( esc_html__( 'HybridMag Theme', 'hybridmag' ), $menu_title , 'edit_theme_options', 'hybridmag', 'hybridmag_themeinfo_page_render' );

}
add_action( 'admin_menu', 'hybridmag_add_themeinfo_page' );

function hybridmag_themeinfo_page_render() { ?>

    <div class="th-theme-info-page wrap">

        <?php $theme_info = wp_get_theme(); ?>



            <h2 class="nav-tab-wrapper">
                <a class="nav-tab <?php if ( $_GET['page'] == 'hybridmag' && ! isset( $_GET['tab'] ) ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'hybridmag' ), 'themes.php' ) ) ); ?>">
                    <?php echo 'HybridMag'; ?>
                </a>
                <a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'starter-templates' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'hybridmag', 'tab' => 'starter-templates' ), 'themes.php' ) ) ); ?>">
                    <?php esc_html_e( 'Starter Templates', 'hybridmag' ); ?>
                </a>
            </h2>


            <div class="th-nav-tab-inner">

                <?php

                    $current_tab = ! empty( $_GET['tab'] ) ? sanitize_title( $_GET['tab'] ) : '';

                    if ( $current_tab == 'starter-templates' ) {
                        hybridmag_starter_templates();
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
 * Recommend Magazine Companion plugin to install demos.
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
            /* translators: %s: theme name */
            esc_html__( 'Install Magazine Companion Plugin Now', 'hybridmag' ),
            esc_html__( 'Magazine Companion', 'hybridmag' ),
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
            /* translators: %s: theme name */
            esc_html__( 'Activate Magazine Companion Plugin Now', 'hybridmag' ),
            esc_html__( 'Magazine Companion', 'hybridmag' ),
            $activate_string
        );

    }
    ?>

    <div class="th-demo-plugin-notice">
        <p>
        <?php 
            echo esc_html__( 'To import demos, simply install and activate our "Magazine Companion" plugin. Click to install and activate.', 'hybridmag' ); 
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
    <div class="th-theme-details-page">
        <div class="th-theme-details-page-inner">
            <div class="th-theme-page-infobox">
                <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Theme Customizer', 'hybridmag' ); ?></h3>
                <p><?php esc_html_e( 'All the HybridMag theme settings are located at the customizer. Start customizing your website with customizer.', 'hybridmag' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( admin_url( '/customize.php' ) ); ?>"><?php esc_html_e( 'Go to customizer','hybridmag' ); ?></a>
                </div>
            </div>

            <div class="th-theme-page-infobox">
            <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Theme Documentation', 'hybridmag' ); ?></h3>
                <p><?php esc_html_e( 'Need to learn all about HybridMag? Read the theme documentation carefully.', 'hybridmag' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( 'https://themezhut.com/hybridmag-wordpress-theme-documentation/' ); ?>"><?php esc_html_e( 'Read the documentation.','hybridmag' ); ?></a>
            </div>
            </div>

            <div class="th-theme-page-infobox">
            <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Theme Info', 'hybridmag' ); ?></h3>
                <p><?php esc_html_e( 'Know all the details about HybridMag theme.', 'hybridmag' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( 'https://themezhut.com/themes/hybridmag/' ); ?>"><?php esc_html_e( 'Theme Details.','hybridmag' ); ?></a>
            </div>
            </div>

            <div class="th-theme-page-infobox">
            <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Theme Demo', 'hybridmag' ); ?></h3>
                <p><?php esc_html_e( 'See the theme preview of free version.', 'hybridmag' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( 'https://themezhut.com/demo/hybridmag/' ); ?>"><?php esc_html_e( 'Theme Preview','hybridmag' ); ?></a>    
            </div>
            </div>

            <div class="th-theme-page-infobox">
            <div class="th-theme-infobox-content">
                <h3><?php esc_html_e( 'Demo Installation', 'hybridmag' ); ?></h3>
                <p><?php esc_html_e( 'Learn about starter sites.', 'hybridmag' ) ?></p>
                <a class="button" target="_blank" href="<?php echo esc_url( 'https://themezhut.com/hybridmag-wordpress-theme-documentation/#demos' ); ?>"><?php esc_html_e( 'Read the documentation.','hybridmag' ); ?></a>    
            </div>
            </div>
        </div>
    </div>

    <?php
}