<?php
    session_start();

    $email = trim($_POST["mail"] ?? '');
    $pass = trim($_POST["pass"] ?? '');

    if (empty($email) || empty($pass)) {
        header("Location: login.php");
        $_SESSION["error"] = "Email ou mot de passe vide.";
        exit();
    }

    $content = file_get_contents("data/users.json");
    $users = json_decode($content, true);

    $found = false;
    foreach ($users as &$user) {
        if (!isset($user["email"]) || $user["email"] != $email) continue;

        $found = true;
        if (!isset($user["password"])) {
            $_SESSION["error"] = "Email ou mot de passe vide.";
            break;
        }
        if (password_verify($pass, $user["password"])) {
            if (!isset($user["username"])) {
                $_SESSION["error"] = "Votre compte n'a pas d'identifiant.";
                break;
            }

            // TODO: Authentification
            $_SESSION["user"] = $user;
            header("Location: profile2.php");
            exit();
        }
        $_SESSION["error"] = "Mot de passe incorrect";
        break;
    }

    if (!$found) $_SESSION["error"] = "Email non trouvée.";
    header("Location: login.php");
    
?>