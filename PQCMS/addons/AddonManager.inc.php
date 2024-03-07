<?php

require_once("classes/Addon.inc.php");

class AddonManager
{
    private function getAddonsObject(): JSONAddons
    {
        require_once(dirname(__DIR__)."/config/data/JSONAddons.php");
        return new JSONAddons();
    }

    public function isAddonEnabled(string $id): bool
    {
        $addons = $this->getAddonsObject();
        return in_array($id,$addons->getAddons());
    }

    public function setEnabledAddon(string $id, bool $enabled): void
    {
        $addons = $this->getAddonsObject();
        $addonList = $addons->getAddons();

        if($enabled && !in_array($id,$addonList))
            $addonList[] = $id;
        if(!$enabled)
            $addonList = array_filter($addonList, function($addon) use ($id) {
                return $addon !== $id;
            });
        $addons->setSelf($addonList);
        $addons->saveData();
    }

    public function getAddonById(string $id): ?Addon
    {
        $add = null;
        /** @var Addon $addon */
        foreach($this->getAddonList() as $addon)
        {
            if($addon->getId() === $id)
            {
                $add = $addon;
                break;
            }
        }
        return $add;
    }

    public function getAddonList(): array
    {
        $addonList = [];

        $path = __DIR__."/addons/";

        foreach (scandir($path) as $addon)
        {
            if($addon === "." || $addon === "..")
                continue;
            if(!is_dir($path.$addon))
                continue;

            $addonFilePath = null;
            foreach(scandir($path.$addon) as $addonFolder)
                if($addonFolder === "config")
                {
                    foreach(scandir($path.$addon."/".$addonFolder) as $configFile)
                    {
                        if($configFile === "addon.json")
                        {
                            $addonFilePath = $path.$addon."/".$addonFolder."/".$configFile;
                            break;
                        }
                    }
                }

            if(!$addonFilePath)
                continue;

            try {
                $class = Addon::getAddonMainClassByAddonFile($addonFilePath);
                $addonList[] = $class;

            } catch(Exception $e) {
//                echo "<pre>";
//                echo $e;
//                echo "</pre>";
            }
        }

        return $addonList;
    }

    public function getEnabledAddons(): array
    {
        $addons = $this->getAddonList();
        for($i=0; $i<sizeof($addons); $i++)
        {
            if(!$this->isAddonEnabled($addons[$i]->getId()))
                unset($addons[$i]);
        }
        return $addons;
    }
}