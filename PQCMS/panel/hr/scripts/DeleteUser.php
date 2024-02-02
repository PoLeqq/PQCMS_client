<?php

@session_start();
if(empty($_SESSION["pqcms"]["panel"]["auth_key"]))
{
    header("location: ../");
    die("Najpierw musisz się zalogować!");
}

if(empty($_GET["username"]) || !is_string($_GET["username"]))
    die(json_encode(["suc" => 0, "desc" => "Pole \"username\" musi być napisem!"], JSON_UNESCAPED_UNICODE));

require_once(dirname(__DIR__, 3) . "/Communicator.inc.php");
$resp = Communicator::communicate(CommunicateURL::DELETE_USER, ["username" => $_GET["username"]]);

require_once(dirname(__DIR__, 2) . "/scripts/notifications/NotificationManager.inc.php");
$type = $resp["suc"] == 1 ? "s" : "e";
NotificationManager::addNewNotification("hr-deleteUser-" . $_GET["username"], "HR - Usuwanie użytkownika", $type, $resp["desc"] . " (${_GET["username"]})");
die(json_encode($resp, JSON_UNESCAPED_UNICODE));