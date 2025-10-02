<?php

/**
 * Displays latest or category wised posts list.
 *
 */

class HybridMag_Sidebar_Posts extends WP_Widget {

	/* Register Widget with WordPress*/
	function __construct() {
		parent::__construct(
			'hybridmag_sidebar_posts', // Base ID
			esc_html__( 'HybridMag: Sidebar Posts', 'hybridmag' ), // Name
			array( 'description' => esc_html__( 'Displays popular posts, latest posts or latest posts from a choosen cateogry. Use this widget in the main sidebars.', 'hybridmag' ), ) // Args
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */

	public function form( $instance ) {
		$defaults = array(
			'title'		            =>	esc_html__( 'Latest Posts', 'hybridmag' ),
            'show_popular_posts'    => false,
			'category'	            =>	'all',
			'number_posts'	        => 5,
			'ignore_sticky_posts'   => true,
            'pp_date_range'         =>  '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'hybridmag' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
        <p>
			<input type="checkbox" <?php checked( $instance['show_popular_posts'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('show_popular_posts'); ?>" name="<?php echo $this->get_field_name('show_popular_posts'); ?>" />
			<label for="<?php echo $this->get_field_id('show_popular_posts'); ?>"><?php esc_html_e( 'Display popular posts instead of latest posts.', 'hybridmag' ); ?></label>
		</p>
		<p>
			<label><?php esc_html_e( 'Select a post category', 'hybridmag' ); ?></label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name('category'), 'selected' => $instance['category'], 'show_option_all' => 'Show all posts' ) ); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_posts' ); ?>"><?php esc_html_e( 'Number of posts:', 'hybridmag' ); ?></label>
			<input type="number" id="<?php echo $this->get_field_id( 'number_posts' ); ?>" name="<?php echo $this->get_field_name( 'number_posts' );?>" value="<?php echo absint( $instance['number_posts'] ) ?>" size="3"/> 
		</p>
		<p>
			<input type="checkbox" <?php checked( $instance['ignore_sticky_posts'], true ) ?> class="checkbox" id="<?php echo $this->get_field_id('ignore_sticky_posts'); ?>" name="<?php echo $this->get_field_name('ignore_sticky_posts'); ?>" />
			<label for="<?php echo $this->get_field_id('ignore_sticky_posts'); ?>"><?php esc_html_e( 'Ignore sticky posts.', 'hybridmag' ); ?></label>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'pp_date_range' ); ?>"><?php esc_html_e( 'Enter the number of days to display popular posts:', 'hybridmag' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'pp_date_range' ); ?>" name="<?php echo $this->get_field_name( 'pp_date_range' ); ?>" type="text" value="<?php echo esc_attr( $instance['pp_date_range'] ); ?>">
		</p>

	<?php

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ]                = sanitize_text_field( $new_instance[ 'title' ] );	
        $instance[ 'show_popular_posts' ]   = isset( $new_instance['show_popular_posts'] ) ? (bool) $new_instance['show_popular_posts'] : false;
		$instance[ 'category' ]	            = absint( $new_instance[ 'category' ] );
		$instance[ 'number_posts' ]         = (int)$new_instance[ 'number_posts' ];
		$instance[ 'ignore_sticky_posts' ]  = isset( $new_instance['ignore_sticky_posts'] ) ? (bool) $new_instance['ignore_sticky_posts'] : false;
        $instance[ 'pp_date_range' ]        = ( ! empty( $new_instance[ 'pp_date_range' ] ) ) ? (int)( $new_instance[ 'pp_date_range' ] ) : '';
		return $instance;
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	
	public function widget( $args, $instance ) {
		extract($args);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';	
        $title = apply_filters( 'widget_title', $title , $instance, $this->id_base );
        $show_popular_posts = ( isset( $instance['show_popular_posts'] ) ) ? $instance['show_popular_posts'] : true;
		$category = ( ! empty( $instance['category'] ) ) ? absint( $instance['category'] ) : 0;
		$number_posts = ( ! empty( $instance['number_posts'] ) ) ? absint( $instance['number_posts'] ) : 5; 
		$ignore_sticky_posts = ( isset( $instance['ignore_sticky_posts'] ) ) ? $instance['ignore_sticky_posts'] : true;
        $pp_date_range = ( ! empty( $instance['pp_date_range'] ) ) ? absint( $instance['pp_date_range'] ) : '';

		// Query arguments to fetch posts.
        $posts_query_args = array(
            'cat' 					=>	$category,
            'posts_per_page' 		=>	$number_posts,
            'post_status'           =>  'publish',
            'no_found_rows' 		=>  true,
            'ignore_sticky_posts' 	=>  $ignore_sticky_posts
        );

        if ( true == $show_popular_posts ) {
            $posts_query_args[ 'orderby' ]    = 'comment_count';
            $posts_query_args[ 'order' ]      = 'desc';

            if( isset( $pp_date_range ) && ! empty( $pp_date_range ) ) {
                $posts_query_args[ 'date_query' ] = array(
                    array(
                        'after' => $pp_date_range . ' days ago'
                    )
                );
            }
        }

		$widget_posts = new WP_Query( $posts_query_args );	

		echo $before_widget; ?>
		<div class="hm-sidebar-posts">
		<?php
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
		?>

		
		<?php if( $widget_posts -> have_posts() ) : ?>	
			<?php while ( $widget_posts -> have_posts() ) : $widget_posts -> the_post(); ?>
					<div class="hms-post clearfix">
						<?php if ( has_post_thumbnail() ) { ?>
							<div class="hms-thumb">
								<a href="<?php the_permalink(); ?>" rel="bookmark" aria-label="<?php the_title_attribute(); ?>">	
									<?php the_post_thumbnail( 'thumbnail' ); ?>
								</a>
							</div>
						<?php } ?>
						<div class="hms-details">
							<?php the_title( sprintf( '<h3 class="hms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
							<div class="entry-meta"><?php echo hybridmag_posted_on(); ?></div>
						</div>
					</div><!-- .hms-post -->
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
        
        </div><!-- .hm-sidebar-posts -->


	<?php
		echo $after_widget;
	}

}

// Register single category posts widget
function hybridmag_register_sidebar_posts() {
    register_widget( 'HybridMag_Sidebar_Posts' );
}
add_action( 'widgets_init', 'hybridmag_register_sidebar_posts' );