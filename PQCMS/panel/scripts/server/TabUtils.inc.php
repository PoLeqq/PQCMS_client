<?php

class TabUtils
{
    public static function verifyUser(string $tabName = null): void
    {
        @session_start();
        if(empty($_SESSION["pqcms-panel-auth_key"]))
        {
            header("location: ../");
            die("Najpierw musisz się zalogować!");
        }

        if(!is_null($tabName))
        {
            require_once(dirname(__DIR__,3)."/Communicator.inc.php");
            $websiteSettingsResponse = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["perms" => ["pqcms.tabs.view.$tabName"]]);
            if(!$websiteSettingsResponse["perms"]["pqcms.tabs.view.$tabName"])
                die("Nie masz uprawnień, aby przeglądać tą stronę!");
        }
    }
}