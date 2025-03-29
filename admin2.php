<?php
    session_start();

    // user non admin ou pas connecté mais se rend sur la page quand meme via URL
    /*
    if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] != "admin"){
        header("Location: login.php");
        exit();
    }
    */
    
    $content = file_get_contents("data/users.json");
    $users = json_decode($content, true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page administrateur</title>
    <link rel="stylesheet" href="style/admin2.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav>
        <a href="index.php">
            <img src="assets/logo2.png" class="logo" alt="logo">
        </a>
        <div class="nav-buttons">
            <!-- 
            <a href="profile2.php" id="profile-button">Mon profil</a>
            <a href="logout.php" id="logout-button">Se déconnecter</a>
            -->
            <?php
                if (isset($_SESSION["user"])) {
                    $username = htmlspecialchars($_SESSION["user"]["username"]);
                    echo '<span class="welcome">Bienvenue, ' . $username . '</span>';
                    echo '<a href="profile2.php" id="nav-button">Mon profil</a>';
                    echo '<a href="logout.php" id="nav-button">Se déconnecter <i class="fa-solid fa-right-from-bracket"></i></a>';
                } else {
                    echo '<a href="register.php" id="nav-button">S\'inscrire</a>';
                    echo '<a href="login.php" id="nav-button">Se connecter</a>';
                }
            ?>
        </div>
    </nav>
    <div id="main-card">
        <h1>Page administrateur</h1>

        <div class="table-container">
            <table>
                <tr>
                    <th>Nom client</th>
                    <th>Prénom client</th>
                    <th>E-mail client</th>
                    <th>Sexe client</th>
                    <th>Date de naissance client</th>
                    <th>Adresse client</th>
                    <th>Actions sur client</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= isset($user["gender"]) && !empty($user["gender"]) ? htmlspecialchars($user["gender"]) : "N/A"; ?></td>
                        <td><?= isset($user["birth"]) && !empty($user["birth"]) ? htmlspecialchars($user["birth"]) : "N/A"; ?></td>
                        <td><?= isset($user["adresse"]) && !empty($user["adresse"]) ? htmlspecialchars($user["adresse"]) : "N/A"; ?></td>
                        <td>
                            <form action="admin_edit.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" name="action" value="edit">Modifier</button>
                            </form>    
                            <form action="admin_trigger.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" name="action" value="vip">Donner VIP</button>
                            </form>
                            <form action="admin_trigger.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" name="action" value="ban">Bannir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
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