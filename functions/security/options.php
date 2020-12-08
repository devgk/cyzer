<?php
/**
 * @package         wp_cyzer_engine
 */

// Disable WP-Admin
function wce_disable_wp_admin(){
  // Disable WP-Admin
  if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 ); exit();
  }
  global $pagenow;
  $action = (isset($_GET['action'])) ? $_GET['action'] : '';
  if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout'))))) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 ); exit();
  }
}

function block_wp_admin(){
  add_action( 'init', 'wce_disable_wp_admin' );
}