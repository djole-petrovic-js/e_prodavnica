<?php
  require_once 'config/connection.php';

  session_start();

  if ( !isset($_SESSION['id_korisnik']) ) {
    header('Location:index.php');
  }

  $userID = (int)$_SESSION['id_korisnik'];
  $email = $_SESSION['email'];
  $totalPrice = 0;

  if ( isset($_POST['btnCartSubmit']) ) {
    $mailIsSent = false;

    $message = "
      <h1>Narucio korisnik sa emailom : $email</h1>
    ";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= "From: djordje.petrovic.6.15@ict.edu.rs";

    $sql2 = "
      SELECT p.naziv as naziv, korp.kolicina, SUM(korp.kolicina * p.cena) as ukupna_cena,
      p.cena ,p.id_proizvodi
      FROM korisnici kors INNER JOIN korpa korp
      ON kors.id_korisnik = korp.id_korisnik INNER JOIN proizvodi p
      ON korp.id_proizvod = p.id_proizvodi
      WHERE kors.id_korisnik = $userID
      GROUP BY p.naziv,korp.kolicina
    ";

    $shoppingCart = mysqli_query($connection,$sql2);
    $total = 0;

    while ( $row = mysqli_fetch_array($shoppingCart) ) {
      $name = $row['naziv'];
      $qty  = $row['kolicina'];
      $price = $row['cena'];
      $totalForProduct = $row['ukupna_cena'];
      $total += (int)$totalForProduct;

      $message .= "
        <h1>Ime proizvoda : $name</h1>
        <p>Kolicina : $qty</p>
        <p>Cena po komadu : $price</p>
        <p>Ukupno za proizvod : $totalForProduct</p>
      ";
    }

    $message .= "<h2>Ukupna cena korpe : $total</h2>";

    if ( 
      @mail($_SESSION['email'], 'Korpa', $message,$headers) &&
      @mail('djordje.petrovic.6.15@ict.edu.rs', 'Korpa', $message,$headers)
    ) {
      $mailIsSent = true;

      $deleteUserCartSQL = "
        DELETE
        FROM korpa
        WHERE id_korisnik = $userID
      ";

      $deleteUserCart = mysqli_query($connection,$deleteUserCartSQL);
    } else {

    }
  }

  $sql = "
    SELECT p.naziv as naziv, korp.kolicina, SUM(korp.kolicina * p.cena) as ukupna_cena,
    p.cena ,p.id_proizvodi
    FROM korisnici kors INNER JOIN korpa korp
    ON kors.id_korisnik = korp.id_korisnik INNER JOIN proizvodi p
    ON korp.id_proizvod = p.id_proizvodi
    WHERE kors.id_korisnik = $userID
    GROUP BY p.naziv,korp.kolicina
  ";

  $result = mysqli_query($connection,$sql);
?>
<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME; ?> | Korpa</title>
    <?php include_once PARTIALS . 'meta.php' ?>
  </head>
  <body>
    <?php include_once PARTIALS . 'navigation.php' ?>

    <div class="container container-min-height" id="wreaper">
      <div class="row">
        <div class="col-md-12">
          <h1>Moja Korpa</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <?php if ( mysqli_num_rows($result) == 0 ): ?>
            <h2>Nemate nista u korpi</h2>
          <?php else: ?>
            <ul class='list-group'>
            <?php while ( $row = mysqli_fetch_array($result) ): ?>
              <?php
                $productID = $row['id_proizvodi'];
                $name = $row['naziv'];
                $qty = $row['kolicina'];
                $individualPrice = $row['cena'];
                $price = $row['ukupna_cena'];
                $totalPrice += $price;
              ?> 
              <li class='list-group-item'>
                <p>Ime : <?php echo $name ?>
                Izabrana kolicina : <?php echo $qty ?></p>
                <p>Pojedinacna cena : <?php echo $individualPrice . ' RSD'; ?>
                Ukupna cena za izabrano : <?php echo $price . ' RSD';?></p>
                <p>
                  <a href="utilities/delete_all_from_cart.php?id=<?php echo $productID ?>" class="btn btn-primary">Izbrisi sve</a>
                  <a href="utilities/delete_one_from_cart.php?id=<?php echo $productID ?>" class="btn btn-primary">Izbrisi jedan</a>
                </p>
              </li>
            <?php endwhile ?>
            </ul>
          <?php endif ?>
        </div>
        <?php if ( mysqli_num_rows($result) > 0  ): ?>
        <div class="col-md-6 cart-left" id="cart-info">
          <h2>Ukupna cena korpe : <?php echo $totalPrice . ' RSD'; ?></h2>
          <p>
            <form action="/cart.php" method="POST">
              <button type="submit" name="btnCartSubmit" class="btn btn-primary">Naruci</button>
            </form>
            <form action="utilities/delete_cart.php" method="POST">
              <button type="submit" class="btn btn-danger">Izbrisi korpu</button>
            </form>
          </p>
        </div>
        <?php endif ?>
        <?php if ( isset($_POST['btnCartSubmit']) ): ?>
        <br/>
        <div class="col-md-6 cart-left" id="cart-info">
          <?php if ( $mailIsSent ): ?>
            <h2>Uspesno ste narucili vase proizvode</h2>
          <?php else: ?>
            <h2>Doslo je do greske ,molimo vas da pokusate ponovo...</h2>
          <?php endif ?>
        </div>
        <?php endif ?>
      </div>
    </div>
    <?php include_once PARTIALS . '/footer.php' ?>
  </body>
</html>