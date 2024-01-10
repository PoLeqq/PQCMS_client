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
                ?>
                </tbody>
            </table>
        </div>

        <div class="col-4 main-section">
            <div class="d-flex">
                <div class="col-10 d-flex align-items-center">
                    <h3>
                        Rangi
                    </h3>
                </div>
                <div class="col-2 addImage img-fluid">
                    <img class="overlayLink" data-overlayPath="./overlays/adders/ranks/" src="images/plus.svg" alt="plus">
                </div>
            </div>
            <i>ID - identyfikator</i><br/>
            <i>Nazwa - wyświetlana nazwa</i><br/>
            <i>P - priorytet</i>
            <table class="px-4 my-3 col-12 data-table" id="ranks-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nazwa</th>
                        <th>P</th>
                        <th>Rodzic</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                $ranksResponse = Communicator::communicate(CommunicateURL::GET_RANK);
                if($ranksResponse["suc"] == 1)
                {
                    foreach($ranksResponse["resp"] as $rank)
                    {
                        $parent = empty($rank["parent"]) ? "-" : $rank["parent"];
                        echo<<<HTML
<tr class="overlayLink" data-overlayPath="./overlays/editors/ranks/">
    <td>${rank["name"]}</td>
    <td>${rank["display_name"]}</td>
    <td>${rank["priority"]}</td>
    <td>${parent}</td>
</tr>
HTML;
//    <pre>
//        Permsy: ${perms}
//    </pre>
                    }
                }
                else
                    echo<<<HTML
<span style="color: red">
Wystąpił błąd podczas pobierania rang! Opis: ${$usersResponse["desc"]}
</span>
HTML;

                ?>
                </tbody>
            </table>
        </div>

        <div class="col-4 main-section">
            <div>
                <div class="col-10 d-flex align-items-center">
                    <h3>
                        Permisje
                    </h3>
                </div>
            </div>
            <ul class="px-4">
                <li>Informacje</li>
                <li>Domyślne</li>
            </ul>

            <table class="px-4 my-3 col-12 data-table" id="perms-table">
                <thead>
                <tr>
                    <th>Uprawnienia</th>
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
Wystąpił błąd podczas pobierania rang! Opis: ${$usersResponse["desc"]}
</span>
HTML;

                ?>
                </tbody>
            </table>
        </div>
    </div>
</form>
</body>
</html>