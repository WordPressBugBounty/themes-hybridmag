<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package HybridMag
 */



if ( ! function_exists( 'hybridmag_primary_nav' ) ) : 
	/**
	 * Displays primary navigation.
	 * 
	 */
	function hybridmag_primary_nav() {
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location'	=> 'primary',
				'menu_id'			=> 'primary-menu',
				'hybridmag_show_icons'	=> true
			) );
		} else {
			wp_page_menu( array(
				'title_li'      	=> '',
				'hybridmag_show_icons'	=> true,
				'walker'			=> new HybridMag_Walker_Page()
			) );
		}
	}

endif;

if ( ! function_exists( 'hybridmag_primary_nav_sidebar' ) ) : 
	/**
	 * Displays primary navigation.
	 * 
	 */
	function hybridmag_primary_nav_sidebar() {
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' 		=> 'primary',
				'menu_id'        		=> 'primary-menu',
				'hybridmag_show_toggles'	=> true
			) );
		} else {
			wp_page_menu( array(
				'title_li'      		=> '',
				'hybridmag_show_toggles'  	=> true,
				'walker'        		=> new HybridMag_Walker_Page()
			) );
		}
	}

endif;

if ( ! function_exists( 'hybridmag_secondary_nav' ) ) : 
	/**
	 * Displays secondary navigation.
	 */
	function hybridmag_secondary_nav() {
		wp_nav_menu( array(
			'theme_location' 	=> 'secondary',
			'menu_id'        	=> 'secondary-menu',
			'hybridmag_show_icons'	=> true
		) );
	}

endif;

if ( ! function_exists( 'hybridmag_secondary_nav_mobile' ) ) : 
	/**
	 * Displays secondary navigation.
	 */
	function hybridmag_secondary_nav_mobile() {
		wp_nav_menu( array(
			'theme_location' 		=> 'secondary',
			'menu_id'        		=> 'secondary-menu',
			'hybridmag_show_toggles'   	=> true
		) );
	}

endif;

if ( ! function_exists( 'hybridmag_social_nav' ) ) : 
	/**
	 * Displays social navigation.
	 */
	function hybridmag_social_nav() {
		if ( has_nav_menu( 'social' ) ) : ?>
			<nav class="hm-social-menu hm-social-nav" aria-label="<?php esc_attr_e( 'Expanded Social links', 'hybridmag' ); ?>">
				<ul class="hm-social-menu hm-social-icons">
				<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'social',
							'container'       => '',
							'container_class' => '',
							'items_wrap'      => '%3$s',
							'menu_id'         => '',
							'menu_class'      => '',
							'depth'           => 1,
							'link_before'     => '<span class="screen-reader-text">',
							'link_after'      => '</span>',
							'fallback_cb'     => '',
						)
					);
				?>
				</ul>
			</nav><!-- .hm-social-menu -->
		<?php
		endif; 
	}
endif;


if ( ! function_exists( 'hybridmag_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function hybridmag_posted_on() {

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published sm-hu" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		add_filter( 'get_the_modified_date', 'hybridmag_convert_modified_to_time_ago', 10, 3 );

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		remove_filter( 'get_the_modified_date', 'hybridmag_convert_modified_to_time_ago', 10, 3 );

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		return $posted_on;

	}
endif;

if ( ! function_exists( 'hybridmag_author_avatar' ) ) :

	function hybridmag_author_avatar() {

		$author_email	= get_the_author_meta( 'user_email' );
		$avatar_url 	= get_avatar_url( $author_email );
		
		return '<span class="hm-author-avatar"><img class="author-photo" alt="' . esc_attr( get_the_author() ) . '" src="' . esc_url( $avatar_url ) . '" /></span>';

	}

endif;

if ( ! function_exists( 'hybridmag_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function hybridmag_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'hybridmag' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		return $byline;

	}
endif;

if ( ! function_exists( 'hybridmag_categories' ) ) :
	/**
	 * Prints the category list
	 */
	function hybridmag_categories( $seperator = '' ) {
		if ( 'post' === get_post_type() ) {

			if ( is_single() ) {
				$show_category_list = get_theme_mod( 'hybridmag_show_cat_links_s', true );
			} else {
				$show_category_list = get_theme_mod( 'hybridmag_show_cat_links', true );
			}

			if ( ! $show_category_list ) {
				return;
			}

			add_filter( 'the_category', 'hybridmag_custom_class_to_category_links', 10, 3 );
			$categories_list = get_the_category_list();
			remove_filter( 'the_category', 'hybridmag_custom_class_to_category_links', 10, 3 );
			if ( $categories_list ) {
				/* translators: 1: posted in label 2: list of categories. */
				printf( 
					'<span class="cat-links"><span class="screen-reader-text">%1$s</span>%2$s</span>', 
					esc_html__( 'Posted in', 'hybridmag' ),
					apply_filters( 'hybridmag_theme_categories', $categories_list )
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

endif;
add_action( 'hybridmag_before_entry_title', 'hybridmag_categories' );

/**
 * Adds a class to class to the category link.
 */
function hybridmag_custom_class_to_category_links( $thelist, $separator, $parents ) {
    // Use regex to find and replace the category links with the added class
    $thelist = preg_replace_callback(
        '/<a href="([^"]+)"(.*?)>(.*?)<\/a>/',
        function( $matches ) {
			$category_id = get_cat_ID( $matches[3] );
			$term        = get_term( $category_id );
			return sprintf( '<a href="%s" class="cat-%d" rel="' . $term->taxonomy . '" >%s</a>', $matches[1], $category_id, $matches[3] );
        },
        $thelist
    );

    return $thelist;
}

if ( ! function_exists( 'hybridmag_tags_list' ) ) :
	/**
	 * Prints the tags list
	 */
	function hybridmag_tags_list() {
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'hybridmag' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'hybridmag' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

endif;

if ( ! function_exists( 'hybridmag_comments_link' ) ) :
	/**
	 * Prints comments link
	 */
	function hybridmag_comments_link() {

		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {

			$num_comments = esc_attr( get_comments_number() );

			if ( $num_comments == 0 ) {
				$comments_txt = esc_html__( '0', 'hybridmag' );
			} elseif ( $num_comments > 1 ) {
				/* translators: %d: number of comments */
				$comments_txt = sprintf( esc_html__( '%d', 'hybridmag' ), $num_comments );
			} else {
				$comments_txt = esc_html__( '1', 'hybridmag' );
			}

			return '<a href="' . esc_url( get_comments_link() ).'">' . $comments_txt . '</a>';
		}	

		return '';

	}

endif;

if ( ! function_exists( 'hybridmag_entry_meta' ) ) :
	/**
	 * Entry Meta
	 */
	function hybridmag_entry_meta() {

		$entry_meta_post_types = apply_filters(
			'hybridmag_entry_meta_post_types',
			array(
				'post',
			)
		);
	
		// Return early if this function called on other post types.
		if ( ! in_array( get_post_type(), $entry_meta_post_types ) ) {
			return;
		}
	
		if ( is_single() ) {
			$entry_meta = get_theme_mod( 'hybridmag_archive_entry_meta_s', 'author,date,comments' );
			$show_avatar = get_theme_mod( 'hybridmag_show_author_avatar_s', false );

		} else {
			$entry_meta = get_theme_mod( 'hybridmag_archive_entry_meta', 'author,date,comments' );
			$show_avatar = get_theme_mod( 'hybridmag_show_author_avatar', false );
		}

		if ( strpos( $entry_meta, ',' ) !== false ) {
			$entry_meta_type_array = explode( ',', $entry_meta );
		} else {
			$entry_meta_type_array = array( $entry_meta );
		}
		
		$entry_meta_type_array = array_map( 'trim', $entry_meta_type_array );

		$entry_meta_array = array();

		$entry_meta_seperator = get_theme_mod( 'hybridmag_entry_meta_seperator', 'dot' );

		foreach( $entry_meta_type_array as $meta_item ) {

			if ( 'author' === $meta_item ) {
				$author_str = '<span class="byline">';
				if ( 'icon' === $entry_meta_seperator && false === $show_avatar ) {
					$author_str .= hybridmag_get_icon_svg( 'user' );
				}
				if ( true === $show_avatar ) {
					$author_str .= hybridmag_author_avatar();
				}
				$author_str .= hybridmag_posted_by();
				$author_str .= '</span>';
				
				$entry_meta_array[] = $author_str;
			}

			if ( 'date' === $meta_item ) {
				$date_str = '<span class="posted-on">';
				if ( 'icon' === $entry_meta_seperator ) {
					$date_str .= hybridmag_get_icon_svg( 'clock' );
				}
				$date_str .= hybridmag_posted_on();
				$date_str .= '</span>';

				$entry_meta_array[] = $date_str;
			}

			if ( 'comments' === $meta_item ) {
				$comments_link = hybridmag_comments_link();
				if ( ! empty( $comments_link ) ) {

					$comments_str = '<span class="comments-link">';
					//if ( 'icon' === $entry_meta_seperator ) {
						$comments_str .= hybridmag_get_icon_svg( 'comment' );
					//}
					$comments_str .= $comments_link;
					$comments_str .= '</span>';

					$entry_meta_array[] = $comments_str;
				}
			}

			if ( 'categories' == $meta_item ) {
				/* translators: used between list items, there is a space after the comma */
				$seperator = esc_html__(  ", ", 'hybridmag' );
				$categories_list = get_the_category_list( $seperator );
				if ( $categories_list ) {
					$categories_str = '<span class="cat-links">';
					$categories_str .= '<span class="screen-reader-text">' . esc_html__( 'Posted in', 'hybridmag' ) . '</span>';
					if ( 'icon' === $entry_meta_seperator ) {
						$categories_str .= hybridmag_get_icon_svg( 'folder' );
					}
					$categories_str .= apply_filters( 'hybridmag_theme_categories', $categories_list );
					$categories_str .= '</span>';

					$entry_meta_array[] = $categories_str;
				}
			}

			if ( 'tags' == $meta_item ) {
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'hybridmag' ) );
				if ( $tags_list ) {				
					$tags_str = '<span class="tags-links">';
					if ( 'icon' === $entry_meta_seperator ) {
						$tags_str .= hybridmag_get_icon_svg( 'tags' );
					}
					$tags_str .= $tags_list;
					$tags_str .= '</span>';

					$entry_meta_array[] = $tags_str;
				}
			}

		}

		if ( ! empty( $entry_meta_array ) ) {
			echo '<div class="entry-meta">';
				switch( $entry_meta_seperator ) {
					case "dash":
						$entry_meta_seperator = '-';
						break;
					case "vbar":
						$entry_meta_seperator = '|';
						break;
					case "mdash":
						$entry_meta_seperator = '—';
						break;	
					case "dot":
						$entry_meta_seperator = '•';
						break;	
					case "slash":
						$entry_meta_seperator = '/';
						break;
					default:
						$entry_meta_seperator = '';
				}
				$entry_meta_string = implode( '<span class="hm-meta-sep">' . $entry_meta_seperator . '</span>', $entry_meta_array );
				echo $entry_meta_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
		}

	}

endif;

/**
 * Sync BNM Blocks meta data separtor with the theme
 */
function hybridmag_sync_meta_separator( $default ) {
	
	$meta_separator = get_theme_mod( 'hybridmag_entry_meta_seperator', 'dot' );
	switch ( $meta_separator ) {
		case 'dash': 
			$sep = '-';
			break;
		case 'vbar': 
			$sep = '|';
			break;
		case 'mdash': 
			$sep = '—';
			break;
		case 'dot': 
			$sep = '•';
			break;
		case 'slash': 
			$sep = '/';
			break;
		default: 
			$sep = $default;
			break;
	}

	return '<span class="hm-meta-sep">' . $sep . '</span>';
}
add_filter( 'bnm_blocks_meta_data_separator', 'hybridmag_sync_meta_separator' );

if ( ! function_exists( 'hybridmag_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function hybridmag_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			if ( is_single() && true === get_theme_mod( 'hybridmag_show_tags_list_s', true ) ) {
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list();
				if ( $tags_list ) {
					/* translators: 1: list of tags. */
					printf( 
						'<div class="hm-tag-list"><span class="hm-tagged">%1$s</span><span class="tags-links hm-tags-links">%2$s</span></div>', 
						esc_html__( 'Tagged', 'hybridmag' ), 
						$tags_list 
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			} 
			
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'hybridmag' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'hybridmag' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'hybridmag_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function hybridmag_post_thumbnail() {

		global $post;

		if ( ! hybridmag_can_show_post_thumbnail() ) {
			return;
		}

		$image_size = apply_filters( 'hybridmag_post_thumbnail_size', 'hm-featured-image' );

		if ( is_singular() ) :

			$hide_featured_image = get_post_meta( $post->ID, '_hybridmag_hide_featured_image', true );
			if ( empty( $hide_featured_image ) || $hide_featured_image === 'false' ) {
				$show_thumbnail = true;
			} else {
				$show_thumbnail = false;
			}

			if ( $show_thumbnail ) {
			?>
				<div class="post-thumbnail">
					<?php the_post_thumbnail( $image_size ); ?>
					<?php hybridmag_post_thumbnail_caption(); ?>
				</div><!-- .post-thumbnail -->
			<?php } ?>

		<?php else : ?>

			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php
						the_post_thumbnail(
							$image_size,
							array(
								'alt' => the_title_attribute(
									array(
										'echo' => false,
									)
								),
							)
						);
					?>
				</a>
			</div><!-- .post-thumbnail -->

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'hybridmag_post_thumbnail_caption' ) ) {
	/**
	 * Displays a post thumbnail caption and/or credit.
	 *
	 * Wraps the caption and credit in a figcaption and span.
	 */
	function hybridmag_post_thumbnail_caption() {
		if ( ! hybridmag_can_show_post_thumbnail() ) {
			return;
		}

		// Check the existance of the caption separately.
		$thumbnail      = get_post( get_post_thumbnail_id() );
		$caption_exists = $thumbnail && $thumbnail->post_excerpt;

		// Only get the caption if one exists.
		if ( $caption_exists ) {
			$caption = get_the_excerpt( get_post_thumbnail_id() );
		}

		if ( $caption_exists ) :
			?>
			<figcaption><span><?php echo wp_kses_post( $caption ); ?></span></figcaption>
			<?php
		endif;
	}
}

if ( ! function_exists( 'hybridmag_post_previous_next' ) ) :
	/**
	 * Prints previous and next links for single posts.
	 */
	function hybridmag_post_previous_next() {
		if ( true === get_theme_mod( 'hybridmag_post_previous_next', true ) && is_singular( 'post' ) ) {
			the_post_navigation(
				array(
					'next_text' => '<span class="posts-nav-text" aria-hidden="true">' . esc_html__( 'Next Article', 'hybridmag' ) . '</span> ' .
						'<span class="screen-reader-text">' . esc_html__( 'Next article:', 'hybridmag' ) . '</span> <br/>' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="posts-nav-text" aria-hidden="true">' . esc_html__( 'Previous Article', 'hybridmag' ) . '</span> ' .
						'<span class="screen-reader-text">' . esc_html__( 'Previous article:', 'hybridmag' ) . '</span> <br/>' .
						'<span class="post-title">%title</span>',
				)
			);
		}
	}
endif;
add_action( 'hybridmag_after_article', 'hybridmag_post_previous_next', 12 );

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;


if ( ! function_exists( 'hybridmag_posts_pagination' ) ) {
	/**
	 * Posts pagination.
	 */
	function hybridmag_posts_pagination() {

		$pagination_type = get_theme_mod( 'hybridmag_pagination_type', 'page-numbers' );

		if ( $pagination_type == 'page-numbers' ) {
			the_posts_pagination();
		} else {
			the_posts_navigation(
				array(
					'prev_text' => esc_html__( '&larr; Older Posts', 'hybridmag' ),
					'next_text' => esc_html__( 'Newer Posts &rarr;', 'hybridmag' ),
				)
			);
		}

	}

}

if ( ! function_exists( 'hybridmag_read_more_button' ) ) {
	/**
	 * Read More Button Markup
	 */
	function hybridmag_read_more_button() {
		if ( 'button' === get_theme_mod( 'hybridmag_read_more_type', 'link' ) ) : ?>
			<div class="entry-readmore">
				<a href="<?php the_permalink(); ?>" class="hm-readmore-btn">
					<?php the_title( '<span class="screen-reader-text">', '</span>' ); ?>
					<?php echo esc_html_e( 'Read More', 'hybridmag' ); ?>
				</a>
			</div>
		<?php endif; 
	}
}

if ( ! function_exists( 'hybridmag_entry_footer_markup' ) ) {
	/**
	 * Entry footer.
	 */
	function hybridmag_entry_footer_markup() {
		if ( is_single() ) {
			?>
				<footer class="entry-footer">
					<?php hybridmag_entry_footer(); ?>
				</footer><!-- .entry-footer -->
			<?php
		}
	}
}
add_action( 'hybridmag_after_entry_content', 'hybridmag_entry_footer_markup' );


/**
 * Add entry meta to the selected locations.
 */
function hybridmag_locate_entry_meta() {

	if ( is_home() || is_archive() || is_search() ) { 

		$location = get_theme_mod( 'hybridmag_entry_meta_location', 'footer' );
		if ( 'footer' === $location ) {
			add_action( 'hybridmag_after_entry_content', 'hybridmag_entry_meta' );
		} else {
			add_action( 'hybridmag_after_entry_title', 'hybridmag_entry_meta' );
		}
		
	} elseif ( is_single() ) {
		add_action( 'hybridmag_after_entry_title', 'hybridmag_entry_meta' );
	}

}
add_action( 'wp', 'hybridmag_locate_entry_meta', 5 );


/**
 * View all link
 */
function hybridmag_get_viewall_link( $category_id ) {
	if ( ! empty( $category_id ) ) {
		$viewall_link = get_category_link( $category_id );
	} else {

		if ( is_home() && is_front_page() ) {
			$viewall_link = "#blog-entries";
		} else {
			$posts_page_id = get_option( 'page_for_posts' );

			if ( $posts_page_id ) {
				$viewall_link = get_page_link( $posts_page_id );
			} else {
				$viewall_link = "";
			}
		}

	}
	return $viewall_link;
}

add_filter( 'bnm_blocks_meta_order', 'hybridmag_sync_bnm_blocks_meta_order', 10, 1 );
function hybridmag_sync_bnm_blocks_meta_order( $plugin_meta_array ) {
	// Get the meta order defined in the theme customizer
	$theme_meta = get_theme_mod( 'hybridmag_archive_entry_meta', 'author,date,comments' );

	$theme_meta_array = array_map( 'trim', explode( ',', $theme_meta ) );

	// Filter the theme's meta array to include only items defined in the plugin's $plugin_meta_array
	$final_meta_array = array_filter( $theme_meta_array, function( $item ) use ( $plugin_meta_array ) {
		return in_array( $item, $plugin_meta_array, true );
	});

	return $final_meta_array;
}

/**
* Determines whether the site has a dark mode logo.
*
* @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
* @return bool Whether the site has a custom logo or not.
*/
function hybridmag_has_dark_mode_logo( $blog_id = 0 ) {
	$switched_blog = false;

	if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
		switch_to_blog( $blog_id );
		$switched_blog = true;
	}

	$custom_logo_id = get_theme_mod( 'hybridmag_dark_mode_logo' );
	$is_image       = ( $custom_logo_id ) ? wp_attachment_is_image( $custom_logo_id ) : false;

	if ( $switched_blog ) {
		restore_current_blog();
	}

	return $is_image;
}

/**
 * Returns a custom logo, linked to home unless the theme supports removing the link on the home page.
 *
 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
 * @return string Custom logo markup.
 */
function hybridmag_get_dark_mode_logo( $blog_id = 0 ) {
	$html          = '';
	$switched_blog = false;

	if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
		switch_to_blog( $blog_id );
		$switched_blog = true;
	}

	// We have a logo. Logo is go.
	if ( hybridmag_has_dark_mode_logo() ) {
		$custom_logo_id   = get_theme_mod( 'hybridmag_dark_mode_logo' );
		$custom_logo_attr = array(
			'class'   => 'custom-logo',
			'loading' => false,
		);

		$unlink_homepage_logo = (bool) get_theme_support( 'custom-logo', 'unlink-homepage-logo' );

		if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
			/*
			 * If on the home page, set the logo alt attribute to an empty string,
			 * as the image is decorative and doesn't need its purpose to be described.
			 */
			$custom_logo_attr['alt'] = '';
		} else {
			/*
			 * If the logo alt attribute is empty, get the site title and explicitly pass it
			 * to the attributes used by wp_get_attachment_image().
			 */
			$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
			if ( empty( $image_alt ) ) {
				$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
			}
		}

		/**
		 * Filters the list of custom dark mode logo image attributes.
		 *
		 * @param array $custom_logo_attr Custom logo image attributes.
		 * @param int   $custom_logo_id   Custom logo attachment ID.
		 * @param int   $blog_id          ID of the blog to get the custom logo for.
		 */
		$custom_logo_attr = apply_filters( 'hybridmag_get_dark_mode_image_attributes', $custom_logo_attr, $custom_logo_id, $blog_id );

		/*
		 * If the alt attribute is not empty, there's no need to explicitly pass it
		 * because wp_get_attachment_image() already adds the alt attribute.
		 */
		$image = wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr );

		// Check that we have a proper HTML img element.
		if ( $image ) {

			if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
				// If on the home page, don't link the logo to home.
				$html = sprintf(
					'<span class="custom-logo-link">%1$s</span>',
					$image
				);
			} else {
				$aria_current = ! is_paged() && ( is_front_page() || is_home() && ( (int) get_option( 'page_for_posts' ) !== get_queried_object_id() ) ) ? ' aria-current="page"' : '';

				$html = sprintf(
					'<a href="%1$s" class="custom-logo-link" rel="home"%2$s>%3$s</a>',
					esc_url( home_url( '/' ) ),
					$aria_current,
					$image
				);
			}
		}
	} elseif ( is_customize_preview() ) {
		// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
		$html = sprintf(
			'<a href="%1$s" class="custom-logo-link" style="display:none;"><img class="custom-logo" alt="" /></a>',
			esc_url( home_url( '/' ) )
		);
	}

	if ( $switched_blog ) {
		restore_current_blog();
	}

	/**
	 * Filters the custom logo output.
	 *
	 * @param string $html    Custom logo HTML output.
	 * @param int    $blog_id ID of the blog to get the custom logo for.
	 */
	return apply_filters( 'hybridmag_get_custom_dark_mode_logo', $html, $blog_id );
}

/**
 * Displays a custom logo for dark mode
 *
 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
 */
function hybridmag_the_dark_mode_logo() {
	echo hybridmag_get_dark_mode_logo();
}