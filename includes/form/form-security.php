<?php
/**
 * Cyzer Form Security - Form Initializer and validator
 */

function we_form_token($form){
  // Token generated time difference in seconds
  $token_freshness = 6;

  // Form Token
  $form_token = $_SESSION[$form.'_token'];

  // Token Generated Timestamp
  $form_token_gen_ts = $_SESSION[$form.'_token_gen_ts'];

  /** Calculate Token Freshness
   * 
   * If token generated time difference in not empty
   * calculate time difference */
  if(!empty($form_token_gen_ts)){
    // Get difference in seconds
    $token_freshness = time() - $generated_time;
  }

  /** Generate Form Token
   * 
   * If form token is empty or if token freshness is 
   * more than 6 seconds */
  if(empty($form_token) || $token_freshness > 5){
    // Generate  Unique Token
    $unique_token = md5(microtime(true));

    // Store token generated time stamp
    $_SESSION[$form.'_token_gen_ts'] = time();

    // Store token value
    $form_token = $_SESSION[$form.'_token'] = $unique_token;
  }

  // Return session token
  return $form_token;
}

function is_valid_req($form, $form_action){  
  // Check Session and post fields
  if(!isset($_SESSION[$form.'_token']) || $_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['action']) || !isset($_POST['token'])) {
    return false;
  }

  // Match token and actions
  if($form_action != $_POST['action'] || $_SESSION[$form.'_token'] != $_POST['token']) {
    return false;
  }

  // Nullify form token session value
  $_SESSION[$form.'_token'] = null;
  return true;
}

function we_safe_submit($form_token, $form_action, $button_name, $button_class){
  echo '<input name="token" type="hidden" value="'.$form_token.'" />'.PHP_EOL;
  echo '<input name="action" type="hidden" value="'.$form_action.'" />'.PHP_EOL;
  echo '<input type="submit" id="'.$form_action.'-button" class="'.$button_class.'" value="'.$button_name.'" />'.PHP_EOL;
}

function we_safe_submit_button($form_token, $form_action, $button_name, $button_class){
  echo '<input name="token" type="hidden" value="'.$form_token.'" />'.PHP_EOL;
  echo '<input name="action" type="hidden" value="'.$form_action.'" />'.PHP_EOL;
  echo '<button type="submit" id="'.$form_action.'-button" class="'.$button_class.'">'.$button_name.'</button>'.PHP_EOL;
}

function we_recaptcha_submit($api_key, $form_token, $form_action, $button_name, $button_class){
  echo '<div class="g-recaptcha" data-sitekey="'.$api_key.'"></div>';
  echo '<input name="token" type="hidden" value="'.$form_token.'" />'.PHP_EOL;
  echo '<input name="action" type="hidden" value="'.$form_action.'" />'.PHP_EOL;
  echo '<input type="submit" id="'.$form_action.'-button" class="'.$button_class.'" value="'.$button_name.'" disabled/>'.PHP_EOL;

  echo '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>'.PHP_EOL;
  echo '<script type="text/javascript">var onloadCallback = function() {document.getElementById("'.$form_action.'-button").disabled = false;};</script>'.PHP_EOL;
}

function we_recaptcha_submit_button($api_key, $form_token, $form_action, $button_name, $button_class){
  echo '<div class="g-recaptcha" data-sitekey="'.$api_key.'"></div>';
  echo '<input name="token" type="hidden" value="'.$form_token.'" />'.PHP_EOL;
  echo '<input name="action" type="hidden" value="'.$form_action.'" />'.PHP_EOL;
  echo '<button type="submit" id="'.$form_action.'-button" class="'.$button_class.'" disabled>'.$button_name.'</button>'.PHP_EOL;

  echo '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>'.PHP_EOL;
  echo '<script type="text/javascript">var onloadCallback = function(){ document.getElementById("'.$form_action.'-button").removeAttribute("disabled"); console.log("I ran");};</script>'.PHP_EOL;
}

function check_recaptcha($response, $api_secret){
  // Bypass
  if($api_secret == '') return true;

  // Validation
	$response = $response;
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret'    => $api_secret,
		'response'  => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);
	if ($captcha_success->success==false) {
		return false;
	} else if ($captcha_success->success==true) {
		return true;
	}
}