<?php

session_start();
if(empty($_SESSION["pqcms-panel-auth_key"]))
    die(json_encode(["suc" => 0, "desc" => "Najpierw się zaloguj!"]));

require_once(dirname(__DIR__,3)."/Communicator.inc.php");
require_once(dirname(__DIR__,3)."/website/classes/Text.php");

$_SESSION["pqcms"]["test"] = "test";

$changes = [];
foreach($_POST as $key => $value)
{
    $valid = !is_null(Text::unsafe_getTextByName($key));
    $hasPermission = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["auth_key" => $_SESSION["pqcms-panel-auth_key"]]);

    $changes[$key]["suc"] = ($valid && $hasPermission);

    if(!$valid)
    {
        $changes[$key]["desc"] = "Niepoprawne id tekstu.";
        $_SESSION["pqcms-panel-change_tab_error_id"] = "Niektóre podane dane były nieprawidłowe. Zobacz jakie zmiany
        zostały wprowadzone. Uważasz że to błąd? Skontaktuj się z administratorem PQCMS.";
    }
    else if(!$hasPermission)
    {
        $changes[$key]["desc"] = "Nie masz permisji!";
        $_SESSION["pqcms-panel-change_tab_error_perms"] = "Niektóre pola nie zostały zmienione, ponieważ nie posiadasz odpowiednich uprawnień.";
    }
}

header("location: ./");
echo "Nieprawidłowe przekierowanie.";