<?php

session_start();
include "include/voyage_utils.php";

// Récupération de l'ID depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$json = file_get_contents("data/voyages.json");

// Décodage du JSON en tableau PHP
$voyages = json_decode($json, true);

// Recherche du voyage correspondant à l'ID
$voyage = null;
foreach ($voyages as $v) {
    if ($v['id'] === $id) {
        $voyage = $v;
        break;
    }
}

// Affichage du voyage si trouvé
if ($voyage):
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($voyage['titre']); ?></title>
    <link rel="stylesheet" href="style/voyage1.css">
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

    <main>
        
        <div class="nom">
            <h1><?php echo htmlspecialchars($voyage['titre']); ?></h1>
        </div>
        <div class="photos">
            <img src="<?php echo file_exists("assets/voyages/". $voyage["id"] . "/miniature.png") ? "assets/voyages/". $voyage["id"] . "/miniature.png" : "assets/no_photo.jpg"; ?>" alt="Image du voyage">
        </div>
        <div class="texte">
            <p><?php echo htmlspecialchars($voyage['texte']); ?></p>
            <p><strong>Durée:</strong> <?php echo duree($voyage); ?> jours</p>
            <p><strong>Prix:</strong> <?php echo htmlspecialchars($voyage['prix']); ?> € par personne</p>
            <p><strong>Pays:</strong> <?php echo htmlspecialchars($voyage['pays']); ?></p>
        </div>

        <div class="etapes">
            <?php foreach ($voyage['etapes'] as $index => $etape): ?>
                <div class="etape">
                    <div class="etape-info">
                        <h4><?php echo htmlspecialchars($etape['secteur']); ?></h4>
                        <p><strong>Poisson ciblé:</strong> <?php echo htmlspecialchars($etape['poisson']); ?></p>
                        <p><strong>Durée de l'étape:</strong> <?php echo htmlspecialchars($etape['nb_jours']); ?> jours</p>
                        <?php if (!empty($etape["options"])): ?>
                            <div class="options">
                                <h5>Options disponibles :</h5>
                                <ul>
                                    <?php foreach ($etape["options"] as $option): ?>
                                        <li><?php echo $option["nom"]; ?> <span><?php echo $option["prix"]; ?>€</span></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="etape-image">
                        <img src="<?php echo htmlspecialchars(file_exists("assets/voyages/".$voyage["id"]."/".($index+1).".jpg") ? "assets/voyages/".$voyage["id"]."/".($index+1).".jpg" : "assets/no_photo.jpg"); ?>" alt="Image de l'étape">
                    </div>
                    
                </div>
                
            <?php endforeach; ?>
        </div>
        <div class="reservation">
            <button onclick="window.location.href='recapitulatif.php?id=<?php echo $voyage['id']; ?>'">Réserver</button>
        </div>
    </main>

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
<?php
else:
    echo "<p>Voyage non trouvé.</p>";
endif;
?>
