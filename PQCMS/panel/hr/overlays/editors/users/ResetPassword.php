<?php

@session_start();

if(empty($_POST["username"]))
    endScript(0,"Nie podano \"username\"!");

require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
$resp = Communicator::communicate(CommunicateURL::RESET_PASSWORD,["username" => $_POST["username"]]);
endScript($resp["suc"],$resp["desc"]);

function endScript(bool $suc, string $desc): void
{
    $_SESSION["pqcms"]["panel"]["hr"]["reset_password-user-result"] = ["suc" => $suc, "desc" => $desc];
    header("location: result.php");
    die($desc);
}