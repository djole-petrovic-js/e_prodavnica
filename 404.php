<?php
    require_once 'config/connection.php';

    session_start();
?>
<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME; ?> | Uloguj se</title>
    <?php include_once(PARTIALS . 'meta.php') ?>
  </head>
  <body>
    <?php include_once(PARTIALS . 'navigation.php') ?>

    <div class="container container-min-height">
        <div class="row">
            <div class="col-md-12">
                <h1>Stranica koju su zatrazili nije nadjena ili ne postoji.</h1>
            </div>
        </div>

    </div>
  <?php include_once PARTIALS . '/footer.php' ?>
  </body>
</html>