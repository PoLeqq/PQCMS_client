<?php

require_once("Addon.inc.php");

/**
 * Reprezentuje panel dodatku
 */
abstract class AddonPanel
{
    /**
     * @var Addon dodatek
     */
    protected Addon $addon;

    /**
     * @param Addon $addon dodatek
     */
    public function __construct(Addon $addon)
    {
        $this->addon = $addon;
    }

    public function generateWebsite(): void
    {
        $id = $this->addon->getId();
        $permName = "pqcms.addons.$id";

        require_once(dirname(__DIR__,2)."/panel/scripts/server/TabUtils.inc.php");
        $verify = TabUtils::verifyUser("addons",[$permName]);

        if($verify["perms"][$permName] !== 1)
            die("Nie masz uprawnień!");

        echo $this->getWebsiteHTML();
    }

    protected abstract function getWebsiteHTML(): string;
}