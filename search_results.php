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
    <link rel="stylesheet" href="style/search_results.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
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
                    echo '<a href="logout.php" id="nav-button">Se déconnecter <i class="fa-solid fa-right-from-bracket"></i></a>';
                } else {
                    echo '<a href="register.php" id="nav-button">S\'inscrire</a>';
                    echo '<a href="login.php" id="nav-button">Se connecter</a>';
                }
            ?>
        </div>
    </nav>
    <main>
        <h1>Résultats de la recherche</h1>
        <div class="results">
            <?php if (count($results) > 0): ?>
                <?php foreach ($resultsToDisplay as $result): ?>
                    <?php $imageUrl = "assets/voyages/". $result["id"] . "/miniature.png";?>
                    <div class="card">
                        <img src="<?= $imageUrl ?>" alt="Image de <?= $imageUrl ?>">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($result['titre']) ?></h3>
                            <p><?= htmlspecialchars($result['texte']) ?></p>
                            <p><strong>Pays :</strong> <?= htmlspecialchars($result['pays']) ?></p>
                            <a href="voyage.php?id=<?= $result['id'] ?>">Voir les détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun résultat trouvé pour votre recherche.</p>
            <?php endif; ?>
        </div>
        <a href="avancee.php" id="back">Retour à la recherche</a>

        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?search=<?= $search ?>&country=<?= $country ?>&travelers=<?= $travelers ?>&page=<?= $currentPage - 1 ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?search=<?= $search ?>&country=<?= $country ?>&travelers=<?= $travelers ?>&page=<?= $i ?>" <?= $i === $currentPage ? 'style="font-weight: bold;"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?search=<?= $search ?>&country=<?= $country ?>&travelers=<?= $travelers ?>&page=<?= $currentPage + 1 ?>">Suivant</a>
            <?php endif; ?>
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