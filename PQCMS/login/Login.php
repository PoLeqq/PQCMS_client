<?php

@session_start();

if(!empty($_SESSION["pqcms"]["panel"]["auth_key"]))
{
    header("location: ../panel");
    die("Sesja jest już aktywna.");
}

// TODO dodać jeszcze tokeny CSRF

if(empty($_POST["username"]) || empty($_POST["password"]))
{
    $_SESSION["pqcms"]["login"]["error"] = "Uzupełnij wszystkie pola!";
    header("location: ../");
    die($_SESSION["pqcms"]["login"]["error"]." Błędne przekierowanie.");
}

require_once(dirname(__DIR__) . "/Communicator.inc.php");
$loginResult = Communicator::communicate(CommunicateURL::LOGIN_USER,["username" => $_POST["username"], "password" => $_POST["password"]]);

if($loginResult["suc"] == 1)
{
    $_SESSION["pqcms"]["panel"]["username"] = $_POST["username"];
    $_SESSION["pqcms"]["panel"]["nickname"] = $loginResult["nickname"];
    $_SESSION["pqcms"]["panel"]["auth_key"] = $loginResult["auth_key"];
    $_SESSION["pqcms"]["panel"]["pqcms_token"] = $loginResult["pqcms_token"];
}
// TODO po zmianie API dodać do błędu ilość pozostałych prób
else
{
    $_SESSION["pqcms"]["login"]["error"] = $loginResult["desc"];
}

header("location: ../");
die($loginResult["desc"]." Błędne przekierowanie.");