<?php

require_once("TrafficLocationChart.inc.php");
require_once(dirname(__DIR__,5)."/utils/logs/CountryList.inc.php");

class TrafficCountryChart extends TrafficLocationChart
{
    public function __construct()
    {
        parent::__construct("Kraje","country",false,CountryList::getPolishList());
    }
}