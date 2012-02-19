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
  
  .billing-header {
    padding: 10px 0;
    font-weight: bold;
  }
  
  .billing div {
    width: 170px;
    float: left;
  }
  
  .billing div input {
    font-size:12px;
  }
  
</style>

<div class="billing-header">
  <?php _e( 'Billing address' ) ?>
</div>

<div class="billing">

  <div>
    <?php _e( 'Email' ) ?>*:<br>
    <input id="email-address" name="email" type="text" class="required email">
  </div>
  
  <div>
    <?php _e( 'Name' ) ?>*:<br>
    <input id="first_name" name="first_name" type="text" class="required">
  </div>
    
  <div>
    <?php _e( 'Surmane' ) ?>*:<br>
    <input id="last_name" name="last_name" type="text" class="required">
  </div>

  <div>
    <?php _e( 'Address' ) ?>*:<br>
    <input id="address1" name="address1" type="text" size="25" class="required">
  </div>
  
  <div>
    <?php _e( 'City' ) ?>*:<br>
    <input id="city" name="city" type="text" class="required">
  </div>
  
  <div>
    <?php _e( 'Zip code' ) ?>*:<br>
    <input id="zip" name="zip" type="text" size="10" class="required number">
  </div>
  
  <div>
    <?php _e( 'Phone' ) ?>*:<br>
    <input id="H_PhoneNumber" name="H_PhoneNumber" type="text" size="10" class="required">
  </div>
  
  <div>
    <?php _e( 'DNI/NIF' ) ?>:<br>
    <input id="dni" name="dni" type="text" size="12">
  </div>

</div>

<div style="clear:both"></div>