<?php
    require_once '../config/connection.php';

    $json = file_get_contents('php://input');
    $obj = json_decode($json);

    $id = (int)$obj->id;
    $photoID = (int)$obj->id_slike;
    $name = $obj->naziv;

    $sql = "
        UPDATE slike_proizvoda
        SET naziv_slike = '$name', id_proizvodi = $id
        WHERE id_slike = $photoID
    ";

    $res = mysqli_query($connection,$sql);
    $response = array();

    $response['success'] = $res ? true : false;

    mysqli_close($connection);

    echo json_encode($response);
?>