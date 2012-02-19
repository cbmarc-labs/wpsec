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
 
global $wpsec_options;

?>
  <style type="text/css">
    input.error {
      border: 1px dotted red; 
    }
  </style>
    
  <div class="wrap">
    <h2><?php _e( esc_html( $wpsec_options->title ) ) ?></h2>
    <h3><?php echo 'Wordpress Simple E-Commerce v' . WPSEC_VERSION ?></h3>
    
    <form id="options_form" name="options_form" action="options.php" method="post">
    
    <?php settings_fields( WPSEC_PREFIX ); ?>
    
    <p><?php _e( 'Wordpress Simple E-Commerce options page.' ); ?></p>
    <h3><?php _e( 'Paypal general' ); ?></h3>

    <table class="form-table">
    
      <tr>
        <th><label for="<?php $wpsec_options->field_name( 'paypal_url' ) ?>"><?php _e( 'Paypal URL' ) ?></label></th>
        <td>
          <input name="<?php $wpsec_options->field_name( 'paypal_url' ) ?>" id="<?php $wpsec_options->field_name( 'paypal_url' ) ?>" type="text" value="<?php $wpsec_options->get_option( 'paypal_url' ); ?>" class="regular-text url" />
          <br>
          <span class="description">
            The PayPal URL to use if you are accessing sandbox or another version of the PayPal website, e.g.:<br>
            https://www.sandbox.paypal.com/cgi-bin/webscr<br>
            https://www.paypal.com/cgi-bin/webscr
          </span>
        </td>
      </tr>
      
      <tr>
        <th><label for="<?php $wpsec_options->field_name( 'paypal_business' ) ?>"><?php _e( 'Paypal Business' ) ?></label></th>
        <td>
          <input name="<?php $wpsec_options->field_name( 'paypal_business' ) ?>" id="<?php $wpsec_options->field_name( 'paypal_business' ) ?>" type="text" value="<?php $wpsec_options->get_option( 'paypal_business' ); ?>" class="regular-text email" />
          <br>
          <span class="description">
            Determines where your customer is paying to, e.g.:<br>
            account@domain.com
          </span>
        </td>
      </tr>
      
      <tr valign="top">
        <th scope="row"><?php _e( 'Paypal IPN Email Notifications' ) ?></th>
        <td>
          <input name="<?php $wpsec_options->field_name( 'paypal_ipn_email' ) ?>" id="<?php $wpsec_options->field_name( 'paypal_ipn_email' ) ?>" type="text" value="<?php $wpsec_options->get_option( 'paypal_ipn_email' ); ?>" class="regular-text multiemail" />
          <label for="<?php $wpsec_options->field_name( 'paypal_ipn_email_test' ) ?>">
          <input name="<?php $wpsec_options->field_name( 'paypal_ipn_email_test' ) ?>" id="<?php $wpsec_options->field_name( 'paypal_ipn_email_test' ) ?>" type="checkbox" value="1" <?php checked('1', $wpsec_options->get_option( 'paypal_ipn_email_test', false ) ); ?> />
          <?php _e( 'Test Email when save changes?' ) ?>
          </label>
          <br>
          <span class="description">
            Multiple recipients may be specified using a comma-separated string., e.g.:<br>
            user1@domain.com,user2@domain.com
          </span>
        </td>
      </tr>
    
    </table>
    
    <?php submit_button(); ?>
    
    </form>
  </div>