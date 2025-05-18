<?php

    $nom = trim($_POST["nom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $gender = $_POST['sexe'];
    $date = $_POST['date'];
    $adresse = $_POST['adresse'];

    $content = file_get_contents("../data/users.json");
    $users = json_decode($content, true);

    foreach ($users as &$user) {
        if (!isset($user["email"]) || $email != $user["email"]) continue;

        // User is found in the json

        $user["username"] = $nom;

        // TODO: check if the new email is not already in use
        $user["email"] = $email;
        $user["gender"] = $gender;
        $user["birth"] = $date;
        $user["adresse"] = $adresse;
    }

    $update_ok = file_put_contents("../data/users.json", json_encode($users, JSON_PRETTY_PRINT)) != false;
    // echo json_encode(["success" => true, "message" => "profil modifié"]); // reponse de l'API
    // header("Location: ../profile2.php");

    header('Content-Type: application/json');
    if ($update_ok == true) {
        echo json_encode(['success' => true, "message" => "profil modifié"]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
    }
    
?>
