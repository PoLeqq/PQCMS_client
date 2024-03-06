<?php

require_once(dirname(__DIR__)."/JSONObject.php");

/**
 * Przedstawia dział "addons" w pqcms/config/data/data.json - dane o dodatkach
 */
class JSONAddons extends JSONObject
{
    function __construct()
    {
        parent::__construct("addons","/files/data.json");
    }

    /**
     * Zwraca listę ID włączonych dodatków
     */
    public function getAddons(): array
    {
        return $this->data;
    }

    public function setSelf(mixed $value): void
    {
        parent::setSelf($value);
    }
}