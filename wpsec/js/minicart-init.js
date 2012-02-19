/**
 * WP Simple E-Commerce
 * Provides a WordPress e-commerce paypal
 *
 * @author Marc Costa <cbmarc@gmail.com>
 *
 * @package WPSEC
 */

/**
 * Paypal Minicart initialization
 */
if( typeof PAYPAL != 'undefined' )
  PAYPAL.apps.MiniCart.render({
    paypalURL: Minicart.paypalURL,
    strings: {
      button: Minicart.button,
      subtotal: Minicart.subtotal,
      discount: Minicart.discount,
      shipping: Minicart.shipping
    }
  });