<?php
  require_once '../config/connection.php';

  session_start();

  $response = array(
    'userDoesntExist' => false,
    'alreadyVoted' => false,
    'success' => false,
    'error' => false
  );

  if ( isset($_SESSION['id_korisnik']) ) {
    $userID = (int)$_SESSION['id_korisnik'];

    $id = $_POST['id'];

    $sql = "
      SELECT *
      FROM korisnici_anketa
      WHERE id_korisnik = $userID
    ";

    $result = mysqli_query($connection,$sql);

    if ( mysqli_num_rows($result) == 0 ) {
      $sql = "
        UPDATE opcije
        SET broj_glasova = broj_glasova + 1
        WHERE id_opcija = $id
      ";

      $res = mysqli_query($connection,$sql);

      $updateUserPolls = "
        INSERT INTO korisnici_anketa(id_korisnik)
        VALUES ($userID)
      ";

      $userPollsRes = mysqli_query($connection,$updateUserPolls);

      $response['success'] = true;

      echo json_encode($response);
    } else {
      $response['alreadyVoted'] = true;

      echo json_encode($response);
    }
  } else {
    $response['userDoesntExist'] = true;

    echo json_encode($response);
  }

  mysqli_close($connection);
?>