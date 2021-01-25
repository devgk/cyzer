<?php

class stag_db{
  // PDO options
  private $pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  // Connection object
  private $connection_obj;

  // Error variable
  public $error = false;

  function __construct($data){
    // Database Connection Details
    $db_host    = $data['host'];
    $db_name    = $data['name'];
    $db_charset = $data['charset'];

    // Database Credentials
    $db_username = $data['username'];
    $db_password = $data['password'];

     // PDO Data Source Name
     $pdo_dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";

     try {
      $this->connection_obj = new PDO(
        $pdo_dsn,
        $db_username,
        $db_password,
        $this->pdo_options
      );
    } catch (PDOException $e) {
      $error = 'Connection to DB failed';
    }
  }

  // Destruct Class Function
  function __destruct(){
    if(!$this->error) $this->connection_obj = null;
  }

  function execute($statement, $arguments_array = null){
    if($this->error) return false;

    try {
      // Prepare sql statement and bind parameters
      $prepared_statement = $this->connection_obj->prepare($statement);

      if(!empty($arguments_array)){
        foreach($arguments_array as $row_data){
          foreach($row_data as $column => $value){
            if(!empty($column)){
              $prepared_statement->bindValue($column, $value);
            }
          }
        }
      }

      // Execute prepare sql statement
      $prepared_statement->execute();

      /* The following call to closeCursor() may be required by some drivers */
      $prepared_statement->closeCursor();
    } catch(PDOException $e) {
      $this->error = $e->getMessage();
      return false;
    }
  }

  function fetch($statement, $arguments_array = null){
    if($this->error) return false;
    
    try {
      // Prepare sql statement and bind parameters
      $prepared_statement = $this->connection_obj->prepare($statement);

      if(!empty($arguments_array)){
        foreach($arguments_array as $row_data){
          foreach($row_data as $column => $value){
            if(!empty($column)){
              $prepared_statement->bindValue($column, $value);
            }
          }
        }
      }

      // Execute prepare sql statement
      $prepared_statement->execute();

      // Fetch data after execution
      $result = $prepared_statement->fetchAll(PDO::FETCH_ASSOC);

      /* The following call to closeCursor() may be required by some drivers */
      $prepared_statement->closeCursor();

      return $result;
    } catch(PDOException $e) {
      $this->error = 'Failed to prepare statement';
      return false;
    }
  }
}