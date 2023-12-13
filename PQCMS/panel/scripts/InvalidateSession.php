<?php

session_start();
if(empty($_SESSION["pqcms-panel-auth_key"]))
{
    header("location: ../");
    die("Najpierw się zaloguj. Nieprawidłowe przekierowanie");
}

if((!isset($_GET["outdated"]) && !isset($_GET["invalidated"])))
    die("Niepoprawne dane. Powodem może być niespójność między plikami lub błędne przekierowanie.");

if(!isset($_GET["outdated"]) || !isset($_GET["invalidated"]))
    die("Niepoprawne dane. Powodem może być niespójność między plikami lub błędne przekierowanie.");

session_start();

foreach(array_keys($_SESSION) as $sessionKey)
    if(str_starts_with($sessionKey,"pqcms-"))
        unset($_SESSION[$sessionKey]);

if($_GET["outdated"] == 1) $_SESSION["pqcms-panel-login-error"] = "Twoja sesja została unieważniona przez administratora!";
else $_SESSION["pqcms-panel-login-error"] = "Twoja sesja wygasła!";
//else if(!empty($_GET["invalidated"])) $_SESSION["pqcms-panel-login-error"] = "Twoja sesja wygasła!";

header("location: ../");
echo "Nieprawidłowe przekierowanie.";