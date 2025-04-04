<?php

    session_start();

    // retour de l'API
    $transaction = $_GET['transaction'] ?? '';
    $montant = $_GET['montant'] ?? '';
    $vendeur = $_GET['vendeur'] ?? '';
    $statut = $_GET['status'] ?? '';
    $control_recu = $_GET['control'] ?? '';

    // reverif du control
    require('../include/getapikey.php');
    $api_key = "zzzz";
    $vendeur = 'MI-5_D' ;
    $api_key = getAPIKey($vendeur);
    $control = md5($api_key. "#" . $transaction. "#" . $montant. "#" . $vendeur. "#" . $statut . "#");
    if ($control != $control_recu) {
        $_SESSION["error"] = "wrong";
        header("Location: ../payment.php");
    }

    // verif du status et redirection
    if ($statut === "accepted") {
        // enregistrement de la transaction
        $content = file_get_contents("../data/transactions.json");
        $transactions = json_decode($content, true);
        $new_transaction = array(
            "transaction" => $transaction,
            "date" => date("d-m-Y H:i:s"), // "14-02-2025 12:30:45"
            "email" => $_SESSION["user"]["email"],
            "reservation_id" => $_SESSION["resaID"],
            "montant" => $montant
        );
    
        $transactions[] = $new_transaction;
        file_put_contents("../data/transactions.json", json_encode($transactions, JSON_PRETTY_PRINT));

        $_SESSION["payment"] = "accepted";
        header("Location: ../payment.php"); // TODO : pages des voyages réservés
    } else {
        $_SESSION["payment"] = "declined";
        header("Location: ../payment.php");
    }
    

?>