<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HybridMag
 */

?>

<?php do_action( 'hybridmag_before_article' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		// Before content hook
		do_action( 'hybridmag_before_content' );
	?>

	<?php 
		// Before entry header hook.
		do_action( 'hybridmag_before_entry_header' );

	?>

	<header class="entry-header">
		<?php

		// Before entry title hook.
		do_action( 'hybridmag_before_entry_title' );
		
		the_title( '<h1 class="entry-title">', '</h1>' );

		// After entry title hook.
		do_action( 'hybridmag_after_entry_title' );

		?>
	</header><!-- .entry-header -->

	<?php
		// After entry header hook.
		do_action( 'hybridmag_after_entry_header' );
	?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'hybridmag' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hybridmag' ),
				'after'  => '</div>',
			)
		);

		?>
	</div><!-- .entry-content -->

	<?php 
		// After entry content hook.
		do_action( 'hybridmag_after_entry_content' );
	?>

	<?php 
		if ( ! is_singular( 'attachment' ) ) { 
			get_template_part( 'template-parts/author', 'bio' ); 
		}
	?>

	<?php 
		// After content hook
		do_action( 'hybridmag_after_content' ); 
	?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action( 'hybridmag_after_article' ); ?>