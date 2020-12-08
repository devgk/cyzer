<?php
/**
 * @package         wp_cyzer_engine
 */


// Change default encoding
function base64_url_encode($data){
  return rtrim( strtr( base64_encode( $data ), '+/', '-_'), '=');
}


// Change default decoding
function base64_url_decode($data){
  return base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
}
