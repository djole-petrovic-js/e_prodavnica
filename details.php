<?php
  require_once 'config/connection.php';

  session_start();

  $productID = (int)$_GET['id'];

  $comments = "
    SELECT *
    FROM komentari k INNER JOIN korisnici ko
    ON k.id_korisnik = ko.id_korisnik
    WHERE k.id_proizvod = $productID
  ";

  $allComments = mysqli_query($connection,$comments);

  $sql = "
    SELECT p.id_proizvodi,p.naziv as pNaziv,p.cena,p.kolicina,p.broj_lajkova,p.broj_dislajkova,
    p.broj_komentara,p.slika,p.opis,k.naziv as kNaziv
    FROM proizvodi p INNER JOIN kategorije k
    ON p.id_kategorija = k.id_kategorije
    WHERE id_proizvodi = $productID
  ";

  $res = mysqli_query($connection,$sql);

  $product = mysqli_fetch_array($res);
?>

<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME; ?> | <?php echo $product['pNaziv']; ?></title>
    <?php include_once(PARTIALS . 'meta.php') ?>
  </head>
  <body>
    <?php include_once(PARTIALS . 'navigation.php') ?>

    <div class="container" id="wreaper">
      <div class="row">
        <div class="col-md-12">
          <h1>
            <?php echo $product['pNaziv'] ?>
            <?php if ( isset($_SESSION['id_korisnik']) ): ?>
              <a href='/utilities/add_to_cart.php?id=<?php echo $product['id_proizvodi']; ?>' 
              class='btn btn-primary block'>Dodaj u korpu</a>
            <?php endif ?>
            <a href="/galerija.php?id=<?php echo $product['id_proizvodi'] ?>" class="btn btn-primary">Galerija</a>
          </h1>
        </div>      
      </div>
      <div class="row">
        <div class="col-md-5">
          <div id="details-img-wreaper">
            <img class="img-responsive" src="/slike/<?php echo $product['slika'] ?>">
          </div>
          <div class="col-md-9">
            <p>
              <?php if ( $product['broj_lajkova'] + $product['broj_dislajkova'] > 0 ): ?>
              <div class="votes-wreaper">
                <?php 
                  $total = (int)$product['broj_lajkova'] + (int)$product['broj_dislajkova'];
                  $likesPercent = round((int)$product['broj_lajkova'] / $total * 100);
                  $dislikesPercent = round((int)$product['broj_dislajkova'] / $total * 100);
                ?>
                <?php ?>
                <?php if ( $likesPercent > 0 ): ?>
                <div id="votes-left" 
                  style="width:<?php echo $likesPercent . '%'; ?>">
                  <?php echo $likesPercent; ?> %
                </div>
                <?php endif ?>
                <?php if ( $dislikesPercent > 0 ): ?>
                  <div id="votes-right"
                    style="width:<?php echo $dislikesPercent . '%'; ?>">
                    <?php echo $dislikesPercent; ?> %
                  </div>
                <?php endif ?>
              </div>
              <?php endif ?>
              <div id="votes">              
                Svidja mi se : <?php echo $product['broj_lajkova']; ?>
                Ne Svidja mi se : <?php echo $product['broj_dislajkova']; ?>
              </div>
            </p>
            <?php if ( isset($_SESSION['id_korisnik']) ): ?>
              <a class="btn btn-primary" 
                href="/utilities/vote_product.php?id=<?php echo $product['id_proizvodi'] ?>&like=yes">Svidja mi se</a>
              <a class="btn btn-danger" 
                href="/utilities/vote_product.php?id=<?php echo $product['id_proizvodi'] ?>&dislike=yes">Ne svidja mi se</a>

             <?php else: ?>
               <p>Morate biti ulogovani da biste glasali...</p>
             <?php endif ?>
          </div>
        </div>
        <div class="col-md-7">
          <p class="details-left">Kategorija : <?php echo $product['kNaziv']; ?></p>
          <p class="details-left">Cena <?php echo $product['cena']; ?> RSD</p>
          <p class="details-left">Trenutno na stanju : <?php echo $product['kolicina']; ?></p>
          <p class="details-left">Opis : <?php echo $product['opis']; ?></p>
          <?php if ( isset($_SESSION['id_korisnik']) ): ?>
            <form action="/utilities/komentar.php" method="POST">
              <h2>Vas komentar...</h2>
                <input type="hidden" name="productID" value="<?php echo $productID ?>">
                <div class="form-group">
                <textarea class="form-control" placeholder="Vas komentar..." name="comment">
                  
                </textarea>
                <br>
                <button type="submit" class="btn btn-primary">Posalji</button>
              </div>
            </form>
          <?php else: ?>
            <h3>Morate biti ulogovani da biste komentarisali...</h3>
          <?php endif ?>

          <?php if ( mysqli_num_rows($allComments) == 0 ): ?>
            <p>Trenutno nema komentara...</p>
          <?php else: ?>
            <h2>Komentari</h2>
            <?php while ( $row = mysqli_fetch_array($allComments) ): ?>
              <div class="col-md-12 comments">
                <h4>Komentar poslao <?php echo $row['korisnicko_ime']; ?>
                  <?php 
                    $date = strtotime($row['komentar_datum']);

                    echo date('d/m/Y',$date); 
                  ?>
                </h4>
                <p>
                  <?php echo htmlspecialchars($row['komentar']); ?>
                </p>
              </div>
            <?php endwhile ?>
          <?php endif ?>

        </div>
      </div>
    </div>

    <?php include_once PARTIALS . '/footer.php' ?>
  </body>
</html>