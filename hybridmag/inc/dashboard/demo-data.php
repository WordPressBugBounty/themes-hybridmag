<?php
/**
 * Demo setup data needed for our Magazine Companion plugin's demo importer.
 */
function hybridmag_demo_importer_files() {
    $demo_data = array(
		array(
			'import_file_name'              => 'Default',
            'import_file_url'               => 'https://themezhutdemos.com/demo-data/free/demo-content.xml',
            'import_widget_file_url'        => 'https://themezhutdemos.com/demo-data/free/widgets.wie',
            'import_customizer_file_url'    => 'https://themezhutdemos.com/demo-data/free/customizer.dat',
            'import_preview_image_url'      => 'https://themezhutdemos.com/demo-data/free/screenshot.jpg',
			'preview_url'                   => 'https://themezhutdemos.com/hybridmag/free/'
        ),
        array(
			'import_file_name'              => 'HybMag',
            'import_file_url'               => 'https://themezhutdemos.com/demo-data/hybmag/demo-content.xml',
            'import_widget_file_url'        => 'https://themezhutdemos.com/demo-data/hybmag/widgets.wie',
            'import_customizer_file_url'    => 'https://themezhutdemos.com/demo-data/hybmag/customizer.dat',
            'import_preview_image_url'      => 'https://themezhutdemos.com/demo-data/hybmag/screenshot.jpg',
			'preview_url'                   => 'https://themezhutdemos.com/hybridmag/hybmag/'
		),
        array(
			'import_file_name'              => 'Journal',
            'import_file_url'               => 'https://themezhutdemos.com/demo-data/journal/demo-content.xml',
            'import_widget_file_url'        => 'https://themezhutdemos.com/demo-data/journal/widgets.wie',
            'import_customizer_file_url'    => 'https://themezhutdemos.com/demo-data/journal/customizer.dat',
            'import_preview_image_url'      => 'https://themezhutdemos.com/demo-data/journal/screenshot.jpg',
			'preview_url'                   => 'https://themezhutdemos.com/hybridmag/journal/',
            'plan'                          => 'pro'
		),
        array(
			'import_file_name'              => 'Lifestyle',
            'import_file_url'               => 'https://themezhutdemos.com/demo-data/lifestyle/demo-content.xml',
            'import_widget_file_url'        => 'https://themezhutdemos.com/demo-data/lifestyle/widgets.wie',
            'import_customizer_file_url'    => 'https://themezhutdemos.com/demo-data/lifestyle/customizer.dat',
            'import_preview_image_url'      => 'https://themezhutdemos.com/demo-data/lifestyle/screenshot.jpg',
			'preview_url'                   => 'https://themezhutdemos.com/hybridmag/lifestyle/',
            'plan'                          => 'pro'
		),
        array(
			'import_file_name'              => 'PRO Default',
            'import_file_url'               => 'https://themezhut.com/demo/ocdi/hybridmag/default-pro/demo-content.xml',
            'import_widget_file_url'        => 'https://themezhut.com/demo/ocdi/hybridmag/default-pro/widgets.wie',
            'import_customizer_file_url'    => 'https://themezhut.com/demo/ocdi/hybridmag/default-pro/customizer.dat',
            'import_preview_image_url'      => 'https://themezhut.com/demo/ocdi/hybridmag/default-pro/screenshot.png',
			'preview_url'                   => 'https://themezhut.com/demo/hybridmag-pro/',
            'plan'                          => 'pro'
        ),
	);

    // Filter to change import data.
    return apply_filters( 'hybridmag_demo_import_data', $demo_data );
}
add_filter( 'bnmbt_import_files', 'hybridmag_demo_importer_files' );

/**
 * After demo import action.
 */
function hybridmag_after_import( $selected_import ) {
 
    if ( 'Default' === $selected_import['import_file_name'] ) {
        // Assign menus to their locations.
        $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
        $social_menu = get_term_by( 'name', 'Social Menu', 'nav_menu' );
    
        set_theme_mod( 'nav_menu_locations', [
                'primary' => $main_menu->term_id,
                'social' => $social_menu->term_id 
            ]
        );
    } elseif ( 'HybMag' === $selected_import['import_file_name'] ) {

        $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
        $social_menu = get_term_by( 'name', 'Social Menu', 'nav_menu' );
        $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );
    
        set_theme_mod( 'nav_menu_locations', [
                'primary' => $main_menu->term_id,
                'social' => $social_menu->term_id,
                'secondary' => $top_menu->term_id
            ]
        );

    }

    // Assign front page and posts page (blog page).
    if ( 'HybMag' === $selected_import['import_file_name'] ) {

        // Get the front page.
        $front_page = get_posts(
            [
            'post_type'              => 'page',
            'title'                  => 'Demo Front Page',
            'post_status'            => 'any',
            'numberposts'            => 1,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            ]
        );
        
        if ( ! empty( $front_page ) ) {
            update_option( 'page_on_front', $front_page[0]->ID );
        }
        
        // Get the blog page.
        $blog_page = get_posts(
            [
            'post_type'              => 'page',
            'title'                  => 'Demo Blog Page',
            'post_status'            => 'any',
            'numberposts'            => 1,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            ]
        );
        
        if ( ! empty( $blog_page ) ) {
            update_option( 'page_for_posts', $blog_page[0]->ID );
        }
        
        if ( ! empty( $blog_page ) || ! empty( $front_page ) ) {
            update_option( 'show_on_front', 'page' );
        }
        
    }
    
}

/**
 * Selects what after import method to run.
 */
function hybridmag_handle_after_import( $selected_import ) {
    if ( function_exists( 'hybridmag_pro_after_import' ) ) {
        hybridmag_pro_after_import( $selected_import );
    } else {
        hybridmag_after_import( $selected_import );
    }
}
add_action( 'bnmbt_importer_after_import', 'hybridmag_handle_after_import' );

/**
 * This information is needed for the demo importer to function properly.
 */
function hybridmag_demo_importer_display_location() {
    return array(
        'parent_slug'   => 'themes.php',
        'menu_slug'     => 'hybridmag',
        'tab'           => 'starter-templates'
    );
}
add_filter( 'bnmbt_importer_display_location_data', 'hybridmag_demo_importer_display_location' );

function hybridmag_theme_pro_url( $url ) {
    $url = 'https://themezhut.com/themes/hybridmag-pro';
    return $url;
}
add_filter( 'bnmbt_importer_pro_url', 'hybridmag_theme_pro_url' );