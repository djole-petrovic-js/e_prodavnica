<?php
  session_start();

  if ( !isset($_SESSION['id_korisnik']) || $_SESSION['id_korisnik'] != 3 ) {
    header('Location:index.php');
  } else {
    require_once 'config/connection.php';
  }
?>
<!DOCTYPE html>
<html lang="sr" ng-app="admin">
  <head>
    <title><?php echo SITE_NAME; ?> | Admin Panel</title>
    <?php include_once(PARTIALS . 'meta.php') ?>
    <script src="/static/js/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-sanitize.js"></script>
    <script src="/static/js/angular.rout.min.js"></script>
    <script src="/static/js/admin.js"></script>
  </head>
  <body>
    <?php include_once(PARTIALS . 'navigation.php') ?>

    <div class="modal fade" id="informationsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header alert-info">
            <h5 class="modal-title" id="exampleModalLabel">Obavestenje</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>{{ info }}</p>
          </div>
          <div class="modal-footer alert-info">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Admin Panel</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2">
          <ul class="list-group">
            <a href="#proizvodi" class="list-group-item">Proizvodi</a>
            <a href="#kategorije" class="list-group-item">Kategorije</a>
            <a href="#linkovi" class="list-group-item">Linkovi</a>
            <a href="#korisnici" class="list-group-item">Korisnici</a>
            <a href="#anketa" class="list-group-item">Anketa</a>
            <a href="#slike_proizvodi" class="list-group-item">Slike proizvoda</a>
          </ul>
        </div>
        <div class="col-md-10" id="admin-left">
          <ng-view></ng-view>
        </div>
      </div>
    </div>
    <?php include_once PARTIALS . '/footer.php' ?>
  </body>
</html>