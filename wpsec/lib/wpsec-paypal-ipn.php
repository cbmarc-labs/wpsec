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

if( !class_exists( 'WPSEC_Paypal_IPN' ) ) :

/**
 * Class WPSEC_Paypal_IPN
 *
 * WP Simple E-Commerce Paypal Class
 *
 */
class WPSEC_Paypal_IPN {

  // singleton instance 
  private static $instance;
  
  /**
   * Paypal Url
   */
  var $options = null;
  
  /**
   * Post Type to insert notification of IPN Paypal
   */
  var $post_type_notification = 'notifications';

  /**
   * PHP4 Constructor
   */
  function WPSEC_Paypal_IPN() {
    $this->__construct();
  }
  
  /**
   * PHP5 constructor
   */
  function __construct() {
    $this->options = get_option( WPSEC_PREFIX . 'options' );
    
    add_action( 'parse_request', array( &$this, 'parse_request' ) );
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
   * parse_request method
   * @param string $wp
   */
  function parse_request( $wp ) {
    // only process requests with "paypal", e.g.: http://example.com/?paypal    
    if ( array_key_exists( WPSEC_PREFIX, $wp->query_vars ) && 
      $wp->query_vars[ WPSEC_PREFIX ] == 'paypal' && isset( $_POST[ 'payment_status' ] ) ) {
      
      // process paypal ipn
      $url = parse_url( $this->options[ 'paypal_url' ] );
      $data = $this->proccess_paypal_ipn( $url['host'] );
      
      // insert data into post type
      if( $this->options[ 'paypal_ipn_post' ] ) {
        $this->insert_into_post_type( $data, $this->post_type_notification );
      }
      
      if( $this->options[ 'paypal_ipn_email' ] !== '' ) {
        wp_insert_post( array( 'post_title' => $this->options[ 'paypal_ipn_email' ], 'post_content' => 'mail', 'post_type' => $this->post_type_notification ) );
        wp_mail( $this->options[ 'paypal_ipn_email' ], 'Paypal IPN Notification', $data );
      }
      
      exit;
    }
  }
    
  /**
   * proccess_paypal_ipn method
   * @param string $wp
   */
  private function proccess_paypal_ipn($paypal_host) {
    $data = date(DATE_RFC822) . "\r\n";
    
    // PHP 4.1

    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    
    foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
    }
    
    // post back to PayPal system to validate
    $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    $fp = fsockopen ('ssl://' . $paypal_host, 443, $errno, $errstr, 30);
    
    if (!$fp) {
    $data .= "HTTP ERROR"; // HTTP ERROR
    } else {
    fputs ($fp, $header . $req);
    while (!feof($fp)) {
    $res = fgets ($fp, 1024);
    if (strcmp ($res, "VERIFIED") == 0) {
    // check the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your Primary PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment
    $data .= "VERIFIED";
    }
    else if (strcmp ($res, "INVALID") == 0) {
    // log for manual investigation
    $data .= "INVALID";
    }
    }
    fclose ($fp);
    }
    
    $data .= " (" . $paypal_host . ")\r\n\r\n";
    $data .= $header . $req;
    
    return $data;
  }
  
  /**
   * insert_into_post_type method
   * @param string $data
   * @param string $post_type
   */
  private function insert_into_post_type( $data, $post_type ) {
    // assign posted variables to local variables
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
    
    $data .= $item_name . "\r\n";
    $data .= $item_number . "\r\n";
    $data .= $payment_status . "\r\n";
    $data .= $payment_amount . "\r\n";
    $data .= $payment_currency . "\r\n";
    $data .= $txn_id . "\r\n";
    $data .= $receiver_email . "\r\n";
    $data .= $payer_email . "\r\n";
  
    // Insert into wordpress post type
    $post = array(
     'post_title'   => $payment_status,
     'post_content' => $data,
     'post_status'  => 'private',
     'post_type'    => $post_type,
    );

    // Insert the post into the database
    $post_id = wp_insert_post( $post );
    
    //update_post_meta( $post_id, '_' . $post_type . '_item_name', wp_kses( $item_name, array() ) );
  }

} // end class WPSEC_Paypal_IPN

endif;

/**
 * Create instance
 */
global $wpsec_paypal_ipn;
if( class_exists( 'WPSEC_Paypal_IPN' ) && !$wpsec_paypal_ipn ) {
    $wpsec_paypal_ipn = WPSEC_Paypal_IPN::getInstance();
}

?>