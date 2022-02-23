<?php
    require_once "fonction.inc.php" ;
?>
<footer>
    <div class = "container-fluid row">
            <a href="#"><img id="fleche" src="./images/flechehaut.png" alt="fleche"/></a>
            <p class = "col-sm-8">Copyright Layssa HAOUASSI &amp; ETAME Cédric - Tous droits réservés</p>
            <p>Université de Cergy Pontoise</p>
            <?php
                echo "<p class = \"col-sm-4\">Navigateur : ".get_navigateur ()."</p>\n" ;
                echo compteurHit();
            ?>
            <p class = "col-md-4" style = "right : 10% ;">Version du site : <?php echo date ("m.d.y")?></p>
    </div>  
</footer>
</body>
</html>