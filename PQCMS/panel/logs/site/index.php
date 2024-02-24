<?php
// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__,2)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("logs");

require_once(dirname(__DIR__,3)."/utils/database/Database.inc.php");
$conn = Database::getConnection();

require_once("LocalLogManager.inc.php");
$logManager = new LocalLogManager($conn,"site-text","Tekst strony",
    [
        "user" => "Użytkownik", "date" => "Data", "old_text" => "Stary tekst", "new_text" => "Nowy tekst"
    ],
    10,10
);

require_once("ExternalLogManager.inc.php");
$sessionLogs = new ExternalLogManager("session","Sesje użytkowników",
    [
        "action" => "Akcja", "date" => "Data", "ip" => "IP" ,"username" => "Użytkownik","logged" => "Zalogowano","admin_logout" => "Admin."
    ],5);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Logi</title>

    <link rel="stylesheet" href="../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../default.css">
    <link rel="stylesheet" href="logs.css"/>
</head>
<body>
    <main class="p-0 col-12 d-flex flex-column justify-content-center align-items-center">
        <div class="row col-12">
            <div class="border border-2 border-secondary border-start-0 border-top-0
                        col-6 p-3">
                <?php
                    echo $logManager->generateHtml();
                ?>

            </div>
            <div class="border border-2 border-secondary border-end-0 border-top-0
                        col-6 p-3">
                <?php
                echo $sessionLogs->generateHtml();
                ?>
<!--                <pre>--><?php //print_r($logs) ?><!--</pre>-->
            </div>
        </div>

        <div class="row col-12">
            <div class="border border-2 border-secondary border-start-0 border-bottom-0
                        col-6 p-3">
                Dział HR:
                <i>
                    Wkrótce
                </i>
            </div>
            <div class="border border-2 border-secondary border-end-0 border-bottom-0
                        col-6 p-3">
                Zmiana ustawień:
                <i>
                    Wkrótce
                </i>
            </div>
        </div>


    </main>

    <script>
    <?php
    echo $sessionLogs->generateJS();
    ?>
    </script>

</body>
</html>