<?php
/**
 * @package         wp_cyzer_engine
 */


// Remove Header Bar
add_filter('show_admin_bar', '__return_false');


// WordPress backend Footer
function we_core_footer () {
  return 'WordPress secured and enhanced by <a href="https://cyzer.dev/">WP Cyzer Engine</a>';
}
add_filter('admin_footer_text', 'we_core_footer', 9999);


// WordPress backend Dashboard Version
function we_core_version() {
  return 'V 1.0';
}
add_filter('update_footer', 'we_core_version', 9999);


/** 
 * Reset Default
 * Removing - wp-embed
 * Adding   - Comment Reply WordPress Script */
function we_reset_default() {
  // De Registering Default jQuery
  wp_deregister_script('jquery');

  // De Registering WP Embed
  wp_deregister_script('wp-embed');

  // Comment Reply WordPress Script
  if((!is_admin()) && is_singular() && comments_open() && get_option('thread_comments')){
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'we_reset_default');