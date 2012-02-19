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

?>

<style type="text/css">
  
  .shipping-header {
    padding: 10px 0;
    font-weight: bold;
  }
  
  .shipping div {
    width: 170px;
    float: left;
  }
  
  .shipping div input {
    font-size:12px;
  }
  
</style>

<div class="shipping-header">
  <?php _e( 'Shipping address' ) ?>
  <br>
  <label for="shipping_checkbox" style="font-weight:normal;font-size:12px;">
    <?php _e( 'Same as billing address' ) ?>
    <input id="shipping_checkbox" type="checkbox" checked />
  </label>
</div>
  
<div id="shipping_body" class="shipping" style="display:none;">

  <div>
    <?php _e( 'Email' ) ?>*:<br>
    <input id="email-address_d" name="email_d" type="text" class="required email">
  </div>
  
  <div>
    <?php _e( 'Name' ) ?>*:<br>
    <input id="first_name_d" name="first_name_d" type="text" class="required">
  </div>
  
  <div>
    <?php _e( 'Surname' ) ?>*:<br>
    <input id="last_name_d" name="last_name_d" type="text" class="required">
  </div>
  
  <div>
    <?php _e( 'Address' ) ?>*:<br>
    <input id="address1_d" name="address1_d" type="text" size="25" class="required">
  </div>
  
  <div>
    <?php _e( 'City' ) ?>*:<br>
    <input id="city_d" name="city_d" type="text" class="required">
  </div>
  
  <div>
    <?php _e( 'Zip code' ) ?>*:<br>
    <input id="zip_d" name="zip_d" type="text" size="10" class="required number">
  </div>
  
  <div>
    <?php _e( 'Phone' ) ?>*:<br>
    <input id="H_PhoneNumber_d" name="H_PhoneNumber_d" type="text" size="10" class="required">
  </div>
  
  <div>
    <?php _e( 'DNI/NIF' ) ?>:<br>
    <input id="dni_d" name="dni_d" type="text" size="12">
  </div>

</div>

<div style="clear:both"></div>

<script type="text/javascript">          
  jQuery( function($) {
    jQuery( "#shipping_checkbox" ).click( function(){
      if( $('#shipping_checkbox').attr( 'checked' ) ) {
        $('#shipping_body').hide( 'slow' );
      } else {
        $('#shipping_body').show( 'slow' );
      }
    });
  });
</script>
