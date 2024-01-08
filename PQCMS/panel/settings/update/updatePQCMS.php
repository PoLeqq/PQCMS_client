<?php

@session_start();
require_once(dirname(__DIR__,2)."/scripts/notifications/NotificationManager.inc.php");
$notificationManager = new NotificationManager("settings-updatePQCMS","Ustawienia - PQCMS");
$notificationManagerError = new NotificationManager("settings-updatePQCMS-perms","Ustawienia - PQCMS");

if(empty($_POST["token"]) || $_POST["token"] != $_SESSION["pqcms-panel-settings-system-token"])
{
    $notificationManager->addNotification("e","Walidacja tokenu nie powiodła się.");
    header("location: ../");
    die("Niepoprawne przekierowanie");
}

if(time() >= $_SESSION["pqcms-panel-settings-system-token-expire"])
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
            "pqcms.settings.pqcms.set.username","pqcms.settings.pqcms.set.licensekey"
        ]]);
    if($userPerms["resp"] == 0)
    {
        $notificationManager->addNotification("e","Token jest przestarzały. Przeładuj stronę!");
        header("location: ../");
        die("Niepoprawne przekierowanie");
    }

    $userPerms = $userPerms["perms"];
    $dataToSave = ["username" => null,"licensekey" => null];

    foreach(array_keys($dataToSave) as $data)
    {
        if(!empty($_POST[$data]))
            if($userPerms["pqcms.settings.pqcms.set.$data"])
                $dataToSave[$data] = $_POST[$data];
            else
                $notificationManagerError->addNotification("e","Niektóre pola nie zostały zmienione, ponieważ nie posiadasz odpowiednich uprawnień!");
    }
}

require_once("UpdateData.inc.php");
$response = updatePQCMS($dataToSave["username"],$dataToSave["licensekey"]);

$notificationManager->addNotification($response["suc"] ? "s" : "e",$response["desc"]);
header("location: ../");
die("Niepoprawne przekierowanie");