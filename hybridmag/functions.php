<?php
/**
 * HybridMag functions and definitions
 *
 * @package HybridMag
 */

if ( ! defined( 'HYBRIDMAG_VERSION' ) ) {
	define( 'HYBRIDMAG_VERSION', '1.0.7' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function hybridmag_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on HybridMag, use a find and replace
		* to change 'hybridmag' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'hybridmag', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	$classic_editor_styles = array(
		'assets/css/editor-style.css',
		'assets/css/font-figtree.css',
	);
	
	// Enqueue editor styles.
	add_editor_style( $classic_editor_styles );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'hybridmag-featured-image', 1300, 9999 );
	add_image_size( 'hybridmag-archive-image', 800, 533, true );
	add_image_size( 'hybridmag-archive-image-large', 1300, 867, true );
	add_image_size( 'hybridmag-thumbnail', 250, 170, true );

	if ( ! get_theme_mod( 'hybridmag_archive_image_crop', true ) ) {
		add_image_size( 'hybridmag-archive-image', 800, 9999, false );
		add_image_size( 'hybridmag-archive-image-large', 1300, 9999, false );
	}

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'hybridmag' ),
			'secondary' => esc_html__( 'Top Menu', 'hybridmag' ),
			'social' => esc_html__( 'Social Menu', 'hybridmag' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'hybridmag_custom_background_args', array(
		'default-color' => '',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      	=> 50,
			'width'       	=> 165,
			'flex-height'	=> true,
			'flex-width' 	=> true,
		)
	);

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets' => array(
			'sidebar-1' => array(
				'recent-posts',
				'categories',
				'text_about'
			),
			'footer-1' => array(
				'text_business_info',
			),
			'footer-2' => array(
				'recent-posts',
			),
			'footer-3' => array(
				'text_about',
				'meta'
			),
		),
		'theme_mods' => array(
			'hybridmag_display_social_topbar' => false,
			'hybridmag_social_beside_logo' => true,
			'hybridmag_show_header_cta' => true,
			'hybridmag_show_slideout_sb' => true,
			'hybridmag_show_pmenu_onslideout' => true
		),
		'nav_menus' => array(
			// Assign a menu to the "menu-social" location.
			'social' => array(
				'name' => esc_html__( 'Social Menu', 'hybridmag' ),
				'items' => array(
					'link_facebook',
					'link_youtube',
					'link_twitter',
				),
			),
		)
	);

	$starter_content = apply_filters( 'hybridmag_starter_content', $starter_content );
	add_theme_support( 'starter-content', $starter_content );	
}
add_action( 'after_setup_theme', 'hybridmag_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function hybridmag_content_width() {

	$site_layout = get_theme_mod( 'hybridmag_site_layout', 'wide' );
	if ( 'boxed' == $site_layout ) {
		$boxed_width = get_theme_mod( 'hybridmag_boxed_width', 1280 );
		$container_width = ( $boxed_width * 93.75 ) / 100;
	} else {
		$container_width = get_theme_mod( 'hybridmag_container_width', 1200 );
	}
	
	$sidebar_width = get_theme_mod( 'hybridmag_sidebar_width', 32.916666667 );
	$layout = hybridmag_get_layout();

	if ( 'left-sidebar' === $layout || 'right-sidebar' === $layout ) {
		$content_width = $container_width * ( ( 100 - $sidebar_width ) / 100 );
	} elseif ( 'no-sidebar' === $layout ) {
		$content_width = $container_width;
	} else {
		$content_width = 900;
	}

	$GLOBALS['content_width'] = apply_filters( 'hybridmag_content_width', $content_width );

}
add_action( 'template_redirect', 'hybridmag_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function hybridmag_widgets_init() {

	$sidebars = array(
		array(
			'name'          => esc_html__( 'Sidebar', 'hybridmag' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'hybridmag' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		array(
			'name'          => esc_html__( 'Slide-out Sidebar', 'hybridmag' ),
			'id'            => 'header-1',
			'description'   => esc_html__( 'Add widgets here to appear in an off-screen sidebar when it is enabled under the Customizer Header Settings.', 'hybridmag' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		array(
			'name'          => esc_html__( 'Header Sidebar', 'hybridmag' ),
			'id'            => 'header-2',
			'description'   => esc_html__( 'Add widgets here to appear on the Header', 'hybridmag' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		array(
			'name'          => esc_html__( 'Below Header', 'hybridmag' ),
			'id'            => 'header-3',
			'description'   => esc_html__( 'Add widgets here to appear below the Header', 'hybridmag' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		array(
			'name'          => esc_html__( 'Footer 1', 'hybridmag' ),
			'id'            => 'footer-1',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		array(
			'name'          => esc_html__( 'Footer 2', 'hybridmag' ),
			'id'            => 'footer-2',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		array(
			'name'          => esc_html__( 'Footer 3', 'hybridmag' ),
			'id'            => 'footer-3',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		array(
			'name'          => esc_html__( 'Footer 4', 'hybridmag' ),
			'id'            => 'footer-4',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	$sidebars = apply_filters( 'hybridmag_sidebars', $sidebars );

	// Register each sidebar
	foreach ( $sidebars as $sidebar ) {
		register_sidebar( $sidebar );
	}

}
add_action( 'widgets_init', 'hybridmag_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function hybridmag_scripts() {
	wp_enqueue_style( 'hybridmag-style', get_stylesheet_uri(), array(), HYBRIDMAG_VERSION );
	wp_style_add_data( 'hybridmag-style', 'rtl', 'replace' );

	wp_enqueue_script( 'hybridmag-main', get_template_directory_uri() . '/assets/js/main.js', array(), HYBRIDMAG_VERSION, true );
    wp_localize_script( 'hybridmag-main', 'hybridmagAdminSettings',
        array(
            'darkModeDefault' => (bool) get_theme_mod( 'hybridmag_is_dark_mode_default', false )
        )
    );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( ( ( is_home() && ! is_paged() ) || ( is_front_page() && ! is_paged() ) ) && true == get_theme_mod( 'hybridmag_show_featured_content', true ) /*&& ! is_page_template( 'page-templates/template-fullwidth.php' )*/ ) {
		wp_enqueue_style( 'hybridmag-swiper', get_template_directory_uri() . '/assets/css/swiper-bundle.css', '', '11.1.4', 'screen' );
		wp_enqueue_script( 'hybridmag-swiper', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', '', '11.1.4', true );
		wp_enqueue_script( 'hybridmag-swiper-custom', get_template_directory_uri() . '/assets/js/hybridmag-swiper.js', array(), HYBRIDMAG_VERSION, true );
		
		$autoplay = get_theme_mod( 'hybridmag_slider_autoplay', false );
		$delay = get_theme_mod( 'hybridmag_slider_autoplay_delay', 6 );
		wp_localize_script( 'hybridmag-swiper-custom', 'hybridmag_swiper_object',
			array(
				'autoplay'	=> $autoplay,
				'delay'		=> $delay
			)
		);
	}

	if ( ( ( is_home() && ! is_paged() ) || ( is_front_page() && ! is_paged() ) ) && true == get_theme_mod( 'hybridmag_display_tabbed_posts', true ) /*&& ! is_page_template( 'page-templates/template-fullwidth.php' )*/ ) {
		wp_enqueue_script( 'hybridmag-tabs', get_template_directory_uri() . '/assets/js/tab-widget.js', array(), HYBRIDMAG_VERSION, true );
	}
	
}
add_action( 'wp_enqueue_scripts', 'hybridmag_scripts' );

/**
 * Block Styles.
 */
require get_template_directory() . '/inc/block-styles.php';

/**
 * Handle SVG icons.
 */ 
require get_template_directory() . '/inc/class-hybridmag-svg-icons.php';

/**
 * Custom Nav Walker
 */
require get_template_directory() . '/inc/class-hybridmag-nav-walker.php';

/**
 * Meta boxes
 */
require get_template_directory() . '/inc/class-hybridmag-meta-boxes.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Add custom header background support.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Block editor related functions. 
 */
require get_template_directory() . '/inc/block-editor.php';

/**
 * Jetpack functions. 
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/wptt-webfont-loader.php';
require get_template_directory() . '/inc/customizer/custom-controls/fonts/fonts.php';
require get_template_directory() . '/inc/customizer/customizer.php';
require get_template_directory() . '/inc/customizer/settings/typography.php';
require get_template_directory() . '/inc/css-output.php';
require get_template_directory() . '/inc/editor-custom-css.php';

/**
 * Widgets.
 */
require get_template_directory() . '/inc/widgets/class-sidebar-posts.php';

if ( ! function_exists( 'hybridmag_get_fonts_url' ) ) :
	/**
	 * Gets the font url.
	 */
	function hybridmag_get_fonts_url() {
		$fonts_arr = hybridmag_typography_loop( 'fonts' );

		if ( empty( $fonts_arr ) ) {
			return;
		}
	
		$font_url = hybridmag_get_google_font_uri( $fonts_arr );

		return $font_url;
	}

endif;

/**
* Enqueue Google fonts.
*/
function hybridmag_load_fonts() {

	$fonts_arr = hybridmag_typography_loop( 'fonts' );

	if ( empty( $fonts_arr ) ) {
		return;
	}

	// Remove duplicates
	$fonts_arr = array_unique( $fonts_arr );

	$default_font_index = array_search( 'Figtree', $fonts_arr );

	if ( $default_font_index !== false ) {

		// Enqueue Default Font CSS from theme.
		wp_enqueue_style( 'hybridmag-font-figtree', get_theme_file_uri( '/assets/css/font-figtree.css' ), array(), null );

		unset( $fonts_arr[ $default_font_index ] );
		$fonts_arr = array_values( $fonts_arr );

	}

	// Check again if empty after changing the array above.
	if ( empty( $fonts_arr ) ) {
		return;
	}

	$font_url = hybridmag_get_google_font_uri( $fonts_arr );

	if ( ! empty( $font_url ) ) {

		if ( ! is_admin() && ! is_customize_preview() ) {
			$font_url = wptt_get_webfont_url( esc_url_raw( $font_url ) );
		}
	
		// Load Google Fonts
		wp_enqueue_style( 'hybridmag-fonts', $font_url, array(), null, 'screen' );

	}

}
add_action( 'wp_enqueue_scripts', 'hybridmag_load_fonts' );

/**
 * Display custom color CSS in customizer and on frontend.
 */
function hybridmag_custom_css_wrap( $output = NULL ) {

	// Add filter for adding custom css via other functions
	$output = apply_filters( 'hybridmag_head_css', $output ); ?>

	<style type="text/css" id="hybridmag-custom-css">
		<?php echo wp_strip_all_tags( $output ); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'hybridmag_custom_css_wrap' );

/**
 * Theme Info Page.
 */
require get_template_directory() . '/inc/dashboard/theme-info.php';


/**
 * Load Structure.
 */
require get_template_directory() . '/inc/structure/header.php';
require get_template_directory() . '/inc/structure/navigation.php';
require get_template_directory() . '/inc/structure/featured.php';
require get_template_directory() . '/inc/structure/footer.php';

/**
 * Demo data.
 */
require get_template_directory() . '/inc/dashboard/demo-data.php';