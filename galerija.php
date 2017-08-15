<?php
  require_once 'config/connection.php';

  session_start();

  $productID = isset($_GET['id']) ? $_GET['id'] : 1;

  $sql = "
    SELECT s.putanja,s.naziv_slike,p.slika,p.naziv
    FROM slike_proizvoda s RIGHT OUTER JOIN proizvodi p
    ON s.id_proizvodi = p.id_proizvodi
    WHERE p.id_proizvodi = $productID
  ";

  $counter = 0;

  $result = mysqli_query($connection,$sql);
?>
<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME; ?> | Galerija</title>
    <?php include_once PARTIALS . 'meta.php' ?>
    <link rel="stylesheet" type="text/css" href="/static/css/lightbox.min.css">
    <script src="/static/js/lightbox.js"></script>
  </head>
  <body>
    <?php include_once PARTIALS . 'navigation.php' ?>

    <div class="container container-min-height" id="wreaper">
      <div class="row">
        <div class="col-md-12">
          <h1>Galerija</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
        <?php if ( mysqli_num_rows($result) == 0 ): ?>
          <h3>Trenutno nema fotografija za izabrani proizvod...</h3>
        <?php else: ?>
          <?php while ( $row = mysqli_fetch_array($result) ): ?>
            <?php 
              $counter++;
            ?>
            <?php if ( $counter == 1 ): ?>
              <div class="images-wreaper">
                <a href='<?php echo "/slike/" . $row['slika'] ; ?>'
                   data-lightbox='image-<?php echo $counter; ?>' 
                   data-title='<?php echo $row['naziv']; ?>'>
                   <img src='<?php echo "/slike/" . $row['slika'] ; ?>' alt=''>
                </a>
              </div>
              <?php $counter++; ?>
            <?php endif ?>
            <?php if ( isset($row['putanja']) ): ?>
            <?php
              $image = $row['putanja'];
              $title = $row['naziv_slike'];
            ?>
            <div class="images-wreaper">
              <a href='<?php echo $image; ?>' 
                 data-lightbox='image-<?php echo $counter; ?>' 
                 data-title='<?php echo $title; ?>'>
                 <img src='<?php echo $image; ?>' alt=''>
               </a>
            </div>
            <?php endif ?>
          <?php endwhile ?>
        <?php endif ?>
        </div>  
      </div>
    </div>
    <?php include_once PARTIALS . '/footer.php' ?>
  </body>
</html>