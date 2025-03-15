<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire</title>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <div class="bg-image"></div>
    <div id="main-card">
        <form action="register_trigger.php" method="post">
            <fieldset>
                <legend>Inscription</legend>
                <?php
                    session_start();
                    if (isset($_SESSION["error"])) {
                        echo "<p style='color: red':>".$_SESSION["error"]."</p>";
                        unset($_SESSION["error"]);
                    }

                ?>

                <label for="mail">Nom</label>
                <input type="text" name="name" id="name" placeholder="Nom" required>
                <label for="mail">Adresse email</label>
                <input type="email" name="email" id="mail" placeholder="Email" required>

                <label for="pass">Mot de passe</label>
                <input type="password" name="password" id="pass" placeholder="Mot de passe" required>

                <input type="submit" value="S'inscrire">
            </fieldset>
        </form>
    </div>
</body>
</html>