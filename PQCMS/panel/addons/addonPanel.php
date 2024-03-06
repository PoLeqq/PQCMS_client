<?php

require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUserChildrenTab("addons");

$addonID = &$_GET["addon"];
if(is_null($addonID))
    die("Nie podano id dodatku!");

require(dirname(__DIR__,2)."/addons/AddonManager.inc.php");;
$addonManager = new AddonManager();
$addon = $addonManager->getAddonById($addonID);

if(is_null($addon))
    die("Nie znaleziono dodatku o podanym ID!");

$addon->getPanel()->generateWebsite();