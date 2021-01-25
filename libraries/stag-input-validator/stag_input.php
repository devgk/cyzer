<?php

require_once 'base-functions.php'; 

class stag_input extends input_validator_base_functions
{
  static function is($args){
    if(isset($args['email']) && !empty($args['email'])){
      return PARENT::is_email($args['email']);
    }

    return FALSE;
  }

  static function validate($args){
    if(isset($args['name']) && !empty($args['name'])){
      return PARENT::validate_person_name($args);
    }

    else if(isset($args['db-name']) && !empty($args['db-name'])){
      return PARENT::validate_db_name($args);
    }

    else if(isset($args['username']) && !empty($args['username'])){
      return PARENT::validate_username($args);
    }

    else if(isset($args['email']) && !empty($args['email'])){
      return PARENT::validate_email($args);
    }

    else if(isset($args['number']) && !empty($args['number'])){
      return PARENT::validate_integer($args);
    }

    else if(isset($args['password']) && !empty($args['password'])){
      return PARENT::validate_password($args);
    }

    else if(isset($args['plain-text']) && !empty($args['plain-text'])){
      return PARENT::validate_plain_text($args);
    }

    return array(
      'error' => 'Field is either empty or in valid!',
      'valid' => FALSE
    );
  }
}