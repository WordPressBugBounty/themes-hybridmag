<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HybridMag
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php

		/**
		 * Before Main Content Hook
		 */
		do_action( 'hybridmag_before_main_content' );

		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.

		/**
		 * After Main Content Hook
		 */
		do_action( 'hybridmag_after_main_content' );

		?>

	</main><!-- #main -->

<?php
hybridmag_get_sidebar();

get_footer();