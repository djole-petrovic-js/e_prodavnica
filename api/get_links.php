<?php
  require_once '../config/connection.php';

  $sql = "
    SELECT id_linkovi,ime,link,dozvola 
    FROM linkovi
    ORDER BY dozvola ASC
  ";

  $rows = array();

  $result = mysqli_query($connection,$sql);

  while ( $row = mysqli_fetch_array($result) ) {
    $rows[] = $row;
  }

  mysqli_close($connection);

  echo json_encode($rows);
?>