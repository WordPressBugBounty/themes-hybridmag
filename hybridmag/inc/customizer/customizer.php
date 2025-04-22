<?php
/**
 * HybridMag Theme Customizer
 *
 * @package HybridMag
 */

 /**
  * Set up customizer helpers early.
  */
function hybridmag_get_customizer_helpers() {
	require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';
}
add_action( 'customize_register', 'hybridmag_get_customizer_helpers', 1 );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function hybridmag_customize_register( $wp_customize ) {

	// Custom Controls.
	$wp_customize->register_control_type( 'HybridMag_Responsive_Number_Control' );

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_control( 'blogname' )->priority         = 1;
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_control( 'blogdescription' )->priority  = 3;
	$wp_customize->get_control( 'background_color' )->priority  = 2;
	$wp_customize->get_control( 'background_color' )->section 	= 'hybridmag_base_colors'; 
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	// Hide the checkbox "Display site title and tagline"
	$wp_customize->remove_control( 'display_header_text' );

	$wp_customize->get_control( 'header_textcolor' )->priority 	= 1;
	$wp_customize->get_control( 'header_textcolor' )->label 	= esc_html__( 'Site title / tagline color', 'hybridmag' );
	$wp_customize->get_control( 'header_textcolor' )->section	= 'hybridmag_header_colors';
	$wp_customize->get_control( 'header_textcolor' )->priority	= 1;
	$wp_customize->get_section( 'header_image' )->panel 		= 'hybridmag_panel_header';
	$wp_customize->get_section( 'header_image' )->priority 		= 50;

	// uri for the customizer images folder
	$images_uri = get_template_directory_uri() . '/inc/customizer/assets/images/'; 

	// Selective Refresh
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'hybridmag_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'hybridmag_customize_partial_blogdescription',
			)
		);
		$wp_customize->selective_refresh->add_partial( 
			'hybridmag_show_featured_content', 
			array(
				'selector' 				=> '.hm-fp1',
				'render_callback' 		=> 'hybridmag_featured_section',
				'container_inclusive' 	=> true,
				'fallback_refresh'		=> true
			) 
		);
		$wp_customize->selective_refresh->add_partial( 
			'hybridmag_display_tabbed_posts', 
			array(
				'selector' 				=> '.hm-tab-posts-wrapper',
				'render_callback' 		=> 'hybridmag_featured_tabs_section',
				'container_inclusive' 	=> true,
				'fallback_refresh'		=> true
			) 
		);
		$wp_customize->selective_refresh->add_partial( 
			'hybridmag_blog_section_title', 
			array(
				'selector' 				=> '.hm-blog-entries-title',
				'render_callback' 		=> 'hybridmag_homepage_article_title',
				'container_inclusive' 	=> true,
				'fallback_refresh'		=> true
			) 
		);
	}

	// Latest articles section title on front page?
	$wp_customize->add_setting(
		'hybridmag_blog_section_title',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'hybridmag_blog_section_title',
		array(
			'settings'			=> 'hybridmag_blog_section_title',
			'section'			=> 'static_front_page',
			'type'				=> 'text',
			'label'				=> esc_html__( 'Front page blog section title.', 'hybridmag' ),
			'active_callback'	=> 'hybridmag_is_showing_blog_on_front'
		)
	);

	// Hide site title
	$wp_customize->add_setting(
		'hybridmag_hide_site_title',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_hide_site_title',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Hide site title', 'hybridmag' ),
			'priority'	  => 2,
			'section'     => 'title_tagline',
		)
	);

	// Hide site title
	$wp_customize->add_setting(
		'hybridmag_hide_site_tagline',
		array(
			'default'           => true,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_hide_site_tagline',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Hide site tagline', 'hybridmag' ),
			'priority'	  => 4,
			'section'     => 'title_tagline',
		)
	);

	// Dark Mode Logo
	$wp_customize->add_setting( 
		'hybridmag_dark_mode_logo', 
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		) 
	);
	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'hybridmag_dark_mode_logo',
			array(
				'label'			=> esc_html__( 'Dark Mode Logo', 'hybridmag' ),
				'section'		=> 'title_tagline',
				'height'		=> 50,
				'width'			=> 165, 
				'flex_width'	=> true, 
				'flex_height'	=> true, 
				'priority'		=> 9,
			)
		)
	);

	// Logo Max Width
	$wp_customize->add_setting(
		'hybridmag_logo_max_width_desktop',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	// Logo Max Width - Tab.
	$wp_customize->add_setting(
		'hybridmag_logo_max_width_tablet',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	// Logo Max Width - Mobile.
	$wp_customize->add_setting(
		'hybridmag_logo_max_width_mobile',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Responsive_Number_Control( $wp_customize, 'hybridmag_logo_max_width',
		array(
			'label'         => esc_html__( 'Logo Max Width (px)', 'hybridmag' ),
			'section'       => 'title_tagline',
			'settings'      => array(
				'desktop'   => 'hybridmag_logo_max_width_desktop',
				'tablet'    => 'hybridmag_logo_max_width_tablet',
				'mobile'    => 'hybridmag_logo_max_width_mobile'
			),
			'active_callback'	=> 'hybridmag_has_custom_logo'
		)
	) );

	// Logo Max Height
	$wp_customize->add_setting(
		'hybridmag_logo_max_height_desktop',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	// Logo Max Height - Tab.
	$wp_customize->add_setting(
		'hybridmag_logo_max_height_tablet',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	// Logo Max Height - Mobile.
	$wp_customize->add_setting(
		'hybridmag_logo_max_height_mobile',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Responsive_Number_Control( $wp_customize, 'hybridmag_logo_max_height',
		array(
			'label'         => esc_html__( 'Logo Max Height (px)', 'hybridmag' ),
			'section'       => 'title_tagline',
			'settings'      => array(
				'desktop'   => 'hybridmag_logo_max_height_desktop',
				'tablet'    => 'hybridmag_logo_max_height_tablet',
				'mobile'    => 'hybridmag_logo_max_height_mobile'
			),
			'active_callback'	=> 'hybridmag_has_custom_logo'
		)
	) );

	// General Settings Panel
	$wp_customize->add_panel(
		'hybridmag_panel_general_settings',
		array(
			'priority' 			=> 22,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'General', 'hybridmag' )
		)
	);

	// General Settings Section
	$wp_customize->add_section(
		'hybridmag_site_layout_section',
		array(
			'title' => esc_html__( 'Site Layout', 'hybridmag' ),
			'panel' => 'hybridmag_panel_general_settings'
		)
	);

	// General - Site Layout
	$wp_customize->add_setting(
		'hybridmag_site_layout',
		array(
			'default' => 'wide',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_site_layout',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Site Layout', 'hybridmag' ),
			'section' => 'hybridmag_site_layout_section',
			'choices' => array(
				'wide' => esc_html__( 'Wide', 'hybridmag' ),
				'boxed' => esc_html__( 'Boxed', 'hybridmag' )
			)
		)
	);

	// General - Content Layout
	$wp_customize->add_setting(
		'hybridmag_content_layout',
		array(
			'default' => 'separate-containers',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_content_layout',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Content Layout', 'hybridmag' ),
			'section' => 'hybridmag_site_layout_section',
			'choices' => array(
				'separate-containers'	=> esc_html__( 'Separate Containers', 'hybridmag' ),
				'one-container'			=> esc_html__( 'One Container', 'hybridmag' )
			)
		)
	);

	// General - Site container width
	$wp_customize->add_setting( 
		'hybridmag_container_width',
		array(
			'default'           => 1200,
			'sanitize_callback' => 'hybridmag_sanitize_slider_number_input',
			'transport'         => 'postMessage'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Slider_Control( $wp_customize, 'hybridmag_container_width',
		array(
			'label'         => esc_html__( 'Container Width (px)', 'hybridmag' ),
			'section'       => 'hybridmag_site_layout_section',
			'input_attrs'   => array(
				'min'   => 300,
				'max'   => 2000,
				'step'  => 1,
			),
			'active_callback' => 'hybridmag_is_wide_layout_active'
		)
	) );

	// General - Boxed Layout width
	$wp_customize->add_setting( 
		'hybridmag_boxed_width',
		array(
			'default'           => 1280,
			'sanitize_callback' => 'hybridmag_sanitize_slider_number_input',
			//'transport'         => 'postMessage'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Slider_Control( $wp_customize, 'hybridmag_boxed_width',
		array(
			'label'         => esc_html__( 'Boxed Layout Width (px)', 'hybridmag' ),
			'section'       => 'hybridmag_site_layout_section',
			'input_attrs'   => array(
				'min'   => 300,
				'max'   => 2000,
				'step'  => 1,
			),
			'active_callback' => 'hybridmag_is_boxed_layout_active'
		)
	) );

	// General - Sidebar width
	$wp_customize->add_setting( 
		'hybridmag_sidebar_width',
		array(
			'default'           => 32.916666667,
			'sanitize_callback' => 'hybridmag_sanitize_slider_number_input',
			//'transport'         => 'postMessage'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Slider_Control( $wp_customize, 'hybridmag_sidebar_width',
		array(
			'label'         => esc_html__( 'Sidebar Width (%)', 'hybridmag' ),
			'description'	=> esc_html__( 'This value applies only when the sidebar is active.', 'hybridmag' ),
			'section'       => 'hybridmag_site_layout_section',
			'input_attrs'   => array(
				'min'   => 15,
				'max'   => 50,
				'step'  => 1,
			)
		)
	) );

	// Breadcrumb Settings Section
	$wp_customize->add_section(
		'hybridmag_breadcrumb_section',
		array(
			'title' => esc_html__( 'Breadcrumb', 'hybridmag' ),
			'panel' => 'hybridmag_panel_general_settings'
		)
	);

	$wp_customize->add_setting(
		'hybridmag_breadcrumb_source',
		array(
			'default' => 'none',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_breadcrumb_source',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Breadcrumb Source', 'hybridmag' ),
			'section' => 'hybridmag_breadcrumb_section',
			'choices' => array(
				'none' 			=> esc_html__( 'None', 'hybridmag' ),
				'yoast' 		=> esc_html__( 'Yoast SEO Breadcrumbs', 'hybridmag' ),
				'navxt' 		=> esc_html__( 'Breadcrumb NavXT', 'hybridmag' ),
				'rankmath' 		=> esc_html__( 'RankMath Breadcrumbs', 'hybridmag' ),
			)
		)
	);

	$wp_customize->add_setting(
		'hybridmag_breadcrumb_location',
		array(
			'default' => 'before-entry-header',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_breadcrumb_location',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Breadcrumb Location', 'hybridmag' ),
			'section' => 'hybridmag_breadcrumb_section',
			'choices' => array(
				'after-site-header'		=> esc_html__( 'After Site Header', 'hybridmag' ),
				'before-entry-header'	=> esc_html__( 'Before Article Header', 'hybridmag' )
			),
			'active_callback' => 'hybridmag_is_showing_breadcrumb'
		)
	);

	// General - Featured images rounded borders
	/*$wp_customize->add_setting(
		'hybridmag_images_rounded_borders',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_images_rounded_borders',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Make corners rounded on featured images', 'hybridmag' ),
			'section'     => 'hybridmag_site_layout_section',
		)
	);*/
	// General Settings Section
	$wp_customize->add_section(
		'hybridmag_site_styling_section',
		array(
			'title' => esc_html__( 'Site Styling', 'hybridmag' ),
			'panel' => 'hybridmag_panel_general_settings'
		)
	);

	// Global Border Radius
	$wp_customize->add_setting( 
		'hybridmag_global_border_radius',
		array(
			'default'           => 6,
			'sanitize_callback' => 'hybridmag_sanitize_slider_number_input',
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Slider_Control( $wp_customize, 'hybridmag_global_border_radius',
		array(
			'label'         => esc_html__( 'Global Border Radius (px)', 'hybridmag' ),
			'section'       => 'hybridmag_site_styling_section',
			'input_attrs'   => array(
				'min'   => 0,
				'max'   => 80,
				'step'  => 1,
			)
		)
	) );

	// Header Settings Panel
	$wp_customize->add_panel(
		'hybridmag_panel_header',
		array(
			'priority' 			=> 24,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Header', 'hybridmag' )
		)
	);

	$wp_customize->add_section(
		'hybridmag_header_layout_section',
		array(
			'title' => esc_html__( 'Header Layout', 'hybridmag' ),
			'priority' => 5,
			'panel'	=> 'hybridmag_panel_header'
		)
	);

	// Header Layout
	$wp_customize->add_setting(
		'hybridmag_header_layout',
		array(
			'default' => 'default',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_header_layout',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Header Layout', 'hybridmag' ),
			'section' => 'hybridmag_header_layout_section',
			'choices' => array(
				'default' => esc_html__( 'Default Layout', 'hybridmag' ),
				'large' => esc_html__( 'Large Layout', 'hybridmag' )
			)
		)
	);

	// Header Width.
	$wp_customize->add_setting(
		'hybridmag_header_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_header_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Header Width', 'hybridmag' ),
			'section' => 'hybridmag_header_layout_section',
			'choices' => array(
				'contained' => esc_html__( 'Contained', 'hybridmag' ),
				'full' => esc_html__( 'Full', 'hybridmag' )
			)
		)
	);

	// Header - Logo Alignment
	$wp_customize->add_setting(
		'hybridmag_logo_align',
		array(
			'default' => 'left',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_logo_align',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Logo Alignment', 'hybridmag' ),
			'section' => 'hybridmag_header_layout_section',
			'choices' => array(
				'left'		=> esc_html__( 'Left', 'hybridmag' ),
				'center'	=> esc_html__( 'Center', 'hybridmag' ),
				'right'		=> esc_html__( 'Right', 'hybridmag' )
			),
			'active_callback' => 'hybridmag_is_large_header'
		)
	);

	// Header - Height
	$wp_customize->add_setting( 
		'hybridmag_header_height',
		array(
			'default'           => '',
			'sanitize_callback' => 'hybridmag_sanitize_slider_number_input',
			'transport'         => 'postMessage'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Slider_Control( $wp_customize, 'hybridmag_header_height',
		array(
			'label'         => esc_html__( 'Header Height (px)', 'hybridmag' ),
			'section'       => 'hybridmag_header_layout_section',
			'input_attrs'   => array(
				'min'   => 30,
				'max'   => 600,
				'step'  => 1,
			),
			'active_callback' => 'hybridmag_is_default_header'
		)
	) );

	// Header Padding Top - Desktop
	$wp_customize->add_setting(
		'hybridmag_header_padding_top_desktop',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	// Header Padding Top - Tablet
	$wp_customize->add_setting(
		'hybridmag_header_padding_top_tablet',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	// Header Padding Top - Mobile
	$wp_customize->add_setting(
		'hybridmag_header_padding_top_mobile',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Responsive_Number_Control( $wp_customize, 'hybridmag_header_padding_top',
		array(
			'label'         => esc_html__( 'Header Padding Top (px)', 'hybridmag' ),
			'section'       => 'hybridmag_header_layout_section',
			'settings'      => array(
				'desktop'   => 'hybridmag_header_padding_top_desktop',
				'tablet'    => 'hybridmag_header_padding_top_tablet',
				'mobile'    => 'hybridmag_header_padding_top_mobile'
			)
		)
	) );

	// Header Padding Bottom - Desktop
	$wp_customize->add_setting(
		'hybridmag_header_padding_bottom_desktop',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	// Header Padding Bottom - Tablet
	$wp_customize->add_setting(
		'hybridmag_header_padding_bottom_tablet',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	// Header Padding Bottom - Mobile
	$wp_customize->add_setting(
		'hybridmag_header_padding_bottom_mobile',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_blank'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Responsive_Number_Control( $wp_customize, 'hybridmag_header_padding_bottom',
		array(
			'label'         => esc_html__( 'Header Padding Bottom (px)', 'hybridmag' ),
			'section'       => 'hybridmag_header_layout_section',
			'settings'      => array(
				'desktop'   => 'hybridmag_header_padding_bottom_desktop',
				'tablet'    => 'hybridmag_header_padding_bottom_tablet',
				'mobile'    => 'hybridmag_header_padding_bottom_mobile'
			)
		)
	) );

	// Menu Section
	$wp_customize->add_section(
		'hybridmag_primary_menu_section',
		array(
			'title' => esc_html__( 'Primary Menu', 'hybridmag' ),
			'priority' => 10,
			'panel'	=> 'hybridmag_panel_header'
		)
	);

	// Header - Menu Width.
	$wp_customize->add_setting(
		'hybridmag_menu_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_menu_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Menu Width', 'hybridmag' ),
			'section' => 'hybridmag_primary_menu_section',
			'choices' => array(
				'contained' => esc_html__( 'Contained', 'hybridmag' ),
				'full' => esc_html__( 'Full', 'hybridmag' )
			),
			'active_callback' => 'hybridmag_is_large_header'
		)
	);

	// Header - Menu Inner Width.
	$wp_customize->add_setting(
		'hybridmag_menu_inner_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_menu_inner_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Menu Inner Width', 'hybridmag' ),
			'section' => 'hybridmag_primary_menu_section',
			'choices' => array(
				'contained' => esc_html__( 'Contained', 'hybridmag' ),
				'full' => esc_html__( 'Full', 'hybridmag' )
			),
			'active_callback' => 'hybridmag_is_large_header'
		)
	);

	// Header - Menu Alignment
	$wp_customize->add_setting(
		'hybridmag_menu_align',
		array(
			'default' => 'left',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_menu_align',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Menu Alignment', 'hybridmag' ),
			'section' => 'hybridmag_primary_menu_section',
			'choices' => array(
				'left'		=> esc_html__( 'Left', 'hybridmag' ),
				'center'	=> esc_html__( 'Center', 'hybridmag' ),
				'right'		=> esc_html__( 'Right', 'hybridmag' )
			),
			'priority'	=> 10,
			'active_callback' => 'hybridmag_is_large_header'
		)
	);

	$wp_customize->add_setting( 
		'hybridmag_show_search_onmenu',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_show_search_onmenu',
		array(
			'label' 	=> esc_html__( 'Display Search Box', 'hybridmag' ),
			'section' 	=> 'hybridmag_primary_menu_section',
			'priority'	=> 15,
		)
	) );

	$wp_customize->add_setting( 
		'hybridmag_show_dark_toggle',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_show_dark_toggle',
		array(
			'label' 	=> esc_html__( 'Display Dark/Light Toggle', 'hybridmag' ),
			'section' 	=> 'hybridmag_primary_menu_section',
			'priority'	=> 15,
		)
	) );

	// Primary Menu - Line Height
	$wp_customize->add_setting( 
		'hybridmag_pmenu_line_height',
		array(
			'default'           => 62,
			'sanitize_callback' => 'hybridmag_sanitize_slider_number_input',
			'transport'         => 'postMessage'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Slider_Control( $wp_customize, 'hybridmag_pmenu_line_height',
		array(
			'label'         => esc_html__( 'Menu Height (px)', 'hybridmag' ),
			'section'       => 'hybridmag_primary_menu_section',
			'input_attrs'   => array(
				'min'   => 20,
				'max'   => 300,
				'step'  => 1,
			),
			'active_callback' => 'hybridmag_is_large_header'
		)
	) );

	// Social Menu Section
	$wp_customize->add_section(
		'hybridmag_social_menu_section',
		array(
			'title' => esc_html__( 'Social Icons', 'hybridmag' ),
			'priority' => 12,
			'panel'	=> 'hybridmag_panel_header'
		)
	);

    // Jump to "Menus" section.
    $wp_customize->add_control( new HybridMag_Custom_Link_Control( $wp_customize, 'hybridmag_jump_to_nav_menus', 
		array(
			'label'    => esc_html__( 'Create / Edit Social Icons Menu', 'hybridmag' ),
			'section'  => 'hybridmag_social_menu_section',
			'settings' => array(), // No setting needed
		) ) 
	);

	// show social on topbar
	$wp_customize->add_setting(
		'hybridmag_display_social_topbar',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_display_social_topbar',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display social menu on topbar', 'hybridmag' ),
			'section'     => 'hybridmag_social_menu_section',
		)
	);

	// Social Menu - show next to logo.
	$wp_customize->add_setting(
		'hybridmag_social_beside_pmenu',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_social_beside_pmenu',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Display Social Menu beside primary menu', 'hybridmag' ),
			'section'     		=> 'hybridmag_social_menu_section'
		)
	);

	// Social Menu - show next to logo.
	$wp_customize->add_setting(
		'hybridmag_social_beside_logo',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_social_beside_logo',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Display Social Menu beside site branding', 'hybridmag' ),
			'section'     => 'hybridmag_social_menu_section',
			'active_callback' => 'hybridmag_is_large_header'
		)
	);

	// Social Menu - show Primary Menu on mobile sidebar.
	$wp_customize->add_setting(
		'hybridmag_show_social_mobile_menu',
		array(
			'default'           => true,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_social_mobile_menu',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Social Menu on Mobile Menu', 'hybridmag' ),
			'section'     => 'hybridmag_social_menu_section'
		)
	);

	// Top Bar Section
	$wp_customize->add_section(
		'hybridmag_topbar_section',
		array(
			'title' => esc_html__( 'Top Bar', 'hybridmag' ),
			'priority' => 15,
			'panel'	=> 'hybridmag_panel_header'
		)
	);

	// Top bar Width.
	$wp_customize->add_setting(
		'hybridmag_topbar_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_topbar_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Top Bar Inner Width', 'hybridmag' ),
			'section' => 'hybridmag_topbar_section',
			'choices' => array(
				'contained' => esc_html__( 'Contained', 'hybridmag' ),
				'full' => esc_html__( 'Full', 'hybridmag' )
			)
		)
	);

	// Header CTA section
	$wp_customize->add_section(
		'hybridmag_header_cta_section',
		array(
			'title' => esc_html__( 'Call to Action Button', 'hybridmag' ),
			'priority' => 20,
			'panel'	=> 'hybridmag_panel_header'
		)
	);

	// Header - show header cta on desktop header
	$wp_customize->add_setting( 
		'hybridmag_show_header_cta',
		array(
			'default' 			=> false,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_show_header_cta',
		array(
			'label' 	=> esc_html__( 'Display on header', 'hybridmag' ),
			'section' 	=> 'hybridmag_header_cta_section'
		)
	) );

	// Header - show header cta on mobile header
	$wp_customize->add_setting(
		'hybridmag_hide_cta_mobile',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_hide_cta_mobile',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Hide on Mobile Header', 'hybridmag' ),
			'section'     		=> 'hybridmag_header_cta_section',
			'active_callback' 	=> 'hybridmag_is_cta_active'
		)
	);	

	$wp_customize->add_setting(
		'hybridmag_header_cta_txt',
		array(
			'default'			=> esc_html__( 'Subscribe', 'hybridmag' ),
			'sanitize_callback'	=> 'hybridmag_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'hybridmag_header_cta_txt',
		array(
			'section'		=> 'hybridmag_header_cta_section',
			'type'			=> 'text',
			'label'			=> esc_html__( 'Button Text', 'hybridmag' ),
			'active_callback' 	=> 'hybridmag_is_cta_active'
		)
	);

	$wp_customize->add_setting(
		'hybridmag_header_cta_url',
		array(
			'default'			=> '',
			'sanitize_callback'	=> 'hybridmag_sanitize_url'
		)
	);
	$wp_customize->add_control(
		'hybridmag_header_cta_url',
		array(
			'section'		=> 'hybridmag_header_cta_section',
			'type'			=> 'url',
			'label'			=> esc_html__( 'Button URL', 'hybridmag' ),
			'active_callback' 	=> 'hybridmag_is_cta_active'
		)
	);

	// Header - Cta open link in new window
	$wp_customize->add_setting(
		'hybridmag_header_cta_target',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_header_cta_target',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Open link in new window', 'hybridmag' ),
			'section'     => 'hybridmag_header_cta_section',
			'active_callback' 	=> 'hybridmag_is_cta_active'
		)
	);

	// Slide-out Sidebar
	$wp_customize->add_section(
		'hybridmag_slideoutsb_section',
		array(
			'title' => esc_html__( 'Slide-out Sidebar', 'hybridmag' ),
			'priority' => 20,
			'panel'	=> 'hybridmag_panel_header'
		)
	);

	// Header - Show slideout sidebar
	$wp_customize->add_setting(
		'hybridmag_show_slideout_sb',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_slideout_sb',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Slide-out sidebar', 'hybridmag' ),
			'description' => sprintf(
				/* translators: %s: link to Slide Out Sidebar widget panel in Customizer. */
				esc_html__( 'Show a Slide-out sidebar in the header, which you can populate by adding widgets %1$s.', 'hybridmag' ),
				'<a rel="goto-section" href="#sidebar-widgets-header-1">' . esc_html__( 'here', 'hybridmag' ) . '</a>'
			),
			'section'     => 'hybridmag_slideoutsb_section',
		)
	);

	// Header - show Primary Menu on slide out sidebar
	$wp_customize->add_setting(
		'hybridmag_show_pmenu_onslideout',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_pmenu_onslideout',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Primary Menu on Slide-out sidebar', 'hybridmag' ),
			'section'     => 'hybridmag_slideoutsb_section',
			'active_callback'	=> 'hybridmag_is_slideout_active'
		)
	);
	
	// Header - show Primary Menu on slide out sidebar
	$wp_customize->add_setting(
		'hybridmag_show_logo_on_slideout',
		array(
			'default'           => true,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_logo_on_slideout',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Site Logo on Slide-out sidebar', 'hybridmag' ),
			'section'     => 'hybridmag_slideoutsb_section',
			'active_callback'	=> 'hybridmag_is_slideout_active'
		)
	);

	// Header - slide out menu position
	$wp_customize->add_setting(
		'hybridmag_slideout_btn_loc',
		array(
			'default'           => 'primary-menu',
			'sanitize_callback' => 'hybridmag_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'hybridmag_slideout_btn_loc',
		array(
			'type'    => 'select',
			'label'   => esc_html__( 'Slide-out sidebar toggle button location', 'hybridmag' ),
			'choices' => array(
				'top-bar'		=> esc_html__( 'On top bar', 'hybridmag' ),
				'before-logo'	=> esc_html__( 'Before site title/logo', 'hybridmag' ),
				'primary-menu'	=> esc_html__( 'On Primary Menu', 'hybridmag' )
			),
			'section' => 'hybridmag_slideoutsb_section',
			'active_callback'	=> 'hybridmag_is_slideout_active'
		)
	);

	// Mobile Sidebar
	$wp_customize->add_section(
		'hybridmag_mobile_menu_section',
		array(
			'title' => esc_html__( 'Mobile Menu', 'hybridmag' ),
			'priority' => 20,
			'panel'	=> 'hybridmag_panel_header'
		)
	);

	// Header - Show Secondary Menu on mobile sidebar.
	$wp_customize->add_setting(
		'hybridmag_show_top_nav_on_mobile_menu',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_top_nav_on_mobile_menu',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Top Bar Menu on Mobile Menu', 'hybridmag' ),
			'description' => esc_html__( 'Top bar menu will display on the mobile menu just after the primary menu.', 'hybridmag' ),
			'section'     => 'hybridmag_mobile_menu_section'
		)
	);

	
	// Header - show log on mobile sidebar
	$wp_customize->add_setting(
		'hybridmag_show_logo_on_mobilesb',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_logo_on_mobilesb',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Site Logo on Mobile Menu', 'hybridmag' ),
			'section'     => 'hybridmag_mobile_menu_section',
		)
	);

	// Header - Show slide out sidebar widgets in Mobile Menu Sidebar
	$wp_customize->add_setting(
		'hybridmag_show_slideout_widgets_on_mobile_menu',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_slideout_widgets_on_mobile_menu',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show Slide-out sidebar widgets on Mobile Menu', 'hybridmag' ),
			'section'     => 'hybridmag_mobile_menu_section',
			'active_callback'	=> 'hybridmag_is_slideout_active'
		)
	);

	// Header Image Location
	$wp_customize->add_setting(
		'hybridmag_header_image_location',
		array(
			'default' => 'before-header-inner',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_header_image_location',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Header Image Position', 'hybridmag' ),
			'section' => 'header_image',
			'choices' => array(
				'before-header-inner'	=> esc_html__( 'Before Logo + Content', 'hybridmag' ),
				'after-header-inner'	=> esc_html__( 'After Logo + Content', 'hybridmag' ),
				'before-site-header'	=> esc_html__( 'Before Site Header', 'hybridmag' ),
				'after-site-header'		=> esc_html__( 'After Site Header', 'hybridmag' ),
				'header-background'		=> esc_html__( 'Display as Header Background', 'hybridmag' ),
			)
		)
	);

	// Header image link to home?
	$wp_customize->add_setting(
		'hybridmag_link_header_image',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_link_header_image',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Link header image to homepage?', 'hybridmag' ),
			'section'     => 'header_image',
		)
	);

	// Color settings.
	$wp_customize->add_panel(
		'hybridmag_colors_panel',
		array(
			'title' => esc_html__( 'Colors', 'hybridmag' ),
			'priority' => 24,
		)
	);

	// Base Colors Section
	$wp_customize->add_section(
		'hybridmag_base_colors',
		array(
			'title' 	=> esc_html__( 'Base Colors', 'hybridmag' ),
			'panel'		=> 'hybridmag_colors_panel',
			'priority' 	=> 5,
		)
	);

	// Primary Color.
	$wp_customize->add_setting(
		'hybridmag_primary_color',
		array(
			'default'			=> '#65bc7b',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_primary_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'priority'			=> 1,
				'label'			    => esc_html__( 'Theme Primary Color', 'hybridmag' ),
			)
		)
	);	

	// Inner background color.
	$wp_customize->add_setting(
		'hybridmag_inner_bg_color',
		array(
			'default'			=> '',
			'transport'         => 'refresh',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_inner_bg_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Inner Background Color', 'hybridmag' ),
				'active_callback'	=> 'hybridmag_is_boxed_layout_active'
			)
		)
	);	

	// Content background color.
	$wp_customize->add_setting(
		'hybridmag_sep_content_bg_color',
		array(
			'default'			=> '',
			'transport'         => 'refresh',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_sep_content_bg_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Content Background Color', 'hybridmag' ),
				'active_callback'	=> 'hybridmag_is_separate_containers_layout_active'
			)
		)
	);

	// Text Color.
	$wp_customize->add_setting(
		'hybridmag_text_color',
		array(
			'default'			=> '#404040',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_text_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Text Color', 'hybridmag' ),
			)
		)
	);	

	// Headings Text Color.
	$wp_customize->add_setting(
		'hybridmag_headings_text_color',
		array(
			'default'			=> '#222222',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_headings_text_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Headings Text Color', 'hybridmag' ),
			)
		)
	);

	// Outer text color.
	$wp_customize->add_setting(
		'hybridmag_sep_content_outer_text_color',
		array(
			'default'			=> '',
			'transport'         => 'refresh',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_sep_content_outer_text_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Outer Text Color', 'hybridmag' ),
				'active_callback'	=> 'hybridmag_is_separate_containers_layout_active'
			)
		)
	);

	// Link Color.
	$wp_customize->add_setting(
		'hybridmag_links_color',
		array(
			'default'			=> '#0a0808',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_links_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Links Color', 'hybridmag' ),
			)
		)
	);

	// Link Color - Hover .
	$wp_customize->add_setting(
		'hybridmag_links_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_links_hover_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Links Color:Hover', 'hybridmag' ),
			)
		)
	);

	// Button Background Color.
	$wp_customize->add_setting(
		'hybridmag_button_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_button_bg_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Button Background Color', 'hybridmag' ),
			)
		)
	);

	// Button Background Color - Hover .
	$wp_customize->add_setting(
		'hybridmag_button_bg_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_button_bg_hover_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Button Background Color:Hover', 'hybridmag' ),
			)
		)
	);

	// Button Text Color.
	$wp_customize->add_setting(
		'hybridmag_button_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_button_text_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Button Text Color', 'hybridmag' ),
			)
		)
	);

	// Button Text Color - Hover .
	$wp_customize->add_setting(
		'hybridmag_button_text_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_button_text_hover_color',
			array(
				'section'		    => 'hybridmag_base_colors',
				'label'			    => esc_html__( 'Button Text Color:Hover', 'hybridmag' ),
			)
		)
	);

	// Headers Colors Section
	$wp_customize->add_section(
		'hybridmag_header_colors',
		array(
			'title' 	=> esc_html__( 'Header Colors', 'hybridmag' ),
			'panel'		=> 'hybridmag_colors_panel',
			'priority' 	=> 10,
		)
	);

	// Header BG Color
	$wp_customize->add_setting(
		'hybridmag_header_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_header_bg_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Header Background Color', 'hybridmag' ),
			)
		)
	);	

	// Menu BG Color
	$wp_customize->add_setting(
		'hybridmag_menu_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_menu_bg_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Menu Background Color', 'hybridmag' ),
				'priority'	  		=> 20,
			)
		)
	);

	// Menu Links Color
	$wp_customize->add_setting(
		'hybridmag_menu_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_menu_link_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Menu Link Color', 'hybridmag' ),
				'priority'	  		=> 25,
			)
		)
	);

	// Menu Links Color: Hover
	$wp_customize->add_setting(
		'hybridmag_menu_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_menu_link_hover_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Menu Link Color: Hover/Active', 'hybridmag' ),
				'priority'	  		=> 30,
			)
		)
	);

	// Menu Links Color: Action
	$wp_customize->add_setting(
		'hybridmag_menu_link_action_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_menu_link_action_hover_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Menu Link Action Color: Hover/Active', 'hybridmag' ),
				'priority'	  		=> 35,
			)
		)
	);

	// Dropdown Menu BG Color
	$wp_customize->add_setting(
		'hybridmag_dropdown_menu_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_dropdown_menu_bg_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Dropdown Menu Background Color', 'hybridmag' ),
				'priority'	  		=> 40,
			)
		)
	);

	// Dropdown Menu Link Color
	$wp_customize->add_setting(
		'hybridmag_dropdown_menu_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_dropdown_menu_link_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Dropdown Menu Link Color', 'hybridmag' ),
				'priority'	  		=> 45,
			)
		)
	);

	// Dropdown Menu Link Hover Color
	$wp_customize->add_setting(
		'hybridmag_dropdown_menu_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_dropdown_menu_link_hover_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Dropdown Menu Link Color: Hover/Active', 'hybridmag' ),
				'priority'	  		=> 50,
			)
		)
	);

	// Dropdown Menu Link Hover Background Color
	$wp_customize->add_setting(
		'hybridmag_dropdown_menu_link_hover_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_dropdown_menu_link_hover_bg_color',
			array(
				'section'		    => 'hybridmag_header_colors',
				'label'			    => esc_html__( 'Dropdown Menu Link Background Color: Hover/Active', 'hybridmag' ),
				'priority'	  		=> 55,
			)
		)
	);

	// Top Bar Colors Section
	$wp_customize->add_section(
		'hybridmag_topbar_colors',
		array(
			'title' 	=> esc_html__( 'Top Bar Colors', 'hybridmag' ),
			'panel'		=> 'hybridmag_colors_panel',
			'priority' 	=> 15,
		)
	);

	// Topbar BG Color
	$wp_customize->add_setting(
		'hybridmag_topbar_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_topbar_bg_color',
			array(
				'section'		    => 'hybridmag_topbar_colors',
				'label'			    => esc_html__( 'Background Color', 'hybridmag' ),
			)
		)
	);

	// Topbar Links Color
	$wp_customize->add_setting(
		'hybridmag_topbar_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_topbar_link_color',
			array(
				'section'		    => 'hybridmag_topbar_colors',
				'label'			    => esc_html__( 'Link Color', 'hybridmag' ),
			)
		)
	);

	// Menu Links Color: Hover
	$wp_customize->add_setting(
		'hybridmag_topbar_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_topbar_link_hover_color',
			array(
				'section'		    => 'hybridmag_topbar_colors',
				'label'			    => esc_html__( 'Link Color: Hover/Active', 'hybridmag' ),
			)
		)
	);

	// Topbar Text Color
	$wp_customize->add_setting(
		'hybridmag_topbar_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_topbar_text_color',
			array(
				'section'		    => 'hybridmag_topbar_colors',
				'label'			    => esc_html__( 'Text Color', 'hybridmag' ),
			)
		)
	);
	

	// Footer Colors Section
	$wp_customize->add_section(
		'hybridmag_footer_colors',
		array(
			'title' 	=> esc_html__( 'Footer Colors', 'hybridmag' ),
			'panel'		=> 'hybridmag_colors_panel',
			'priority' 	=> 20,
		)
	);

	// Footer Widget Area Bg Color.
	$wp_customize->add_setting(
		'hybridmag_footer_widget_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_footer_widget_bg_color',
			array(
				'section'		    => 'hybridmag_footer_colors',
				'label'			    => esc_html__( 'Widget Area Background Color', 'hybridmag' ),
			)
		)
	);

	// Footer Widget Text Color.
	$wp_customize->add_setting(
		'hybridmag_footer_widget_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_footer_widget_text_color',
			array(
				'section'		    => 'hybridmag_footer_colors',
				'label'			    => esc_html__( 'Widget Text Color', 'hybridmag' ),
			)
		)
	);

	// Footer Widget Links Color.
	$wp_customize->add_setting(
		'hybridmag_footer_widget_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_footer_widget_link_color',
			array(
				'section'		    => 'hybridmag_footer_colors',
				'label'			    => esc_html__( 'Widget Link Color', 'hybridmag' ),
			)
		)
	);

	// Footer Widget Links Hover Color.
	$wp_customize->add_setting(
		'hybridmag_footer_widget_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_footer_widget_link_hover_color',
			array(
				'section'		    => 'hybridmag_footer_colors',
				'label'			    => esc_html__( 'Widget Link Color:Hover', 'hybridmag' ),
			)
		)
	);

	// Footer bottom Area Bg Color.
	$wp_customize->add_setting(
		'hybridmag_footer_bottom_bg_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_footer_bottom_bg_color',
			array(
				'section'		    => 'hybridmag_footer_colors',
				'label'			    => esc_html__( 'Footer Bottom Area Background Color', 'hybridmag' ),
			)
		)
	);

	// Footer bottom Text Color.
	$wp_customize->add_setting(
		'hybridmag_footer_bottom_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_footer_bottom_text_color',
			array(
				'section'		    => 'hybridmag_footer_colors',
				'label'			    => esc_html__( 'Footer Bottom Text Color', 'hybridmag' ),
			)
		)
	);

	// Footer bottom Links Color.
	$wp_customize->add_setting(
		'hybridmag_footer_bottom_link_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_footer_bottom_link_color',
			array(
				'section'		    => 'hybridmag_footer_colors',
				'label'			    => esc_html__( 'Footer Bottom Link Color', 'hybridmag' ),
			)
		)
	);

	// Footer bottom Links Hover Color.
	$wp_customize->add_setting(
		'hybridmag_footer_bottom_link_hover_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_footer_bottom_link_hover_color',
			array(
				'section'		    => 'hybridmag_footer_colors',
				'label'			    => esc_html__( 'Footer Bottom Link Color:Hover', 'hybridmag' ),
			)
		)
	);

	// Category Colors Section
	$wp_customize->add_section(
		'hybridmag_category_colors',
		array(
			'title' 	=> esc_html__( 'Category Colors', 'hybridmag' ),
			'panel'		=> 'hybridmag_colors_panel',
			'priority' 	=> 25,
		)
	);

	$wp_customize->add_setting(
		'hybridmag_category_text_color',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_alpha_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( 
			$wp_customize,
			'hybridmag_category_text_color',
			array(
				'section'		    => 'hybridmag_category_colors',
				'label'			    => esc_html__( 'Category Text Color', 'hybridmag' ),
			)
		)
	);

	$categories = get_categories( array( 'hide_empty' => 1 ) );
	foreach ( $categories as $category ) {

		$term_id = $category->term_id;

		$wp_customize->add_setting(
			'hybridmag_cat_color_' . esc_attr( $term_id ),
			array(
				'default'			=> '',
				'capability'		=> 'edit_theme_options',
				'sanitize_callback'	=> 'hybridmag_sanitize_hex_color'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control( 
				$wp_customize,
				'hybridmag_cat_color_' . esc_attr( $term_id ),
				array(
					'section'		    => 'hybridmag_category_colors',
					'label'			    => sprintf( esc_html__( '%1$s Color', 'hybridmag' ), esc_html( $category->name ) ),
				)
			)
		);

	}

	// Featured Section settings.
	$wp_customize->add_panel(
		'hybridmag_featured_panel',
		array(
			'title' => esc_html__( 'Featured Sections', 'hybridmag' ),
			'priority' => 28,
		)
	);

	$wp_customize->add_section(
		'hybridmag_sfp_section',
		array(
			'title' 	=> esc_html__( 'Small Posts Top', 'hybridmag' ),
			'panel'		=> 'hybridmag_featured_panel',
			'priority' 	=> 5,
		)
	);

	/**
	 * Slider + 2 Posts Section
	 */
	$wp_customize->add_section(
		'hybridmag_featured_slider',
		array(
			'title' 	=> esc_html__( 'Slider + 2 Posts', 'hybridmag' ),
			'panel'		=> 'hybridmag_featured_panel',
			'priority' 	=> 10,
		)
	);

	// Archive - Show category list
	$wp_customize->add_setting( 
		'hybridmag_show_featured_content',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_show_featured_content',
		array(
			'label' 	=> esc_html__( 'Display slider + 2 posts.', 'hybridmag' ),
			'section' 	=> 'hybridmag_featured_slider',
			'priority'	=> 3,
		)
	) );

	$wp_customize->add_setting(
		'hybridmag_featured_content_displayed_on',
		array(
			'default'           => array( 'front' ),
			'transport'         => 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_multiple_checkboxes'
		)
	);

	$wp_customize->add_control(
		new HybridMag_Multiple_Checkboxes(
			$wp_customize,
			'hybridmag_featured_content_displayed_on',
			array(
				'section' => 'hybridmag_featured_slider',
				'label'   => esc_html__( 'Where to display slider + 2 posts?', 'hybridmag' ),
				'choices' => array(
					'front'		=> esc_html__( 'Front Page', 'hybridmag' ),
					'blog'		=> esc_html__( 'Blog Posts Page', 'hybridmag' )
				),
				'priority'	=> 6,
				'active_callback' => 'hybridmag_is_featured_content_active'
			)
		)
	);

	// Slider Posts Source.
	$wp_customize->add_setting(
		'hybridmag_slider_posts_source',
		array(
			'default' => 'latest',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_slider_posts_source',
		array(
			'type' 		=> 'select',
			'label' 	=> esc_html__( 'Slider Posts Source', 'hybridmag' ),
			'section' 	=> 'hybridmag_featured_slider',
			'choices' 	=> array(
				'latest' 	=> esc_html__( 'Latest Posts', 'hybridmag' ),
				'category' 	=> esc_html__( 'By Category', 'hybridmag' ),
				'tag' 		=> esc_html__( 'By Tag', 'hybridmag' ),
				'sticky'	=> esc_html__( 'Sticky Posts', 'hybridmag' ),
			),
			'priority'	=> 9,
			'active_callback' => 'hybridmag_is_featured_content_active'
		)
	);

	// Slider Posts Source - Category
	$wp_customize->add_setting(
		'hybridmag_slider_posts_category',
		array(
			'default'			=> '0',
			'sanitize_callback'	=> 'hybridmag_sanitize_category_dropdown'
		)
	);

	$wp_customize->add_control(
		new HybridMag_Customize_Category_Control( 
			$wp_customize,
			'hybridmag_slider_posts_category', 
			array(
			    'label'   			=> esc_html__( 'Select the category for slider posts.', 'hybridmag' ),
			    'section' 			=> 'hybridmag_featured_slider',
				'priority'			=> 12,
				'active_callback'	=> 'hybridmag_is_slider_source_category'
			) 
		) 
	);

	// Slider Posts Source - Tag
	$wp_customize->add_setting(
		'hybridmag_slider_posts_tag',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'hybridmag_slider_posts_tag',
		array(
			'section'			=> 'hybridmag_featured_slider',
			'type'				=> 'text',
			'label'				=> esc_html__( 'Enter the tag slug', 'hybridmag' ),
			'priority'			=> 15,
			'active_callback'	=> 'hybridmag_is_slider_source_tag'
		)
	);

	// ignore sticky posts slider
	$wp_customize->add_setting(
		'hybridmag_ignore_sticky_posts_slider',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_ignore_sticky_posts_slider',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Ignore Sticky Posts', 'hybridmag' ),
			'section'     		=> 'hybridmag_featured_slider',
			'priority'			=> 18,
			'active_callback'	=> 'hybridmag_is_not_sticky_posts_slider'
		)
	);

	// Archive - Show category list
	$wp_customize->add_setting( 
		'hybridmag_slider_autoplay',
		array(
			'default' 			=> false,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_slider_autoplay',
		array(
			'label' 	=> esc_html__( 'Slider autoplay', 'hybridmag' ),
			'section' 	=> 'hybridmag_featured_slider',
			'priority'	=> 21,
			'active_callback' => 'hybridmag_is_featured_content_active'
		)
	) );

	// Slider - autoplay cycle length
	$wp_customize->add_setting( 
		'hybridmag_slider_autoplay_delay',
		array(
			'default'           => 6,
			'sanitize_callback' => 'hybridmag_sanitize_slider_number_input'
		)
	);
	$wp_customize->add_control( 
		new HybridMag_Slider_Control( $wp_customize, 'hybridmag_slider_autoplay_delay',
		array(
			'label'         => esc_html__( 'Slider autoplay delay in seconds', 'hybridmag' ),
			'section'       => 'hybridmag_featured_slider',
			'input_attrs'   => array(
				'min'   => 3,
				'max'   => 30,
				'step'  => 1,
			),
			'priority'	=> 24,
			'active_callback' => 'hybridmag_is_slider_autoplay_active'
		)
	) );

	// Featured Posts Source.
	$wp_customize->add_setting(
		'hybridmag_featured_posts_source',
		array(
			'default' => 'latest',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_featured_posts_source',
		array(
			'type' 		=> 'select',
			'label' 	=> esc_html__( 'Featured Posts Source', 'hybridmag' ),
			'section' 	=> 'hybridmag_featured_slider',
			'choices' 	=> array(
				'latest' 	=> esc_html__( 'Latest Posts', 'hybridmag' ),
				'category' 	=> esc_html__( 'By Category', 'hybridmag' ),
				'tag' 		=> esc_html__( 'By Tag', 'hybridmag' ),
				'sticky'	=> esc_html__( 'Sticky Posts', 'hybridmag' ),
			),
			'priority'	=> 27,
			'active_callback' => 'hybridmag_is_featured_content_active'
		)
	);

	// Featured Posts Source - Category
	$wp_customize->add_setting(
		'hybridmag_featured_posts_category',
		array(
			'default'			=> '0',
			'sanitize_callback'	=> 'hybridmag_sanitize_category_dropdown'
		)
	);

	$wp_customize->add_control(
		new HybridMag_Customize_Category_Control( 
			$wp_customize,
			'hybridmag_featured_posts_category', 
			array(
			    'label'   			=> esc_html__( 'Select the category for featured posts.', 'hybridmag' ),
			    'section' 			=> 'hybridmag_featured_slider',
				'priority'			=> 30,
				'active_callback'	=> 'hybridmag_is_fps_source_category'
			) 
		) 
	);

	// Featured Posts Source - Tag
	$wp_customize->add_setting(
		'hybridmag_featured_posts_tag',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'hybridmag_featured_posts_tag',
		array(
			'section'			=> 'hybridmag_featured_slider',
			'type'				=> 'text',
			'label'				=> esc_html__( 'Enter the tag slug', 'hybridmag' ),
			'priority'			=> 33,
			'active_callback'	=> 'hybridmag_is_fps_source_tag'
		)
	);
	
	// Remove placeholder image
	$wp_customize->add_setting(
		'hybridmag_remove_placeholder',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_remove_placeholder',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Hide placeholder image.', 'hybridmag' ),
			'section'     => 'hybridmag_featured_slider',
			'priority'	  => 36,
			'active_callback' => 'hybridmag_is_featured_content_active'
		)
	);

	$wp_customize->add_section(
		'hybridmag_featured_tabs',
		array(
			'title' 	=> esc_html__( 'Tabbed Posts', 'hybridmag' ),
			'panel'		=> 'hybridmag_featured_panel',
			'priority' 	=> 10,
		)
	);

	// Show tabbed posts section
	$wp_customize->add_setting( 
		'hybridmag_display_tabbed_posts',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_display_tabbed_posts',
		array(
			'label' 	=> esc_html__( 'Display tabbed posts section', 'hybridmag' ),
			'section' 	=> 'hybridmag_featured_tabs'
		)
	) );

	// Where to display tabbed posts?
	$wp_customize->add_setting(
		'hybridmag_featured_tabs_displayed_on',
		array(
			'default'           => array( 'front' ),
			'transport'         => 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_multiple_checkboxes'
		)
	);
	$wp_customize->add_control(
		new HybridMag_Multiple_Checkboxes(
			$wp_customize,
			'hybridmag_featured_tabs_displayed_on',
			array(
				'section' => 'hybridmag_featured_tabs',
				'label'   => esc_html__( 'Where to display tabbed posts?', 'hybridmag' ),
				'choices' => array(
					'front'		=> esc_html__( 'Front Page', 'hybridmag' ),
					'blog'		=> esc_html__( 'Blog Posts Page', 'hybridmag' )
				),
				'active_callback' => 'hybridmag_is_tabbed_posts_active'
			)
		)
	);

	for ( $i = 1; $i <= 5; $i++ ) {
		// Tab 1 - Category
		$wp_customize->add_setting(
			'hybridmag_tab_posts'. $i .'_category',
			array(
				'default'			=> '0',
				'sanitize_callback'	=> 'hybridmag_sanitize_category_dropdown'
			)
		);
		$wp_customize->add_control(
			new HybridMag_Customize_Category_Control( 
				$wp_customize,
				'hybridmag_tab_posts'. $i .'_category', 
				array(
					'label'   			=> sprintf( 
						/* translators: %s: tab number in tabbed posts section. */
						esc_html__( 'Select the category for tab %s', 'hybridmag' ), 
						$i 
					),
					'section' 			=> 'hybridmag_featured_tabs',
					'active_callback' 	=> 'hybridmag_is_tabbed_posts_active'
				) 
			) 
		);
	}

	// View all text
	$wp_customize->add_setting(
		'hybridmag_tabbed_view_all_text',
		array(
			'default'			=> esc_html__( 'View More', 'hybridmag' ),
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'hybridmag_tabbed_view_all_text',
		array(
			'section'			=> 'hybridmag_featured_tabs',
			'type'				=> 'text',
			'label'				=> esc_html__( 'View all text', 'hybridmag' ),
			'active_callback' 	=> 'hybridmag_is_tabbed_posts_active'
		)
	);
	
	/**
	 * Blog Settings Panel
	 */ 
	$wp_customize->add_panel(
		'hybridmag_panel_blog',
		array(
			'priority' 			=> 30,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Blog / Archive', 'hybridmag' )
		)
	);

	$wp_customize->add_section(
		'hybridmag_blog_layout_section',
		array(
			'title' => esc_html__( 'Blog Layout', 'hybridmag' ),
			'priority' => 5,
			'panel'	=> 'hybridmag_panel_blog'
		)
	);

	// Archive Layout / Sidebar Alignment
	$wp_customize->add_setting(
		'hybridmag_archive_layout',
		array(
			'default'			=> 'right-sidebar',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		new HybridMag_Radio_Image_Control( 
			$wp_customize,
			'hybridmag_archive_layout',
			array(
				'section'		=> 'hybridmag_blog_layout_section',
				'label'			=> esc_html__( 'Blog Layout', 'hybridmag' ),
				'choices'		=> array(
					'right-sidebar'	        => $images_uri . '2cr.png',
					'left-sidebar' 	        => $images_uri . '2cl.png',
					'no-sidebar' 		    => $images_uri . '1c.png',
					'center-content' 	    => $images_uri . '1cc.png'
				)
			)
		)
	);

	// Entries Layout
	$wp_customize->add_setting(
		'hybridmag_entries_layout',
		array(
			'default' => 'list',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_entries_layout',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Entries Layout', 'hybridmag' ),
			'section' => 'hybridmag_blog_layout_section',
			'choices' => array(
				'list' => esc_html__( 'List', 'hybridmag' ),
				'grid' => esc_html__( 'Grid', 'hybridmag' )
			)
		)
	);

	// Number of grid columns.
	$wp_customize->add_setting(
		'hybridmag_entries_grid_columns',
		array(
			'default' => '2',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_entries_grid_columns',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Number of grid columns', 'hybridmag' ),
			'section' => 'hybridmag_blog_layout_section',
			'choices' => array(
				'2' => esc_html__( '2', 'hybridmag' ),
				'3' => esc_html__( '3', 'hybridmag' ),
				'4' => esc_html__( '4', 'hybridmag' ),
				'5' => esc_html__( '5', 'hybridmag' ),
				'6' => esc_html__( '6', 'hybridmag' ),
			),
			'active_callback'	=> 'hybridmag_is_entries_grid'
		)
	);

	// Archive - Featured Image Position.
	$wp_customize->add_setting(
		'hybridmag_archive_thumbnail_position',
		array(
			'default' 			=> hybridmag_get_archive_image_position_default(),
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_archive_thumbnail_position',
		array(
			'type' => 'radio',
			'label' => esc_html__( 'Featured Image Position', 'hybridmag' ),
			'section' => 'hybridmag_blog_layout_section',
			'choices' => array(
				'before-header' => esc_html__( 'Before article header', 'hybridmag' ),
				'after-header' => esc_html__( 'After article header', 'hybridmag' ),
				'beside-article' => esc_html__( 'Beside article', 'hybridmag' ),
				'beside-content' => esc_html__( 'Beside article content', 'hybridmag' ),
				'hidden' => esc_html__( 'Hidden', 'hybridmag' ),
			)
		)
	);

	// Archive Featured Image Align
	$wp_customize->add_setting(
		'hybridmag_archive_thumbnail_align',
		array(
			'default' => 'left',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_archive_thumbnail_align',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Featured Image Align', 'hybridmag' ),
			'section' => 'hybridmag_blog_layout_section',
			'choices' => array(
				'left' => esc_html__( 'Left', 'hybridmag' ),
				'right' => esc_html__( 'Right', 'hybridmag' )
			),
			'active_callback'	=> 'hybridmag_thumbnail_align_active'
		)
	);

	// Archive - Leave featured image uncropped
	$wp_customize->add_setting(
		'hybridmag_archive_image_crop',
		array(
			'default'           => true,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_archive_image_crop',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Crop featured image to theme defined size? (changes require regenerating thumbnails for existing featured images)', 'hybridmag' ),
			'section'     => 'hybridmag_blog_layout_section',
		)
	);

	// Archive - Pagination Style
	$wp_customize->add_setting(
		'hybridmag_pagination_type',
		array(
			'default' => 'page-numbers',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_pagination_type',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Blog Pagination Style', 'hybridmag' ),
			'section' => 'hybridmag_blog_layout_section',
			'choices' => array(
				'page-numbers' => esc_html__( 'Numbers', 'hybridmag' ),
				'next-prev' => esc_html__( 'Next/Prev', 'hybridmag' )
			)
		)
	);

	$wp_customize->add_section(
		'hybridmag_blog_meta_section',
		array(
			'title' => esc_html__( 'Post Meta', 'hybridmag' ),
			'priority' => 15,
			'panel'	=> 'hybridmag_panel_blog'
		)
	);

	// Archive - Show category list
	$wp_customize->add_setting( 
		'hybridmag_show_cat_links',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_show_cat_links',
		array(
			'label' 	=> esc_html__( 'Show category links top', 'hybridmag' ),
			'section' 	=> 'hybridmag_blog_meta_section'
		)
	) );

	// Entry Meta Sort and show/hide
	$wp_customize->add_setting( 
		'hybridmag_archive_entry_meta',
		array(
			'default' 			=> 'author,date,comments',
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_sortable_checkbox'
		)
	);
	$wp_customize->add_control( new HybridMag_Pill_Checkbox_Custom_Control( $wp_customize, 'hybridmag_archive_entry_meta',
		array(
			'label' 		=> esc_html__( 'Post Meta', 'hybridmag' ),
			'description' 	=> esc_html__( 'Select what you want to display and order them as you prefer', 'hybridmag' ),
			'section' 		=> 'hybridmag_blog_meta_section',
			'input_attrs' 	=> array(
				'sortable' 	=> true,
				'fullwidth' => true,
			),
			'choices' => array(
				'author' 		=> esc_html__( 'Author', 'hybridmag' ),
				'date' 			=> esc_html__( 'Date', 'hybridmag' ),
				'comments' 		=> esc_html__( 'Comments', 'hybridmag'  ),
				'categories' 	=> esc_html__( 'Categories', 'hybridmag'  ),
				'tags' 			=> esc_html__( 'Tags', 'hybridmag'  ),
			)
		)
	) );

	// Entry meta display location
	$wp_customize->add_setting(
		'hybridmag_entry_meta_location',
		array(
			'default' => 'footer',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_entry_meta_location',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Post Meta Display', 'hybridmag' ),
			'section' => 'hybridmag_blog_meta_section',
			'choices' => array(
				'footer' 	=> esc_html__( 'After Content/Excerpt', 'hybridmag' ),
				'header' 	=> esc_html__( 'After Post Title', 'hybridmag' )
			)
		)
	);

	// Archive - Meta Separator
	$wp_customize->add_setting(
		'hybridmag_entry_meta_seperator',
		array(
			'default' => 'dot',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_entry_meta_seperator',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Meta Separator', 'hybridmag' ),
			'section' => 'hybridmag_blog_meta_section',
			'choices' => array(
				'dot'		=> '•',
				'slash'		=> '/',
				'vbar'		=> '|',
				'mdash'		=> '—',
				'dash'		=> '-',
				'icon' 		=> esc_html__( 'Icon', 'hybridmag' ),
				'none'		=> esc_html__( 'None', 'hybridmag' ),
			)
		)
	);

	// Archive - Show author avatar
	$wp_customize->add_setting(
		'hybridmag_show_author_avatar',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_author_avatar',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show author avatar', 'hybridmag' ),
			'section'     => 'hybridmag_blog_meta_section',
			'active_callback'	=> 'hybridmag_is_showing_author'
		)
	);

	// Archive - Show time ago format
	$wp_customize->add_setting(
		'hybridmag_time_ago',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_time_ago',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Use "time ago" date format', 'hybridmag' ),
			'section'     		=> 'hybridmag_blog_meta_section',
			'active_callback'	=> 'hybridmag_is_showing_date'
		)
	);

	// Archive - Cut off for "time ago" date in days
	$wp_customize->add_setting(
		'hybridmag_time_ago_date_count',
		array(
			'default'			=> 14,
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_absint'
		)
	);
	$wp_customize->add_control(
		'hybridmag_time_ago_date_count',
		array(
			'section'			=> 'hybridmag_blog_meta_section',
			'type'				=> 'number',
			'label'				=> esc_html__( 'Cut off for "time ago" date in days.', 'hybridmag' ),
		)
	);	

	// Archive - Show updated date
	$wp_customize->add_setting(
		'hybridmag_show_updated_date',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_updated_date',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Show "last updated" date.', 'hybridmag' ),
			'description' 		=> esc_html__( 'When paired with the "time ago" date format, the cut off for that format will automatically be switched to one day.', 'hybridmag' ),
			'section'     		=> 'hybridmag_blog_meta_section',
			'active_callback'	=> 'hybridmag_is_showing_date'
		)
	);

	// Content type
	$wp_customize->add_setting(
		'hybridmag_content_type',
		array(
			'default' => 'excerpt',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_content_type',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Content Type', 'hybridmag' ),
			'section' => 'hybridmag_blog_meta_section',
			'choices' => array(
				'excerpt' 	=> esc_html__( 'Excerpt', 'hybridmag' ),
				'content' 	=> esc_html__( 'Content', 'hybridmag' ),
				'none'		=> esc_html__( 'None', 'hybridmag' )
			)
		)
	);

	// Excerpt length.
	$wp_customize->add_setting(
		'hybridmag_excerpt_length',
		array(
			'default'			=> 16,
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_number_absint'
		)
	);
	$wp_customize->add_control(
		'hybridmag_excerpt_length',
		array(
			'section'			=> 'hybridmag_blog_meta_section',
			'type'				=> 'number',
			'label'				=> esc_html__( 'Excerpt Length', 'hybridmag' ),
			'active_callback'	=> 'hybridmag_is_excerpt_type'
		)
	);

	// Archive - Read More Link
	$wp_customize->add_setting(
		'hybridmag_read_more_type',
		array(
			'default' => 'link',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_read_more_type',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Read More Link Type', 'hybridmag' ),
			'section' => 'hybridmag_blog_meta_section',
			'choices' => array(
				'link'		=> esc_html__( 'Link', 'hybridmag' ),
				'button' 	=> esc_html__( 'Button', 'hybridmag' ),
				'none'		=> esc_html__( 'None', 'hybridmag' )
			)
		)
	);

	// Post Settings Panel
	$wp_customize->add_panel(
		'hybridmag_panel_post',
		array(
			'priority' 			=> 32,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Single Posts', 'hybridmag' )
		)
	);

	$wp_customize->add_section(
		'hybridmag_post_layout_section',
		array(
			'title' => esc_html__( 'Post Layout', 'hybridmag' ),
			'priority' => 5,
			'panel'	=> 'hybridmag_panel_post'
		)
	);

	// Post Layout / Sidebar Alignment
	$wp_customize->add_setting(
		'hybridmag_post_layout',
		array(
			'default'			=> 'right-sidebar',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		new HybridMag_Radio_Image_Control( 
			$wp_customize,
			'hybridmag_post_layout',
			array(
				'section'		=> 'hybridmag_post_layout_section',
				'label'			=> esc_html__( 'Post Layout', 'hybridmag' ),
				'choices'		=> array(
					'right-sidebar'	        => $images_uri . '2cr.png',
					'left-sidebar' 	        => $images_uri . '2cl.png',
					'no-sidebar' 		    => $images_uri . '1c.png',
					'center-content' 	    => $images_uri . '1cc.png'
				)
			)
		)
	);

	// Post - Featured Image Position.
	$wp_customize->add_setting(
		'hybridmag_post_image_position',
		array(
			'default' => 'after-header',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_post_image_position',
		array(
			'type' => 'radio',
			'label' => esc_html__( 'Featured Image Position', 'hybridmag' ),
			'section' => 'hybridmag_post_layout_section',
			'choices' => array(
				'before-header' => esc_html__( 'Before article header', 'hybridmag' ),
				'after-header' => esc_html__( 'After article header', 'hybridmag' ),
				'hidden' => esc_html__( 'Hidden', 'hybridmag' ),
			)
		)
	);

	// Post Meta Section
	$wp_customize->add_section(
		'hybridmag_post_meta_section',
		array(
			'title' => esc_html__( 'Post Meta', 'hybridmag' ),
			'priority' => 10,
			'panel'	=> 'hybridmag_panel_post'
		)
	);

	// Post - Show category list
	$wp_customize->add_setting( 
		'hybridmag_show_cat_links_s',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_show_cat_links_s',
		array(
			'label' 	=> esc_html__( 'Show category links top', 'hybridmag' ),
			'section' 	=> 'hybridmag_post_meta_section'
		)
	) );

	// Entry Meta Sort and show/hide
	$wp_customize->add_setting( 
		'hybridmag_archive_entry_meta_s',
		array(
			'default' 			=> 'author,date,comments',
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_sortable_checkbox'
		)
	);
	$wp_customize->add_control( new HybridMag_Pill_Checkbox_Custom_Control( $wp_customize, 'hybridmag_archive_entry_meta_s',
		array(
			'label' 		=> esc_html__( 'Post Meta', 'hybridmag' ),
			'description' 	=> esc_html__( 'Select what you want to display and order them as you prefer', 'hybridmag' ),
			'section' 		=> 'hybridmag_post_meta_section',
			'input_attrs' 	=> array(
				'sortable' 	=> true,
				'fullwidth' => true,
			),
			'choices' => array(
				'author' 		=> esc_html__( 'Author', 'hybridmag' ),
				'date' 			=> esc_html__( 'Date', 'hybridmag' ),
				'comments' 		=> esc_html__( 'Comments', 'hybridmag'  ),
				'categories' 	=> esc_html__( 'Categories', 'hybridmag'  ),
				'tags' 			=> esc_html__( 'Tags', 'hybridmag'  ),
			)
		)
	) );

	// Post - Show author avatar
	$wp_customize->add_setting(
		'hybridmag_show_author_avatar_s',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_author_avatar_s',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Show author avatar', 'hybridmag' ),
			'section'     => 'hybridmag_post_meta_section',
			'active_callback'	=> 'hybridmag_is_showing_author_s'
		)
	);

	// Post - Show time ago format
	$wp_customize->add_setting(
		'hybridmag_time_ago_s',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_time_ago_s',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Use "time ago" date format', 'hybridmag' ),
			'description' => sprintf(
				/* translators: %s: link to the setting - Cut off for "time ago" date in days. */
				esc_html__( 'You can set the number of cut off days from %1$s.', 'hybridmag' ),
				'<a rel="goto-control" href="#hybridmag_time_ago_date_count">' . esc_html__( 'Blog Settings', 'hybridmag' ) . '</a>'
			),
			'section'     		=> 'hybridmag_post_meta_section',
			'active_callback'	=> 'hybridmag_is_showing_date_s'
		)
	);

	// Post - Show updated date format
	$wp_customize->add_setting(
		'hybridmag_show_updated_date_s',
		array(
			'default'           => false,
			'sanitize_callback' => 'hybridmag_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'hybridmag_show_updated_date_s',
		array(
			'type'        		=> 'checkbox',
			'label'       		=> esc_html__( 'Show "last updated" date.', 'hybridmag' ),
			'description' 		=> esc_html__( 'When paired with the "time ago" date format, the cut off for that format will automatically be switched to one day.', 'hybridmag' ),
			'section'     		=> 'hybridmag_post_meta_section',
			'active_callback'	=> 'hybridmag_is_showing_date_s'
		)
	);

	// Post - Show tags
	$wp_customize->add_setting( 
		'hybridmag_show_tags_list_s',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_show_tags_list_s',
		array(
			'label' 	=> esc_html__( 'Show tags list', 'hybridmag' ),
			'section' 	=> 'hybridmag_post_meta_section'
		)
	) );
	
	// Post - Show Next Previous Links
	$wp_customize->add_setting( 
		'hybridmag_post_previous_next',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_post_previous_next',
		array(
			'label' 	=> esc_html__( 'Display previous and next links at the bottom of each post.', 'hybridmag' ),
			'section' 	=> 'hybridmag_post_meta_section'
		)
	) );
	
	// Post - Show Author Bio
	$wp_customize->add_setting( 
		'hybridmag_show_author_bio',
		array(
			'default' 			=> true,
			'transport' 		=> 'refresh',
			'sanitize_callback' => 'hybridmag_sanitize_switch'
		)
	);
	$wp_customize->add_control( new HybridMag_Toggle_Switch_Control( $wp_customize, 'hybridmag_show_author_bio',
		array(
			'label' 	=> esc_html__( 'Display author bio at the bottom of the post.', 'hybridmag' ),
			'section' 	=> 'hybridmag_post_meta_section'
		)
	) );

	// Page Settings Section
	$wp_customize->add_section(
		'hybridmag_page_section',
		array(
			'title' => esc_html__( 'Pages', 'hybridmag' ),
			'priority' => 34
		)
	);

	$wp_customize->add_setting(
		'hybridmag_page_layout',
		array(
			'default'			=> 'right-sidebar',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		new HybridMag_Radio_Image_Control( 
			$wp_customize,
			'hybridmag_page_layout',
			array(
				'section'		=> 'hybridmag_page_section',
				'label'			=> esc_html__( 'Page Layout', 'hybridmag' ),
				'choices'		=> array(
					'right-sidebar'	        => $images_uri . '2cr.png',
					'left-sidebar' 	        => $images_uri . '2cl.png',
					'no-sidebar' 		    => $images_uri . '1c.png',
					'center-content' 	    => $images_uri . '1cc.png'
				)
			)
		)
	);

	// Footer Panel
	$wp_customize->add_panel(
		'hybridmag_panel_footer',
		array(
			'priority' 			=> 36,
			'capability' 		=> 'edit_theme_options',
			'title' 			=> esc_html__( 'Footer', 'hybridmag' )
		)
	);

	// Footer Widgets
	$wp_customize->add_section(
		'hybridmag_footer_widgets_section',
		array(
			'title' => esc_html__( 'Footer Widgets', 'hybridmag' ),
			'priority' => 10,
			'panel'	=> 'hybridmag_panel_footer'
		)
	);

	// Footer Number of sidebars
	$wp_customize->add_setting(
		'hybridmag_footer_sidebar_count',
		array(
			'default' => '3',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_footer_sidebar_count',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Widget Columns', 'hybridmag' ),
			'section' => 'hybridmag_footer_widgets_section',
			'choices' => array(
				'1' => esc_html__( '1', 'hybridmag' ),
				'2' => esc_html__( '2', 'hybridmag' ),
				'3' => esc_html__( '3', 'hybridmag' ),
				'4' => esc_html__( '4', 'hybridmag' )
			)
		)
	);

	$wp_customize->add_setting(
		'hybridmag_footer_widget_area_width',
		array(
			'default' => 'contained',
			'sanitize_callback' => 'hybridmag_sanitize_select'
		)
	);
	$wp_customize->add_control(
		'hybridmag_footer_widget_area_width',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Widget Area Width', 'hybridmag' ),
			'section' => 'hybridmag_footer_widgets_section',
			'choices' => array(
				'full' 		=> esc_html__( 'Full', 'hybridmag' ),
				'contained' => esc_html__( 'Contained', 'hybridmag' )
			)
		)
	);

	$wp_customize->add_section(
		'hybridmag_footer_bottom_section',
		array(
			'title' => esc_html__( 'Footer Bottom', 'hybridmag' ),
			'priority' => 15,
			'panel'	=> 'hybridmag_panel_footer'
		)
	);

	$wp_customize->add_setting(
		'hybridmag_footer_copyright_text',
		array(
			'default'			=> '',
			'type'				=> 'theme_mod',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'hybridmag_sanitize_html'
		)
	);
	$wp_customize->add_control(
		'hybridmag_footer_copyright_text',
		array(
			'section'		=> 'hybridmag_footer_bottom_section',
			'type'			=> 'textarea',
			'label'			=> esc_html__( 'Copyright Text', 'hybridmag' )
		)
	);

}
add_action( 'customize_register', 'hybridmag_customize_register' );

/**
 * Gets the default image position based on the selected post layout.
 */
function hybridmag_get_archive_image_position_default() {
	$entries_layout = get_theme_mod( 'hybridmag_entries_layout', 'list' );
	if ( 'list' === $entries_layout ) {
		return 'beside-article';
	} elseif ( 'grid' === $entries_layout ) {
		return 'before-header';
	}
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function hybridmag_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function hybridmag_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function hybridmag_customize_preview_js() {
	wp_enqueue_script( 'hm-customizer', get_template_directory_uri() . '/inc/customizer/assets/js/customizer.js', array( 'customize-preview' ), HYBRIDMAG_VERSION, true );
}
add_action( 'customize_preview_init', 'hybridmag_customize_preview_js' );

/**
 * Enqueue the customizer stylesheet.
 */
function hybridmag_enqueue_customizer_stylesheets() {
    wp_register_style( 'hm-customizer-css', get_template_directory_uri() . '/inc/customizer/assets/css/customizer.css', NULL, NULL, 'all' );
    wp_enqueue_style( 'hm-customizer-css' );
}
add_action( 'customize_controls_print_styles', 'hybridmag_enqueue_customizer_stylesheets' );

/**
 * Enqueue Customize Control JS
 */
function hybridmag_enqueue_customize_control_scripts() {
	wp_enqueue_script( 'hm-customizer-controls', get_template_directory_uri() . '/inc/customizer/assets/js/customizer-controls.js', array( 'jquery', 'customize-base', 'customize-controls' ), false, true );
}
add_action( 'customize_controls_enqueue_scripts', 'hybridmag_enqueue_customize_control_scripts' );

/**
 * Select sanitization callback.
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function hybridmag_sanitize_select( $input, $setting ) {
	
	// Ensure input is a slug.
	$input = sanitize_key( $input );
	
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Number sanitization.
 *
 * @param int                  $number  Number to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return int Sanitized number; otherwise, the setting default.
 */
function hybridmag_sanitize_number_absint( $number, $setting ) {
	// Ensure $number is an absolute integer (whole number, zero or greater).
	$number = absint( $number );
	
	// If the input is an absolute integer, return it; otherwise, return the default
	return ( $number ? $number : $setting->default );
}

/**
 * Check if the given value is a number or blank.
 */
function hybridmag_sanitize_number_blank( $number, $setting ) {

	if ( '' != $number ) {
		// Ensure $number is an absolute integer (whole number, zero or greater).
		$number = absint( $number );

		if ( $number >= 0 ) {
			return $number;
		} 
	}

	return $setting->default;

}

/**
 * Number Range sanitization.
 *
 * @param int                  $number  Number to check within the numeric range defined by the setting.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return int|string The number, if it is zero or greater and falls within the defined range; otherwise,
 *                    the setting default.
 */
function hybridmag_sanitize_number_range( $number, $setting ) {
	
	// Ensure input is an absolute integer.
	$number = absint( $number );
	
	// Get the input attributes associated with the setting.
	$atts = $setting->manager->get_control( $setting->id )->input_attrs;
	
	// Get minimum number in the range.
	$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );
	
	// Get maximum number in the range.
	$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );
	
	// Get step.
	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );
	
	// If the number is within the valid range, return it; otherwise, return the default
	return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
}

/**
 * HEX Color sanitization.
 *
 * @param string               $hex_color HEX color to sanitize.
 * @param WP_Customize_Setting $setting   Setting instance.
 * @return string The sanitized hex color if not null; otherwise, the setting default.
 */
function hybridmag_sanitize_hex_color( $hex_color, $setting ) {
	// Sanitize $input as a hex value without the hash prefix.
	$hex_color = sanitize_hex_color( $hex_color );
	
	// If $input is a valid hex value, return it; otherwise, return the default.
	return ( ! is_null( $hex_color ) ? $hex_color : $setting->default );
}

/**
 * Checkbox sanitization callback example.
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function hybridmag_sanitize_checkbox( $checked ) {
	// Boolean check.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitization callback of Multiple Checkboxes Control
 */
function hybridmag_sanitize_multiple_checkboxes( $values ) {

	$multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

	return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
	
}

/**
 * HTML sanitization callback.
 *
 * @param string $html HTML to sanitize.
 * @return string Sanitized HTML.
 */
function hybridmag_sanitize_html( $html ) {
	return wp_filter_post_kses( $html );
}

/**
 * URL sanitization.
 *
 * @param string $url URL to sanitize.
 * @return string Sanitized URL.
 */
function hybridmag_sanitize_url( $url ) {
	return esc_url_raw( $url );
}

/**
 * Email sanitization
 * @param string               $email   Email address to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string The sanitized email if not null; otherwise, the setting default.
 */
function hybridmag_sanitize_email( $email, $setting ) {
	// Strips out all characters that are not allowable in an email address.
	$email = sanitize_email( $email );
	
	// If $email is a valid email, return it; otherwise, return the default.
	return ( ! is_null( $email ) ? $email : $setting->default );
}

function hybridmag_sanitize_slider_number_input( $number, $setting ) {
	
	// Ensure input is a number.
	$number = (float)$number ;
	
	// Get the input attributes associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->input_attrs;
	
	// Get minimum number in the range.
	$min = ( isset( $choices['min'] ) ? $choices['min'] : $number );
	
	// Get maximum number in the range.
	$max = ( isset( $choices['max'] ) ? $choices['max'] : $number );
	
	// Get step.
	$step = ( isset( $choices['step'] ) ? $choices['step'] : 1 );

	if ( $number <= $min ) {
		$number = $min;
	} elseif ( $number >= $max ) {
		$number = $max;
	}
	
	// If the number is within the valid range, return it; otherwise, return the default
	return ( is_numeric( $number / $step ) ? $number : $setting->default );
}

/**
 * Category dropdown sanitization.
 *
 * @param int $catid to sanitize.
 * @return int $cat_id.
 */
function hybridmag_sanitize_category_dropdown( $catid ) {
	// Ensure $catid is an absolute integer.
	return $cat_id = absint( $catid );
}

/**
 * Toggle Switch sanitization.
 */
function hybridmag_sanitize_switch( $input ) {
	if ( true === $input ) {
		return true;
	} else {
		return false;
	}
}

function hybridmag_sanitize_sortable_checkbox( $input ) {
	if ( strpos( $input, ',' ) !== false) {
		$input = explode( ',', $input );
	}
	if( is_array( $input ) ) {
		foreach ( $input as $key => $value ) {
			$input[$key] = sanitize_text_field( $value );
		}
		$input = implode( ',', $input );
	}
	else {
		$input = sanitize_text_field( $input );
	}
	return $input;
}

/**
 * Alpha color picker sanitization.
 */
/**
 * Sanitize colors.
 *
 * @since 1.0.0
 * @param string $value The color.
 * @return string
 */
function hybridmag_sanitize_alpha_color( $value ) {
	// This pattern will check and match 3/6/8-character hex, rgb, rgba, hsl, & hsla colors.
	$pattern = '/^(\#[\da-f]{3}|\#[\da-f]{6}|\#[\da-f]{8}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/';
	\preg_match( $pattern, $value, $matches );
	// Return the 1st match found.
	if ( isset( $matches[0] ) ) {
		if ( is_string( $matches[0] ) ) {
			return $matches[0];
		}
		if ( is_array( $matches[0] ) && isset( $matches[0][0] ) ) {
			return $matches[0][0];
		}
	}
	// If no match was found, return an empty string.
	return '';
}

/**
 * Check if the grid style is active.
 */
function hybridmag_is_slideout_active( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_show_slideout_sb' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the large header layout is active.
 */
function hybridmag_is_large_header( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_header_layout' )->value() === 'large' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the default header layout is active.
 */
function hybridmag_is_default_header( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_header_layout' )->value() === 'default' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the wide layout is active.
 */
function hybridmag_is_wide_layout_active( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_site_layout' )->value() === 'wide' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the boxed layout is active.
 */
function hybridmag_is_boxed_layout_active( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_site_layout' )->value() === 'boxed' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the one container layout is active.
 */
function hybridmag_is_one_container_layout_active( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_content_layout' )->value() === 'one-container' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the separate container layout is active.
 */
function hybridmag_is_separate_containers_layout_active( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_content_layout' )->value() === 'separate-containers' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the grid layout is active.
 */
function hybridmag_is_entries_grid( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_entries_layout' )->value() === 'grid' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the list layout is active.
 */
function hybridmag_is_entries_list( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_entries_layout' )->value() === 'list' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the list layout is active.
 */
function hybridmag_is_excerpt_type( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_content_type' )->value() === 'excerpt' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the featured content area is displaying.
 */
function hybridmag_is_featured_content_active( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_show_featured_content' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the tabbed posts section is displaying.
 */
function hybridmag_is_tabbed_posts_active( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_display_tabbed_posts' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the featured content area is displaying.
 */
function hybridmag_is_slider_autoplay_active( $control ) {
	if ( hybridmag_is_featured_content_active( $control ) && $control->manager->get_setting( 'hybridmag_slider_autoplay' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the cta button active
 */
function hybridmag_is_cta_active( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_show_header_cta' )->value() === true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks featured image alignment should be active or not
 */
function hybridmag_thumbnail_align_active( $control ) {
	$thumbnail_position = $control->manager->get_setting( 'hybridmag_archive_thumbnail_position' )->value();
	if ( 'beside-article' === $thumbnail_position || 'beside-content' === $thumbnail_position ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Checks if hybridmag is showing author.
 */
function hybridmag_is_showing_author( $control ) {
	$entry_meta = $control->manager->get_setting( 'hybridmag_archive_entry_meta' )->value();

	if ( strpos( $entry_meta, ',' ) !== false ) {
		$entry_meta_type_array = explode( ',', $entry_meta );
	} else {
		$entry_meta_type_array = array( $entry_meta );
	}
	
	$entry_meta_type_array = array_map( 'trim', $entry_meta_type_array );

	if ( in_array( 'author', $entry_meta_type_array ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if hybridmag is showing author in single post.
 */
function hybridmag_is_showing_author_s( $control ) {
	$entry_meta = $control->manager->get_setting( 'hybridmag_archive_entry_meta_s' )->value();

	if ( strpos( $entry_meta, ',' ) !== false ) {
		$entry_meta_type_array = explode( ',', $entry_meta );
	} else {
		$entry_meta_type_array = array( $entry_meta );
	}
	
	$entry_meta_type_array = array_map( 'trim', $entry_meta_type_array );

	if ( in_array( 'author', $entry_meta_type_array ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if hybridmag is showing date
 */
function hybridmag_is_showing_date( $control ) {
	$entry_meta = $control->manager->get_setting( 'hybridmag_archive_entry_meta' )->value();

	if ( strpos( $entry_meta, ',' ) !== false ) {
		$entry_meta_type_array = explode( ',', $entry_meta );
	} else {
		$entry_meta_type_array = array( $entry_meta );
	}
	
	$entry_meta_type_array = array_map( 'trim', $entry_meta_type_array );

	if ( in_array( 'date', $entry_meta_type_array ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if hybridmag is showing date in single posts
 */
function hybridmag_is_showing_date_s( $control ) {
	$entry_meta = $control->manager->get_setting( 'hybridmag_archive_entry_meta_s' )->value();

	if ( strpos( $entry_meta, ',' ) !== false ) {
		$entry_meta_type_array = explode( ',', $entry_meta );
	} else {
		$entry_meta_type_array = array( $entry_meta );
	}
	
	$entry_meta_type_array = array_map( 'trim', $entry_meta_type_array );

	if ( in_array( 'date', $entry_meta_type_array ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if hybridmag is showing time ago
 */
function hybridmag_is_time_ago( $control ) {
	if ( ( $control->manager->get_setting( 'hybridmag_time_ago' )->value() === true ) || ( $control->manager->get_setting( 'hybridmag_time_ago_s' )->value() === true ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the custom logo has been set.
 */
function hybridmag_has_custom_logo() {
	if ( has_custom_logo() ) {
		return true;
	} else {
		return false;
	}
}


function hybridmag_is_showing_breadcrumb( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_breadcrumb_source' )->value() !== 'none' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if blog is displaying on front page
 */
function hybridmag_is_showing_blog_on_front( $control ) {
	if ( 'posts' == get_option( 'show_on_front' ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the small featured posts source is "Category"
 */
function hybridmag_is_sfp_source_category( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_sfp_source' )->value() === 'category' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the small featured posts source is "Tag"
 */
function hybridmag_is_sfp_source_tag( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_sfp_source' )->value() === 'tag' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the slider posts source is "Category"
 */
function hybridmag_is_slider_source_category( $control ) {
	if ( hybridmag_is_featured_content_active( $control ) && $control->manager->get_setting( 'hybridmag_slider_posts_source' )->value() === 'category' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the slider posts source is "Tag"
 */
function hybridmag_is_slider_source_tag( $control ) {
	if ( hybridmag_is_featured_content_active( $control ) && $control->manager->get_setting( 'hybridmag_slider_posts_source' )->value() === 'tag' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the featured posts source is "Category"
 */
function hybridmag_is_fps_source_category( $control ) {
	if ( hybridmag_is_featured_content_active( $control ) && $control->manager->get_setting( 'hybridmag_featured_posts_source' )->value() === 'category' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if the featured posts source is "Tag"
 */
function hybridmag_is_fps_source_tag( $control ) {
	if ( hybridmag_is_featured_content_active( $control ) && $control->manager->get_setting( 'hybridmag_featured_posts_source' )->value() === 'tag' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Returns true if the slider posts source is not sticky posts.
 */
function hybridmag_is_not_sticky_posts_slider( $control ) {
	if ( hybridmag_is_featured_content_active( $control ) && $control->manager->get_setting( 'hybridmag_slider_posts_source' )->value() !== 'sticky' ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Returns true if the featured posts source is not sticky posts.
 */
function hybridmag_is_not_sticky_posts_fps( $control ) {
	if ( $control->manager->get_setting( 'hybridmag_featured_posts_source' )->value() !== 'sticky' ) {
		return true;
	} else {
		return false;
	}
}