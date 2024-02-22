<?php

@session_start();
if(empty($_SESSION["pqcms"]["panel"]["auth_key"]))
{
    header("location: ../");
    die("Najpierw się zaloguj. Nieprawidłowe przekierowanie");
}

//if(!isset($_GET["outdated"]) && !isset($_GET["invalidated"]) && !isset($_GET["not_secure"]))
//    die("Niepoprawne dane. Powodem może być niespójność między plikami lub błędne przekierowanie.");

require_once(dirname(__DIR__,2)."/Communicator.inc.php");
Communicator::communicate(CommunicateURL::LOGOUT_USER);

unset($_SESSION["pqcms"]);

if($_GET["not_secure"] == 1)
    $_SESSION["pqcms"]["login"]["error"] = "Twoja sesja została unieważniona, ponieważ przesłane dane nie były bezpieczne! (niepoprawny \"auth_key\")";
else if($_GET["invalidated"] == 1)
    $_SESSION["pqcms"]["login"]["error"] = "Twoja sesja została unieważniona przez administratora!";
else if($_GET["outdated"] == 1)
    $_SESSION["pqcms"]["login"]["error"] = "Twoja sesja wygasła!";
else
    $_SESSION["pqcms"]["login"]["error"] = "Twoja sesja została unieważniona z nieznanego powodu!";

header("location: ../");
echo "Nieprawidłowe przekierowanie.";