<?php
    $title = "AlloMovies";
    require_once "./include/header.inc.php";
    include "./include/fonction.inc.php";
?>
<section>
    <h2>The latest Movie/Tv Show you’ve watched:</h2>
<?php
    
    if (file_exists ("./files/user_".$_SERVER['REMOTE_ADDR'].".csv")){
            echo write3LastUser_search ($_SERVER['REMOTE_ADDR']) ;
    }
    else{
        echo "\t<p>Vous n'avez encore rien vu </p>\n";  
    }
        
?>
</section>
<section>
    <h2>News Movies</h2>
    <?php
        echo writeNewFilms();
    ?>
</section>
<section>
    <h2>News TV Shows</h2>
    <?php
        echo writeNewSeries();
    ?>
</section>
<section>
    <h2>Localisation</h2>
    <?php
        echo ipXML();
    ?>
</section>
<section>
    <h2>Nasa's daily picture</h2>
    <?php
        echo apiNasa();
    ?>
</section>
<?php
    require "./include/footer.inc.php";
?>