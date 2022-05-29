<?php
    require_once "hw1_client_id.php";
    $curl= curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res_1= curl_exec($curl);
    curl_close($curl);
    $obj= json_decode($res_1);
    $marche= array();
    $max=30;
    //seleziono marche
    for($i=0; $i<$max; $i++){
        $marche[$i]["Make_ID"]= $obj->Results[$i]->Make_ID;
        $marche[$i]["Make_Name"]= $obj->Results[$i]->Make_Name;
    }
    //assegno un url del logo per ogni marca
    $multicurl= array();
    $mh= curl_multi_init();
    for($i=0; $i<$max; $i++){
        $dati= urlencode($marche[$i]["Make_Name"]." emblem");
        $multicurl[$i]= curl_init();
        $url = 'https://api.unsplash.com/search/photos?per_page=2&client_id='.$client_id.'&query='.$dati;
        /*curl_setopt($multicurl[$i], CURLOPT_URL, "https://imsea.herokuapp.com/api/1?q=".$dati);
        curl_setopt($multicurl[$i], CURLOPT_RETURNTRANSFER, 1);*/

        curl_setopt($multicurl[$i], CURLOPT_URL, $url);
        curl_setopt($multicurl[$i], CURLOPT_RETURNTRANSFER, 1);
        
        curl_multi_add_handle($mh,$multicurl[$i]);
    }
    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while ($running);
    for($i=0; $i<$max; $i++) {
        $results[$i] = curl_multi_getcontent($multicurl[$i]);
        curl_multi_remove_handle($mh, $multicurl[$i]);
        curl_close($multicurl[$i]);
        $loghi[$i]= json_decode($results[$i]);
        $marche[$i]["Make_url_logo"]= $loghi[$i]->results[0]->urls->regular;
    }
    curl_multi_close($mh);
    echo json_encode($marche);
?>