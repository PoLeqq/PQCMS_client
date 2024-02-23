<?php

// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("hr");

require_once("HRHTML.inc.php");
$hrHTML = new HRHTML();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - HR</title>

    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../default.css">
    <link rel="stylesheet" href="index.css">

    <script src="hr.js" type="module" defer></script>
</head>
<body>
    <div id="overlay">
        <iframe id="overlay_iframe"></iframe>
    </div>
    <div class="d-flex">
        <div class="col-4 main-section">
            <?php
            $hrHTML->getUsers();
            ?>
        </div>

        <div class="col-4 main-section">
            <?php
            $hrHTML->getRanks();
            ?>
        </div>

        <div class="col-4 main-section">
            <div>
                <div class="col-10 d-flex align-items-center">
                    <h3>
                        Permisje
                    </h3>
                </div>
            </div>
            <table class="px-4 my-3 col-12 data-table" id="perms-table">
                <thead>
                <tr>
                    <th>Uprawnienie</th>
                    <th>Opis</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $allPerms = Communicator::communicate(CommunicateURL::GET_PERMS);
                if($allPerms["suc"] == 1)
                {
                    foreach($allPerms["resp"] as $perm)
                    {
                        echo<<<HTML
<tr>
    <td><pre>${perm["perm"]}</pre></td>
    <td>${perm["description"]}</td>
</tr>
HTML;
                    }
                }
                else
                    echo<<<HTML
<span style="color: red">
Wystąpił błąd podczas pobierania listy permisji! Opis: ${allPerms["desc"]}
</span>
HTML;

                ?>
                </tbody>
            </table>
        </div>
    </div>
    <script type="module">
//        window.addEventListener('message', function(event) {
//            <?php
//            require_once(dirname(__DIR__,2)."/config/data/JSONPQCMS.php");
//            $pqcms = new JSONPQCMS();
//            ?>
////            todo odkomentować na prodzie
//            //if (event.origin !== 'http://<?php ////echo $pqcms->getDomain() ?>////' ||
//            //    event.origin !== "https://<?php ////echo $pqcms->getDomain() ?>////") {
//            //    return;
//            //}
//
//
//            let iframeListener = new IframeListener(event.data);
//            iframeListener.addRow();
//        });
    </script>
</body>
</html>