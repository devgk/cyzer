<?php
/**
 * @package         wp_cyzer_engine
 */

class cyzer_page_maker{
  static private function validate_template_location($template_location){
    $template_location = substr($template_location, 1);

    $template_directory = get_stylesheet_directory();

    $file_directory = $template_directory.'/'.$template_location;

    if(file_exists($file_directory)) {
      return $template_location;
    }
    else return FALSE;
  }

  static function update_slug($page_id, $slug){
    wp_update_post(array (
      'ID'        => $setup_page_id,
      'post_name' => $slug
    ));
  }

  static function update_title($page_id, $slug){
    wp_update_post(array (
      'ID'        => $setup_page_id,
      'post_name' => $slug
    ));
  }

  static function set_on_front($page_id){
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $page_id );
  }

  static function set_as_child($page_id, $parent_id){
    wp_update_post(array(
      'ID'          => (int)$page_id,
      'post_parent' => (int)$parent_id
    ));
  }

  static function reset_permalink(){
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
    $wp_rewrite->flush_rules();
  }

  static function create_page($title, $template_location, $post_type = 'page'){
    $template_location = self::validate_template_location($template_location);

    if(!$template_location) return FALSE;

    $setup_page = array(
      'post_type'     => $post_type,
      'post_title'    => $title,
      'post_content'  => '',
      'post_status'   => 'publish'
    );
    $setup_page_id =  wp_insert_post($setup_page);

    if(is_wp_error($setup_page_id)) return FALSE;

    update_post_meta($setup_page_id, '_wp_page_template', $template_location);

    return $setup_page_id;
  }
}