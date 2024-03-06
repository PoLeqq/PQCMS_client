<?php

$post = json_decode(file_get_contents("php://input"), true);
require_once(dirname(__DIR__,2)."/scripts/notifications/NotificationManager.inc.php");

if($post === null)
{
    NotificationManager::addNewNotification("addon-activation", "Zmiana aktywności dodatku","e","Otrzymano niepoprawne dane!");
    die(json_encode(["suc" => 0, "desc" => "Niepoprawne dane POST"]));
}
$post = $post["data"];

if(empty($post["addon"]) || !is_string($post["addon"]))
{
    NotificationManager::addNewNotification("addon-activation", "Zmiana aktywności dodatku","e","Niepoprawne pole \"addon\".");
    die(json_encode(["suc" => 0, "desc" => "Pole \"addon\" musi być napisem!"]));
}
if(!isset($post["enabled"]) || !is_bool($post["enabled"]))
{
    NotificationManager::addNewNotification("addon-activation", "Zmiana aktywności dodatku","e","Niepoprawne pole \"enabled\".");
    die(json_encode(["suc" => 0, "enabled" => $post["enabled"], "desc" => "Pole \"enabled\" musi być wartością 0 lub 1!"]));
}

$notifi = new NotificationManager("addon-activation-${post["addon"]}","Zmiana aktywności dodatku");
@session_start();
require_once(dirname(__DIR__,3)."/utils/PQCMSToken.inc.php");

$verifyToken = PQCMSToken::verifyTokenGetResponse($notifi,$_SESSION["pqcms"]["panel"]["addons"]["change-activation"]["token"],$post["token"]);
if($verifyToken["suc"] === 0)
{
    $verifyToken["token"] = $_SESSION["pqcms"]["panel"]["addons"]["change-activation"]["token"]["value"];
    $verifyToken["posttoken"] = $post["token"];
    die(json_encode($verifyToken,JSON_UNESCAPED_UNICODE));
}

$token = PQCMSToken::generateToken();
$_SESSION["pqcms"]["panel"]["addons"]["change-activation"]["token"] = $token;

require_once(dirname(__DIR__,3)."/addons/AddonManager.inc.php");
$addonManager = new AddonManager();
$addonManager->setEnabledAddon($post["addon"],$post["enabled"]);

die(json_encode(["suc" => 1, "desc" => "", "token" => $_SESSION["pqcms"]["panel"]["addons"]["change-activation"]["token"]["value"]]));