<?php

/**
 * Adds jetpack modules support.
 */
function hybridmag_jetpack_setup() {
	if ( ! defined( 'HYBRIDMAG_PRO_VERSION' ) ) {
		add_theme_support( 'infinite-scroll', array(
			'type'           	=> 'click',
			'container' 		=> 'blog-entries',
			'render'			=> 'hybridmag_infinite_scroll_render',
			'footer' 			=> 'page',
			'footer_widgets' 	=> false,
		) );
	}
}
add_action( 'after_setup_theme', 'hybridmag_jetpack_setup' );

/**
 * Infinite scroll render
 */
function hybridmag_infinite_scroll_render() {
    ?>
    <div id="blog-entries">

	<?php
		while ( have_posts() ) {
			the_post();

			if ( is_search() ) :
				get_template_part( 'template-parts/content', 'search' );
			else :
				get_template_part( 'template-parts/content', get_post_type() );
			endif;
		} 
	?>

	</div><!-- #blog-entries -->

    <?php
}
