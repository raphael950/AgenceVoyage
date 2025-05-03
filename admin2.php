<?php
    session_start();

    // user non admin ou pas connecté mais se rend sur la page quand meme via URL
    if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] != "admin"){
        header("Location: login.php");
        exit();
    }
    
    $content = file_get_contents("data/users.json");
    $users = json_decode($content, true);

    
    $usersPerPage = 5;
    $totalUsers = count($users);
    $totalPages = ceil($totalUsers / $usersPerPage); // ceil = arrondir float
    $currentPage = isset($_GET['page']) ? max(1, min($totalPages, intval($_GET['page']))) : 1;

    $startIndex = ($currentPage - 1) * $usersPerPage;
    $usersToDisplay = array_slice($users, $startIndex, $usersPerPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page administrateur</title>
    <link rel="stylesheet" href="style/admin2.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
    <script src="script/admin.js" defer></script>
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
        <h1>Page administrateur</h1>

        <div class="table-container">
            <table>
                <tr>
                    <th>Nom client</th>
                    <th>E-mail client</th>
                    <th>Sexe client</th>
                    <th>Date de naissance client</th>
                    <th>Adresse client</th>
                    <th>Actions sur client</th>
                </tr>
                <?php foreach ($usersToDisplay as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= isset($user["gender"]) && !empty($user["gender"]) ? htmlspecialchars($user["gender"]) : "N/A"; ?></td>
                        <td><?= isset($user["birth"]) && !empty($user["birth"]) ? htmlspecialchars($user["birth"]) : "N/A"; ?></td>
                        <td><?= isset($user["adresse"]) && !empty($user["adresse"]) ? htmlspecialchars($user["adresse"]) : "N/A"; ?></td>
                        <td>
                            <form action="admin_edit.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="editButton" name="action" value="edit">Modifier</button>
                            </form>    
                            <form action="script/admin_trigger.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="vipButton" name="action" value="vip"><?= ($user['role'] == "vip") ? "Enlever VIP" : "Donner VIP"; ?></button>
                            </form>
                            <form action="script/admin_trigger.php" method="post">
                                <input type="hidden" name="client_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="banButton" name="action" value="ban">Bannir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i === $currentPage ? 'style="font-weight: bold;"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>">Suivant</a>
            <?php endif; ?>
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