<?php

if ( false === get_theme_mod( 'hybridmag_show_sfp', false ) ) {
    return;
}

$hybridmag_sfp_source = get_theme_mod( 'hybridmag_sfp_source', 'latest' );
$hybridmag_ignore_sticky_posts_sfp = get_theme_mod( 'hybridmag_ignore_sticky_posts_sfp', true );
$hybridmag_sfp_args = array(
    'posts_per_page'        => 4,
    'ignore_sticky_posts'   => $hybridmag_ignore_sticky_posts_sfp
);
if ( 'category' === $hybridmag_sfp_source ) {
    $hybridmag_sfp_args['cat'] = get_theme_mod( 'hybridmag_sfp_category', '' );
} elseif ( 'tag' === $hybridmag_sfp_source ) {
    $hybridmag_sfp_args['tag'] = get_theme_mod( 'hybridmag_sfp_tag', '' );
}

$hybridmag_sf_posts = new WP_Query( $hybridmag_sfp_args );

?>

<div class="hm-fp-small-top">
    <?php if ( $hybridmag_sf_posts->have_posts() ) : 
            while ( $hybridmag_sf_posts->have_posts() ) : $hybridmag_sf_posts->the_post(); ?>
                <div class="hm-fp-small">
                    <div class="post-thumbnail"><?php the_post_thumbnail( 'hybridmag-thumbnail' ); ?></div>
                    <?php the_title('<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>'); ?>
                </div>
    <?php 
            endwhile;
        endif; 
    ?>
</div>