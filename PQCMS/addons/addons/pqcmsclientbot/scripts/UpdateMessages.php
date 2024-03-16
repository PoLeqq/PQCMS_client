<?php

//todo PQCMSToken

$messages = [];
foreach($_POST["messages"] as $message)
{
    $msg = [];

    if(empty($message["id"]) || !is_string($message["id"]))
        continue;
    $msg["id"] = $message["id"];

    if(empty($message["name"]) || !is_string($message["name"]))
        continue;
    $msg["name"] = $message["name"];

    if(!isset($message["bot_resp"]) || !is_array($message["bot_resp"]))
        continue;
    foreach($message["bot_resp"] as $botResp)
    {
        if(!is_string($botResp))
            continue;
        $msg["bot_resp"][] = $botResp;
    }

    if(!isset($message["user_resp"]) || !is_array($message["user_resp"]))
        continue;
    foreach($message["user_resp"] as $userResp)
    {
        if(empty($userResp["text"]) || !is_string($userResp["text"]))
            continue;
        if(empty($userResp["redirect"]) || !is_string($userResp["redirect"]))
            continue;
        if(empty($userResp["user_text"]) || !is_string($userResp["user_text"]))
            continue;

        $userResponse["text"] = $userResp["text"];
        $userResponse["redirect"] = $userResp["redirect"];
        $userResponse["user_text"] = $userResp["user_text"];
        $userResponse["actions"] = [];

        $userRespActions = [];
        if(empty($userResp["actions"]) || !is_array($userResp["actions"]))
        {
            $msg["user_resp"][] = $userResponse;
            continue;
        }

        foreach($userResp["actions"] as $userRespActionUrl)
        {
            if(!is_string($userRespActionUrl))
                continue;
            $userRespAction = urldecode($userRespActionUrl);
            $userRespAction = json_decode($userRespAction,true);
            if(!$userRespAction)
                continue;
            $userResponse["actions"][] = $userRespAction;
        }

        $msg["user_resp"][] = $userResponse;
    }

    $messages[] = $msg;
}


require(dirname(__DIR__)."/PQCMSClientBot.inc.php");
$addon = new PQCMSClientBot();
$cfg = $addon->getConfig();
$cfg["responses"] = $messages;
$addon->setConfig($cfg);

$path = $addon->getRelativePathFromAddonToPanel();

require_once(dirname(__DIR__,4)."/panel/scripts/notifications/NotificationManager.inc.php");
NotificationManager::addNewNotification("pqcmsclientbot-update-config","(Dodatek) Client Bot","s","Pomyślnie zmieniono wiadomości!");

header("location: ../$path");
die();