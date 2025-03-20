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
</head>
<body>
    <nav>
        <a href="index.html">
            <img src="assets/logo2.png" class="logo" alt="logo">
        </a>
        <button>Mon profil</button>
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
                        <td><?= isset($user["genre"]) ? htmlspecialchars($user["genre"]) : "N/A"; ?></td>
                        <td><?= isset($user["birth"]) ? htmlspecialchars($user["birth"]) : "N/A"; ?></td>
                        <td><?= isset($user["adresse"]) ? htmlspecialchars($user["adresse"]) : "N/A"; ?></td>
                        <td>
                            <form action="edit.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="edit">Modifier</button>
                            </form>    
                            <form action="vip.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="vip">Donner VIP</button>
                            </form>
                            <form action="ban.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="ban">Bannir</button>
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