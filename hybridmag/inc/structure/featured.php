<?php
/**
 * Functions related to front page featured sections
 */

/**
 * Display featured section.
 */
function hybridmag_featured_section() {
    
    if ( ! hybridmag_is_featured_section_displayed() || is_paged() ) {
        return;
    }

    do_action( 'hybridmag_before_featured_section' );

    get_template_part( 'template-parts/featured/slider' );

    do_action( 'hybridmag_after_featured_section' );

}
add_action( 'hybridmag_inside_site_content_top', 'hybridmag_featured_section', 30 );


/**
 * Check if the featured section is displayed.
 */
function hybridmag_is_featured_section_displayed() {

    if ( false == get_theme_mod( 'hybridmag_show_featured_content', true ) ) {
        return false;
    }

    if ( hybridmag_is_section_displayed( get_theme_mod( 'hybridmag_featured_content_displayed_on', array( 'front' ) ) ) ) {
		return true;
	}

    return false;

}

/**
 * Display posts tabs section.
 */
function hybridmag_featured_tabs_section() {
    
    if ( ! hybridmag_is_featured_tabs_displayed() || is_paged() ) {
        return;
    }

    do_action( 'hybridmag_before_featured_tabs_section' );

    get_template_part( 'template-parts/featured/tabs' );

    do_action( 'hybridmag_after_featured_tabs_section' );

}
add_action( 'hybridmag_inside_site_content_top', 'hybridmag_featured_tabs_section', 35 );


/**
 * Check if the post tabs section is displayed.
 */
function hybridmag_is_featured_tabs_displayed() {

    if ( false == get_theme_mod( 'hybridmag_display_tabbed_posts', true ) ) {
        return false;
    }

    if ( hybridmag_is_section_displayed( get_theme_mod( 'hybridmag_featured_tabs_displayed_on', array( 'front' ) ) ) ) {
		return true;
	}

    return false;

}