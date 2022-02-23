<?php
    $title = "Films Streaming";
    require_once "./include/header.inc.php";
    include "./include/fonction.inc.php";
?>
    <?php
       if(isset($_GET['film']) && !empty($_GET['film'])){
        $film = $_GET['film'] ;
        echo writeResultSearch ($film) ;
    }else if(isset($_GET['id']) && !empty($_GET['id'])){
        $film = $_GET['id'] ;
        echo writeResultSearch_inf ($film) ;
    }
    else if(isset($_GET['action']) || isset($_GET['adventure']) || isset($_GET['animated'])
    || isset($_GET['romance']) || isset($_GET['fantasy']) || isset($_GET['comedy'])
    || isset($_GET['drama']) || isset($_GET['horror']) || isset($_GET['historical'])
    || isset($_GET['thriller']) || isset($_GET['western'])){
        if(isset ($_GET['action'])){
            echo writeMoviesWithgenre("219404","Action");
        }
        if(isset ($_GET['adventure'])){
            echo writeMoviesWithgenre("271361","Adventure");
        }
        if(isset ($_GET['animated'])){
            echo writeMoviesWithgenre("6513","Animated");
        }
        if(isset ($_GET['romance'])){
            echo writeMoviesWithgenre("9840","Romance");
        }
        if(isset ($_GET['fantasy'])){
            echo writeMoviesWithgenre("272512","Fantasy");
        }
        if(isset ($_GET['comedy'])){
            echo writeMoviesWithgenre("272275","Comedy");
        }
        if(isset ($_GET['drama'])){
            echo writeMoviesWithgenre("210351","Drama");
        }
        if(isset ($_GET['horror'])){
            echo writeMoviesWithgenre("8087","Horror");
        }
        if(isset ($_GET['historical'])){
            echo writeMoviesWithgenre("15126","Historical");
        }
        if(isset ($_GET['thriller'])){
            echo writeMoviesWithgenre("9950","Thriller");
        }
        if(isset ($_GET['western'])){
            echo writeMoviesWithgenre("10511","Western");
        }

    }
    else{
        echo "\n<h2>Résultat</h2>\n";
        echo "\t<figure>\n";
        echo "\t\t<img width = '300' src =\"./images/noimage1.png\" alt= \"film\"/></a>\n" ;           
        echo "\t\t<figcaption><em>$title</em></figcaption>\n";
        echo "\t</figure>\n";
        echo "\t\t<p>Genre : UNKNOW</p>\n" ;
        echo "\t\t<p>Actors : UNKNOW</p>\n" ;
        echo "\t\t<p>Writer : UNKNOW</p>\n" ;
        echo "\t\t<p>Awards : UNKNOW</p>\n" ;
        echo "\t\t<p>Type : UNKNOW</p>\n" ;
    }
    ?>
<?php
    require "./include/footer.inc.php";
?>