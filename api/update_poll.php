<?php
  require_once '../config/connection.php';

  $json = file_get_contents('php://input');
  $obj  = json_decode($json);

  $pollID = (int)$obj->pollID;
  $pollName = $obj->pollName;
  $pollShow = (int)$obj->pollShow;

  $optionsJSON = json_encode($obj->options);

  $options = json_decode($optionsJSON,true);

  $sql = "
    UPDATE anketa
    SET ime = '$pollName' , vidljivost = $pollShow
    WHERE id_anketa = $pollID
  ";

  $result = mysqli_query($connection,$sql);

  foreach ( $options as $option ) {
    $optionID = $option['id_opcija'];
    $optionName = $option['opcija'];

    $sql = "
      UPDATE opcije
      SET opcija = '$optionName'
      WHERE id_opcija = $optionID
    ";

    $res = mysqli_query($connection,$sql);
  }

  $response = array();

  if ( $result ) {
    $response['success'] = true;
  } else {
    $response['success'] = false;
  }

  echo json_encode($response);
?>