<?php
    require_once 'config/connection.php';

    session_start();
?>
<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME; ?> | O autoru</title>
    <?php include_once PARTIALS . 'meta.php' ?>
    <link rel="stylesheet" type="text/css" href="/static/css/lightbox.min.css">
    <script src="/static/js/lightbox.js"></script>
  </head>
  <body>
    <?php include_once PARTIALS . 'navigation.php' ?>

    <div class="container container-min-height" id="wreaper">
      <div class="row">
        <div class="col-md-12">
          <h1>O autoru</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
            <img src="/images/Djordje-Petrovic.jpg" id="img">
        </div>
        <div class="col-md-6">
            <p id="bio">Ja sam Djordje Petrovic. Rodjen sam u Pirotu, a zivim u Beogradu od 2010-e godine, i imam 24 godina. Trenutno studiram web programiranje na Visokoj ICT skoli u Beogradu. U slobodno vreme volim da gledam filmove , da igram poker, da izlazim itd itd...</p>
        </div>
      </div>
    </div>
    <?php include_once PARTIALS . '/footer.php' ?>
  </body>
</html>