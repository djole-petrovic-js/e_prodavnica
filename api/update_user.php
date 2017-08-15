<?php
  require_once '../config/connection.php';

  $dozvole = array(0,1,2,3);

  $json = file_get_contents('php://input');
  $obj = json_decode($json);

  $userID  = (int)$obj->userID;
  $dozvola = (int)$obj->dozvola;

  $response = array();

  if ( !in_array($dozvola, $dozvole) ) {
    $response['success'] = false;

    echo json_encode($response);
  } else {
    $sql = "
      UPDATE korisnici
      SET dozvola = $dozvola
      WHERE id_korisnik = $userID
    ";

    $res = mysqli_query($connection,$sql);

    if ( $res ) {
      $response['success'] = true;
    } else {
      $response['success'] = false;
    }

    echo json_encode($response); 
  }
?>