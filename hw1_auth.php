<?php
    require_once 'hw1_dbconfig.php';
    session_start();

    function checkAuth() {
        // Se esiste già una sessione, la ritorno, altrimenti ritorno 0
        if(isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        } else 
            return 0;
    }
?>