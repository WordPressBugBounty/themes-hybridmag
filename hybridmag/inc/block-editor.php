<?php
/**
 * Functions related to block editor
 */

/**
 * Check wgat sidebar layout we are using on the current editing post.
 */
function hybridmag_get_block_editor_sidebar_layout() {

	$layout = 'right-sidebar';

	if ( function_exists( 'get_current_screen' ) ) {
		$screen = get_current_screen();

		if ( is_object( $screen ) ) {
            if ( 'post' == $screen->post_type ) {
                $layout = get_theme_mod( 'hybridmag_post_layout', 'right-sidebar' );

				// Get the post specific layout
				$layout_meta = get_post_meta( get_the_ID(), '_hybridmag_layout_meta', true );
				if ( $layout_meta && 'default-layout' != $layout_meta ) {
					$layout = $layout_meta;
				}

            } elseif ( 'page' == $screen->post_type ) {
                $layout = get_theme_mod( 'hybridmag_page_layout', 'right-sidebar' );

				// Get the page specific layout
				$layout_meta = get_post_meta( get_the_ID(), '_hybridmag_layout_meta', true );
				if ( $layout_meta && 'default-layout' != $layout_meta ) {
					$layout = $layout_meta;
				}

				$post_id = get_the_ID();
                if ( $post_id ) {
					$template_slug = get_page_template_slug( $post_id );
					// Over write everything if magazine template or fullwidth template
					if ( 'page-templates/template-magazine.php' === $template_slug || 'page-templates/template-fullwidth.php'=== $template_slug ) {
						$layout = 'no-sidebar';
					}
                }
            }
        } 

	}

	return apply_filters( 'hybridmag_block_editor_sidebar_layout', $layout );

}

 /**
 * Get the content width for current post
 */
function hybridmag_get_block_editor_content_width() {
	
	$site_layout = get_theme_mod( 'hybridmag_site_layout', 'wide' );
	if ( 'boxed' == $site_layout ) {
		$boxed_width = get_theme_mod( 'hybridmag_boxed_width', 1280 );
		$container_width = ( $boxed_width * 93.75 ) / 100;
	} else {
		$container_width = get_theme_mod( 'hybridmag_container_width', 1200 );
	}
	
	$sidebar_width = get_theme_mod( 'hybridmag_sidebar_width', 32.916666667 );
	$layout = hybridmag_get_block_editor_sidebar_layout();

	if ( 'left-sidebar' === $layout || 'right-sidebar' === $layout ) {
		$content_width = $container_width * ( ( 100 - $sidebar_width ) / 100 );
	} elseif ( 'no-sidebar' === $layout ) {
		$content_width = $container_width;
	} else {
		$content_width = 900;
	}

	return apply_filters( 'hybridmag_block_editor_content_width', $content_width );
}

/**
 * Inline styles for the block editor.
 */
function hybridmag_inline_block_editor_styles() {
    $custom_css = "";

    $css_variables = "";

    $content_width = hybridmag_get_block_editor_content_width();

	// For now this variable is not using.
    if ( $content_width ) {
        $css_variables .= '
            --hybridmag-content-width: '. esc_attr( $content_width ) .'px !important;
        ';
    }

    $custom_css .= '
        :root { ' . $css_variables . ' }
    ';

    $custom_css .= '
        .wp-block {
            max-width: '. esc_attr( $content_width ) .'px;
        }
    ';

    return $custom_css;
}

/**
 * Adds custom classes to the body on Gutenberg Editor
 * 
 * @since 1.0.7
 */
function hybridmag_gutenberg_body_class( $classes ) {

    $screen = get_current_screen();

	if ( $screen && $screen->is_block_editor ) {

		$site_layout = get_theme_mod( 'hybridmag_site_layout', 'wide' );
		if ( 'boxed' === $site_layout ) {
			$classes .= ' hybridmag-boxed';
		} elseif ( 'wide' === $site_layout ) {
			$classes .= ' hybridmag-wide';
		}

		$content_layout = get_theme_mod( 'hybridmag_content_layout', 'separate-containers' );
		if ( 'separate-containers' === $content_layout ) {
			$classes .= ' hm-cl-sep';
		} else {
			$classes .= ' hm-cl-one';
		}

		if ( $screen->post_type === 'page' ) {
			$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
			if ( $post_id ) {
				$template_slug = get_page_template_slug( $post_id );
				// Check if this page uses the 'template-magazine.php' template
				if ( $template_slug === 'page-templates/template-magazine.php' ) {
					$classes .= ' page-template-template-magazine';
				}
			}
		}

	}

    return $classes;
}
add_filter( 'admin_body_class', 'hybridmag_gutenberg_body_class' );