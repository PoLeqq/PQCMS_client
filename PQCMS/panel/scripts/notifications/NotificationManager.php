<?php

header("Content-type: application/json");

if(empty($_GET["id"]))
    die(json_encode(["suc" => 0, "desc" => "Nie podano id powiadomienia!"],JSON_UNESCAPED_UNICODE));
if(empty($_GET["action"]))
    die(json_encode(["suc" => 0, "desc" => "Nie podano typu akcji!"],JSON_UNESCAPED_UNICODE));

@session_start();
switch($_GET["action"])
{
    case "a": {
        require_once "NotificationManager.inc.php";

        if(empty($_GET["title"]))
            die(json_encode(["suc" => 0, "desc" => "Nie podano tytułu powiadomienia!"],JSON_UNESCAPED_UNICODE));
        if(empty($_GET["text"]))
            die(json_encode(["suc" => 0, "desc" => "Nie podano tekstu powiadomienia!"],JSON_UNESCAPED_UNICODE));

        NotificationManager::addNewNotification($_GET["id"],$_GET["title"],$_GET["type"],$_GET["text"]);

        break;
    }
    case "d": {
        unset($_SESSION["pqcms"]["panel"]["notifications"][$_GET["id"]]);
        break;
    }
    default: {
        die(json_encode(["suc" => 0, "desc" => "Podano niepoprawną akcję!"],JSON_UNESCAPED_UNICODE));
    }
}

echo json_encode(["suc" => 1]);