<?php

/**
 * Calls the class on the post edit screen.
 */
function hybridmag_metaboxes_call() {
    new HybridMag_Metaboxes();
}
 
if ( is_admin() ) {
    add_action( 'load-post.php',     'hybridmag_metaboxes_call' );
    add_action( 'load-post-new.php', 'hybridmag_metaboxes_call' );
}
 
/**
 * Adds a Layout select meta box to posts and pages.
 */
class HybridMag_Metaboxes {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save'         ) );
    }
 
    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'post', 'page' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'hybridmag_layout_meta',
                esc_html__( 'Theme Page Options', 'hybridmag' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'side',
                'default'
            );
        }
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['hybridmag_theme_page_options_metabox_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = sanitize_key( $_POST['hybridmag_theme_page_options_metabox_nonce'] );
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'hybridmag_theme_page_options_metabox' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        if ( isset( $_POST['hybridmag_layout'] ) ) {
            
            // Sanitize the user input.
            $selected_layout = sanitize_text_field( wp_unslash( $_POST['hybridmag_layout'] ) );
    
            // Update the meta field.
            update_post_meta( $post_id, '_hybridmag_layout_meta', $selected_layout );

        }

        $hide_featured_image = isset( $_POST['hybridmag_hide_featured_image'] ) ? "true" : "false";
        update_post_meta( $post_id, '_hybridmag_hide_featured_image', $hide_featured_image );

        
        $hide_page_title = isset( $_POST['hybridmag_hide_page_title'] ) ? "true" : "false";
        update_post_meta( $post_id, '_hybridmag_hide_page_title', $hide_page_title );
        
    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'hybridmag_theme_page_options_metabox', 'hybridmag_theme_page_options_metabox_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $selected_layout = get_post_meta( $post->ID, '_hybridmag_layout_meta', true );
 
        // Display the form, using the current value.
        if( empty( $selected_layout) ) { $selected_layout = 'default-layout'; }

        $hide_featured_image = get_post_meta( $post->ID, '_hybridmag_hide_featured_image', true );
        $hide_page_title = get_post_meta( $post->ID, '_hybridmag_hide_page_title', true );
        ?>

        <label class="hm-metabox-select-label" for="hybridmag_layout">
            <?php esc_html_e( 'Sidebar Layout', 'hybridmag' ); ?>
        </label>
        <select name="hybridmag_layout" id="hybridmag_layout" class="hm-metabox-field">
            <option value="default-layout" <?php selected( $selected_layout, 'default-layout' ); ?>><?php esc_html_e( 'Default Layout', 'hybridmag' ); ?></option>
            <option value="right-sidebar" <?php selected( $selected_layout, 'right-sidebar' ); ?>><?php esc_html_e( 'Right Sidebar', 'hybridmag' ); ?></option>
            <option value="left-sidebar" <?php selected( $selected_layout, 'left-sidebar' ); ?>><?php esc_html_e( 'Left Sidebar', 'hybridmag' ); ?></option>
            <option value="no-sidebar" <?php selected( $selected_layout, 'no-sidebar' ); ?>><?php esc_html_e( 'No Sidebar Full Width', 'hybridmag' ); ?></option>
            <option value="center-content" <?php selected( $selected_layout, 'center-content' ); ?>><?php esc_html_e( 'No Sidebar Content Centered', 'hybridmag' ); ?></option>
        </select>

        <p>
            <label for="hybridmag_hide_featured_image" class="hm-metabox-label">
                <input type="checkbox" name="hybridmag_hide_featured_image" id="hybridmag_hide_featured_image" class="hm-metabox-field" value="true" <?php checked( $hide_featured_image, "true" ); ?>>
                <?php esc_html_e( 'Hide featured image', 'hybridmag' ); ?>
            </label>
        </p>

        <?php if ( $post->post_type == 'page' ) : ?>
            <p>
                <label for="hybridmag_hide_page_title" class="hm-metabox-label">
                    <input type="checkbox" name="hybridmag_hide_page_title" id="hybridmag_hide_page_title" class="hm-metabox-field" value="true" <?php checked( $hide_page_title, "true" ); ?>>
                    <?php esc_html_e( 'Hide page title', 'hybridmag' ); ?>
                </label>
            </p>
        <?php endif; ?>
        
        <?php
    }
}