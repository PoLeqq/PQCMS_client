<?php

require_once("TrafficLocationChart.inc.php");

class TrafficContinentChart extends TrafficLocationChart
{
    public function __construct()
    {
        parent::__construct("Kontynenty","continent",false);
    }
}