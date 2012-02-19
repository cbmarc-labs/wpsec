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

if( !class_exists( 'WPSEC_Minicart' ) ) :

/**
 * Class WPSEC_Minicart
 *
 * WP Simple E-Commerce WPSEC_Minicart Class
 *
 */
class WPSEC_Minicart {

	/**
	 * $instance Field
	 *
	 * @var unknown_type
	 */
	private static $instance;


	/**
	 * $query_var Field
	 *
	 * @var unknown_type
	 */
	private $query_var = 'checkout';

	/**
	 * $action Field
	 *
	 * @var unknown_type
	 */
	private $action = 'checkout-submit';

	/**
	 * PHP5 Constructor
	 */
	function __construct() {
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
	 * Callback init
	 */
	function init() {
		add_filter( 'query_vars', array( &$this, 'query_vars' ) );
		add_action( 'parse_request', array( &$this, 'parse_request' ) );

		// http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/
		add_action( 'wp_ajax_nopriv_checkout-submit', array( &$this, 'checkout_submit' ) );
		add_action( 'wp_ajax_checkout-submit', array( &$this, 'checkout_submit' ) );

		if( !is_admin() ) {
			wp_enqueue_script( 'minicart', get_stylesheet_directory_uri() . '/js/minicart/minicart.js', false, null, true );
			wp_enqueue_script( 'minicart-init', get_stylesheet_directory_uri() . '/js/minicart-init.js', false, null, true );

			wp_localize_script( 'minicart-init', 'Minicart', array(
        'paypalURL' => get_bloginfo( 'wpurl' ) . '/?checkout',
        'button' => __( 'Checkout' ),
        'subtotal' => __( 'Subtotal: ' ),
        'discount' => __( 'Discount' ),
        'shipping' => __( 'Transport costs not included.' )
			) );
		}
	}

	/**
	 * wp_ajax_checkout_submit method
	 */
	function checkout_submit() {
		$success = false;
		$nonce = $_POST[ 'nonce' ];

		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if( wp_verify_nonce( $nonce, 'checkout-form-nonce' ) && $_POST[ 'cmd' ] === '_cart' ) {
			$data = ucwords( $this->query_var ) . "\r\n\r\n";
			foreach( $_POST as $key => $val )
			$data .= $key . " => " . $val . "\r\n";

			// Insert into wordpress post type
			$post = array(
				'post_title'   => 'Checkout',
				'post_content' => $data,
				'post_status'  => 'private',
				'post_type'    => 'notifications',
			);

			// Insert the post into the database
			$post_id = wp_insert_post( $post );

			// generate the response
			$success = true;
		}

		$response = json_encode( array( 'success' => $success ) );

		// response output
		header( "Content-Type: application/json" );
		echo $response;

		// IMPORTANT: don't forget to "exit"
		exit;
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
	 * parse_request method
	 * @param string $wp
	 */
	function parse_request( $wp ) {
	// only process requests with "checkout", e.g.: http://example.com/wordpress/?checkout
	if( array_key_exists( $this->query_var, $wp->query_vars ) && isset( $_POST[ 'cmd' ] ) ) {

		wp_dequeue_script( 'minicart-init' );
		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'jqueryvalidate', get_stylesheet_directory_uri() . '/js/jquery.validate.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'checkout-form', get_stylesheet_directory_uri() . '/js/checkout-form.js', false, null, true );
		wp_localize_script( 'checkout-form', 'Checkout', array(
		// URL to wp-admin/admin-ajax.php to process the request
		        'ajaxURL'          => admin_url( 'admin-ajax.php' ),

		// generate a nonce with a unique ID
		// so that you can check it later when an AJAX request is sent
		        'nonce' => wp_create_nonce( 'checkout-form-nonce' ),

		        'invalid' => __( 'Please make sure form is complete and valid.' ),
		        'failed' => __( 'Checkout Failed.' ),
		        'action' => $this->action,
		)
		);

		require_once STYLESHEETPATH . '/inc/checkout-form.php';

		exit;
	}
	}

	/**
	 * get_button method
	 * @param string $title
	 * @param string $price
	 */
	public function the_form_button() {
		global $post;

		$href = get_permalink( $post->ID );
		$item_number = $post->ID;
		$item_name = $post->post_title;
		$amount = get_post_meta( $post->ID, '_product_price', true );

		?>

		<form>
		<fieldset>
		<input type="hidden" name="cmd" value="_cart" /> 
		<input
			type="hidden" name="add" value="1" /> 
			<input type="hidden" name="currency_code" value="EUR"> 
			<input type="hidden" name="href" value="<?php echo $href ?>" /> 
			<input type="hidden" name="item_number" value="<?php echo $item_number ?>" /> 
			<input type="hidden" name="item_name" value="<?php echo $item_name ?>" /> 
			<input type="hidden" name="amount" value="<?php echo $amount ?>" /> 
			<input type="submit" name="submit" value="Add to cart" class="button" />
			</fieldset>
		</form>

		<?php
	}

} // end class WPSEC_Minicart

endif;

/**
 * Create instance
 */
global $wpsec_minicart;
if ( class_exists( 'WPSEC_Minicart' ) && !$wpsec_minicart ) {
	$wpsec_minicart = WPSEC_Minicart::getInstance();
}

?>