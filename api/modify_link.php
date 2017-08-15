<?php
  require_once '../config/connection.php';

  $json = file_get_contents('php://input');
  $obj  = json_decode($json);

  $id   = (int)$obj->id_linkovi;
  $name = $obj->ime;
  $link = $obj->link;
  $dozvola = (int)$obj->dozvola;

  $sql = "
    UPDATE linkovi
    SET ime = '$name' , link = '$link' , dozvola = $dozvola
    WHERE id_linkovi = $id
  ";

  $res = mysqli_query($connection,$sql);

  mysqli_close($connection);

  $response = array();

  if ( !$res ) {
      $response['success'] = false;
  } else {
      $response['success'] = true;
  }

  echo json_encode($response);
?>