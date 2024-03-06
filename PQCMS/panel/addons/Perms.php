<?php

require_once(dirname(__DIR__)."/TabPerms.php");

class AddonsPerms extends TabPerms
{
    public function __construct(array $addonsNames)
    {
        $permsNames = [];
        foreach ($addonsNames as $addon)
            $permsNames[] = "pqcms.addons.".$addon->getId();

        parent::__construct($permsNames);
    }
}