<?php

header("Content-type: application/json");

if(empty($_GET["name"]))
    die(json_encode(["suc" => 0, "desc" => "Nie podano nazwy powiadomienia!"],JSON_UNESCAPED_UNICODE));
if(empty($_GET["action"]))
    die(json_encode(["suc" => 0, "desc" => "Nie podano akcji!"],JSON_UNESCAPED_UNICODE));


@session_start();
switch($_GET["action"])
{
    case "a": {
        if(empty($_GET["text"]))
            die(json_encode(["suc" => 0, "desc" => "Nie podano tekstu!"],JSON_UNESCAPED_UNICODE));

        $_SESSION["pqcms"]["panel"]["notifications"][$_GET["id"]]["text"] = $_GET["text"];

        if(isset($_GET["type"]))
            $_SESSION["pqcms"]["panel"]["notifications"][$_GET["id"]]["type"] = $_GET["type"];

        if(isset($_GET["title"]))
            $_SESSION["pqcms"]["panel"]["notifications"][$_GET["id"]]["type"] = $_GET["title"];
        else
            $_SESSION["pqcms"]["panel"]["notifications"][$_GET["id"]]["type"] = $_GET["id"];

        break;
    }
    case "d": {
        unset($_SESSION["pqcms"]["panel"]["notifications"][$_GET["name"]]);
        break;
    }
    default: {
        die(json_encode(["suc" => 0, "desc" => "Podano niepoprawną akcję!"]));
    }
}

echo json_encode(["suc" => 1]);