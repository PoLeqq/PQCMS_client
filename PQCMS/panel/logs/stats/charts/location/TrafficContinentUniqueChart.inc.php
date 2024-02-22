<?php

require_once("TrafficLocationChart.inc.php");

class TrafficContinentUniqueChart extends TrafficLocationChart
{
    public function __construct()
    {
        parent::__construct("Kontynenty","continent",true);
    }
}