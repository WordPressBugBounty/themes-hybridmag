<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package HybridMag
 */

$hybridmag_sidebar_id = hybridmag_get_sidebar_id();

if ( ! is_active_sidebar( $hybridmag_sidebar_id ) ) {
	return;
}

?>

<aside id="secondary" class="widget-area">
	<?php 

	/**
	 * Before sidebar hook.
	 */
	do_action( 'hybridmag_before_main_sidebar' );
	
	
	dynamic_sidebar( $hybridmag_sidebar_id ); 

	/**
	 * After sidebar hook.
	 */
	do_action( 'hybridmag_after_main_sidebar' );
	
	?>
</aside><!-- #secondary -->
