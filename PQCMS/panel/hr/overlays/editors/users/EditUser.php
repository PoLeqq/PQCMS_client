<?php

@session_start();

$posts = [];

//if(empty($_POST["username"]) && empty($_POST["nickname"]) && empty($_POST["password"]) && !isset($_POST["disabled"]))
//    endScript(0,"Żadne pole nie jest uzupełnione, więc nie można nic zmienić!");
if(!empty($_POST["username"]))
    $posts["username"] = $_POST["username"];
if(!empty($_POST["nickname"]))
    $posts["nickname"] = $_POST["nickname"];
if(!empty($_POST["password"]))
    $posts["password"] = $_POST["password"];
if(!empty($_POST["email"]))
    $posts["email"] = $_POST["email"];
if(isset($_POST["disabled"]))
    $posts["disabled"] = $_POST["disabled"];

if($posts === [])
    endScript(0,"Żadne pole nie jest uzupełnione, więc nie można nic zmienić!",null);

$perms = (empty($_POST["perms"])) ? [] : parsePermsArray($_POST["perms"]);
if(is_null($perms))
    endScript(0,"Podano niepoprawne uprawnienia!",null);
else
    $posts["perms"] = $perms;

require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
//var_dump($posts);
$resp = Communicator::communicate(CommunicateURL::EDIT_USER,$posts);
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
    "iframe_name": "EditUser",
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