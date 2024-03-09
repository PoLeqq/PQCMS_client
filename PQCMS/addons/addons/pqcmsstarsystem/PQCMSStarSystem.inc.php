<?php

require_once(dirname(__DIR__,2)."/classes/Addon.inc.php");
require_once("panel/PQCMSStarSystemPanel.inc.php");
require_once("website/PQCMSStarSystemWebsite.php");
class PQCMSStarSystem extends Addon
{
    public const addonID = "pqcmsstarsystem";
    public function __construct()
    {
        parent::__construct(PQCMSStarSystem::addonID, new PQCMSStarSystemPanel($this),new PQCMSStarSystemWebsite($this));
    }
}