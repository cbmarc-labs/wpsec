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

if( !class_exists( 'WPSEC_Options' ) ) :


/**
 * WPSEC_Options Class
 *
 * WP Simple E-Commerce WPSEC_Options Class
 */
class WPSEC_Options {

  /**
   * singleton instance
   * @var unknown_type
   */
  private static $instance;
  
  
  /**
   * $title Field
   *
   * @var string
   */
  var $title = 'Options Store';
  
  /**
   * $html Field
   *
   * @var string
   */
  var $html = '/inc/options.php';
  
  /**
   * $options Field
   *
   * @var unknown_type
   */
  private static $options;

  /**
   * PHP4 Constructor
   */
  function WPSEC_Options() {
    $this->__construct();
  }
  
  /**
   * PHP5 constructor
   */
  function __construct() {
    add_action( 'admin_init', array( &$this, 'admin_init' ) );
    add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
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
   * Callback admin_init
   */
  function admin_init() {
    // For validate form fields
    wp_enqueue_script( 'jqueryvalidate', get_stylesheet_directory_uri() . '/js/jquery.validate.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'options-form', get_stylesheet_directory_uri() . '/js/options-form.js', false, null, true );
    
    wp_localize_script( 'options-form', 'Options', array(      
      'invalid' => __( 'Please make sure form is complete and valid.' ),
      )
    );
    
    if ( ! current_user_can( 'manage_options' ) )
      wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );
    
    register_setting( WPSEC_PREFIX ,  WPSEC_PREFIX . 'options' );
    
    $this->options = get_option( WPSEC_PREFIX . 'options' );

    // Test Email Notification
    if( $this->get_option( 'paypal_ipn_email_test', false ) ) {
      unset( $this->options[ 'paypal_ipn_email_test' ] );
      update_option( WPSEC_PREFIX . 'options', $this->options );

      $mail = wp_mail( $this->get_option( 'paypal_ipn_email', false ), __( 'Paypal IPN Notification Test' ), __( 'Paypal IPN Notification Test' ) );
      if( !$mail )
        add_settings_error( 'paypal_ipn_email_test', 'error_paypal_ipn_email_test', __( 'There was a problem sending your message. Please try again.' ) );
    }
  }
  
  /**
   * admin_menu Method
   */
  function admin_menu() {
    $title = __( $this->title );
    
    add_options_page( $title, $title, 'manage_options', 'options-store', array( &$this, 'options_store' ) );
  }
    
  /**
   * options_store Method
   */
  function options_store() {
    if( !current_user_can( 'manage_options' ) )  {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    require_once STYLESHEETPATH . $this->html;
  }
  
  /**
   * Field Name method
   * @param string $name
   * @param boolean $echo
   */
  function field_name( $name, $echo = true ) {
      $name = WPSEC_PREFIX . "options[$name]";
      if( $echo )
          echo $name;
      else
          return $name;
  }
  
  /**
   * Get Option method
   * @param string $name
   * @param boolean $echo
   */
  public function get_option( $name, $echo = true ) {
      $val = '';
      if( is_array( $this->options ) && isset( $this->options[$name] ) )
        $val = ( $this->options[$name] );

      if( $echo )
        echo $val;
      else
        return $val;
  }
  
} // end class WPSEC_Options

endif;

/**
 * Create instance
 */
global $wpsec_options;
if ( class_exists( 'WPSEC_Options' ) && !$wpsec_options ) {
    $wpsec_options = WPSEC_Options::getInstance();
}

?>