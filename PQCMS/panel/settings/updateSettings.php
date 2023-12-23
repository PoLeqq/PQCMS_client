<?php

@session_start();
require_once(dirname(__DIR__)."/scripts/notifications/NotificationManager.inc.php");

$notificationManager = new NotificationManager("settings-updateSettings","Ustawienia - Ustawienia systemowe");
if(empty($_POST["token"]) || $_POST["token"] != $_SESSION["pqcms-panel-settings-settings-token"])
{
    $notificationManager->addNotification("e","Walidacja tokenu nie powiodła się.");
    header("location: ./");
    die("Niepoprawne przekierowanie");
}

if(time() >= $_SESSION["pqcms-panel-settings-settings-token-expire"])
{
    $notificationManager->addNotification("e","Token jest przestarzały. Przeładuj stronę!");
    header("location: ./");
    die("Niepoprawne przekierowanie");
}

require_once("updateData.php");

$response = updateSettings((int) $_POST["login_count"], isset($_POST["login_count_reset"]),
    (int) $_POST["login_session_time"], isset($_POST["login_session_time_reset"]),
    (int) $_POST["token_lifespan"], isset($_POST["token_lifespan_reset"]));

$notificationManager->addNotification($response["suc"] ? "s" : "e",$response["desc"]);
header("location: ./");
die("Niepoprawne przekierowanie");
//endScript($response["suc"],$response["desc"]);

//function endScript(bool $suc, string $desc): void
//{
//    $_SESSION["pqcms-panel-settings-settings-suc"] = $suc;
//    $_SESSION["pqcms-panel-settings-settings-desc"] = $desc;
//    header("location: ./");
//    die($_SESSION["pqcms-panel-settings-settings-desc"]." Błędne przekierowanie.");
//}