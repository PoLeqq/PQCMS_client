<?php

@session_start();
require_once(dirname(__DIR__)."/scripts/notifications/NotificationManager.inc.php");
$notificationManager = new NotificationManager("settings-updateSystem","Ustawienia - PQCMS");

if(empty($_POST["token"]) || $_POST["token"] != $_SESSION["pqcms-panel-settings-system-token"])
{
    $notificationManager->addNotification("e","Walidacja tokenu nie powiodła się.");
    header("location: ./");
    die("Niepoprawne przekierowanie");
}

if(time() >= $_SESSION["pqcms-panel-settings-system-token-expire"])
{
    $notificationManager->addNotification("e","Token jest przestarzały. Przeładuj stronę!");
    header("location: ./");
    die("Niepoprawne przekierowanie");
}

require_once("updateData.php");
$response = updateSystem($_POST["user"],$_POST["license_key"]);

$notificationManager->addNotification($response["suc"] ? "s" : "e",$response["desc"]);
header("location: ./");
die("Niepoprawne przekierowanie");