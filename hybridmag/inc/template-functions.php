<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package HybridMag
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function hybridmag_body_classes( $classes ) {
	
	$site_layout = get_theme_mod( 'hybridmag_site_layout', 'wide' );
	$logo_alignment = get_theme_mod( 'hybridmag_logo_align', 'left' );
	$header_layout = hybridmag_get_header_layout();
	$sidebar_layout = hybridmag_get_layout();
	$content_layout = get_theme_mod( 'hybridmag_content_layout', 'separate-containers' );

	if ( 'boxed' === $site_layout ) {
		$classes[] = 'hybridmag-boxed';
	}

	if ( 'wide' === $site_layout ) {
		$classes[] = 'hybridmag-wide';
	}

	if ( 'separate-containers' === $content_layout ) {
		$classes[] = 'hm-cl-sep';
	} else {
		$classes[] = 'hm-cl-one';
	}

	if ( hybridmag_show_updated_date() ) {
		$classes[] = 'hm-show-updated';
	}

	if ( isset( $sidebar_layout ) ) {
		$classes[] = 'hm-' . esc_attr( $sidebar_layout );
	}

	// body class to be targeted by other plugins to style.
	$classes[] = 'th-hm-es';

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( 'default' === $header_layout ) {

		$classes[] = 'hm-h-de';

	} elseif ( 'large' === $header_layout ) {
		
		$classes[] = 'hm-h-lg';

		if ( 'left' === $logo_alignment ) {
			$classes[] = 'logo-aligned-left';
		} elseif ( 'right' === $logo_alignment ) {
			$classes[] = 'logo-aligned-right';
		} elseif ( 'center' === $logo_alignment ) {
			$classes[] = 'logo-aligned-center';
		}

		$menu_alignment = get_theme_mod( 'hybridmag_menu_align', 'left' );
		if ( 'left' === $menu_alignment ) {
			$classes[] = 'menu-align-left';
		} elseif ( 'right' === $menu_alignment ) {
			$classes[] = 'menu-align-right';
		} elseif ( 'center' === $menu_alignment ) {
			$classes[] = 'menu-align-center';
		}

		$menu_width = get_theme_mod( 'hybridmag_menu_width', 'contained' );
		if ( 'full' === $menu_width ) {
			$classes[] = 'hm-wide-pmenu';
		}

		$menu_inner_width = get_theme_mod( 'hybridmag_menu_inner_width', 'contained' );
		if ( 'full' === $menu_inner_width ) {
			$classes[] = 'hm-wide-pmenu-inner';
		}

	}

	$header_width = get_theme_mod( 'hybridmag_header_width', 'contained' );
	if ( 'full' === $header_width ) {
		$classes[] = 'hm-wide-header';
	}

	$topbar_width = get_theme_mod( 'hybridmag_topbar_width', 'contained' );
	if ( 'full' === $topbar_width ) {
		$classes[] = 'hm-wide-topbar';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'hm-no-sidebar';
	}

	if ( is_active_sidebar( 'header-2' ) ) {
		$classes[] = 'has-header-sb';
	}

	if ( is_home() || is_archive() || is_search() ) {

		$entries_layout = get_theme_mod( 'hybridmag_entries_layout', 'list' );
		if ( 'grid' === $entries_layout ) {
			$classes[] = 'hm-post-grid';
			$number_of_cols = get_theme_mod( 'hybridmag_entries_grid_columns', '2' );
			$classes[] = 'hm-grid-' . esc_attr( $number_of_cols );
		} else {
			$classes[] = 'hm-post-list';
		}

		$archive_thumbnail_position = hybridmag_archive_thumbnail_position();
		$archive_thumbnail_align = get_theme_mod( 'hybridmag_archive_thumbnail_align', 'left' );
		if ( 'beside-article' ===  $archive_thumbnail_position ) {
			$classes[] = 'hm-arc-img-ba';
			$classes[] = 'hybridmagaif-' . esc_attr( $archive_thumbnail_align );
		} elseif ( 'beside-content' === $archive_thumbnail_position ) {
			$classes[] = 'hm-arc-img-bc';
			$classes[] = 'hybridmagaif-' . esc_attr( $archive_thumbnail_align );
		} elseif ( 'before-header' === $archive_thumbnail_position ) {
			$classes[] = 'hm-arc-img-bh';
		} elseif ( 'after-header' === $archive_thumbnail_position ) {
			$classes[] = 'hm-arc-img-ah';
		} else {
			$classes[] = 'hm-arc-no-img';
		}

	}

	$footer_sidebar_count = get_theme_mod( 'hybridmag_footer_sidebar_count', '3' );
	if ( $footer_sidebar_count ) {
		$classes[] = 'hm-footer-cols-' . esc_attr( $footer_sidebar_count );
	}

	if ( 'full' === get_theme_mod( 'hybridmag_footer_widget_area_width', 'contained' ) ) {
		$classes[] = 'hm-wide-footer';
	}

	if ( hybridmag_has_dark_mode_logo() ) {
		$classes[] = 'hm-has-dm-logo';
	}

	return $classes;
}
add_filter( 'body_class', 'hybridmag_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function hybridmag_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'hybridmag_pingback_header' );


if ( ! function_exists( 'hybridmag_get_layout' ) ) {
	/**
	 * Get the layout for the current page.
	 *
	 * @return string The sidebar layout location.
	 */
	function hybridmag_get_layout() {

		global $post;

		$layout = 'right-sidebar';

		if ( is_home() || is_archive() || is_search() || is_tax() ) {
			$layout = get_theme_mod( 'hybridmag_archive_layout', 'right-sidebar'  );
		}

		if ( is_single() ) {
			$post_specific_layout = get_post_meta( $post->ID, '_hybridmag_layout_meta', true );
			if ( empty( $post_specific_layout ) || $post_specific_layout === 'default-layout' ) {
				$layout = get_theme_mod( 'hybridmag_post_layout', 'right-sidebar' );
			} else {
				$layout = $post_specific_layout;
			}
		}

		if ( is_page() ) {
			$page_specific_layout = get_post_meta( $post->ID, '_hybridmag_layout_meta', true );
			if ( empty( $page_specific_layout ) || $page_specific_layout === 'default-layout' ) {
				$layout = get_theme_mod( 'hybridmag_page_layout', 'right-sidebar' );
			} else {
				$layout = $page_specific_layout;
			}
			$template_slug = get_page_template_slug( $post->ID );
			// Over write everything if magazine template or fullwidth template
			if ( 'page-templates/template-magazine.php' === $template_slug || 'page-templates/template-fullwidth.php'=== $template_slug ) {
				$layout = 'no-sidebar';
			}	
		}

		return apply_filters( 'hybridmag_sidebar_layout', $layout );
	}
}

if ( ! function_exists( 'hybridmag_get_sidebar' ) ) {
	/**
	 * Selects the sidebar.
	 */
	function hybridmag_get_sidebar() {
		$sidebar_position = hybridmag_get_layout();
		if ( 'right-sidebar' === $sidebar_position || 'left-sidebar' === $sidebar_position ) {
			get_sidebar();
		}
	}
}

/**
 * Adds a Sub Nav Toggle to the Mobile Menu.
 *
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param WP_Post  $item  Menu item data object.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return stdClass An object of wp_nav_menu() arguments.
 * @since HybridMag 1.0.0
 */
function hybridmag_add_dropdown_toggle_to_menu( $args, $item, $depth ) {

	// Add sub menu toggles to the menu.
	if ( isset( $args->hybridmag_show_toggles ) && $args->hybridmag_show_toggles ) {

		$args->after = '';

		// Add a toggle to items with children.
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

			// Note: Implement mobile menu dropdown toggles later. 
			$toggle_target_string = '.hm-mobile-menu .menu-item-' . $item->ID . ' > .sub-menu';

			$svg_icon = hybridmag_get_icon_svg( 'chevron-down' );

			// Add the sub menu toggle.
			$args->after = '<button class="hm-dropdown-toggle" data-toggle-target="' . $toggle_target_string . '" aria-expanded="false">'. $svg_icon .'<span class="screen-reader-text">' . esc_html__( 'Show sub menu', 'hybridmag' ) . '</span></button>';

		}

	} 

	return $args;

}
add_filter( 'nav_menu_item_args', 'hybridmag_add_dropdown_toggle_to_menu', 10, 3 );


/**
 * Add dropdown svg icon if menu item has children.
 *
 * @param string   $title The menu item's title.
 * @param WP_Post  $item  The current menu item.
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return string The menu item's title with dropdown icon.
 */
function hybridmag_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {

	if ( isset( $args->hybridmag_show_icons ) && $args->hybridmag_show_icons ) {
		foreach ( $item->classes as $value ) {
			if ( 'menu-item-has-children' === $value ) {
				$title = $title . '<span class="hm-menu-icon">' . hybridmag_get_icon_svg( 'chevron-down' ) . '</span>';
			}
		}
	}

	return $title;
}
add_filter( 'nav_menu_item_title', 'hybridmag_dropdown_icon_to_menu_link', 10, 4 );

/**
 * Displays SVG icons in social links menu.
 * 
 * @since HybridMag 1.0.0
 * 
 * @param string	$item_output 	The menu item's starting HTML output.
 * @param WP_Post 	$item 			Menu item data object.
 * @param int 		$depth 			Depth of the menu. Used for padding.
 * @param stdClass 	$args 			An object of wp_nav_menu() arguments.
 * @return string The menu item output with social icon.
 */
function hybridmag_nav_menu_social_icons( $item_output, $item, $depth, $args ) {
	// Change SVG icon inside social links menu if there is supported URL.
	if ( 'social' === $args->theme_location ) {
		$svg = HybridMag_SVG_Icons::get_social_link_svg( $item->url );
		if ( empty( $svg ) ) {
			$svg = hybridmag_get_icon_svg( 'link' );
		}
		$item_output = str_replace( $args->link_after, '</span>' . $svg, $item_output );
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'hybridmag_nav_menu_social_icons', 10, 4 );

/**
 * Adds custom class to the array of posts classes.
 */
function hybridmag_post_classes( $classes, $class, $post_id ) {

	if ( is_archive() || is_home() || is_search() ) {
		$classes[] = 'hm-entry';
	}

	if ( is_singular() ) {
		$classes[] = 'hm-entry-single';
	}

	return $classes;
	
}
add_filter( 'post_class', 'hybridmag_post_classes', 10, 3 );

/**
 * Gets the archive thumbnail position.
 */
function hybridmag_archive_thumbnail_position() {
	$position = get_theme_mod( 'hybridmag_archive_thumbnail_position', hybridmag_get_archive_image_position_default() );
	return $position;
}

/**
 * Custom excerpt length.
 */
function hybridmag_excerpt_length( $length ) {
	if( is_admin() ) {
		return $length;
	}
	$custom_length = get_theme_mod( 'hybridmag_excerpt_length', 16 );
	return absint( $custom_length );
}
add_filter( 'excerpt_length', 'hybridmag_excerpt_length', 999 );

if ( ! function_exists( 'hybridmag_excerpt_more' ) ) {
	add_filter( 'excerpt_more', 'hybridmag_excerpt_more', 20 );
	/**
	 * Prints the read more HTML to post excerpts.
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The HTML for the more link.
	 */
	function hybridmag_excerpt_more( $more ) {
		if ( 'link' === get_theme_mod( 'hybridmag_read_more_type', 'link' ) ) {
			return apply_filters(
				'hybridmag_excerpt_more_output',
				sprintf(
					' &hellip; <a title="%1$s" class="hm-read-more" href="%2$s"><span class="screen-reader-text">%3$s</span>%4$s</a>',
					the_title_attribute( 'echo=0' ),
					esc_url( get_permalink( get_the_ID() ) ),
					esc_html( get_the_title( get_the_ID() ) ),
					__( 'Read more', 'hybridmag' ),
				)
			);
		} else {
			return '&hellip;';
		}
	}
}

/**
 * Change date to 'time ago' format if enabled in the Customizer.
 */
function hybridmag_math_to_time_ago( $post_time, $format, $post, $updated ) {
	if ( is_single() ) {
		$use_time_ago = get_theme_mod( 'hybridmag_time_ago_s', false );
	} else {
		$use_time_ago = get_theme_mod( 'hybridmag_time_ago', false );
	}
	
	// Only filter time when $use_time_ago is enabled, and it's not using a machine-readable format (for datetime).
	if ( true === $use_time_ago && 'Y-m-d\TH:i:sP' !== $format ) {
		$current_time = current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
		$cut_off      = get_theme_mod( 'hybridmag_time_ago_date_count', '14' );
		$org_time     = strtotime( $post->post_date );

		if ( true === $updated ) {
			$org_time = strtotime( $post->post_modified );
		}

		// Transform cut off from days to seconds.
		$cut_off_seconds = $cut_off * 86400;

		if ( is_single() ) {
			$show_updated_date = get_theme_mod( 'hybridmag_show_updated_date_s', false );
		} else {
			$show_updated_date = get_theme_mod( 'hybridmag_show_updated_date', false );
		}

		if ( true === $show_updated_date ) {
			// Switch cut off to 24 hours.
			$cut_off_seconds = 86400;
		}

		if ( $cut_off_seconds >= ( $current_time - $org_time ) ) {
			$post_time = sprintf(
				/* translators: %s: Time ago date format */
				esc_html__( '%s ago', 'hybridmag' ),
				human_time_diff( $org_time, $current_time )
			);
		}
	}

	return $post_time;
}


/**
 * Apply time ago format to publish dates if enabled.
 */
function hybridmag_convert_to_time_ago( $post_time, $format, $post ) {
	// Don't override specifically requested formats.
	if ( empty( $format ) ) {
		$post_time = hybridmag_math_to_time_ago( $post_time, $format, $post, false );
	}
	return $post_time;
}
add_filter( 'get_the_date', 'hybridmag_convert_to_time_ago', 10, 3 );

/**
 * Apply time ago format to modified dates if enabled.
 */
function hybridmag_convert_modified_to_time_ago( $post_time, $format, $post ) {
	return hybridmag_math_to_time_ago( $post_time, $format, $post, true );
}

/**
 * Checks to see if it should display the updated date.
 */
function hybridmag_show_updated_date() {
	if ( is_single() ) {
		$show_updated_date = get_theme_mod( 'hybridmag_show_updated_date_s', false );
	} else {
		$show_updated_date = get_theme_mod( 'hybridmag_show_updated_date', false );
	}
	return $show_updated_date;
}

if ( ! function_exists( 'hybridmag_get_icon_svg' ) ) :
	/**
	 * Get SVG Icons.
	 * 
	 * @since HybridMag 1.0.0
	 * 
	 * @param string $icon_name The name of the icon.
	 * @param string $group The group the icon belongs to.
	 */
	function hybridmag_get_icon_svg( $icon_name, $group = 'ui' ) {

		// Make sure that only our allowed tags and attributes are included.
		$svg = wp_kses( HybridMag_SVG_Icons::get_svg( $icon_name, $group ), array(
			'svg'     => array(
				'class'       => true,
				'xmlns'       => true,
				'width'       => true,
				'height'      => true,
				'viewbox'     => true,
				'aria-hidden' => true,
				'role'        => true,
				'focusable'   => true,
			),
			'path'    => array(
				'fill'      => true,
				'fill-rule' => true,
				'd'         => true,
				'transform' => true,
			),
			'polygon' => array(
				'fill'      => true,
				'fill-rule' => true,
				'points'    => true,
				'transform' => true,
				'focusable' => true,
			),
		) );

		if ( ! $svg ) {
			return false;
		}

		return $svg;

	}

endif;


if ( ! function_exists( 'hybridmag_the_icon_svg' ) ) {
	/**
	 * Echo svg icon.
	 * 
	 * @since HybridMag 1.0.0
	 * 
	 * @param string $icon_name The name of the icon.
	 * @param string $group The group the icon belongs to.
	 */
	function hybridmag_the_icon_svg( $icon_name, $group = 'ui' ) {
		echo hybridmag_get_icon_svg( $icon_name, $group ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in hybridmag_get_icon_svg().
	}
}

if ( ! function_exists( 'hybridmag_filter_the_archive_title' ) ) {
	/**
	 * Remove "category" and "tag" text from the archive title
	 * 
	 * @param string $title The archive title. 
	 * @return string The changed archive title.
	 */
	function hybridmag_filter_the_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		}

		return $title;
	}
	add_filter( 'get_the_archive_title', 'hybridmag_filter_the_archive_title' );

}

if ( ! function_exists( 'hybridmag_get_selected_breadcrumb' ) ) {
	/**
	 * Get the user selected breadcrumb
	 * 
	 * @since 1.0.0
	 */
	function hybridmag_get_selected_breadcrumb() {
		$breadcrumb_source = get_theme_mod( 'hybridmag_breadcrumb_source', 'none' );

		if ( 'yoast' === $breadcrumb_source ) {
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<div id="hm-yoast-breadcrumbs">', '</div>' );
			}
		} elseif ( 'navxt' === $breadcrumb_source ) {
			if ( function_exists( 'bcn_display' ) ) {
				bcn_display();
			}
		} elseif ( 'rankmath' === $breadcrumb_source ) {
			if ( function_exists( 'rank_math_the_breadcrumbs') ) {
				rank_math_the_breadcrumbs();
			}
		}
	}

}

if ( ! function_exists( 'hybridmag_locate_breadcrumbs' ) ) {
	/**
	 * Adds the breadcrumb to the selected location
	 * 
	 * @since 1.0.0
	 */
	function hybridmag_locate_breadcrumbs() {

		if ( 'none' === get_theme_mod( 'hybridmag_breadcrumb_source', 'none' ) ) {
			return;
		}

		if ( 'before-entry-header' === get_theme_mod( 'hybridmag_breadcrumb_location', 'before-entry-header' ) ) {
			if ( is_archive() || is_search() ) {
				add_action( 'hybridmag_before_main_content', 'hybridmag_breadcrumb_template', 15 );
			} elseif ( is_singular() ) {
				add_action( 'hybridmag_before_entry_header', 'hybridmag_breadcrumb_template', 15 );
			}
		} else {
			add_action( 'hybridmag_after_header', 'hybridmag_breadcrumb_template', 15 );
		}

	}
	add_action( 'wp', 'hybridmag_locate_breadcrumbs' );
}

if ( ! function_exists( 'hybridmag_breadcrumb_template' ) ) {
	/**
	 * Hook breadcrumb template.
	 * 
	 * @since 1.0.0
	 */
	function hybridmag_breadcrumb_template() {
		$breadcrumb_location = get_theme_mod( 'hybridmag_breadcrumb_location', 'before-entry-header' );

		if ( 'after-site-header' === $breadcrumb_location ) {
			echo '<div class="hm-header-bar hm-header-breadcrumb">
					<div class="hm-container">';
		}

		echo '<div class="hm-breadcrumb-wrap">';
		
		hybridmag_get_selected_breadcrumb();

		echo '</div>';

		if ( 'after-site-header' === $breadcrumb_location ) {
			echo '</div>
				</div>';
		}

	}

}

if ( ! function_exists( 'hybridmag_homepage_article_title' ) ) {
	/**
	 * Displays a section title before homepage articles list.
	 * 
	 * @since 1.0.0
	 */
	function hybridmag_homepage_article_title() {
		if ( is_front_page() && is_home() && ! is_paged() ) {

			// Blog section title.
			$hybridmag_blog_section_title = get_theme_mod( 'hybridmag_blog_section_title', '' );

			if ( ! empty( $hybridmag_blog_section_title ) ) {
				echo '<div class="hm-blog-section-header">';
					echo '<h2 class="hm-blog-entries-title">';
						echo esc_html( $hybridmag_blog_section_title );
					echo '</h2>';
				echo '</div>';
			}

		}
	}
	add_action( 'hybridmag_before_blog_entries', 'hybridmag_homepage_article_title' );
}

/**
 * Filter for changing featured image size on different locations.
 */
function hybridmag_set_featured_image_sizes( $image_size ) {
	if ( is_home() || is_archive() || is_search() ) {
		$image_size = 'hybridmag-archive-image';

		$archive_layout = get_theme_mod( 'hybridmag_entries_layout', 'list' );
		$image_position = get_theme_mod( 'hybridmag_archive_thumbnail_position', hybridmag_get_archive_image_position_default() );

		if ( 'list' === $archive_layout && ( 'before-header' === $image_position || 'after-header' === $image_position ) ) {
			$image_size = 'hybridmag-archive-image-large';
		}
	} elseif ( is_single() ) {
		$image_size = 'hybridmag-featured-image';
	} else {
		$image_size = '';
	}
	return $image_size;
}
add_filter( 'hybridmag_post_thumbnail_size', 'hybridmag_set_featured_image_sizes' );

/**
 * Function to position featured images.
 */
function hybridmag_position_featured_image() {

	if ( is_single() ) {

		$image_position = get_theme_mod( 'hybridmag_post_image_position', 'after-header' );

		if ( 'before-header' === $image_position ) {
			add_action( 'hybridmag_before_entry_header', 'hybridmag_post_thumbnail' );
		} elseif ( 'after-header' === $image_position ) {
			add_action( 'hybridmag_after_entry_header', 'hybridmag_post_thumbnail' );
		}

	} elseif ( is_home() || is_archive() || is_search() ) {

		$image_position = hybridmag_archive_thumbnail_position();

		if ( 'before-header' === $image_position || 'beside-article' === $image_position ) {
			add_action( 'hybridmag_before_content', 'hybridmag_post_thumbnail' );
		} elseif ( 'after-header' === hybridmag_archive_thumbnail_position() ) {
			add_action( 'hybridmag_after_entry_header', 'hybridmag_post_thumbnail' );
		} elseif ( 'beside-content' === hybridmag_archive_thumbnail_position() ) {
			add_action( 'hybridmag_before_entry_content', 'hybridmag_post_thumbnail' );
		}

	} elseif ( is_page() ) {

		if ( get_theme_mod( 'hybridmag_show_page_thumbnail', true ) ) {
			add_action( 'hybridmag_after_page_header', 'hybridmag_post_thumbnail' );
		}
		
	}

}
add_action( 'wp', 'hybridmag_position_featured_image', 5 );

/**
 * Determines if post thumbnail can be displayed.
 */
function hybridmag_can_show_post_thumbnail() {
	return apply_filters( 'hybridmag_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Check if a section is displayed.
 *
 * @param  array $displayed_on Array of pages where the section is displayed.
 * @return bool               Section is displayed.
 */
function hybridmag_is_section_displayed( $displayed_on = array() ) {

	$displayed = false;

	if ( is_front_page() && in_array( 'front', $displayed_on, true ) ) {
		$displayed = true;
	} elseif ( is_home() && in_array( 'blog', $displayed_on, true ) ) {
		$displayed = true;
	} elseif ( is_search() && in_array( 'search', $displayed_on, true ) ) {
		$displayed = true;
	} elseif ( is_archive() && in_array( 'archive', $displayed_on, true ) ) {
		$displayed = true;
	} elseif ( is_404() && in_array( '404', $displayed_on, true ) ) {
		$displayed = true;
	}

	return $displayed;
}