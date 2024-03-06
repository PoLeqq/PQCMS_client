<?php

require_once("PQCMSUpdater.php");
//todo zabezpieczenie pliku
$updater = new PQCMSUpdater();
$updater->update();
header("location: ../");
die("Błędne przekierowanie! Możesz opuścić tą stronę.");