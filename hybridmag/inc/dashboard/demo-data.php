<?php
/**
 * Demo setup data needed for our Magazine Companion plugin's demo importer.
 */
function hybridmag_demo_importer_files() {
    $demo_data = array(
		array(
			'import_file_name'              => esc_html__( 'Default', 'hybridmag' ),
            'import_file_url'               => 'https://themezhut.com/demo/ocdi/hybridmag/default/demo-content.xml',
            'import_widget_file_url'        => 'https://themezhut.com/demo/ocdi/hybridmag/default/widgets.wie',
            'import_customizer_file_url'    => 'https://themezhut.com/demo/ocdi/hybridmag/default/customizer.dat',
            'import_preview_image_url'      => 'https://themezhut.com/demo/ocdi/hybridmag/default/screenshot.png',
			'preview_url'                   => 'https://themezhut.com/demo/hybridmag/'
		)
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