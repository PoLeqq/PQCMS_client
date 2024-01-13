<?php

// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("settings");

require_once(dirname(__DIR__, 2) . "/config/data/JSONDatabase.php");
require_once(dirname(__DIR__, 2) . "/config/data/JSONPQCMS.php");

$databaseData = new JSONDatabase();
$pqcms = new JSONPQCMS();

//    TODO zrobić, aby te dane pobierały się z Communicatora (czas tokenu)
$tokenExpireTime = 600;
$_SESSION["pqcms"]["panel"]["settings"]["system"]["token"]["value"] = bin2hex(random_bytes(64));
$_SESSION["pqcms"]["panel"]["settings"]["system"]["token"]["expire"] = time() + $tokenExpireTime;

$_SESSION["pqcms"]["panel"]["settings"]["database"]["token"]["value"] = bin2hex(random_bytes(64));
$_SESSION["pqcms"]["panel"]["settings"]["database"]["token"]["expire"] = time() + $tokenExpireTime;

$_SESSION["pqcms"]["panel"]["settings"]["settings"]["token"]["value"] = bin2hex(random_bytes(64));
$_SESSION["pqcms"]["panel"]["settings"]["settings"]["token"]["expire"] = time() + $tokenExpireTime;

//    Pobieranie ustawień z serwera
require_once(dirname(__DIR__,2)."/Communicator.inc.php");
$websiteSettingsResponse = Communicator::communicate(CommunicateURL::GET_SETTINGS,[]);
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
            <form method="POST" action="updateSystem.php" class="col-3 p-4">
                <fieldset class="d-flex flex-column justify-content-center align-items-start" >
                    <legend class="">PQCMS</legend>

                    <input type="hidden" name="token" value="<?php echo $_SESSION["pqcms-panel-settings-system-token"] ?>">

                    <label class="mt-1">Użytkownik</label>
                    <input type="text" name="user" class="my-2 rounded-0" placeholder="użytkownik" value="<?php echo $pqcms->getLogin() ?>" />
                    
                    <label class="mt-1">Klucz licencyjny</label>
                    <input type="text" name="license_key" class="my-2 rounded-0" placeholder="klucz" value="<?php echo $pqcms->getLicenseKey() ?>" />

                    <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Aktualizuj">

                </fieldset>
            </form>
            <form method="POST" action="updateDatabase.php" class="col-3 p-4"> 
                <fieldset class="d-flex flex-column justify-content-center align-items-start" >
                    <legend class="">Baza Danych</legend>

                    <input type="hidden" name="token" value="<?php echo $_SESSION["pqcms-panel-settings-database-token"] ?>">

                    <label class="mt-1">Host</label>
                    <input type="text" name="host" class="my-2 rounded-0" placeholder="nazwa hosta" value="<?php echo $databaseData->getHost() ?>" />
                    
                    <label class="mt-1">Użytkownik</label>
                    <input type="text" name="user" class="my-2 rounded-0" placeholder="nazwa użytkownika" value="<?php echo $databaseData->getUser() ?>" />
                    
                    <label class="mt-1">Hasło</label>
                    <input type="text" name="password" class="my-2 rounded-0" placeholder="hasło" value="<?php echo $databaseData->getPassword() ?>" />

                    <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Aktualizuj">

                </fieldset>
            </form>
            <form method="POST" action="updateSettings.php" class="col-3 p-4">
                <fieldset class="d-flex flex-column justify-content-center align-items-start" >
                    <legend class="">Ustawienia</legend>

                    <?php
                    if($websiteSettingsResponse["suc"] == 0)
                        echo<<<END
                        <div class="error">
                            <p>
                                Brak połączenia z serwerem. Czy na pewno wpisane dane systemowe (Ustawienia -> PQCMS) są dobre?
                            </p>
                            <p>
                                Jeśli uważasz, że problem jest po naszej stronie, skontaktuj się z adminsitratorem PQCMS!
                            </p>
                            Opis: {$websiteSettingsResponse["desc"]}
                        </div>
                        END;
                    else
                        echo<<<END
                        <input type="hidden" name="token" value="{$_SESSION["pqcms-panel-settings-settings-token"]}">

                        <label for="login_count" class="mt-1">Ilość logowań na dobę (devcom: link?)</label>
                        <i>(devcom: minusowe wartości - nielimitowane)</i>
                        <i>(devcom: 0 - zablokowane (oprócz admina))</i>
                        <input type="number" name="login_count" id="login_count" class="my-2 rounded-0" placeholder="czas w sekundach" value="{$websiteSettingsResponse["resp"]["login_attempts"]}" />
    
                        <label>
                            <input type="checkbox" name="login_count_reset" class="my-2 rounded-0" placeholder="czas w sekundach" value="" />
                            Reset
                        </label>
    
                        <label for="login_session_time" class="mt-1">Sesja użytkownika</label>
                        <i>Czas, przez jaki sesja użytkownika będzie ważna na serwerach PQCMS</i>
                        <input type="number" name="login_session_time" id="login_session_time" class="my-2 rounded-0" placeholder="czas w sekundach" value="{$websiteSettingsResponse["resp"]["login_session_time"]}" />
    
                        <label>
                            <input type="checkbox" name="login_session_time_reset" class="my-2 rounded-0"/>
                            Reset
                        </label>
                        
                        <label for="token_lifespan" class="mt-1">Żywotność tokenu CSRF</label>
                        <i>Przez ile czasu token CSRF będzie ważny</i>
                        <input type="number" name="token_lifespan" id="token_lifespan" class="my-2 rounded-0" placeholder="czas w sekundach" value="{$websiteSettingsResponse["resp"]["token_lifespan"]}" />
    
                        <label>
                            <input type="checkbox" name="token_lifespan_reset" class="my-2 rounded-0"/>
                            Reset
                        </label>
    
                        <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Aktualizuj">
END;
                    ?>
                </fieldset>
            </form>
            <div class="col-3 p-4">
                <h4>Aktualizacje</h4>
                <a href="update/">Szukaj aktualizacji</a>;
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