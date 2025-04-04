<?php

    $content = file_get_contents("data/users.json");
    $users = json_decode($content, true);
    $client_id = intval($_POST['client_id']);

    function give_vip($users, $client_id){
        foreach ($users as &$user) {
            if ($client_id != $user['id']) continue;
            if($user["role"] == "vip"){
                $user["role"] = "client";
            }
            else{
                $user["role"] = "vip";
            }
        }
        file_put_contents("data/users.json", json_encode($users, JSON_PRETTY_PRINT));
    }
    
    function ban($users, $client_id){
        foreach ($users as $key => $user) {
            if ($user['id'] == $client_id) {
                unset($users[$key]);
                break;
            }
        }
        file_put_contents("data/users.json", json_encode($users, JSON_PRETTY_PRINT));
    }

    $action = $_POST['action']; // récupére le but du bouton cliqué
    switch ($action){
        case "vip":
            give_vip($users, $client_id);
            break;
        case "ban":
            ban($users, $client_id);
            break;
        default:
            echo "Action non reconnue.";
            break;
    }

    header("Location: admin2.php");
?>