<?php

/**
 * GWT Wordpress
 *
 * Google Web Toolkit Wordpress
 *
 * @package WPSEC
 * @author Marc Costa <cbmarc@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

require_once STYLESHEETPATH . '/lib/wpsec.php';

require_once STYLESHEETPATH . '/widgets/wpsec-recentproductswidget.php';
require_once STYLESHEETPATH . '/widgets/wpsec-productcategorieswidget.php';

add_filter( 'query_vars', 'query_vars' );
add_action( 'parse_request', 'parse_request' );

function query_vars( $vars ) {
  $vars[] = "gwordpress";

  return $vars;
}

function parse_request( $wp ) {
  global $post;
  
  if( !array_key_exists( "gwordpress", $wp->query_vars ) )
    return;
  
  $arr = array();
  
  switch($wp->query_vars['gwordpress']) {
      case 'p':
      case 'products':
        unset($wp->query_vars['gwordpress']);
      
        query_posts( $wp->query_vars );
  
        if (have_posts()) :
          while (have_posts()) : the_post();
          
            $image = wp_get_attachment_image_src( get_post_thumbnail_id(), "medium" );
            $image_src = $image[0];
            if($image[0] == '')
              $image_src = get_stylesheet_directory_uri() . '/imgs/default_product.png';
            
            array_push(
              $arr,
              array (
                'post_title' => $post->post_title,
                'price' => get_post_meta($post->ID, '_product_price', true ) . '&nbsp;&euro;',
                'image' => $image_src,
                'post_name' => $post->post_name,
                'post_content' => $post->post_content
              )
            );
          
          endwhile;
        endif;
        
        wp_reset_query();
        break;
    case 'categories':
    
      $tax = 'products_category';
      $cats = get_terms( $tax, '' );
      
      if ($cats) {
        foreach($cats as $cat) {
          
          $permalink = get_term_link($cat, $tax);
          $url_endpoint = parse_url( $permalink );
          $url_endpoint = $url_endpoint['path'];
          
          array_push(
            $arr,
            array(
              'name' => $cat->name,
              'permalink' => $permalink,
              'slug' => $cat->slug
            )
          );
          
        }
      }
      
      break;
  }
  
  echo json_encode( $arr );
  
  exit;
}