<?php

require_once(dirname(__DIR__,2)."/classes/Addon.inc.php");
require_once("PQCMSStarSystemPanel.inc.php");
class PQCMSStarSystem extends Addon
{
    public function __construct()
    {
        parent::__construct("pqcmsstarsystem", new PQCMSStarSystemPanel());
    }
}