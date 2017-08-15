<?php
    $sql = "
        SELECT *
        FROM linkovi
        WHERE dozvola = 0
    ";

    if ( !isset($_SESSION['id_korisnik']) ) {
        $sql .= "OR dozvola = 1";
    } else if ( $_SESSION['dozvola'] == 2 ) {
        $sql .= "OR dozvola = 2";
    } else {
        $sql .= "OR dozvola = 2 OR dozvola = 3";
    }

    $sql .= ' ORDER BY dozvola DESC';

    $links = mysqli_query($connection,$sql);

    $linksHTML = '';
    $linksForFooter = array();

    if ( !$links ) {
        echo mysqli_error($connection);
    }

    while ( $row = mysqli_fetch_array($links) ) {
        $name = $row['ime'];
        $link = $row['link'];

        $linksHTML .= "
            <li>
                <a href='$link'>$name</a>
            </li>
        ";

        $linksForFooter[] = "<a class='footer-links' href='$link'>$name</a>";
    }
?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/index.php"><?php echo SITE_NAME; ?></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php echo $linksHTML; ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>