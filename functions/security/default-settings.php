<?php
/**
 * @package         wp_cyzer_engine
 */


// REMOVE WP VERSION
function we_remove_version( $src) {
  global $wp_version;
  parse_str(parse_url($src, PHP_URL_QUERY), $query);
  if(!empty($query['ver']) && $query['ver'] == $wp_version){
    $src = remove_query_arg('ver', $src);
  }
  return $src;
}
add_filter('script_loader_src', 'we_remove_version');
add_filter('style_loader_src', 'we_remove_version');


// Frontend Cleanup
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);


// Disable Generator Meta Tag (Must Be Optional)
add_filter('the_generator', '__return_false');


// REMOVE WP EMOJI (Must Be Optional)
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');


// Remove oEmbed (Must Be Optional)
remove_filter('the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8);


// Disable REST API link tag (Must Be Optional)
remove_action('wp_head', 'rest_output_link_wp_head', 10);


// Disable REST API link in HTTP headers (Must Be Optional)
remove_action('template_redirect', 'rest_output_link_header', 11, 0);


// Disable oEmbed Discovery Links (Must Be Optional)
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);


// Remove Comments from frontend (Must Be Optional)
function callback($buffer){
  $buffer = preg_replace('/<!--(.|s)*?-->/', '', $buffer); return $buffer;
}
function buffer_start(){ ob_start("callback"); }
function buffer_end(){ ob_end_flush(); }
function remove_comments(){
  add_action('get_header', 'buffer_start');
  add_action('wp_footer', 'buffer_end');
}


/**
 * Login Security
 * Reset Default Error Message */
function customize_login_error($error){
  global $errors;
  $err_codes = $errors->get_error_codes();
  if(in_array( 'invalid_username', $err_codes)){
    $error = '<strong>ERROR</strong>: Un-Authorized Access!';
  }
  if(in_array('incorrect_password', $err_codes)){
    $error = '<strong>ERROR</strong>: Un-Authorized Access!';
  }
  return $error;
}
add_filter('login_errors', 'customize_login_error', 1, 1);