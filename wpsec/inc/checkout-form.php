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

get_header(); ?>

		<div id="primary" class="checkout">
			<div id="content" role="main">
        
        <?php
        
        $options = get_option( WPSEC_PREFIX . 'options' );
        $paypal_url = $options[ 'paypal_url' ];
        $paypal_business = $options[ 'paypal_business' ];
        
        ?>
        
        <style>
          .checkout_content {
            padding: 15px;
            border-radius: 15px;
            border: 1px solid #ddd;
            margin: 15px;
          }
        </style>
        
        <div id="checkout_empty_div" class="checkout_content" style="display:none;">
          <?php _e( 'The cart is empty.' ); ?>
        </div>
				
				<div id="checkout_form_div">
          <form id="checkout_form" action="<?php echo $paypal_url ?>">
          
            <input type="hidden" name="cmd" value="_cart">
            <input type="hidden" name="upload" value="1">
            <input type="hidden" name="business" value="<?php echo $paypal_business ?>">
            <input type="hidden" name="currency_code" value="EUR">
            <!-- <input type="hidden" name="notify_url" value="<?php /* bloginfo( 'url' ) */ ?>/?notify"> -->
            <input type="hidden" name="return" value="<?php bloginfo( 'url' ) ?>/?return">
            <input type="hidden" name="cancel_return" value="<?php bloginfo( 'url' ) ?>/?cancel">
            
            <div class="checkout_content"><?php get_template_part( 'inc/checkout-form', 'cart' ); ?></div>
            <div class="checkout_content"><?php get_template_part( 'inc/checkout-form', 'billing' ); ?></div>
            <div class="checkout_content"><?php get_template_part( 'inc/checkout-form', 'shipping' ); ?></div>

            <div id="checkout_form_submit_div" style="text-align:right;margin:10px;">
              <input id="checkout_form_submit_input" type="submit" value="<?php _e( 'Proceed to Checkout' ) ?>">
            </div>
            
            <div id="checkout_form_redirect_div" style="text-align:right;margin:10px;display:none;">
              <?php _e( 'Redirecting to Paypal, please wait...' ); ?>
            </div>
          
          </form>
        </div>
        
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>