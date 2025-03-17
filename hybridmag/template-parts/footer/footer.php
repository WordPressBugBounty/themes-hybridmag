<?php
/**
 * Footer Template
 */
?>

<footer id="colophon" class="site-footer">

    <?php
        /**
         * Footer widget area
         */
        do_action( 'hybridmag_footer_widget_area' );
    ?>

    <div class="hm-footer-bottom">
        <?php
            /**
             * Footer Bottom
             */
            do_action( 'hybridmag_footer_bottom' );
        ?>
    </div><!-- .hm-footer-bottom -->

</footer><!-- #colophon -->