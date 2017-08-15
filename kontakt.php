<?php
  require_once 'config/connection.php';

  session_start();

  $errors = array();
  $emailRegex = '/[a-zA-Z0-9\.\?\!]+@[a-z]+(\.[a-z]{2,4})?\.[a-z]{2,4}/';
  $messageRegex = '/[a-zA-Z0-9\s\.\?\!\-_\'\"]{10,100}/';

  if ( isset($_POST['btnSend']) ) {
    $email = '';
    $message = '';

    if ( isset($_POST['email']) && $_POST['email'] != '' ) {
      $email = $_POST['email'];

      if ( !preg_match($emailRegex, $email) ) {
        $errors[] = 'E-mail nije u validnom formatu';
      }
    } else {
      $errors[] = 'Niste uneli email...';
    }

    if ( isset($_POST['question']) && $_POST['question'] != '' ) {
      $message = trim($_POST['question']);

      if ( !preg_match($messageRegex, $message) ) {
        $errors[] = 'Vas komentar sadrzi nedozvoljene karaktere.Takodje mora biti najmanje 10 karaktera...';
      }
    } else {
      $errors[] = 'Niste nista upisali...';
    }

    if ( count($errors) == 0) {
      $mailIsSent = false;
      $headers = "From: djordje.petrovic.6.15@ict.edu.rs";

      if ( @mail('djordje.petrovic.6.15@ict.edu.rs', 'Informacije', $message,$headers) ) {
        $mailIsSent = true;
      } else {
        $mailIsSent = false;
      }
    } 
  }
?>
<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME; ?> | Kontakt</title>
    <?php include_once(PARTIALS . 'meta.php') ?>
    <link rel="stylesheet" type="text/css" href="/static/css/lightbox.min.css">
    <script src="/static/js/lightbox.js"></script>
  </head>
  <body>
    <?php include_once PARTIALS . 'navigation.php' ?>

    <div class="container" id="wreaper">
      <div class="row">
        <div class="col-md-12">
          <h1>Kontakt</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <h2>Ukoliko imate bilo kakvih nedoumica ili vam je potrebna bilo kakva dodatna informacija, kontaktirajte nas preko forme ispod.
          <?php if ( !isset($_SESSION['id_korisnik']) ): ?>
            Molimo vas da unesete ispravan e-mail da bi vam odgovor uspesno stigao...
          <?php endif ?>
          <?php if ( !isset($_SESSION['id_korisnik']) ): ?>
            <h2>
              Da bi ste bili u mogucnosti da kupujete kod nas , molimo vas da se
              <a href="/register.php">registrujete</a>.Ako vec imate nalog, molimo vas da se
              <a href="/login.php">ulogujete</a>
            </h2>
          <?php endif?>
          </h2>
          <p>Nas telefon : 011/555-333</p>
          <p>Nas e-mail : djordje.petrovic.6.15&#64;ict.edu.rs</p>
        </div>  
      </div>
      <div class="row">
        <div class="col-md-6">
          <form action="/kontakt.php" method="POST">
            <div class="form-group">
              <?php if ( isset($_SESSION['id_korisnik']) ): ?>
                <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
              <?php else: ?>
                <input class="form-control" type="text" name="email" placeholder="Vas email">
              <?php endif ?>
            </div>
            <div class="form-group">
              <textarea id="contact-area" class="form-control" name="question" placeholder="Vase pitanje...">
                
              </textarea>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit" name="btnSend">Posalji</button>
            </div>
          </form>
        </div>
        <?php if ( isset($_POST['btnSend']) && count($errors) > 0 ): ?>
        <div class="col-md-6">
          <ul class="list-group">
            <?php foreach ( $errors as $e ): ?>
              <li class="list-group-item"><?php echo $e; ?></li>
            <?php endforeach ?>
          </ul>
        </div>
        <?php endif ?>
        <?php if ( isset($mailIsSent) ): ?>
          <div class="col-md-6">
          <?php if ( $mailIsSent ):  ?>
            <p>Vasa poruka je uspesno poslata, odgovoricemo sto pre...</p>
          <?php else: ?>
            <h3>Doslo je do greske prilikom slanja maila , molimo vas da pokusate ponovo...</h3>
          <?php endif ?>     
          </div>
        <?php endif ?>
      </div>
    </div>

    <?php include_once PARTIALS . '/footer.php' ?>
  </body>
</html>