<?php
  require_once '../config/connection.php';

  $json = file_get_contents('php://input');
  $obj = json_decode($json);

  $id = (int)$obj->id;
  $name = $obj->naziv;

  $sql = "
    UPDATE kategorije
    SET naziv = '$name'
    WHERE id_kategorije = $id
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