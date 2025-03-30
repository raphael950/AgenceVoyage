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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de la recherche</title>
    <link rel="stylesheet" href="style/reecherche.css">
</head>
<body>
    <h1>Résultats de la recherche</h1>
    <?php if (count($results) > 0): ?>
        <ul>
            <?php foreach ($results as $result): ?>
                <li>
                    <h3><?= htmlspecialchars($result['titre']) ?></h3>
                    <p><?= htmlspecialchars($result['texte']) ?></p>
                    <p><strong>Pays :</strong> <?= htmlspecialchars($result['pays']) ?></p>
                    <a href="voyage.php?id=<?= $result['id'] ?>">Voir les détails</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun résultat trouvé pour votre recherche.</p>
    <?php endif; ?>
    <a href="avancee.php">Retour à la recherche</a>
</body>
</html>