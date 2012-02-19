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

if ( !class_exists( 'WPSEC' ) ) :

/**
 * Class WPSEC
 *
 * WP Simple E-Commerce Main Class
 *
 */
class WPSEC {

  // singleton instance 
  private static $instance; 

  /**
   * PHP4 Constructor
   */
  function WPSEC() {
    $this->__construct();
  }
  
  /**
   * PHP5 constructor
   */
  function __construct() {
    add_theme_support( 'post-thumbnails' );
  
    require_once STYLESHEETPATH . '/lib/wpsec-constants.php';
    require_once STYLESHEETPATH . '/lib/wpsec-options.php';
    
    require_once STYLESHEETPATH . '/lib/wpsec-products.php';
    require_once STYLESHEETPATH . '/lib/wpsec-notifications.php';

    require_once STYLESHEETPATH . '/lib/wpsec-minicart.php';
    
    //require_once dirname( __FILE__ ) . '/wpsec-paypal-ipn.php';
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

} // end class WPSEC

endif;

/**
 * Create instance
 */
global $wpsec;
if ( class_exists( 'WPSEC' ) && !$wpsec ) {
    $wpsec = WPSEC::getInstance();
}	

?>