<?php

class DbConnection{

  public static function getConnection()
  {
    $host = 'localhost';
    $dbname = 'task';
    $user = 'root';
    $password = '';

    return mysqli_connect($host, $user, $password, $dbname);
  }
}
?>
