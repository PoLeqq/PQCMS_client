<?php

require_once(dirname(__DIR__,4)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUserChildrenTab("hr");

require_once(dirname(__DIR__,5)."/utils/PQCMSToken.inc.php");
require_once(dirname(__DIR__,4)."/scripts/notifications/NotificationManager.inc.php");
$notificationManager = new NotificationManager("hr-addRank","HR - Dodawanie rangi");
PQCMSToken::verifyToken($notificationManager,$_SESSION["pqcms"]["panel"]["hr"]["ranks"]["add"]["token"],$_POST["token"]);

if(empty($_POST["name"]) || empty($_POST["display_name"]) || !isset($_POST["priority"]))
    endScript(0,"Uzupełnij wszystkie pola!",null);

$perms = (empty($_POST["perms"])) ? [] : parsePermsArray($_POST["perms"]);
if(is_null($perms))
    endScript(0,"CLIENT: Podano niepoprawne uprawnienia!",null);

require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
$resp = Communicator::communicate(CommunicateURL::ADD_RANK,[
    "name" => $_POST["name"],
    "display_name" => $_POST["display_name"],
    "perms" => $perms,
    "priority" => $_POST["priority"]
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
    "iframe_name": "AddRank",
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