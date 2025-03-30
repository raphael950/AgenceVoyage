<?php
    session_start();

    // Récupération de l'ID du voyage depuis l'URL
    // $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Recup l'ID de la resa depuis la session
    $idReservation = intval($SESSION["resaID"]);
    // recup de la reservation
    $reservations = json_decode(file_get_contents('data/reservations.json'), true);

    // recup des precedents paiements lies a la reservation

    // TODO: Recup les precedents paiements liés puis trouver ce qu'il reste à payer

    // Chargement des données des voyages
    $json = file_get_contents("data/voyages.json");
    $voyages = json_decode($json, true);

    // Recherche du voyage correspondant à l'ID
    $voyage = null;
    foreach ($voyages as $v) {
        if ($v['id'] === 1) { // 1 c'est hardcodé en attendant les autres voyages
            $voyage = $v;
            break;
        }
    }
    /* Si le voyage n'est pas trouvé, rediriger vers la page d'accueil
    if(!$voyage) {
        header("Location: index.php");
        exit();
    }*/

    // mise en session de l'id du voyage pour plus tard
    $_SESSION["voyage"] = $voyage;

    // parcours du fichier transactions pour donner l'id de transaction
    $json_transactions = file_get_contents("data/transactions.json");
    $transactions = json_decode($json_transactions, true);

    require('getapikey.php');
    $api_key = "zzzz";
    $vendeur = 'MI-5_D' ;
    $api_key = getAPIKey($vendeur);
    /*
    if(preg_match("/^[0-9a-zA-Z]{15}$/", $api_key)) {
        echo "API Key valide";
    }*/
    $transaction = str_pad(count($transactions), 10, "0", STR_PAD_LEFT); // jsp pk mais l'API ne prends pas en charge les int ni les str !=10 
    $montant = "1000.00"; // RECUP MONTANT RESTANT
    $retour = "http://localhost:8080/payment_check.php";
    $control = md5($api_key. "#" . $transaction. "#" . $montant. "#" . $vendeur. "#" . $retour . "#");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement : <?= htmlspecialchars($voyage['titre']); ?></title>
    <link rel="stylesheet" href="style/payment.css">
</head>
<body>
    <div id="main-card">
        <h1>Paiement</h1>
        <h2>Récapitulatif du voyage</h2>
        <p><strong>Titre :</strong> <?= htmlspecialchars($voyage['titre']); ?></p>
        <p><strong>Dates :</strong> <?= htmlspecialchars($voyage['duree']); ?></p>
        <p><strong>Prix :</strong> <?= htmlspecialchars($voyage['prix']); ?> €</p>

        <?php
            if(isset($_SESSION["payment"]) && $_SESSION["payment"] == "declined"){
                echo "PAIEMENT REFUSE, veuillez réesayer.";
            }
        ?>
        <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
            <input type='hidden' name='transaction' value='<?= $transaction ?>'>
            <input type='hidden' name='montant' value='<?= $montant ?>'>
            <input type='hidden' name='vendeur' value='<?= $vendeur ?>'>
            <input type='hidden' name='retour' value='<?= $retour ?>'>
            <input type='hidden' name='control' value='<?= $control ?>'>
            <input type='submit' value="Valider et payer">
        </form>
    </div>
</body>
</html>