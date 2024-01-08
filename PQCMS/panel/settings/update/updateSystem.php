<?php

@session_start();
require_once(dirname(__DIR__,2)."/scripts/notifications/NotificationManager.inc.php");

$notificationManager = new NotificationManager("settings-updateSystem","Ustawienia - Ustawienia systemowe");
$notificationManagerError = new NotificationManager("settings-updateSystem-perms","Ustawienia - Ustawienia systemowe");
if(empty($_POST["token"]) || $_POST["token"] != $_SESSION["pqcms-panel-settings-settings-token"])
{
    $notificationManager->addNotification("e","Walidacja tokenu nie powiodła się.");
    header("location: ../");
    die("Niepoprawne przekierowanie");
}

if(time() >= $_SESSION["pqcms-panel-settings-settings-token-expire"])
{
    $notificationManager->addNotification("e","Token jest przestarzały. Przeładuj stronę!");
    header("location: ../");
    die("Niepoprawne przekierowanie");
}

// Sprawdzenie permisji, ustawienie danych
{
    require_once(dirname(__DIR__,3)."/Communicator.inc.php");
    $userPerms = Communicator::communicate(CommunicateURL::HAS_PERMISSION,
        ["perms" => [
            "pqcms.settings.system.set.loginattempts","pqcms.settings.system.set.loginsessiontime","pqcms.settings.system.set.tokenlifespan"
        ]]);
    if($userPerms["resp"] == 0)
    {
        $notificationManager->addNotification("e","Token jest przestarzały. Przeładuj stronę!");
        header("location: ../");
        die("Niepoprawne przekierowanie");
    }

    $userPerms = $userPerms["perms"];
    $dataToSave = ["loginattempts" => null,"loginsessiontime" => null,"tokenlifespan" => null];

    foreach(array_keys($dataToSave) as $data)
    {
        if(!empty($_POST[$data]))
            if($userPerms["pqcms.settings.system.set.$data"])
                $dataToSave[$data] = $_POST[$data];
            else
                $notificationManagerError->addNotification("e","Niektóre pola nie zostały zmienione, ponieważ nie posiadasz odpowiednich uprawnień!");
    }
}

require_once("UpdateData.inc.php");
$response = updateSettings((int) $_POST["login_count"], isset($_POST["login_count_reset"]),
    (int) $_POST["login_session_time"], isset($_POST["login_session_time_reset"]),
    (int) $_POST["token_lifespan"], isset($_POST["token_lifespan_reset"]));

$notificationManager->addNotification($response["suc"] ? "s" : "e",$response["desc"]);
header("location: ../");
die("Niepoprawne przekierowanie");
//endScript($response["suc"],$response["desc"]);

//function endScript(bool $suc, string $desc): void
//{
//    $_SESSION["pqcms-panel-settings-settings-suc"] = $suc;
//    $_SESSION["pqcms-panel-settings-settings-desc"] = $desc;
//    header("location: ./");
//    die($_SESSION["pqcms-panel-settings-settings-desc"]." Błędne przekierowanie.");
//}