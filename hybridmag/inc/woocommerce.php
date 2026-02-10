<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package hybridmag
 */


/**
 * Add theme support for WooCommerce.
 */
function hybridmag_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}
add_action( 'after_setup_theme', 'hybridmag_add_woocommerce_support' );


/**
 * Remove HybridMag entry classes from WooCommerce products/pages.
 */
function hybridmag_remove_entry_classes_from_products( $classes, $product ) {

	$remove = array(
		'hm-entry',
		'hm-entry-single',
	);

	$classes = array_diff( $classes, $remove );

	return $classes;
}
add_filter( 'woocommerce_post_class', 'hybridmag_remove_entry_classes_from_products', 10, 2 );



if ( ! function_exists( 'hybridmag_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function hybridmag_woocommerce_wrapper_before() {
		?>
			<main id="primary" class="site-main">
				<div class="hm-woocommerce-container">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'hybridmag_woocommerce_wrapper_before' );



if ( ! function_exists( 'hybridmag_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function hybridmag_woocommerce_wrapper_after() {
		?>
				</div><!-- .hm-woocommerce-container -->
			</main><!-- .site-main -->
		<?php
		hybridmag_get_sidebar();
	}
}
add_action( 'woocommerce_after_main_content', 'hybridmag_woocommerce_wrapper_after' );


/**
 * Add Customizer related controls
 */
function hybridmag_woocommerce_customize( $wp_customize ) {
	
	// uri for the customizer images folder
	$images_uri = get_template_directory_uri() . '/inc/customizer/assets/images/';

	$wp_customize->add_section(
		'hybridmag_woocommerce_general',
		array(
			'title'		=> esc_html__( 'General', 'hybridmag' ),
			'priority'	=> 5,
			'panel'		=> 'woocommerce'
		)
	);

	$wp_customize->add_setting(
		'hybridmag_woo_main_layout',
		array(
			'default'			=> 'right-sidebar',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_select'
		)
	);
	if ( class_exists( 'HybridMag_Radio_Image_Control' ) ) {
		$wp_customize->add_control(
			new HybridMag_Radio_Image_Control( 
				$wp_customize,
				'hybridmag_woo_main_layout',
				array(
					'section'		=> 'hybridmag_woocommerce_general',
					'label'			=> esc_html__( 'Main Sidebar Layout', 'hybridmag' ),
					'choices'		=> array(
						'right-sidebar'	        => $images_uri . '2cr.png',
						'left-sidebar' 	        => $images_uri . '2cl.png',
						'no-sidebar' 		    => $images_uri . '1c.png',
						'center-content' 	    => $images_uri . '1cc.png'
					),
					'priority'		=> 10
				)
			)
		);
	}

}
add_action( 'customize_register', 'hybridmag_woocommerce_customize' );


/**
 * Select WooCommerce Sidebar Layout.
 */
function hybridmag_woocommerce_sidebar_layout( $layout ) {

	if ( ! function_exists( 'is_woocommerce' ) ) {
		return $layout;
	}

	// Cart, checkout, account -> always no sidebar
	if ( is_cart() || is_checkout() || is_account_page() ) {
		return 'no-sidebar';
	}

	// Shop + product archives
	if ( is_shop() || is_product_taxonomy() ) {
		return get_theme_mod( 'hybridmag_woo_main_layout', 'right-sidebar' );
	}

	// Single product
	if ( is_product() ) {
		return get_theme_mod( 'hybridmag_woo_main_layout', 'right-sidebar' );
	}

	return $layout;
}
add_filter( 'hybridmag_sidebar_layout', 'hybridmag_woocommerce_sidebar_layout', 10 );

/**
 * Enqueue Theme WooCommerce Styles.
 */
function hybridmag_enqueue_woocommerce_styles() {
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'hybridmag-woocommerce', get_template_directory_uri() . '/woocommerce.css', [], wp_get_theme()->get( 'Version' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'hybridmag_enqueue_woocommerce_styles' );
