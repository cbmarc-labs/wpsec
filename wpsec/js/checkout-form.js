/**
 * WP Simple E-Commerce
 * Provides a WordPress e-commerce paypal
 *
 * @author Marc Costa <cbmarc@gmail.com>
 *
 * @package WPSEC
 */

/**
 * Checkout Form JQuery functions
 */
jQuery(function($) {

  $( '#resetcart' ).click(function(){
    PAYPAL.apps.MiniCart.reset();
    
    $( '#checkout_form_div' ).hide( 0, function() {
      $( '#checkout_empty_div' ).show();
    });
  });
  
  // is shipping_checkbox checked
  $( '#checkout_form_submit_input' ).click(function(){
  
    if( $( '#shipping_checkbox' ).attr( 'checked' ) ) {
    
      $( '#email-address_d' ).val( $( '#email-address' ).val() );
      $( '#first_name_d' ).val( $( '#first_name' ).val() );
      $( '#last_name_d' ).val( $( '#last_name' ).val() );
      $( '#address1_d' ).val( $( '#address1' ).val() );
      $( '#city_d' ).val( $( '#city' ).val() );
      $( '#zip_d' ).val( $( '#zip' ).val() );
      $( '#H_PhoneNumber_d' ).val( $( '#H_PhoneNumber' ).val() );
      $( '#dni_d' ).val( $( '#dni' ).val() );
      
    }
  });
  
  // validate form
  $( '#checkout_form' ).validate({
  
    errorPlacement: function( error, element ) {},
    
    invalidHandler: function(form, validator) {
      alert( Checkout.invalid );
    },
    
    submitHandler: function( form ) {
    
      // AJAX Submit
      jQuery( '#checkout_form' ).ajaxSubmit({
      
        url: Checkout.ajaxURL,
        data: { 
          action: Checkout.action,
          nonce: Checkout.nonce
        },
        type: 'post',
        
        beforeSubmit: function( formData, jqForm, options ) {
          $( '#checkout_form_submit_div' ).hide( 0, function(){
            $( '#checkout_form_redirect_div' ).show();
          });
        },
        
        success : function( responseText, statusText, xhr, $form ) {
        
          // Check Ajax response
          if( responseText[ 'success' ] == false) {
            alert( Checkout.failed );
            
          } else {
            // Normal submit
            form.submit();
          }
        },
        
        timeout: 3000
      });
      
    }
  });
  
});