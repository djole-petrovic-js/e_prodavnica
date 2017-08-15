<?php
  if ( isset($_POST['submit']) ) {
    require_once '../config/connection.php';

    $name = $_POST['naziv'];
    $categorie = (int)$_POST['kategorija'];
    $price = (double)$_POST['cena'];
    $qty = (int)$_POST['kolicina'];
    $desc = trim($_POST['opis']);
    $time = time();
    $imageName = $time . $_FILES['slika']['name'];
    $imagePath = '../' . IMAGES . '/' . $time . basename($_FILES["slika"]["name"]);
    $tempImagePath = $_FILES['slika']['tmp_name'];

    $sql = "
      INSERT INTO proizvodi (naziv,id_kategorija,cena,kolicina,slika,opis)
      VALUES ('$name',$categorie,$price,$qty,'$imageName','$desc')
    ";

    $res = mysqli_query($connection,$sql);

    move_uploaded_file($tempImagePath, $imagePath);

    mysqli_close($connection);
  }

  header('Location:/admin.php');
?>