<?php
    // Verifica se l'utente è già loggato, in caso positivo va alla home
    include 'hw1_auth.php';
    if (checkAuth()) {
        header('Location: hw1_home.php');
        exit;
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) ){
        // Se username e password sono stati inviati
        // Connessione al DB
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        // Eseguo escape string per motivi di sicurezza
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        // ID e Username per sessione, password per controllo
        $query = "SELECT id, username, password FROM users WHERE username = '$username'";
        // Esecuzione
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));;
        if (mysqli_num_rows($res) > 0) {
            // Ritorna una sola riga, il che ci basta perché l'utente autenticato è solo uno
            $entry = mysqli_fetch_assoc($res);
            if (password_verify($_POST['password'], $entry['password'])) {
                // Imposto una sessione dell'utente
                $_SESSION["username"] = $entry['username'];
                $_SESSION["user_id"] = $entry['id'];
                header("Location: hw1_home.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        // Se l'utente non è stato trovato o la password non ha passato la verifica
        $error = "Username e/o password errati.";
    }
    else if (isset($_POST["username"]) || isset($_POST["password"])) {
        // Se solo uno dei due è impostato
        $error = "Inserisci username e password.";
    }
?>

<html>
    <head>
        <title>hw1_login</title>
        <link rel="stylesheet" href="hw1_login.css" />
        <script src="hw1_login.js" defer="true"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Koulen&family=Libre+Baskerville&family=Lobster&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <section class="sfondo">
            <div class="overlay"></div>
            <div class="form">
                <h1>Benvenuto!</h1>
                <?php
                    if (isset($error)) {
                        echo "<span class='error'>$error</span>";
                    }
                ?>
                <form name='login' method='post'>
                <div class="username">
                    <div><label for='username'>Nome utente</label></div>
                    <div><input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></div>
                    <span>Inserire Username</span>
                </div>
                <div class="password">
                    <div><label for='password'>Password</label></div>
                    <div><input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>></div>
                    <span>Inserire Password</span>
                </div>
                <div class="submit submit_login">
                    <input type='submit' value="Accedi">
                </div>
            </form>
            <div class="signup"><h2>Non hai un account? <a href="hw1_signup.php">Iscriviti</a></h2>
            </div>
        </section>
    </body>
</html>