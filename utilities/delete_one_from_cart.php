<?php
  require_once '../config/connection.php';

  session_start();

  $productID = (int)$_GET['id'];
  $userID = (int)$_SESSION['id_korisnik'];

  $updateCart = "
    UPDATE korpa
    SET kolicina = kolicina - 1
    WHERE id_korisnik = $userID AND id_proizvod = $productID
  ";

  mysqli_query($connection, $updateCart);

  $sql = "
    SELECT *
    FROM korpa
    WHERE id_korisnik = $userID AND id_proizvod = $productID
  ";

  $res = mysqli_query($connection,$sql);
  $product = mysqli_fetch_array($res);

  if ( $product['kolicina'] == 0 ) {
    $deleteFromCart = "
      DELETE
      FROM korpa
      WHERE id_korisnik = $userID AND id_proizvod = $productID 
    ";

    mysqli_query($connection,$deleteFromCart);
  }

  mysqli_close($connection);

  header('Location:/cart.php');
?>