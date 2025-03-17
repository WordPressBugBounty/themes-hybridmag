<?php

/**
 * Footer template part.
 */
function hybridmag_footer_template() {
    get_template_part( 'template-parts/footer/footer' );
}
add_action( 'hybridmag_footer', 'hybridmag_footer_template' );

/**
 * Footer widget area template.
 */
function hybridmag_footer_widgets_template() {
    get_template_part( 'template-parts/footer/widgets' );
}
add_action( 'hybridmag_footer_widget_area', 'hybridmag_footer_widgets_template' );

/**
 * Footer Bottom
 */
function hybridmag_footer_bottom_structure() {
    ?>
    
    <div class="hm-container hm-footer-bottom-content">

        <?php
            /**
             * Before Copyright hook
             */
            do_action( 'hybridmag_before_copyright' );
        ?>

        <div class="hm-footer-copyright">
            <?php 
                $hybridmag_copyright_text = get_theme_mod( 'hybridmag_footer_copyright_text', '' ); 

                if ( ! empty( $hybridmag_copyright_text ) ) {
                    echo wp_kses_post( $hybridmag_copyright_text );
                } else {
                    $hybridmag_site_link = '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" >' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
                    /* translators: 1: Year 2: Site URL. */
                    printf( esc_html__( 'Copyright &#169; %1$s %2$s.', 'hybridmag' ), date_i18n( 'Y' ), $hybridmag_site_link ); // WPCS: XSS OK.
                }		
            ?>
        </div><!-- .hm-footer-copyright -->

        <?php
            /**
             * After Copyright hook
             */
            do_action( 'hybridmag_after_copyright' );
        ?>

        </div><!-- .hm-container -->

    <?php
}
add_action( 'hybridmag_footer_bottom', 'hybridmag_footer_bottom_structure' );

function hybridmag_designer_credit() {
    $hybridmag_display_designer_credit = apply_filters( 'hybridmag_display_designer_credit', true );
    if ( true === $hybridmag_display_designer_credit ) : ?>
        <div class="hm-designer-credit">
            <?php
                /* translators: 1: WordPress 2: Theme Author. */
                $hybridmag_designer_credit = sprintf( esc_html__( 'Powered by %1$s and %2$s.', 'hybridmag' ),
                    '<a href="https://wordpress.org" target="_blank">WordPress</a>',
                    '<a href="https://themezhut.com/themes/hybridmag/" target="_blank">HybridMag</a>'
                ); 

                $hybridmag_designer_credit = apply_filters( 'hybridmag_designer_credit', $hybridmag_designer_credit );

                echo $hybridmag_designer_credit; // WPCS: XSS OK.
            ?>
        </div><!-- .hm-designer-credit" -->
    <?php
    endif;
}
add_action( 'hybridmag_after_copyright', 'hybridmag_designer_credit' );