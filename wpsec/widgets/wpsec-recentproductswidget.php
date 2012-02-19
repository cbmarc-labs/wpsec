<?php

/**
 * WP Simple E-Commerce
 *
 * A simple project that provides a WordPress E-Commerce paypal
 *
 * @package WPSEC
 * @author Marc Costa <cbmarc@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

if( !class_exists( 'WPSEC_RecentProductsWidget' ) ) :

class WPSEC_RecentProductsWidget extends WP_Widget {
	
	function WPSEC_RecentProductsWidget() {
		$widget_ops = array(
			'description' => 'The most recent procducts on your store'
		);
		
		$this->WP_Widget( 'WPSEC_RecentProductsWidget', 'Recent Products', $widget_ops );
	} //ending registration function
	
	function form( $instance ) {
		$title = esc_attr( $instance[ 'title' ] );
	?>
	
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo attribute_escape( $title ); ?>" />
	</label>
	</p>

	<?php
	} //ending form creation
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );

		return $instance;
	} //ending update
	
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		echo $before_widget;
		
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		if( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		
		//*********************************
		query_posts( array('post_type' => 'products', 'showposts' => 5 ) );
		if (have_posts()) : 
			echo '<ul>';
			while (have_posts()) : the_post(); 
				echo '<li style="list-style-type:none;margin:5px;">';
				echo '<a href="'. get_permalink() . '">';
				echo the_post_thumbnail( array( 32, 32 ) );
				echo '&nbsp;&nbsp;' . get_the_title();
				echo '</a>';
				echo '</li>';	
		 
			endwhile;
			echo "</ul>";
		endif; 
		wp_reset_query();
		//*********************************
		
		echo $after_widget;
	} //ending function widget
	
} // end class WPSEC_RecentProductsWidget

endif;

register_widget( 'WPSEC_RecentProductsWidget' );
