<?php
    $title = "TV Shows";
    require_once "./include/header.inc.php";
    include "./include/fonction.inc.php";
?>
<aside class="filter">
    <h2>Table of genre:</h2>
    <fieldset>
        <legend>Genre</legend>
        <form action="traitement.php" method="get">
            <nav>
                <ul>
                    <li>
                        <label for = "action">Action</label>
                        <input name = "action" type = "checkbox" id = "action" value = "acces"/>
                    </li>
                    <li>
                        <label for = "adventure">Adventure</label>
                        <input name = "adventure" type = "checkbox" id = "adventure" value = "acces"/>
                    </li>
                    <li>
                        <label for = "animated">Animated</label>
                        <input name = "animated" type = "checkbox" id = "animated" value = "acces"/>
                    </li>
				    <li>
                        <label for = "romance">Romance</label>
                        <input name = "romance" type = "checkbox" id = "romance" value = "acces"/>
                    </li>
				    <li>
                        <label for = "fantasy">Fantasy</label>
                        <input name = "fantasy" type = "checkbox" id = "fantasy" value = "acces"/>
                    </li>
				    <li>
                        <label for = "comedy">Comedy</label>
                        <input name = "comedy" type = "checkbox" id = "comedy" value = "acces"/>
                    </li>    
				    <li>
                        <label for = "drama">Drama</label>
                        <input name = "drama" type = "checkbox" id = "drama" value = "acces"/>
                    </li>
				    <li>
                        <label for = "horror">Horror</label>
                        <input name = "horror" type = "checkbox" id = "horror" value = "acces"/>
                    </li>
                    <li>
                        <label for = "historical">Historical</label>
                        <input name = "historical" type = "checkbox" id = "historical" value = "acces"/>
                    </li>
                    <li>
                        <label for = "thriller">Thriller</label>
                        <input name = "thriller" type = "checkbox" id = "thriller" value = "acces"/>
                    </li>
			        <li>
                        <label for = "western">Western</label>
                        <input name = "western" type = "checkbox" id = "western" value = "acces"/>
                    </li>
                </ul>
                <input type="submit" value="valid" />
            </nav>
        </form>
    </fieldset>
</aside>
<section class="formulaire">
    <h2>News</h2>
    <?php
        echo writeNewSeries();
    ?>
</section>
<section class="formulaire">
    <h2>Top TV shows</h2>
    <?php
        echo writeTopSeries() ;
    ?>
    
</section>
<?php
    require "./include/footer.inc.php";
?>