<?php

session_start();
if(empty($_POST["token"]) || $_POST["token"] != $_SESSION["pqcms-panel-settings-settings-token"])
    endScript(false, "Walidacja tokenu nie powiodła się.",null);

if(time() >= $_SESSION["pqcms-panel-settings-settings-token-expire"])
    endScript(false,"Token jest przestarzały. Przeładuj stronę!",null);

require_once("updateData.php");

$response = updateSettings((int) $_POST["login_count"], isset($_POST["login_count_reset"]),
    (int) $_POST["login_session_time"], isset($_POST["login_session_time_reset"]),
    (int) $_POST["token_lifespan"], isset($_POST["token_lifespan_reset"]));

//if(!isset($response["resp"]))
//    endScript(false,$response["desc"]);
endScript($response["suc"],$response["desc"]);

function endScript(bool $suc, string $desc): void
{
    $_SESSION["pqcms-panel-settings-settings-suc"] = $suc;
    $_SESSION["pqcms-panel-settings-settings-desc"] = $desc;
    header("location: ./");
    die($_SESSION["pqcms-panel-settings-settings-desc"]." Błędne przekierowanie.");
}