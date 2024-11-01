<?php
/**
 * Section Index Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class SectionIndex_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function SectionIndex_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sectionindex-widget', 'description' => __('A list of the various sections of the page', 'wp-section-index') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'sectionindex-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'sectionindex-widget', __('Section Index', 'wp-section-index'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		global $wpsi, $post;
		
		$disable_on_post = get_post_meta ( $post->ID, 'wpsi_disable_index', true);
		
		if ( $disable_on_post == false) {
		
			if ( count($wpsi->get_anchors()) > 0 ) {
				
				if ( ( is_page() && $wpsi->use_pages == 1 ) || ( is_single() && $wpsi->use_posts == 1 ) ) {
				
				/* Our variables from the widget settings. */
				$title = apply_filters('widget_title', $instance['title'] );
		
				/* Before widget (defined by themes). */
				echo $before_widget;
		
				/* Display the widget title if one was input (before and after defined by themes). */
				if ( $title ) {
					echo $before_title . $title . $after_title;
				}
				
				/* Widget content. */
				
				$wpsi->create_anchor_list();
		
				/* After widget (defined by themes). */
				echo $after_widget;
				
				} // End IF Statement
			
			} // End IF Statement
		
		} // End IF Statement
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Section Index', 'wp-section-index') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wp-section-index'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

	<?php
	}
}
?>