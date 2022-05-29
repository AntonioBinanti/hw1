<?php 
    require_once 'hw1_auth.php';
    if (!$userid = checkAuth()) {
        header("Location: hw1_login.php");
        exit;
    }
?>

<html>
    <?php 
        // Carico le informazioni dell'utente loggato
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        $userid = mysqli_real_escape_string($conn, $userid);
        $query = "SELECT * FROM users WHERE id = $userid";
        $res_1 = mysqli_query($conn, $query) or die("Errore: ".mysqli_error($conn));
        $userinfo = mysqli_fetch_assoc($res_1);   
    ?>
<head>
    <title>hw1</title>
    <link rel="stylesheet" href="hw1_home.css" />
    <script src="hw1_home.js" defer="true"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Koulen&family=Libre+Baskerville&family=Lobster&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <nav>
        <div id="saved_cars">
            <a href="hw1_auto_salvate.php">AUTO SALVATE</a>
            <?php echo "<span>".$userinfo['n_car_saved']."</span>" ?>
        </div>
        <div><a href="hw1_logout.php">LOGOUT</a></div>
        <div id="profilo">
            <?php echo "<a>".$userinfo['username']."</a>" ?>
        </div>
    </nav>
    <header>
        <h1>Trova le specifiche della tua auto</h1>
        
        <div id="overlay"></div>
    </header>
    <content id="galleria">
        <h2>scegli un modello:</h2>
        <div id="loghi">
            
        </div>
    </content>
    <content id="modal_view" class="hidden">
    </content>
    <footer>
        <h2>Powered by Antonio Binanti 1000002208</h2>
    </footer>
</body>
</html>

<?php 
    mysqli_free_result($res_1);
    mysqli_close($conn); 
?>