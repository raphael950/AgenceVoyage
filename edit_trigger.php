<?php
    session_start();

    $nom = trim($_POST["nom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $genre = $_POST['sexe'];
    $date = $_POST['date'];
    $adresse = $_POST['adresse'];

    $content = file_get_contents("data/users.json");
    $users = json_decode($content, true);

    foreach ($users as &$user) {
        if (!isset($user["email"]) || $user["email"] != $_SESSION[ "user"]["email"]) continue;

        $user["username"] = $nom;
        $user["email"] = $email;
        $user["genre"] = $genre;
        $user["birth"] = $date;
        $user["adresse"] = $adresse;
    }

    file_put_contents("data/users.json", json_encode($users, JSON_PRETTY_PRINT));
    $_SESSION["user"]=$user;
    header("Location: profile2.html")
    
?>
