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

if( !class_exists( 'WPSEC_ProductCategoriesWidget' ) ) :

class WPSEC_ProductCategoriesWidget extends WP_Widget {
	
	function WPSEC_ProductCategoriesWidget() {
		$widget_ops = array(
			'description' => 'A list or dropdown of product categories'
		);
		
		$this->WP_Widget( 'WPSEC_ProductCategoriesWidget', 'Product Categories', $widget_ops );
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
		wp_tag_cloud( 'taxonomy=products_category&number=0&format=list&smallest=12&largest=12&unit=px' ); 
		//*********************************
		
		echo $after_widget;
	} //ending function widget
	
} // end class WPSEC_ProductCategoriesWidget

endif;

register_widget( 'WPSEC_ProductCategoriesWidget' );
