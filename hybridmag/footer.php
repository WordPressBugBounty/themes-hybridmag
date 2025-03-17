<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package HybridMag
 */

?>
	</div><!-- .hm-container -->
	</div><!-- .site-content -->

	<?php
		/**
		 * Before Footer Hook
		 */
		do_action( 'hybridmag_before_footer' ); 


		/**
		 * Footer Hook.
		 */
		do_action( 'hybridmag_footer' );


		/**
		 * After Footer Hook.
		 */
		do_action( 'hybridmag_after_footer' );
	?>

</div><!-- #page -->

<?php
get_template_part( 'template-parts/mobile', 'sidebar' );
get_template_part( 'template-parts/desktop', 'sidebar' );
?>

<?php wp_footer(); ?>

</body>
</html>
