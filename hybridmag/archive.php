<?php
/**
 * The template for displaying archive pages
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
		?>

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					do_action( 'hybridmag_before_archive_title' );
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					do_action( 'hybridmag_after_archive_title' );
				?>
			</header><!-- .page-header -->

			<?php
				/**
				 * Before blog entries hook.
				 * @since 1.0.0
				 */
				do_action( 'hybridmag_before_blog_entries' );
			?>

			<div id="blog-entries">

				<?php

				/**
				 * Before Archive Loop Hook
				 */
				do_action( 'hybridmag_before_loop', 'archive' );

				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/*
					* Include the Post-Type-specific template for the content.
					* If you want to override this in a child theme, then include a file
					* called content-___.php (where ___ is the Post Type name) and that will be used instead.
					*/
					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;

				/**
				 * After Archive Loop Hook
				 */
				do_action( 'hybridmag_after_loop', 'archive' );

				?>

			</div><!-- #blog-entries -->

		<?php

			hybridmag_posts_pagination();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;

		/**
		 * After Main Content Hook
		 */
		do_action( 'hybridmag_after_main_content' );

		?>

	</main><!-- #main -->

<?php
hybridmag_get_sidebar();

get_footer();
