<?php

@session_start();
if(empty($_SESSION["pqcms"]["panel"]["auth_key"]))
{
    header("location: ../");
    die("Najpierw musisz się zalogować!");
}

if(empty($_GET["name"]) || !is_string($_GET["name"]))
    die(json_encode(["suc" => 0, "desc" => "Pole \"name\" musi być napisem!"], JSON_UNESCAPED_UNICODE));

require_once(dirname(__DIR__, 3) . "/Communicator.inc.php");
$resp = Communicator::communicate(CommunicateURL::DELETE_RANK, ["name" => $_GET["name"]]);

require_once(dirname(__DIR__, 2) . "/scripts/notifications/NotificationManager.inc.php");
$type = $resp["suc"] == 1 ? "s" : "e";
NotificationManager::addNewNotification("hr-deleteRank-" . $_GET["name"], "HR - Usuwanie rangi", $type, $resp["desc"] . " (${_GET["name"]})");
die(json_encode($resp, JSON_UNESCAPED_UNICODE));