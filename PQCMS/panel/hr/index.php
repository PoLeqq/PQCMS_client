<?php

// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("hr");

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
        <iframe></iframe>
    </div>
    <!-- <i>Jeżeli chcesz ustawić pole na puste, po prostu nic nie wpisuj.</i> -->
    <div class="d-flex">
        <div class="col-4 main-section">
            <div class="d-flex">
                <div class="col-10 d-flex align-items-center">
                    <h3>
                        Użytkownicy
                    </h3>
                </div>
                <div class="col-2 addImage img-fluid">
                    <img class="overlayLink" data-overlayPath="./overlays/adders/users/" src="images/plus.svg" alt="plus">
                </div>
            </div>
            <i>L_Nazwa - nazwa do loginu</i><br/>
            <i>W_Nazwa - wyświetlana nazwa</i><br/>
            <i>Stan - czy konto jest włączone (można się zalogować)</i><br/>
            <i>Sesja - czy sesja konta jest aktywna (użytkownik jest zalogowany)</i>
            <table class="px-4 my-3 col-12 data-table" id="users-table">
                <thead>
                <tr>
                    <th>L_Nazwa</th>
                    <th>W_Nazwa</th>
                    <th>Stan</th>
                    <th>Sesja</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $usersResponse = Communicator::communicate(CommunicateURL::GET_USER,["admin" => 0]);

                if($usersResponse["suc"] == 1)
                {
                    foreach($usersResponse["resp"] as $user)
                    {
                        if($user["username"] === $_SESSION["pqcms-panel-auth_key"])
                            continue;
                        $enabledClass = $user["disabled"] ? "enabled-no" : "enabled-yes";
                        $enabledText = $user["disabled"] ? "Wył" : "Wł";
                        $sessionClass = isset($user["active_session"]) ? "enabled-yes" : "enabled-no";
                        $sessionText = isset($user["active_session"]) ? "<abbr title=\"Sesja wygasa: ${user["active_session"]}\">Wł</abbr>" : "Wył";

                        $urlencodedPerms = urlencode(json_encode($user["perms"]));
                        echo<<<HTML
<tr class="overlayLink" data-overlayPath="./overlays/editors/users/index.php?username=${user["username"]}&nickname=${user["nickname"]}&disabled=${user["disabled"]}&perms=${urlencodedPerms}">
    <td>${user["username"]}</td>
    <td>${user["nickname"]}</td>
    <td class="${enabledClass}">${enabledText}</td>
    <td class="${sessionClass} user-session-close" data-username="${user["username"]}">${sessionText}</td>
</tr>
HTML;
                    }
                }
                else
                    echo<<<HTML
<span style="color: red">
    Wystąpił błąd podczas pobierania użytkowników: ${$usersResponse["desc"]}
</span>
HTML;
                        }

                        ?>
                    </ul>
                </li>
                <li class="expand">
                    <div class="liManageable">
                        <div class="col-10 d-flex align-items-center">
                            <div class="col-2 ulArrow" rotate="true">
                                <img src="images/arrow.svg" alt="arrow">
                            </div>
                            <div class="ulText">
                                Rangi
                            </div>
                        </div>
                        <div class="col-2 ulPlus">
                            <img class="overlayLink" overlayPath="./overlays/ranks/" src="images/plus.svg">
                        </div>
                    </div>
                    <ul class="px-4 ulHideable">
                    <?php
                    //                        TODO Connector may change this code
                        require_once("../../hr/Rank.php");
//                            foreach(getAllRanksOrder("priority",false) as $rank)
//                                echo "<li>$rank->name</li>";
                    ?>
                    </ul>
                </li>
                <li class="expand">
                    <div class="liManageable">
                        <div class="col-10 d-flex align-items-center">
                            <div class="col-2 ulArrow" rotate="true">
                                <img src="images/arrow.svg">
                            </div>
                            <div class="ulText">
                                Permisje
                            </div>
                        </div>
                        <div class="col-2 ulPlus">
                            <img src="images/plus.svg">
                        </div>
                    </div>
                    <ul class="px-4 ulHideable">
                        <li>Informacje</li>
                        <li>Domyślne</li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="row col-9">
            <div>Tutaj np. jak kliknie na jakąś rangę/usera to coś tam może mu edytować</div>
        </div>
    </div>
</form>
</body>
</html>