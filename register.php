<?php
  require_once 'config/connection.php';

  session_start();

  $errors     = array();
  $email      = '';
  $password   = '';
  $adress     = '';
  $phone      = '';
  $username   = '';

  $emailRegex  = '/[a-zA-Z0-9\.\?\!]+@[a-z]+(\.[a-z]{2,4})?\.[a-z]{2,4}/';
  $adressRegex = '/[a-zA-Z0-9\.\/]{5,}/';
  $phoneRegex  = '/06[0-9][0-9]{6,7}/';
  $usernameRegex = '/[a-zA-Z0-9.\?\!]{5,15}/';
  
  if ( isset($_POST['register']) ) {
    if ( isset($_POST['email']) && $_POST['email'] != '' ) {
      $email = $_POST['email'];
      $tempEmail = addslashes($email);

      if ( !preg_match($emailRegex,$email) ) {
        $errors[] = 'Email nije u validnom formatu';
      }

      $checkIfEmailExists = "
        SELECT *
        FROM korisnici
        WHERE email = '$tempEmail'
      ";

      $user = mysqli_query($connection,$checkIfEmailExists);

      if ( mysqli_num_rows($user) > 0 ) {
        $errors[] = 'E-mail vec postoji...';
      }
    } else {
      $errors[] = 'Morate da unesete e-mail';
    }

    if ( isset($_POST['password']) && $_POST['password'] != '' ) {
      $password = $_POST['password'];
    } else {
      $errors[] = 'Morate da unesete sifru';
    }

    if ( isset($_POST['adress']) && $_POST['adress'] != '' ) {
      $adress   = $_POST['adress'];

      if ( !preg_match($adressRegex,$adress) ) {
        $errors[] = 'Adresa nije u validnom formatu';
      }
    } else {
      $errors[] = 'Morate da unesete adresu';
    }

    if ( isset($_POST['phone']) && $_POST['phone'] != '' ) {
      $phone = $_POST['phone'];

      if ( !preg_match($phoneRegex, $phone) ) {
        $errors[] = 'Broj telefona nije u validnom formatu';
      }
    } else {
      $errors[] = 'Morate da unesete broj telefona';
    }

    if ( isset($_POST['username']) && $_POST['username'] != '' ) {
      $username = $_POST['username'];

      if ( !preg_match($usernameRegex, $username) ) {
        $errors[] = 'Korisnicko ime nije u validnom formatu';
      }
    } else {
      $errors[] = 'Morate da uneste korisnicko ime';
    }

    if ( count($errors) == 0 ) {
      $hash = password_hash($password,PASSWORD_DEFAULT);

      $sql = "
        INSERT INTO korisnici(email,password,adresa,broj_telefona,korisnicko_ime)
        VALUES (?,?,?,?,?)
      ";

      $stmt = $connection->prepare($sql);
      $stmt->bind_param('sssss',$email,$hash,$adress,$phone,$username);
      $stmt->execute();

      $sql = "
        SELECT *
        FROM korisnici
        WHERE email = '$email'
      ";

      $user = mysqli_query($connection,$sql);

      $u = mysqli_fetch_array($user);

      session_start();

      $_SESSION['id_korisnik'] = $u['id_korisnik'];
      $_SESSION['dozvola'] = $u['dozvola'];
      $_SESSION['email'] = $u['email'];

      header('Location:index.php');
    }
  }
?>

<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME ?> | Registracija</title>
    <?php include_once PARTIALS . 'meta.php' ?>
  </head>
  <body>
    <?php include_once PARTIALS . 'navigation.php' ?>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="alert">
            <h1>Registracija</h1>
            <?php if ( !isset($_SESSION['id_korisnik']) ): ?>
              <h2>Vec imate nalog? <a href="/login.php">Ulogujte se</a></h2>
            <?php endif ?>
          </div>
        </div>      
      </div>
      <div class="row">
        <div class="col-md-6">
          <form action="/register.php" method="POST">
            <div class="form-group">
              <label for="username">Korisnicko ime</label>
              <input type="text" name="username" placeholder="Korisniko ime..." class="form-control">
            </div>
            <div class="form-group">
              <label for="email">E-Mail</label>
              <input type="text" name="email" class="form-control" placeholder="E-Mail...">
            </div>
            <div class="form-group">
              <label for="password">Sifra</label>
              <input type="password" name="password" class="form-control" placeholder="Sifra...">
            </div>
            <div class="form-group">
              <label for="adress">Adresa</label>
              <input type="text" name="adress" class="form-control" placeholder="Adresa...">
            </div>
            <div class="form-group">
              <label for="adress">Broj Telefona</label>
              <input type="text" name="phone" class="form-control" placeholder="Broj Telefona...">
            </div>
            <button class="btn btn-primary" type="submit" name='register'>Registruj se</button>
          </form>
        </div>
        <?php if ( isset($_POST['register']) && count($errors > 0) ): ?>
        <div class="col-md-6">
          <h3>Doslo je do greske prilikom registracije vaseg naloga , molimo vas da probate ponovo...</h3>
          <ul class="list-group">
          <?php foreach ( $errors as $e ): ?>
            <li class='list-group-item'><?php echo $e; ?></li>
          <?php endforeach ?>
          </ul>
        </div>
        <?php endif ?>
      </div>
    </div>
    <?php include_once PARTIALS . 'footer.php' ?>
  </body>
</html>