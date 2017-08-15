<?php
  require_once '../config/connection.php';

  $json = file_get_contents('php://input');
  $obj = json_decode($json);

  $id = $obj->id;

  $sql = "
    DELETE FROM kategorije
    WHERE id_kategorije = $id
  ";

  $res = mysqli_query($connection,$sql);

  $response = array();

  if ( !$res ) {
      $response['success'] = false;
  } else {
      $response['success'] = true;
  }

  echo json_encode($response);

  mysqli_close($connection);
?>