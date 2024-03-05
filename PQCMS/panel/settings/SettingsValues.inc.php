<?php

require_once(dirname(__DIR__, 2) . "/config/data/JSONDatabase.php");
require_once(dirname(__DIR__, 2) . "/config/data/JSONPQCMS.php");

class SettingsValues
{
    private ?array $userPerms;
    private JSONDatabase $databaseData;
    private JSONPQCMS $pqcmsData;

    public function __construct()
    {
        require_once(dirname(__DIR__,2)."/Communicator.inc.php");
        $this->userPerms = Communicator::communicate(CommunicateURL::HAS_PERMISSION,
            ["perms" => ["pqcms.tabs.view.settings",
                "pqcms.settings.view.pqcms",
                "pqcms.settings.pqcms.get.username","pqcms.settings.pqcms.get.licensekey",
                "pqcms.settings.pqcms.set.username","pqcms.settings.pqcms.set.licensekey",
                "pqcms.settings.view.database",
                "pqcms.settings.database.get.host","pqcms.settings.database.get.username","pqcms.settings.database.get.password","pqcms.settings.database.get.name",
                "pqcms.settings.database.set.host","pqcms.settings.database.set.username","pqcms.settings.database.set.password","pqcms.settings.database.set.name",
                "pqcms.settings.view.system",
                "pqcms.settings.system.get.loginattempts","pqcms.settings.system.get.loginsessiontime",
                "pqcms.settings.system.set.loginattempts","pqcms.settings.system.set.loginsessiontime",
                "pqcms.settings.systemupdate"
            ]]);
        $this->databaseData = new JSONDatabase();
        $this->pqcmsData = new JSONPQCMS();
    }

    public function areUserPermsValid(): bool
    {
        return $this->userPerms["suc"] == 1;
    }

    public function getUserPerms(): array
    {
        return $this->userPerms;
    }

    public function getDatabaseData(): JSONDatabase
    {
        return $this->databaseData;
    }

    public function getPQCMSData(): JSONPQCMS
    {
        return $this->pqcmsData;
    }

    public function hasPermissionViewForm(string $formName): bool
    {
        if(!$this->areUserPermsValid())
            return false;
        return $this->userPerms["perms"]["pqcms.settings.view.$formName"];
    }
}