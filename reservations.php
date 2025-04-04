<?php
    session_start();

    // user non connecté mais se rend sur la page quand meme via URL
    if (!isset($_SESSION["user"])) {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="style/reservations.css">
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
        <h1>Mes réservations</h1>

        <section id="reserves">
        <?php
            // recup voyages reserves
            $contenu = file_get_contents("data/voyages.json");
            $voyages = json_decode($contenu, true);
            $contenu = file_get_contents("data/reservations.json");
            $reservations = json_decode($contenu, true);

            
            $generated = false; // utilisé pour gérer le cas 0 réservations
            foreach($reservations as $reservation){
                foreach($voyages as $voyage){
                    if($_SESSION["user"]["id"] == $reservation["user_id"] && $reservation["voyage_id"]==$voyage["id"]){
                        $generated = true;
                        $titre = $voyage["titre"];
                        $imageUrl = "assets/voyages/". $voyage["id"] . ".jpg";
                        $prix = $voyage["prix"];
                        echo <<<HTML
                            <div class="card">
                                <a href="voyage.php?id={$voyage['id']}">
                                <img src="$imageUrl" alt="$titre">
                                <div class="text">
                                    <h3>$titre</h3>
                                    <p>1h 50, sans escale</p>
                                    <p>lun. 3/3 > lun. 10/3</p>
                                    <h3>Prix payé : {$prix}€</h3>
                                </div>
                                </a>
                        
                            </div>
                        HTML;
                    }                    
                }            
            }
            if($generated == false){
                echo '<p class="noreservation">Aucune réservation effectuée. Méfiez-vous de l\'eau qui dort...</p>';
            }
        ?>
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