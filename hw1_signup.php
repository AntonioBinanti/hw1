<?php
    require_once 'hw1_auth.php';

    if (checkAuth()) {
        header("Location: hw1_home.php");
        exit;
    }  

    // Verifica dati POST
    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["name"]) && 
        !empty($_POST["surname"]) && !empty($_POST["confirm_password"]) && !empty($_POST["allow"])&& !empty($_POST["allow"])){
        $error = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        
        // USERNAME
        if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $_POST['username'])) {
            $error[] = "Username non valido";
        } else {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            // Cerco se l'username esiste già
            $query = "SELECT username FROM users WHERE username = '$username'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Username già utilizzato";
            }
        }
        // PASSWORD
        if (strlen($_POST["password"]) < 8) {
            $error[] = "La password deve superare 8 caratteri";
        } 
        // CONFERMA PASSWORD
        if (strcmp($_POST["password"], $_POST["confirm_password"]) != 0) {
            $error[] = "Le password non coincidono";
        }
        // EMAIL
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = "Email non valida";
        } else {
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
            $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Email già utilizzata";
            }
        }
        // REGISTRAZIONE NEL DATABASE
        if (count($error) == 0) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $surname = mysqli_real_escape_string($conn, $_POST['surname']);

            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users(username, password, name, surname, email) VALUES('$username', '$password', '$name', '$surname', '$email')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION["username"] = $_POST["username"];
                $_SESSION["user_id"] = mysqli_insert_id($conn); //ritorna id connessione
                mysqli_close($conn);
                header("Location: hw1_home.php");
                exit;
            } else {
                $error[] = "Errore di connessione al Database";
            }
        }

        mysqli_close($conn);
    }
    else if (isset($_POST["username"])) {
        $error = array("Riempi tutti i campi");
    }

?>
<html>
    <head>
    <title>hw1_signup</title>
    <link rel="stylesheet" href="hw1_login.css" />
    <script src="hw1_signup.js" defer="true"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Koulen&family=Libre+Baskerville&family=Lobster&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <section class="sfondo">
        <div class="overlay"></div>
            <div class="form">
                <h1>Inserisci i tuoi dati</h1>
                <?php
                    if (isset($error)) {
                        foreach($error as $er){
                            echo "<span class='error'>$er</span>";
                        }
                    }
                ?>
                <form name='signup' method='post'>
                <div class="name">
                    <div><label for='name'>Nome</label></div>
                    <div><input type='text' name='name' <?php if(isset($_POST["name"])){echo "value=".$_POST["name"];} ?>></div>
                    <span>Inserisci Nome</span>
                </div>
                <div class="surname">
                    <div><label for='surname'>Cognome</label></div>
                    <div><input type='text' name='surname' <?php if(isset($_POST["surname"])){echo "value=".$_POST["surname"];} ?>></div>
                    <span>Inserisci Cognome</span>
                </div>
                <div class="username">
                    <div><label for='username'>Nome utente</label></div>
                    <div><input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></div>
                    <span>Inserisci Nome Utente</span>
                </div>
                <div class="email">
                    <div><label for='email'>E-mail</label></div>
                    <div><input type='text' name='email' <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>></div>
                    <span>Inserisci E-mail</span>
                </div>
                <div class="password">
                    <div><label for='password'>Password</label></div>
                    <div><input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>></div>
                    <span>Inserisci almeno 8 caratteri</span>
                </div>
                <div class="confirm_password">
                    <div><label for='confirm_password'>Conferma password</label></div>
                    <div><input type='password' name='confirm_password' <?php if(isset($_POST["confirm_password"])){echo "value=".$_POST["confirm_password"];} ?>></div>
                    <span>Password non coincidenti</span>
                </div>
                <div class="allow"> 
                    <div><input type='checkbox' name='allow' value="1" <?php if(isset($_POST["allow"])){echo $_POST["allow"] ? "checked" : "";} ?>></div>
                    <div><label for='allow'>Acconsento al furto dei dati personali</label></div>
                    <span>Campo obbligatorio</span>
                </div>
                <div class="submit">
                    <input type='submit' value="Registrati">
                </div>
            </form>
            <div class="signup"><h2>Hai già un account? <a href="hw1_login.php">Accedi</a></h2>
            </div>
        </section>
    </body>
</html>