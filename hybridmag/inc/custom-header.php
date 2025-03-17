<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package HybridMag
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses hybridmag_header_style()
 */
function hybridmag_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'hybridmag_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 2000,
				'height'             => 550,
				'flex-height'        => true,
				'flex-width'		 => true,
				'wp-head-callback'   => 'hybridmag_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'hybridmag_custom_header_setup' );

if ( ! function_exists( 'hybridmag_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see hybridmag_custom_header_setup().
	 */
	function hybridmag_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
			.site-title a,
			.site-title a:hover,
			.site-title a:active,
			.site-title a:focus,
			.site-title a:visited,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		</style>
		<?php
	}
endif;

if ( ! function_exists( 'hybridmag_header_image' ) ) :
	/**
	 * Displays header image.
	 */
	function hybridmag_header_image() {

		$header_image = get_header_image();

		if ( ! empty ( $header_image ) ) : 

			$custom_header = get_custom_header();

			$alt = "";

			// Use alternative text assigned to the image, if available. Otherwise, leave it empty.
			if ( ! empty( $custom_header->attachment_id ) ) {
				$image_alt = get_post_meta( $custom_header->attachment_id, '_wp_attachment_image_alt', true );
			
				if ( is_string( $image_alt ) ) {
					$alt = $image_alt;
				}
			}
		
			$hybridmag_link_header_image = get_theme_mod( 'hybridmag_link_header_image', false );
			echo '<div class="hm-header-image">';
				if ( $hybridmag_link_header_image == true ) { echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" rel="home">'; }
					echo '<img src="' . esc_url ( $header_image ) . '" height="' . esc_attr( $custom_header->height ) . '" width="' . esc_attr( $custom_header->width ) . '" alt="' . esc_attr( $alt ) . '" />';
				if ( $hybridmag_link_header_image == true ) { echo '</a>'; }
			echo '</div>';

		endif;

	}

endif;