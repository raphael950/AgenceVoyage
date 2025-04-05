<?php
    session_start();

    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $pass = trim($_POST["password"] ?? '');

    if (empty($email) || empty($pass)) {
        header("Location: ../register.php");
        exit();
    }

    $content = file_get_contents("../data/users.json");
    $users = json_decode($content, true);

    // Check if email already exists
    foreach ($users as $user) {
        if (isset($user["email"]) && $user["email"] == $email) {
            $_SESSION["error"] = "Un compte utilise déjà cette adresse email.";
            header("Location: ../login.php");
            exit();
        }
    }

    // Create new user
    $new_user = array(
        "id" => count($users) + 1,
        "username" => $name,
        "email" => $email,
        "password" => password_hash($pass, PASSWORD_DEFAULT),
        "role" => "client"
    );

    $users[] = $new_user;
    file_put_contents("../data/users.json", json_encode($users, JSON_PRETTY_PRINT));
    $_SESSION["user"]=$new_user;
    header("Location: ../profile2.php");
    
?>