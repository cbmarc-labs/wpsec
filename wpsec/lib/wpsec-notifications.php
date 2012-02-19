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

if( !class_exists( 'WPSEC_Notifications' ) ) :

/**
 * Class WPSEC_Notifications
 *
 * WP Simple E-Commerce WPSEC_Notifications Class
 *
 */
class WPSEC_Notifications {

  // singleton instance 
  private static $instance;
  
  // Post type name Id, Plural Name, Singular Name
  private $post_name = array( 'notifications' , 'notifications', 'notification' );
  
  // Post type icon
  private $icon = "exclamation.png";

  /**
   * PHP4 Constructor
   */
  function WPSEC_Post_Type_Notification() {
    $this->__construct();
  }
  
  /**
   * PHP5 constructor
   */
  function __construct() {
    $this->icon = get_stylesheet_directory_uri() . '/imgs/' . $this->icon;
  
    add_action( "init", array( &$this, 'init' ) );
    
    add_action( "admin_menu", array( &$this, 'admin_menu' ) );
    add_filter( "bulk_actions-edit-{$this->post_name[0]}", array( &$this, 'bulk_actions_edit' ) );
    add_filter( "views_edit-{$this->post_name[0]}", array( &$this, 'views_edit' ) );
    add_action( "admin_print_styles", array( &$this, 'admin_print_styles' ) );
    
    add_action( 'admin_head', array( &$this, 'admin_head' ) );
    
    add_filter( "manage_{$this->post_name[0]}_posts_columns", array( &$this, 'manage_posts_columns' ) );
    add_action( "manage_{$this->post_name[0]}_posts_custom_column", array( &$this, 'manage_posts_custom_column' ), 10, 2 );
    add_filter( "manage_edit-{$this->post_name[0]}_sortable_columns", array( &$this, 'manage_edit_sortable_columns' ) );
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
    $post_name = ucwords( __( $this->post_name[1] ) );
    $post_singular = ucwords( __( $this->post_name[2] ) );
    register_post_type( $this->post_name[0], array(
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
      'public'  => false,
      'publicly_queryable' => false,
      'exclude_from_search' => true,
      'show_ui' => true,
      'query_var' => false,
      'can_export' => true,
    ) );
  }
  
  /**
   * Callback admin_menu
   * Remove admin UI parts that we don't support in notification management
   */
  function admin_menu() {
    global $menu, $submenu;
    unset( $submenu[ 'edit.php?post_type=' . $this->post_name[0] ] );
  }
  
  /**
   * Callback bulk_actions_edit_notifications
   * @param string $actions
   */
  function bulk_actions_edit( $actions ) {
    global $current_screen;
    if( $current_screen->id !== 'edit-' . $this->post_name[0] )
      return $actions;
    
    unset( $actions['edit'] );
      
    return $actions;
  }
  
  /**
   * Callback views_edit_notifications
   * @param string $views
   */
  function views_edit( $views ) {
    global $current_screen;
    if( $current_screen->id !== 'edit-' . $this->post_name[0] )
      return $views;
      
    unset( $views['publish'] );

    preg_match( '|post_type=notification\'( class="current")?\>(.*)\<span class=|', $views['all'], $match );
    if ( !empty( $match[2] ) )
      $views['all'] = str_replace( $match[2], 'Notifications ', $views['all'] );
    
    return $views;
  }
  
  /**
   * Callback admin_print_styles
   */
  function admin_print_styles() {
    global $current_screen;
    
    wp_enqueue_style( 'my_admin_style' , get_stylesheet_directory_uri() . '/admin.css' );
    
    if( $current_screen->id !== 'edit-' . $this->post_name[0] && $current_screen->id !== $this->post_name[0] )
      return;
    ?>

    <style type='text/css'>
    .add-new-h2, .inline, .view-switch, body.no-js .tablenav select[name^=action], body.no-js #doaction, body.no-js #doaction2 { 
      display: none
    }
    </style>

<?php
  }
	
	function admin_head() {
    ?>
    
    <style type="text/css" media="screen">
      #menu-posts-notifications .wp-menu-image {
        background: url(<?php echo $this->icon ?>) no-repeat 6px -17px !important;
      }
      
      #menu-posts-notifications:hover .wp-menu-image, #menu-posts-notifications.wp-has-current-submenu .wp-menu-image {
        background-position:6px 7px!important;
      }
    </style>
    
    <?php 
  }
  
  /**
   * Callback manage_notification_posts_columns
   * @param string $cols
   */
  function manage_posts_columns( $cols ) {
    $col_ip = array( 'notifications_ip' => 'IP' );
    $col_date = array( 'notifications_date' => 'Date' );
  
    $cols = array_slice( $cols, 0, 2, true ) + $col_ip + array_slice( $cols, 2, NULL, true );
    $cols = array_slice( $cols, 0, 3, true ) + $col_date + array_slice( $cols, 3, NULL, true );
    
    unset( $cols[ 'date' ] );
    
    return $cols;
  }
  
  /**
   * Callback manage_posts_custom_column
   * @param string $col
   * @param string $post_id
   */
  function manage_posts_custom_column( $col, $post_id ) {
    global $post;

    switch ( $col ) {
      case 'notifications_ip':
        break;
      case 'notifications_date':
        echo get_the_date( __( 'd/m/Y @ G:i:s' ) );
        break;
    }
  }
  
  /**
   * manage_edit_sortable_columns Method
   *
   * @param unknown_type $cols
   * @return string
   */
  function manage_edit_sortable_columns( $cols ) {
    $cols[ 'notifications_date' ] = 'date';
   
    return $cols;
  }

} // end class WPSEC_Notifications

endif;

/**
 * Create instance
 */
global $wpsec_notifications;
if( class_exists( 'WPSEC_Notifications' ) && !$wpsec_notifications ) {
    $wpsec_notifications = WPSEC_Notifications::getInstance();
}	

?>