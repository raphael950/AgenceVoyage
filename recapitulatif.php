<?php
    session_start();

    // user non connecté mais se rend sur la page quand meme via URL
    if (!isset($_SESSION["user"])) {
        header("Location: login.php");
        exit();
    }
    
    $voyages = json_decode(file_get_contents('data/voyages.json'), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // recup données du formulaire
        $voyage_id = (int) $_POST['voyage_id'];
        $date_depart = $_POST['date_depart'];
        $nombre_personne = (int) $_POST['nombre_personne'];
        $options_souscrites = [];
        
        foreach ($voyages as $voyage) {
            if ($voyage['id'] == $voyage_id) {
                // Parcourir les étapes et les options
                foreach ($voyage['etapes'] as $etape) {
                    foreach ($etape['options'] as $option) {
                        if (isset($_POST['options'][$option['nom']])) {
                            // calcul du prix pour les options de type 'individuel'
                            if ($option['type'] == 'individuel') {
                                $quantite_option = $_POST['options'][$option['nom']];
                                if ((int) $quantite_option == 0) continue;
                                $options_souscrites[$option['nom']] = (int) $quantite_option;
                            } else {
                                // calcul du prix pour les options de type 'groupe'
                                $options_souscrites[$option['nom']] = 1;
                            }
                        }
                    }
                }
            }
        }
        
        // Charger les réservations existantes
        $reservations = json_decode(file_get_contents('data/reservations.json'), true);

        $resa_id = count($reservations) + 1;
        
        // Ajouter la nouvelle réservation
        $reservations[] = [
            'id' => $resa_id,
            'user_id' => $_SESSION["user"]["id"],
            'voyage_id' => $voyage_id,
            'date_depart' => $date_depart,
            'nombre_personne' => $nombre_personne,
            'options_souscrites' => $options_souscrites
        ];
        
        // Sauvegarder les réservations mises à jour
        file_put_contents('data/reservations.json', json_encode($reservations, JSON_PRETTY_PRINT));

        $_SESSION["resaID"] = $resa_id;

        // panier :
        $_SESSION["panier"]["id"] = $voyage_id;
        $_SESSION["panier"]["options"] = $options_souscrites;
        $_SESSION["panier"]["nbpersonne"] = $nombre_personne;
        
        // Redirection vers la page de paiement
        header('Location: payment.php');
        exit;
    }

    if(isset($_GET["id"])){
        foreach ($voyages as $voyage) {
            if ($voyage['id'] == $_GET["id"]) {
                $prix = $voyage['prix']; // pour transmettre la variable au js

                // panier :
                // $_SESSION["panier"]["id"] = $voyage['id'];
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation de Voyage</title>
    <link id="theme-style" rel="stylesheet" href="style/recapitulatif.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
    <script src="script/recapitulatif.js" defer></script>
    <script src="script/theme.js"></script>

</head>
<body data-prixPersonne="<?php echo $prix; ?>">
    <div class="bg-image"></div>
    <div id="main-card">
        <a href="<?php echo "voyage.php?id=".$_GET["id"]; ?>">
            <i class="fa-solid fa-arrow-left"></i> Retour page précédente
        </a>
        <h1>Réservez votre voyage</h1>
        <form method="post">
            <label for="voyage">Choisissez un voyage :</label>
            <select name="voyage_id" id="voyage" required>
                <?php foreach ($voyages as $voyage) : ?>
                    <option value="<?= $voyage['id'] ?>" <?= (isset($_GET["id"]) && $voyage['id'] == $_GET["id"]) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($voyage['titre']) ?> - <?= $voyage['prix'] ?>€ par pers.
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date_depart">Date de départ :</label>
            <input type="date" name="date_depart" id="date_depart" required>
            <p id="erreurdate" style="color: red;"></p>

            <label for="nombre_personne">Nombre de personnes :</label>
            <input type="number" name="nombre_personne" id="nombre_personne" min="1" value="1" required>

            <h2>Options</h2>
            <?php foreach ($voyages as $voyage) : ?>
            <div class="options" id="options-<?= $voyage['id'] ?>" style="display:none;">
                <?php foreach ($voyage['etapes'] as $etape) : ?>
                    <h3><?= htmlspecialchars($etape['secteur']) ?></h3>
                    <?php foreach ($etape['options'] as $option) : ?>
                        <label>
                            <?php if ($option['type'] == 'individuel') : ?>
                                <span><?= htmlspecialchars($option['nom']) ?> - <?= $option['prix'] ?>€ (<?= htmlspecialchars($option['description']) ?>)</span>
                                <input type="number" name="options[<?= htmlspecialchars($option['nom']) ?>]" min="0" value="0" class="option-individuelle" data-prix="<?= $option['prix'] ?>">
                            <?php else : ?>
                                <input type="checkbox" name="options[<?= htmlspecialchars($option['nom']) ?>]" value="1" class="option-groupe" data-prix="<?= $option['prix'] ?>">
                                <?= htmlspecialchars($option['nom']) ?> - <?= $option['prix'] ?>€ (<?= htmlspecialchars($option['description']) ?>)
                            <?php endif; ?>
                        </label><br>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>


            <h3 id="prix_total">0</h3>

            <button type="submit">Réserver</button>
        </form>
    </div>

    <script>
    document.getElementById('voyage').addEventListener('change', function() {

        // masquer les options
        document.querySelectorAll('.options').forEach(div => div.style.display = 'none');

        var selectedVoyageId = this.value;
        var optionsContainer = document.getElementById('options-' + selectedVoyageId);
        if (optionsContainer) {
            optionsContainer.style.display = 'block';
        }
    });

    window.onload = function() {
        var selectedVoyageId = document.getElementById('voyage').value;
        if (selectedVoyageId) {
            var optionsContainer = document.getElementById('options-' + selectedVoyageId);
            if (optionsContainer) {
                optionsContainer.style.display = 'block';
            }
        }
    };
</script>
    
</body>
</html>
