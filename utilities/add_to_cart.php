<?php
  require_once '../config/connection.php';

  session_start();

  if ( !isset($_SESSION['id_korisnik']) ) {
    header('Location:index.php');
  }

  $userID = (int)$_SESSION['id_korisnik'];
  $productID = (int)$_GET['id'];
  $insertOrUpdateShoppingCart = '';

  $sql = "
    SELECT *
    FROM korpa
    WHERE id_korisnik = $userID AND id_proizvod = $productID
  ";

  $result = mysqli_query($connection,$sql);

  if ( mysqli_num_rows($result) == 0 ) {
    $insertOrUpdateShoppingCart = "
      INSERT INTO korpa(id_korisnik,id_proizvod)
      VALUES ($userID,$productID)
    ";
  } else {
    $insertOrUpdateShoppingCart = "
      UPDATE korpa
      SET kolicina = kolicina + 1
      WHERE id_korisnik = $userID AND id_proizvod = $productID 
    ";
  }

  $res = mysqli_query($connection,$insertOrUpdateShoppingCart);

  mysqli_close($connection);

  $page = $_SERVER['HTTP_REFERER'];

  $location = 'Location:';

  if ( strpos($page, 'details.php') ) {
    $location .= '/details.php?id=' . $_GET['id'];
  } else if ( strpos($page, 'categories.php') ) {
    if ( isset($_GET['kategorija']) && $_GET['kategorija'] != '' ) {
      $location .= '/categories.php?kategorija=' . $_GET['kategorija'];
    } else {
      $location .= '/categories.php';
    }
  } else {
    $location .= '/index.php';
  }

  if ( isset($_GET['strana']) ) {
    if ( strpos($location, '?') ) {
      $location .= '&strana=' . $_GET['strana'];
    } else {
      $location .= '?strana=' . $_GET['strana'];
    }
  }

  header($location);
?>