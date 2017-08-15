<?php
  session_start();

  if ( isset($_SESSION['id_korisnik']) ) {
    session_destroy();
  }

  header('Location:index.php');
?>