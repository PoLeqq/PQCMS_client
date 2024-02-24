<?php

@session_start();
require_once(dirname(__DIR__,2)."/scripts/notifications/NotificationManager.inc.php");

$notificationManager = new NotificationManager("settings-updateSystem","Ustawienia - Ustawienia systemowe");
$notificationManagerError = new NotificationManager("settings-updateSystem-perms","Ustawienia - Ustawienia systemowe");

require_once(dirname(__DIR__,3)."/utils/PQCMSToken.inc.php");
PQCMSToken::verifyToken($notificationManager,$_SESSION["pqcms"]["panel"]["settings"]["settings"]["token"], $_POST["token"]);

// Sprawdzenie permisji, ustawienie danych
{
    require_once(dirname(__DIR__,3)."/Communicator.inc.php");
    $userPerms = Communicator::communicate(CommunicateURL::HAS_PERMISSION,
        ["perms" => [
            "pqcms.settings.system.set.loginattempts","pqcms.settings.system.set.loginsessiontime"
        ]]);
    if($userPerms["suc"] == 0)
    {
        $notificationManager->addNotification("e","Token jest przestarzały. Przeładuj stronę!");
        header("location: ../");
        die("Niepoprawne przekierowanie");
    }

    $userPerms = $userPerms["perms"];
    $dataToSave = ["loginattempts" => null,"loginsessiontime" => null];

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
    (int) $_POST["login_session_time"], isset($_POST["login_session_time_reset"]));

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