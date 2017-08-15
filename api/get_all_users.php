<?php
  require_once '../config/connection.php';

  $sql = "
    SELECT id_korisnik,email,adresa,datum,dozvola
    FROM korisnici
  ";

  $result = mysqli_query($connection,$sql);
  $rows = array();

  while ( $row = mysqli_fetch_array($result) ) {
    $rows[] = $row;
  }

  mysqli_close($connection);

  echo json_encode($rows);
?>