<?php
    include 'hw1_auth.php';
    if (!$userid = checkAuth()) exit;

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    $user = mysqli_real_escape_string($conn, $userid);
    $marca = mysqli_real_escape_string($conn, $_POST["marca"]);
    $modello = mysqli_real_escape_string($conn, $_POST["modello"]);
    $anno = mysqli_real_escape_string($conn, $_POST["anno"]);
    $img = mysqli_real_escape_string($conn, $_POST["img"]);

    $query_check = "SELECT * FROM car WHERE user = '$user'";
    $query_save = "INSERT INTO car(user,marca,modello,anno,img) VALUES ('$user','$marca','$modello','$anno','$img')";
    $query_unsave = "DELETE FROM car WHERE user = '$user' AND marca = '$marca' AND modello = '$modello' AND anno = '$anno'";
    $query_out = "SELECT n_car_saved FROM users WHERE id = '$user'";

    $res_check = mysqli_query($conn, $query_check) or die ('Errore: '. mysqli_error($conn));
    //$res = mysqli_fetch_assoc($res_check);

    while($res = mysqli_fetch_assoc($res_check)){
        if($res["marca"]==$marca && $res["modello"]==$modello && $res["anno"]==$anno){ //auto già salvata, quindi la devo eliminare dalle auto salvate
            $res_unsave = mysqli_query($conn, $query_unsave) or die ('Errore: '. mysqli_error($conn));
            $res_out= mysqli_query($conn, $query_out) or die ('Errore: '. mysqli_error($conn));
            if (mysqli_num_rows($res_out) > 0) {
                $entry = mysqli_fetch_assoc($res_out);
                $returndata = array('ok' => true, 'n_car_saved' => $entry['n_car_saved'], 'operation' => 'unsaved');
                echo json_encode($returndata);
                mysqli_close($conn);
                exit;
            }
        }
    }
    //altrimenti salvo auto
    $res_save = mysqli_query($conn, $query_save) or die ('Errore: '. mysqli_error($conn));

    $res_out= mysqli_query($conn, $query_out) or die ('Errore: '. mysqli_error($conn));
    if (mysqli_num_rows($res_out) > 0) {
        $entry = mysqli_fetch_assoc($res_out);
        $returndata = array('ok' => true, 'n_car_saved' => $entry['n_car_saved'], 'operation' => 'saved');
        echo json_encode($returndata);
        mysqli_close($conn);
        exit;
    }
    
    mysqli_close($conn); 
    echo json_encode(array('ok' => false));
?>