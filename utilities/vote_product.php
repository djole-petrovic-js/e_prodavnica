<?php
  require_once '../config/connection.php';

  $productID = (int)$_GET['id'];
  $update = '';

  if ( isset($_GET['like']) ) {
    $update = 'SET broj_lajkova = broj_lajkova + 1';
  } else if ( isset($_GET['dislike']) ) {
    $update = 'SET broj_dislajkova = broj_dislajkova + 1';
  }

  $sql = "
    UPDATE proizvodi
    $update
    WHERE id_proizvodi = $productID
  ";

  $result = mysqli_query($connection,$sql);

  mysqli_close($connection);

  header('Location:/details.php?id=' . $productID);
?>