<?php
// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("logs");

require_once("LogsHTML.inc.php");
$logsHTML = new LogsHTML();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Logi</title>

    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../default.css">

    <style>
        body {
            min-height: 100vh;
        }

        .log-type-button {
            border: 3px solid #282828;
            border-radius: 10px;
            background-color: rgba(44, 74, 190, 0.8);
            color: white;
            transition: .3s;
        }

        .log-type-button:hover {
            transition: .2s;
            transform: scale(0.95);
            cursor: pointer;
            filter: brightness(90%);
        }
    </style>
</head>
<body>
    <main class="d-flex justify-content-around align-items-center" style="min-height: 100vh">
        <?php
        echo $logsHTML->getLogsButtons();
        ?>

    </main>
    <?php
    echo $logsHTML->getLogsScripts();
    ?>

</body>
</html>