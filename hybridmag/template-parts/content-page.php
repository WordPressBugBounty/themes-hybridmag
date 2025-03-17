<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HybridMag
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		// Before content hook
		do_action( 'hybridmag_before_content' );

		/**
		 * Before entry header hook.
		 * 
		 * @since 1.0.0
		 */
		do_action( 'hybridmag_before_entry_header' );
	?>

	<header class="entry-header">
		<?php 
			// Before page title hook.
			do_action( 'hybridmag_before_page_title' );

			$hybridmag_hide_page_title = get_post_meta( $post->ID, '_hybridmag_hide_page_title', true );
			
			if ( empty( $hybridmag_hide_page_title ) || "false" === $hybridmag_hide_page_title ) {
				the_title( '<h1 class="entry-title">', '</h1>' ); 
			}

			// After page title hook.
			do_action( 'hybridmag_after_page_title' );
		?>
	</header><!-- .entry-header -->

	<?php 
		// After page header hook.
		do_action( 'hybridmag_after_page_header' );

		if ( get_theme_mod( 'hybridmag_show_page_thumbnail', true ) ) {
			hybridmag_post_thumbnail(); 
		}
		
	?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hybridmag' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
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
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
	
	<?php 
		// After content hook
		do_action( 'hybridmag_after_content' ); 
	?>


</article><!-- #post-<?php the_ID(); ?> -->
