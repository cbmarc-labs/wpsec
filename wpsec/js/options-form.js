/**
 * WP Simple E-Commerce
 * Provides a WordPress e-commerce paypal
 *
 * @author Marc Costa <cbmarc@gmail.com>
 *
 * @package WPSEC
 */

/**
 * Options Form JQuery functions
 */

// Add "multiemail" method validation
jQuery.validator.addMethod( "multiemail", function( value, element ) {
  if ( this.optional( element ) ) // return true on optional element
    return true;
  
  var emails = value.split( new RegExp( "\\s*,\\s*", "gi" ) );
  valid = true;
  for( var i in emails ) {
    value = emails[ i ];
    valid = valid && jQuery.validator.methods.email.call( this, value, element );
  }
  return valid;
}, 'The format for one or more emails is incorrect.');

// JQuery Validation
jQuery(function($) {
  $( '#options_form' ).validate({
    errorPlacement: function( error, element ) {},
    invalidHandler: function( form, validator ) {
      alert( Options.invalid );
    }
  });
});