<?php
  require_once('config.php');

  $connection = mysqli_connect(SERVER_NAME,USERNAME,PASSWORD,DATABASE);
  mysqli_set_charset($connection,"utf8");
  
  $errors = array();

  if ( !$connection ) {
    echo mysqli_error($connection);
  }

?>