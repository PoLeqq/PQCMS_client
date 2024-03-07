<?php

require_once(dirname(__DIR__,2)."/classes/Addon.inc.php");
require_once("panel/PQCMSClientBotPanel.inc.php");
require_once("website/PQCMSClientBotWebsite.php");
class PQCMSClientBot extends Addon
{
    public const addonID = "pqcmsclientbot";
    public function __construct()
    {
        parent::__construct(PQCMSClientBot::addonID, new PQCMSClientBotPanel($this), new PQCMSClientBotWebsite($this));
    }
}