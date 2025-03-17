<?php
/**
 * Load necessary Customizer controls and functions.
 * 
 * @package HybridMag
 */

// Custom Controls.
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/fonts/class-fonts-control.php';
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/responsive-number/class-responsive-number-control.php';
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/slider/class-slider-control.php';
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/radio-image/class-radio-image-control.php';
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/class-category-dropdown.php';
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/toggle-switch/class-toggle-switch.php';
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/sortable-checkbox/class-sortable-checkbox.php';
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/custom-link/class-custom-link.php';
require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/multiple-checkboxes/class-multiple-checkboxes.php';
if ( ! defined( 'HYBRIDMAG_PRO_VERSION' ) ) {
    require_once trailingslashit( get_template_directory() ) . 'inc/customizer/custom-controls/class-upsell-customize.php';
}