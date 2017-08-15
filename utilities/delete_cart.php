<?php
  require_once '../config/connection.php';

  session_start();

  $userID = (int)$_SESSION['id_korisnik'];

  $sql = "
    DELETE
    FROM korpa
    WHERE id_korisnik = $userID
  ";

  $res = mysqli_query($connection,$sql);

  mysqli_close($connection);

  header('Location:/cart.php');
?>