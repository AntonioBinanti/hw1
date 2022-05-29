<?php 
    require_once 'hw1_auth.php';
    require_once "hw1_client_id.php";
    if (!$userid = checkAuth()) {
        header("Location: hw1_login.php");
        exit;
    }
    if (empty($_GET["modello"] || empty($_GET["marca"]) || empty($_GET["anno"]))){
        $error= "Marca, modello o anno non inseriti!";
        exit;
    } 
    else{
        $curl= curl_init();
        $URL= "https://vpic.nhtsa.dot.gov/api/vehicles/GetCanadianVehicleSpecifications/?Year=".urlencode($_GET["anno"])."&Make=".urlencode($_GET["marca"])."&Model=".urlencode($_GET["modello"])."&units=&format=json";
        curl_setopt($curl, CURLOPT_URL, $URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res= curl_exec($curl);
        curl_close($curl);
        $obj= json_decode($res);
        //Carico immagine auto
        //$URL_img= "https://imsea.herokuapp.com/api/1?q=".urlencode($_GET["marca"]." ".$_GET["modello"]." ".$_GET["anno"]);
    $URL_img= 'https://api.unsplash.com/search/photos?per_page=2&client_id='.$client_id.'&query='.urlencode($_GET["marca"]." ".$_GET["modello"]/*." ".$_GET["anno"]*/);
        $curl_img= curl_init();
        curl_setopt($curl_img, CURLOPT_URL, $URL_img);
        curl_setopt($curl_img, CURLOPT_RETURNTRANSFER, 1);
        $res_img= curl_exec($curl_img);
        curl_close($curl_img);
        $obj_img= json_decode($res_img);
        $img= $obj_img->results[0]->urls->regular;
    }
?>

<html>
    <?php 
        // Carico le informazioni dell'utente loggato
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        $userid = mysqli_real_escape_string($conn, $userid);
        $query = "SELECT * FROM users WHERE id = $userid";
        $query_car = "SELECT * FROM car WHERE user = $userid";
        $res_1 = mysqli_query($conn, $query) or die("Errore: ".mysqli_error($conn));
        $res_car = mysqli_query($conn, $query_car) or die("Errore: ".mysqli_error($conn));
        $userinfo = mysqli_fetch_assoc($res_1);
    ?>
    <head>
        <title>hw1_specifiche_auto</title>
        <link rel="stylesheet" href="hw1_specifiche_auto.css" />
        <script src="hw1_specifiche_auto.js" defer="true"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Koulen&family=Libre+Baskerville&family=Lobster&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
    <nav>
        <div><a href="hw1_home.php">Home</a></div>
        <div id="saved_cars">
            <a href="hw1_auto_salvate.php">AUTO SALVATE</a>
            <?php echo "<span>".$userinfo['n_car_saved']."</span>" ?>
        </div>
        <div><a href="hw1_logout.php">LOGOUT</a></div>
        <div id="profilo">
            <?php echo "<a>".$userinfo['username']."</a>" ?>
        </div>
    </nav>
    <content>
        <?php  
            if(empty($_GET)){
                echo "<span>Nessun auto trovata!</span>";
                exit;
            }
        ?>
        <div id="auto">
            <?php 
                if(!$img){
                    echo "<span>Immagine non disponibile</span>";
                }
                else{
                    echo "<img src=".$img."></img>";
                } 
            ?>
            <h2 id="marca">Marca: <?php echo "<em>".$_GET["marca"]."</em>"?></h2>
            <h2 id="modello">Modello: <?php echo "<em>".$_GET["modello"]."</em>"?></h2>
            <h2 id="anno">Anno: <?php echo "<em>".$_GET["anno"]."</em>"?></h2>
            <button id="salva">
                <?php
                    while($carinfo = mysqli_fetch_assoc($res_car)){
                        if($carinfo["marca"]==$_GET["marca"] && $carinfo["modello"]==$_GET["modello"] && $carinfo["anno"]==$_GET["anno"]){
                            $saved=true;
                        }
                    }
                    if(isset($saved)){
                        echo "<img src='saved.png'></img>";
                    }else{
                        echo "<img src='save.png'></img>";
                    }
                ?>
            </button>
        </div>
        <div id="scheda">
            <h1>SPECIFICHE:</h1>
            <div id="dati">
                <?php
                if($obj->Count==0){
                    echo "<span>Nessun dato disponibile</span>";
                }else{
                    $length= count($obj->Results[0]->Specs);
                    for($i=3; $i<$length; $i++){
                        if($obj->Results[0]->Specs[$i]->Name=="CW"){
                            echo "<p>".$obj->Results[0]->Specs[$i]->Name."=".$obj->Results[0]->Specs[$i]->Value."Kg</p>";
                        }
                        else if($obj->Results[0]->Specs[$i]->Name=="WD"){
                            echo "<p>".$obj->Results[0]->Specs[$i]->Name."=".$obj->Results[0]->Specs[$i]->Value."%</p>";
                        }
                        else{
                            echo "<p>".$obj->Results[0]->Specs[$i]->Name."=".$obj->Results[0]->Specs[$i]->Value."cm</p>";
                        }
                    }
                }
                ?>
            </div>
            <div id="legenda">
                <h1>LEGENDA:</h1>
                <img src="specifiche.jpg"></img>
                <p>A->Distanza longitudinale tra il centro del paraurti anteriore e il centro della base del parabrezza</p>
                <p>B->-Autovettura: Distanza longitudinale tra il centro del paraurti posteriore e il centro la base delle luci posteriori<br>
                    &nbsp&nbsp&nbsp&nbsp-Station Wagon e van: Distanza longitudinale tra la modanatura superiore della retroilluminazione e il montante del chiavistello 
                    della porta anteriore<br>&nbsp&nbsp&nbsp&nbsp-Pick-up: Distanza longitudinale tra la proiezione più arretrata e il montante del 
                    chiavistello della porta anteriore
                </p>
                <p>C->L'altezza verticale massima del vetro laterale</p>
                <p>D->Distanza verticale tra la base del vetro laterale e il bordo inferiore del pannello a bilanciere</p>
                <p>E->Distanza tra le guide laterali o larghezza massima della parte superiore</p>
                <p>F->Sbalzo anteriore</p>
                <p>G->Sbalzo posteriore</p>
                <p>OLO->Lunghezza</p>
                <p>OW->Larghezza</p>
                <p>OH->Altezza totale</p>
                <p>WB->Passo</p>
                <p>TWF->Carreggiata anteriore</p>
                <p>WW1->Carreggiata posteriore</p>
                <p>CW->Peso a vuoto</p>
                <p>WD->Distribuzione del peso (anteriore/posteriore)</p>
            </div>
        </div>
    </content>
    
    <footer>
        <h2>Powered by Antonio Binanti 1000002208</h2>
    </footer>
    </body>
</html>

<?php 
    mysqli_free_result($res_1);
    mysqli_free_result($res_car);
    mysqli_close($conn); 
?>