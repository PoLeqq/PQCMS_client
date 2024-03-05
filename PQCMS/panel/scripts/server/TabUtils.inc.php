<?php

class TabUtils
{
    private static function checkIfLogged(): void
    {
        if(empty($_SESSION["pqcms"]["panel"]["auth_key"]))
        {
            header("location: ../");
            die("Najpierw musisz się zalogować!");
        }
    }

    public static function verifyUser(string $tabName = null, ?array $addPerms = null): ?array
    {
        @session_start();
        TabUtils::checkIfLogged();

        $perms = [];
        if(!is_null($tabName))
            $perms[] = "pqcms.tabs.view.$tabName";

        if(!is_null($addPerms))
            $perms = array_merge($perms,$addPerms);

        if(!is_null($addPerms) || !is_null($tabName))
        {
            require_once(dirname(__DIR__,3)."/Communicator.inc.php");
            $websiteSettingsResponse = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["perms" => $perms]);
            if($websiteSettingsResponse["suc"] == 0)
                die("Wystąpił błąd podczas sprawdzania uprawnień! Ze względów bezpieczeństwa nie masz dostępu do tej strony. 
                Jeżeli problem będzie się powtarzał, skontaktuj się z administratorem PQCMS!");

            if(!is_null($addPerms))
                if(!$websiteSettingsResponse["perms"]["pqcms.tabs.view.$tabName"])
                    die("Nie masz uprawnień, aby przeglądać tą stronę!");

            if(empty($_SESSION["pqcms"]["panel"]["allow_view"]) || !in_array($tabName,$_SESSION["pqcms"]["panel"]["allow_view"]))
                $_SESSION["pqcms"]["panel"]["allow_view"][] = $tabName;
            return $websiteSettingsResponse;
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