<?php

require_once("classes/Addon.inc.php");
class AddonManager
{
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
}