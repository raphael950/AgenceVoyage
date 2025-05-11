<?php
    session_start();
    include "include/voyage_utils.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fishing Travel - Recherche Avancée</title>
    <link id="theme-style" rel="stylesheet" href="style/recherche.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
    <script src="script/theme.js"></script>

</head>
<body>
    <nav>
        <button id="login-button" onclick="switchTheme('style/recherche')">
            <i class="fa-regular fa-lightbulb"></i>
        </button>
        <?php
            if (isset($_SESSION["user"])) {
                $username = htmlspecialchars($_SESSION["user"]["username"]);
                echo '<span class="welcome">Bienvenue, ' . $username . '</span>';
                echo '<a href="profile2.php" id="login-button">Mon profil</a>';
                echo '<a href="script/logout.php" id="login-button">Se déconnecter <i class="fa-solid fa-right-from-bracket"></i></a>';
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
                    <?php
                        $contenu = file_get_contents("data/voyages.json");
                        $voyages = json_decode($contenu, true);
                        $paysSet = [];
                        foreach($voyages as $voyage){
                            $listePays = explode(',', $voyage['pays']);
                            foreach ($listePays as $p) {
                                $paysSet[] = trim($p);
                            }
                            $paysUniques = array_unique($paysSet);
                            sort($paysUniques);
                        }
                        foreach($paysUniques as $pays){
                            echo <<<HTML
                                <option value=$pays>$pays</option>
                            HTML;
                        }
                    ?>
                </select>
                <input type="number" name="travelers" class="search" placeholder="Nombre de voyageurs" min="1" max="4">
                <button type="submit" class="gosearch">Rechercher</button>
            </form>
        </div>
    </div>
    <section id="exemples">
        <?php
            #recup voyages
            #pour chaque voyage afficher une carte avec titre, image, etc
            $contenu = file_get_contents("data/voyages.json");
            $voyages = json_decode($contenu, true);
            $voyageShow = 0;

            foreach($voyages as $voyage) {
                $voyageShow++;
                $titre = $voyage["titre"];
                $imageUrl = "assets/voyages/". $voyage["id"] . "/miniature.png";
                if (!file_exists($imageUrl)) $imageUrl = "assets/no_photo.jpg";
                $prix = $voyage["prix"];
                $duree = duree($voyage);
                $etapes = count($voyage["etapes"]);

                echo <<<HTML
                    <div class="card">
                        <a href="voyage.php?id={$voyage['id']}">
                            <img src="$imageUrl" alt="$titre">
                            <div class="text">
                                <div>
                                    <h3>$titre</h3>
                                    <p>$duree jours, $etapes étapes</p>
                                </div>
                                <h3 class="bottom">à partir de {$prix}€/pers</h3>
                            </div>
                        </a>
                
                    </div>
                HTML;
                if($voyageShow == 5) break;
            }
        ?>          
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
