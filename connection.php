<?php
  require_once('config.php');

  $connection = mysqli_connect(SERVER_NAME,USERNAME,PASSWORD,DATABASE)
  
  $errors = array();

  if ( !$connection ) {
    echo mysqli_error($connection);
  }

?>
