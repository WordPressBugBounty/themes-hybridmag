<?php
    $hybridmag_footer_sidebar_count = get_theme_mod( 'hybridmag_footer_sidebar_count', '3' );
?>

<div class="hm-footer-widget-area">
    <div class="hm-container hm-footer-widgets-inner">
        <div class="hm-footer-column">
            <?php dynamic_sidebar( 'footer-1' ); ?>
        </div><!-- .hm-footer-column -->

        <?php if ( $hybridmag_footer_sidebar_count >= 2 ) : ?>
            <div class="hm-footer-column">
                <?php dynamic_sidebar( 'footer-2' ); ?>
            </div><!-- .hm-footer-column -->
        <?php endif; ?>

        <?php if ( $hybridmag_footer_sidebar_count >= 3 ) : ?>
            <div class="hm-footer-column">
                <?php dynamic_sidebar( 'footer-3' ); ?>
            </div><!-- .hm-footer-column -->
        <?php endif; ?>

        <?php if ( $hybridmag_footer_sidebar_count >= 4 ) : ?>
            <div class="hm-footer-column">
                <?php dynamic_sidebar( 'footer-4' ); ?>
            </div><!-- .hm-footer-column -->
        <?php endif; ?>
    </div><!-- .hm-footer-widgets-inner -->
</div><!-- .hm-footer-widget-area -->