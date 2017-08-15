<?php
  require_once 'config/connection.php';

  $err = '';

  if ( isset($_POST['login']) ) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = array();

    $sql = 'SELECT * FROM korisnici WHERE email = ?';

    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ( mysqli_num_rows($result) === 0 ) {
      $errors[] = 'Email ili lozinka nisu tacni , pokusajte ponovo...';
    } else {
      $row  = $result->fetch_assoc();
      $hash = $row['password'];
      $isValid = password_verify($password,$hash);

      if ( $isValid == 0 ) {
        $errors[] = 'Email ili lozinka nisu tacni , pokusajte ponovo...';
      } else {
        session_start();

        $_SESSION['id_korisnik'] = $row['id_korisnik'];
        $_SESSION['dozvola'] = $row['dozvola'];
        $_SESSION['email'] = $row['email'];

        header('Location: index.php');
      }
    }

    if ( count($errors) > 0) {
      $err = '<ul class="list-group">';

      foreach ($errors as $e) {
        $err .= "<li class='list-group-item'>$e</li>";
      }

      $err .= '</ul>';
    }
  }

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
          <div class="alert">
            <h1>Uloguj se</h1>
          </div>
        </div>      
      </div>
      <div class="row">
        <div class="col-md-6">
          <?php
            echo $err;
          ?>
          <form action="/login.php" method="POST">
            <div class="form-group">
              <label for="email">E-Mail</label>
              <input type="text" name="email" class="form-control" placeholder="E-Mail...">
            </div>
            <div class="form-group">
              <label for="password">Sifra</label>
              <input type="password" name="password" class="form-control" placeholder="Sifra...">
            </div>
            <button class="btn btn-primary" type="submit" name='login'>Uloguj se</button>
          </form>
        </div>
      </div>
    </div>
    <?php include_once PARTIALS . 'footer.php'; ?>
  </body>
</html>