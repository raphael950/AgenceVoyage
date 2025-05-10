<?php
    session_start();

    // récupérer les paramètres de recherche
    $search = trim($_GET['search'] ?? '');
    $country = trim($_GET['country'] ?? '');
    $travelers = intval($_GET['travelers'] ?? 0);

    // charger les données des voyages
    $voyages = json_decode(file_get_contents('data/voyages.json'), true);
    $results = [];

    // parcourir les voyages pour trouver des correspondances
    foreach ($voyages as $voyage) {
        $match = true;

        // vérifier le champ "search" dans titre, texte, secteur, poissons
        if (!empty($search)) {
            $foundInTitle = stripos($voyage['titre'], $search) !== false;
            $foundInText = stripos($voyage['texte'], $search) !== false;
            $foundInCountry = stripos($voyage['pays'], $search) !== false;

            // vérifier dans les secteurs et poissons
            $foundInSecteur = false;
            $foundInPoissons = false;
            foreach ($voyage['etapes'] as $etape) {
                if (stripos($etape['secteur'], $search) !== false) {
                    $foundInSecteur = true;
                }
                if (stripos($etape['poisson'], $search) !== false) {
                    $foundInPoissons = true;
                }
            }

            if (!$foundInTitle && !$foundInText && !$foundInCountry && !$foundInSecteur && !$foundInPoissons) {
                $match = false;
            }
        }

        // vérifier le pays
        if (!empty($country) && stripos($voyage['pays'], $country) === false) {
            $match = false;
        }

        // ajouter le voyage aux résultats s'il correspond
        if ($match) {
            $results[] = $voyage;
        }
    }

    // pagination
    $travelsPerPage = 5;
    $totalTravels = count($results);
    $totalPages = ceil($totalTravels / $travelsPerPage); // ceil = arrondir float
    $currentPage = isset($_GET['page']) ? max(1, min($totalPages, intval($_GET['page']))) : 1;
    $startIndex = ($currentPage - 1) * $travelsPerPage;
    $resultsToDisplay = array_slice($results, $startIndex, $travelsPerPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche voyages avancée</title>
    <link id="theme-style" rel="stylesheet" href="style/search_results.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
    <script src="script/theme.js"></script>
</head>
<body>
    <nav>
        <a href="index.php">
            <img src="assets/logo2.png" class="logo" alt="logo">
        </a>
        <div class="nav-buttons">
            <button class="button" id="nav-button" onclick="switchTheme('style/search_results')">
                <i class="fa-regular fa-lightbulb"></i>
            </button>
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
    <main>
        <h1>Résultats de la recherche</h1>
        <div class="sort-controls">
            <p><strong>Trier par :</strong>
                <button onclick="sortResults('prix')">Prix</button>
                <button onclick="sortResults('duree')">Durée</button>
                <button onclick="sortResults('etapes')">Nombre d'étapes</button>
            </p>
        </div>

        <div class="results"></div>
        <div class="pagination"></div>

        
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
    <script>
        const allResults = <?= json_encode($results) ?>;
    </script>
    <script src="script/tris.js"></script>

</body>
</html>