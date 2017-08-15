<?php
  require_once('config/connection.php');

  session_start();

  $sql = "
    SELECT *
    FROM anketa as a INNER JOIN opcije as o
    ON a.id_anketa = o.id_anketa
    WHERE a.vidljivost = 1
  ";

  $poll = mysqli_query($connection,$sql);
  $pollHTML = '';
  $optionsHTML = '';
  $pollNameHTML = '';

  if ( mysqli_num_rows($poll) > 0 ) {
    while ( $row = mysqli_fetch_array($poll) ) {
        $id = $row['id_opcija'];
        $label = $row['opcija'];

        $pollNameHTML = $row['ime'];

        $optionsHTML .= "
            <li class='list-group-item'>
                <div class='checkbox'>
                    <label>
                        <input name='poll' type='radio' value='$id'> $label
                    </label>
                </div>
            </li>
        ";
    }

    $pollHTML .= "<div class='panel panel-primary'>
            <div class='panel-heading'>
                <h3 class='panel-title'><span class='fa fa-line-chart'></span> $pollNameHTML</h3>
            </div>
            <div class='panel-body'>
                <ul class='list-group' id='pollUL'>
                    $optionsHTML
                </ul>
            </div>
            <div class='panel-footer text-center'>
                <button id='btnVote' type='button' class='btn btn-primary btn-block btn-sm'>
                    Glasaj!
                </button>
                <button class='btn btn-primary btn-block btn-sm' id='btnShowVotes' class='small'>
                    Pogledajte rezultate
                </button>
            </div>
        </div>";
  }  

  $sql = "
    SELECT p.id_proizvodi, p.naziv as pNaziv , p.cena,p.kolicina,p.datum,p.broj_lajkova,
    p.broj_dislajkova,p.broj_komentara,p.opis,p.slika,k.naziv as kNaziv,k.id_kategorije
    FROM proizvodi as p INNER JOIN kategorije as k
    ON p.id_kategorija = k.id_kategorije
    ORDER BY p.datum DESC
    LIMIT 6
  ";

  $res = mysqli_query($connection,$sql);

  $productsHTML = '';

  while ( $row = mysqli_fetch_array($res) ) {
    $id = $row['id_proizvodi'];
    $name = $row['pNaziv'];
    $img = $row['slika'];
    $price = $row['cena'];
    $desc = substr($row['opis'],0,60) . '...';
    $numberOfVotes = (int)$row['broj_lajkova'] + (int)$row['broj_dislajkova'];
    $numberOfComments = $row['broj_komentara'];

    $addToCartButton = isset($_SESSION['id_korisnik'])
        ? "<a href='/utilities/add_to_cart.php?id=$id' class='btn btn-primary block'>Dodaj u korpu</a>"
        : "";

    $productsHTML .= "
        <div class='col-sm-4 col-lg-4 col-md-4 products'>
            <div class='thumbnail'>
                <a href='/details.php?id=$id'><img src='/slike/$img' alt=''></a>
                <div class='caption'>
                    <h4 class='pull-right'>$price RSD</h4>
                    <h4><a href='/details.php?id=$id'>$name</a></h4>
                        $addToCartButton
                    <p>$desc</p>
                </div>
                <div class='ratings'>
                    <p class='pull-right'>$numberOfComments komentara</p>
                    <p>
                        $numberOfVotes glasova
                    </p>
                </div>
            </div>
        </div>
    ";
  }

  $sql = "SELECT * FROM kategorije";

  $categories = mysqli_query($connection,$sql);
?>
<!DOCTYPE html>
<html lang="sr">
  <head>
    <title><?php echo SITE_NAME ?> | Pocetna</title>
    <?php include_once PARTIALS . 'meta.php' ?>
  </head>
  <body>
    <?php include_once PARTIALS . 'navigation.php' ?>
    
    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <h1 class="lead product-title text-success">Prodaja Video Igara</h1>
                <div class="list-group">
                    <a href='/categories.php' class='list-group-item'>Sve kategorije</a>
                    <?php while ( $row = mysqli_fetch_array($categories) ): ?>
                        <?php
                            $id = $row['id_kategorije'];
                            $categorie = $row['naziv'];
                        ?>
                        <a href='/categories.php?kategorija=<?php echo $id; ?>' 
                        class='list-group-item'>
                        <?php echo $categorie; ?></a>
                    <?php endwhile; ?>
                </div>
                <?php echo $pollHTML; ?>
            </div>
            <div class="col-md-9">

                <div class="row carousel-holder">

                    <div class="col-md-11">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img class="slide-image" src="/cover/1.jpg" alt="">
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="/cover/2.jpg" alt="">
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="/cover/3.jpg" alt="">
                                </div>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>

                </div>
                <br/>

                <div class="row">
                    <?php 
                        if ( mysqli_num_rows($res) == 0 ) {
                            echo "
                                <br>
                                <div class='alert alert-info'>
                                    <p>Nista nije nadjeno...</p>
                                </div>
                            ";
                        }
                    ?>
                    <?php echo $productsHTML ?>
                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->

    <?php include_once PARTIALS . '/footer.php' ?>

  </body>
  <script src="/static/js/index_client.js"></script>
</html>