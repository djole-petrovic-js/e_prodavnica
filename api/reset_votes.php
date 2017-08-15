<?php
  require_once '../config/connection.php';

  $sql = "
    UPDATE opcije
    SET broj_glasova = 0
  ";

  $res = mysqli_query($connection,$sql);

  $emptyUserWhoVoted = "
    DELETE FROM korisnici_anketa
  ";

  $e = mysqli_query($connection,$emptyUserWhoVoted);

  mysqli_close($connection);

  $response = array();

  if ( $res && $e ) {
    $response['success'] = true;
  } else {
    $response['success'] = false;
  }

  echo json_encode($response);
?>