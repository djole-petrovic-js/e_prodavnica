<?php
  require_once '../config/connection.php';

  $json = file_get_contents('php://input');
  $obj = json_decode($json);

  $userID = (int)$obj->id;

  $sql = "
    DELETE
    FROM korisnici
    WHERE id_korisnik = $userID
  ";

  $res = mysqli_query($connection,$sql);
  $response = array();

  if ( $res ) {
    $response['success'] = true;
  } else {
    $response['success'] = false;
  }

  mysqli_close($connection);

  echo json_encode($response);
?>