<?php
  require_once '../config/connection.php';

  $sql = "
    SELECT SUM(broj_glasova) as broj_glasova
    FROM opcije
  ";

  $result = mysqli_query($connection,$sql);
  $rows = array();

  while ( $row = mysqli_fetch_array($result) ) {
    $rows[] = $row;
  }

  echo json_encode($rows);
?>