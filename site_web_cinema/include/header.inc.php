<?php
//if (!empty ($_SERVER['REMOTE_ADDR']){
    setcookie ('utilisateur' , $_SERVER['REMOTE_ADDR'] , "/") ;
//}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Films Streaming</title>
        <meta charset="utf-8"/>
        <meta name='language' content='FR' />
        <meta name="author" content="HAOUASSI Layssa et ETAME Cedric" />
        <meta name="keywords" content="Films, Séries, Streaming" />
        <meta name="description" content="Site de streaming" />
        <meta name='date' content='April. 07, 2021' />
        <link rel = "stylesheet" href = "bootstrap.min.css"/>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <header>
            <figure>
                <img src="./images/bobine.jpg" alt="logo" />
                <figcaption><em>logo du site</em></figcaption>
            </figure>
            <?php
                echo "<h1><span class=\"badge bg-secondary\">".$title."</span></h1>"
            ?>
            <fieldset>
                <legend>Movie's or TV Show title</legend>
                <form action="traitement.php" method="get">
                <p style = "font-size : 25px ;">
                    <!-- <label for="first">Search:</label> -->
                    <input name="film" type="search"  placeholder = "Search movie or serie" size = "20" id= "first" />
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </p>
                </form>
            </fieldset>
            <nav style = "margin-top : 30px ;" class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class = "container-fluid tata">
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul style = "font-size : 20px;" class="navbar-nav">
                    <li class="nav-item">
                      <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="film.php">Movies</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="serie.php">Series</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="statistique.php">Statistics</a>
                    </li> 
                </ul>
                </div>
            </div>
            </nav>
        </header>