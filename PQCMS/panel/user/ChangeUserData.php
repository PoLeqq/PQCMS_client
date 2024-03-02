<?php

@session_start();

$posts = [];

//if(empty($_POST["username"]) && empty($_POST["nickname"]) && empty($_POST["password"]) && !isset($_POST["disabled"]))
//    endScript(0,"Żadne pole nie jest uzupełnione, więc nie można nic zmienić!");
if(empty($_SESSION["pqcms"]["panel"]["username"]))
    die("Najpierw się zaloguj!");
$posts["username"] = $_SESSION["pqcms"]["panel"]["username"];
if(!empty($_POST["nickname"]))
    $posts["nickname"] = $_POST["nickname"];
if(!empty($_POST["email"]))
    $posts["email"] = $_POST["email"];
else
    $posts["email"] = "";

if($posts === [])
    endScript(0,"Żadne pole nie jest uzupełnione, więc nie można nic zmienić!");

require_once(dirname(__DIR__, 2) . "/Communicator.inc.php");
$resp = Communicator::communicate(CommunicateURL::EDIT_USER,$posts);

endScript($resp["suc"],$resp["desc"]);

function endScript(int $suc, string $desc): void
{
    require_once(dirname(__DIR__)."/scripts/notifications/NotificationManager.inc.php");
    $type = $suc ? "s" : "e";
    NotificationManager::addNewNotification("edit-self","Edycja własnych danych",$type,$desc);

    header("location: ./");
    die($desc);
}

function parsePermsArray($array): ?array
{
    if(!is_array($array))
        return null;
    if(count($array) == 1 && (!key_exists("true",$array) && !key_exists("false",$array)))
        return null;
    if(count($array) == 2 && (!key_exists("true",$array) || !key_exists("false",$array)))
        return null;

    $perms = [];
    if(key_exists("true",$array))
        foreach($array["true"] as $perm => $value)
            if($value !== "on")
                return null;
            else
                $perms[$perm] = true;

    if(key_exists("false",$array))
        foreach($array["false"] as $perm => $value)
            if($value !== "on")
                return null;
            else
                $perms[$perm] = false;

    return $perms;
}