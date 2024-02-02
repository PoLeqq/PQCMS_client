<?php

@session_start();

if(empty($_POST["username"]) || empty($_POST["nickname"]) || !isset($_POST["email"]) || empty($_POST["password"]) || !isset($_POST["disabled"]))
    endScript(0,"Uzupełnij wszystkie pola!",null);

$perms = (empty($_POST["perms"])) ? [] : parsePermsArray($_POST["perms"]);
if(is_null($perms))
    endScript(0,"CLIENT: Podano niepoprawne uprawnienia!!",null);

$email = (empty($_POST["email"])) ? "" : $_POST["email"];
require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
$resp = Communicator::communicate(CommunicateURL::ADD_USER,[
    "username" => $_POST["username"],
    "nickname" => $_POST["nickname"],
    "email" => $email,
    "password" => $_POST["password"],
    "perms" => $perms,
    "disabled" => $_POST["disabled"]
]);
$perms = empty($resp["perms"]) ? [] : $resp["perms"];
endScript($resp["suc"],$resp["desc"],$perms);

function endScript(int $suc, string $desc, ?array $perms): void
{
    if(is_null($perms))
        $perms = json_encode([],JSON_UNESCAPED_UNICODE);
    else
        $perms = json_encode($perms,JSON_UNESCAPED_UNICODE);

    echo<<<JS
<script>
const user = {
    "username": "${_POST['username']}",
    "nickname": "${_POST['nickname']}",
    "email": "${_POST["email"]}",
    "perms": $perms,
    "disabled": ${_POST['disabled']}
}
const response = {
    "suc": $suc,
    "desc": "$desc",
    "close_overlay": 1,
    "iframe_name": "AddUser",
    "user": user
}
parent.postMessage(response,"*");
</script>
JS;

    die($desc);
}

function parsePermsArray($array): ?array
{
    if(!is_array($array))
        return null;
    if(count($array) == 1 && (!key_exists("true",$array) && !key_exists("false",$array)))
        return null;
    if(count($array) == 2 && (!key_exists("true",$array) || !key_exists("false",$array)))
        return null;

    $perms = [];
    if(key_exists("true",$array))
        foreach($array["true"] as $perm => $value)
            if($value !== "on")
                return null;
            else
                $perms[$perm] = true;

    if(key_exists("false",$array))
        foreach($array["false"] as $perm => $value)
            if($value !== "on")
                return null;
            else
                $perms[$perm] = false;

    return $perms;
}