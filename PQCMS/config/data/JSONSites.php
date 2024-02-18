<?php

require_once(dirname(__DIR__)."/JSONObject.php");

class JSONSites extends JSONObject
{
    function __construct()
    {
        parent::__construct("sites","/files/data.json");
    }

    function getSites(): array
    {
        return $this->data;
    }
}