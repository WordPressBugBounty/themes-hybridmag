<?php
/**
 * Template Name: Magazine Template
 *
 * @package HybridMag
 * @since HybridMag 1.0.0
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

        the_content();

    endwhile; // End of the loop.

    /**
     * After Main Content Hook
    */
    do_action( 'hybridmag_after_main_content' );

    ?>

</main><!-- #main -->

<?php

get_footer();