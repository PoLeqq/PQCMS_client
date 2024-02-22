<?php
// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__,2)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUserChildrenTab("logs");

$_SESSION["pqcms"]["panel"]["logs"]["charts"] = true;

require_once("charts/TrafficChart.php");
require_once("charts/TrafficTabsChart.inc.php");

$files = glob("charts/location/" . '/*.php');
foreach ($files as $file)
    require_once $file;

$charts = [];
$charts[] = new TrafficChart();
$charts[] = new TrafficTabsChart();

$continentChart = new TrafficContinentChart();
$countryChart = new TrafficCountryChart();
$cityChart = new TrafficCityChart();

$continentUniqueChart = new TrafficContinentUniqueChart();
$countryUniqueChart = new TrafficCountryUniqueChart();
$cityUniqueChart = new TrafficCityUniqueChart();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Logi</title>

    <link rel="stylesheet" href="../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../default.css">
    <link rel="stylesheet" href="stats.css">
</head>
<body>

    <main class="p-2">
        <?php
        foreach ($charts as $chart)
            echo $chart->generateHTML();

        echo '<div class="row justify-content-center">';
        echo $continentUniqueChart->generateHTML();
        echo $countryUniqueChart->generateHTML();
        echo $cityUniqueChart->generateHTML();
        echo '</div>';

        echo '<div class="row justify-content-center">';
        echo $continentChart->generateHTML();
        echo $countryChart->generateHTML();
        echo $cityChart->generateHTML();
        echo '</div>';

        ?>

    </main>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>

    <?php
    foreach ($charts as $chart)
        echo $chart->generateJS();
    echo $continentChart->generateJS();
    echo $countryChart->generateJS();
    echo $cityChart->generateJS();
    echo $continentUniqueChart->generateJS();
    echo $countryUniqueChart->generateJS();
    echo $cityUniqueChart->generateJS();

    ?>

</body>
</html>