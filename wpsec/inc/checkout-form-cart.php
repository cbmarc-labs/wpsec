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

  .cart-header {
    padding: 10px 0;
    font-weight: bold;
  }
  
  .cart div {
    float: left;
    padding: 5px;
    width: 94px;
    height: 35px;
  }
  
  .cart-caption div {
    background-color: #eee;
    font-weight: bold;
  }
  
</style>

<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="carlos_1300205813_biz@gmail.com">

<div class="cart-header">
  <?php _e( 'Cart Summary' ) ?>
</div>

<div class="cart cart-caption">
  <div><?php _e( 'Image' ) ?></div>
  <div><?php _e( 'Ref.' ) ?></div>
  <div><?php _e( 'Product' ) ?></div>
  <div style="text-align:right;"><?php _e( 'Quantity' ) ?></div>
  <div style="text-align:right;"><?php _e( 'Subtotal' ) ?></div>
</div>

<div style="clear:both"></div>

<div>
  <?php

  $total = 0.0;
  for( $i = 1; ; $i++ ) :
    if( !array_key_exists( 'quantity_' . $i, $_POST ) )
      break;
    
  ?>

  <input type="hidden" name="quantity_<?php echo $i ?>" value="<?php echo $_POST[ 'quantity_' . $i ] ?>">
  <input type="hidden" name="href_<?php echo $i ?>" value="<?php echo $_POST[ 'href_' . $i ] ?>">
  <input type="hidden" name="item_number_<?php echo $i ?>" value="<?php echo $_POST[ 'item_number_' . $i ] ?>">
  <input type="hidden" name="item_name_<?php echo $i ?>" value="<?php echo $_POST[ 'item_name_' . $i ] ?>">
  <input type="hidden" name="amount_<?php echo $i ?>" value="<?php echo $_POST[ 'amount_' . $i ] ?>">
  
  <div class="cart">
    <div>
      <?php echo get_the_post_thumbnail( $_POST[ 'item_number_' . $i ], array( 32, 32 ) ); ?>
    </div>
    
    <div>
      #<?php echo $_POST[ 'item_number_' . $i ] ?>
    </div>
    
    <div>
      <?php echo $_POST[ 'item_name_' . $i ] ?>
    </div>
    
    <div style="text-align:right;">
      <?php echo $_POST[ 'quantity_' . $i ] ?>
    </div>
    
    <div style="text-align:right;">
      <?php 
        $subtotal = $_POST[ 'quantity_' . $i ] * $_POST[ 'amount_' . $i ];
        echo number_format( (float) str_replace( ',', '', $subtotal ), 2, '.', '' ) . '&nbsp;&euro;';
      ?>
    </div>
  </div>

  <div style="clear:both"></div>
    
  <?php
    
  $total += ( $_POST[ 'quantity_' . $i ] * $_POST[ 'amount_' . $i ] );
    
  endfor;

  ?>
</div>

<div class="cart cart-caption">
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div style="text-align:right;">
    <?php echo number_format( (float) str_replace( ',', '', $total ), 2, '.', '' ); ?>&nbsp;&euro;
  </div>
</div>

<div class="cart">
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div style="text-align:right;">
    <input id="resetcart" type="button" value="<?php _e( 'Reset Cart' ) ?>">
  </div>
</div>

<div style="clear:both"></div>