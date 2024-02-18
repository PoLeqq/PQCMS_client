<?php

require_once(dirname(__DIR__,4)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUserChildrenTab("hr");

require_once(dirname(__DIR__,5)."/utils/PQCMSToken.inc.php");
require_once(dirname(__DIR__,4)."/scripts/notifications/NotificationManager.inc.php");
$notificationManager = new NotificationManager("hr-addRank","HR - Edytowanie rangi");
PQCMSToken::verifyToken($notificationManager,$_SESSION["pqcms"]["panel"]["hr"]["ranks"]["edit"]["token"],$_POST["token"]);

$posts = [];

//if(empty($_POST["username"]) && empty($_POST["nickname"]) && empty($_POST["password"]) && !isset($_POST["disabled"]))
//    endScript(0,"Żadne pole nie jest uzupełnione, więc nie można nic zmienić!");
if(!empty($_POST["name"]))
    $posts["name"] = $_POST["name"];
if(!empty($_POST["display_name"]))
    $posts["display_name"] = $_POST["display_name"];
if(!empty($_POST["priority"]))
    $posts["priority"] = $_POST["priority"];

if($posts === [])
    endScript(0,"Żadne pole nie jest uzupełnione, więc nie można nic zmienić!",null);

$perms = (empty($_POST["perms"])) ? [] : parsePermsArray($_POST["perms"]);
if(is_null($perms))
    endScript(0,"Podano niepoprawne uprawnienia!",null);
else
    $posts["perms"] = $perms;
require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
//var_dump($posts);
$resp = Communicator::communicate(CommunicateURL::EDIT_RANK,$posts);

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
const rank = {
    "name": "${_POST['name']}",
    "display_name": "${_POST['display_name']}",
    "perms": $perms,
    "priority": ${_POST['priority']}
}
const response = {
    "suc": $suc,
    "desc": "$desc",
    "close_overlay": 1,
    "iframe_name": "EditRank",
    "rank": rank
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