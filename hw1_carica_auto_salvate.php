<?php
    require_once 'hw1_auth.php';
    if (!$userid = checkAuth()) {
        header("Location: hw1_login.php");
        exit;
    }
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    $userid = mysqli_real_escape_string($conn, $userid);
    $query = "SELECT * FROM users WHERE id = $userid";
    $query_car = "SELECT * FROM car WHERE user = $userid";
    $res_1 = mysqli_query($conn, $query) or die("Errore: ".mysqli_error($conn));
    $res_car = mysqli_query($conn, $query_car) or die("Errore: ".mysqli_error($conn));
    $userinfo = mysqli_fetch_assoc($res_1);
    $return=array();
    $i=0;
    while($carinfo = mysqli_fetch_assoc($res_car)){
        $return[$i]["marca"]=$carinfo["marca"];
        $return[$i]["modello"]=$carinfo["modello"];
        $return[$i]["anno"]=$carinfo["anno"];
        $return[$i]["img"]=$carinfo["img"];
        $i++;
    }
    echo json_encode($return);
    mysqli_close($conn);
?>