<?php

session_start();
if(empty($_SESSION["pqcms-panel-auth_key"]))
{
    header("location: ../");
    die("Najpierw się zaloguj.");
}

//if(!isset($_GET["invalidated"]) || !isset($_GET["outdated"]))
//{
//    header("location: ../");
//    die("Podane dane są nieprawidłowe. Czy używasz przestarzałego skryptu?");
//}

session_start();

foreach(array_keys($_SESSION) as $sessionKey)
    if(str_starts_with($sessionKey,"pqcms-"))
        unset($_SESSION[$sessionKey]);

if(!empty($_GET["invalidated"])) $_SESSION["pqcms-panel-login-error"] = "Twoja sesja została unieważniona przez administratora!";
else if(!empty($_GET["outdated"])) $_SESSION["pqcms-panel-login-error"] = "Twoja sesja wygasła!";

header("location: ../");
die("Nieprawidłowe przekierowanie.");