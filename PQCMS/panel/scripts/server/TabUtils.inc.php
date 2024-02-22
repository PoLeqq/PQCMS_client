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
            if($websiteSettingsResponse["suc"] == 0)
                die("Wystąpił błąd podczas sprawdzania uprawnień! Ze względów bezpieczeństwa nie masz dostępu do tej strony. Skontaktuj się z administratorem PQCMS!");
            if(!$websiteSettingsResponse["perms"]["pqcms.tabs.view.$tabName"])
                die("Nie masz uprawnień, aby przeglądać tą stronę!");
        }

        return [];
    }

    public static function verifyUserChildrenTab(string $parentTabName): void
    {
        @session_start();
        TabUtils::checkIfLogged();

        $error = "Nie masz uprawnień do tej zakładki. Najpierw otwórz stronę główną, z której możesz tutaj dotrzeć.";
        @session_start();
        if(empty($_SESSION["pqcms"]["panel"]["allow_view"]))
            die($error);

        if(!in_array($parentTabName, $_SESSION["pqcms"]["panel"]["allow_view"]))
            die($error);
    }
}