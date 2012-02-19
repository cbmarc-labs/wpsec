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

require_once STYLESHEETPATH . '/lib/wpsec-imageaccess.php';

if( !class_exists( 'WPSEC_Products' ) ) :

/**
 * Class WPSEC_Products
 *
 * WP Simple E-Commerce WPSEC_Products Class
 *
 */
class WPSEC_Products {

	// singleton instance
	private static $instance;

	// Post type
	private $post_name = 'products';
	private $post_plural = 'products';
	private $post_singular = 'product';

	// Taxomomy type
	private $tax_name = 'products_category';
	private $tax_plural = 'categories';
	private $tax_singular = 'category';

  // Default product image
	private $default_image = 'default_product.png';
  
  // Post type icon
  private $icon = 'store.png';

	/**
	 * PHP5 constructor
	 */
	public function __construct() {
		$this->default_image = get_stylesheet_directory_uri() . '/imgs/' . $this->default_image;
		$this->icon = get_stylesheet_directory_uri() . '/imgs/' . $this->icon;

		add_action( "init", array( &$this, 'init' ) );
		add_action( "admin_init", array( &$this, 'admin_init' ) );

		add_filter( "request", array( &$this, 'request' ) );
		add_filter( "manage_edit-{$this->post_name}_sortable_columns", array( &$this, 'manage_edit_sortable_columns' ) );
		add_filter( "manage_{$this->post_name}_posts_columns", array( &$this, 'manage_posts_columns' ) );

		add_filter( "post_thumbnail_html", array( &$this, 'post_thumbnail_html' ), 10, 5 );

		add_action( "manage_{$this->post_name}_posts_custom_column", array( &$this, 'manage_posts_custom_column' ) );
		
		add_action( 'admin_head', array( &$this, 'admin_head' ) );

		add_action( "save_post", array( &$this, 'save_post' ), 10, 2 );
	}

	/**
	 * getInstance method
	 */
	public static function getInstance() {
		if(!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Callback init
	 */
	function init() {
		$post_name = ucwords( __( $this->post_plural ) );
		$post_singular = ucwords( __( $this->post_singular ) );
		register_post_type( $this->post_name, array(
      'labels' => array(
        'name'=> _x( $post_name, 'post type general name' ),
        'singular_name' => _x( $post_singular, 'post type singular name' ),
        'add_new' => _x( 'Add New', $post_singular ),
        'add_new_item' => __( 'Add New ' . $post_singular ),
        'edit_item' => __( 'Edit ' . $post_singular ),
        'new_item' => __( 'New ' . $post_singular ),
        'all_items' => __( 'All ' . $post_name ),
        'view_item' => __( 'View ' . $post_singular ),
        'search_items' => __( 'Search ' . $post_name ),
        'not_found' =>  __( 'No ' . $post_name . ' found' ),
        'not_found_in_trash' => __('No ' . $post_name . ' found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => $post_name
		),
      'public' => true,
      'show_ui' => true,
      'hierarchical' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail' )
		) );

		$tax_name = ucwords( __( $this->tax_plural ) );
		$tax_name_singular = ucwords( __( $this->tax_singular ) );
		register_taxonomy( $this->tax_name, $this->post_name, array(
      'labels' => array(
        'name'=> _x( $tax_name, 'taxonomy general name' ),
        'singular_name' => _x( $tax_name_singular, 'taxonomy singular name' ),
        'add_new' => _x( 'Add New', $tax_name_singular ),
        'add_new_item' => __( 'Add New ' . $tax_name_singular ),
        'edit_item' => __( 'Edit ' . $tax_name_singular ),
        'new_item' => __( 'New ' . $tax_name_singular ),
        'all_items' => __( 'All ' . $tax_name ),
        'view_item' => __( 'View ' . $tax_name_singular ),
        'search_items' => __( 'Search ' . $tax_name ),
        'not_found' =>  __( 'No ' . $tax_name . ' found' ),
        'not_found_in_trash' => __('No ' . $tax_name . ' found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => $tax_name
		),
      'label' => __( $tax_name ),
      'public' => true,
      'has_archive' => true,
      'show_ui' => true,
      'hierarchical' => true,
		) );
	}

	/**
	 * Callback admin_init
	 */
	function admin_init() {
		add_meta_box( $this->post_name, __( 'Products Options' ), array( &$this, 'addMetaContent' ), $this->post_name );
	}

	/**
	 * Callback price
	 * @param string $post
	 * @param string $metabox
	 */
	function addMetaContent( $post, $metabox ) {
		global $wpsec_imageaccess;
		$price = get_post_meta( $post->ID, '_product_price', true );
		?>

		<h4>Price</h4>
		<div><label>Price (e.g.: 0.00) :</label><input type="text"
			name="_product_price" value="<?php _e( $price ) ?>" />&nbsp;&euro;</div>
		
		<h4>Images</h4>
		<span class="description"><?php _e( 'Click on image to remove.' ) ?></span>
		<br>
		<br>

		<?php $wpsec_imageaccess->the_images( '_product_image' ); ?>

		<?php
	}

	/**
	 * Callback post_thumbnail_html
	 * param string $html
	 * param string $post_id
	 * param string $post_image_id
	 */
	function post_thumbnail_html( $html, $post_id, $post_image_id, $size, $attr ) {
		if( '' == $html ) {//!has_post_thumbnail() ) {
			$image_src = $this->default_image;
			$title = esc_attr( get_post_field( 'post_title', $post_id ) );
			$dimensions = 'width="' . $size[0] . '" height="' . $size[1] . '" ';

			$html = '<img ' . $dimensions . 'src="' . $image_src . '" alt="' . $title . '" title="' . $title . '">';
		}
			
		return $html;
	}

	/**
	 * Callback request
	 * param string $vars
	 */
	function request( $vars ) {
		if ( isset( $vars[ 'orderby' ] ) && 'price' == $vars[ 'orderby' ] ) {
			$vars = array_merge( $vars, array(
        'meta_key' => '_product_price',
        'orderby' => 'meta_value_num'
        ) );
		} elseif( !isset( $_GET[ 'orderby' ] ) ) {
			$vars = array_merge( $vars, array(
        'order' => 'desc',
        'orderby' => 'date'
        ) );
		}

		return $vars;
	}

	/**
	 * Callback manage_editproduct_sortable_columns
	 * param string $columns
	 */
	function manage_edit_sortable_columns( $cols ) {
		$cols[ 'products_price' ] = 'price';
			
		return $cols;
	}

	/**
	 * Callback manage_products_posts_columns
	 * param string $columns
	 */
	function manage_posts_columns( $cols ) {
		$col_thumbnail = array( 'products_thumbnail' => 'Thumbnail' );
		$col_price = array( 'products_price' => 'Price' );
		$col_productcategory = array( $this->tax_name => ucwords( __( $this->tax_plural ) ) );

		$cols = array_slice( $cols, 0, 1, true ) + $col_thumbnail + array_slice( $cols, 1, NULL, true );
		$cols = array_slice( $cols, 0, 3, true ) + $col_price + array_slice( $cols, 3, NULL, true );
		$cols = array_slice( $cols, 0, 4, true ) + $col_productcategory + array_slice( $cols, 4, NULL, true );

		return $cols;
	}

	/**
	 * Callback manage_products_posts_custom_column
	 * param string $column
	 */
	function manage_posts_custom_column( $col ) {
		global $post;

		switch($col) {
			case 'products_thumbnail':
				echo '<a href="' . admin_url( "post.php?post=" . $post->ID . "&action=edit" ) . '">';
				if( has_post_thumbnail() )
				echo get_the_post_thumbnail( $post->ID, array( 64, 64 ) );
				else
				echo '<img src="' . $this->default_image . '" width="64" />';
				echo '</a>';
				break;

			case 'products_price' :
				echo get_post_meta( $post->ID , '_product_price' , true );
				break;

			case $this->tax_name :
				$post_cats = wp_get_object_terms( $post->ID, $this->tax_name );
				$i = 0;
				foreach( $post_cats as $cat ){
					if ( $i ++ > 0 ) echo "&nbsp;|&nbsp;";
					echo '<a href="?post_type=' . $this->post_name . '&amp;' . $this->tax_name . '='.$cat->slug.'">'.$cat->name.'</a>';
				}

				break;
		}
	}
	
	function admin_head() {
    ?>
    
    <style type="text/css" media="screen">
      #menu-posts-products .wp-menu-image {
        background: url(<?php echo $this->icon ?>) no-repeat 6px -17px !important;
      }
      
      #menu-posts-products:hover .wp-menu-image, #menu-posts-products.wp-has-current-submenu .wp-menu-image {
        background-position:6px 7px!important;
      }
    </style>
    
    <?php 
  }

	/**
	 * Callback save_post
	 * @param string $post_id
	 */
	function save_post( $post_ID, $post ) {
		// to prevent metadata or custom fields from disappearing...
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_ID;

		if( get_post_type( $post_ID ) !== $this->post_name || !current_user_can( 'edit_post', $post_ID ) )
		return $post_ID;

		if( isset( $_POST[ '_product_price' ] ) ) {
			if ( $_POST[ '_product_price' ] <> '' ) {
				$price = number_format( (float) str_replace( ',', '', $_POST[ '_product_price' ] ), 2, '.', '' );

				update_post_meta( $post_ID, '_product_price', $price );
			} else {
				update_post_meta( $post_ID, '_product_price', '0.00' );
			}
		}

		update_post_meta( $post_ID, '_product_image', $_POST[ '_product_image'] );

		return $post_ID;
	}

	/**
	 * the_images Method
	 * @param unknown_type $width
	 */
	public function the_images( $width = '64' ) {
		global $post;

		$images = get_post_meta($post->ID, '_product_image', false );

		if( is_array( $images[0] ) ) {
			foreach ( $images[0] as $key => $value ) {
				$image_attributes = wp_get_attachment_image_src( $value, array( $width, $width ) );
				$style = 'width:' . $width . 'px;border:1px solid #ddd;padding:5px;margin:5px;';

				echo '<img src="' . $image_attributes[0] . '" style="' . $style . '">';
			}
		}
	}

} // end class WPSEC_Product

endif;

/**
 * Create instance
 */
global $wpsec_products;
if( class_exists( 'WPSEC_Products' ) && !$wpsec_products ) {
	$wpsec_products = WPSEC_Products::getInstance();
}

?>