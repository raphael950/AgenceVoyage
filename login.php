<?php
    session_start();

    $email = $_POST["mail"];
    $email = isset($email) ? trim($email) : '';

    $pass = $_POST["pass"];
    $pass = isset($pass) ? trim($pass) : '';

    if (empty($email) || empty($pass)) {
        header("Location: login.html");
        exit();
    }

    // Check si l'utilisateur existe
    // le connecter

    
?>