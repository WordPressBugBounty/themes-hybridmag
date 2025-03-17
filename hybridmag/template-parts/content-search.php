<?php
/**
 * Template part for displaying results in search pages
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
	?>

	<div class="hm-article-inner">

		<?php 
			// Before entry header hook.
			do_action( 'hybridmag_before_entry_header' );

		?>
		
		<header class="entry-header">

			<?php

			// Before entry title hook.
			do_action( 'hybridmag_before_entry_title' );
			
			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); 
			
			// After entry title hook.
			do_action( 'hybridmag_after_entry_title' );

			?>
		</header><!-- .entry-header -->

		<?php
			// After entry header hook.
			do_action( 'hybridmag_after_entry_header' );
		?>

		<div class="entry-content-wrapper">

			<?php
				// Before entry content hook.
				do_action( 'hybridmag_before_entry_content' );
			?>

			<div class="entry-content">
				<?php 
					the_excerpt();

					hybridmag_read_more_button();
				?>
			</div><!-- .entry-content -->

			<?php 
				// After entry content hook.
				do_action( 'hybridmag_after_entry_content' );
			?>

		</div><!-- .entry-content-wrapper -->

	</div><!-- .hm-article-inner -->

	<?php 
		// After content hook
		do_action( 'hybridmag_after_content' ); 
	?>
	
</article><!-- #post-<?php the_ID(); ?> -->