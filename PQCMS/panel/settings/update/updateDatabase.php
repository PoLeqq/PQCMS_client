<?php

@session_start();
require_once(dirname(__DIR__,2)."/scripts/notifications/NotificationManager.inc.php");

$notificationManager = new NotificationManager("settings-updateDatabase","Ustawienia - Baza danych");
$notificationManagerError = new NotificationManager("settings-updateDatabase-perms","Ustawienia - Baza danych");
require_once(dirname(__DIR__,3)."/utils/PQCMSToken.inc.php");
PQCMSToken::verifyToken($notificationManager,$_SESSION["pqcms"]["panel"]["settings"]["database"]["token"], $_POST["token"]);


require_once(dirname(__DIR__,3)."/Communicator.inc.php");
$userPerms = Communicator::communicate(CommunicateURL::HAS_PERMISSION,
    ["perms" => [
        "pqcms.settings.database.set.host","pqcms.settings.database.set.username","pqcms.settings.database.set.password","pqcms.settings.database.set.name",
    ]]);
if($userPerms["suc"] == 0)
{
    $notificationManager->addNotification("e","Nic nie zmieniono, ponieważ wystąpił błąd podczas pobierania uprawnień. Skontaktuj się z administratorem PQCMS!");
    header("location: ../");
    die("Niepoprawne przekierowanie");
}

$userPerms = $userPerms["perms"];
$dataToSave = ["host" => null,"username" => null,"password" => null,"name" => null];

foreach(array_keys($dataToSave) as $data)
{
    if(!empty($_POST[$data]))
    {
        if($userPerms["pqcms.settings.database.set.$data"])
            $dataToSave[$data] = $_POST[$data];
        else
            $notificationManagerError->addNotification("e","Niektóre pola nie zostały zmienione, ponieważ nie posiadasz odpowiednich uprawnień!");
    }
}

require_once("UpdateData.inc.php");
$response = updateDatabase($dataToSave["host"],$dataToSave["username"],$dataToSave["password"],$dataToSave["name"]);

$notificationManager->addNotification($response["suc"] ? "s" : "e",$response["desc"]);
if(isset($response["conn_err"]) && $response["conn_err"])
    $notificationManager->addNewNotification( "settings-updateDatabase-warning","Ustawienia - Baza danych","w","Ostatnia zmiana spowodowała utratę połączenia z bazą danych!");
header("location: ../");
die("Niepoprawne przekierowanie");