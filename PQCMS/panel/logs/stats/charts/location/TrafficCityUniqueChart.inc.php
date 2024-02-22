<?php

require_once("TrafficLocationChart.inc.php");

class TrafficCityUniqueChart extends TrafficLocationChart
{
    public function __construct()
    {
        parent::__construct("Miasta","city",true);
    }
}