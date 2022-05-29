<?php
    include 'hw1_dbconfig.php';

    // Distruggo la sessione esistente
    session_start();
    session_destroy();

    header('Location: hw1_login.php');
?>