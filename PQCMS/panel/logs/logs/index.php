<?php
// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__,2)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("logs");


?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Logi</title>

    <link rel="stylesheet" href="../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../default.css">
</head>
<body>
    <main class="p-0">

        Wszystkie logi:


    </main>

</body>
</html>