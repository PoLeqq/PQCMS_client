<?php

/**
 * Reprezentuje panel dodatku
 */
abstract class AddonPanel
{
    public function getWebsite()
    {
        @session_start();
//        if($_SESSION[""])
    }

    protected abstract function getWebsiteHTML();
}