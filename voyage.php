<?php

session_start();

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
            <img src="<?php echo isset($voyage['images'][0]) ? htmlspecialchars($voyage['images'][0]) : ''; ?>" alt="Image du voyage">
        </div>
        <div class="texte">
            <p><?php echo htmlspecialchars($voyage['texte']); ?></p>
            <p><strong>Durée:</strong> <?php echo htmlspecialchars($voyage['duree']); ?></p>
            <p><strong>Prix:</strong> <?php echo htmlspecialchars($voyage['prix']); ?> €</p>
            <p><strong>Pays:</strong> <?php echo htmlspecialchars($voyage['pays']); ?></p>
        </div>

        <div class="etapes">
            <?php foreach ($voyage['etapes'] as $index => $etape): ?>
                <div class="etape">
                    <div class="etape-info">
                        <h4>Secteur: <?php echo htmlspecialchars($etape['secteur']); ?></h4>
                        <p><strong>Poisson ciblé:</strong> <?php echo htmlspecialchars($etape['poisson']); ?></p>
                        <p><strong>Options:</strong> <?php echo implode(', ', array_map('htmlspecialchars', $etape['options'])); ?></p>
                    </div>
                    <div class="etape-image">
                        <img src="<?php echo htmlspecialchars($voyage['images'][$index + 1] ?? $voyage['images'][0]); ?>" alt="Image de l'étape">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="reservation">
            <button onclick="window.location.href='reservation.php?id=<?php echo $voyage['id']; ?>'">Réserver</button>
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
