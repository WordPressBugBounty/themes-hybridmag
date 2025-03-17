<?php

/**
 * Displays posts on tabs.
 */

if ( false == get_theme_mod( 'hybridmag_display_tabbed_posts', true ) ) {
    return;
}

$hybridmag_tab_posts_cat_list = array();
for ( $i = 1; $i <= 5; $i++ ) {
    $hybridmag_tab_posts_cat_list[$i] = get_theme_mod( 'hybridmag_tab_posts'. $i .'_category', '' );
}

?>

<div class="hm-container">

<div class="hm-featured-tabs">

    <ul class="hm-tab-nav">
        <?php 
            foreach ( $hybridmag_tab_posts_cat_list as $index => $cat ) { 

                if ( ! empty( $cat ) ) {
                    $hybridmag_cat_name = get_cat_name( $cat );
                } else {
                    $hybridmag_cat_name = esc_html__( 'Latest', 'hybridmag' );
                }

                ?>

                <?php if ( 1 === $index || ! empty( $cat ) ) : ?>
                    <li class="hm-tab">
                        <a class="hm-tab-anchor" aria-label="tab-posts-<?php echo esc_attr( $index ); ?>" href="#hm-tab-posts-<?php echo esc_attr( $index ); ?>">
                            <?php echo esc_html( $hybridmag_cat_name ); ?>
                        </a>
                    </li>
                <?php endif; ?>

        <?php } ?>
    </ul>

    <div class="tab-content clearfix">
        
        <?php
        foreach ( $hybridmag_tab_posts_cat_list as $index => $cat ) {

            $hybridmag_tab_posts_args = array(
                'posts_per_page' => 4,
                'cat' => $cat,
                'ignore_sticky_posts' => true
            );

            $hybridmag_tab_posts = new WP_Query( $hybridmag_tab_posts_args ); ?>

            <?php if ( 1 === $index || ! empty( $cat ) ) : ?>

            <div id="hm-tab-posts-<?php echo esc_attr( $index ); ?>">

                <div class="hm-tab-posts-wrapper">
            
                <?php if ( $hybridmag_tab_posts->have_posts() ) : ?>

                    <?php while( $hybridmag_tab_posts->have_posts() ) : 
                        
                        $hybridmag_tab_posts->the_post();
                    
                    ?>

                        <div class="hm-tab-post-card">
                            <div class="hm-tab-post-img">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'hybridmag-archive-image' ); ?>
                                </a>
                            </div>
                            <div class="hm-tab-post-details">
                                <?php the_title( '<h3 class="hm-tab-post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
                                <div class="entry-meta"><span class="posted-on"><?php echo hybridmag_posted_on(); ?></span></div>
                                <?php //the_excerpt(); ?>        
                            </div>                                
                        </div>

                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                    
                <?php else : ?>
                    <div>
                        <?php esc_html_e( 'No posts found', 'hybridmag' ); ?>
                    </div>
                <?php endif; ?>
                </div>

                <?php
                    $view_all_link = hybridmag_get_viewall_link( $cat );
                    $view_all_text = get_theme_mod( 'hybridmag_tabbed_view_all_text', 'View More' );

                    if ( ! empty( $view_all_link ) && ! empty( $view_all_text ) ) {
                        echo '<span class="hm-tabs-view-more">';
                        echo '<a href="'. esc_url( $view_all_link ) .'">' . esc_html( $view_all_text ) . '</a>';
                        echo '</span>';
                    }
                ?>  

            </div><!-- #hm-tab-posts-<?php echo $i ?> -->

        <?php endif; ?>

        <?php } // End for loop ?>

    </div><!-- .tab-content -->		

</div><!-- .hm-featured-tabs -->

</div><!-- .hm-container -->
