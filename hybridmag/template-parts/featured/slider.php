<?php
/**
 * Displays featured content on the Blog page.
 */
    
do_action( 'hybridmag_before_featured_content' ); 

/**
 * Posts query for slider.
 */
$hybridmag_slider_source = get_theme_mod( 'hybridmag_slider_posts_source', 'latest' );
$hybridmag_ignore_sticky_posts_slider = get_theme_mod( 'hybridmag_ignore_sticky_posts_slider', false );
$hybridmag_slider_args = array(
    'posts_per_page'        => get_theme_mod( 'hybridmag_slider_number_posts', 5 ),
    'ignore_sticky_posts'   => $hybridmag_ignore_sticky_posts_slider
);
if ( 'category' === $hybridmag_slider_source ) {
    $hybridmag_slider_args['cat'] = get_theme_mod( 'hybridmag_slider_posts_category', '' );
} elseif ( 'tag' === $hybridmag_slider_source ) {
    $hybridmag_slider_args['tag'] = get_theme_mod( 'hybridmag_slider_posts_tag', '' );
} elseif ( 'sticky' === $hybridmag_slider_source ) {
    $hybridmag_slider_args[ 'post__in' ] = get_option( 'sticky_posts' );
    $hybridmag_slider_args[ 'ignore_sticky_posts' ] = 1;
}

$hybridmag_slider_posts = new WP_Query( $hybridmag_slider_args );

/**
 * Posts query for highlighted posts.
 */
$hybridmag_fps_source = get_theme_mod( 'hybridmag_featured_posts_source', 'latest' );
$hybridmag_fps_args = array(
    'posts_per_page'        => 2,
    'ignore_sticky_posts'   => 1
);

if ( 'category' === $hybridmag_fps_source ) {
    $hybridmag_fps_args['cat'] = get_theme_mod( 'hybridmag_featured_posts_category', '' );
} elseif ( 'tag' === $hybridmag_fps_source ) {
    $hybridmag_fps_args['tag'] = get_theme_mod( 'hybridmag_featured_posts_tag', '' );
} elseif ( 'sticky' === $hybridmag_fps_source ) {
    $hybridmag_fps_args = array(
        'post__in'              => get_option( 'sticky_posts' )
    );
}

$hybridmag_highlighted_posts = new WP_Query( $hybridmag_fps_args );

?>

<div class="hm-container">

<div class="hm-fp1">

    <div class="hm-fp1-left">

    <?php if ( $hybridmag_slider_posts->have_posts() ) : ?>

        <div class="hm-swiper hm-slider">

            <div class="hm-swiper-wrapper">

                <?php 

                    $hm_slide_count = 1;
                    
                    while( $hybridmag_slider_posts->have_posts() ) : $hybridmag_slider_posts->the_post(); 
                    
                    $hm_lazy_loading = 'lazy';
                    
                    ?>

                        <div class="hm-swiper-slide">

                            <div class="hm-slide-holder">
                                <div class="hm-slide-image">
                                    <?php 
                                        if ( $hm_slide_count == 1 ) {
                                            $hm_lazy_loading = false;
                                        }
                                        if ( has_post_thumbnail() ) {
                                            the_post_thumbnail( 'hybridmag-featured-image', array( 'loading' => $hm_lazy_loading ) );
                                        } else {
                                            if ( false === get_theme_mod( 'hybridmag_remove_placeholder', false ) ) {
                                                $featured_image_url = get_template_directory_uri() . '/assets/images/default.jpg'; ?>
                                                <img src="<?php echo esc_url( $featured_image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                                                <?php
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="hm-fp-overlay">
                                    <a class="hm-fp-link-overlay" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" aria-label="<?php the_title_attribute(); ?>" rel="bookmark"></a>
                                </div>
                                <div class="hm-slide-content">
                                    <div class="hm-slider-details-container hmfpwmeta">
                                        <h3 class="hm-slider-title"><?php the_title(); ?></h3>
                                    </div><!-- .hm-slider-details-container -->
                                </div><!-- .hm-slide-content -->
                            </div><!--.hm-slide-holder-->
                            
                        </div><!-- .hm-slider-container -->
                    
                <?php 
                    $hm_slide_count++;
                    endwhile; 
                
                ?>

                <?php wp_reset_postdata(); ?>

            </div><!-- .hm-swiper-wrapper -->
            <div class="hm-swiper-pagination"></div>

            <div class="hm-swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 320 512"><path d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"/></svg></div>
            <div class="hm-swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"  viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg></div>

        </div><!-- .hm-slider -->

    <?php else : ?>
        <div>
            <?php esc_html_e( 'No posts found', 'hybridmag' ); ?>
        </div>
    <?php endif; ?>

    </div><!-- .hm-fp1-left" -->

    <div class="hm-fp1-right">

    <?php if ( $hybridmag_highlighted_posts->have_posts() ) : ?>

        <div class="hm-highlighted-posts">

            <?php 
                while( $hybridmag_highlighted_posts->have_posts() ) : $hybridmag_highlighted_posts->the_post(); ?>

                    <div class="hm-highlighted-post">
                        <div class="hmhp-inner">
                            <div class="hmhp-thumb">
                                <?php if ( has_post_thumbnail() ) { ?>
                                    <?php the_post_thumbnail( 'hybridmag-archive-image', array( 'class' => 'hm-fpw-img') ); ?>
                                <?php } else { 
                                    if ( false === get_theme_mod( 'hybridmag_remove_placeholder', false ) ) {
                                        $featured_image_url = get_template_directory_uri() . '/assets/images/default.jpg'; ?>
                                        <img src="<?php echo esc_url( $featured_image_url ); ?>">
                                        <?php
                                    }
                                } ?>
                            </div>
                            <div class="hm-fp-overlay">
                                <a class="hm-fp-link-overlay" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" aria-label="<?php the_title_attribute(); ?>" rel="bookmark"></a>
                            </div>
                            <div class="hmhp-content">
                                <div class="hmhp-details-container">
                                    <h3 class="hmhp-title">
                                        <?php the_title(); ?>
                                    </h3>
                                </div><!-- .hmhp-details-container -->
                            </div><!-- .hmhp-content -->
                        </div><!-- .hmhp-inner -->
                    </div><!-- .hm-highlighted-post -->

            <?php endwhile; ?>

            <?php wp_reset_postdata(); ?>

        </div><!-- .hm-highlighted-posts -->

    <?php else : ?>
        <div>
            <?php esc_html_e( 'No posts found', 'hybridmag' ); ?>
        </div>
    <?php endif; ?>

    </div><!-- .hm-fp1-right -->

</div><!-- .hm-fp1" -->

</div><!-- .hm-container -->

<?php do_action( 'hybridmag_after_featured_content' ); ?>