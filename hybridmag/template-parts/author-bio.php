<?php
/**
 * The template for displaying author info.
 * 
 * @package HybridMag
 */

if ( false === get_theme_mod( 'hybridmag_show_author_bio', true ) ) {
    return;
}

$hybridmag_author_avatar = get_avatar( get_the_author_meta( 'ID' ), 80 );
$hybridmag_posts_author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
?>

<div class="hm-author-bio">
    <?php if ( $hybridmag_author_avatar ) : ?>
        <div class="hm-author-image">
            <a href="<?php echo esc_url( $hybridmag_posts_author_url ); ?>" rel="author">
                <?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $hybridmag_author_avatar;
                ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="hm-author-content">
        <div class="hm-author-name"><a href="<?php echo esc_url( $hybridmag_posts_author_url ); ?>" rel="author"><?php echo esc_html( get_the_author() );?></a></div>
        <div class="hm-author-description"><?php echo wp_kses_post( wpautop( get_the_author_meta( 'description' ) ) ); ?></div>
        <a class="hm-author-link" href="<?php echo esc_url( $hybridmag_posts_author_url ); ?>" rel="author">
            <?php
                /* translators: %s is the current author's name. */
                printf( esc_html__( 'More by %s', 'hybridmag' ), esc_html( get_the_author() ) );
            ?>
        </a>
    </div>
</div>