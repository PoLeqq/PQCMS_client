<?php

session_start();

if(empty($_POST["username"]) || empty($_POST["nickname"]) || empty($_POST["password"]) || !isset($_POST["rank"]) || !isset($_POST["disabled"]))
    endScript(0,"Uzupełnij wszystkie pola!");

require_once(dirname(__DIR__,4)."/Communicator.inc.php");
$resp = Communicator::communicate(CommunicateURL::ADD_USER,["username" => $_POST["username"], "nickname" => $_POST["nickname"],
    "password" => $_POST["password"], "rank" => $_POST["rank"], "disabled" => $_POST["disabled"]]);

//if($resp["resp"])

function endScript(bool $suc, string $desc): void
{
    $_SESSION["pqcms-panel-hr-add_user-result"] = ["suc" => $suc, "desc" => $desc];
    header("location: success.php");
    die($desc);
}