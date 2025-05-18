<?php
    header("Content-Type: application/json");
    include("../include/voyage_utils.php");

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["error" => "Données invalides"]);
        exit;
    }

    $voyage_id = $data["voyage_id"];
    $nombre_personnes = intval($data["nombre_personnes"]);
    $options_individuelles = $data["options_individuelles"] ?? [];
    $options_groupes = $data["options_groupes"] ?? [];

    $options_souscrites = [];

    foreach ($options_individuelles as $opt) {
        if ($opt["quantite"] > 0) {
            $options_souscrites[$opt["nom"]] = $opt["quantite"];
        }
    }

    foreach ($options_groupes as $nomOption) {
        $options_souscrites[$nomOption] = 1;
    }

    $reserv = [
        "voyage_id" => $voyage_id,
        "nombre_personne" => $nombre_personnes,
        "options_souscrites" => $options_souscrites
    ];

    $total = prixTotalExt($reserv);

    echo json_encode([
        "prix_total" => round($total, 2)
    ]);

?>