<?php

require_once(dirname(__DIR__)."/JSONObject.php");

class JSONTabs extends JSONObject
{
    function __construct()
    {
        parent::__construct("tabs","/files/data.json");
    }

    function getTabs(): array
    {
        return $this->data;
    }
}