<?php
    require_once '../config/connection.php';

    $productID = (int)$_POST['id'];
    $name = $_POST['name'];
    $imageName = $_FILES['image']['name'];
    $time = time();
    $imagePath = '../slike_proizvoda/' . $time . basename($_FILES["image"]["name"]);
    $imagePath2 = '/slike_proizvoda/' . $time . basename($_FILES["image"]["name"]);
    $tempImagePath = $_FILES['image']['tmp_name'];

    if ( move_uploaded_file($tempImagePath, $imagePath) ) {
        $sql = "
            INSERT INTO slike_proizvoda(putanja,id_proizvodi,naziv_slike)
            VALUES ('$imagePath2',$productID,'$name')
        ";

        $res = mysqli_query($connection,$sql);
    }


    header('Location:/admin.php');
?>