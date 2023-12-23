<?php

@session_start();
require_once(dirname(__DIR__)."/scripts/notifications/NotificationManager.inc.php");

$notificationManager = new NotificationManager("settings-updateDatabase","Ustawienia - Baza danych");
if(empty($_POST["token"]) || $_POST["token"] != $_SESSION["pqcms-panel-settings-database-token"])
{
    $notificationManager->addNotification("e","Walidacja tokenu nie powiodła się.");
    header("location: ./");
    die("Niepoprawne przekierowanie");
}

if(time() >= $_SESSION["pqcms-panel-settings-database-token-expire"])
{
    $notificationManager->addNotification("e","Token jest przestarzały. Przeładuj stronę!");
    header("location: ./");
    die("Niepoprawne przekierowanie");
}

require_once("updateData.php");
$response = updateDatabase($_POST["host"],$_POST["user"],$_POST["password"]);

$notificationManager->addNotification($response["suc"] ? "s" : "e",$response["desc"]);
if($response["conn_err"])
    $notificationManager->addNewNotification( "settings-updateDatabase-warning","Ustawienia - Baza danych","w","Ostatnia zmiana spowodowała utratę połączenia z bazą danych!");
header("location: ./");
die("Niepoprawne przekierowanie");