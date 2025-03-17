<?php
/**
 * Header Template
 */
?>

<header id="masthead" class="site-header hide-header-search">

    <?php do_action( 'hybridmag_header_top' ); ?>

    <div class="hm-header-inner-wrapper">

        <?php 
            /**
             * Before header inner action
             */
            do_action( 'hybridmag_before_header_inner' );
        ?>

        <div class="hm-header-inner hm-container">

            <?php do_action( 'hybridmag_before_header_main' ); ?>

            <?php do_action( 'hybridmag_header_main' ); ?>

            <?php do_action( 'hybridmag_after_header_main' ); ?>
        
        </div><!-- .hm-header-inner -->

        <?php 
            /**
             * After header inner action
             */
            do_action( 'hybridmag_after_header_inner' );
        ?>

    </div><!-- .hm-header-inner-wrapper -->

    <?php do_action( 'hybridmag_header_bottom' ); ?>

</header><!-- #masthead -->