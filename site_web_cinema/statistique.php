<?php
    $title = "Statistics";
    require_once "./include/header.inc.php";
    include "./include/fonction.inc.php";
?>
<section>
    <h2>The most watched movies and TV shows</h2>
    <?php 
            makeStats () ;
    ?>
    <img src = "./images/stats.png" alt = "Histogramme films"/> 
</section>
<?php
    require "./include/footer.inc.php";
?>