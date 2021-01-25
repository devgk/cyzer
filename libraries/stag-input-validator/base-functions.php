<?php

class input_validator_base_functions
{
  static protected function is_email($string){
    // Validates whether the value is a valid e-mail address.
    return filter_var($string, FILTER_VALIDATE_EMAIL);
  }

  static protected function is_ip($string){
    // Validates whether the value is a valid e-mail address.
    return filter_var($string, FILTER_VALIDATE_IP);
  }

  static protected function is_mac_address($string){
    // Validates whether the value is a valid e-mail address.
    return filter_var($string, FILTER_VALIDATE_MAC);
  }

  static protected function filter($string, $type = 'string'){
    if('plain-text' == $type){
      $string = filter_var($string, FILTER_SANITIZE_STRING);
    }

    else if('name' == $type){
      $expression = "/(^[a-z ]*)/";
      $string = preg_replace($expression, "", $string);
    }

    else if('db-name' == $type){
      $expression = "/(^[a-z0-9_]*)/";
      $string = preg_replace($expression, "", $string);
    }

    else if('username' == $type) {
      $expression = "/([^A-Za-z0-9])/";
      $string = preg_replace($expression, "", $string);
    }

    else if('integer' == $type) {
      $expression = "/[^\d]/";
      $string = preg_replace($expression, "", $string);
    }

    else if('email' == $type) {
      // Sanitize String
      $string = filter_var($string, FILTER_SANITIZE_EMAIL);
      $string = str_replace(' ', '', $string);
    }

    return $string;
  }

  static protected function validate_plain_text($args){
    $plain_text = $args['plain-text'];

    // Filter input
    $plain_text_formatted = self::filter($plain_text, 'plain-text');

    // Filter email if specified
    if(isset($args['filter']) && (bool)$args['filter'])
    $plain_text = $plain_text_formatted;
    
    // Don't filter email, show error if not specified
    else if($plain_text != $plain_text_formatted) return array(
      'error' => 'Plain text is not valid',
      'valid' => FALSE
    );

    return array(
      'plain-text' => $plain_text,
      'valid' => TRUE
    );
  }

  static protected function validate_person_name($args){
    $name = strtolower($args['name']);

    // Filter input
    $name_formatted = self::filter($name, 'name');

    // Filter email if specified
    if(isset($args['filter']) && (bool)$args['filter'])
    $name = $name_formatted;
    
    // Don't filter email, show error if not specified
    else if($name != $name_formatted) return array(
      'error' => 'Name is not valid',
      'valid' => FALSE
    );

    return array(
      'name' => $name,
      'valid' => TRUE
    );
  }
  
  static protected function validate_db_name($args){
    $db_name = strtolower($args['db-name']);

    // Filter input
    $db_name_formatted = self::filter($db_name, 'db-name');

    // Filter email if specified
    if(isset($args['filter']) && (bool)$args['filter'])
    $db_name = $db_name_formatted;
    
    // Don't filter email, show error if not specified
    else if($db_name != $db_name_formatted) return array(
      'error' => 'DB name is not valid',
      'valid' => FALSE
    );

    return array(
      'name' => $db_name,
      'valid' => TRUE
    );
  }

  static protected function validate_username($args){
    $username = $args['username'];

    // Filter input
    $username_formatted = self::filter($username, 'username');
  
    if(isset($args['filter']) && (bool)$args['filter'])
    $username = $username_formatted;
  
    // Don't filter, show error if filter not specified
    else if($username != $username_formatted) return array(
      'error' => 'Username is not valid',
      'valid' => FALSE
    );

    // Get the length
    $length = strlen($username);

    // Check Max Length
    if(isset($args['max-length']) && $length > (int)$args['max-length']) return array(
      'error' => 'Username is longer than the length specified!',
      'valid' => FALSE
    );

    // Check Min Length
    if(isset($args['min-length']) && $length < (int)$args['min-length']) return array(
      'error' => 'Username is shorter than the length specified!',
      'valid' => FALSE
    );

    return array(
      'username' => $username,
      'valid' => TRUE
    );
  }

  static protected function validate_email($args){
    // Email must be in lower case only
    $email = strtolower($args['email']);

    // Format & Removed HTML Tags
    $email_formatted = self::filter($email, 'email');
  
    // Filter email if specified
    if(isset($args['filter']) && (bool)$args['filter'])
    $email = $email_formatted;
    
    // Don't filter email, show error if not specified
    else if($email != $email_formatted) return array(
      'error' => 'Email is not valid',
      'valid' => FALSE
    );
    
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) return array(
      'email' => $email,
      'valid' => TRUE
    );

    return array(
      'error' => 'Email is not valid',
      'valid' => FALSE
    );
  }

  static protected function validate_integer($args){
    $number = (int)$args['number'];

    // Format & Removed HTML Tags
    $number_formatted = self::filter($number, 'integer');

    // Filter email if specified
    if(isset($args['filter']) && TRUE === $args['filter'])
    $number = $number_formatted;

    // Get the length
    $length = strlen((string)$number);

    // Check Length
    if(isset($args['length']) && $length != (int)$args['length']) return array(
      'error' => 'Specified length of number did not match',
      'valid' => FALSE
    );

    // Check Max Length
    if(isset($args['max-length']) && $length > (int)$args['max-length']) return array(
      'error' => 'Number is not valid. Digits are more than it should be!',
      'valid' => FALSE
    );

    // Check Min Length
    if(isset($args['min-length']) && $length < (int)$args['min-length']) return array(
      'error' => 'Number is not valid. Digits are less than it should be!',
      'valid' => FALSE
    );

    // Check Max Value
    if(isset($args['max-value']) && (int)$number > (int)$args['max-value']) return array(
      'error' => 'Number is not valid. Value is more than it should be!',
      'valid' => FALSE
    );

    // Check Min Value
    if(isset($args['min-value']) && (int)$number < (int)$args['min-value']) return array(
      'error' => 'Number is not valid. Value is less than it should be!',
      'valid' => FALSE
    );

    return array(
      'number' => $number,
      'valid' => TRUE
    );
  }

  static protected function validate_password($args){
    $password = $args['password'];

    // Get the length
    $length = strlen($password);

    // Check Max Length
    if(isset($args['max-length']) && $length > (int)$args['max-length']) return array(
      'error' => 'Password is longer than the length specified!',
      'valid' => FALSE
    );

    // Check Min Length
    if(isset($args['min-length']) && $length < (int)$args['min-length']) return array(
      'error' => 'Password is shorter than the length specified!',
      'valid' => FALSE
    );

    // Password Contains Number
    if(isset($args['number-required']) && TRUE === $args['number-required']) {
      if(!preg_match("/\d/", $password)) return array(
        'error' => 'Password must contain at least one number!',
        'valid' => FALSE
      );
    }

    // Password Contains Uppercase Letter
    if(isset($args['uppercase-required']) && TRUE === $args['uppercase-required']) {
      if(!preg_match("/[A-Z]/", $password)) return array(
        'error' => 'Password must contain at least one uppercase letter!',
        'valid' => FALSE
      );
    }

    // Password Contains Lowercase Letter
    if(isset($args['lowercase-required']) && TRUE === $args['lowercase-required']) {
      if(!preg_match("/[a-z]/", $password)) return array(
        'error' => 'Password must contain at least one lowercase letter!',
        'valid' => FALSE
      );
    }

    // Password Contains Special characters Letter
    if(isset($args['special-required']) && TRUE === $args['special-required']) {
      if(!preg_match("/[\/\\\s\'\.\`\"\^\£\:\;\!\$\%\&\*\(\)\{\}\[\]\@\#\~\?\>\<\>\,\|\=\+\-]/", $password)) return array(
        'error' => 'Password must contain at least one special character!',
        'valid' => FALSE
      );
    }

    return array(
      'password' => $password,
      'valid' => TRUE
    );
  }
}