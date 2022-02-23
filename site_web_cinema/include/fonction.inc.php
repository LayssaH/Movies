<?php
    require "jpgraph/src/jpgraph.php" ;
    require "jpgraph/src/jpgraph_bar.php" ;

        /**
     * Fonction qui affiche l'image (ou la vidéo) du jour de la Nasa.
     * @return string contenant le flux html de l'image de la Nasa.
     */

    function apiNasa() : string {
        $url = "https://api.nasa.gov/planetary/apod?api_key=QHbaPjqzhpEFuJIhwLh0SYDdb2VoQSzGu62oRt2a&date=".date('Y-m-d');
        $json = file_get_contents($url);
        $obj = json_decode($json);
        $str = "";
        if($obj->{'media_type'} == "image"){
            $image = $obj->{'url'};
            $str .= "\n<figure>"."\n".
                "\t<img width='300' src='$image' alt='image aléatoire'/>"."\n".
                "\t<figcaption>\n<em>Image du jour</em>\n</figcaption>"."\n".
                "</figure>"."\n";
        }
        else{
        
            $video = $obj->{'url'};

            $str .= "\n<iframe width=\"420\" height=\"315\" src=\"$video\">";
            $str .= "</iframe>\n";    
        }
        
        return $str;
    }

        /**
     * Fonction qui affiche la localisation de l'utilisateur
     * @return string contenant le flux html des informations de la localisation
     * du client.
     */

    function ipXML() : string {
        $url = "http://www.geoplugin.net/xml.gp?ip=".$_SERVER['REMOTE_ADDR'];
        //$url = "http://www.geoplugin.net/xml.gp?ip=193.54.115.192";
        $xml = simplexml_load_file($url);

        $ville = utf8_decode($xml->geoplugin_city);
        $region = utf8_decode($xml->geoplugin_region);
        $departement = utf8_decode($xml->geoplugin_regionName);
        $pays = utf8_decode($xml->geoplugin_countryName);

        $str ="\n<p> Your country: $pays</p>\n";
        $str .="<p> Your region: $region</p>\n";
        $str .="<p> Your department: $departement</p>\n";
        $str .="<p> Your city: $pays</p>\n";

        return $str;
    }


    /**
     * Fonction qui recherche un film à partir du nom passé en parametre
     * @param string $name est le nom du film à rechercher
     * @return array contenant tous les films correspondants au nom en parametre
     */
    function searchMovies (string $name) : ?array{
        $film = preg_replace('/ /', '+', htmlspecialchars($name)) ;
        $url = "https://api.themoviedb.org/3/search/movie?api_key=4ae1dd658382bb63e5a137f061a508a8&query=".$film."&language=en-US&page=1+page=2&include_adult=false" ;
        $json = file_get_contents ($url) ;
        if ($json != NULL){
            $parsed_json = json_decode ($json , true) ;
        }
        return $parsed_json  ;
    }
     /**
     * Fonction qui recherche un serie à partir du nom passé en parametre
     * @param string $name est le nom du film à rechercher
     * @return array contenant tous les films correspondants au nom en parametre
     */
    function searchSeries (string $name) : ?array{
        $film = preg_replace('/ /', '+', htmlspecialchars($name)) ;
        $url = "https://api.themoviedb.org/3/search/tv?api_key=4ae1dd658382bb63e5a137f061a508a8&query=".$film."&language=en-US&page=1+page=2&include_adult=false" ;
        $json = file_get_contents ($url) ;
        if ($json != NULL){
            $parsed_json = json_decode ($json , true) ;
        }
        return $parsed_json  ;
    }

     /**
     * Fonction qui recherche toutes les infos concernant un film ou serie à partir du nom passé en parametre
     * @param string $name est le nom du film ou serie à trouve
     * @return array contenant toutes les informations pour le film ou la serie trouvée
     */
    
    function getMediaIntels (string  $name) : ?array{
        $film= str_replace (' ', '+', htmlspecialchars($name));
        $url = "http://www.omdbapi.com/?apikey=edac5edc&t=$film";      
        $json = file_get_contents($url);
        if ($json != NULL){
            $parsed_json = json_decode($json ,true);
        }
        return $parsed_json ;
    }

    /**
     * Fonction qui recherche toutes les infos concernant un film ou serie à partir de l'ID d'un film/série
     * @param string $id est l'identifiant d'un film ou d'une série
     * @return array contenant toutes les informations pour le film ou la serie trouvée.
     */

    function getMediaIntels_id (string  $id) : ?array{
        $url = "http://www.omdbapi.com/?apikey=edac5edc&i=$id";       
        $json = file_get_contents($url);
        if ($json != NULL){
            $parsed_json = json_decode($json ,true);
        }
        return $parsed_json ;
    }
     /**
     * Fonction qui renvoie qui renvoit un le flux html pour la presentation des resultats de la recherche de l'internaute
     * Elle fait appelle à @uses searchSeries et searchMovies
     * @param string $name est le nom du film à rechercher
     * @return string qui est le flux html du resultat de la recherche
     */
    function writeResultSearch (string $name) : string{
        /*On recupere les resusltats de la recherche pour les films */
        $movie = searchMovies ($name) ;
        /* On reucpere les resultats de la recherche pour les series */
        $serie = searchSeries ($name) ;
        $allFilm = array () ;
        $allSerie = array () ;
        $para = "\n<section>\n" ;
        $para .= "\t<h2>Résultats pour Films</h2>\n" ;
        /*On recupere tous les films contenus dans le tableau "results"*/
        $allFilm = $movie ['results'] ;
        /*Pour chacun de ces films on construit son flux html avec toutes infos necessaires*/
        foreach ($allFilm as $film){
            $intels = getMediaIntels ($film ['original_title']) ;

            $id = $intels['imdbID'];
            $titre = $film ['original_title'];
            $image = "https://image.tmdb.org/t/p/w780/".$film['poster_path'];
            if($image == "https://image.tmdb.org/t/p/w780/"){
                $image = "./images/noimage1.png";
            }
            $para .= "\n<div class=\"divImage\">\n" ;
            $para .= "\t<figure>\n" ;
            $para .= "\t\t<a href=\"traitement.php?id=$id\"><img width = '300' src = \"$image\" alt= \"".$film ['poster_path']."\"/></a>\n" ;
            $para .= "\t\t<figcaption><em>".$titre."</em></figcaption>\n" ;
            $para .= "\t\t</figure>\n" ; 
            $para .= "</div>\n" ;          
        }
        $para .= "</section>\n" ;
        $para .= "<section class=\"traitement\">\n" ;
        $para .= "\t<h2>Résultats pour Séries</h2>\n" ;

        /* On recupere toutes les series contenues dans le tableau "results"*/
        
        $allSerie = $serie ['results'] ;
        /*Pour chacun de ces series on construit son flux html avec toutes infos necessaires*/
        foreach ($allSerie as $show){
                $intels = getMediaIntels($show['original_name']) ;

                $titre = $show['original_name'];
                $id = $intels['imdbID'];
                $image = "https://image.tmdb.org/t/p/w780/".$show ['poster_path'];
                if($image == "https://image.tmdb.org/t/p/w780/"){
                    $image = "./images/noimage1.png";
                }
                $para .= "\n<div class=\"divImage\">\n" ;
                $para .= "\t<figure>\n" ;
                $para .= "\t\t<a href=\"traitement.php?id=$id\"><img width = '300' src = \"$image\" alt= \"".$show ['poster_path']."\"/></a>\n" ;
                $para .= "\t\t<figcaption><em>".$show ['original_name']."</em></figcaption>\n" ;
                $para .= "\t</figure>\n" ;
                $para .= "</div>\n" ;
            }
            $para .= "</section>\n" ;
        return $para ;
    }

    /**
     * Fonction qui renvoie qui renvoit un le flux html correspondant aux détails 
     * d'un film/série.
     * Elle fait appelle à @uses getMediaIntels_id
     * @param string $id est l'identifiant du film ou de la série désiré(e).
     * @return string qui est le flux html des informations du film/série.
     */

    function writeResultSearch_inf (string $id) : string{
        $movie = getMediaIntels_id ($id) ;

        $title = htmlspecialchars($movie['Title']);

        stockage ($title , $id) ;

        $image = $movie['Poster'];

        $str = "\n\t<h2>$title</h2>\n";
        $str .= "\t<figure>\n";
        $str .= "\t\t<img width = '300' src =\"$image\" alt= \"film\"/>\n" ;           
        $str .= "\t\t<figcaption><em>$title</em></figcaption>\n";
        $str .= "\t</figure>\n";

        if ($movie['Response'] !== "False"){ 
            $director = htmlspecialchars($movie['Director']);
            $writer = htmlspecialchars($movie['Writer']);
            $actors = htmlspecialchars($movie['Actors']);
            $image = htmlspecialchars($movie['Poster']);
            $date = htmlspecialchars($movie['Released']);
            $overview = htmlspecialchars($movie['Plot']);
            $genre = htmlspecialchars($movie['Genre']);
            $awards = htmlspecialchars($movie['Awards']);
            $type = htmlspecialchars($movie['Type']);

            $str .= "\t<p>Director: $director</p>\n";
            $str .= "\t<p>Writer: $writer</p>\n";
            $str .= "\t<p>Date of Released: $date</p>\n";
            $str .= "\t<p>Synopsis: $overview</p>\n" ;
            $str .= "\t<p>Actors: $actors</p>\n";
            $str .= "\t<p>Genre: $genre</p>\n";
            $str .= "\t<p>Awards: $awards</p>\n";
            $str .= "\t<p>Type: $type</p>\n";

            if ($movie['Type'] != "movie"){
                $para .= "\t<p>Total Seasons : ".$movie['totalSeasons']."</p>\n" ;
            }
        }
        else{
            $str .= "\t\t<p>Genre : UNKNOW</p>\n" ;
            $str .= "\t\t<p>Actors : UNKNOW</p>\n" ;
            $str .= "\t\t<p>Writer : UNKNOW</p>\n" ;
            $str .= "\t\t<p>Awards : UNKNOW</p>\n" ;
            $str .= "\t\t<p>Type : UNKNOW</p>\n" ;
        }
        return $str;
    }
///////////////////////////////////SERIE////////////////////////////


     /**
     * Fonction qui retourne les 12 meilleurs series du moment
     * @return array contenant les 12 meilleures series du moment
     */
    function getTopSeries () : ?array{
        $curl = curl_init("https://api.themoviedb.org/3/tv/popular?api_key=4ae1dd658382bb63e5a137f061a508a8&language=en-US&page=1");
        curl_setopt ($curl , CURLOPT_CAINFO , __DIR__. DIRECTORY_SEPARATOR ."certificat.cer") ;
        curl_setopt ($curl , CURLOPT_RETURNTRANSFER , true) ;
        curl_setopt ($curl , CURLOPT_TIMEOUT , 1) ;
        
         
        $data = curl_exec ($curl) ;
        if ($data === false){
            var_dump (curl_error ($curl)) ;
        }
        else{
            $data = json_decode ($data , true) ;
        }
        curl_close ($curl) ;
        for ($i = 0 ; $i < 12 ; $i++){
            $response [] = $data ['results'] [$i] ;
        }
        return $response ;
    }

     /**
     * Fonction qui renvoie le flux html pour la presentation des 12 meilleures series du moment
     * Elle fait appelle à @uses getTopSeries
     * @return string qui est le flux html des 12 meilleures series du moment
     */
    function writeTopSeries () : string{
        $data = getTopSeries () ;
        $para = "" ;
        foreach ($data as $serie){
            $intels = getMediaIntels ($serie ['original_name']) ;

            $id = $intels['imdbID'];
            $titre = $intels ['Title'];
            $image = $intels['Poster']; 
            
            if($image != ""){
                $para .= "\t<div class=\"divImage\">\n" ;
                $para .= "\t\t<figure>\n" ;
                $para .= "\t\t\t<a href=\"traitement.php?id=$id\"><img width = '200' src = \"$image\" alt= \"".$titre."\"/></a>\n" ;
                $para .= "\t\t\t<figcaption><em>".$titre."</em></figcaption>\n" ;
                $para .= "\t\t\t</figure>\n" ; 
                $para .= "\t</div>\n" ;
            }
        }
        return $para ;
    }

    /**
    * Fonction qui renvoie grâce au fichier JSON les nouvelles séries du moment.
    * @return array qui contient les nouvelles séries du moment.
    */
    function getNewSeries () : ?array{
        $curl = curl_init ("https://api.themoviedb.org/3/tv/on_the_air?api_key=4ae1dd658382bb63e5a137f061a508a8&language=en-US&page=1") ;
        curl_setopt ($curl , CURLOPT_CAINFO , __DIR__. DIRECTORY_SEPARATOR .'certificat.cer') ;
        curl_setopt ($curl , CURLOPT_RETURNTRANSFER , true) ;
        curl_setopt ($curl , CURLOPT_TIMEOUT , 1) ;
 
        $data = curl_exec ($curl) ;
        if ($data === false){
            var_dump (curl_error ($curl)) ;
        }
        else{
            $data = json_decode ($data , true) ;
        }
        curl_close ($curl) ;
        for ($i = 0 ; $i < 12 ; $i++){
            $response [] = $data ["results"] [$i] ;
        }
        return $response ;
    }

    /**
    * Fonction qui affiche les nouvelles séries du moment.
    * Elle fait appelle à @uses getNewSeries
    * @return string qui contient le flux HTML affichant à la suite les images
    *et les titres des nouvelles séries.
    */

    function writeNewSeries () : string{
        $data = getNewSeries () ;
        $para = "" ;
        foreach ($data as $serie){
            $intels = getMediaIntels ($serie ['original_name']) ;

            $id = $intels['imdbID'];
            $titre = $intels ['Title'];
            $image = $intels['Poster']; 

            if($image != ""){
                $para .= "\t<div class=\"divImage\">\n" ;
                $para .= "\t\t<figure>\n" ;
                $para .= "\t\t\t<a href=\"traitement.php?id=$id\"><img width = '200' src = \"$image\" alt= \"".$titre."\"/></a>\n" ;
                $para .= "\t\t\t<figcaption><em>".$titre."</em></figcaption>\n" ;
                $para .= "\t\t\t</figure>\n" ; 
                $para .= "\t</div>\n" ;
            }
        }     
        return $para ;
    }

 ///////////////////////////////////FILMS////////////////////////////

    /**
     * Fonction qui renvoie les informations principales d'un film ou d'une série.
     * @return array qui contient les différentes informations sur le film ou la série en question.
     */

    function getIntels () : ?array{
        $curl = curl_init ("https://api.themoviedb.org/3/11/images?api_key=4ae1dd658382bb63e5a137f061a508a8&language=en-US") ;
        curl_setopt ($curl , CURLOPT_CAINFO , __DIR__. DIRECTORY_SEPARATOR .'certificat.cer') ;
        curl_setopt ($curl , CURLOPT_RETURNTRANSFER , true) ;
        curl_setopt ($curl , CURLOPT_TIMEOUT , 1) ;
 
        $data = curl_exec ($curl) ;
        curl_close ($curl) ;
        return json_decode ($data , true) ;
    }

    /**
     * Fonction qui recupère les les tops films du moment dans l'api et le résultat sous forme de tableau.
     * @return array qui contient tous les meilleurs films du moments disponible sur l'api.
     */

    function getTop () : ?array{
        $curl = curl_init ("https://api.themoviedb.org/3/movie/popular?api_key=4ae1dd658382bb63e5a137f061a508a8&language=en-US&page=1") ;
        curl_setopt ($curl , CURLOPT_CAINFO , __DIR__. DIRECTORY_SEPARATOR .'certificat.cer') ;
        curl_setopt ($curl , CURLOPT_RETURNTRANSFER , true) ;
        curl_setopt ($curl , CURLOPT_TIMEOUT , 1) ;
        $data = curl_exec ($curl) ;
     
        if ($data === false){
            var_dump (curl_error ($curl)) ;
        }
        else{
            $data = json_decode ($data , true) ;
        }
        curl_close ($curl) ;
        return $data ;
     }

     /**
     * Fonction qui renvoie le le flux HTML qui présentera les 12 meileurs films du moment.
     * @return string qui est le flux html en question.
     */

     function writeTopFilms () : string{
        $new = getTop () ;
        $allFilm = array () ;
        $allFilm [] = $new ['results'] [0] ;
        $allFilm [] = $new ['results'] [1] ;
        $allFilm [] = $new ['results'] [2] ;
        $allFilm [] = $new ['results'] [3] ;
        $allFilm []=  $new ['results'] [4] ;
        $allFilm [] = $new ['results'] [5] ;
        $allFilm [] = $new ['results'] [6] ;
        $allFilm [] = $new ['results'] [7] ;  
        $allfilm [] = $new ['results'] [8] ;
        $allfilm [] = $new ['results'] [9] ;
        $allfilm [] = $new ['results'] [10] ;
        $allfilm [] = $new ['results'] [11] ;
        $allfilm [] = $new ['results'] [12] ;
   
        $para = "" ;
        foreach ($allFilm as $film){
            
            $intels = getMediaIntels ($film ['title']) ;

            $id = $intels['imdbID'];
            $titre = $intels ['Title'];
            $image = $intels['Poster'];

            if($image != ""){
                $para .= "\t<div class=\"divImage\">\n" ;
                $para .= "\t\t<figure>\n" ;
                $para .= "\t\t\t<a href=\"traitement.php?id=$id\"><img width = '200' src = \"$image\" alt= \"".$titre."\"/></a>\n" ;
                $para .= "\t\t\t<figcaption><em>".$titre."</em></figcaption>\n" ;
                $para .= "\t\t\t</figure>\n" ;
                $para .= "\t</div>\n" ;
            }
        }
        return $para ;
    }

    /**
     * Fonction qui recupère les nouveaux films du moment sur l'api et les renvoie sur forme de tableau.
     * @return array qui contient les nouveautés films présents sur l'api.
     */

    function getNew () : ?array{
        $curl = curl_init ("https://api.themoviedb.org/3/movie/upcoming?api_key=4ae1dd658382bb63e5a137f061a508a8&language=en-US") ;
        curl_setopt ($curl , CURLOPT_CAINFO , __DIR__. DIRECTORY_SEPARATOR .'certificat.cer') ;
        curl_setopt ($curl , CURLOPT_RETURNTRANSFER , true) ;
        curl_setopt ($curl , CURLOPT_TIMEOUT , 5) ;
        $data = curl_exec ($curl) ;

        if ($data === false){
            var_dump (curl_error ($curl)) ;
        }
        else{
            $data = json_decode ($data , true) ;
        }
        curl_close ($curl) ;
        return $data ;
    }


    /**
     * Fonction qui renvoie le flux HTML qui présentera les 12 nouveaux films du moments présents sur l'api.
     * Cette fonction utilise @uses getMediaIntels afin de récupérer les informations sur un film.
     * @return string qui est le flux HTML qui présentera les nouveautés films.
     */

    function writeNewFilms () : string{
        $new = getNew () ;
        $allFilm = array () ;
        $para = "" ;
        for ($i = 0 ; $i < 12 ; $i++){
           $allFilm [] = $new ['results'] [$i] ;
        }
        foreach ($allFilm as $film){ 
            $intels = getMediaIntels ($film ['original_title']) ;

            $id = $intels['imdbID'];
            $titre = $intels ['Title'];
            $image = $intels['Poster'];

            if($image != ""){
                $para .= "\t<div class=\"divImage\">\n" ;
                $para .= "\t\t<figure>\n" ;
                $para .= "\t\t\t<a href=\"traitement.php?id=$id\"><img width = '200' src = \"$image\" alt= \"".$titre."\"/></a>\n" ;
                $para .= "\t\t\t<figcaption><em>".$titre."</em></figcaption>\n" ;
                $para .= "\t\t\t</figure>\n" ;
            
            $para .= "\t</div>\n" ;
            }
        }
        return $para ;
    }

    /**
     * Fonction qui récupère les films correspondant à un genre particulier.
     * @param string $genre qui le genre en question dont on veut avoir les films correspondants.
     * @return array qui contient les films correspondant les au genre précisé.
     */
        
    function searchMoviesWithgenre (string $genre) : ?array{
        $genre = preg_replace('/ /', '+', htmlspecialchars($genre)) ;
        $url = "https://api.themoviedb.org/3/keyword/$genre/movies?api_key=4ae1dd658382bb63e5a137f061a508a8&language=en-US&include_adult=false" ;
        $json = file_get_contents ($url) ;
        if ($json != NULL){
            $parsed_json = json_decode ($json , true) ;
        }
        return $parsed_json  ;
    }

    /**
     * Fonction qui renvoie le flux HTML qui présentera les films correspondants à un genre particulier. 
     * @param string $idgenre qui est l'id d'un genre dans l'api et @param string $genre est le genre dont on cherche les 
     * films correspondants.
     * Cette fonction utilise @uses searchMoviesWithgenre afin de récupérer les films correspondants à un genre dans un tableau.
     * Elle utilise également @uses getMediaIntels afin de récupérer les principales informations pour un film x.
     * @return string qui est le flux HTML qui présentera les films correspondants au genre précisé.
     */

    function writeMoviesWithgenre(string $idgenre, string $genre) : string{
        $movies_genre = searchMoviesWithgenre($idgenre);
        $allmovies = array();
        $str = "\n<section>\n" ;
        $str .= "<h2>$genre</h2>\n" ;
        for ($i = 0 ; $i < 12 ; $i++){
            $allmovies[] = $movies_genre['results'][$i] ;
        }
        foreach ($allmovies as $movie){ 
            $intels = getMediaIntels ($movie['original_title']) ;

            $id = $intels['imdbID'];
            $titre = $intels ['Title'];
            $image = $intels['Poster'];

            if($image != ""){
                $str .= "\t<div class=\"divImage\">\n" ;
                $str .= "\t\t<figure>\n" ;
                $str .= "\t\t\t<a href=\"traitement.php?id=$id\"><img width = '300' src = \"$image\" alt= \"".$titre."\"/></a>\n" ;
                $str .= "\t\t\t<figcaption><em>".$titre."</em></figcaption>\n" ;
                $str .= "\t\t\t</figure>\n" ;
                $str .= "\t</div>\n" ;
            }
        }
        $str .= "</section>\n" ;
        return $str;
    }
    ///////////////////////////////////STOCKAGE////////////////////////


    /**
     * Fonction qui permet de stocker les sur le seveur les films déjà consultés par les internautes.
     * @param string $film qui est le nom du film à stocker et @param $id qui est l'id du film en question sur l'api.
     * Cette fonction utilise @uses readCsv afin de récupérer le contenu d'un fichier csv sous forme de tableau. 
     * Elle utlise également @uses existsAlready permettant de vérifier si un film x est déjà présent ou non dans le fichier csv en question.
     * Elle utilise aussi @uses UpdateStats permettant de mettre à jour le nombre d'occurence de consultation du film sur le site internet.
     * Elle utilise @uses StockageUser afin de sauvegarder les films consultés par l'internaute si nécessaires. 
     */

    function stockage (string $film , string $id){
            $counter_file = fopen('./files/film.csv', "a+") ;
            $i = 1 ;
            $file = readCsv ($counter_file) ;
            $index = existsAlready ($file , $film) ;
            if ($index !== -1){
                $counter = updateStats ($file , $index , 1) ;
                $file [$index] [0] = $counter [$index] [0] ;
                $file [$index] [1] = $counter [$index] [1] ;
                $_file = fopen ('./files/film.csv' , "w+") ;
                for ($i = 0 ; $i < count ($file) ; $i++){
                    $counter1 = $file[$i] [0].";".$file [$i] [1];
                    //fputs($counter_file, $counter1);
                    fputcsv ($_file , array ($file[$i] [0].";".$file [$i] [1]) , ";") ;
                }
                fclose ($_file) ;
            }
            else{
                $counter = array ($film.";".$i) ;
                //$counter = $film.";".$i ;
                //fputs($counter_file, $counter);
                fputcsv ($counter_file , $counter , ";") ;
            }
            fclose ($counter_file) ;    
        if (!empty ($_COOKIE ['utilisateur'])){
            stockageUser ($film , $_COOKIE ['utilisateur'] , $id) ;
        }
        return "done" ;
    }

    /**
     * Fonction qui permet d'enregistrer les films consultés par un internaute qui contient si ce dernier 
     * s'est connecté sur le site internet.
     * @param string film est le nom du film en question.
     * @param $user est le nom de cet internaute.
     * @param string $id l'id ddu film dans l'api.
     * @uses readCsv afin de récupérer le contenu d'un fichier csv sous forme de tableau.
     * @uses existsAlready permettant de vérifier si un film est déjà présent dans le fichier csv ou pas.
     * @uses updateStats permet de mettre à jour le nombre d'occurrence de consultation du film dans sur le site internet.
     */

    function stockageUser (string $film , $user , string $id){
        $counter_file = fopen("./files/user_".$user.".csv" , "a+") ;
        $i = 1 ;
        $file = readCsv ($counter_file) ;
        $index = existsAlready ($file , $film) ;
        if ($index !== -1){
            $counter = updateStats ($file , $index , 1) ;
            $file [$index] [0] = $counter [$index] [0] ;
            $file [$index] [1] = $counter [$index] [1] ;
            $_file = fopen ("./files/user_".$user.".csv" , "w+") ;
            for ($i = 0 ; $i < count ($file) ; $i++){
                fputcsv ($_file , array ($file[$i] [0].";".$file [$i] [1].";".$user.";".$file [$i] [3].";".date('l jS \of F Y')) , ";") ;
            }
            fclose ($_file) ;
        }
        else{
            $counter = array ($film.";".$i.";".$user.";".$id.";".date('l jS \of F Y')) ;
            fputcsv ($counter_file , $counter , ";") ;
        }
        fclose ($counter_file) ;
        return "done" ;
    }

    /**
     * Fonction qui permet de vérifier si un film est déjà présent dans un fichier csv.
     * @param array $line qui est le tableau qui contient le contenu d'un fichier csv.
     * @param string film qui est le nom du film à rechercher.
     */

    function existsAlready (array $line , string $film){
        for ($i = 0 ; $i < count ($line) ; $i++){
            if ($line [$i] [0] === $film){
                return $i ;
            }
        }
        return -1 ;
    }

    /**
     * Fonction qui met à jour le nombre d'occurrence de consultation d'un film sur le site internet.
     * @param array $line est tableau qui contient le contenu d'un fichier csv.
     * @param int $index1 est l'indice auquel se trouve le film qui nous interresse.
     * @param $index2 est l'index auquel se trouve le film le nombre d'occurrence de consultation du film sur le site internet.
     * @return array qui contient le contenu du fichier csv mis à jour.
     */
    
    function updateStats (array $line , int $index1 , $index2) : ?array{
        $count = $line [$index1] [$index2] ;
        $count = $count + 1 ;
        $line [$index1] [$index2] = $count ;
        return $line ;
    }

    /**
     * Cette Fonction permet d'ouvrir le contenu d'un fichier csv grâce à sa ressource.
     * @param $stream est la ressource permettant d'ouvrir le fichier.
     */

    function readCsv ($stream){
        while (!feof ($stream)){
            $line [] = fgetcsv ($stream , 1024) ;
        }
        $all = array () ;
        for ($i = 0 ; $i < count ($line) ; $i++){
            if (is_array ($line [$i])){
                for ($j = 0 ; $j < count ($line [$i]) ; $j++){
                    $all [] = explode (";" , $line [$i] [$j]) ;
                }
            }
        }
        return $all ;

    }

    /**
     * Fonction qui permet de lire un fichier csv à partir de ce nom ou de son chemin absolu.
     * @param string $file est le nom ou le chemin absolu du fichier à ouvrir.
     */

    function readsv (string $file){
        if (($handle = fopen ($file , "r")) !== FALSE){
            while (!feof ($handle)){
                $line [] = fgetcsv ($handle , 1024) ;
            }
        }
        $all = array () ;
        for ($i = 0 ; $i < count ($line) ; $i++){
            if (is_array ($line [$i])){
                    $all []= explode (";" , $line [$i] [0]) ;
            }
        }
        fclose ($handle) ;
        return $all ;
    }

    /**
     * Fonction qui permet de renvoyer le flux HTML permettant de présenter les 3 derniers films consultés par l'internaute.
     * @param $name qui est le nom d'un internaute x.
     * @uses getMediaIntels_id qui permet de récupérer les informations principales d'un film ou d'une série par l'id de ce film ou série.
     * @return string qui est le flux HTML renvoyé qui affichera les 3 derniers films consutlés par l'internaute.
     */

    function write3LastUser_search ($name) : string{
        $array = readsv ("./files/user_".$name.".csv") ;
        $size = count ($array) ;
        $str = "" ;
        if($size == 1){
            $id = $array [0] [3] ;
            $movie = getMediaIntels_id ($id) ;
            $title = $movie['Title'];
            $image = $movie['Poster'];
            $str .= "\n<div class=\"divImage\">\n";
            $str .= "\t<figure>\n";
            $str .= "\t\t<img width = '200' src =\"$image\" alt= \"film\"/>\n" ;
            $str .= "\t\t<figcaption><em>$title</em></figcaption>\n";
            $str .= "\t</figure>\n";
            $str .= "\t<p>Accessed : ".$array [$size-1] [4]."</p>\n" ;
            $str .= "</div>\n";
        }
        else if($size == 2){

            for ($i = $size-1 ; $i > -1 ; $i--){
                $id = $array [$i] [3] ;
                $movie = getMediaIntels_id ($id) ;
                $title = $movie['Title'];
                $image = $movie['Poster'];
                $str .= "\n<div class=\"divImage\">\n";
                $str .= "\t\t<figure>\n";
                $str .= "\t\t\t<img width = '200' src =\"$image\" alt= \"film\"/>\n" ;
                $str .= "\t\t\t<figcaption><em>$title</em></figcaption>\n";
                $str .= "\t\t</figure>\n";
                $str .= "\t\t<p>Accessed : ".$array [$size-1] [4]."</p>\n" ;
                $str .= "</div>\n";
            }
        }
        else{
            for ($i = $size-1 ; $i > $size-4 ; $i--){
                $id = $array [$i] [3] ;
                $movie = getMediaIntels_id ($id) ;
                $title = $movie['Title'];
                $image = $movie['Poster'];
                $str .= "\n<div class=\"divImage\">\n";
                $str .= "\t\t<figure>\n";
                $str .= "\t\t\t<img width = '200' src =\"$image\" alt= \"film\"/>\n" ;
                $str .= "\t\t\t<figcaption><em>$title</em></figcaption>\n";
                $str .= "\t\t</figure>\n";
                $str .= "\t\t<p>Accessed : ".$array [$size-1] [4]."</p>" ;
                $str .= "</div>\n";
            }
        }
        return $str ;
    }

    /**
     * Fonction qui permet de réaliser un graphique qui présentera les films et séries les plus consultés.
     */
    
    function makeStats (){

        $data = readsv ("./files/film.csv") ;
        $xdata = array () ;
        $ydata = array () ;
        for ($i = 0 ; $i < count ($data) ; $i++){
            $ydata [] = $data [$i] [1] ;
            $xdata [] = $data [$i] [0] ;
        }
        //var_dump ($xdata) ;
        //var_dump ($ydata) ;
        //creation du graph
        $graph = new Graph (count ($ydata)*150 , count ($xdata)*50) ;
        $graph->SetScale ("textint") ;
        //style
        $graph->SetShadow () ;
        $graph->SetColor ("white") ;
        $graph->SetMarginColor ("gray6") ;
        $graph->SetMargin (40 , 30 , 40 , 40) ;
        //titres et etiquettes
        $graph->title->Set ("Films et Séries les plus recherchés") ;
        $graph->yaxis->title->Set ("Frequence de recherche") ;
        $graph->xaxis->title->Set ("Films et Séries") ;
        $graph->xaxis->SetTickLabels ($xdata) ;
        $graph->xaxis->SetTextLabelInterval (1) ;
        //creation histogramme
        $hist = new BarPlot ($ydata) ;
        $hist->SetWidth (1.0) ;
        $hist->SetFillGradient ("blue" , "white" , GRAD_MIDVER) ;
        //Ajout du composant histogramme
        $graph->Add ($hist) ;
        //Envoi de l'image au format png
        $graph->img->SetImgFormat ("png") ;
        if (!file_exists ("./images/stats.png")){
            $graph->Stroke ("./images/stats.png") ;
        }
        else{
            unlink ("./images/stats.png") ;
            $graph->Stroke ("./images/stats.png") ;
        }
    }
    
    function get_navigateur () : string{

        $agent = getenv("HTTP_USER_AGENT");

        // Browser Detection
        if (preg_match('/Firefox/i',$agent)){
            $browser = 'Firefox' ; 
        }
        elseif (preg_match('/Mac/i',$agent)) {
            $browser = 'Mac';
        }

        elseif (preg_match('/Chrome/i',$agent)){
            $browser = 'Chrome'; 
        } 
        elseif (preg_match('/Opera/i',$agent)) {
            $browser = 'Opera' ; 
        }
        elseif (preg_match('/Microsoft Edge/i',$agent)) {
            $browser = 'Microsoft Edge' ; 
        }
        elseif (preg_match('/Internet Explorer/i',$agent)) {
            $browser = 'Internet Explorer' ; 
        }
        else if (preg_match('/MSIE/i',$agent)){
            $browser = 'IE' ;
        }
        else{
            $browser = 'Unknown';
        } 

        return $browser ;
    }

    function compteurHit() : string{
        if(file_exists('./files/visite.txt')){
            $counter_file = fopen('./files/visite.txt', 'r+');
            $counter = intval(fgets($counter_file));
        

            $counter++;

            fseek($counter_file, 0); 
            fputs($counter_file, strval($counter)); 
        
            fclose($counter_file);

            $str = "<p>Nb de visiteurs:".$counter."</p>";
        }else{
            echo $str = "<p>error</p>";
        } 
        return $str;  
    }



?>