<?php
  require_once 'config/connection.php';

  session_start();

  $categorie = isset($_GET['kategorija']) ? (int)$_GET['kategorija'] : '';
  $page = isset($_GET['strana']) ? (int)$_GET['strana'] : 1;

  $categories = $categorie !== ''
    ? "WHERE id_kategorija = $categorie"
    : '';

  $limit = 4;
  $offset = ($page - 1) * $limit ;
  $paginateHTML = '';
  $productsHTML = '';

  $sql = "
    SELECT *
    FROM proizvodi
    $categories
    ORDER BY datum DESC
    LIMIT $limit OFFSET $offset
  ";

  $result = mysqli_query($connection,$sql);

  $getNumberOfProducts = "
    SELECT *
    FROM proizvodi
  ";

  if ( $categorie !== '' ) {
    $getNumberOfProducts .= "WHERE id_kategorija = $categorie";
  }

  $n = mysqli_query($connection,$getNumberOfProducts);

  $numberOfProducts = mysqli_num_rows($n);
  $lastPageNumber = ceil($numberOfProducts / $limit);

  if (  $numberOfProducts > $limit ) {
    $c = '';

    if ( $categorie !== '' ) {
      $c = "kategorija=$categorie";
    }

    if ( $page !== 1 ) {
      $backPage = $page - 1;
      
      $paginateHTML .= "
        <a class='paginate-button' href='categories.php?$c&strana=$backPage'>Prethodna</a>
      ";
    }

    for ( $i = 1 ; $i <= $lastPageNumber ; $i++ ) {
      if ( $i !== $page ) {
        $paginateHTML .= "
          <a href='categories.php?$c&strana=$i'>$i</a>
        ";
      } else {
        $paginateHTML .= "<span>$i</span>";
      }
    }
 
    if ( $page != $lastPageNumber ) {
      $nextPage = $page + 1;

      $paginateHTML .= "
        <a class='paginate-button' href='categories.php?$c&strana=$nextPage'>Sledeca</a>
      ";
    }
  }

?>
<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME; ?> | Kategorije</title>
    <?php include_once PARTIALS . 'meta.php' ?>
  </head>
  <body>
    <?php include_once PARTIALS . 'navigation.php' ?>

    <div class="container container-min-height" id="wreaper">
      <div class="row">
        <div class="col-md-12">
          <?php if ( $numberOfProducts == 0 ): ?>
            <h1>Nije nista nadjeno sa ovom kategorijom...</h1>
          <?php else: ?>
            <?php while ( $row = mysqli_fetch_array($result) ): ?>
              <?php
                $id = $row['id_proizvodi'];
                $name = $row['naziv'];
                $img = $row['slika'];
                $price = $row['cena'];
                $desc = substr($row['opis'],0,60) . '...';
                $numberOfVotes = (int)$row['broj_lajkova'] + (int)$row['broj_dislajkova'];
                $numberOfComments = $row['broj_komentara'];
              ?>
              <div class='col-sm-4 col-lg-3 col-md-3 products'>
                <div class='thumbnail'>
                  <a href="/details.php?id=<?php echo $id; ?>"><img src='/slike/<?php echo $img; ?>' alt=''></a>
                  <div class='caption'>
                      <h4 class='pull-right'><?php echo $price; ?> din</h4>
                      <h4><a href='/details.php?id=<?php echo $id; ?>'><?php echo $name; ?></a>
                      <?php if ( isset($_SESSION['id_korisnik']) ): ?>
                        <br/>
                        <a 
                          class="btn btn-primary"
                          href="/utilities/add_to_cart.php?kategorija=<?php echo $categorie; ?>&id=<?php echo $id; ?>&strana=<?php echo $page; ?>">Dodaj u korpu</a>
                      <?php endif ?>
                      </h4>
                      <p><?php echo $desc; ?></p>
                  </div>
                  <div class='ratings'>
                      <p class='pull-right'><?php echo $numberOfComments; ?> komentara</p>
                      <p><?php echo $numberOfVotes; ?> glasova</p>
                  </div>
                </div>
              </div>
            <?php endwhile ?>
          <?php endif ?>
        </div>  
      </div>
      <?php if ( $paginateHTML != '' ): ?>
        <div class="row">
          <div class="col-md-12" id="pagination">
          <?php echo $paginateHTML; ?>
          </div>
        </div>
      <?php endif ?>
    </div>

    <?php include_once PARTIALS . '/footer.php' ?>
  </body>
</html>