<?php
    session_start();

    // user non admin ou pas connecté mais se rend sur la page quand meme via URL
    if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] != "admin"){
        header("Location: login.php");
        exit();
    }

    $content = file_get_contents("data/users.json");
    $users = json_decode($content, true);
    $client_id = intval($_POST['client_id']);
    foreach ($users as $key => $user) {
        if ($user['id'] == $client_id) {
            $client = $user;
            break;
        }
    }

    // user non admin ou pas connecté mais se rend sur la page quand meme via URL
    /*
    if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] != "admin"){
        header("Location: login.php");
        exit();
    }
    */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un profil</title>
    <link id="theme-style" rel="stylesheet" href="style/profile2.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
    <script src="script/theme.js"></script>

    
</head>
<body>
    <nav>
        <a href="index.php">
            <img src="assets/logo2.png" class="logo" alt="logo">
        </a>
        <div class="nav-buttons">
            <?php
                if (isset($_SESSION["user"])) {
                    $username = htmlspecialchars($_SESSION["user"]["username"]);
                    echo '<span class="welcome">Bienvenue, ' . $username . '</span>';
                    echo '<a href="profile2.php" id="nav-button">Mon profil</a>';
                    echo '<a href="script/logout.php" id="nav-button">Se déconnecter <i class="fa-solid fa-right-from-bracket"></i></a>';
                } else {
                    echo '<a href="register.php" id="nav-button">S\'inscrire</a>';
                    echo '<a href="login.php" id="nav-button">Se connecter</a>';
                }
            ?>
        </div>
    </nav>
    <div id="main-card">
        <form action="script/admin_edit_trigger.php" method="post">
            <fieldset>

                <?php
                    if (isset($_SESSION["error"])) {
                        echo "<p style='color: red':>".$_SESSION["error"]."</p>";
                        unset($_SESSION["error"]);
                    }
                ?>

                <legend>Modifier le profil n°<?php echo $client_id;?></legend>
                <table>
                    <tr>
                        <th><label for="nom">Nom</label></td>
                        <td><input type="text" id="nom" name="nom" value="<?php echo $client["username"];?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="email">E-mail</label></td>
                        <td><input type="email" id="email" name="email" value="<?php echo $client["email"];?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="sexe">Sexe</label></td>
                        <td>
                            <select name="sexe">
                                <option value="Homme" <?= (($client["gender"] ?? '') == "Homme") ? 'selected' : '' ?>>Homme</option>
                                <option value="Femme" <?= (($client["gender"] ?? '') == "Femme") ? 'selected' : '' ?>>Femme</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="date">Date de naissance</label></td>
                        <td><input type="date" name="date" value="<?php if(isset($client["birth"])){echo $client["birth"];}?>"></td>
                    </tr>
                    <tr>
                        <th><label for="adresse">Adresse</label></td>
                        <td><input type="text" id="adresse" name="adresse" value="<?php if(isset($client["adresse"])){echo $client["adresse"];}?>"/></td>
                    </tr>
                </table>
                <input type="submit" value="Modifier">
            </fieldset>
        </form>
    </div>
    <footer>
        <p>&copy; Fishing Travel</p>
        <ul>
            <li>Derrien Enzo</li>  
            <li>Drecourt Raphaël</li>
            <li>Nisar Sofiane</li>
        </ul>
        <a href="https://github.com/raphael950/AgenceVoyage.git">Lien du github</a>
    </footer>
</body>
</html>