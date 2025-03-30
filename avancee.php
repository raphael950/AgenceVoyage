<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fishing Travel - Recherche Avancée</title>
    <link rel="stylesheet" href="style/recherche.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav>
        <?php
            if (isset($_SESSION["user"])) {
                $username = htmlspecialchars($_SESSION["user"]["username"]);
                echo '<span class="welcome">Bienvenue, ' . $username . '</span>';
                echo '<a href="profile2.php" id="login-button">Mon profil</a>';
                echo '<a href="logout.php" id="login-button">Se déconnecter <i class="fa-solid fa-right-from-bracket"></i></a>';
            } else {
                echo '<a href="register.php" id="login-button">S\'inscrire</a>';
                echo '<a href="login.php" id="login-button">Se connecter</a>';
            }
        ?>
    </nav>
    <div id="header">
        <img src="assets/logo2.png" alt="logo">
        <div id="side">
            <h1>Trouvez votre spot de pêche idéal</h1>
            <form class="search-container" action="search_results.php" method="get">
                <input class="search" type="text" name="search" placeholder="Rechercher (optionnel)">
                <select name="country" class="search">
                    <option value="">Sélectionner un pays</option>
                    <option value="costa_rica">Costa Rica</option>
                    <option value="canada">Canada</option>
                    <option value="france">France</option>
                </select>
                <input type="number" name="travelers" class="search" placeholder="Nombre de voyageurs" min="1" max="4">
                <button type="submit" class="gosearch">Rechercher</button>
            </form>
        </div>
    </div>
    <section>
        <div class="card">
            <img src="https://d2xuzatlfjyc9k.cloudfront.net/wp-content/uploads/2014/05/Best-Costa-Rica-Fishing-Trips-Marinas-1.jpg" alt="Costa Rica">
            <div class="text">
                <h3>Costa Rica</h3>
                <p>1h 50, sans escale</p>
                <p>lun. 3/3 > lun. 10/3</p>
                <h3>à partir de 31</h3>
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; Fishing Travel</p>
        <ul>
            <li>Derrien Enzo</li>
            <li>Drecourt Raphaël</li>
            <li>Nisar Sofiane</li>
        </ul>
        <a href="https://github.com/raphael950/AgenceVoyage.git">Lien du GitHub</a>
    </footer>
</body>
</html>
