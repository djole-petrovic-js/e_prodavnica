<?php
  require_once '../config/connection.php';

  session_start();

  $productID = (int)$_POST['productID'];
  $userID = (int)$_SESSION['id_korisnik'];
  $comment = trim($_POST['comment']);

  $sql = "
    INSERT INTO komentari(komentar,id_korisnik,id_proizvod)
    VALUES ('$comment',$userID,$productID)
  ";

  $updateCommentsNumber = "
    UPDATE proizvodi
    SET broj_komentara = broj_komentara + 1
    WHERE id_proizvodi = $productID
  ";

  $res = mysqli_query($connection,$sql);
  $res2 = mysqli_query($connection,$updateCommentsNumber);

  mysqli_close($connection);

  header('Location:/details.php?id=' . $productID);
?>