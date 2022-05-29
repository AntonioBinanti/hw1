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
?>
<html>
    <head>
        <title>hw1_auto_salvate</title>
        <link rel="stylesheet" href="hw1_auto_salvate.css" />
        <script src="hw1_auto_salvate.js" defer="true"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Koulen&family=Libre+Baskerville&family=Lobster&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
    <nav>
        <div><a href="hw1_home.php">Home</a></div>
        <div id="saved_cars">
            <a href="">AUTO SALVATE</a>
            <?php echo "<span>".$userinfo['n_car_saved']."</span>" ?>
        </div>
        <div><a href="hw1_logout.php">LOGOUT</a></div>
        <div id="profilo">
            <?php echo "<a>".$userinfo['username']."</a>" ?>
        </div>
    </nav>
    <content>
        <div id="galleria">
        </div>
    </content>
    <footer>
        <h2>Powered by Antonio Binanti 1000002208</h2>
    </footer>
    </body>
</html>

<?php 
    mysqli_close($conn); 
?>