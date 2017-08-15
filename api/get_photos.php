<?php
    require_once '../config/connection.php';

    $productID = (int)$_GET['id'];

    $sql = "
        SELECT *
        FROM slike_proizvoda
        WHERE id_proizvodi = $productID
    ";

    $res = mysqli_query($connection,$sql);
    $rows = array();

    while ( $row = mysqli_fetch_array($res) ) {
        $rows[] = $row;
    }

    mysqli_close($connection);

    echo json_encode($rows);
?>