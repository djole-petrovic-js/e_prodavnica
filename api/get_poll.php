<?php
  require_once '../config/connection.php';

  $sql = "
    SELECT *
    FROM anketa as a INNER JOIN opcije as o
    ON a.id_anketa = o.id_anketa
  ";

  $poll = mysqli_query($connection,$sql);

  $rows = array();

  if ( mysqli_num_rows($poll) > 0 ) {
    while ( $row = mysqli_fetch_array($poll) ) {
      $rows[] = $row;
    }

    echo json_encode($rows);
  } else {
    echo json_encode($rows);
  }
?>