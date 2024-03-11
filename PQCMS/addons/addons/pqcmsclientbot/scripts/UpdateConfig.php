<?php

//todo PQCMSToken
$botName = &$_POST["botname"];
$errorMessage = &$_POST["errormessage"];
$startMessages = &$_POST["startmessages"];

if(!isset($botName) || !isset($errorMessage) || !isset($startMessages))
    die("Niepoprawny formularz!");


require(dirname(__DIR__)."/PQCMSClientBot.inc.php");
$addon = new PQCMSClientBot();
$cfg = $addon->getConfig();
$cfg["bot_name"] = $botName;
$cfg["error_message"] = $errorMessage;
$cfg["start_messages"] = $startMessages;
$addon->setConfig($cfg);

$path = $addon->getRelativePathFromAddonToPanel();
header("location: ../$path");