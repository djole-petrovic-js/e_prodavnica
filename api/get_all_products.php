<?php
  require_once '../config/connection.php';

  $sql = "
    SELECT p.id_proizvodi,p.naziv AS pNaziv,p.cena,p.kolicina,p.opis,k.naziv AS kNaziv,
    k.id_kategorije
    FROM proizvodi p INNER JOIN kategorije k
    ON p.id_kategorija = k.id_kategorije
  ";

  $result = mysqli_query($connection,$sql);

  $rows = array();

  while ( $row = mysqli_fetch_array($result) ) {
    $rows[] = $row;
  }

  mysqli_close($connection);

  echo json_encode($rows);
?>