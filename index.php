<?php
    session_start();
    include "include/voyage_utils.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!--
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    -->
    <title>Fishing Travel</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
    
</head>
<body>
    <nav>
    <?php
        if (isset($_SESSION["user"])) {
            $username = htmlspecialchars($_SESSION["user"]["username"]);
            echo '<span class="welcome">Bienvenue, ' . $username . '</span>';
            echo '<a href="profile2.php" class="button">Mon profil</a>';
            echo '<a href="logout.php" class="button">Se déconnecter <i class="fa-solid fa-right-from-bracket"></i></a>';
        } else {
            echo '<a href="register.php" class="button" id="register-button">S\'inscrire</a>';
            echo '<a href="login.php" class="button">Se connecter</a>';
        }
    ?>

        
    </nav>
    <div id="header">
        <img src="assets/logo2.png" alt="logo">
        <div id="side">
            <h1>Pêchez votre voyage de rêve</h1>
            <form class="search-container" action="x" method="get" enctype="multipart/form-data">
                <input class="search" type="text" name="search" id="search" placeholder="Rechercher">
                <input class="gosearch" type="image" name="gosearch" id="gosearch" src="https://png.pngtree.com/png-vector/20221023/ourmid/pngtree-magnifying-glass-or-loupe-in-flat-style-png-image_6383627.png">
            </form>
            <p>ou</p>
            <a href="avancee.php" class="button" id="button-avancee">Recherche avancée</a>
        </div>
    </div>
    <section id="points-forts">
        <h1>Pourquoi nous ?</h1>
        <div>
            <div class="point">
                <i class="fa-solid fa-map-marker-alt fa-7x"></i>
                <h3>On connaît le spot</h3>
                <p>Nos guides connaissent les meilleurs endroits pour une expérience de pêche inoubliable.</p>
            </div>
            <div class="point">
                <i class="fa-solid fa-fish fa-7x"></i>
                <h3>Un guide expert</h3>
                <p>Votre guide vous enseigne les meilleures techniques pour améliorer votre pêche.</p>
            </div>
            <div class="point">
                <i class="fa-solid fa-toolbox fa-7x"></i>
                <h3>Matériel fourni</h3>
                <p>Tout l'équipement nécessaire est mis à votre disposition pour une expérience optimale.</p>
            </div>
        </div>
    </section>
    <section id="exemples">
        <?php
            #recup voyages
            #pour chaque voyage afficher une carte avec titre, image, etc
            $contenu = file_get_contents("data/voyages.json");
            $voyages = json_decode($contenu, true);

            foreach($voyages as $voyage) {
                $titre = $voyage["titre"];
                $imageUrl = "assets/voyages/". $voyage["id"] . "/miniature.jpg";
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