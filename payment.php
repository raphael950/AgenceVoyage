<?php
    session_start();
    include "include/voyage_utils.php";

    // user non connecté mais se rend sur la page quand meme via URL
    if (!isset($_SESSION["user"])) {
        header("Location: login.php");
        exit();
    }

    // Récupération de l'ID du voyage depuis l'URL
    // $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Recup l'ID de la resa depuis la session
    $idReservation = intval($_SESSION["resaID"]);
    // recup de la reservation
    $reservations = json_decode(file_get_contents('data/reservations.json'), true);
    $reservation;
    foreach ($reservations as $reserv) {
        if ($reserv["id"] == $idReservation) {
            $reservation = $reserv;
            break;
        }
    }

    // recup des precedentes transactions lies a la reservation
    $transactions = json_decode(file_get_contents('data/transactions.json'), true);
    $dejaPaye=[];
    $somme=0.0;
    foreach ($transactions as $transac) {
        if ($transac["reservation_id"] == $reservation["id"]) {
            $dejaPaye[] = $transac;
            $somme += floatval($transac["montant"]);
        }
    }
    $prixTotalReserv = prixTotal($reservation);
    $montantRestant = $prixTotalReserv - $somme;

    // Recherche du voyage correspondant à l'ID
    $voyage = voyageFromId($reservation["voyage_id"]);

    /* Si le voyage n'est pas trouvé, rediriger vers la page d'accueil
    if(!$voyage) {
        header("Location: index.php");
        exit();
    }*/

    // mise en session de l'id du voyage pour plus tard
    $_SESSION["voyage"] = $voyage;

    require('include/getapikey.php');
    $api_key = "zzzz";
    $vendeur = 'MI-5_D' ;
    $api_key = getAPIKey($vendeur);
    /*
    if(preg_match("/^[0-9a-zA-Z]{15}$/", $api_key)) {
        echo "API Key valide";
    }*/
    $transaction = str_pad(count($transactions)+1, 10, "0", STR_PAD_LEFT); // jsp pk mais l'API ne prends pas en charge les int ni les str !=10 
    $montant = $montantRestant;
    $retour = "http://localhost:8080/script/payment_check.php";
    $control = md5($api_key. "#" . $transaction. "#" . $montant. "#" . $vendeur. "#" . $retour . "#");

    // panier
    $_SESSION["panier"]["prix"] = $prixTotalReserv;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement : <?= htmlspecialchars($voyage['titre']); ?></title>
    <link id="theme-style" rel="stylesheet" href="style/payment.css">
    <script src="https://kit.fontawesome.com/1633e685ed.js" crossorigin="anonymous"></script>
    <script src="script/theme.js"></script>

</head>
<body>
    <div id="main-card">
        <h1>Paiement</h1>
        <h2>Récapitulatif du voyage</h2>
        <p><?= htmlspecialchars($voyage['titre']); ?></p>
        <p><strong>Durée du voyage :</strong> <?= htmlspecialchars(duree($voyage)); ?> jours</p>
        <p><strong>Date de départ :</strong> <?= htmlspecialchars($reservation["date_depart"]); ?></p>
        <p><strong>Vous avez déjà payé :</strong> <?= htmlspecialchars($somme); ?> €</p>
        <p><strong>Il vous reste à payer :</strong> <?= htmlspecialchars($montantRestant); ?> €</p>

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
        <a href="<?php echo "recapitulatif.php?id=".$voyage["id"]; ?>">
            <i class="fa-solid fa-arrow-left"></i> Retour à la personnalisation du voyage
        </a>
    </div>
</body>
</html>