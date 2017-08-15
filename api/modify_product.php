<?php
    require_once '../config/connection.php';

    $json = file_get_contents('php://input');
    $obj  = json_decode($json);

    $id_proizvodi = (int)$obj->id_proizvodi;
    $naziv = $obj->naziv;
    $id_kategorije = (int)$obj->id_kategorije;
    $cena = (int)$obj->cena;
    $kolicina = (int)$obj->kolicina;
    $opis = $obj->opis;

    $sql = "
        UPDATE proizvodi
        SET naziv = '$naziv', id_kategorija = $id_kategorije , cena = $cena,
        kolicina = $kolicina, opis = '$opis'
        WHERE id_proizvodi = $id_proizvodi
    ";

    $res = mysqli_query($connection,$sql);

    $response = array();

    if ( !$res ) {
        $response['success'] = false;
    } else {
        $response['success'] = true;
    }

    echo json_encode($response);

    mysqli_close($connection);
?>