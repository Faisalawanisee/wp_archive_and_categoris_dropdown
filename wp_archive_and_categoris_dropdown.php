<?php
/*
Plugin Name: Wp Archive And Categories
*/

/**
 * Adds User Get widget.
 */
class wp_archive_and_categories_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wp_archive_and_categories_widget', // Base ID
			__( 'Wp Archive And Categories', 'text_domain' ), // Name
			array( 'description' => __( 'Wp Archive And Categories', 'text_domain' ), ) // Args
		);
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
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		} 
		// Archives
		if ( ! empty( $instance['archvies'] ) ) { ?>
			<select name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
				  <option value=""><?php echo esc_attr( __( 'Select Month' ) ); ?></option> 
				  <?php wp_get_archives( array( 'type' => 'monthly', 'format' => 'option', 'show_post_count' => 1 ) ); ?>
			</select>
		<?php }
		// Archives
		if ( ! empty( $instance['categories'] ) ) { ?>
			<select name="event-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> 
				 <option value=""><?php echo esc_attr(__('Select Event')); ?></option> 
				 <?php 
				  $categories = get_categories(); 
				  foreach ($categories as $category) {
				  	$option = '<option value="'.get_category_link( $category->term_id ).'">';
					$option .= $category->cat_name;
					$option .= ' ('.$category->category_count.')';
					$option .= '</option>';
					echo $option;
				  }
				 ?>
			</select>
		<?php }
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		$archvies = $instance['archvies'];
		$categories = $instance['categories'];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<input <?php if($archvies){ echo 'checked '; } ?> class="widefat archvies-checkbox" id="<?php echo $this->get_field_id( 'archvies' ); ?>" name="<?php echo $this->get_field_name( 'archvies' ); ?>" type="checkbox" value="<?php echo $this->get_field_name( 'archvies' ); ?>">
			<label for="<?php echo $this->get_field_id( 'archvies' ); ?>"><?php _e( 'Archive' ); ?></label> 
		</p>
		<p>
			<input <?php if($categories){ echo 'checked '; } ?> class="widefat" id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ); ?>" type="checkbox" value="<?php echo $this->get_field_name( 'categories' ); ?>">
			<label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Categories' ); ?></label> 
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
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['archvies'] = $new_instance['archvies'];
		$instance['categories'] = $new_instance['categories'];
		return $instance;
	}

} // class wp_archive_and_categories_widget

// register wp_archive_and_categories_widget widget
function register_wp_archive_and_categories_widget() {	
    register_widget( 'wp_archive_and_categories_widget' );
}
add_action( 'widgets_init', 'register_wp_archive_and_categories_widget' );
