<?php

    $content = file_get_contents("../data/users.json");
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
        $update_ok = file_put_contents("../data/users.json", json_encode($users, JSON_PRETTY_PRINT)) != false;
        return $update_ok;
    }
    
    function ban($users, $client_id){
        foreach ($users as $key => $user) {
            if ($user['id'] == $client_id) {
                unset($users[$key]);
                break;
            }
        }
        $update_ok = file_put_contents("../data/users.json", json_encode($users, JSON_PRETTY_PRINT)) != false;
        return $update_ok;
    }

    $action = $_POST['action']; // récupére le but du bouton cliqué
    switch ($action){
        case "vip":
            $update_ok = give_vip($users, $client_id);
            break;
        case "ban":
            $update_ok = ban($users, $client_id);
            break;
        default:
            echo "Action non reconnue.";
            break;
    }

    //$update_ok = file_put_contents("../data/users.json", json_encode($users, JSON_PRETTY_PRINT)) != false;
    //header('Content-Type: application/json');
    sleep(3);
    header('Content-Type: text/plain');
    if ($update_ok == true) {
        http_response_code(200);
        // echo "OK";
    } else {
        http_response_code(500);
        // echo "ERR";
    }
?>