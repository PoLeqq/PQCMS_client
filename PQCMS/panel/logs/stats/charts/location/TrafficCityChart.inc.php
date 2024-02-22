<?php

require_once("TrafficLocationChart.inc.php");

class TrafficCityChart extends TrafficLocationChart
{
    public function __construct()
    {
        parent::__construct("Miasta","city",false);
    }
}