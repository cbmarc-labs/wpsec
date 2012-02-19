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

if( !class_exists( 'WPSEC_ImageAccess' ) ) :

/**
 * ImageAccess Class
 *
 * WP Simple E-Commerce ImageAccess Class
 */
class WPSEC_ImageAccess {

	/**
	 * $instance Field
	 *
	 * @var unknown_type
	 */
	private static $instance;

	/**
	 * $query_var Field
	 *
	 * @var string
	 */
	private $query_var = 'imageaccess';
	
	/**
	 * $img_container Field
	 *
	 * @var unknown_type
	 */
	private $img_container = 'imageaccess';
	
	/**
	 * $meta_key Field
	 *
	 * @var unknown_type
	 */
	private $meta_key = '_imageaccess';
	
	/**
	 * $img_width Field
	 *
	 * @var unknown_type
	 */
	private $img_width = 64;
	
	/**
	 * $img_height Field
	 *
	 * @var unknown_type
	 */
	private $img_height = 64;

	/**
	 * PHP5 Constructor
	 */
	function __construct() {
		if( current_user_can( 'manage_options' ) )
			add_action( 'init', array( &$this, 'init' ) );
	}

	/**
	 * getInstance Method
	 *
	 * @return unknown_type
	 */
	public static function getInstance() {
		if(!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/**
	 * init Method
	 */
	function init() {
		add_filter( 'query_vars', array( &$this, 'query_vars' ) );
		add_action( 'parse_request', array( &$this, 'parse_request' ) );
	}

	/**
	 * query_vars method
	 * @param string $vars
	 */
	function query_vars( $vars ) {
		$vars[] = $this->query_var;

		return $vars;
	}

	/**
	 * parse_request Method
	 * @param unknown_type $wp
	 */
	function parse_request( $wp ) {
		if( !array_key_exists( $this->query_var, $wp->query_vars ) )
			return;
		
		wp_deregister_script( 'admin-bar' );
    wp_deregister_style( 'admin-bar' );
    remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
		wp_enqueue_script( 'jquery' );
		$meta_key = $_GET[ $this->query_var ];
		
		?>
		
		<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"  
    		"http://www.w3.org/TR/html4/strict.dtd">  
		<html>
		<head>
			<meta http-equiv="content-type" content="text/html; charset=utf-8">
			<title>imageaccess</title> 
			
			<?php $this->the_style(); ?>
			
			<script type="text/javascript">
				function imageaccess_click( id, img_src ) {
					jQuery(function($) {
            $("#image_added").stop(true, true).fadeOut();
            $("#image_added").show();
            $("#image_added").fadeOut( "slow", function () {} );
					
						parent_id = $("#<?php echo $this->img_container ?>", parent.document.body);
	
						html = '<a id="<?php echo $meta_key ?>-' + id + '" href="JavaScript:imageaccess_remove(' + id + ')">';
						html += '<input name="<?php echo $meta_key ?>[]" value="' + id + '">';
						html += '<img src="' + img_src + '" width="<?php echo $this->img_width ?>" height="<?php echo $this->img_height ?>">';
						html += '</a>';
						
						parent_id.append( html );
					});
				}
			</script>
		
			<?php wp_head(); ?>
		</head>
		<body>		
		<h4 style="color:#888;">Click on image to insert.</h4>
		
		<?php 
	  
		$query_images_args = array(
		 'post_type' => 'attachment',
		 'post_mime_type' =>'image',
		 'post_status' => 'inherit',
		 'posts_per_page' => -1,
		 );

		 $query_images = new WP_Query( $query_images_args );
		 echo '<div id="' . $this->img_container . '">';
		 foreach( $query_images->posts as $image) {
		 	$image_attributes = wp_get_attachment_image_src( $image->ID, array( $this->img_width, $this->img_height ) );
		 	
		 	echo '<a href="JavaScript:imageaccess_click(' . $image->ID . ', \'' . $image_attributes[0] . '\')">';
		 	echo '<img src="' . $image_attributes[0] . '" width="' . $this->img_width . '" height="' . $this->img_height . '">';
			echo '</a>';
			}
		echo '</div>';
	  
		?>
		
		<div id="image_added" style="display:none;text-align:center;padding:10px;font-size:24px;">Image added.</div>
		
		</body>
		
		<?php wp_footer(); ?>
		
		</html>
		
		<?php
	  	
		exit;
	}
	
	/**
	 * theStyle Method
	 */
	private function the_style() {
		echo '
		<style type="text/css">
			#' . $this->img_container . ' {
				border:1px solid #ddd;
				padding:10px;
				border-radius: 15px;
			}
			
			#' . $this->img_container . ' input {
				display:none;
			}
			
			#' . $this->img_container . ' img {
				padding:10px;
			}
		</style>
		';
	}

	/**
	 * getButton Method
	 *
	 * @return string
	 */
	private function the_button() {
		$url = site_url() . '/?' . $this->query_var . '=' . $this->meta_key . '&TB_iframe=1';
		
		echo '<div>';
		echo '<a href="' . $url . '" class="button thickbox">';
		echo __( 'Add Images' );
		echo '</a>';
	}
	
	/**
	 * getImages Method
	 * @param unknown_type $post_id
	 */
	public function the_images( $meta_key = '_imageaccess' ) {
		global $post;
		
		$this->meta_key = $meta_key;
		
		$images = get_post_meta( $post->ID, $this->meta_key, true );
		
		$this->the_style();
		
		echo '<script type="text/javascript">';
		echo 'function imageaccess_remove( id ) {';
		echo 'jQuery(function($) {';
		echo 'if(confirm( "' . __( 'Remove image ?' ) . '" ))';
		echo '$( "#' . $this->meta_key . '-" + id ).remove();';
		echo '});';
		echo '}';
		echo '</script>';
		
		echo '<div id="' . $this->img_container . '">';
		if( $images && is_array( $images ) ) {
			foreach( $images as $id ) {
				$image_attributes = wp_get_attachment_image_src( $id, array( $this->img_width, $this->img_height ) );
				
				echo '<a id="' . $this->meta_key . '-' . $id . '" href="JavaScript:imageaccess_remove(' . $id . ')">';
				echo '<input name="' . $this->meta_key . '[]" value="' . $id . '">';
				echo '<img src="' . $image_attributes[0] . '" width="' . $this->img_width . '" height="' . $this->img_height . '">';
				echo '</a>';
			}
		}
		echo '</div>';
		
		echo '<br>';
		
		$this->the_button();
	}

} // end ImageAccess Class

endif;

/**
 * Create instance
 */
global $wpsec_imageaccess;
if( class_exists( 'WPSEC_ImageAccess' ) && !$wpsec_imageaccess ) {
	$wpsec_imageaccess = WPSEC_ImageAccess::getInstance();
}