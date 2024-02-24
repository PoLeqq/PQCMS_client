<?php

// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("settings");

// Sprawdzenie, czy nie wystąpiły inne błędy
require_once "SettingsValues.inc.php";
$settingsValues = new SettingsValues();
//var_dump($settingsValues->getUserPerms());
if(!$settingsValues->areUserPermsValid())
    die("Wystąpił błąd podczas sprawdzania uprawnień! Ze względów bezpieczeństwa nie masz dostępu do tej strony. Skontaktuj się z administratorem PQCMS!");
$userPerms = $settingsValues->getUserPerms()["perms"];
if(!$userPerms["pqcms.tabs.view.settings"])
    die("Nie masz uprawnień, aby przeglądać tą stronę!");

// Sprawdzenie, czy połączenie z bazą danych istnieje - jeśli nie, doda się powiadomienie
require_once(dirname(__DIR__, 2) . "/utils/database/Database.inc.php");
Database::getConnection();

require_once(dirname(__DIR__,2)."/utils/PQCMSToken.inc.php");
$_SESSION["pqcms"]["panel"]["settings"]["system"]["token"] = PQCMSToken::generateToken();
$_SESSION["pqcms"]["panel"]["settings"]["database"]["token"] = PQCMSToken::generateToken();
$_SESSION["pqcms"]["panel"]["settings"]["settings"]["token"] = PQCMSToken::generateToken();

// Pobieranie ustawień z serwera
$websiteSettingsResponse = Communicator::communicate(CommunicateURL::GET_SETTINGS);

require_once "SettingsHTML.inc.php";
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../default.css">

    <style>
        #form-run-overlay {
            visibility: hidden;
            opacity: 0;
            transition: all .5s;
            background-color: rgba(0,0,0,.8);
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div id="form-run-overlay"></div>
    <div class="p-4">
        <div class="row col-12">
            <?php
            if($settingsValues->hasPermissionViewForm("pqcms"))
                echo SettingsHTML::getPQCMSForm($userPerms,$settingsValues);

            if($settingsValues->hasPermissionViewForm("database"))
                echo SettingsHTML::getDatabaseForm($userPerms,$settingsValues);

            if($settingsValues->hasPermissionViewForm("system"))
                echo SettingsHTML::getSystemForm($userPerms,$websiteSettingsResponse);

            ?>
            <div class="col-3 p-4">
                <h4>Licencja</h4>

                <?php
                $licenseExpiration = Communicator::communicate(CommunicateURL::GET_LICENSE_EXPIRATION);
                if($licenseExpiration["suc"] == 1)
                {
                    $licenseExpirationDate = is_null($licenseExpiration["license_expiration"]) ? "nigdy" : $licenseExpiration["license_expiration"];
                    echo<<<HTML
<p style="font-size: 20px">Licencja wygasa: $licenseExpirationDate</p>
HTML;
                }
                else
                    echo<<<HTML
<p style="color: red">
    ${licenseExpiration["desc"]}
</p>
HTML;

                ?>
            </div>
        </div>
    </div>

    <script>
        const formRunOverlay = document.querySelector("#form-run-overlay");

        document.querySelectorAll("form > fieldset > input[type=submit]").forEach((e) =>
        {
            e.addEventListener("click", () => {
               formRunOverlay.style.visibility = "visible";
               formRunOverlay.style.opacity = "1";
            });
        });
    </script>
</body>
</html>