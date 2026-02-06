<?php
/**
 * Block styles for Magazine Companion Blocks.
 */

if ( function_exists( 'register_block_style' ) ) {
    function hybridmag_register_block_styles() {

        $blocks_array = array(
            'bnm-blocks/posts-ultra',
            'bnm-blocks/post-block-1',
            'bnm-blocks/post-block-2',
            'bnm-blocks/featured-posts-1',
            'bnm-blocks/featured-posts-2',
            'bnm-blocks/posts-slider'
        );

        foreach ( $blocks_array as $block ) {
            register_block_style(
                $block,
                array(
                    'name'         => 'boxed',
                    'label'        => __( 'Boxed', 'hybridmag' )
                )
            );
        }

        register_block_style(
			'core/heading',
			array(
				'name'  => 'hybridmag-heading-s1',
				'label' => esc_html__( 'Border Bottom', 'hybridmag' ),
			)
		);

    }
    add_action( 'init', 'hybridmag_register_block_styles' );
}

