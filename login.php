<?php
    session_start();

    // user déja connecté mais se rend sur la page quand meme via URL
    if (isset($_SESSION["user"])) {
        header("Location: profile2.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link id="theme-style" rel="stylesheet" href="style/login.css">
    <script src="script/theme.js"></script>

</head>
<body>
    <div class="bg-image"></div>
    <div id="main-card">
        <form action="script/login_trigger.php" method="post">
            <fieldset>
                <?php
                    if (isset($_SESSION["error"])) {
                        echo "<p style='color: red':>".$_SESSION["error"]."</p>";
                        unset($_SESSION["error"]);
                    }
                ?>
                <legend>Connexion</legend>
                <label for="email">Adresse email</label>
                <input type="email" name="mail" id="email" placeholder="Email" required>

                <label for="pass">Mot de passe</label>
                <input type="password" name="pass" id="pass" placeholder="Mot de passe" required>
                <a href="oublie.html">Mot de passe oublié</a>
                <input type="submit" value="Se connecter">
            </fieldset>
        </form>
    </div>
</body>
</html>